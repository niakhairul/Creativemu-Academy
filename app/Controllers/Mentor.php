<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class Mentor extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Pengecekan sesi manual tanpa filter
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') != 'mentor') {
            header('Location: ' . base_url('login'));
            exit();
        }
        $this->db = \Config\Database::connect();
    }

    // 1. Dashboard Mentor (Total Kelas, Total Peserta, Jadwal Harian)
    public function dashboard()
    {
        $id_mentor = session()->get('id_users');

        // Query contoh untuk mengambil statistik
        $data['total_kelas'] = $this->db->table('kelas')->where('id_mentor', $id_mentor)->countAllResults();
        $data['total_peserta'] = $this->db->table('pendaftaran')->where('status', 'Disetujui')->countAllResults(); // sesuaikan relasi kelas
        $data['jadwal_harian'] = $this->db->table('jadwal')->where('id_mentor', $id_mentor)->where('tanggal', date('Y-m-d'))->get()->getResultArray();

        return view('mentor/dashboard', $data);
    }

    // 2. Daftar Kelas yang Diampu
    public function daftarKelas()
    {
        $id_mentor = session()->get('id_users');
        $data['kelas'] = $this->db->table('kelas')->where('id_mentor', $id_mentor)->get()->getResultArray();

        return view('mentor/daftar_kelas', $data);
    }

    // 3. Detail Kelas (Informasi Kelas, Data Peserta, Kontak, Rekap Absen)
    public function detailKelas($id_kelas)
    {
        $data['kelas'] = $this->db->table('kelas')->where('id_kelas', $id_kelas)->get()->getRowArray();
        $data['peserta'] = $this->db->table('pendaftaran')->where('id_kelas', $id_kelas)->where('status', 'Disetujui')->get()->getResultArray();
        $data['rekap_absen'] = $this->db->table('presensi')->where('id_kelas', $id_kelas)->get()->getResultArray();

        return view('mentor/detail_kelas', $data);
    }

    // 4. KBM (Absensi Mentor, Upload Materi, Soal Tugas)
    public function kbm($id_kelas)
    {
        $data['id_kelas'] = $id_kelas;
        $data['materi'] = $this->db->table('materi')->where('id_kelas', $id_kelas)->get()->getResultArray();
        
        return view('mentor/kbm', $data);
    }

    public function simpanKbm($id_kelas)
{
    $fileMateri = $this->request->getFile('file_materi');
    $namaFileBaru = null;

    // Pastikan file valid dan belum pernah dipindah
    if ($fileMateri && $fileMateri->isValid() && !$fileMateri->hasMoved()) {
        $namaFileBaru = $fileMateri->getRandomName();
        // Simpan fisik file ke folder public/uploads/materi/
        $fileMateri->move(ROOTPATH . 'public/uploads/materi', $namaFileBaru);
    }

    // Simpan data ke database
    $this->db->table('materi')->insert([
        'id_kelas'      => $id_kelas,
        'judul_materi'  => $this->request->getPost('judul_materi'),
        'file_materi'   => $namaFileBaru,
        'created_at'    => date('Y-m-d H:i:s')
    ]);

    return redirect()->to(base_url('mentor/kelas/kbm/' . $id_kelas))->with('pesan', 'Materi berhasil diunggah!');
}

    // 5. Ujian (Input Soal, Nilai, Angket)
    public function ujian($id_kelas)
    {
        $data['id_kelas'] = $id_kelas;
        $data['soal'] = $this->db->table('soal_ujian')->where('id_kelas', $id_kelas)->get()->getResultArray();
        $data['nilai'] = $this->db->table('nilai_ujian')->where('id_kelas', $id_kelas)->get()->getResultArray();
        $data['angket'] = $this->db->table('angket')->where('id_kelas', $id_kelas)->get()->getResultArray();

        return view('mentor/ujian', $data);
    }

    // 6. Profil Mentor
    public function profil()
    {
        $id_mentor = session()->get('id_users');
        $data['profil'] = $this->db->table('users')->where('id_users', $id_mentor)->get()->getRowArray();

        return view('mentor/profil', $data);
    }

}