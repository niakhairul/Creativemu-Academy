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

    public function absensi(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');

        $idUser = (int) session()->get('id_users');
        $jadwal = $this->db->table('jadwal_kelas')
            ->select('jadwal_kelas.*, absensi.id_absensi, absensi.status AS status_absen, absensi.waktu_absen')
            ->join('absensi', 'absensi.id_jadwal_kelas = jadwal_kelas.id_jadwal_kelas AND absensi.id_user = ' . $idUser, 'left')
            ->where('jadwal_kelas.id_kelas', $idKelas)
            ->orderBy('jadwal_kelas.pertemuan_ke', 'ASC')
            ->get()->getResultArray();

        $pesertaAbsensi = $this->db->table('jadwal_kelas')
            ->select('jadwal_kelas.id_jadwal_kelas, jadwal_kelas.pertemuan_ke, users.nama AS nama_peserta, users.email, absensi.status, absensi.waktu_absen')
            ->join('pendaftaran', 'pendaftaran.id_kelas = jadwal_kelas.id_kelas AND (pendaftaran.status_pembayaran IN ("valid", "Valid", "Disetujui", "approved") OR pendaftaran.status IN ("disetujui", "Disetujui", "approved"))', 'inner')
            ->join('users', 'users.id_users = pendaftaran.id_users', 'inner')
            ->join('absensi', 'absensi.id_jadwal_kelas = jadwal_kelas.id_jadwal_kelas AND absensi.id_user = pendaftaran.id_users', 'left')
            ->where('jadwal_kelas.id_kelas', $idKelas)
            ->orderBy('jadwal_kelas.pertemuan_ke', 'ASC')
            ->orderBy('users.nama', 'ASC')
            ->get()->getResultArray();

        return view('mentor/absensi', ['kelas' => $kelas, 'jadwal' => $jadwal, 'pesertaAbsensi' => $pesertaAbsensi]);
    }

    public function simpanJadwal(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $pertemuan = (int) $this->request->getPost('pertemuan_ke');
        $materi = trim((string) $this->request->getPost('materi'));
        $keterangan = trim((string) $this->request->getPost('keterangan'));
        $tanggal = trim((string) $this->request->getPost('tanggal_kbm'));
        $judulMateri = trim((string) $this->request->getPost('judul_materi'));
        $urlMateri = trim((string) $this->request->getPost('url_materi'));
        $fileMateri = $this->request->getFile('file_materi');
        if ($pertemuan < 1 || $materi === '' || $tanggal === '') return redirect()->back()->with('error', 'Nomor pertemuan, topik materi, dan jadwal wajib diisi.');
        if (! preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $tanggal)) return redirect()->back()->with('error', 'Format jadwal tidak valid.');
        if ($judulMateri === '' || ((! $fileMateri || ! $fileMateri->isValid()) && $urlMateri === '')) return redirect()->back()->with('error', 'Judul materi serta file atau tautan materi wajib diisi.');
        if ($urlMateri !== '' && ! filter_var($urlMateri, FILTER_VALIDATE_URL)) return redirect()->back()->with('error', 'Tautan materi tidak valid.');

        $sudahAda = $this->db->table('jadwal_kelas')->where(['id_kelas' => $idKelas, 'pertemuan_ke' => $pertemuan])->countAllResults();
        if ($sudahAda > 0) return redirect()->back()->with('error', "Pertemuan ke-$pertemuan sudah tersedia.");

        $this->db->table('jadwal_kelas')->insert([
            'id_kelas' => $idKelas,
            'pertemuan_ke' => $pertemuan,
            'materi' => $materi,
            'tanggal_kbm' => str_replace('T', ' ', $tanggal) . ':00',
            'absensi_dibuka' => 0,
        ]);
        $idJadwal = $this->db->insertID();
        try {
            $namaFile = $this->simpanBerkas($fileMateri);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $fieldNames = $this->db->getFieldNames('materi');
        $dataMateri = ['id_kelas' => $idKelas, 'judul_materi' => $judulMateri, 'file_materi' => $namaFile];
        if (in_array('id_jadwal_kelas', $fieldNames, true)) $dataMateri['id_jadwal_kelas'] = $idJadwal;
        if (in_array('pertemuan_ke', $fieldNames, true)) $dataMateri['pertemuan_ke'] = $pertemuan;
        if (in_array('deskripsi', $fieldNames, true)) $dataMateri['deskripsi'] = $keterangan;
        if (in_array('tipe_materi', $fieldNames, true)) $dataMateri['tipe_materi'] = $this->request->getPost('tipe_materi') ?: 'Dokumen';
        if (in_array('url_materi', $fieldNames, true)) $dataMateri['url_materi'] = $urlMateri ?: null;
        if (in_array('created_at', $fieldNames, true)) $dataMateri['created_at'] = date('Y-m-d H:i:s');
        $this->db->table('materi')->insert($dataMateri);
        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', "Sesi materi pertemuan ke-$pertemuan berhasil ditambahkan.");
    }

    public function simpanAbsensi(int $idKelas, int $idJadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $jadwal = $this->db->table('jadwal_kelas')->where(['id_jadwal_kelas' => $idJadwal, 'id_kelas' => $idKelas])->get()->getRowArray();
        if (! $jadwal) return redirect()->back()->with('error', 'Jadwal mengajar tidak ditemukan.');

        $idUser = (int) session()->get('id_users');
        $absensi = $this->db->table('absensi')->where(['id_jadwal_kelas' => $idJadwal, 'id_user' => $idUser])->get()->getRowArray();
        if ($absensi) return redirect()->back()->with('error', 'Anda sudah mengisi absensi pada pertemuan ini.');

        $this->db->table('absensi')->insert(['id_jadwal_kelas' => $idJadwal, 'id_user' => $idUser, 'status' => 'hadir', 'waktu_absen' => date('Y-m-d H:i:s')]);
        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', 'Absensi mengajar berhasil dicatat.');
    }

    public function bukaAbsensi(int $idKelas, int $idJadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $jadwal = $this->db->table('jadwal_kelas')->where(['id_jadwal_kelas' => $idJadwal, 'id_kelas' => $idKelas])->get()->getRowArray();
        if (! $jadwal) return redirect()->back()->with('error', 'Jadwal mengajar tidak ditemukan.');

        $idUser = (int) session()->get('id_users');
        $mentorAbsen = $this->db->table('absensi')->where(['id_jadwal_kelas' => $idJadwal, 'id_user' => $idUser, 'status' => 'hadir'])->get()->getRowArray();
        if (! $mentorAbsen) return redirect()->back()->with('error', 'Mentor wajib mengisi absensi terlebih dahulu sebelum membuka absensi peserta.');

        $jamMulai = trim((string) $this->request->getPost('jam_mulai_absensi'));
        $jamSelesai = trim((string) $this->request->getPost('jam_selesai_absensi'));
        if (! preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $jamMulai) || ! preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $jamSelesai)) {
            return redirect()->back()->with('error', 'Jam mulai dan jam selesai wajib diisi dengan benar.');
        }

        $tanggal = date('Y-m-d');
        $mulai = "$tanggal $jamMulai:00";
        $selesai = "$tanggal $jamSelesai:00";
        if (strtotime($selesai) <= strtotime($mulai)) {
            return redirect()->back()->with('error', 'Jam selesai harus lebih besar dari jam mulai.');
        }
        $this->db->table('jadwal_kelas')->where('id_jadwal_kelas', $idJadwal)->update(['absensi_dibuka' => 1, 'absensi_mulai' => $mulai, 'absensi_selesai' => $selesai, 'updated_at' => $mulai]);

        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', "Absensi peserta dibuka pukul $jamMulai sampai $jamSelesai.");
    }

    public function tutupAbsensi(int $idKelas, int $idJadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $this->db->table('jadwal_kelas')->where(['id_jadwal_kelas' => $idJadwal, 'id_kelas' => $idKelas])->update(['absensi_dibuka' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', 'Absensi peserta ditutup.');
    }

    public function materi(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        return view('mentor/materi', ['kelas' => $kelas, 'materi' => $this->db->table('materi')->where('id_kelas', $idKelas)->orderBy('id_materi_kelas', 'DESC')->get()->getResultArray()]);
    }

    public function ujianTugas(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        $ujian = $this->db->table('ujian')->where('id_kelas', $idKelas)->orderBy('id_ujian', 'DESC')->get()->getResultArray();
        $tugas = $this->db->tableExists('tugas') ? $this->db->table('tugas')->where('id_kelas', $idKelas)->orderBy('id_tugas', 'DESC')->get()->getResultArray() : [];
        return view('mentor/ujian_tugas', ['kelas' => $kelas, 'ujian' => $ujian, 'tugas' => $tugas]);
    }

    public function simpanUjianTugas(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $jenis = $this->request->getPost('jenis');
        $judul = trim((string) $this->request->getPost('judul'));
        $keterangan = trim((string) $this->request->getPost('keterangan'));
        $deadline = trim((string) $this->request->getPost('deadline'));
        if (! in_array($jenis, ['ujian', 'tugas'], true) || $judul === '') return redirect()->back()->with('error', 'Jenis dan judul wajib diisi.');
        $deadlineDb = $deadline !== '' ? str_replace('T', ' ', $deadline) . ':00' : null;

        if ($jenis === 'ujian') {
            $file = $this->request->getFile('file_soal');
            $namaFile = null;
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $folder = FCPATH . 'uploads/ujian';
                if (! is_dir($folder)) mkdir($folder, 0755, true);
                $namaFile = $file->getRandomName();
                $file->move($folder, $namaFile);
            }
            $this->db->table('ujian')->insert(['id_kelas' => $idKelas, 'judul_ujian' => $judul, 'keterangan' => $keterangan, 'deadline' => $deadlineDb, 'file_soal' => $namaFile, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            if (! $this->db->tableExists('tugas')) return redirect()->back()->with('error', 'Tabel tugas belum tersedia. Jalankan migrasi database terlebih dahulu.');
            $this->db->table('tugas')->insert(['id_kelas' => $idKelas, 'judul_tugas' => $judul, 'deskripsi' => $keterangan, 'deadline' => $deadlineDb, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        }
        return redirect()->to(base_url("mentor/kelas/$idKelas/ujian-tugas"))->with('success', ucfirst($jenis) . ' berhasil ditambahkan ke kelas.');
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
