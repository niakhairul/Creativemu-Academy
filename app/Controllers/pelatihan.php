<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\AngketModel;
use App\Models\HasilUjianModel;
use App\Models\JadwalKelasModel;
use App\Models\KelasModel;
use App\Models\PendaftaranModel;
use App\Models\PengumpulanTugasModel;
use App\Models\UserModel;

class Pelatihan extends BaseController
{
    public function store()
{
    // 1. Ambil file upload bukti pembayaran
    $fileBukti = $this->request->getFile('bukti_pembayaran');
    $namaFile = null;

    if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
        $namaFile = $fileBukti->getRandomName();
        // Pastikan folder public/uploads/bukti_pembayaran sudah ada
        $fileBukti->move('uploads/bukti_pembayaran', $namaFile);
    }

    // 2. Siapkan data yang dikirim dari form
    $data = [
        'id_users'            => session()->get('id_users'), // Pastikan session login aktif
        'id_kelas'            => $this->request->getPost('id_kelas'),
        'jenis_kelas'         => $this->request->getPost('jenis_kelas'),
        'tanggal_daftar'      => date('Y-m-d'),
        'bukti_pembayaran'    => $namaFile,
        'metode_pembayaran'   => $this->request->getPost('metode_pembayaran'),
        'metode_pembelajaran' => $this->request->getPost('metode_pembelajaran'),
        'jenis_kelamin'       => $this->request->getPost('jenis_kelamin'),
        'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
        'status_pembayaran'   => 'pending', // Default awal pending
    ];

    // 3. Simpan ke database
    $pendaftaranModel = new \App\Models\PendaftaranModel();
    $pendaftaranModel->insert($data);

