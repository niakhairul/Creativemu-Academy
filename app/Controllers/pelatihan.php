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

    public function materi()
    {
    return view('peserta/materi');
    }

    public function tugas()
    {
    return view('peserta/tugas');
    }

    public function dashboard()
{
    if (!session()->get('logged_in')) {

        return redirect()->to(base_url('pelatihan/login'));

    }

    return view('peserta/dashboard');
}
}