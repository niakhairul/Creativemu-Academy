<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\AngketModel;
use App\Models\AngketPenilaianModel;
use App\Models\HasilUjianModel;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\LokasiPelatihanModel;
use App\Models\PendaftaranModel;
use App\Models\PengumpulanTugasModel;
use App\Models\UserModel;
use App\Models\SertifikatModel;


class Pelatihan extends BaseController
{
    protected $db;

    // TAMBAHKAN KODE INI SUPAYA $this->db OTOMATIS AKTIF DI SEMUA FUNGSI
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->db = \Config\Database::connect();
    }

    public function store() // atau nama fungsi submit pendaftaran kamu
{
    $pendaftaranModel = new PendaftaranModel();

    // NIS dibuat saat admin menyetujui pendaftaran, bukan saat peserta mengisi form.

    // 4. Masukkan data ke database tanpa NIS
    $dataSimpan = [
        'id_kelas'          => $this->request->getPost('id_kelas'),
        'nama'              => $this->request->getPost('nama'),
        'email'             => $this->request->getPost('email'),
        'lokasi_pelatihan' => $this->request->getPost('lokasi_pelatihan'), // Pastikan ini ad
        'no_hp'             => $this->request->getPost('no_hp'),
        'nis'               => null,
        'status_pembayaran' => 'pending',
        // Sesuaikan input form lainnya di bawah ini...
    ];


    $pendaftaranModel->insert($dataSimpan);

    return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('success', 'Pendaftaran berhasil! Silakan menunggu validasi admin.');
}

    public function sukses()
    {
        return view('pendaftaran_sukses');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    protected function requireLogin()
{
    // Pastikan session 'logged_in' atau 'id_user' sesuai dengan saat proses login berhasil
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'))->with('error', 'Sesi habis, silakan login kembali.');
    }
}

    protected function userId()
    {
        return session()->get('id_users') ?? session()->get('id') ?? session()->get('id_user');
    }

    private function approvedEnrollment()
    {
        $userId = $this->userId();
        if (!$userId) {
            return null;
        }

        return (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.deskripsi, kelas.tipe_kelas, kelas.tanggal_mulai_kelas, kelas.jumlah_pertemuan, kelas.ringkasan, kelas.thumbnail, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $userId)
            ->groupStart()
                ->where('pendaftaran.status', 'Disetujui')
                ->orWhere('pendaftaran.status_pembayaran', 'valid')
            ->groupEnd()
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();
    }

    public function index()
{
    $userId = session()->get('id_user'); // atau id_peserta sesuai session kamu

    // Ambil data peserta & kelas yang diikuti
    $pendaftaranModel = new \App\Models\PendaftaranModel();
    $jadwalModel = new \App\Models\JadwalModel();
    $userModel = new \App\Models\UserModel();

    $data['user'] = $userModel->find($userId);
    $data['pendaftaran'] = $pendaftaranModel->where('id_user', $userId)->first();

    // Ambil jadwal pelatihan berdasarkan id_kelas dari pendaftaran peserta
    if (!empty($data['pendaftaran']['id_kelas'])) {
        $data['jadwal_pelatihan'] = $jadwalModel->select('jadwal.*, jadwal.absensi_dibuka')
                                                ->where('id_kelas', $data['pendaftaran']['id_kelas'])
                                                ->orderBy('pertemuan_ke', 'ASC')
                                                ->findAll();
    } else {
        $data['jadwal_pelatihan'] = [];
    }

    return view('peserta/dashboard', $data);
}

    public function dashboard()
{
    $session = session();
    $userId = $session->get('id_users') ?? session()->get('id_user');
    $userEmail = $session->get('email');

    $idKelasDipilih = $this->request->getGet('id_kelas');
  
    $pendaftaranModel = new \App\Models\PendaftaranModel();
    $userModel = new \App\Models\UserModel();
    $jadwalModel = new \App\Models\JadwalModel();

    // 1. Ambil semua data pendaftaran peserta
    $semuaPendaftaran = [];
    if ($userId) {
        $builder = $pendaftaranModel->select('pendaftaran.*, kelas.nama_kelas, kelas.tanggal_mulai_kelas as jadwal_kelas, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $userId);

        $semuaPendaftaran = $builder->orderBy('pendaftaran.id_pendaftaran', 'DESC')->findAll();
    }

    if (empty($semuaPendaftaran) && $userEmail) {
        $builder = $pendaftaranModel->select('pendaftaran.*, kelas.nama_kelas, kelas.tanggal_mulai_kelas as jadwal_kelas, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.email', $userEmail);

        $semuaPendaftaran = $builder->orderBy('pendaftaran.id_pendaftaran', 'DESC')->findAll();
    }

    // 2. Tentukan kelas aktif
    $pendaftaran = null;
    if (!empty($semuaPendaftaran)) {
        if (!empty($idKelasDipilih)) {
            foreach ($semuaPendaftaran as $p) {
                if ($p['id_kelas'] == $idKelasDipilih) {
                    $pendaftaran = $p;
                    break;
                }
            }
        }
        if (!$pendaftaran) {
            $pendaftaran = $semuaPendaftaran[0];
        }
    }

    if ($pendaftaran) {
        $pendaftaran['jadwal'] = $pendaftaran['jadwal_kelas'] ?? $pendaftaran['tanggal_mulai_kelas'] ?? '-';
        $pendaftaran['nama_mentor'] = $pendaftaran['nama_mentor'] ?? 'Mentor Belum Ditentukan';
    }

    // Ambil data user
    $userData = null;
    if ($userId) {
        $userData = $userModel->find($userId);
    }
    if (!$userData && $userEmail) {
        $userData = $userModel->where('email', $userEmail)->first();
    }

    if (!is_array($userData)) {
        $userData = [];
    }
    if (empty($userData['nama'])) {
        $userData['nama'] = $session->get('nama') ?? ($pendaftaran['nama'] ?? null) ?? 'Peserta';
    }
    if (empty($userData['email'])) {
        $userData['email'] = $session->get('email') ?? ($pendaftaran['email'] ?? null) ?? '-';
    }

    $list_jadwal = [];
    if ($pendaftaran && !empty($pendaftaran['id_kelas'])) {
        $list_jadwal = $jadwalModel->select('jadwal.*, jadwal.absensi_dibuka')
                                 ->where('id_kelas', $pendaftaran['id_kelas'])
                                 ->orderBy('pertemuan_ke', 'ASC')
                                 ->findAll();
    }

    // ==========================================
    // 3. LOGIKA CEK STATUS KELULUSAN & NOTIFIKASI
    // ==========================================
    $db = \Config\Database::connect();
    $notifikasiAngket = false;
    $statusUjianKeluar = false;
    $isLulus = false;

    if ($pendaftaran && !empty($pendaftaran['id_kelas'])) {
        $semuaUjian = $db->table('ujian')
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->get()
            ->getResultArray();

        if (!empty($semuaUjian)) {
            $lulusSemua = true;
            $adaNilai = false;
            foreach ($semuaUjian as $u) {
                // Sesuaikan 'id_user' atau 'id_peserta' dengan kolom database Anda
                $cekNilai = $db->table('nilai_ujian')
                    ->where('id_users', $userId)
                    ->where('id_ujian', $u['id_ujian'])
                    ->get()
                    ->getRowArray();

                if ($cekNilai) {
                    $adaNilai = true;
                    $nilaiAkhir = $cekNilai['nilai_remidi'] ?? $cekNilai['nilai_awal'] ?? $cekNilai['nilai'] ?? 0;
                    if ((float)$nilaiAkhir < 70 || strtolower($cekNilai['status_kelulusan'] ?? '') !== 'lulus') {
                        $lulusSemua = false;
                    }
                } else {
                    $lulusSemua = false;
                }
            }

            if ($adaNilai) {
                $statusUjianKeluar = true; // Nilai ujian sudah keluar
                $isLulus = $lulusSemua;    // Status kelulusan akhir

                if ($lulusSemua) {
                    // Cek apakah sudah isi angket
                    $sudahAngket = $db->table('angket_penilaian')
                        ->where('id_peserta', $userId)
                        ->where('id_kelas', $pendaftaran['id_kelas'])
                        ->get()
                        ->getRow();
                    
                    if (!$sudahAngket) {
                        $notifikasiAngket = true; // Belum isi angket, munculkan notifikasi
                    }
                }
            }
        }
    }

    $data = [
        'title'               => 'Dashboard Peserta',
        'pendaftaran'         => $pendaftaran,        
        'semua_pendaftaran'   => $semuaPendaftaran,   
        'user'                => $userData, 
        'list_jadwal'         => $list_jadwal,
        'semua_kelas_peserta' => $semuaPendaftaran,   
        'statusUjianKeluar'   => $statusUjianKeluar, // Sinkronisasi variabel ke view
        'is_lulus'            => $isLulus,           // Sinkronisasi status kelulusan ke view
        'notifikasiAngket'    => $notifikasiAngket, 
    ];

    return view('peserta/dashboard', $data);
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

    $db = \Config\Database::connect();
    $userId = $this->userId();



    // =========================================================
    // 1. AMBIL DATA AKUN PESERTA
    // =========================================================
    $user = $db->table('users')
        ->where('id_users', $userId)
        ->get()
        ->getRowArray();

    // =========================================================
    // 2. AMBIL DATA PENDAFTARAN PESERTA
    // =========================================================
    $pendaftaran = $db->table('pendaftaran')
        ->where('id_users', $userId)
        ->orderBy('id_pendaftaran', 'DESC')
        ->get()
        ->getRowArray();

    // Jika belum ditemukan berdasarkan id_users,
    // cari berdasarkan email akun yang sedang login
    if (!$pendaftaran && !empty($user['email'])) {
        $pendaftaran = $db->table('pendaftaran')
            ->where('email', $user['email'])
            ->orderBy('id_pendaftaran', 'DESC')
            ->get()
            ->getRowArray();
    }

    // Jika data pendaftaran tidak ditemukan
    if (!$pendaftaran) {
        return redirect()->to(base_url('pelatihan/pengaturan'))
            ->with('error', 'Data pendaftaran peserta tidak ditemukan.');
    }

    // =========================================================
    // 3. KIRIM DATA USER DAN PENDAFTARAN KE VIEW
    // =========================================================
    return view('peserta/edit_profil', [
        'user' => $user,
        'pendaftaran' => $pendaftaran
    ]);
}

    

        public function daftar($id_kelas = null)
{
    // Pastikan ID kelas ada
    if (!$id_kelas) {
        return redirect()->back()->with('error', 'ID Kelas tidak ditemukan.');
    }

    $modelKelas = new \App\Models\KelasModel();
    $data['kelas'] = $modelKelas->find($id_kelas);

    // Jika data kelas di database tidak ada
    if (!$data['kelas']) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Kelas tidak ditemukan');
    }

    // Ambil data user yang sedang login (jika ada)
    $userId = session()->get('id_user'); // Sesuaikan dengan session Anda
    $modelUser = new \App\Models\UserModel();
    $data['user'] = $modelUser->find($userId);

    return view('pelatihan/form_daftar', $data);
}
    public function pendaftaran($id_kelas = null)
    {
        $db = \Config\Database::connect();
        $userId = method_exists($this, 'userId') ? $this->userId() : session()->get('id_users');

        // 1. Ambil riwayat kelas yang sudah diambil oleh user ini (agar tidak muncul lagi)
        $takenClassIds = [];
        if ($userId) {
            $takenRows = $db->table('pendaftaran')
                ->select('id_kelas')
                ->where('id_users', $userId)
                ->get()
                ->getResultArray();
            $takenClassIds = array_filter(array_column($takenRows, 'id_kelas'));
        }

        // 2. Ambil seluruh kelas yang berstatus aktif dari API Laravel
        $apiService = new \App\Services\LaravelApiService();
        $availableClasses = [];
        try {
            $response = $apiService->request('GET', 'classes');
            $kelasApi = $response['data'] ?? $response ?? [];
            if (!empty($kelasApi) && is_array($kelasApi)) {
                foreach ($kelasApi as $k) {
                    if (empty($takenClassIds) || !in_array($k['id'], $takenClassIds)) {
                        $availableClasses[] = [
                            'id_kelas' => $k['id'],
                            'nama_kelas' => $k['name'] ?? 'Kelas',
                            'kategori' => $k['training']['name'] ?? $k['category'] ?? 'Umum',
                            'kapasitas' => $k['capacity']['target_students'] ?? $k['capacity'] ?? 0,
                            'harga_reguler' => $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? 0,
                            'harga_privat' => $k['pricing']['price'] ?? $k['private_price'] ?? 0,
                            'nama_mentor' => (!empty($k['trainers']) && isset($k['trainers'][0]['name'])) 
                                              ? $k['trainers'][0]['name'] 
                                              : 'Mentor Belum Ditentukan',
                            'tanggal_mulai_kelas' => $k['schedule']['start_date'] ?? $k['start_date'] ?? '-',
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal memuat kelas dari API: ' . $e->getMessage());
        }

        // Hitung kapasitas tersedia & fallback nama mentor
        foreach ($availableClasses as &$item) {
            $jumlahDisetujui = $db->table('pendaftaran')
                ->where('id_kelas', $item['id_kelas'])
                ->where('status_pembayaran', 'valid')
                ->countAllResults();

            $item['kapasitas_tersedia'] = max(0, (int) ($item['kapasitas'] ?? 0) - $jumlahDisetujui);
            $item['nama_mentor'] = !empty($item['nama_mentor']) ? $item['nama_mentor'] : 'Mentor Creativemu';
        }
        unset($item);

        // Jika tidak ada kelas aktif yang tersedia untuk diambil user ini
        if (empty($availableClasses)) {
            if ($userId) {
                return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                    ->with('error', 'Saat ini tidak ada kelas tambahan yang tersedia untuk didaftarkan atau Anda telah mengikuti semua kelas aktif.');
            } else {
                return redirect()->to(base_url('pelatihan/daftar-kelas'))
                    ->with('error', 'Saat ini belum ada kelas pelatihan aktif yang dibuka.');
            }
        }

        // Tentukan kelas yang terpilih (default atau dari ID URL)
        $selectedKelas = null;
        if ($id_kelas !== null) {
            // Cek apakah $id_kelas termasuk kelas yang sudah didaftarkan peserta
            if ($userId && in_array((int)$id_kelas, array_map('intval', $takenClassIds))) {
                return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                    ->with('error', 'Anda sudah terdaftar di kelas tersebut. Silakan pilih kelas lainnya.');
            }

            foreach ($availableClasses as $ac) {
                if ((int)$ac['id_kelas'] === (int)$id_kelas) {
                    $selectedKelas = $ac;
                    break;
                }
            }

            if (!$selectedKelas) {
                // Jika ID kelas tidak ditemukan di kelas yang aktif/tersedia
                return redirect()->to(base_url('pelatihan/pendaftaran'))
                    ->with('error', 'Kelas yang Anda pilih tidak tersedia atau kuota telah penuh. Silakan pilih kelas dari daftar.');
            }
        } else {
            // Default pilih kelas pertama dari kelas yang tersedia
            $selectedKelas = $availableClasses[0];
        }

        // 3. Ambil data akun dan riwayat profil peserta login secara dinamis
        $userData = [
            'is_logged_in'        => false,
            'nama'                => '',
            'email'               => '',
            'no_hp'               => '',
            'alamat'              => '',
            'ttl'                 => '',
            'jenis_kelamin'       => '',
            'pendidikan_terakhir' => '',
            'asal_instansi'       => '',
            'semester'            => '',
            'status'              => '',
            'status_locked'       => false,
        ];

        if ($userId) {
            $userAccount = $db->table('users')->where('id_users', $userId)->get()->getRowArray() ?? [];
            $lastRegistration = $db->table('pendaftaran')
                ->where('id_users', $userId)
                ->orderBy('id_pendaftaran', 'DESC')
                ->get()
                ->getRowArray() ?? [];

            // Status profesi: cari dari record pendaftaran yang valid
            $statusDB = $lastRegistration['status'] ?? '';
            if (in_array(strtolower($statusDB), ['pending', 'disetujui', 'ditolak', 'menunggu'])) {
                $statusDB = $lastRegistration['pilihan_status'] ?? '';
            }

            $userData['is_logged_in']        = true;
            $userData['nama']                = $userAccount['nama'] ?? $lastRegistration['nama'] ?? '';
            $userData['email']               = $userAccount['email'] ?? $lastRegistration['email'] ?? '';
            $userData['no_hp']               = $userAccount['no_hp'] ?? $lastRegistration['no_hp'] ?? '';
            $userData['alamat']              = $lastRegistration['alamat'] ?? '';
            $userData['ttl']                 = $lastRegistration['ttl'] ?? '';
            $userData['jenis_kelamin']       = $userAccount['jenis_kelamin'] ?? $lastRegistration['jenis_kelamin'] ?? '';
            $userData['pendidikan_terakhir'] = $lastRegistration['pendidikan_terakhir'] ?? '';
            $userData['asal_instansi']       = $lastRegistration['asal_instansi'] ?? '';
            $userData['semester']            = $lastRegistration['semester'] ?? '';
            $userData['status']              = $statusDB;
            $userData['status_locked']       = !empty($statusDB);
        }

        $data = [
            'title'          => 'Formulir Pendaftaran Pelatihan - Creativemu Academy',
            'kelas'          => $selectedKelas,
            'kelasList'      => $availableClasses,
            'user'           => $userData,
            'isStatusLocked' => $userData['status_locked'],
            'lokasiPelatihan' => $db->table('lokasi_pelatihan')
                ->where('is_online !=', 1)
                ->notLike('nama_lokasi', 'Online')
                ->notLike('nama_lokasi', 'Surakarta')
                ->orderBy('id_lokasi', 'ASC')
                ->get()
                ->getResultArray(),
        ];

        return view('peserta/pendaftaran', $data);
    }

    public function simpanPendaftaran()
    {
        $db = \Config\Database::connect();
        $pendaftaranModel = new \App\Models\PendaftaranModel();

        // =========================================================
        // 1. CEK ID KELAS & CEK DUPLIKASI PENDAFTARAN
        // =========================================================
        $idKelas = $this->request->getPost('id_kelas');
        if (!$idKelas) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Silakan pilih kelas pelatihan terlebih dahulu.');
        }

        $userId = method_exists($this, 'userId') ? $this->userId() : session()->get('id_users');
        if ($userId) {
            $sudahAda = $db->table('pendaftaran')
                ->where('id_users', $userId)
                ->where('id_kelas', $idKelas)
                ->countAllResults();

            if ($sudahAda > 0) {
                return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                    ->with('error', 'Anda sudah terdaftar di kelas ini. Tidak dapat mendaftarkan kelas yang sama dua kali.');
            }
        }

        // Ambil data kelas dari database
        $kelas = $db->table('kelas')
            ->where('id_kelas', $idKelas)
            ->get()
            ->getRowArray();

        if (!$kelas) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data kelas pelatihan tidak ditemukan.');
        }

        // Cek kapasitas kelas yang tersedia
        $jumlahDisetujui = $db->table('pendaftaran')
            ->where('id_kelas', $idKelas)
            ->where('status_pembayaran', 'valid')
            ->countAllResults();

        $kapasitasTersedia = max(0, (int) ($kelas['kapasitas'] ?? 0) - $jumlahDisetujui);
        if ($kapasitasTersedia <= 0 && ($kelas['kapasitas'] ?? 0) > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Maaf, kapasitas kelas "' . $kelas['nama_kelas'] . '" sudah penuh.');
        }

        // =========================================================
        // 2. AMBIL & SANITASI INPUT FORM
        // =========================================================
        $nama               = trim((string) $this->request->getPost('nama'));
        $email              = trim((string) $this->request->getPost('email'));
        $noHp               = trim((string) $this->request->getPost('no_hp'));
        $alamat             = trim((string) $this->request->getPost('alamat'));
        $ttl                = trim((string) $this->request->getPost('ttl'));
        $jenisKelamin       = trim((string) $this->request->getPost('jenis_kelamin'));
        $pendidikanTerakhir = trim((string) $this->request->getPost('pendidikan_terakhir'));
        $asalInstansi       = trim((string) $this->request->getPost('asal_instansi'));
        $semester           = trim((string) $this->request->getPost('semester'));
        $metodePembayaran   = trim((string) $this->request->getPost('metode_pembayaran'));
        $metodePembelajaran = strtolower(trim((string) $this->request->getPost('metode_pembelajaran')));
        $jenisKelas         = trim((string) $this->request->getPost('jenis_kelas')) ?: 'Reguler';
        $kategoriKelas      = trim((string) $this->request->getPost('kategori_kelas')) ?: ($kelas['kategori'] ?? 'Basic Pelatihan');
        $sumberInformasi    = trim((string) $this->request->getPost('sumber_informasi')) ?: 'Website CreativeMU';

        // Jika user login, ambil data pendukung dari database jika form kosong
        if ($userId) {
            $userAccount = $db->table('users')->where('id_users', $userId)->get()->getRowArray() ?? [];
            $lastRegistration = $db->table('pendaftaran')
                ->where('id_users', $userId)
                ->orderBy('id_pendaftaran', 'DESC')
                ->get()
                ->getRowArray() ?? [];

            $nama               = $nama !== '' ? $nama : trim((string) ($userAccount['nama'] ?? $lastRegistration['nama'] ?? ''));
            $email              = $email !== '' ? $email : trim((string) ($userAccount['email'] ?? $lastRegistration['email'] ?? ''));
            $noHp               = $noHp !== '' ? $noHp : trim((string) ($userAccount['no_hp'] ?? $lastRegistration['no_hp'] ?? ''));
            $alamat             = $alamat !== '' ? $alamat : trim((string) ($lastRegistration['alamat'] ?? '-'));
            $ttl                = $ttl !== '' ? $ttl : trim((string) ($lastRegistration['ttl'] ?? '-'));
            $jenisKelamin       = $jenisKelamin !== '' ? $jenisKelamin : trim((string) ($userAccount['jenis_kelamin'] ?? $lastRegistration['jenis_kelamin'] ?? 'Laki-laki'));
            $pendidikanTerakhir = $pendidikanTerakhir !== '' ? $pendidikanTerakhir : trim((string) ($lastRegistration['pendidikan_terakhir'] ?? '-'));
            $asalInstansi = trim((string) ($lastRegistration['asal_instansi'] ?? $asalInstansi));
            $semester     = trim((string) ($lastRegistration['semester'] ?? $semester));
        }


        // Validasi field utama yang wajib diisi (Longgar agar tidak mudah mental)
        if (empty($nama) || empty($email) || empty($noHp) || empty($metodePembayaran)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon lengkapi kolom utama (Nama, Email, No HP, Metode Pembayaran) yang bertanda bintang (*).');
        }

        // Status Peserta
        $statusPeserta = $this->request->getPost('pilihan_status') ?: $this->request->getPost('status');
        if (empty($statusPeserta)) {
            $statusPeserta = 'Umum';
        }

        // Lokasi Pelatihan Offline / Online
        $lokasiPelatihan = ($metodePembelajaran === 'offline') ? trim((string) $this->request->getPost('pilihan_lokasi')) : 'Online / Daring';
        if ($metodePembelajaran === 'offline' && empty($lokasiPelatihan)) {
            $lokasiPelatihan = 'Kantor Utama Creativemu';
        }

        // =========================================================
        // 3. UPLOAD PAS FOTO (OPSIONAL)
        // =========================================================
        $namaFoto = null;
        $fileFoto = $this->request->getFile('pas_foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $ext = strtolower($fileFoto->getClientExtension());
            if (in_array($ext, ['jpg', 'jpeg', 'png']) && $fileFoto->getSize() <= 2 * 1024 * 1024) {
                $folderFoto = 'uploads/foto/';
                if (!is_dir(FCPATH . $folderFoto)) {
                    mkdir(FCPATH . $folderFoto, 0777, true);
                }
                $namaFoto = $fileFoto->getRandomName();
                $fileFoto->move(FCPATH . $folderFoto, $namaFoto);
            }
        }

        // =========================================================
        // 4. UPLOAD FILE BUKTI PEMBAYARAN
        // =========================================================
        $namaBukti = null;
        $fileBukti = $this->request->getFile('bukti_pembayaran');
        if (strtoupper($metodePembayaran) === 'TRANSFER') {
            if (!$fileBukti || !$fileBukti->isValid() || $fileBukti->hasMoved()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bukti transaksi transfer bank wajib diunggah.');
            }
            $extBukti = strtolower($fileBukti->getClientExtension());
            if (!in_array($extBukti, ['jpg', 'jpeg', 'png', 'pdf'])) {
                return redirect()->back()->withInput()->with('error', 'Format bukti transfer harus JPG, JPEG, PNG, atau PDF.');
            }
            if ($fileBukti->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file bukti transfer maksimal 2 MB.');
            }

            $folderBukti = 'uploads/bukti/';
            if (!is_dir(FCPATH . $folderBukti)) {
                mkdir(FCPATH . $folderBukti, 0777, true);
            }
            $namaBukti = $fileBukti->getRandomName();
            $fileBukti->move(FCPATH . $folderBukti, $namaBukti);
        }

        // =========================================================
        // 5. SIMPAN DATA PENDAFTARAN KE DATABASE
        // =========================================================
        $dataPendaftaran = [
            'nis'                 => null,
            'id_users'            => $userId,
            'id_kelas'            => $idKelas,
            'nama'                => $nama,
            'email'               => $email,
            'no_hp'               => $noHp,
            'alamat'              => $alamat,
            'ttl'                 => $ttl,
            'jenis_kelamin'       => $jenisKelamin,
            'pendidikan_terakhir' => $pendidikanTerakhir,
            'asal_instansi'       => $asalInstansi,
            'semester'            => $semester,
            'pas_foto'            => $namaFoto,
            'status'              => $statusPeserta,
            'status_pendaftaran'  => 'Menunggu',
            'lokasi_pelatihan'    => $lokasiPelatihan,
            'pilihan_pelatihan'   => $kelas['nama_kelas'],
            'jenis_kelas'         => $jenisKelas,
            'metode_pembelajaran' => $metodePembelajaran,
            'pilihan_kelas'       => $kelas['nama_kelas'],
            'kategori_kelas'      => $kategoriKelas,
            'tanggal_mulai_kelas' => $kelas['tanggal_mulai_kelas'] ?? date('Y-m-d'),
            'metode_pembayaran'   => $metodePembayaran,
            'bukti_pembayaran'    => $namaBukti,
            'status_pembayaran'   => 'pending',
            'alasan_penolakan'    => null,
            'persetujuan_syarat'  => $this->request->getPost('persetujuan_syarat') ? 1 : 0,
        ];

        if ($db->fieldExists('sumber_informasi', 'pendaftaran')) {
            $dataPendaftaran['sumber_informasi'] = $sumberInformasi;
        }

        try {
            if ($pendaftaranModel->insert($dataPendaftaran)) {
                if ($userId) {
                    return redirect()
                        ->to(base_url('pelatihan/daftar-kelas-peserta'))
                        ->with('success', 'Pendaftaran kelas "' . $kelas['nama_kelas'] . '" berhasil dikirim! Menunggu validasi admin.');
                } else {
                    return redirect()
                        ->to(base_url('pelatihan/daftar-kelas'))
                        ->with('success', 'Pendaftaran berhasil dikirim! Silakan menunggu konfirmasi dari admin.');
                }
            } else {
                $errors = $pendaftaranModel->errors();
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal memproses pendaftaran ke database: ' . json_encode($errors));
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

public function setujuiPendaftaran($id_pendaftaran)
{
    $pendaftaranModel = new \App\Models\PendaftaranModel();
    $db = \Config\Database::connect();

    $db->transBegin();
    try {
        $pendaftaran = $db->query(
            'SELECT * FROM pendaftaran WHERE id_pendaftaran = ? FOR UPDATE',
            [(int) $id_pendaftaran]
        )->getRowArray();

        if (!$pendaftaran) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $existingUser = $db->table('users')->where('email', $pendaftaran['email'])->get()->getRowArray();
        if ($existingUser) {
            $userId = $existingUser['id_users'];
        } else {
            $db->table('users')->insert([
                'nama'          => $pendaftaran['nama'],
                'email'         => $pendaftaran['email'],
                'no_hp'         => $pendaftaran['no_hp'],
                'jenis_kelamin' => $pendaftaran['jenis_kelamin'],
                'password'      => password_hash('123456', PASSWORD_DEFAULT),
                'role'          => 'peserta',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
            $userId = $db->insertID();
        }

        $nisBaru = $pendaftaran['nis'] ?: (new \App\Models\BukuIndukModel())->generateNis(date('ym', strtotime($pendaftaran['created_at'] ?? 'now')));

        $pendaftaranModel->update($id_pendaftaran, [
            'id_users'            => $userId,
            'status_pembayaran'   => 'valid',
            'status_pendaftaran'  => 'Disetujui',
            'status'              => 'Disetujui',
            'nis'                 => $nisBaru,
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Pendaftaran gagal disetujui.');
        }

        $db->transCommit();
        return redirect()->back()->with('success', 'Pendaftaran disetujui, akun peserta aktif, dan NIS berhasil dibuat: ' . $nisBaru);
    } catch (\Throwable $e) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Pendaftaran gagal disetujui: ' . $e->getMessage());
    }
}

    public function status()
{
    $keyword = $this->request->getGet('keyword') ?? $this->request->getPost('keyword');
    $pendaftaran = null;

    if ($keyword) {
        $pendaftaran = (new \App\Models\PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->groupStart()
                ->where('pendaftaran.email', $keyword)
                ->orWhere('pendaftaran.no_hp', $keyword)
            ->groupEnd()
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();
    }



   return redirect()->to(base_url('admin/validasi'))->with('success', 'Pendaftaran berhasil disetujui!');
}

    public function updateBukti($id)
{
    // Ambil data pendaftaran berdasarkan ID
    $pendaftaranModel = new PendaftaranModel();
    $pendaftaran = $pendaftaranModel->find($id);

    if (!$pendaftaran) {
        return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
    }

    // Ambil file bukti pembayaran yang baru diunggah
    $fileBukti = $this->request->getFile('bukti_pembayaran');

    if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
        // Hapus file bukti lama jika ada
        if (!empty($pendaftaran['bukti_pembayaran']) && file_exists(FCPATH . 'uploads/pembayaran/' . $pendaftaran['bukti_pembayaran'])) {
            unlink(FCPATH . 'uploads/pembayaran/' . $pendaftaran['bukti_pembayaran']);
        }

        // PERBAIKAN DI SINI: Menambahkan tanda dolar ($) pada namaBaru
        $namaBaru = $fileBukti->getRandomName();
        $fileBukti->move(FCPATH . 'uploads/pembayaran', $namaBaru);

        // Update data di database: masukkan file baru, ubah status jadi 'pending' / 'menunggu verifikasi', dan kosongkan alasan penolakan sebelumnya
        $pendaftaranModel->update($id, [
    'bukti_pembayaran' => $namaBaru,
    'status_pembayaran' => 'pending', // Perbaiki ke status_pembayaran
    'alasan_penolakan' => null
]);

        return redirect()->to(base_url('pelatihan/status?keyword=' . $pendaftaran['email']))
                         ->with('success', 'Bukti pembayaran berhasil dikirim ulang! Silakan tunggu verifikasi admin.');
    }

    return redirect()->back()->with('error', 'Gagal mengunggah file. Pastikan format dan ukuran file sudah sesuai.');
}

    public function ajax_cek_status()
{
    $keyword = $this->request->getGet('keyword');

    $pendaftaranModel = new PendaftaranModel();

    $pendaftaran = $pendaftaranModel->select('pendaftaran.*, kelas.nama_kelas') // Pastikan pendaftaran.* ada di sini
                                    ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
                                    ->groupStart()
                                    ->like('email', $keyword)
                                    ->orLike('no_hp', $keyword)
                                    ->groupEnd()
                                    ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
                                    ->first();

    header('Content-Type: application/json');

    if ($pendaftaran) {
        echo json_encode([
            'status' => 'success',
            'data' => $pendaftaran
        ]);
        exit;
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data pendaftaran dengan email atau nomor HP tersebut tidak ditemukan.'
        ]);
        exit;
    }
}

    public function uploadUlang($id_pendaftaran)
    {
        $pendaftaranModel = new PendaftaranModel();
        $data['pendaftaran'] = $pendaftaranModel->find($id_pendaftaran);

        if (!$data['pendaftaran']) {
            return redirect()->to('/pelatihan/daftar-kelas')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Ubah dari 'pelatihan/upload_ulang' menjadi 'upload_ulang' saja
        return view('peserta/upload_ulang', $data);
    }

    public function prosesUploadUlang($id_pendaftaran)
{
    $pendaftaranModel = new PendaftaranModel();

    // Pastikan ini murni menggunakan id_pendaftaran
    $pendaftaran = $pendaftaranModel->where('id_pendaftaran', $id_pendaftaran)->first();

    if (!$pendaftaran) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // Sisa kode proses upload...
}

    public function generateNIS()
{
    $tahunBulanTanggal = date('Ymd'); // Contoh: 20260902

    // Cari data terakhir hari ini berdasarkan awalan NIS (misal: 20260902...)
    $builder = $this->db->table('tabel_siswa'); // Ganti dengan nama tabel Anda
    $builder->select('nis');
    $builder->like('nis', $tahunBulanTanggal, 'after');
    $builder->orderBy('nis', 'DESC');
    $builder->limit(1);
    $query = $builder->get()->getRow();

    if ($query) {
        // Jika hari ini sudah ada pendaftaran, ambil 3 digit terakhir lalu +1
        $nisTerakhir = $query->nis;
        $noUrut = (int) substr($nisTerakhir, -3);
        $noUrut++;
    } else {
        // Jika hari ini belum ada pendaftaran sama sekali, mulai dari 1
        $noUrut = 1;
    }

    // Format nomor urut menjadi 3 digit (contoh: 001, 002, 003)
    $formattedUrut = str_pad($noUrut, 3, '0', STR_PAD_LEFT);

    // Gabungkan menjadi NIS akhir: YYYYMMDD + 003
    return $tahunBulanTanggal . $formattedUrut;
}


    public function daftarKelasPeserta()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $pendaftaranModel = new PendaftaranModel();

    // Ambil data kelas yang diambil oleh peserta berdasarkan id_users yang sedang login dengan JOIN lengkap
    $kelasSaya = $pendaftaranModel
        ->select('pendaftaran.*, kelas.nama_kelas, kelas.thumbnail, mentor.nama_mentor')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('pendaftaran.id_users', $this->userId())
        ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
        ->findAll();


    $data['kelas'] = $kelasSaya;

    return view('peserta/daftar_kelas_peserta', $data);
}

    public function tambahKelas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        // Langsung arahkan peserta ke form pendaftaran kelas
        return $this->pendaftaran();
    }

    public function daftarKelas()
    {
        $apiService = new \App\Services\LaravelApiService();
        try {
            $response = $apiService->request('GET', 'classes');
            $data['kelas'] = [];
            
            $kelasApi = $response['data'] ?? $response ?? [];
            if (!empty($kelasApi) && is_array($kelasApi)) {
                foreach ($kelasApi as $k) {
                    $data['kelas'][] = [
                        'id_kelas' => $k['id'],
                        'nama_kelas' => $k['name'] ?? 'Kelas',
                        'thumbnail' => $k['thumbnail'] ?? $k['image'] ?? '',
                        'kategori' => $k['training']['name'] ?? $k['category'] ?? 'Umum',
                        'status' => $k['status'] ?? 'aktif',
                        'deskripsi' => $k['description'] ?? $k['summary'] ?? '-',
                        'nama_mentor' => (!empty($k['trainers']) && isset($k['trainers'][0]['name'])) 
                                          ? $k['trainers'][0]['name'] 
                                          : 'Mentor Belum Ditentukan',
                        'jumlah_pertemuan' => $k['schedule']['meetings'] ?? $k['sessions_count'] ?? $k['meetings'] ?? 0,
                        'kapasitas' => $k['capacity']['target_students'] ?? $k['capacity'] ?? 0,
                        'kapasitas_tersedia' => $k['capacity']['remaining_students'] ?? $k['available_capacity'] ?? $k['capacity'] ?? 0,
                        'harga_reguler' => $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? 0,
                        'harga_privat' => $k['pricing']['price'] ?? $k['private_price'] ?? 0,
                        'tanggal_mulai_kelas' => $k['schedule']['start_date'] ?? $k['start_date'] ?? '-',
                        'jenis_kelas' => $k['type'] ?? 'Reguler',
                        'metode_pembelajaran' => $k['schedule']['method'] ?? $k['method'] ?? 'offline'
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal mengambil data kelas dari API Laravel: ' . $e->getMessage());
            $data['kelas'] = [];
            $data['api_error'] = true;
        }

        return view('peserta/daftar_kelas', $data);
    }

    /**
     * Halaman Utama Manajemen KBM & Kelulusan Peserta
     */
    

    /**
     * Process Update Status Kelulusan Peserta
     */
    public function updateStatusKelulusanPeserta($id)
    {
        $pendaftaranModel = new \App\Models\PendaftaranModel();
        $statusKelulusan  = $this->request->getPost('status_kelulusan'); // 'Lulus' / 'Tidak Lulus'

        $pendaftaranModel->update($id, [
            'status_kelulusan' => $statusKelulusan
        ]);

        return redirect()->to(base_url('admin/manajemen-kbm'))->with('success', 'Status kelulusan peserta berhasil diperbarui.');
    }

    public function detailPendaftaran($id_pendaftaran = null)
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();
    $userId = $this->userId();

    // Pastikan id_pendaftaran ada
    if (!$id_pendaftaran) {
        // Coba ambil pendaftaran terbaru milik user jika parameter URL kosong
        $pendaftaranTerakhir = $db->table('pendaftaran')
            ->where('id_users', $userId)
            ->orderBy('id_pendaftaran', 'DESC')
            ->get()
            ->getRowArray();

        if ($pendaftaranTerakhir) {
            $id_pendaftaran = $pendaftaranTerakhir['id_pendaftaran'];
        } else {
            return redirect()->to(base_url('peserta/dashboard'))->with('error', 'Belum ada data pendaftaran yang ditemukan.');
        }
    }

    // Ambil data pendaftaran secara lengkap beserta relasi kelas dan mentor
    $detail = $db->table('pendaftaran')
        ->select('pendaftaran.*, kelas.nama_kelas, kelas.deskripsi, kelas.tipe_kelas, kelas.tanggal_mulai_kelas, kelas.kapasitas, mentor.nama_mentor')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('pendaftaran.id_pendaftaran', $id_pendaftaran)
        ->where('pendaftaran.id_users', $userId)
        ->get()
        ->getRowArray();

    if (!$detail) {
        return redirect()->to(base_url('peserta/dashboard'))->with('error', 'Data detail pendaftaran dengan ID ' . $id_pendaftaran . ' tidak ditemukan.');
    }

    $data = [
        'title'  => 'Detail Status Pendaftaran - Creativemu Academy',
        'detail' => $detail,
        'user'   => (new \App\Models\UserModel())->find($userId),
    ];

    return view('peserta/detail_pendaftaran', $data);
}

    // Method tambahan untuk mengatasi error "Controller method is not found: kelas"
    // Method untuk halaman KBM (Absen, Materi, Ujian, Sertifikat, Angket)
    public function kelas()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $idKelas = $this->request->getGet('id_kelas');

    $kelasBuilder = (new PendaftaranModel())
        ->select('pendaftaran.*, kelas.*, mentor.nama_mentor')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('pendaftaran.id_users', $this->userId());

    if (!empty($idKelas)) {
        $kelasBuilder->where('pendaftaran.id_kelas', $idKelas);
    }

    $kelas = $kelasBuilder
        ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
        ->first();

    $db = \Config\Database::connect();

    $jadwal = [];
    if ($kelas) {
        $jadwal = $db->table('jadwal_kelas')
            ->select('jadwal_kelas.*')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('pertemuan_ke', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($jadwal as &$item) {
            $idJadwal = (int) ($item['id_jadwal'] ?? $item['id_jadwal_kelas'] ?? 0);
            $item['absensi'] = $db->table('absensi')
                ->where('id_user', $this->userId())
                ->groupStart()
                    ->where('id_jadwal_kelas', $idJadwal)
                    ->orWhere('id_jadwal', $idJadwal)
                ->groupEnd()
                ->get()
                ->getRowArray();
        }
        unset($item);
    }

    // Ambil data ujian & nilai peserta
    $ujian = [];
    $semua_ujian_lulus = false;
    $ada_nilai_keluar = false;
    $nilai_ujian = '-'; 

    if ($kelas) {
        $ujian = $db->table('ujian')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('id_ujian', 'ASC')
            ->get()
            ->getResultArray();

        if (!empty($ujian)) {
            $lulusSemua = true;
            $sudahAdaNilai = false;

            foreach ($ujian as &$itemUjian) {
                $hasilUjian = $db->table('nilai_ujian')
                    ->where('id_users', $this->userId())
                    ->where('id_ujian', $itemUjian['id_ujian'])
                    ->orderBy('id_nilai_ujian', 'DESC')
                    ->get()
                    ->getRowArray();

                $itemUjian['nilai_record'] = $hasilUjian;

                // Cek apakah nilai sudah diinput/diverifikasi oleh admin
                if ($hasilUjian && (
                    (isset($hasilUjian['nilai_awal']) && $hasilUjian['nilai_awal'] !== null) || 
                    (isset($hasilUjian['nilai_remidi']) && $hasilUjian['nilai_remidi'] !== null) ||
                    (isset($hasilUjian['nilai']) && $hasilUjian['nilai'] !== null)
                )) {
                    $sudahAdaNilai = true;
                    $nilaiTerbaru = isset($hasilUjian['nilai_remidi']) && $hasilUjian['nilai_remidi'] !== null 
                        ? (float)$hasilUjian['nilai_remidi'] 
                        : (float)($hasilUjian['nilai_awal'] ?? $hasilUjian['nilai'] ?? 0);

                    $itemUjian['nilai_terbaru'] = $nilaiTerbaru;
                    // Status hanya akan menjadi 'lulus' atau 'belum' jika admin sudah menginput nilai
                    $itemUjian['status_kelulusan'] = $hasilUjian['status_kelulusan'] ?? ($nilaiTerbaru >= 70 ? 'lulus' : 'belum');

                    if (strtolower($itemUjian['status_kelulusan']) !== 'lulus') {
                        $lulusSemua = false;
                    }
                } else {
                    // Jika belum dinilai admin, kosongkan / set null
                    $itemUjian['nilai_terbaru'] = null;
                    $itemUjian['status_kelulusan'] = 'menunggu';
                    $lulusSemua = false;
                }
            }
            unset($itemUjian);

            // Ambil nilai ujian terbaru untuk kartu ringkasan
            foreach ($ujian as $itemUjian) {
                if (!empty($itemUjian['nilai_record'])) {
                    $record = $itemUjian['nilai_record'];
                    if (isset($record['nilai_remidi']) && $record['nilai_remidi'] !== null) {
                        $nilai_ujian = $record['nilai_remidi'];
                    } elseif (isset($record['nilai_awal']) && $record['nilai_awal'] !== null) {
                        $nilai_ujian = $record['nilai_awal'];
                    } elseif (isset($record['nilai']) && $record['nilai'] !== null) {
                        $nilai_ujian = $record['nilai'];
                    }
                    break;
                }
            }

            $ada_nilai_keluar = $sudahAdaNilai;
            $semua_ujian_lulus = $lulusSemua && $sudahAdaNilai;
        }
    }

    // ==========================================
    // Cek Pengisian Angket
    // ==========================================
    $sudah_isi_angket = false;
    if ($kelas) {
        $sudah_isi_angket = (bool) $db->table('angket_penilaian')
            ->where('id_peserta', $this->userId())
            ->where('id_kelas', $kelas['id_kelas'])
            ->get()
            ->getRow();
    }

    // Syarat Angket Muncul & Bisa Diisi: 
    // 1. Nilai ujian sudah keluar ($ada_nilai_keluar)
    // 2. Keterangan Lulus Semua ($semua_ujian_lulus)
    $bisa_isi_angket = ($ada_nilai_keluar && $semua_ujian_lulus);

    $sertifikatTerbit = null;

if ($kelas) {
    $sertifikatTerbit = (new SertifikatModel())
        ->where('id_user', $this->userId())
        ->where('id_kelas', $kelas['id_kelas'])
        ->first();
}

// Letakkan kode ini di dalam method kelas() untuk mengecek data
//$id_user = session()->get('id_user') ?? session()->get('id');
//$cek_data = $db->table('nilai_ujian')->where('id_user', $id_user)->get()->getResultArray();
//dd($cek_data);

    $status_kelulusan = '';
    $status_remidi = '';
    $id_nilai_ujian = null;
    
    if (!empty($ujian)) {
        foreach ($ujian as $itemUjian) {
            if (!empty($itemUjian['nilai_record'])) {
                $status_kelulusan = strtolower($itemUjian['nilai_record']['status_kelulusan'] ?? '');
                $status_remidi = strtolower($itemUjian['nilai_record']['status_remidi'] ?? '');
                $id_nilai_ujian = $itemUjian['nilai_record']['id_nilai_ujian'] ?? null;
                break;
            }
        }
    }
    
    // Override syarat angket based on status_kelulusan if admin explicitly set it to lulus
    if ($status_kelulusan === 'lulus') {
        $bisa_isi_angket = true;
    } elseif ($status_kelulusan === 'remidi' || $status_kelulusan === 'tidak lulus') {
        $bisa_isi_angket = false;
    }

    return view('peserta/kelas', [
    'kelas'               => $kelas,
    'jadwal'              => $jadwal,
    'ujian'               => $ujian,
    'nilai_ujian'         => $nilai_ujian,
    'status_kelulusan'    => $status_kelulusan,
    'status_remidi'       => $status_remidi,
    'id_nilai_ujian'      => $id_nilai_ujian,
    'bisa_isi_angket'     => $bisa_isi_angket,
    'sudah_isi_angket'    => $sudah_isi_angket,
    'sertifikatTerbit'    => $sertifikatTerbit,
]);
}

    public function detailKelas($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'ID Kelas tidak valid.');
        }

        $apiService = new \App\Services\LaravelApiService();
        try {
            $response = $apiService->request('GET', 'classes/' . $id);
            $k = $response['data'] ?? $response ?? null;

            if (!$k || !isset($k['id'])) {
                return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'Data kelas tidak ditemukan di server utama.');
            }

            $data['kelas'] = [
                'id_kelas' => $k['id'],
                'nama_kelas' => $k['name'] ?? 'Kelas',
                'thumbnail' => $k['thumbnail'] ?? $k['image'] ?? '',
                'kategori' => $k['training']['name'] ?? $k['category'] ?? 'Umum',
                'status' => $k['status'] ?? 'aktif',
                'deskripsi' => $k['description'] ?? $k['summary'] ?? '-',
                'nama_mentor' => (!empty($k['trainers']) && isset($k['trainers'][0]['name'])) 
                                  ? $k['trainers'][0]['name'] 
                                  : 'Mentor Belum Ditentukan',
                'jumlah_pertemuan' => $k['schedule']['meetings'] ?? $k['sessions_count'] ?? $k['meetings'] ?? 0,
                'kapasitas' => $k['capacity']['target_students'] ?? $k['capacity'] ?? 0,
                'kapasitas_tersedia' => $k['capacity']['remaining_students'] ?? $k['available_capacity'] ?? $k['capacity'] ?? 0,
                'harga_reguler' => $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? 0,
                'harga_privat' => $k['pricing']['price'] ?? $k['private_price'] ?? 0,
                'tanggal_mulai_kelas' => $k['schedule']['start_date'] ?? $k['start_date'] ?? '-',
                'jenis_kelas' => $k['type'] ?? 'Reguler',
                'metode_pembelajaran' => $k['schedule']['method'] ?? $k['method'] ?? 'offline'
            ];

            $data['title'] = 'Detail Kelas: ' . $data['kelas']['nama_kelas'];
        } catch (\Exception $e) {
            log_message('error', 'Gagal memuat detail kelas dari API: ' . $e->getMessage());
            return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'Sistem gagal menghubungi server utama.');
        }

        return view('peserta/detail_kelas', $data);
    }

    public function detailJadwal($idJadwal)
{
    $absensiModel = new \App\Models\AbsensiModel();
    $jadwalModel = new \App\Models\JadwalModel(); // Sesuaikan dengan model jadwal Anda

    // Cek apakah user sudah pernah absen di jadwal ini
    $sudahAbsen = $absensiModel->where('id_jadwal_kelas', $idJadwal)
                                 ->where('id_user', $this->userId())
                                 ->first();

    // Ambil data jadwal berdasarkan ID
    $jadwal = $jadwalModel->find($idJadwal);

    $data = [
        'jadwal'      => $jadwal,
        'sudah_absen' => $sudahAbsen ? true : false,
        'data_absen'  => $sudahAbsen
    ];

    return view('peserta/detail_jadwal', $data);
}

   public function kbm($id_kelas = null)
{
    $db = \Config\Database::connect();
    
    // Sesuaikan cara pengambilan ID user dari session aplikasi Anda (contoh: session('id_users') atau session('id_user'))
    $userId = session()->get('id_users') ?? session()->get('id_user');

    if (!$userId) {
        return redirect()->to(base_url('pelatihan/login'))->with('error', 'Silakan login terlebih dahulu.');
    }

    // 1. Ambil id_kelas dari parameter URL (segment) atau Query String (?id_kelas=...)
    if (!$id_kelas) {
        $id_kelas = $this->request->getGet('id_kelas');
    }

    // 2. Validasi apakah peserta benar-benar terdaftar di kelas tersebut
    if ($id_kelas) {
        $cekPendaftaran = $db->table('pendaftaran')
            ->where('id_users', $userId)
            ->where('id_kelas', $id_kelas)
            ->get()
            ->getRowArray();

        if (!$cekPendaftaran) {
            return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                ->with('error', 'Anda tidak memiliki akses ke ruang KBM kelas tersebut.');
        }
    } else {
        // Jika tetap kosong, ambil kelas pendaftaran terakhir milik peserta
        $pendaftaran = $db->table('pendaftaran')
            ->where('id_users', $userId)
            ->orderBy('id_pendaftaran', 'DESC')
            ->get()
            ->getRowArray();
        $id_kelas = $pendaftaran['id_kelas'] ?? null;
    }

    if (!$id_kelas) {
        return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
            ->with('error', 'Belum ada kelas aktif yang diikuti.');
    }

    // 3. Ambil data kelas & mentor berdasarkan $id_kelas yang dipilih
    $kelas = $db->table('kelas')
        ->select('kelas.*, mentor.nama_mentor')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('kelas.id_kelas', $id_kelas)
        ->get()
        ->getRowArray();

    // 4. Ambil data materi & jadwal sesuai id_kelas
    $materi = $db->table('materi')->where('id_kelas', $id_kelas)->get()->getResultArray();
    $jadwal = $db->table('jadwal')->where('id_kelas', $id_kelas)->orderBy('pertemuan_ke', 'ASC')->get()->getResultArray();

    foreach ($jadwal as &$item) {
        $idJadwal = (int) ($item['id_jadwal'] ?? $item['id_jadwal_kelas'] ?? 0);
        $item['absensi'] = $db->table('absensi')
            ->where('id_user', $userId)
            ->groupStart()
                ->where('id_jadwal_kelas', $idJadwal)
                ->orWhere('id_jadwal', $idJadwal)
            ->groupEnd()
            ->get()
            ->getRowArray();
    }
    unset($item);

    // 5. Ambil Nilai Ujian Peserta
    $nilaiUjianRow = null;
    if ($userId && $id_kelas) {
        $builderNilai = $db->table('nilai_ujian')->where('id_users', $userId);
        if ($db->fieldExists('id_kelas', 'nilai_ujian')) {
            $builderNilai->where('id_kelas', $id_kelas);
        }
        $nilaiUjianRow = $builderNilai->orderBy('id_nilai_ujian', 'DESC')->get()->getRowArray();

        if (!$nilaiUjianRow) {
            $nilaiUjianRow = $db->table('nilai_ujian')
                ->select('nilai_ujian.*')
                ->join('ujian', 'ujian.id_ujian = nilai_ujian.id_ujian', 'inner')
                ->where('nilai_ujian.id_users', $userId)
                ->where('ujian.id_kelas', $id_kelas)
                ->orderBy('nilai_ujian.id_nilai_ujian', 'DESC')
                ->get()
                ->getRowArray();
        }
    }

    $nilaiUjian = '-';
    $statusKelulusan = '';
    $statusRemidi = '';
    $idNilaiUjian = null;

    if ($nilaiUjianRow) {
        $nilaiUjian = $nilaiUjianRow['nilai_remidi'] ?? $nilaiUjianRow['nilai_awal'] ?? $nilaiUjianRow['nilai'] ?? '-';
        $statusKelulusan = strtolower($nilaiUjianRow['status_kelulusan'] ?? '');
        $statusRemidi = strtolower($nilaiUjianRow['status_remidi'] ?? '');
        $idNilaiUjian = $nilaiUjianRow['id_nilai_ujian'] ?? null;
    }

    $bisaIsiAngket = ($statusKelulusan === 'lulus' || ($nilaiUjian !== '-' && is_numeric($nilaiUjian) && (float)$nilaiUjian >= 70 && $statusKelulusan !== 'remidi' && $statusKelulusan !== 'tidak lulus'));

    // Cek status pengisian angket
    $sudahIsiAngket = false;
    if ($userId && $id_kelas) {
        $cekAngket = $db->table('jawaban_angket')
            ->select('jawaban_angket.*')
            ->join('angket_pertanyaan', 'angket_pertanyaan.id_angket_pertanyaan = jawaban_angket.id_pertanyaan')
            ->where('jawaban_angket.id_siswa', $userId)
            ->where('angket_pertanyaan.id_kelas', $id_kelas)
            ->get()
            ->getRowArray();
        
        if ($cekAngket) {
            $sudahIsiAngket = true;
        }
    }

    $sertifikatTerbit = (new \App\Models\SertifikatModel())
        ->where('id_kelas', $id_kelas)
        ->where('id_user', $userId)
        ->first();

    // 6. Kirim data ke view
    $data = [
        'kelas'           => $kelas,
        'materi'          => $materi,
        'jadwal'          => $jadwal,
        'nilai_ujian'     => $nilaiUjian,
        'status_kelulusan'=> $statusKelulusan,
        'status_remidi'   => $statusRemidi,
        'id_nilai_ujian'  => $idNilaiUjian,
        'bisa_isi_angket' => $bisaIsiAngket,
        'sudah_isi_angket'=> $sudahIsiAngket,
        'pendaftaran'     => $db->table('pendaftaran')->where('id_users', $userId)->where('id_kelas', $id_kelas)->get()->getRowArray(),
        'sertifikatTerbit'=> $sertifikatTerbit,
    ];

    return view('peserta/kelas', $data); 
}

    public function ikutRemidi()
{
    $id_nilai_ujian = $this->request->getGet('id');
    
    if ($id_nilai_ujian) {
        $db = \Config\Database::connect();
        $db->table('nilai_ujian')->where('id_nilai_ujian', $id_nilai_ujian)->update([
            'status_remidi' => 'bersedia'
        ]);
        session()->setFlashdata('success', 'Berhasil mengkonfirmasi bersedia mengikuti remidi!');
    } else {
        session()->setFlashdata('error', 'Data ujian tidak valid.');
    }
    
    return redirect()->back();
}

    public function daftarMateri()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $kelas = $this->approvedEnrollment();

    $materi = [];

    if ($kelas) {
        $materi = $db->table('materi')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('id_materi_kelas', 'ASC')
            ->get()
            ->getResultArray();
    }

    return view('peserta/daftar_materi', [
        'kelas' => $kelas,
        'materi' => $materi
    ]);
}
   public function materi($id = null)
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    // Ambil kelas yang diikuti peserta
    $kelas = (new PendaftaranModel())
        ->select('pendaftaran.*, kelas.*, mentor.nama_mentor')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('pendaftaran.id_users', $this->userId())
        ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
        ->first();

    // Kalau peserta belum punya kelas
    if (!$kelas) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Kelas tidak ditemukan.');
    }

    // Cari materi berdasarkan ID dan pastikan materinya milik kelas peserta
    $materi = null;

    if ($id) {
        $materi = $db->table('materi')
            ->where('id_materi_kelas', $id)
            ->where('id_kelas', $kelas['id_kelas'])
            ->get()
            ->getRowArray();
    }

    // Kalau materi tidak ditemukan
    if (!$materi) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Materi tidak ditemukan.');
    }

    // Cari jadwal/pertemuan yang terkait dengan materi
    $jadwal = null;

    if (!empty($materi['id_jadwal'])) {
        $jadwal = $db->table('jadwal')
            ->where('id_jadwal', $materi['id_jadwal'])
            ->where('id_kelas', $kelas['id_kelas'])
            ->get()
            ->getRowArray();
    }

    // Kalau materi belum memiliki jadwal
    if (!$jadwal) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Pertemuan untuk materi ini belum ditentukan.');
    }

    // Cek apakah peserta sudah melakukan absensi pada pertemuan tersebut
    $absensi = $db->table('absensi')
        ->where('id_jadwal_kelas', $jadwal['id_jadwal'])
        ->where('id_user', $this->userId())
        ->where('status', 'hadir')
        ->get()
        ->getRowArray();

    // Jika belum absen, materi tidak boleh dibuka
    if (!$absensi) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Silakan melakukan absensi terlebih dahulu sebelum mempelajari materi pertemuan ini.');
    }

    return view('peserta/materi', [
        'kelas'   => $kelas,
        'materi'  => $materi,
        'jadwal'  => $jadwal,
        'absensi' => $absensi
    ]);
}


    /**
     * Memastikan kolom-kolom tabel untuk absensi GPS dan jadwal selalu tersedia
     */
    private function ensureAbsensiGpsColumns(): void
    {
        try {
            $db = \Config\Database::connect();
            $forge = \Config\Database::forge();

            if ($db->tableExists('absensi')) {
                if (!$db->fieldExists('id_jadwal', 'absensi')) {
                    $forge->addColumn('absensi', [
                        'id_jadwal' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id_absensi']
                    ]);
                }
                if (!$db->fieldExists('id_jadwal_kelas', 'absensi')) {
                    $forge->addColumn('absensi', [
                        'id_jadwal_kelas' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id_jadwal']
                    ]);
                }
                if (!$db->fieldExists('latitude', 'absensi')) {
                    $forge->addColumn('absensi', [
                        'latitude' => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true, 'after' => 'status']
                    ]);
                }
                if (!$db->fieldExists('longitude', 'absensi')) {
                    $forge->addColumn('absensi', [
                        'longitude' => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true, 'after' => 'latitude']
                    ]);
                }
                if (!$db->fieldExists('jarak', 'absensi')) {
                    $forge->addColumn('absensi', [
                        'jarak' => ['type' => 'INT', 'constraint' => 11, 'null' => true, 'after' => 'longitude']
                    ]);
                }
            }

            if ($db->tableExists('jadwal')) {
                if (!$db->fieldExists('token_absen', 'jadwal')) {
                    $forge->addColumn('jadwal', [
                        'token_absen' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true]
                    ]);
                }
                if (!$db->fieldExists('absensi_dibuka', 'jadwal')) {
                    $forge->addColumn('jadwal', [
                        'absensi_dibuka' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0]
                    ]);
                }
                if (!$db->fieldExists('absensi_mulai', 'jadwal')) {
                    $forge->addColumn('jadwal', [
                        'absensi_mulai' => ['type' => 'DATETIME', 'null' => true]
                    ]);
                }
                if (!$db->fieldExists('absensi_selesai', 'jadwal')) {
                    $forge->addColumn('jadwal', [
                        'absensi_selesai' => ['type' => 'DATETIME', 'null' => true]
                    ]);
                }
                if (!$db->fieldExists('latitude', 'jadwal')) {
                    $forge->addColumn('jadwal', [
                        'latitude' => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true]
                    ]);
                }
                if (!$db->fieldExists('longitude', 'jadwal')) {
                    $forge->addColumn('jadwal', [
                        'longitude' => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true]
                    ]);
                }
                if (!$db->fieldExists('radius_meter', 'jadwal')) {
                    $forge->addColumn('jadwal', [
                        'radius_meter' => ['type' => 'INT', 'constraint' => 11, 'default' => 100, 'null' => true]
                    ]);
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'ensureAbsensiGpsColumns error: ' . $e->getMessage());
        }
    }

    public function prosesAbsenGps()
    {
        $this->ensureAbsensiGpsColumns();

        $json = $this->request->getJSON();
        $userLat  = $json->latitude ?? $json->user_latitude ?? null;
        $userLng  = $json->longitude ?? $json->user_longitude ?? null;
        $idJadwal = (int) ($json->id_jadwal ?? $json->id_jadwal_kelas ?? 0);
        $tokenInput = trim((string) ($json->token_absen ?? ''));

        if (!$idJadwal) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'ID Jadwal sesi pertemuan tidak valid.'
            ]);
        }

        $userId = $this->userId();
        if (!$userId) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Sesi login berakhir. Silakan login kembali.'
            ]);
        }

        $db = \Config\Database::connect();
        $jadwal = $db->table('jadwal')
            ->where('id_jadwal', $idJadwal)
            ->get()
            ->getRowArray();

        if (!$jadwal && $db->tableExists('jadwal_kelas')) {
            $jadwal = $db->table('jadwal_kelas')
                ->where('id_jadwal_kelas', $idJadwal)
                ->get()
                ->getRowArray();
        }

        if (!$jadwal) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Jadwal pertemuan tidak ditemukan.'
            ]);
        }

        $pendaftaran = (new \App\Models\PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $userId)
            ->where('pendaftaran.id_kelas', $jadwal['id_kelas'])
            ->groupStart()
                ->where('pendaftaran.status', 'Disetujui')
                ->orWhere('pendaftaran.status_pembayaran', 'valid')
            ->groupEnd()
            ->first();

        if (!$pendaftaran) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Anda tidak terdaftar atau belum disetujui untuk kelas ini.'
            ]);
        }

        if (!$jadwal) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Jadwal sesi kelas tidak ditemukan.'
            ]);
        }

        if ((int)($jadwal['absensi_dibuka'] ?? 0) !== 1) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Sesi absensi untuk pertemuan ini belum dibuka oleh mentor/admin.'
            ]);
        }

        if (!empty($jadwal['absensi_selesai']) && strtotime($jadwal['absensi_selesai']) < time()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Waktu sesi absensi untuk pertemuan ini telah berakhir.'
            ]);
        }

        // Bypass Token (Sesuai instruksi: Jangan gunakan token)
        /*
        $tokenDb = trim((string) ($jadwal['token_absen'] ?? ''));
        if ($tokenDb !== '') {
            if ($tokenInput === '') {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Silakan masukkan 4 digit token absensi dari mentor.'
                ]);
            }
            $secretKey = "KunciRahasiaKelasOffline" . $idJadwal;
            $currBlock = floor(time() / 12);
            $validTokens = [
                $tokenDb,
                substr(abs(crc32(($currBlock) . $secretKey)), 0, 4),
                substr(abs(crc32(($currBlock - 1) . $secretKey)), 0, 4),
                substr(abs(crc32(($currBlock - 2) . $secretKey)), 0, 4),
                substr(abs(crc32(($currBlock + 1) . $secretKey)), 0, 4),
            ];

            if (!in_array($tokenInput, $validTokens, true)) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Token absensi salah! Silakan periksa kembali token dari mentor.'
                ]);
            }
        }
        */

        $cekAbsen = $db->table('absensi')
            ->where('id_user', $userId)
            ->groupStart()
                ->where('id_jadwal_kelas', $idJadwal)
                ->orWhere('id_jadwal', $idJadwal)
            ->groupEnd()
            ->get()
            ->getRowArray();

        if ($cekAbsen) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Anda sudah melakukan absensi pada pertemuan ini sebelumnya.'
            ]);
        }

        $lokasiInfo = $this->resolveLokasiPelatihan(
            $jadwal['lokasi_nama'] ?? $pendaftaran['lokasi_pelatihan'] ?? null,
            $pendaftaran['metode_pembelajaran'] ?? null
        );

        $waktuSekarang = date('Y-m-d H:i:s');

        // Kelas Online
        if (!empty($lokasiInfo['is_online'])) {
            $db->table('absensi')->insert([
                'id_jadwal'       => $idJadwal,
                'id_jadwal_kelas' => $idJadwal,
                'id_user'         => $userId,
                'status'          => 'hadir',
                'latitude'        => null,
                'longitude'       => null,
                'jarak'           => 0,
                'waktu_absen'     => $waktuSekarang,
                'created_at'      => $waktuSekarang,
                'updated_at'      => $waktuSekarang,
            ]);

            return $this->response->setJSON([
                'status'  => true,
                'message' => 'Absensi berhasil! Anda berhasil melakukan absensi Pertemuan ' . $jadwal['pertemuan_ke'] . '.'
            ]);
        }

        // Kelas Offline: Validasi GPS
        if ($userLat === null || $userLng === null || trim((string)$userLat) === '' || trim((string)$userLng) === '') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data koordinat GPS tidak lengkap atau izin lokasi tidak aktif.'
            ]);
        }

        $userLat = (float) str_replace(',', '.', (string)$userLat);
        $userLng = (float) str_replace(',', '.', (string)$userLng);

        if ($userLat == 0.0 && $userLng == 0.0) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Koordinat GPS tidak valid (0, 0).'
            ]);
        }

        $targetLat       = (float) (!empty($jadwal['latitude']) ? $jadwal['latitude'] : $lokasiInfo['latitude']);
        $targetLng       = (float) (!empty($jadwal['longitude']) ? $jadwal['longitude'] : $lokasiInfo['longitude']);
        $radiusToleransi = (int) (!empty($jadwal['radius_meter']) ? $jadwal['radius_meter'] : ($lokasiInfo['radius_meter'] ?? 100));

        $jarakMeter = round($this->hitungJarakGPS($userLat, $userLng, $targetLat, $targetLng));

        if ($jarakMeter > $radiusToleransi) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Anda berada di luar area pelatihan. Silakan menuju lokasi pelatihan untuk melakukan absensi.'
            ]);
        }

        $db->table('absensi')->insert([
            'id_jadwal'       => $idJadwal,
            'id_jadwal_kelas' => $idJadwal,
            'id_user'         => $userId,
            'status'          => 'hadir',
            'latitude'        => $userLat,
            'longitude'       => $userLng,
            'jarak'           => $jarakMeter,
            'waktu_absen'     => $waktuSekarang,
            'created_at'      => $waktuSekarang,
            'updated_at'      => $waktuSekarang,
        ]);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Absensi HADIR berhasil! Terverifikasi di lokasi pelatihan (' . $jarakMeter . ' meter dari titik pusat).'
        ]);
    }

    public function prosesAbsen(?int $idJadwal = null)
    {
        $this->ensureAbsensiGpsColumns();

        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        if (!$idJadwal) {
            $idJadwal = (int) ($this->request->getPost('id_jadwal') ?? $this->request->getPost('id_jadwal_kelas'));
        }

        if (!$idJadwal) {
            return redirect()->back()->with('error', 'ID Jadwal sesi pertemuan tidak valid.')->with('active_tab', 'absensi');
        }

        $db = \Config\Database::connect();

        // 1. Ambil jadwal dari tabel jadwal (atau fallback jadwal_kelas)
        $jadwal = $db->table('jadwal')
            ->where('id_jadwal', $idJadwal)
            ->get()
            ->getRowArray();

        if (!$jadwal && $db->tableExists('jadwal_kelas')) {
            $jadwal = $db->table('jadwal_kelas')
                ->where('id_jadwal_kelas', $idJadwal)
                ->get()
                ->getRowArray();
        }

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal pertemuan tidak ditemukan untuk kelas Anda.')->with('active_tab', 'absensi');
        }

        $pendaftaran = (new \App\Models\PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->where('pendaftaran.id_kelas', $jadwal['id_kelas'])
            ->groupStart()
                ->where('pendaftaran.status', 'Disetujui')
                ->orWhere('pendaftaran.status_pembayaran', 'valid')
            ->groupEnd()
            ->first();

        if (!$pendaftaran) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Anda belum memiliki kelas yang disetujui untuk sesi ini.');
        }

        // 2. Cek apakah absensi dibuka
        $absensiDibuka = (int) ($jadwal['absensi_dibuka'] ?? 0);
        if ($absensiDibuka !== 1) {
            return redirect()->back()->with('error', 'Sesi absensi untuk pertemuan ini belum dibuka oleh mentor/admin.')->with('active_tab', 'absensi');
        }

        // 3. Cek batas waktu jika diatur
        if (!empty($jadwal['absensi_selesai']) && strtotime($jadwal['absensi_selesai']) < time()) {
            return redirect()->back()->with('error', 'Waktu sesi absensi untuk pertemuan ini telah berakhir.')->with('active_tab', 'absensi');
        }

        // 4. Validasi token jika mentor/admin menetapkan token sesi
        // Bypass Token (Sesuai instruksi: Jangan gunakan token)
        /*
        $tokenDb = trim((string) ($jadwal['token_absen'] ?? ''));
        $tokenInput = trim((string) $this->request->getPost('token_absen'));
        if ($tokenDb !== '') {
            if ($tokenInput === '') {
                return redirect()->back()->with('error', 'Silakan masukkan 4 digit token absensi dari mentor.')->with('active_tab', 'absensi');
            }
            $secretKey = "KunciRahasiaKelasOffline" . $idJadwal;
            $currBlock = floor(time() / 12);
            $validTokens = [
                $tokenDb,
                substr(abs(crc32(($currBlock) . $secretKey)), 0, 4),
                substr(abs(crc32(($currBlock - 1) . $secretKey)), 0, 4),
                substr(abs(crc32(($currBlock - 2) . $secretKey)), 0, 4),
                substr(abs(crc32(($currBlock + 1) . $secretKey)), 0, 4),
            ];

            if (!in_array($tokenInput, $validTokens, true)) {
                return redirect()->back()->with('error', 'Token absensi salah! Silakan periksa kembali token dari mentor.')->with('active_tab', 'absensi');
            }
        }
        */

        // 5. Cek absensi ganda
        $absensiModel = new AbsensiModel();
        $sudahAbsen = $absensiModel
            ->where('id_user', $this->userId())
            ->groupStart()
                ->where('id_jadwal', $idJadwal)
                ->orWhere('id_jadwal_kelas', $idJadwal)
            ->groupEnd()
            ->first();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi pada pertemuan ini.')->with('active_tab', 'absensi');
        }

        $waktuSekarang = date('Y-m-d H:i:s');

        // 6. Opsi status: tidak hadir
        $pilihanStatus = strtolower(trim((string) $this->request->getPost('status_absen')));
        if ($pilihanStatus === 'tidak hadir' || $pilihanStatus === 'tidak_hadir') {
            $absensiModel->insert([
                'id_jadwal'       => $idJadwal,
                'id_jadwal_kelas' => $idJadwal,
                'id_user'         => $this->userId(),
                'status'          => 'tidak hadir',
                'latitude'        => null,
                'longitude'       => null,
                'jarak'           => null,
                'waktu_absen'     => $waktuSekarang,
                'created_at'      => $waktuSekarang,
                'updated_at'      => $waktuSekarang,
            ]);

            return redirect()->back()->with('success', 'Status TIDAK HADIR berhasil disimpan.')->with('active_tab', 'absensi');
        }

        // 7. Resolusi koordinat pusat lokasi pelatihan
        $lokasiInfo = $this->resolveLokasiPelatihan(
            $jadwal['lokasi_nama'] ?? $pendaftaran['lokasi_pelatihan'] ?? null,
            $pendaftaran['metode_pembelajaran'] ?? null
        );

        // A. KELAS ONLINE: Tidak membutuhkan validasi GPS
        if (!empty($lokasiInfo['is_online'])) {
            $absensiModel->insert([
                'id_jadwal'       => $idJadwal,
                'id_jadwal_kelas' => $idJadwal,
                'id_user'         => $this->userId(),
                'status'          => 'hadir',
                'latitude'        => null,
                'longitude'       => null,
                'jarak'           => 0,
                'waktu_absen'     => $waktuSekarang,
                'created_at'      => $waktuSekarang,
                'updated_at'      => $waktuSekarang,
            ]);

            return redirect()->back()->with('success', 'Absensi berhasil! Anda berhasil melakukan absensi Pertemuan ' . $jadwal['pertemuan_ke'] . '.')->with('active_tab', 'absensi');
        }

        // B. KELAS OFFLINE: Validasi GPS perangkat pengguna
        $userLat = $this->request->getPost('user_latitude') ?? $this->request->getPost('latitude');
        $userLng = $this->request->getPost('user_longitude') ?? $this->request->getPost('longitude');
        $gpsError = $this->request->getPost('gps_error');

        if ($gpsError || $userLat === null || $userLng === null || trim((string)$userLat) === '' || trim((string)$userLng) === '') {
            return redirect()->back()->with('error', 'Absensi gagal! Koordinat GPS tidak terdeteksi. Pastikan izin lokasi (Geolocation) diizinkan di browser Anda dan GPS perangkat dalam keadaan aktif.')->with('active_tab', 'absensi');
        }

        $userLat = (float) str_replace(',', '.', (string)$userLat);
        $userLng = (float) str_replace(',', '.', (string)$userLng);

        if ($userLat == 0.0 && $userLng == 0.0) {
            return redirect()->back()->with('error', 'Titik koordinat GPS tidak valid (0, 0). Pastikan GPS Anda aktif dan mendapatkan sinyal yang akurat.')->with('active_tab', 'absensi');
        }

        $targetLat       = (float) (!empty($jadwal['latitude']) ? $jadwal['latitude'] : $lokasiInfo['latitude']);
        $targetLng       = (float) (!empty($jadwal['longitude']) ? $jadwal['longitude'] : $lokasiInfo['longitude']);
        $radiusToleransi = (int) (!empty($jadwal['radius_meter']) ? $jadwal['radius_meter'] : ($lokasiInfo['radius_meter'] ?? 100));

        // Hitung jarak Haversine di server
        $jarakMeter = round($this->hitungJarakGPS($userLat, $userLng, $targetLat, $targetLng));

        // Validasi radius jarak
        if ($jarakMeter > $radiusToleransi) {
            return redirect()->back()->with('error', 'Anda berada di luar area pelatihan. Silakan menuju lokasi pelatihan untuk melakukan absensi. (Jarak: ' . $jarakMeter . 'm | Target: ' . $targetLat . ',' . $targetLng . ' | Anda: ' . $userLat . ',' . $userLng . ')')->with('active_tab', 'absensi');
        }

        // Simpan data absensi HADIR lengkap
        $absensiModel->insert([
            'id_jadwal'       => $idJadwal,
            'id_jadwal_kelas' => $idJadwal,
            'id_user'         => $this->userId(),
            'status'          => 'hadir',
            'latitude'        => $userLat,
            'longitude'       => $userLng,
            'jarak'           => $jarakMeter,
            'waktu_absen'     => $waktuSekarang,
            'created_at'      => $waktuSekarang,
            'updated_at'      => $waktuSekarang,
        ]);

        return redirect()->back()->with(
            'success',
            'Absensi berhasil! Anda berhasil melakukan absensi Pertemuan ' . $jadwal['pertemuan_ke'] . ' (Terverifikasi ' . $jarakMeter . ' meter dari pusat area).'
        )->with('active_tab', 'absensi');
    }

    // Fungsi pendukung untuk menghitung jarak GPS (dalam meter menggunakan Haversine Formula)
    public function hitungJarakGPS($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latFrom = deg2rad((float) $lat1);
        $lonFrom = deg2rad((float) $lon1);
        $latTo   = deg2rad((float) $lat2);
        $lonTo   = deg2rad((float) $lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (float) ($earthRadius * $c);
    }

    public function hitungJarakHaversine($lat1, $lon1, $lat2, $lon2): float
    {
        return $this->hitungJarakGPS($lat1, $lon1, $lat2, $lon2);
    }

    /**
     * Menentukan koordinat dan radius tempat pelatihan peserta secara dinamis
     */
    public function resolveLokasiPelatihan(?string $namaLokasi, ?string $metodePembelajaran = null): array
    {
        $isOnline = false;

        $cleanMetode = strtolower(trim((string) $metodePembelajaran));
        $cleanNama   = strtolower(trim((string) $namaLokasi));

        if ($cleanMetode === 'online' || str_contains($cleanNama, 'online') || str_contains($cleanNama, 'daring')) {
            $isOnline = true;
        }

        if ($isOnline) {
            return [
                'nama_lokasi'  => $namaLokasi ?: 'Online / Daring',
                'alamat'       => 'Online / Jarak Jauh',
                'latitude'     => 0.000000,
                'longitude'    => 0.000000,
                'radius_meter' => 9999999,
                'is_online'    => 1,
            ];
        }

        if (str_contains($cleanNama, 'pusat')) {
            return [
                'nama_lokasi'  => 'Kantor Pusat',
                'alamat'       => 'Jl. Gn. Bulu No.89, RT.34, Bandut Lor, Argorejo, Kec. Sedayu, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55752',
                'latitude'     => -7.818933,
                'longitude'    => 110.285813,
                'radius_meter' => 100,
                'is_online'    => 0,
            ];
        }
        
        if (str_contains($cleanNama, 'cabang')) {
            return [
                'nama_lokasi'  => 'Kantor Cabang',
                'alamat'       => 'Jl. Glagahsari No.46C, Warungboto, Kec. Umbulharjo, Kota Yogyakarta, Daerah Istimewa Yogyakarta',
                'latitude'     => -7.810000,
                'longitude'    => 110.380000,
                'radius_meter' => 100,
                'is_online'    => 0,
            ];
        }
        
        if (str_contains($cleanNama, 'perwakilan')) {
            return [
                'nama_lokasi'  => 'Kantor Perwakilan',
                'alamat'       => 'Jl. Soekarno Hatta, Sawitan, Kabupaten Magelang, Jawa Tengah',
                'latitude'     => -7.580000,
                'longitude'    => 110.220000,
                'radius_meter' => 100,
                'is_online'    => 0,
            ];
        }

        return [
            'nama_lokasi'  => $namaLokasi ?: 'Kantor Pusat',
            'alamat'       => 'Jl. Gn. Bulu No.89, RT.34, Bandut Lor, Argorejo, Kec. Sedayu, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55752',
            'latitude'     => -7.818933,
            'longitude'    => 110.285813,
            'radius_meter' => 100,
            'is_online'    => 0,
        ];
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

        $db = \Config\Database::connect();

        // Ambil kelas yang diikuti peserta
        $kelas = $this->approvedEnrollment();

        if (!$kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))
                ->with('error', 'Kelas tidak ditemukan atau belum disetujui.');
        }

        // Ambil ujian berdasarkan kelas peserta
        $ujian = $db->table('ujian')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('id_ujian', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($ujian)) {
            $ujian = [
                [
                    'id_ujian'    => 1,
                    'id_kelas'    => $kelas['id_kelas'],
                    'judul_ujian' => 'Ujian Akhir ' . ($kelas['nama_kelas'] ?? 'Pelatihan'),
                    'keterangan'  => 'Ujian akhir untuk mengukur pemahaman materi pelatihan.',
                    'deadline'    => null,
                ]
            ];
        }

        // Ambil riwayat nilai peserta untuk setiap ujian
        foreach ($ujian as &$item) {
            $nilaiRow = $db->table('nilai_ujian')
                ->where('id_users', $this->userId())
                ->groupStart()
                    ->where('id_ujian', $item['id_ujian'])
                    ->orWhere('id_kelas', $kelas['id_kelas'])
                ->groupEnd()
                ->orderBy('id_nilai_ujian', 'DESC')
                ->get()
                ->getRowArray();

            $item['nilai_record'] = $nilaiRow;

            if ($nilaiRow) {
                $nilaiAwal    = isset($nilaiRow['nilai_awal']) ? (float) $nilaiRow['nilai_awal'] : (float) $nilaiRow['nilai'];
                $nilaiRemidi  = isset($nilaiRow['nilai_remidi']) && $nilaiRow['nilai_remidi'] !== null ? (float) $nilaiRow['nilai_remidi'] : null;
                $nilaiTerbaru = ($nilaiRemidi !== null) ? $nilaiRemidi : $nilaiAwal;

                // Sinkronkan status kelulusan berdasarkan database / nilai terbaru (>= 70 Lulus, < 70 Remidi)
                $dbStatusKelulusan = strtolower($nilaiRow['status_kelulusan'] ?? '');
                
                $item['sudah_ujian']    = true;
                $item['nilai_awal']     = $nilaiAwal;
                $item['nilai_remidi']   = $nilaiRemidi;
                $item['nilai_terbaru']  = $nilaiTerbaru;
                
                // Jika di admin diubah jadi remidi atau nilai < 70
                $item['is_lulus']       = ($dbStatusKelulusan === 'lulus' || ($dbStatusKelulusan === '' && $nilaiTerbaru >= 70));
                $item['status_teks']    = $item['is_lulus'] ? 'LULUS' : 'REMIDI / BELUM LULUS';
                $item['bisa_remidi']    = !$item['is_lulus'];
            } else {
                $item['sudah_ujian']    = false;
                $item['nilai_awal']     = null;
                $item['nilai_remidi']   = null;
                $item['nilai_terbaru']  = null;
                $item['is_lulus']       = false;
                $item['status_teks']    = 'Belum Dikerjakan';
                $item['bisa_remidi']    = false;
            }
        }

        return view('peserta/ujian', [
            'title' => 'Ujian Peserta',
            'kelas' => $kelas,
            'ujian' => $ujian,
        ]);
    }