    return redirect()->to('/user/pendaftaran/sukses')->with('pesan', 'Pendaftaran berhasil dikirim!');
}

    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    private function requireLogin()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        return null;
    }

    private function userId(): ?int
    {
        return session()->get('id_users') ?? session()->get('id');
    }

    private function approvedEnrollment()
    {
        return (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.deskripsi, kelas.tipe_kelas, kelas.lokasi_media, kelas.tanggal_mulai_kelas, kelas.jumlah_pertemuan, kelas.ringkasan, kelas.thumbnail, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->where('pendaftaran.status_pendaftaran', 'Disetujui')
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();
    }

    public function index()
{
    $pendaftaranModel = new \App\Models\PendaftaranModel();
    $userId = session()->get('id_users');

    // Contoh jika menggunakan JOIN ke tabel kelas
    $data['pendaftaran'] = $pendaftaranModel
        ->select('pendaftaran.*, kelas.nama_kelas, kelas.mentor, kelas.jadwal')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->where('pendaftaran.id_users', $userId)
        ->first();

    return view('peserta/dashboard', $data);
}

    public function dashboard()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $userId = $this->userId();
        $user = (new UserModel())->find($userId);
        $kelas = (new KelasModel())->where('status', 'aktif')->findAll();
        
        // Mengambil data dengan join ke tabel kelas dan tabel mentor
        $pendaftaran = (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.tanggal_mulai_kelas as jadwal, mentor.nama_mentor as mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $userId)
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();

        return view('peserta/dashboard', [
            'user' => $user,
            'kelas' => $kelas,
            'pendaftaran' => $pendaftaran,
        ]);
    }

    public function profil()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();

        return view('peserta/profil', [
            'user' => (new UserModel())->find($this->userId()),
            'pendaftaran' => $pendaftaran,
        ]);
    }

    public function editProfil()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/edit_profil', ['user' => (new UserModel())->find($this->userId())]);
    }

    public function updateProfil()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'asal_sekolah' => $this->request->getPost('asal_sekolah'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            if ($foto->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran foto maksimal 2 MB.');
            }

            $ext = strtolower($foto->getClientExtension());
            if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                return redirect()->back()->with('error', 'Format foto harus JPG, JPEG, atau PNG.');
            }

            $folder = FCPATH . 'uploads/profil';
            if (! is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $data['foto'] = $foto->getRandomName();
            $foto->move($folder, $data['foto']);
        }

        (new UserModel())->update($this->userId(), $data);
        session()->set(array_filter($data, static fn ($value) => $value !== null));

        return redirect()->to(base_url('pelatihan/profil'))->with('success', 'Profil berhasil diperbarui.');
    }

    public function daftar()
{
    // 1. Ambil data dari form
    $kelasId = $this->request->getPost('kelas_id');
    
    // Data yang akan disimpan ke tabel pendaftaran/transaksi
    $dataPendaftaran = [
        'kelas_id'          => $kelasId,
        'nama'              => $this->request->getPost('nama'),
        'email'             => $this->request->getPost('email'),
        'no_hp'             => $this->request->getPost('no_hp'),
        'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
        'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
        'jenis_pendaftaran' => $this->request->getPost('jenis_pendaftaran'),
        'pembayaran'        => $this->request->getPost('pembayaran'),
        'status'            => 'Pending' // atau status awal pendaftaran
    ];

    // Handle upload bukti jika ada
    $fileBukti = $this->request->getFile('bukti');
    if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
        $namaFile = $fileBukti->getRandomName();
        $fileBukti->move('uploads/bukti/', $namaFile);
        $dataPendaftaran['bukti'] = $namaFile;
    }

    // 2. Simpan ke tabel pendaftaran
    // (Sesuaikan Model pendaftaran dengan project kamu, misal: $this->pendaftaranModel->insert(...))
    $simpan = $this->db->table('pendaftaran')->insert($dataPendaftaran);

    if ($simpan) {
        // --- 3. LOGIKA UNTUK MENGURANGI KUOTA KELAS ---
        // Asumsikan di tabel kelas ada kolom 'kuota' atau 'sisa_kuota'
        
        // Ambil data kelas saat ini terlebih dahulu
        $kelas = $this->db->table('kelas')->where('id_kelas', $kelasId)->get()->getRowArray();
        
        if ($kelas && isset($kelas['kuota']) && $kelas['kuota'] > 0) {
            $kuotaBaru = $kelas['kuota'] - 1; // Kurangi 1
            
            // Update kuota di database
            $this->db->table('kelas')->where('id_kelas', $kelasId)->update(['kuota' => $kuotaBaru]);
        }
        // ---------------------------------------------

        // Redirect dengan pesan sukses
        return redirect()->to(base_url('pelatihan/sukses'))->with('success', 'Pendaftaran berhasil dikirim!');
    } else {
        // Jika gagal
        return redirect()->back()->with('error', 'Gagal memproses pendaftaran.');
    }
}

    public function pendaftaran()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $idKelas = $this->request->getGet('id');
        $kelas = (new KelasModel())
            ->select('kelas.*, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('kelas.id_kelas', $idKelas)
            ->first();

        if (! $kelas) {
            return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'Kelas tidak ditemukan.');
        }

        return view('peserta/pendaftaran', [
            'kelas' => $kelas,
            'user' => (new UserModel())->find($this->userId()),
        ]);
    }

    public function simpanPendaftaran()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $userId = $this->userId();
        $kelasId = $this->request->getPost('kelas_id');
        $metodePembayaran = $this->request->getPost('pembayaran');

        $pendaftaranModel = new PendaftaranModel();
        $cek = $pendaftaranModel
            ->where('id_users', $userId)
            ->where('id_kelas', $kelasId)
            ->where('status_pendaftaran !=', 'Ditolak')
            ->first();

        if ($cek) {
            return redirect()->to(base_url('peserta/dashboard'))->with('error', 'Anda sudah memiliki pendaftaran untuk kelas ini.');
        }

        $namaFile = null;
        $file = $this->request->getFile('bukti');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            if ($file->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran bukti pembayaran maksimal 2 MB.');
            }

            $folder = FCPATH . 'uploads/bukti_pembayaran';
            if (! is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $namaFile = $file->getRandomName();
            $file->move($folder, $namaFile);
        }

        // TAMBAHKAN PENANGKAPAN DATA DI SINI:
        $pendaftaranModel->insert([
            'id_users'             => $userId,
            'id_kelas'             => $kelasId,
            'jenis_kelas'          => $this->request->getPost('jenis_kelas'),          // <-- Ditambahkan
            'metode_pembelajaran'  => $this->request->getPost('metode'),
            'metode_pembayaran'    => $metodePembayaran,
            'jenis_kelamin'        => $this->request->getPost('jenis_kelamin'),        // <-- Ditambahkan
            'pendidikan_terakhir'  => $this->request->getPost('pendidikan_terakhir'),  // <-- Ditambahkan
            'tanggal_daftar'       => date('Y-m-d H:i:s'),                              // <-- Pastikan tanggal terisi jika ada kolomnya
            'bukti_pembayaran'     => $namaFile,
            'status_pendaftaran'   => 'Menunggu',
            'status_pembayaran'    => $namaFile ? 'Belum Diverifikasi' : 'Menunggu Bukti',
        ]);

        return redirect()->to(base_url('peserta/dashboard'))->with('success', 'Pendaftaran berhasil dikirim. Silakan menunggu validasi admin.');
    }

    public function status()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();

        return view('peserta/status_pendaftaran', ['pendaftaran' => $pendaftaran]);
    }

    public function kelas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.*, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();

        return view('peserta/kelas', ['kelas' => $kelas]);
    }

    public function detailKelas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $idKelas = $this->request->getGet('id');
        $kelas = (new KelasModel())
            ->select('kelas.*, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('kelas.id_kelas', $idKelas)
            ->first();

        return view('peserta/detail_kelas', ['kelas' => $kelas]);
    }

    public function kbm()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (! $kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas Anda belum disetujui admin.');
        }

        return view('peserta/kbm', ['kelas' => $kelas]);
    }

    public function daftarMateri()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/daftar_materi', ['kelas' => $this->approvedEnrollment()]);
    }

    public function materi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/materi', ['kelas' => $this->approvedEnrollment()]);
    }

    public function tugas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (! $kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas Anda belum disetujui admin.');
        }

        $pengumpulan = (new PengumpulanTugasModel())
            ->where('id_users', $this->userId())
            ->first();

        return view('peserta/tugas', ['kelas' => $kelas, 'pengumpulan' => $pengumpulan]);
    }

    public function uploadTugas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $file = $this->request->getFile('tugas');
        if (! $file || ! $file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $folder = FCPATH . 'uploads/tugas';
        if (! is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $namaFile = $file->getRandomName();
        $file->move($folder, $namaFile);

        (new PengumpulanTugasModel())->save([
            'id_tugas' => 1,
            'id_users' => $this->userId(),
            'file_tugas' => $namaFile,
            'status' => 'Belum Dinilai',
        ]);

        return redirect()->to(base_url('pelatihan/tugas'))->with('success', 'Tugas berhasil diupload.');
    }

    public function ujian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/ujian', ['title' => 'Ujian Peserta', 'ujian_selesai' => session()->get('ujian_selesai')]);
    }

    public function kerjakanUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $soal = [
            ['id' => 1, 'pertanyaan' => 'Apa yang dimaksud dengan Digital Marketing?', 'pilihan_a' => 'Pemasaran menggunakan media digital', 'pilihan_b' => 'Pemasaran menggunakan koran saja', 'pilihan_c' => 'Pemasaran secara langsung', 'pilihan_d' => 'Pemasaran tanpa internet'],
            ['id' => 2, 'pertanyaan' => 'Manakah yang termasuk media sosial untuk pemasaran?', 'pilihan_a' => 'Instagram', 'pilihan_b' => 'Kalkulator', 'pilihan_c' => 'Notepad', 'pilihan_d' => 'File Explorer'],
            ['id' => 3, 'pertanyaan' => 'Apa tujuan utama promosi melalui media sosial?', 'pilihan_a' => 'Mengurangi pelanggan', 'pilihan_b' => 'Meningkatkan jangkauan pemasaran', 'pilihan_c' => 'Menghapus produk', 'pilihan_d' => 'Mengurangi informasi produk'],
        ];

        return view('peserta/kerjakan_ujian', ['soal' => $soal]);
    }

    public function submitUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $jawaban = $this->request->getPost('jawaban');
        $kunci = [1 => 'A', 2 => 'A', 3 => 'B'];
        $benar = 0;

        if (is_array($jawaban)) {
            foreach ($kunci as $nomor => $jawabanBenar) {
                if (($jawaban[$nomor] ?? null) === $jawabanBenar) {
                    $benar++;
                }
            }
        }

        $jumlahSoal = count($kunci);
        $nilai = ($benar / $jumlahSoal) * 100;
        $kelas = $this->approvedEnrollment();

        if ($kelas) {
            (new HasilUjianModel())->insert([
                'id_user' => $this->userId(),
                'id_users' => $this->userId(),
                'id_kelas' => $kelas['id_kelas'],
                'benar' => $benar,
                'jumlah_soal' => $jumlahSoal,
                'nilai' => $nilai,
                'status_penilaian' => 'menunggu',
                'status_kelulusan' => $nilai >= 70 ? 'lulus' : 'belum_lulus',
            ]);
        }

        session()->set(['ujian_selesai' => true, 'ujian_benar' => $benar, 'ujian_jumlah_soal' => $jumlahSoal, 'ujian_nilai' => $nilai]);

        return view('peserta/hasil_ujian', ['benar' => $benar, 'jumlahSoal' => $jumlahSoal, 'nilai' => $nilai]);
    }

    public function hasilUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/hasil_ujian', [
            'benar' => session()->get('ujian_benar') ?? 0,
            'jumlahSoal' => session()->get('ujian_jumlah_soal') ?? 0,
            'nilai' => session()->get('ujian_nilai') ?? 0,
        ]);
    }

    public function angket()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        if (! $pendaftaran) {
            return redirect()->to(base_url('peserta/dashboard'))->with('error', 'Kelas Anda belum divalidasi.');
        }

        $sudahIsi = (new AngketModel())
            ->where('id_users', $this->userId())
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->first();

        return view('peserta/angket', ['pendaftaran' => $pendaftaran, 'sudahIsi' => $sudahIsi]);
    }

    public function simpanAngket()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        (new AngketModel())->save([
            'id_users' => $this->userId(),
            'id_kelas' => $this->request->getPost('kelas_id'),
            'materi' => $this->request->getPost('materi'),
            'mentor' => $this->request->getPost('mentor'),
            'penyampaian' => $this->request->getPost('penyampaian'),
            'manfaat' => $this->request->getPost('manfaat'),
            'saran' => $this->request->getPost('saran'),
        ]);

        return redirect()->to(base_url('pelatihan/angket'))->with('success', 'Angket berhasil dikirim.');
    }

    public function sertifikat()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        $hasilUjian = null;
        if ($pendaftaran) {
            $hasilUjian = (new HasilUjianModel())
                ->where('id_kelas', $pendaftaran['id_kelas'])
                ->groupStart()
                    ->where('id_user', $this->userId())
                    ->orWhere('id_users', $this->userId())
                ->groupEnd()
                ->orderBy('id_hasil_ujian', 'DESC')
                ->first();
        }

        $statusLulus = (bool) ($hasilUjian && $hasilUjian['status_kelulusan'] === 'lulus');
        $statusAngket = (bool) ($pendaftaran && (new AngketModel())->where('id_users', $this->userId())->where('id_kelas', $pendaftaran['id_kelas'])->first());

        return view('peserta/sertifikat', [
            'statusLulus' => $statusLulus,
            'statusAngket' => $statusAngket,
            'sertifikatAcademy' => $statusLulus && $statusAngket,
        ]);
    }

    public function absensi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        if (! $pendaftaran) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Anda belum memiliki kelas yang disetujui.');
        }

        $jadwal = (new JadwalKelasModel())->where('id_kelas', $pendaftaran['id_kelas'])->orderBy('pertemuan_ke', 'ASC')->findAll();
        $absensiModel = new AbsensiModel();
        foreach ($jadwal as &$item) {
            $item['absensi'] = $absensiModel->where('id_jadwal_kelas', $item['id_jadwal_kelas'])->where('id_user', $this->userId())->first();
        }

        return view('peserta/absensi', ['jadwal' => $jadwal]);
    }

    public function simpanAbsensi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $idJadwal = $this->request->getPost('id_jadwal_kelas');
        $sudahAbsen = (new AbsensiModel())->where('id_jadwal_kelas', $idJadwal)->where('id_user', $this->userId())->first();
        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi pada pertemuan ini.');
        }

        (new AbsensiModel())->insert([
            'id_jadwal_kelas' => $idJadwal,
            'id_user' => $this->userId(),
            'status' => 'hadir',
            'waktu_absen' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('pelatihan/absensi'))->with('success', 'Absensi berhasil.');
    }

    public function riwayatAbsensi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        $jadwal = [];
        if ($pendaftaran) {
            $jadwal = (new JadwalKelasModel())->where('id_kelas', $pendaftaran['id_kelas'])->orderBy('pertemuan_ke', 'ASC')->findAll();
        }

        $absensiModel = new AbsensiModel();
        $jumlahHadir = $jumlahIzin = $jumlahAlpa = 0;
        foreach ($jadwal as &$item) {
            $item['absensi'] = $absensiModel->where('id_jadwal_kelas', $item['id_jadwal_kelas'])->where('id_user', $this->userId())->first();
            $status = $item['absensi']['status'] ?? null;
            if ($status === 'hadir') $jumlahHadir++;
            if ($status === 'izin') $jumlahIzin++;
            if ($status === 'alpa') $jumlahAlpa++;
        }

        $totalPertemuan = count($jadwal);
        return view('peserta/riwayat_absensi', [
            'jadwal' => $jadwal,
            'totalPertemuan' => $totalPertemuan,
            'jumlahHadir' => $jumlahHadir,
            'jumlahIzin' => $jumlahIzin,
            'jumlahAlpa' => $jumlahAlpa,
            'persentaseKehadiran' => $totalPertemuan > 0 ? round(($jumlahHadir / $totalPertemuan) * 100) : 0,
        ]);
    }

    public function pengaturan()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/pengaturan', ['user' => (new UserModel())->find($this->userId())]);
    }

    public function ubahPassword()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/ubah_password');
    }

    public function updatePassword()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $userModel = new UserModel();
        $user = $userModel->find($this->userId());
        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $konfirmasi = $this->request->getPost('konfirmasi_password');

        if (! password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        if ($passwordBaru !== $konfirmasi) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $userModel->update($this->userId(), ['password' => password_hash($passwordBaru, PASSWORD_DEFAULT)]);

        return redirect()->to(base_url('pelatihan/pengaturan'))->with('success', 'Password berhasil diubah.');
    }
}
