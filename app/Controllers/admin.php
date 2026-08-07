<?php

namespace App\Controllers;

use App\Models\PendaftaranModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        return view('admin/dashboard');
    }

    public function pendaftaran()
    {
        $pendaftaranModel = new PendaftaranModel();

        $data['pendaftaran'] = $pendaftaranModel
            ->select('pendaftaran.*, users.nama, kelas.nama_kelas')
            ->join('users', 'users.id = pendaftaran.user_id')
            ->join('kelas', 'kelas.id = pendaftaran.kelas_id')
            ->findAll();

        return view('admin/pendaftaran', $data);
    }
}