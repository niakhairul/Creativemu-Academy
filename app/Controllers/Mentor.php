<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Mentor extends BaseController
{
    protected $db;
    private ?array $mentorLogin = null;

    public function __construct() { $this->db = \Config\Database::connect(); }

    private function requireMentor()
    {
        return (! session()->get('logged_in') || session()->get('role') !== 'mentor')
            ? redirect()->to(base_url('pelatihan/login'))->with('error', 'Silakan masuk sebagai mentor terlebih dahulu.') : null;
    }

    private function mentorLogin(): ?array
    {
        if ($this->mentorLogin !== null) return $this->mentorLogin;
        $userId = (int) session()->get('id_users');
        if ($this->db->fieldExists('id_users', 'mentor')) $this->mentorLogin = $this->db->table('mentor')->where('id_users', $userId)->get()->getRowArray();
        if (! $this->mentorLogin && $this->db->fieldExists('id_user', 'mentor')) $this->mentorLogin = $this->db->table('mentor')->where('id_user', $userId)->get()->getRowArray();
        return $this->mentorLogin;
    }

    private function kelasMilikMentor(int $idKelas): ?array
    {
        $mentor = $this->mentorLogin();
        return $mentor ? $this->db->table('kelas')->where(['id_kelas' => $idKelas, 'id_mentor' => $mentor['id_mentor']])->get()->getRowArray() : null;
    }

    private function pesertaQuery(int $idKelas)
    {
        return $this->db->table('pendaftaran')->select('pendaftaran.*, users.nama AS nama_user, users.email AS email_user, users.no_hp AS no_hp_user')
            ->join('users', 'users.id_users = pendaftaran.id_users', 'left')->where('pendaftaran.id_kelas', $idKelas)
            ->groupStart()->whereIn('pendaftaran.status_pembayaran', ['valid', 'Valid', 'Disetujui', 'approved'])->orWhereIn('pendaftaran.status', ['disetujui', 'Disetujui', 'approved'])->groupEnd();
    }

    public function dashboard()
    {
        if ($r = $this->requireMentor()) return $r;
        $mentor = $this->mentorLogin();
        if (! $mentor) return redirect()->to(base_url('logout'))->with('error', 'Profil mentor tidak ditemukan. Hubungi admin.');
        $kelas = $this->db->table('kelas')->where('id_mentor', $mentor['id_mentor'])->orderBy('tanggal_mulai_kelas', 'ASC')->get()->getResultArray();
        $idKelas = array_column($kelas, 'id_kelas');
        $totalPeserta = $idKelas ? $this->db->table('pendaftaran')->whereIn('id_kelas', $idKelas)->groupStart()->whereIn('status_pembayaran', ['valid', 'Valid', 'Disetujui', 'approved'])->orWhereIn('status', ['disetujui', 'Disetujui', 'approved'])->groupEnd()->countAllResults() : 0;
        $totalMateri = ($idKelas && $this->db->tableExists('materi')) ? $this->db->table('materi')->whereIn('id_kelas', $idKelas)->countAllResults() : 0;
        foreach ($kelas as &$item) {
            $item['jumlah_peserta'] = $this->pesertaQuery((int) $item['id_kelas'])->countAllResults();
            $kapasitas = (int) ($item['kapasitas'] ?? 0);
            $item['persentase_peserta'] = $kapasitas > 0 ? min(100, (int) round(($item['jumlah_peserta'] / $kapasitas) * 100)) : 0;
        }
        unset($item);
        return view('mentor/dashboard', ['total_kelas' => count($kelas), 'total_peserta' => $totalPeserta, 'total_materi' => $totalMateri, 'kelas_terbaru' => array_slice($kelas, 0, 5)]);
    }

    public function kelas()
    {
        if ($r = $this->requireMentor()) return $r;
        $mentor = $this->mentorLogin();
        if (! $mentor) return redirect()->to(base_url('logout'))->with('error', 'Profil mentor tidak ditemukan. Hubungi admin.');
        $kelas = $this->db->table('kelas')->where('id_mentor', $mentor['id_mentor'])->orderBy('tanggal_mulai_kelas', 'DESC')->get()->getResultArray();
        foreach ($kelas as &$item) {
            $item['jumlah_peserta'] = $this->pesertaQuery((int) $item['id_kelas'])->countAllResults();
            $item['jumlah_materi'] = $this->db->tableExists('materi') ? $this->db->table('materi')->where('id_kelas', $item['id_kelas'])->countAllResults() : 0;
            $kapasitas = (int) ($item['kapasitas'] ?? 0);
            $item['persentase_peserta'] = $kapasitas > 0 ? min(100, (int) round(($item['jumlah_peserta'] / $kapasitas) * 100)) : 0;
        }
        unset($item);
        return view('mentor/kelas', ['kelas' => $kelas]);
    }

    public function detail(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        $peserta = $this->pesertaQuery($idKelas)->orderBy('users.nama', 'ASC')->get()->getResultArray();
        $jumlahMateri = $this->db->tableExists('materi') ? $this->db->table('materi')->where('id_kelas', $idKelas)->countAllResults() : 0;
        $kapasitas = (int) ($kelas['kapasitas'] ?? 0);
        return view('mentor/detail_kelas', ['kelas' => $kelas, 'peserta' => $peserta, 'jumlah_materi' => $jumlahMateri, 'persentase_peserta' => $kapasitas > 0 ? min(100, (int) round((count($peserta) / $kapasitas) * 100)) : 0]);
    }

    public function materi(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        return view('mentor/materi', ['kelas' => $kelas, 'materi' => $this->db->table('materi')->where('id_kelas', $idKelas)->orderBy('id_materi_kelas', 'DESC')->get()->getResultArray()]);
    }

    private function simpanBerkas($file, ?string $fileLama = null): ?string
    {
        if (! $file || ! $file->isValid() || $file->hasMoved()) return null;
        if ($file->getSizeByUnit('mb') > 20) throw new \RuntimeException('Ukuran file maksimal 20 MB.');
        $folder = FCPATH . 'uploads/materi'; if (! is_dir($folder)) mkdir($folder, 0755, true);
        $baru = $file->getRandomName(); $file->move($folder, $baru);
        if ($fileLama) { $lama = $folder . DIRECTORY_SEPARATOR . basename($fileLama); if (is_file($lama)) unlink($lama); }
        return $baru;
    }

    public function simpanMateri(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $judul = trim((string) $this->request->getPost('judul_materi')); $url = trim((string) $this->request->getPost('url_materi')); $file = $this->request->getFile('file_materi');
        if ($judul === '' || (! $file->isValid() && $url === '')) return redirect()->back()->withInput()->with('error', 'Judul serta file atau tautan materi wajib diisi.');
        if ($url && ! filter_var($url, FILTER_VALIDATE_URL)) return redirect()->back()->withInput()->with('error', 'Tautan materi tidak valid.');
        try { $namaFile = $this->simpanBerkas($file); } catch (\RuntimeException $e) { return redirect()->back()->withInput()->with('error', $e->getMessage()); }
        $this->db->table('materi')->insert(['id_kelas' => $idKelas, 'judul_materi' => $judul, 'tipe_materi' => $this->request->getPost('tipe_materi') ?: 'Dokumen', 'file_materi' => $namaFile, 'url_materi' => $url ?: null, 'created_at' => date('Y-m-d H:i:s')]);
        return redirect()->to(base_url("mentor/kelas/$idKelas/materi"))->with('success', 'Materi berhasil ditambahkan.');
    }

    public function ubahMateri(int $idKelas, int $idMateri)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $materi = $this->db->table('materi')->where(['id_materi_kelas' => $idMateri, 'id_kelas' => $idKelas])->get()->getRowArray();
        if (! $materi) return redirect()->back()->with('error', 'Materi tidak ditemukan.');
        $judul = trim((string) $this->request->getPost('judul_materi')); $url = trim((string) $this->request->getPost('url_materi'));
        if ($judul === '') return redirect()->back()->withInput()->with('error', 'Judul materi wajib diisi.');
        if ($url && ! filter_var($url, FILTER_VALIDATE_URL)) return redirect()->back()->withInput()->with('error', 'Tautan materi tidak valid.');
        $data = ['judul_materi' => $judul, 'tipe_materi' => $this->request->getPost('tipe_materi') ?: 'Dokumen', 'url_materi' => $url ?: null, 'updated_at' => date('Y-m-d H:i:s')];
        try { $file = $this->simpanBerkas($this->request->getFile('file_materi'), $materi['file_materi'] ?? null); } catch (\RuntimeException $e) { return redirect()->back()->withInput()->with('error', $e->getMessage()); }
        if ($file) $data['file_materi'] = $file;
        $this->db->table('materi')->where('id_materi_kelas', $idMateri)->update($data);
        return redirect()->to(base_url("mentor/kelas/$idKelas/materi"))->with('success', 'Materi berhasil diperbarui.');
    }

    public function hapusMateri(int $idKelas, int $idMateri)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $materi = $this->db->table('materi')->where(['id_materi_kelas' => $idMateri, 'id_kelas' => $idKelas])->get()->getRowArray();
        if (! $materi) return redirect()->back()->with('error', 'Materi tidak ditemukan.');
        $this->db->table('materi')->where('id_materi_kelas', $idMateri)->delete();
        if (! empty($materi['file_materi'])) { $file = FCPATH . 'uploads/materi/' . basename($materi['file_materi']); if (is_file($file)) unlink($file); }
        return redirect()->to(base_url("mentor/kelas/$idKelas/materi"))->with('success', 'Materi berhasil dihapus.');
    }

    public function profil()
    {
        if ($r = $this->requireMentor()) return $r;
        return view('mentor/profil', ['mentor' => $this->mentorLogin(), 'user' => $this->db->table('users')->where('id_users', session()->get('id_users'))->get()->getRowArray()]);
    }
}
