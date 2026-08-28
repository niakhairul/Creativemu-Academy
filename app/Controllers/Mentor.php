<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\HasilUjianModel;
use App\Models\JadwalKelasModel;
use App\Models\KelasModel;
use App\Models\MentorModel;
use App\Models\PendaftaranModel;
use App\Models\UserModel;

class Mentor extends BaseController
{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
{
    // Do Not Edit This Line
    parent::initController($request, $response, $logger);

    // Cek apakah session session 'logged_in' ada DAN rolenya adalah 'mentor'
    $session = session();
    if (!$session->get('logged_in') || $session->get('role') != 'mentor') {
        // Jika belum login / bukan mentor, arahkan ke halaman login
        header('Location: ' . base_url('pelatihan/login'));
        exit();
    }
}
    private function requireMentor()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        if (session()->get('role') !== 'mentor') {
            return redirect()->to(base_url('/'))->with('error', 'Akses mentor saja.');
        }

        return null;
    }

    private function mentor()
    {
        return (new MentorModel())
            ->groupStart()
                ->where('id_users', session()->get('id_users'))
                ->orWhere('email', session()->get('email'))
            ->groupEnd()
            ->first();
    }

    private function kelasQuery()
    {
        $mentor = $this->mentor();
        return (new KelasModel())->where('id_mentor', $mentor['id_mentor'] ?? 0);
    }

    public function dashboard()
    {
        if ($redirect = $this->requireMentor()) return $redirect;

        $mentor = $this->mentor();
        $kelas = $this->kelasQuery()->findAll();
        $kelasIds = array_column($kelas, 'id_kelas');
        $pesertaCount = 0;
        if ($kelasIds) {
            $pesertaCount = (new PendaftaranModel())->whereIn('id_kelas', $kelasIds)->where('status_pendaftaran', 'Disetujui')->countAllResults();
        }

        return view('mentor/dashboard', [
            'title' => 'Dashboard Mentor',
            'mentor' => $mentor,
            'total_kelas' => count($kelas),
            'total_peserta' => $pesertaCount,
            'jadwal' => (new JadwalKelasModel())->whereIn('id_kelas', $kelasIds ?: [0])->orderBy('tanggal_kbm', 'ASC')->findAll(5),
        ]);
    }

    public function kelas()
    {
        if ($redirect = $this->requireMentor()) return $redirect;
        return view('mentor/kelas', ['title' => 'Daftar Kelas', 'kelas' => $this->kelasQuery()->findAll()]);
    }

    public function detail($idKelas)
    {
        if ($redirect = $this->requireMentor()) return $redirect;

        $kelas = $this->kelasQuery()->where('id_kelas', $idKelas)->first();
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan.');

        $peserta = (new PendaftaranModel())
            ->select('pendaftaran.*, users.nama, users.email, users.no_hp')
            ->join('users', 'users.id_users = pendaftaran.id_users', 'left')
            ->where('pendaftaran.id_kelas', $idKelas)
            ->where('pendaftaran.status_pendaftaran', 'Disetujui')
            ->findAll();

        return view('mentor/detail_kelas', ['title' => 'Detail Kelas', 'kelas' => $kelas, 'peserta' => $peserta]);
    }

    public function kbm($idKelas)
    {
        if ($redirect = $this->requireMentor()) return $redirect;

        $kelas = $this->kelasQuery()->where('id_kelas', $idKelas)->first();
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan.');

        return view('mentor/kbm', [
            'title' => 'KBM Mentor',
            'kelas' => $kelas,
            'jadwal' => (new JadwalKelasModel())->where('id_kelas', $idKelas)->orderBy('pertemuan_ke', 'ASC')->findAll(),
            'hasil' => (new HasilUjianModel())->where('id_kelas', $idKelas)->findAll(),
        ]);
    }

    public function simpanJadwal($idKelas)
    {
        if ($redirect = $this->requireMentor()) return $redirect;

        $materi = $this->request->getPost('materi');
        $file = $this->request->getFile('materi_file');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $folder = FCPATH . 'uploads/materi';
            if (! is_dir($folder)) mkdir($folder, 0777, true);
            $namaFile = $file->getRandomName();
            $file->move($folder, $namaFile);
            $materi = trim($materi . ' | File: ' . $namaFile);
        }

        (new JadwalKelasModel())->insert([
            'id_kelas' => $idKelas,
            'pertemuan_ke' => $this->request->getPost('pertemuan_ke'),
            'materi' => $materi,
            'tanggal_kbm' => $this->request->getPost('tanggal_kbm'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
        ]);

        return redirect()->to(base_url('mentor/kelas/' . $idKelas . '/kbm'))->with('success', 'Jadwal/materi berhasil disimpan.');
    }

    public function simpanNilai($idKelas)
    {
        if ($redirect = $this->requireMentor()) return $redirect;

        $hasilModel = new HasilUjianModel();
        foreach (($this->request->getPost('nilai') ?? []) as $idHasil => $nilai) {
            $hasilModel->update($idHasil, [
                'nilai' => $nilai,
                'status_penilaian' => 'dinilai',
                'status_kelulusan' => ((float) $nilai >= 70) ? 'lulus' : 'belum_lulus',
                'catatan_mentor' => $this->request->getPost('catatan')[$idHasil] ?? null,
            ]);
        }

        return redirect()->to(base_url('mentor/kelas/' . $idKelas . '/kbm'))->with('success', 'Nilai ujian berhasil diperbarui.');
    }

    public function profil()
    {
        if ($redirect = $this->requireMentor()) return $redirect;
        return view('mentor/profil', ['title' => 'Profil Mentor', 'mentor' => $this->mentor(), 'user' => (new UserModel())->find(session()->get('id_users'))]);
    }
}
