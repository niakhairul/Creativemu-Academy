<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // ==========================
    // Registrasi
    // ==========================
    public function saveRegister()
    {
        if ($this->request->getPost('password') != $this->request->getPost('konfirmasi_password')) {

            return redirect()->back()->with('error', 'Konfirmasi password tidak sesuai.');

        }

        $userModel = new UserModel();

        $data = [

            'nama'           => $this->request->getPost('nama'),
            'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
            'email'          => $this->request->getPost('email'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'           => 'peserta',
            'status'         => 'aktif'

        ];

        $userModel->insert($data);

        return redirect()->to(base_url('pelatihan/login'))
                         ->with('success', 'Registrasi berhasil, silakan login.');
    }


    // ==========================
    // Login
    // ==========================
    public function loginProcess()
{
    $userModel = new UserModel();

    $email = trim($this->request->getPost('email'));
    $password = trim($this->request->getPost('password'));

    $user = $userModel->where('email', $email)->first();

    if (!$user) {
        dd('EMAIL TIDAK DITEMUKAN');
    }

    // dd(
//     $password,
//     $user['password'],
//     password_verify($password, $user['password'])
// );

if (!password_verify($password, $user['password'])) {
    return redirect()->back()->with('error', 'Password salah.');
}

session()->set([
    'id'        => $user['id'],
    'nama'      => $user['nama'],
    'email'     => $user['email'],
    'role'      => $user['role'],
    'logged_in' => true
]);

return redirect()->to(base_url('peserta/dashboard'));
}

    public function logout()
{
    session()->destroy();

    return redirect()->to(base_url('pelatihan/login'))
                     ->with('success', 'Berhasil logout.');
}
}