public function simpanJawabanUjian()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $idUjian = $this->request->getPost('id_ujian');

    if (!$idUjian) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Ujian tidak ditemukan.');
    }

    $db = \Config\Database::connect();

    // Ambil pendaftaran peserta berdasarkan user yang sedang login
    $kelas = (new PendaftaranModel())
        ->select('pendaftaran.*, kelas.nama_kelas, kelas.deskripsi, kelas.tipe_kelas, kelas.tanggal_mulai_kelas, kelas.jumlah_pertemuan, kelas.ringkasan, kelas.thumbnail, mentor.nama_mentor')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('pendaftaran.id_users', $this->userId())
        ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
        ->first();

    if (!$kelas) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Kelas peserta tidak ditemukan.');
    }

    // Pastikan ujian memang milik kelas peserta
    $ujian = $db->table('ujian')
        ->where('id_ujian', $idUjian)
        ->where('id_kelas', $kelas['id_kelas'])
        ->get()
        ->getRowArray();

    if (!$ujian) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Ujian tidak ditemukan atau bukan untuk kelas Anda.');
    }

    // Cek deadline
    if (!empty($ujian['deadline']) && strtotime($ujian['deadline']) < time()) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Deadline pengumpulan jawaban sudah berakhir.');
    }

    // Ambil file jawaban
    $file = $this->request->getFile('file_jawaban');

    if (!$file || !$file->isValid()) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'File jawaban belum dipilih.');
    }

    // File harus PDF
    if (strtolower($file->getExtension()) !== 'pdf') {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'File jawaban harus berupa PDF.');
    }

    // Maksimal 10 MB
    if ($file->getSize() > 10 * 1024 * 1024) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Ukuran file maksimal 10 MB.');
    }

    // Folder penyimpanan jawaban
    $folder = FCPATH . 'uploads/jawaban_ujian/';

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    // Nama file otomatis
    $namaFile = $file->getRandomName();

    // Pindahkan file
    if (!$file->move($folder, $namaFile)) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'File jawaban gagal disimpan.');
    }

    $waktuSekarang = date('Y-m-d H:i:s');

    // Cek apakah peserta sudah pernah mengumpulkan jawaban
    $jawabanLama = $db->table('jawaban_ujian')
        ->where('id_ujian', $idUjian)
        ->where('id_user', $this->userId())
        ->get()
        ->getRowArray();

    $data = [
        'id_ujian'     => $idUjian,
        'id_user'      => $this->userId(),
        'file_jawaban' => $namaFile,
        'waktu_kumpul' => $waktuSekarang,
        'updated_at'   => $waktuSekarang,
    ];

    // Jika sudah pernah mengumpulkan, update jawaban
    if ($jawabanLama) {

        $data['created_at'] = $jawabanLama['created_at'];

        $berhasil = $db->table('jawaban_ujian')
            ->where('id_jawaban', $jawabanLama['id_jawaban'])
            ->update($data);

        if (!$berhasil) {

            // Hapus file baru jika database gagal
            $fileBaru = $folder . $namaFile;

            if (is_file($fileBaru)) {
                unlink($fileBaru);
            }

            $errorDb = $db->error();

            return redirect()->to(base_url('pelatihan/kelas'))
                ->with(
                    'error',
                    'Jawaban gagal disimpan ke database. '
                    . ($errorDb['message'] ?? 'Terjadi kesalahan database.')
                );
        }

        // Hapus file jawaban lama setelah update berhasil
        if (!empty($jawabanLama['file_jawaban'])) {

            $fileLama = $folder . $jawabanLama['file_jawaban'];

            if (is_file($fileLama)) {
                unlink($fileLama);
            }
        }

    } else {

        // Jawaban pertama kali dikumpulkan
        $data['created_at'] = $waktuSekarang;

        $berhasil = $db->table('jawaban_ujian')
            ->insert($data);

        if (!$berhasil) {

            // Hapus file jika database gagal
            $fileBaru = $folder . $namaFile;

            if (is_file($fileBaru)) {
                unlink($fileBaru);
            }

            $errorDb = $db->error();

            return redirect()->to(base_url('pelatihan/kelas'))
                ->with(
                    'error',
                    'Jawaban gagal disimpan ke database. '
                    . ($errorDb['message'] ?? 'Terjadi kesalahan database.')
                );
        }
    }

    return redirect()->to(base_url('pelatihan/kelas#ujian'))
        ->with('success', 'Jawaban ujian berhasil dikumpulkan.');
}

    public function kerjakanUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (!$kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas Anda belum disetujui.');
        }

        $isRemidi = (int) ($this->request->getGet('remidi') ?? 0);
        $idUjian  = (int) ($this->request->getGet('id_ujian') ?? 0);

        // Cari info ujian jika ada di database
        $db = \Config\Database::connect();
        $ujianInfo = null;
        if ($idUjian > 0) {
            $ujianInfo = $db->table('ujian')->where('id_ujian', $idUjian)->where('id_kelas', $kelas['id_kelas'])->get()->getRowArray();
        }
        if (!$ujianInfo) {
            $ujianInfo = $db->table('ujian')->where('id_kelas', $kelas['id_kelas'])->orderBy('id_ujian', 'ASC')->get()->getRowArray();
        }

        $judulUjian = $ujianInfo['judul_ujian'] ?? ('Ujian Akhir ' . ($kelas['nama_kelas'] ?? 'Pelatihan'));
        $targetIdUjian = $ujianInfo['id_ujian'] ?? 1;

        // Soal ujian profesional pilihan ganda
        $soal = [
            [
                'id' => 1,
                'pertanyaan' => 'Apa tujuan utama pelaksanaan pelatihan kompetensi di CreativeMU Academy?',
                'pilihan_a' => 'Meningkatkan pemahaman praktis dan penguasaan keterampilan industri peserta',
                'pilihan_b' => 'Hanya sekadar memenuhi kehadiran tanpa evaluasi kemampuan',
                'pilihan_c' => 'Mengurangi interaksi langsung dengan mentor profesional',
                'pilihan_d' => 'Menghindari ujian akhir kelulusan',
            ],
            [
                'id' => 2,
                'pertanyaan' => 'Platform utama apakah yang disediakan CreativeMU Academy untuk memantau KBM, materi, dan evaluasi?',
                'pilihan_a' => 'Learning Management System (LMS) & Dashboard Peserta',
                'pilihan_b' => 'Aplikasi kalkulator komputer',
                'pilihan_c' => 'Notepad lokal tanpa jaringan internet',
                'pilihan_d' => 'File Explorer sistem operasi',
            ],
            [
                'id' => 3,
                'pertanyaan' => 'Mengapa sistem absensi kehadiran kelas offline dilengkapi dengan verifikasi titik koordinat GPS?',
                'pilihan_a' => 'Memastikan integritas kehadiran peserta benar-benar berada di tempat pelatihan',
                'pilihan_b' => 'Mempersulit peserta dalam mengikuti sesi pembelajaran',
                'pilihan_c' => 'Menggantikan materi pengajaran yang disampaikan mentor',
                'pilihan_d' => 'Menghapus data pendaftaran peserta secara otomatis',
            ],
            [
                'id' => 4,
                'pertanyaan' => 'Jika peserta memperoleh nilai ujian di bawah batas kelulusan minimal (kurang dari 70%), mekanisme apa yang disediakan?',
                'pilihan_a' => 'Peserta wajib mengikuti program remidi untuk perbaikan nilai tanpa menghapus nilai awal',
                'pilihan_b' => 'Peserta langsung dinyatakan gugur permanen',
                'pilihan_c' => 'Sistem menghapus akun peserta yang bersangkutan',
                'pilihan_d' => 'Tidak diberikan kesempatan evaluasi lanjutan',
            ],
            [
                'id' => 5,
                'pertanyaan' => 'Apa indikator keberhasilan utama setelah menyelesaikan seluruh kurikulum dan evaluasi di CreativeMU Academy?',
                'pilihan_a' => 'Mendapatkan nilai kelulusan kompetensi serta sertifikat resmi pelatihan',
                'pilihan_b' => 'Hanya menghadiri sesi tanpa mengumpulkan tugas dan ujian',
                'pilihan_c' => 'Menolak mengisi angket kepuasan mentor',
                'pilihan_d' => 'Tidak menyelesaikan evaluasi akhir',
            ],
        ];

        return view('peserta/kerjakan_ujian', [
            'kelas'      => $kelas,
            'judulUjian' => $judulUjian,
            'idUjian'    => $targetIdUjian,
            'soal'       => $soal,
            'isRemidi'   => $isRemidi,
        ]);
    }

    public function submitUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (!$kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas Anda belum disetujui.');
        }

        $jawaban  = $this->request->getPost('jawaban');
        $idUjian  = (int) ($this->request->getPost('id_ujian') ?? 1);
        $isRemidi = (int) ($this->request->getPost('is_remidi') ?? 0);

        // Kunci jawaban: 1=>A, 2=>A, 3=>A, 4=>A, 5=>A
        $kunci = [1 => 'A', 2 => 'A', 3 => 'A', 4 => 'A', 5 => 'A'];
        $benar = 0;

        if (is_array($jawaban)) {
            foreach ($kunci as $nomor => $jawabanBenar) {
                if (isset($jawaban[$nomor]) && strtoupper(trim($jawaban[$nomor])) === $jawabanBenar) {
                    $benar++;
                }
            }
        }

        $jumlahSoal  = count($kunci);
        $nilaiPersen = round(($benar / $jumlahSoal) * 100);

        $db            = \Config\Database::connect();
        $waktuSekarang = date('Y-m-d H:i:s');
        $userId        = $this->userId();

        // Cari riwayat nilai sebelumnya
        $existing = $db->table('nilai_ujian')
            ->where('id_users', $userId)
            ->groupStart()
                ->where('id_ujian', $idUjian)
                ->orWhere('id_kelas', $kelas['id_kelas'])
            ->groupEnd()
            ->orderBy('id_nilai_ujian', 'DESC')
            ->get()
            ->getRowArray();

        // Logika Remidi vs Ujian Pertama
        if ($isRemidi && $existing) {
            // REMIDI: Simpan nilai remidi secara terpisah tanpa menimpa nilai awal
            $nilaiAwal     = isset($existing['nilai_awal']) ? (float) $existing['nilai_awal'] : (float) $existing['nilai'];
            $statusLulus   = ($nilaiPersen >= 70) ? 'lulus' : 'belum_lulus';
            $statusRemidi  = ($nilaiPersen >= 70) ? 'selesai' : 'wajib';

            $updateData = [
                'nilai_remidi'     => $nilaiPersen,
                'status_kelulusan' => $statusLulus,
                'status_remidi'    => $statusRemidi,
                'is_remidi'        => 1,
                'updated_at'       => $waktuSekarang,
            ];

            $db->table('nilai_ujian')
                ->where('id_nilai_ujian', $existing['id_nilai_ujian'])
                ->update($updateData);

            $pesan = ($nilaiPersen >= 70)
                ? 'Selamat! Nilai remidi Anda ' . $nilaiPersen . '% dan dinyatakan LULUS.'
                : 'Nilai remidi Anda ' . $nilaiPersen . '%. Masih belum mencapai batas kelulusan (70%).';

        } else {
            // UJIAN PERTAMA: Nilai disimpan pada kolom nilai dan nilai_awal
            $statusLulus  = ($nilaiPersen >= 70) ? 'lulus' : 'belum_lulus';
            $statusRemidi = ($nilaiPersen >= 70) ? 'tidak_perlu' : 'wajib';

            $data = [
                'id_users'         => $userId,
                'id_kelas'         => $kelas['id_kelas'],
                'id_ujian'         => $idUjian,
                'benar'            => $benar,
                'jumlah_soal'      => $jumlahSoal,
                'nilai'            => $nilaiPersen,
                'nilai_awal'       => $nilaiPersen,
                'nilai_remidi'     => null,
                'status_kelulusan' => $statusLulus,
                'status_remidi'    => $statusRemidi,
                'is_remidi'        => 0,
                'catatan'          => 'Ujian Utama',
                'created_at'       => $waktuSekarang,
                'updated_at'       => $waktuSekarang,
            ];

            if ($existing) {
                $db->table('nilai_ujian')
                    ->where('id_nilai_ujian', $existing['id_nilai_ujian'])
                    ->update($data);
            } else {
                $db->table('nilai_ujian')->insert($data);
            }

            $pesan = ($nilaiPersen >= 70)
                ? 'Selamat! Anda memperoleh nilai ' . $nilaiPersen . '% dan dinyatakan LULUS.'
                : 'Anda memperoleh nilai ' . $nilaiPersen . '%. Nilai Anda di bawah 70% dan WAJIB mengikuti remidi.';
        }

        return redirect()->to(base_url('pelatihan/ujian/hasil'))->with('success', $pesan);
    }

    public function hasilUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (!$kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $nilaiRow = $db->table('nilai_ujian')
            ->where('id_users', $this->userId())
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('id_nilai_ujian', 'DESC')
            ->get()
            ->getRowArray();

        if (!$nilaiRow) {
            return redirect()->to(base_url('pelatihan/ujian'))->with('error', 'Anda belum mengerjakan ujian.');
        }

        $nilaiAwal    = isset($nilaiRow['nilai_awal']) ? (float) $nilaiRow['nilai_awal'] : (float) $nilaiRow['nilai'];
        $nilaiRemidi  = isset($nilaiRow['nilai_remidi']) && $nilaiRow['nilai_remidi'] !== null ? (float) $nilaiRow['nilai_remidi'] : null;
        $nilaiTerbaru = ($nilaiRemidi !== null) ? $nilaiRemidi : $nilaiAwal;

        $statusKelulusan = strtolower($nilaiRow['status_kelulusan'] ?? 'menunggu');

        $isLulus = ($statusKelulusan === 'lulus');
        $statusTeks = strtoupper($statusKelulusan);
        $bisaRemidi = ($statusKelulusan === 'remidi');

        return view('peserta/hasil_ujian', [
            'kelas'           => $kelas,
            'nilaiRow'        => $nilaiRow,
            'nilaiAwal'       => $nilaiAwal,
            'nilaiRemidi'     => $nilaiRemidi,
            'nilaiTerbaru'    => $nilaiTerbaru,
            'isLulus'         => $isLulus,
            'statusTeks'      => $statusTeks,
            'statusKelulusan' => $statusKelulusan,
            'bisaRemidi'      => $bisaRemidi,
            'isRemidiApplied' => ($nilaiRemidi !== null),
        ]);
    }

    public function angket()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $id_kelas = $this->request->getGet('id_kelas');
    $userId = $this->userId();

    if ($id_kelas) {
        $pendaftaran = (new \App\Models\PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $userId)
            ->where('pendaftaran.id_kelas', $id_kelas)
            ->groupStart()
                ->where('pendaftaran.status', 'Disetujui')
                ->orWhere('pendaftaran.status_pembayaran', 'valid')
            ->groupEnd()
            ->first();
    } else {
        $pendaftaran = $this->approvedEnrollment();
    }

    if (! $pendaftaran) {
        return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas tidak ditemukan.');
    }

    $idPeserta = $this->userId();
    $idKelas = $pendaftaran['id_kelas'];

    // Ambil pertanyaan angket aktif untuk kelas peserta
    // atau pertanyaan yang berlaku untuk semua kelas.
    $pertanyaan = $this->db
        ->table('angket_pertanyaan')
        ->where('status', 'Aktif')
        ->groupStart()
            ->where('id_kelas', $idKelas)
            ->orWhere('id_kelas IS NULL', null, false)
        ->groupEnd()
        ->orderBy('id_angket_pertanyaan', 'ASC')
        ->get()
        ->getResultArray();

    // Ambil daftar kelas dari tabel kelas
    $semuaKelas = $this->db
        ->table('kelas')
        ->where('status', 'aktif')
        ->get()
        ->getResultArray();

    // Cek apakah sudah mengisi angket untuk kelas ini
    $sudahIsiCheck = $this->db
        ->table('jawaban_angket ja')
        ->join('angket_pertanyaan ap', 'ap.id_angket_pertanyaan = ja.id_pertanyaan', 'inner')
        ->where('ja.id_siswa', $idPeserta)
        ->where('(ap.id_kelas IS NULL OR ap.id_kelas = ' . $this->db->escape($idKelas) . ')', null, false)
        ->get()
        ->getRowArray();

    $sudahIsi = $sudahIsiCheck ? true : false;

    return view('peserta/angket', [
        'pendaftaran' => $pendaftaran,
        'pertanyaan'  => $pertanyaan,
        'semuaKelas'  => $semuaKelas,
        'sudahIsi'    => $sudahIsi
    ]);
}

    public function simpanAngket()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $idPeserta = $this->userId();
        $jawaban = $this->request->getPost('jawaban');

        if (empty($jawaban) || !is_array($jawaban)) {
            return redirect()->back()->with('error', 'Jawaban angket tidak boleh kosong.');
        }

        $pendaftaran = $this->approvedEnrollment();
        if (!$pendaftaran) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $idKelas = $pendaftaran['id_kelas'];

        // Cek apakah sudah mengisi
        $sudahIsi = $this->db
            ->table('jawaban_angket ja')
            ->join('angket_pertanyaan ap', 'ap.id_angket_pertanyaan = ja.id_pertanyaan', 'inner')
            ->where('ja.id_siswa', $idPeserta)
            ->where('ap.id_kelas IS NULL OR ap.id_kelas = ' . $this->db->escape($idKelas), null, false)
            ->get()
            ->getRowArray();

        if ($sudahIsi) {
            return redirect()->back()->with('error', 'Anda sudah mengisi angket evaluasi.');
        }

        $this->db->transBegin();

        try {
            foreach ($jawaban as $idPertanyaan => $jawab) {
                $this->db->table('jawaban_angket')->insert([
                    'id_pertanyaan' => $idPertanyaan,
                    'id_siswa' => $idPeserta,
                    'jawaban' => $jawab,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->back()->with('error', 'Gagal menyimpan angket.');
            }

            $this->db->transCommit();
            return redirect()->to(base_url('pelatihan/angket'))->with('success', 'Angket berhasil dikirim.');

        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function sertifikat()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $id_kelas = $this->request->getGet('id_kelas');
        $userId = $this->userId();

        if ($id_kelas) {
            $pendaftaran = (new \App\Models\PendaftaranModel())
                ->select('pendaftaran.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
                ->where('pendaftaran.id_users', $userId)
                ->where('pendaftaran.id_kelas', $id_kelas)
                ->groupStart()
                    ->where('pendaftaran.status', 'Disetujui')
                    ->orWhere('pendaftaran.status_pembayaran', 'valid')
                ->groupEnd()
                ->first();
        } else {
            $pendaftaran = $this->approvedEnrollment();
        }
        $hasilUjian = null;
        $sertifikat = null;
        
        if ($pendaftaran) {
            $hasilUjian = (new HasilUjianModel())
                ->where('id_kelas', $pendaftaran['id_kelas'])
                ->where('id_users', $this->userId())
                ->orderBy('id_nilai_ujian', 'DESC')
                ->first();
                
            $sertifikat = (new \App\Models\SertifikatModel())
                ->where('id_kelas', $pendaftaran['id_kelas'])
                ->where('id_user', $this->userId())
                ->first();
        }

        $statusLulus = (bool) ($hasilUjian && $hasilUjian['status_kelulusan'] === 'lulus');
        $db = \Config\Database::connect();
        $statusAngket = (bool) ($pendaftaran && $db->table('angket_penilaian')->where('id_peserta', $this->userId())->where('id_kelas', $pendaftaran['id_kelas'])->get()->getRow());

        return view('peserta/sertifikat', [
            'statusLulus' => $statusLulus,
            'statusAngket' => $statusAngket,
            'sertifikatTarget' => $statusLulus && $statusAngket,
            'sertifikat' => $sertifikat,
        ]);
    }

    public function downloadSertifikat($id)
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $sertifikatModel = new SertifikatModel();

    $sertifikat = $sertifikatModel
        ->where('id_sertifikat', $id)
        ->where('id_user', $this->userId())
        ->first();

    if (!$sertifikat) {
        return redirect()->back()
            ->with('error', 'Sertifikat tidak ditemukan.');
    }

    if (empty($sertifikat['file_sertifikat'])) {
        return redirect()->back()
            ->with('error', 'File sertifikat belum tersedia.');
    }

    $namaFile = $sertifikat['file_sertifikat'];

    $path = FCPATH . 'uploads/sertifikat/' . $namaFile;

    if (!is_file($path)) {
        return redirect()->back()
            ->with('error', 'File sertifikat tidak ditemukan di server.');
    }

    return $this->response->download($path, null);
}

    public function absensi()
    {
        $this->ensureAbsensiGpsColumns();

        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        if (! $pendaftaran) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Anda belum memiliki kelas yang disetujui.');
        }

        // Resolusi lokasi pelatihan & koordinat GPS dinamis
        $lokasiInfo = $this->resolveLokasiPelatihan(
            $pendaftaran['lokasi_pelatihan'] ?? null,
            $pendaftaran['metode_pembelajaran'] ?? null
        );

        // Ambil jadwal kelas
        $jadwal = (new JadwalModel())
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->orderBy('pertemuan_ke', 'ASC')
            ->findAll();

        $absensiModel = new AbsensiModel();
        foreach ($jadwal as &$item) {
            $idJadwal = (int) ($item['id_jadwal'] ?? $item['id_jadwal_kelas'] ?? 0);
            $item['absensi'] = $absensiModel
                ->where('id_user', $this->userId())
                ->groupStart()
                    ->where('id_jadwal_kelas', $idJadwal)
                    ->orWhere('id_jadwal', $idJadwal)
                ->groupEnd()
                ->first();
        }

    // ==========================================
    // Evaluasi Status Kelulusan Seluruh Ujian
    // ==========================================
    $sudah_ujian = false;
    $db = \Config\Database::connect();

    $semuaUjian = $db->table('ujian')
        ->where('id_kelas', $pendaftaran['id_kelas'])
        ->get()
        ->getResultArray();

    if (!empty($semuaUjian)) {
        $lulusSemua = true;

        foreach ($semuaUjian as $u) {
            $hasilUjian = $db->table('nilai_ujian')
                ->where('id_users', $this->userId())
                ->where('id_ujian', $u['id_ujian'])
                ->orderBy('id_nilai_ujian', 'DESC')
                ->get()
                ->getRowArray();

            if (!$hasilUjian || $hasilUjian['status_kelulusan'] !== 'lulus') {
                $lulusSemua = false;
                break;
            }
        }

        $sudah_ujian = $lulusSemua;
    }

    // SIMULASI SEMENTARA - HAPUS SETELAH TESTING
    // Bypass dibuka untuk SEMUA PESERTA (seperti Elis) agar bisa menguji angket
    $isDev = (
        (defined('ENVIRONMENT') && ENVIRONMENT === 'development') ||
        (isset($_SERVER['CI_ENVIRONMENT']) && $_SERVER['CI_ENVIRONMENT'] === 'development') ||
        (getenv('CI_ENVIRONMENT') === 'development')
    );

    if ($isDev) {
        $sudah_ujian = true;
    }

    // ==========================================
    // Cek Pengisian Angket
    // ==========================================
    $sudah_isi_angket = (bool) $db->table('jawaban_angket')
        ->where('id_siswa', $this->userId())
        ->get()
        ->getRow();

    return view('peserta/absensi', [
        'kelas'            => $pendaftaran,
        'pendaftaran'      => $pendaftaran,
        'lokasiInfo'       => $lokasiInfo,
        'jadwal'           => $jadwal,
        'sudah_ujian'      => $sudah_ujian,
        'sudah_isi_angket' => $sudah_isi_angket,
    ]);
    }

    public function simpanAbsensi()
    {
        return $this->prosesAbsen();
    }


public function riwayatAbsensi()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $pendaftaran = $this->approvedEnrollment();

    $jadwal = [];

    if ($pendaftaran) {
        $jadwal = (new JadwalModel())
            ->select('jadwal.*, jadwal.absensi_dibuka')
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->orderBy('pertemuan_ke', 'ASC')
            ->findAll();
    }

    $absensiModel = new AbsensiModel();

    $jumlahHadir = 0;
    $jumlahIzin  = 0;
    $jumlahAlpa  = 0;

    foreach ($jadwal as &$item) {
        $idJadwal = (int) ($item['id_jadwal'] ?? $item['id_jadwal_kelas'] ?? 0);
        $item['absensi'] = $absensiModel
            ->where('id_user', $this->userId())
            ->groupStart()
                ->where('id_jadwal_kelas', $idJadwal)
                ->orWhere('id_jadwal', $idJadwal)
            ->groupEnd()
            ->first();

        $status = $item['absensi']['status'] ?? null;

        if ($status === 'hadir') {
            $jumlahHadir++;
        }

        if ($status === 'izin') {
            $jumlahIzin++;
        }

        if ($status === 'alpa') {
            $jumlahAlpa++;
        }
    }

    $totalPertemuan = count($jadwal);

    return view('peserta/riwayat_absensi', [
        'jadwal'              => $jadwal,
        'totalPertemuan'      => $totalPertemuan,
        'jumlahHadir'         => $jumlahHadir,
        'jumlahIzin'          => $jumlahIzin,
        'jumlahAlpa'          => $jumlahAlpa,
        'persentaseKehadiran' => $totalPertemuan > 0
            ? round(($jumlahHadir / $totalPertemuan) * 100)
            : 0,
    ]);
}
public function pengaturan()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();
    $userId = $this->userId();


    // =========================================================
    // 1. AMBIL DATA AKUN PESERTA
    // =========================================================
    $user = $db->table('users')
        ->where('id_users', $userId)
        ->get()
        ->getRowArray();

    // =========================================================
