<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\PendaftaranModel;

class Pelatihan extends BaseController
{
    // ==========================
    // LOGIN
    // ==========================
    public function login()
    {
        return view('auth/login');
    }

    // ==========================
    // REGISTER
    // ==========================
    public function register()
    {
        return view('auth/register');
    }

   // ==========================
// FORM PENDAFTARAN
// ==========================
public function pendaftaran()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $userModel = new UserModel();
    $kelasModel = new KelasModel();

    // Data user yang login
    $user = $userModel->find(session()->get('id'));

    // Ambil id kelas dari URL
    $id = $this->request->getGet('id');

    // Ambil satu data kelas
    $kelas = $kelasModel->find($id);

    if (!$kelas) {
        return redirect()->to(base_url('pelatihan/daftar-kelas'))
                         ->with('error', 'Kelas tidak ditemukan.');
    }

    return view('peserta/pendaftaran', [
        'user'  => $user,
        'kelas' => $kelas
    ]);
}
    // ==========================
    // STATUS PENDAFTARAN
    // ==========================
    public function status()
    {
        return view('peserta/status_pendaftaran');
    }

    // ==========================
    // KELAS SAYA
    // ==========================
    public function kelas()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();

    $kelas = $pendaftaranModel
        ->select('pendaftaran.*, kelas.nama_kelas, kelas.mentor, kelas.metode, kelas.jadwal, kelas.jam')
        ->join('kelas', 'kelas.id = pendaftaran.kelas_id')
        ->where('user_id', session()->get('id'))
        ->first();

    return view('peserta/kelas', [
        'kelas' => $kelas
    ]);
}
    // ==========================
    // DETAIL KELAS
    // ==========================
    public function detailKelas()
    {
        return view('peserta/detail_kelas');
    }

    // ==========================
    // MATERI
    // ==========================
    public function materi()
    {
        return view('peserta/materi');
    }

    // ==========================
    // TUGAS
    // ==========================
    public function tugas()
    {
        return view('peserta/tugas');
    }

    // ==========================
    // DASHBOARD PESERTA
    // ==========================
    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $userModel = new UserModel();
        $kelasModel = new KelasModel();
        $pendaftaranModel = new PendaftaranModel();

        // Data user login
        $user = $userModel->find(session()->get('id'));

        // Semua kelas aktif
        $kelas = $kelasModel
                    ->where('status', 'Aktif')
                    ->findAll();

        // Status pendaftaran user
        $pendaftaran = $pendaftaranModel
                            ->where('user_id', session()->get('id'))
                            ->first();

        $data = [
            'user'         => $user,
            'kelas'        => $kelas,
            'pendaftaran'  => $pendaftaran
        ];

        return view('peserta/dashboard', $data);
    }

    

    // ==========================
    // PROFIL
    // ==========================
    public function profil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $userModel = new UserModel();

        $user = $userModel->find(session()->get('id'));

        return view('peserta/profil', [
            'user' => $user
        ]);
    }

    // ==========================
    // DAFTAR KELAS
    // ==========================
    public function daftarKelas()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $kelasModel = new KelasModel();
    $pendaftaranModel = new PendaftaranModel();

    $kelas = $kelasModel
                ->where('status','Aktif')
                ->findAll();

    foreach($kelas as &$k){

        $terdaftar = $pendaftaranModel
                ->where('kelas_id', $k['id'])
                ->where('status_pendaftaran !=', 'Ditolak')
                ->countAllResults();

        $k['terdaftar'] = $terdaftar;
        $k['sisa'] = $k['kuota'] - $terdaftar;
    }

    return view('peserta/daftar_kelas',[
        'kelas'=>$kelas
    ]);
}

    // ==========================
    // KBM
    // ==========================
    public function kbm()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        return view('peserta/kbm');
    }

    // ==========================
    // PENGATURAN AKUN
    // ==========================
    public function pengaturan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $userModel = new UserModel();

        $user = $userModel->find(session()->get('id'));

        return view('peserta/pengaturan', [
            'user' => $user
        ]);
    }

    // ==========================
// SIMPAN PENDAFTARAN
// ==========================
public function simpanPendaftaran()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();

    // Cek apakah sudah pernah mendaftar
    $cek = $pendaftaranModel
            ->where('user_id', session()->get('id'))
            ->first();

    if ($cek) {
        return redirect()->back()
                ->with('error', 'Anda sudah mendaftar kelas.');
    }

    // Upload bukti pembayaran
    $namaFile = null;

    $file = $this->request->getFile('bukti');

    if ($file && $file->isValid() && !$file->hasMoved()) {

        $namaFile = $file->getRandomName();

        $file->move(
            FCPATH . 'uploads/bukti_pembayaran',
            $namaFile
        );
    }

    // Status pembayaran
    if ($this->request->getPost('pembayaran') == 'Transfer') {

        $statusPembayaran = 'Belum Diverifikasi';

    } else {

        $statusPembayaran = 'Lunas';

    }

    $pendaftaranModel->save([

        'user_id' => session()->get('id'),

        'kelas_id' => $this->request->getPost('kelas_id'),

        'metode_pembelajaran' => $this->request->getPost('metode'),

        'metode_pembayaran' => $this->request->getPost('pembayaran'),

        'bukti_pembayaran' => $namaFile,

        'status_pendaftaran' => 'Menunggu',

        'status_pembayaran' => $statusPembayaran

    ]);

    return redirect()->to(base_url('peserta/dashboard'))
            ->with('success', 'Pendaftaran berhasil, silakan menunggu validasi admin.');
}
}