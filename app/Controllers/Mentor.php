<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalModel;

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


    public function simpanJadwal(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $pertemuan = (int) $this->request->getPost('pertemuan_ke');
        $materi = trim((string) $this->request->getPost('materi'));
        $tanggal = trim((string) $this->request->getPost('tanggal_kbm'));

        if ($pertemuan < 1 || $materi === '' || $tanggal === '') {
            return redirect()->back()->with('error', 'Nomor pertemuan, topik materi, dan jadwal wajib diisi.');
        }

        // Cek ke tabel jadwal_kelas
        $sudahAda = $this->db->table('jadwal_kelas')->where(['id_kelas' => $idKelas, 'pertemuan_ke' => $pertemuan])->countAllResults();
        if ($sudahAda > 0) return redirect()->back()->with('error', "Pertemuan ke-$pertemuan sudah tersedia.");

        $filePdf = $this->request->getFile('file_pdf');
        $namaFilePdf = null;
        if ($filePdf && $filePdf->isValid() && ! $filePdf->hasMoved()) {
            $namaFilePdf = $filePdf->getRandomName();
            $filePdf->move('uploads/materi/', $namaFilePdf);
        }

        // Simpan ke tabel jadwal_kelas
        $this->db->table('jadwal')->insert([
            'id_kelas'          => $idKelas,
            'pertemuan_ke'      => $pertemuan,
            'tanggal_kbm'       => $tanggal,
            'waktu_mulai'       => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'     => $this->request->getPost('waktu_selesai'),
            'materi'            => $materi,
            'ruangan_atau_link' => $this->request->getPost('ruangan_atau_link'),
            'link_materi'       => $this->request->getPost('link_materi'),
            'file_pdf'          => $namaFilePdf,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', "Sesi pertemuan ke-$pertemuan berhasil ditambahkan.");
    }

    public function updateMateri($id_jadwal)
    {
        $db = \Config\Database::connect();
        
      
        $dataUpdate = [
            'materi'      => $this->request->getPost('materi'),
            'link_materi' => $this->request->getPost('link_materi'),
        ];

        $filePdf = $this->request->getFile('file_pdf');
        if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
            $namaFile = $filePdf->getRandomName();
            $filePdf->move('uploads/materi/', $namaFile);
            $dataUpdate['file_pdf'] = $namaFile;
        }

        // Simpan pembaruan materi dan berkas ke tabel 'jadwal'
        $db->table('jadwal')->where('id_jadwal', $id_jadwal)->update($dataUpdate);
        return redirect()->back()->with('success', 'Materi dan berkas PDF berhasil diperbarui.');
    }

    public function absensi(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');

        $idUser = (int) session()->get('id_users');
        $jadwal = $this->db->table('jadwal')
            ->select('jadwal.*, absensi.id_absensi, absensi.status AS status_absen, absensi.waktu_absen')
            ->join('absensi', 'absensi.id_jadwal = jadwal.id_jadwal AND absensi.id_user = ' . $idUser, 'left')
            ->where('jadwal.id_kelas', $idKelas)
            ->orderBy('jadwal.pertemuan_ke', 'ASC')
            ->get()->getResultArray();

        $pesertaAbsensi = $this->db->table('jadwal')
            ->select('jadwal.id_jadwal, jadwal.pertemuan_ke, users.nama AS nama_peserta, users.email, absensi.status, absensi.waktu_absen')
            ->join('pendaftaran', 'pendaftaran.id_kelas = jadwal.id_kelas AND (pendaftaran.status_pembayaran IN ("valid", "Valid", "Disetujui", "approved") OR pendaftaran.status IN ("disetujui", "Disetujui", "approved"))', 'inner')
            ->join('users', 'users.id_users = pendaftaran.id_users', 'inner')
            ->join('absensi', 'absensi.id_jadwal = jadwal.id_jadwal AND absensi.id_user = pendaftaran.id_users', 'left')
            ->where('jadwal.id_kelas', $idKelas)
            ->orderBy('jadwal.pertemuan_ke', 'ASC')
            ->orderBy('users.nama', 'ASC')
            ->get()->getResultArray();

        return view('mentor/absensi', ['kelas' => $kelas, 'jadwal' => $jadwal, 'pesertaAbsensi' => $pesertaAbsensi]);
    }

    public function prosesAbsen()
    {
        $db = \Config\Database::connect();
        $id_jadwal = $this->request->getPost('id_jadwal');
        $tokenInput = trim($this->request->getPost('token_absen'));
        $id_user = session()->get('id_users'); // ID peserta yang sedang login

        // Ambil data jadwal
        $jadwal = $db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();

        // 1. Cek apakah absensi sedang dibuka
        if (!$jadwal || $jadwal['absensi_dibuka'] != 1) {
            return redirect()->back()->with('error', 'Maaf, absensi untuk pertemuan ini belum dibuka oleh mentor.');
        }

        // 2. Cek apakah token sesuai
        if ((string)$jadwal['token_absen'] !== $tokenInput) {
            return redirect()->back()->with('error', 'Token absensi salah! Silakan masukkan token yang valid dari mentor.');
        }

        // 3. Cek apakah peserta sudah pernah absen di jadwal ini
        $sudahAbsen = $db->table('absensi')
                         ->where('id_jadwal', $id_jadwal)
                         ->where('id_user', $id_user)
                         ->countAllResults();

        if ($sudahAbsen > 0) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi untuk pertemuan ini.');
        }

        // 4. Simpan absensi peserta
        $db->table('absensi')->insert([
            'id_jadwal'   => $id_jadwal,
            'id_user'     => $id_user,
            'status'      => 'hadir', // Sesuaikan dengan format status di tabel Anda ('hadir' / 'Hadir')
            'waktu_absen' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Berhasil absen! Kehadiran Anda telah dicatat.');
    }

    public function simpanAbsensi(int $idKelas, int $idJadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $jadwal = $this->db->table('jadwal')->where(['id_jadwal' => $idJadwal, 'id_kelas' => $idKelas])->get()->getRowArray();
        if (! $jadwal) return redirect()->back()->with('error', 'Jadwal mengajar tidak ditemukan.');

        $idUser = (int) session()->get('id_users');
        $absensi = $this->db->table('absensi')->where(['id_jadwal' => $idJadwal, 'id_user' => $idUser])->get()->getRowArray();
        if ($absensi) return redirect()->back()->with('error', 'Anda sudah mengisi absensi pada pertemuan ini.');

        $this->db->table('absensi')->insert(['id_jadwal' => $idJadwal, 'id_user' => $idUser, 'status' => 'hadir', 'waktu_absen' => date('Y-m-d H:i:s')]);
        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', 'Absensi mengajar berhasil dicatat.');
    }

    // Method untuk mentor membuka absensi dan membuat token acak
    // Method untuk mentor membuka absensi dan membuat token acak
    public function bukaAbsensi($id_kelas, $id_jadwal)
    {
        // Muat model jadwal secara dinamis
        $jadwalModel = model('JadwalModel'); // Sesuaikan nama model jika berbeda (misal: \App\Models\JadwalModel)

        // Logika untuk mengubah status absensi dibuka di database
        $jadwalModel->update($id_jadwal, [
            'absensi_dibuka' => 1,
            // Tambahkan token acak atau waktu selesai otomatis di sini jika diperlukan
        ]);

        return redirect()->to(base_url('mentor/kelas/' . $id_kelas . '/absensi'))->with('success', 'Sesi absensi berhasil dibuka.');
    }


    // Method untuk mentor menutup absensi kembali
    public function tutupAbsen(int $idKelas, int $idJadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $data = [
            'absensi_dibuka' => 0,
            'token_absen'    => null,
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $this->db->table('jadwal')->where(['id_jadwal' => $idJadwal, 'id_kelas' => $idKelas])->update($data);

        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))
                         ->with('success', 'Absensi berhasil ditutup.');
    }

    public function generateTimeToken() {
    // Membagi waktu UNIX timestamp saat ini dengan 5 detik
    // Angka ini akan sama selama rentang waktu 5 detik yang sama
    $timeBlock = floor(time() / 5);
    
    // Buat token unik numerik 4 digit menggunakan salt/secret rahasia kelas
    $secretKey = "KunciRahasiaKelasOffline"; 
    $token = substr(abs(crc32($timeBlock . $secretKey)), 0, 4);
    
    return $token;
}

public function getLiveToken($id_jadwal) {
    // Hitung token berdasarkan waktu 5 detik
    $timeBlock = floor(time() / 5);
    $secretKey = "KunciRahasiaKelasOffline" . $id_jadwal; // Unik per jadwal
    $token = substr(abs(crc32($timeBlock . $secretKey)), 0, 4);

    return $this->response->getJSON(['token' => $token]);
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

    // Method baru untuk update Materi, Link GDrive, & File PDF oleh Mentor berdasarkan Jadwal
    

    public function profil()
    {
        if ($r = $this->requireMentor()) return $r;
        return view('mentor/profil', ['mentor' => $this->mentorLogin(), 'user' => $this->db->table('users')->where('id_users', session()->get('id_users'))->get()->getRowArray()]);
    }
}