// 2. AMBIL DATA PENDAFTARAN PESERTA YANG SEDANG LOGIN
// =========================================================
$pendaftaran = $db->table('pendaftaran')
    ->where('id_users', $userId)
    ->orderBy('id_pendaftaran', 'DESC')
    ->get()
    ->getRowArray();

// Jika belum ditemukan berdasarkan id_users,
// cari berdasarkan email akun yang sedang login
if (!$pendaftaran && !empty($user['email'])) {
    $pendaftaran = $db->table('pendaftaran')
        ->where('email', $user['email'])
        ->orderBy('id_pendaftaran', 'DESC')
        ->get()
        ->getRowArray();
}

    // =========================================================
    // 3. AMBIL DATA KELAS
    // =========================================================
    $kelas = null;

    if ($pendaftaran) {
        $kelas = $db->table('pendaftaran')
            ->select('pendaftaran.*, kelas.*, mentor.nama_mentor')
            ->join(
                'kelas',
                'kelas.id_kelas = pendaftaran.id_kelas',
                'left'
            )
            ->join(
                'mentor',
                'mentor.id_mentor = kelas.id_mentor',
                'left'
            )
            ->where(
                'pendaftaran.id_pendaftaran',
                $pendaftaran['id_pendaftaran']
            )
            ->get()
            ->getRowArray();
    }

    // =========================================================
    // 4. AMBIL DATA JADWAL & ABSENSI
    // =========================================================
    $jadwal = [];

    if ($kelas) {

        $jadwal = (new JadwalModel())
            ->select('jadwal.*, jadwal.absensi_dibuka')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('pertemuan_ke', 'ASC')
            ->findAll();
    }

    $jumlahHadir = 0;

    foreach ($jadwal as &$item) {
        $idJadwal = (int) ($item['id_jadwal'] ?? $item['id_jadwal_kelas'] ?? 0);
        $absensi = $db->table('absensi')
            ->where('id_user', $userId)
            ->groupStart()
                ->where('id_jadwal_kelas', $idJadwal)
                ->orWhere('id_jadwal', $idJadwal)
            ->groupEnd()
            ->get()
            ->getRowArray();
        $item['absensi'] = $absensi;

        if (($absensi['status'] ?? null) === 'hadir') {
            $jumlahHadir++;
        }
    }

    unset($item);

    // =========================================================
    // 5. HITUNG KEHADIRAN
    // =========================================================
    $totalPertemuan = count($jadwal);

    $persentaseKehadiran = $totalPertemuan > 0
        ? round(($jumlahHadir / $totalPertemuan) * 100)
        : 0;

    // =========================================================
    // 6. DATA TAMBAHAN
    // =========================================================
    $sudahIsiAngket = false;
    $sertifikatTerbit = false;

    // =========================================================
    // 7. KIRIM KE VIEW
    // =========================================================
    return view('peserta/pengaturan', [

        'user'                => $user,

        // Data utama peserta dari tabel pendaftaran
        'pendaftaran'         => $pendaftaran,

        // Data kelas untuk kebutuhan lain
        'kelas'               => $kelas,

        'jadwal'              => $jadwal,

        'totalPertemuan'      => $totalPertemuan,

        'jumlahHadir'         => $jumlahHadir,

        'persentaseKehadiran' => $persentaseKehadiran,

        'sudahIsiAngket'      => $sudahIsiAngket,

        'sertifikatTerbit'   => $sertifikatTerbit,
    ]);
}
public function updateProfil()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    // =========================================================
    // ID PESERTA YANG SEDANG LOGIN
    // =========================================================
    $userId = $this->userId();

    if (!$userId) {
        return redirect()->to(base_url('login'))
            ->with('error', 'Silakan login terlebih dahulu.');
    }


    // =========================================================
    // 1. AMBIL DATA USER
    // =========================================================
    $user = $db->table('users')
        ->where('id_users', $userId)
        ->get()
        ->getRowArray();

    if (!$user) {
        return redirect()->to(base_url('pelatihan/pengaturan'))
            ->with('error', 'Data pengguna tidak ditemukan.');
    }


    // =========================================================
    // 2. CARI DATA PENDAFTARAN PESERTA
    // =========================================================
    $pendaftaran = null;

