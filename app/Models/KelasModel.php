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
        'lokasi_media',
        'thumbnail'
    ];

    // Mengambil data kelas beserta nama mentor
    // dan menghitung kapasitas yang masih tersedia
    public function getKelasWithMentor()
    {
        $kelas = $this->select('kelas.*, mentor.nama_mentor')
            ->join(
                'mentor',
                'mentor.id_mentor = kelas.id_mentor',
                'left'
            )
            ->findAll();

        $db = \Config\Database::connect();

        foreach ($kelas as &$item) {

            // Hitung jumlah peserta yang sudah disetujui
            $jumlahDisetujui = $db->table('pendaftaran')
    ->where('id_kelas', $item['id_kelas'])
    ->where('status_pembayaran', 'valid')
    ->countAllResults();

            // Hitung kapasitas yang masih tersedia
            $item['kapasitas_tersedia'] =
                max(0, (int) $item['kapasitas'] - $jumlahDisetujui);

            // Jumlah peserta yang sudah disetujui
            $item['jumlah_peserta'] = $jumlahDisetujui;
        }

        unset($item);

        return $kelas;
    }
}