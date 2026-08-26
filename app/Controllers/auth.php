<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    public function loginProcess()
    {
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        $db = \Config\Database::connect();

        $user = $db->table('users')
            ->where('email', $email)
            ->get()
            ->getRowArray();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'Email tidak ditemukan.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()
                ->with('error', 'Password salah.');
        }

        session()->set([
            'id_users'  => $user['id_users'],
            'id'        => $user['id_users'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'no_hp'     => $user['no_hp'] ?? '',
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to(base_url('admin/dashboard'));
        }

        if ($user['role'] === 'mentor') {
            return redirect()->to(base_url('mentor/dashboard'));
        }

        return redirect()->to(base_url('peserta/dashboard'));
    }

    public function register()
    {
        return view('auth/register'); // Sesuaikan path view register Anda
    }

    public function save()
    {
        return $this->saveRegister();
    }

    public function saveRegister()
{
    // Tangkap data inputan form secara spesifik
    $nama         = $this->request->getPost('nama');
    $jenisKelamin = $this->request->getPost('jenis_kelamin');
    $no_hp        = $this->request->getPost('no_hp');
    $email        = $this->request->getPost('email');
    $password     = $this->request->getPost('password');
    $konfirmasi   = $this->request->getPost('konfirmasi_password');

    // Pengecekan ekstra: Jika field nama kosong sama sekali, kembalikan dengan pesan error
    if (empty($nama)) {
        return redirect()->back()->withInput()->with('error', 'Nama lengkap wajib diisi dan tidak boleh kosong!');
    }

    if ($password !== $konfirmasi) {
        return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak cocok.');
    }

    $db = \Config\Database::connect();
    
    // Cek apakah email sudah terdaftar
    $cekEmail = $db->table('users')->where('email', $email)->get()->getRowArray();
    if ($cekEmail) {
        return redirect()->back()->withInput()->with('error', 'Email sudah terdaftar, silakan gunakan email lain.');
    }

    // Data yang akan disimpan ke database
    $dataSimpan = [
        'nama'          => trim($nama),
        'jenis_kelamin' => $jenisKelamin,
        'no_hp'         => $no_hp,
        'email'         => $email,
        'password'      => password_hash($password, PASSWORD_DEFAULT),
        'role'          => 'peserta'
    ];

    // Proses insert ke tabel users
    $db->table('users')->insert($dataSimpan);

    return redirect()->to(base_url('pelatihan/login'))->with('success', 'Registrasi berhasil! Silakan login.');
}
}