<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function dashboard()
    {
        $data = [
            'title'            => 'Dashboard Admin',
            'total_kelas'      => 12,
            'total_mentor'     => 5,
            'total_peserta'    => 48,
            'pending_validasi' => 3, // Jumlah pendaftaran yang butuh validasi
        ];

        return view('admin/dashboard', $data);
    }

    public function masterKelas()
    {
        $data = [
            'title' => 'Master Kelas - Panel Admin'
        ];

        return view('admin/master_kelas/index', $data);
    }

    public function dataPeserta()
    {
        $data = [
            'title' => 'Data Peserta - Panel Admin'
        ];

        return view('admin/data_peserta/index', $data);
    }

    public function validasi()
    {
        $data = [
            'title' => 'Validasi Pendaftaran - Panel Admin'
        ];

        return view('admin/validasi/index', $data);
    }

    // Process Validasi (Terima / Tolak)
    public function updateValidasi($id = null, $status = null)
    {
        // Nantinya di sini diproses perubahan status ke database
        // $this->pendaftaranModel->update($id, ['status_pendaftaran' => $status]);

        if ($status == 'Diterima') {
            session()->setFlashdata('success', 'Pendaftaran berhasil disetujui/divalidasi!');
        } else {
            session()->setFlashdata('warning', 'Pendaftaran telah ditolak.');
        }

        return redirect()->to(base_url('admin/validasi'));
    }
}