// Cari berdasarkan email terlebih dahulu
if (!empty($user['email'])) {
    $pendaftaran = $db->table('pendaftaran')
        ->where('email', $user['email'])
        ->orderBy('id_pendaftaran', 'DESC')
        ->get()
        ->getRowArray();
}

// Jika tidak ditemukan, baru cari berdasarkan id_users
if (!$pendaftaran) {
    $pendaftaran = $db->table('pendaftaran')
        ->where('id_users', $userId)
        ->orderBy('id_pendaftaran', 'DESC')
        ->get()
        ->getRowArray();
}


    // =========================================================
// 3. DATA YANG DISIMPAN KE USERS
// =========================================================
$dataUser = [
    'nama'          => $this->request->getPost('nama'),
    'no_hp'         => $this->request->getPost('no_hp'),
    'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
];

// Email hanya diperbarui jika memang berubah
$emailBaru = trim($this->request->getPost('email'));
$emailLama = trim($user['email'] ?? '');

if ($emailBaru !== $emailLama) {

    // Pastikan email tidak dipakai akun peserta lain
    $emailDipakai = $db->table('users')
        ->where('email', $emailBaru)
        ->where('id_users !=', $userId)
        ->get()
        ->getRowArray();

    if ($emailDipakai) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Email tersebut sudah digunakan oleh akun lain.');
    }

    $dataUser['email'] = $emailBaru;
}


    // =========================================================
    // 4. UPLOAD FOTO PROFIL
    // =========================================================
    $fileFoto = $this->request->getFile('foto');

    if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {

        // Maksimal 2 MB
        if ($fileFoto->getSize() > 2 * 1024 * 1024) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ukuran foto maksimal 2 MB.');
        }

        $folderFoto = 'uploads/profil/';

        // Buat folder jika belum ada
        if (!is_dir(FCPATH . $folderFoto)) {
            mkdir(FCPATH . $folderFoto, 0777, true);
        }

        // Nama file random agar tidak bentrok
        $namaFoto = $fileFoto->getRandomName();

        $fileFoto->move(
            FCPATH . $folderFoto,
            $namaFoto
        );

        // Simpan nama file ke users
        $dataUser['foto_profil'] = $namaFoto;
    }


    // =========================================================
    // 5. UPDATE USERS
    // =========================================================
    $db->table('users')
        ->where('id_users', $userId)
        ->update($dataUser);


    // =========================================================
    // 6. UPDATE DATA PRIBADI DI PENDAFTARAN
    // =========================================================
    if ($pendaftaran) {

       $dataPendaftaran = [
    'nama'                => $this->request->getPost('nama'),
    'email'               => $this->request->getPost('email'),
    'no_hp'               => $this->request->getPost('no_hp'),
    'jenis_kelamin'       => $this->request->getPost('jenis_kelamin'),
    'ttl'                 => $this->request->getPost('ttl'),
    'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
    'asal_instansi' => $this->request->getPost('asal_instansi'),
    'semester'      => $this->request->getPost('semester'),
    'alamat'              => $this->request->getPost('alamat'),
    'updated_at'          => date('Y-m-d H:i:s'),
];

        $db->table('pendaftaran')
            ->where(
                'id_pendaftaran',
                $pendaftaran['id_pendaftaran']
            )
            ->update($dataPendaftaran);
    }


    // =========================================================
    // 7. UPDATE SESSION
    // =========================================================
    session()->set([
        'nama' => $dataUser['nama'],
    ]);

    if (!empty($dataUser['foto_profil'])) {
        session()->set(
            'foto',
            $dataUser['foto_profil']
        );
    }


    // =========================================================
    // 8. KEMBALI KE PENGATURAN
    // =========================================================
    return redirect()
        ->to(base_url('pelatihan/pengaturan'))
        ->with('success', 'Profil berhasil diperbarui.');
}
    public function ubahPassword()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $userModel = new UserModel();
    $user = $userModel->find($this->userId());

    if (!$user) {
        return redirect()->to(base_url('pelatihan/pengaturan'))
            ->with('error', 'Data akun tidak ditemukan.');
    }

    return view('peserta/ubah_password', [
        'user' => $user
    ]);
}

