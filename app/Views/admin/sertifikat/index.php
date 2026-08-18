<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Sertifikat extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // Tampilan Utama Halaman Sertifikat
    public function index()
    {
        // Mengambil data peserta yang sudah divalidasi beserta status sertifikatnya
        $builder = $this->db->table('pendaftaran p');
        $builder->select('p.id_users, p.kelas_id, u.nama, u.email, k.nama_kelas, s.id as sertifikat_id, s.no_sertifikat, s.tanggal_penerbitan');
        $builder->join('users u', 'u.id = p.id_users');
        $builder->join('kelas k', 'k.id = p.kelas_id');
        $builder->join('sertifikat s', 's.id_users = p.id_users AND s.kelas_id = p.kelas_id', 'left');
        $builder->where('p.status_validasi', 'disetujui');
        
        $data['peserta'] = $builder->get()->getResultArray();

        return view('admin/sertifikat/index', $data);
    }

    // Proses Generasi Nomor Sertifikat
    public function generate()
    {
        $userId  = $this->request->getPost('id_users');
        $kelasId = $this->request->getPost('id_kelas');

        if (!$userId || !$kelasId) {
            return redirect()->back()->with('error', 'Data peserta tidak valid.');
        }

        // Format nomor sertifikat otomatis: SRTF/TAHUN/BULAN/RANDOM_HEX
        $noSertifikat = 'SRTF/' . date('Ym') . '/' . strtoupper(bin2hex(random_bytes(3)));

        $this->db->table('sertifikat')->insert([
            'id_users'            => $userId,
            'id_kelas'           => $kelasId,
            'no_sertifikat'      => $noSertifikat,
            'tanggal_penerbitan' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/sertifikat'))->with('success', 'Sertifikat berhasil diterbitkan dengan nomor: ' . $noSertifikat);
    }

    // Tampilan / Download Cetak Sertifikat
    public function cetak($sertifikatId)
    {
        $sertifikat = $this->db->table('sertifikat s')
            ->select('s.*, u.nama, k.nama_kelas')
            ->join('users u', 'u.id = s.id_user')
            ->join('kelas k', 'k.id = s.id_kelas')
            ->where('s.id', $sertifikatId)
            ->get()->getRowArray();

        if (!$sertifikat) {
            return redirect()->to(base_url('admin/sertifikat'))->with('error', 'Data sertifikat tidak ditemukan.');
        }

        return view('admin/sertifikat/cetak_template', ['sertifikat' => $sertifikat]);
    }
}