<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table      = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'id_mentor',
        'kategori',
        'nama_kelas',
        'jumlah_pertemuan',
        'deskripsi',
        'kapasitas',
        'tanggal_mulai_kelas',
        'ringkasan',
        'harga_reguler',
        'harga_privat',
        'status',
        'tipe_kelas',
        'lokasi_pelatihan',
        'thumbnail'
    ];

    // Mengambil data kelas beserta nama mentor
    // dan menghitung kapasitas yang masih tersedia
    public function getKelasWithMentor()
    {
        $db = \Config\Database::connect();

        $kelas = $db->table('kelas')
            ->select('kelas.*, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->get()
            ->getResultArray();

        foreach ($kelas as &$item) {
            $jumlahDisetujui = $db->table('pendaftaran')
                ->where('id_kelas', $item['id_kelas'])
                ->where('status_pembayaran', 'valid')
                ->countAllResults();

            $item['kapasitas_tersedia'] =
                max(0, (int) $item['kapasitas'] - $jumlahDisetujui);

            $item['jumlah_peserta'] = $jumlahDisetujui;
        }

        unset($item);

        return $kelas;
    }

    // Method baru khusus detail kelas untuk menghindari sisa cache error sintaks
    public function getDetailKelasFix($id)
    {
        $db = \Config\Database::connect();

        return $db->table('kelas')
            ->select('kelas.*, mentor.nama_mentor, mentor.keahlian, mentor.email AS email_mentor, mentor.telepon AS telepon_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('kelas.id_kelas', $id)
            ->get()
            ->getRowArray();
    }

    // Mengambil data detail satu kelas beserta informasi lengkap mentornya (Menggunakan Array Select)
    // Mengambil data detail satu kelas beserta informasi mentor dengan aman
    public function getKelasByIdWithMentor($id)
    {
        $db = \Config\Database::connect();

        return $db->table('kelas')
            ->select('kelas.*, mentor.nama_mentor, mentor.keahlian, mentor.email AS email_mentor, mentor.telepon AS telepon_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('kelas.id_kelas', $id)
            ->get()
            ->getRowArray();
    }
}