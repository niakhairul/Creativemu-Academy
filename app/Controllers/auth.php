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
        $user = $db->table('users')->where('email', $email)->get()->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah.');
        }

        session()->set([
            'id_users'  => $user['id_users'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to(base_url('admin/dashboard'));
        }

        return redirect()->to(base_url('peserta/dashboard'));
    }
}