public function updatePassword()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    // ID akun peserta yang sedang login
    $userId = $this->userId();

    $userModel = new UserModel();
    $user = $userModel->find($userId);

    if (!$user) {
        return redirect()->to(base_url('pelatihan/pengaturan'))
            ->with('error', 'Data akun tidak ditemukan.');
    }

    // Ambil data dari form
    $passwordLama = $this->request->getPost('password_lama');
    $passwordBaru = $this->request->getPost('password_baru');
    $konfirmasi   = $this->request->getPost('konfirmasi_password');

    // Cek password lama
    if (!password_verify($passwordLama, $user['password'])) {
        return redirect()->back()
            ->with('error', 'Password lama salah.');
    }

    // Minimal 8 karakter
    if (strlen($passwordBaru) < 8) {
        return redirect()->back()
            ->with('error', 'Password baru minimal 8 karakter.');
    }

    // Cek konfirmasi password
    if ($passwordBaru !== $konfirmasi) {
        return redirect()->back()
            ->with('error', 'Konfirmasi password baru tidak sesuai.');
    }

    // Jangan menggunakan password lama sebagai password baru
    if (password_verify($passwordBaru, $user['password'])) {
        return redirect()->back()
            ->with('error', 'Password baru harus berbeda dari password lama.');
    }

    // Simpan password baru ke akun yang sedang login
    $userModel->update($userId, [
        'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
    ]);

    return redirect()
        ->to(base_url('pelatihan/pengaturan'))
        ->with('success', 'Password berhasil diubah.');
}
    public function logout()
    {
        // Menghapus semua data session yang aktif
        session()->destroy();

        // Arahkan kembali ke halaman login dengan pesan sukses
        return redirect()->to(base_url('pelatihan/login'))->with('success', 'Anda telah berhasil keluar.');
    }
}

