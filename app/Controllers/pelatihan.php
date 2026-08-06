<?php

namespace App\Controllers;

class Pelatihan extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function pendaftaran()
    {
    return view('peserta/pendaftaran');
    }

    public function status()
    {
    return view('peserta/status_pendaftaran');
    }

    public function kelas()
    {
    return view('peserta/kelas');
    }

    public function detailKelas()
    {
    return view('peserta/detail_kelas');
}
}