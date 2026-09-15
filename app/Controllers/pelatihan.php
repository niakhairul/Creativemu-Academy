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

    // 1. Ambil format NIS otomatis: Tahun + Bulan + Urutan (YYYYMMXXX, contoh: 202609001)
    $bukuIndukModel = new \App\Models\BukuIndukModel();
    $nisBaru = $bukuIndukModel->generateNis(date('Ym'));

    // 4. Masukkan data ke database termasuk NIS baru
    $dataSimpan = [
        'id_kelas'          => $this->request->getPost('id_kelas'),
        'nama'              => $this->request->getPost('nama'),
        'email'             => $this->request->getPost('email'),
        'lokasi_pelatihan' => $this->request->getPost('lokasi_pelatihan'), // Pastikan ini ad
        'no_hp'             => $this->request->getPost('no_hp'),
        'nis'               => $nisBaru, // <--- NIS otomatis masuk di sini
        'status_pembayaran' => 'pending',
        // Sesuaikan input form lainnya di bawah ini...
    ];
    

    $pendaftaranModel->insert($dataSimpan);

    return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('success', 'Pendaftaran berhasil! NIS Anda: ' . $nisBaru);
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
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.deskripsi, kelas.tipe_kelas, kelas.lokasi_media, kelas.tanggal_mulai_kelas, kelas.jumlah_pertemuan, kelas.ringkasan, kelas.thumbnail, mentor.nama_mentor')
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
    $userId = $session->get('id_users');
    $userEmail = $session->get('email'); 

    $pendaftaranModel = new \App\Models\PendaftaranModel();
    $userModel = new \App\Models\UserModel(); 
    $jadwalModel = new \App\Models\JadwalModel();

    // 1. Cari data pendaftaran berdasarkan id_users ATAU email, sekaligus JOIN ke tabel kelas & mentor
    $pendaftaran = null;
    if ($userId) {
        $pendaftaran = $pendaftaranModel->select('pendaftaran.*, kelas.nama_kelas, kelas.tanggal_mulai_kelas as jadwal_kelas, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $userId)
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();
    }
    
    if (!$pendaftaran && $userEmail) {
        $pendaftaran = $pendaftaranModel->select('pendaftaran.*, kelas.nama_kelas, kelas.tanggal_mulai_kelas as jadwal_kelas, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.email', $userEmail)
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();
        
        if ($pendaftaran && empty($pendaftaran['id_users']) && $userId) {
            $pendaftaranModel->update($pendaftaran['id_pendaftaran'], ['id_users' => $userId]);
            $pendaftaran['id_users'] = $userId;
        }
    }

    if ($pendaftaran) {
        // Mapping jadwal dari tanggal mulai kelas atau pendaftaran
        $pendaftaran['jadwal'] = $pendaftaran['jadwal_kelas'] ?? $pendaftaran['tanggal_mulai_kelas'] ?? '-';
        // Pastikan nama mentor ada fallback-nya jika belum di-set di relasi kelas
        $pendaftaran['nama_mentor'] = $pendaftaran['nama_mentor'] ?? 'Mentor Belum Ditentukan';
    }

    // 2. Ambil data user yang sedang login
    $userData = $userModel->find($userId);
    if (!$userData && $userEmail) {
        $userData = $userModel->where('email', $userEmail)->first();
    }

    // 3. Ambil data list jadwal pelatihan berdasarkan id_kelas peserta yang sedang aktif
    // Ambil data list jadwal pelatihan berdasarkan id_kelas peserta 
    // DAN pastikan kolom 'materi' tidak kosong (sudah diisi oleh mentor)
    $list_jadwal = [];
if ($pendaftaran && !empty($pendaftaran['id_kelas'])) {
    $list_jadwal = $jadwalModel->select('jadwal.*, jadwal.absensi_dibuka')
                               ->where('id_kelas', $pendaftaran['id_kelas'])
                               ->orderBy('pertemuan_ke', 'ASC')
                               ->findAll();
}
    

    // 4. Masukkan 'list_jadwal' ke dalam array data yang dikirim ke view
    $data = [
        'title'       => 'Dashboard Peserta',
        'pendaftaran' => $pendaftaran,
        'user'        => $userData ?? ['nama' => session()->get('nama') ?? 'Peserta'],
        'list_jadwal' => $list_jadwal, // <--- Ini wajib ada agar terbaca di file view
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

        // 2. Ambil seluruh kelas yang berstatus aktif dari database
        $kelasBuilder = $db->table('kelas')
            ->select('kelas.*, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('LOWER(kelas.status)', 'aktif');

        // Jika user login, kecualikan kelas yang sudah pernah didaftarkan
        if (!empty($takenClassIds)) {
            $kelasBuilder->whereNotIn('kelas.id_kelas', $takenClassIds);
        }

        $availableClasses = $kelasBuilder->orderBy('kelas.id_kelas', 'DESC')->get()->getResultArray();

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
            $userData['status']              = $statusDB;
            $userData['status_locked']       = !empty($statusDB);
        }

        $data = [
            'title'          => 'Formulir Pendaftaran Pelatihan - Creativemu Academy',
            'kelas'          => $selectedKelas,
            'kelasList'      => $availableClasses,
            'user'           => $userData,
            'isStatusLocked' => $userData['status_locked'],
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
        // 2. VALIDASI BACKEND FIELD WAJIB
        // =========================================================
        $nama               = trim((string) $this->request->getPost('nama'));
        $email              = trim((string) $this->request->getPost('email'));
        $noHp               = trim((string) $this->request->getPost('no_hp'));
        $alamat             = trim((string) $this->request->getPost('alamat'));
        $ttl                = trim((string) $this->request->getPost('ttl'));
        $jenisKelamin       = trim((string) $this->request->getPost('jenis_kelamin'));
        $pendidikanTerakhir = trim((string) $this->request->getPost('pendidikan_terakhir'));
        $metodePembayaran   = trim((string) $this->request->getPost('metode_pembayaran'));
        $metodePembelajaran = strtolower(trim((string) $this->request->getPost('metode_pembelajaran')));
        $jenisKelas         = trim((string) $this->request->getPost('jenis_kelas')) ?: 'Reguler';
        $kategoriKelas      = trim((string) $this->request->getPost('kategori_kelas')) ?: ($kelas['kategori'] ?? 'Basic Pelatihan');

        if (empty($nama) || empty($email) || empty($noHp) || empty($alamat) || empty($ttl) || empty($jenisKelamin) || empty($pendidikanTerakhir) || empty($metodePembayaran)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon lengkapi seluruh kolom formulir yang bertanda bintang (*).');
        }

        // Aturan Status: Jika akun login sudah punya data status di database, kunci nilai tersebut
        $statusPeserta = $this->request->getPost('pilihan_status') ?: $this->request->getPost('status');
        if ($userId) {
            $lastReg = $db->table('pendaftaran')
                ->where('id_users', $userId)
                ->where('status IS NOT NULL')
                ->where("status != ''")
                ->whereNotIn('LOWER(status)', ['pending', 'disetujui', 'ditolak', 'menunggu'])
                ->orderBy('id_pendaftaran', 'DESC')
                ->get()
                ->getRowArray();
            if (!empty($lastReg['status'])) {
                $statusPeserta = $lastReg['status'];
            }
        }
        if (empty($statusPeserta)) {
            $statusPeserta = 'Umum';
        }

        // Tempat pelatihan untuk offline
        $lokasiPelatihan = ($metodePembelajaran === 'offline') ? $this->request->getPost('pilihan_lokasi') : 'Online / Daring';
        if ($metodePembelajaran === 'offline' && empty($lokasiPelatihan)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pilihan tempat pelatihan offline wajib dipilih.');
        }

        // =========================================================
        // 3. UPLOAD PAS FOTO (OPSIONAL)
        // =========================================================
        $namaFoto = null;
        $fileFoto = $this->request->getFile('pas_foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $ext = strtolower($fileFoto->getClientExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                return redirect()->back()->withInput()->with('error', 'Format pas foto harus berformat JPG, JPEG, atau PNG.');
            }
            if ($fileFoto->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran pas foto maksimal 2 MB.');
            }

            $folderFoto = 'uploads/foto/';
            if (!is_dir(FCPATH . $folderFoto)) {
                mkdir(FCPATH . $folderFoto, 0777, true);
            }
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . $folderFoto, $namaFoto);
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
        // Cek apakah akun peserta sudah memiliki NIS dari pendaftaran sebelumnya
        $existingNis = null;
        if ($userId) {
            $prevWithNis = $db->table('pendaftaran')
                ->where('id_users', $userId)
                ->where('nis IS NOT NULL')
                ->where("nis != ''")
                ->orderBy('id_pendaftaran', 'DESC')
                ->get()
                ->getRowArray();
            if (!empty($prevWithNis['nis'])) {
                $existingNis = $prevWithNis['nis'];
            }
        }

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
                    ->with('error', 'Gagal memproses pendaftaran: ' . json_encode($errors));
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
    
    $pendaftaran = $pendaftaranModel->find($id_pendaftaran);

    if ($pendaftaran) {
        // 1. Cek apakah peserta sudah punya akun users berdasarkan emailnya
        $existingUser = $db->table('users')->where('email', $pendaftaran['email'])->get()->getRowArray();
        
        if ($existingUser) {
            $userId = $existingUser['id_users'];
        } else {
            // Jika belum punya akun, buatkan akun baru secara otomatis
            // Password default diset '123456' (peserta bisa mengganti nanti melalui menu pengaturan)
            $userData = [
                'nama'          => $pendaftaran['nama'],
                'email'         => $pendaftaran['email'],
                'no_hp'         => $pendaftaran['no_hp'],
                'jenis_kelamin' => $pendaftaran['jenis_kelamin'],
                'password'      => password_hash('123456', PASSWORD_DEFAULT), 
            ];
            
            $db->table('users')->insert($userData);
            $userId = $db->insertID(); // Ambil ID user yang baru saja dibuat
        }

        // 2. Generate NIS jika belum ada
        if (empty($pendaftaran['nis'])) {
            $tanggalHariIni = date('Ymd');

            $pendaftaranTerakhir = $pendaftaranModel
                ->like('nis', $tanggalHariIni, 'after')
                ->orderBy('id_pendaftaran', 'DESC')
                ->first();

            if ($pendaftaranTerakhir && !empty($pendaftaranTerakhir['nis'])) {
                $urutanTerakhir = (int) substr($pendaftaranTerakhir['nis'], -3);
                $urutanBaru = $urutanTerakhir + 1;
            } else {
                $urutanBaru = 1;
            }

            $nisBaru = $tanggalHariIni . str_pad($urutanBaru, 3, '0', STR_PAD_LEFT);
        } else {
            $nisBaru = $pendaftaran['nis'];
        }

        // 3. Update data pendaftaran: masukkan id_users yang baru terhubung, ubah status jadi disetujui & simpan NIS
        $pendaftaranModel->update($id_pendaftaran, [
            'id_users'            => $userId,
            'status_pembayaran'   => 'valid',
            'status'              => 'Disetujui',
            'nis'                 => $nisBaru
        ]);

        return redirect()->back()->with('success', 'Pendaftaran disetujui, Akun peserta aktif, dan NIS berhasil dibuat: ' . $nisBaru);
    }

    return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
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

    
    public function kelas()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    // Ambil data kelas & mentor
    $kelas = (new PendaftaranModel())
        ->select('pendaftaran.*, kelas.*, mentor.nama_mentor')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('pendaftaran.id_users', $this->userId())
        ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
        ->first();

    $db = \Config\Database::connect();

    // Ambil data jadwal
    $jadwal = [];

    if ($kelas) {
        $jadwal = (new JadwalModel())
            ->select('jadwal.*, jadwal.absensi_dibuka')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('pertemuan_ke', 'ASC')
            ->findAll();
    }
    // Ambil data ujian berdasarkan kelas peserta
    $ujian = [];

    if ($kelas) {
        $ujian = $db->table('ujian')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('id_ujian', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($ujian as &$itemUjian) {
            $itemUjian['jawaban'] = $db->table('jawaban_ujian')
                ->where('id_ujian', $itemUjian['id_ujian'])
                ->where('id_user', $this->userId())
                ->get()
                ->getRowArray();
        }
        unset($itemUjian);
    }

    // Ambil materi yang benar-benar terhubung dengan jadwal kelas peserta
$materi = [];

if ($kelas && !empty($jadwal)) {
    $idJadwalKelas = array_column($jadwal, 'id_jadwal');

    $materi = $db->table('materi')
        ->where('id_kelas', $kelas['id_kelas'])
        ->whereIn('id_jadwal_kelas', $idJadwalKelas)
        ->orderBy('id_jadwal_kelas', 'ASC')
        ->get()
        ->getResultArray();
}

    // Hitung absensi dan hubungkan materi dengan pertemuan
    // Hitung absensi dan hubungkan materi dengan pertemuan
    $jumlahHadir = 0;

    foreach ($jadwal as &$item) {

        // Sesuaikan 'id' di bawah ini dengan nama primary key di tabel jadwal Anda (misal: 'id' atau 'id_jadwal_kelas')
        $idJadwal = $item['id_jadwal'] ?? $item['id'] ?? null;

        // Cari absensi peserta pada pertemuan ini
        $absensi = null;
        if ($idJadwal) {
           $absensi = $db->table('absensi')
    ->where('id_jadwal_kelas', $idJadwal)
    ->where('id_user', $this->userId())
    ->get()
    ->getRowArray();
        }

        $item['absensi'] = $absensi;

        // Cari materi yang terkait dengan jadwal/pertemuan ini
        $item['materi'] = null;

       foreach ($materi as $materiItem) {
    if (
        isset($materiItem['id_jadwal_kelas']) &&
        $materiItem['id_jadwal_kelas'] == $idJadwal
    ) {
        $item['materi'] = $materiItem;
        break;
    }
}

        // Materi hanya terbuka jika peserta sudah hadir
        $item['materi_terbuka'] =
            (($absensi['status'] ?? null) === 'hadir');

        if (($absensi['status'] ?? null) === 'hadir') {
            $jumlahHadir++;
        }
    }

    unset($item);

    $totalPertemuan = count($jadwal);

    $persentaseKehadiran = $totalPertemuan > 0
        ? round(($jumlahHadir / $totalPertemuan) * 100)
        : 0;

    // Status angket dan sertifikat
    $sudahIsiAngket = false;
    $sertifikatAcademy = false;

    // Kirim data ke halaman KBM
    return view('peserta/kelas', [
        'kelas'               => $kelas,
        'jadwal'              => $jadwal,
        'materi'              => $materi,
        'ujian'               => $ujian,
        'totalPertemuan'      => $totalPertemuan,
        'jumlahHadir'         => $jumlahHadir,
        'persentaseKehadiran' => $persentaseKehadiran,
        'sudahIsiAngket'      => $sudahIsiAngket,
        'sertifikatAcademy'   => $sertifikatAcademy,
    ]);
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
    $kelasModel = new \App\Models\KelasModel();

    // Harus mengambil banyak data (array multidimensi)
    $data['kelas'] = $kelasModel->getKelasWithMentor(); 

    return view('peserta/daftar_kelas', $data);
}

    public function detail($id)
{
    $kelasModel = new \App\Models\KelasModel();

    // Mengambil data kelas berdasarkan ID beserta data mentornya
    $data['kelas'] = $kelasModel
        ->select('kelas.*, mentor.nama_mentor')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->find($id);

    // Jika data kelas tidak ditemukan
    if (empty($data['kelas'])) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            "Kelas dengan ID $id tidak ditemukan."
        );
    }

    // Hitung jumlah peserta yang sudah disetujui Admin
    $db = \Config\Database::connect();

    $jumlahDisetujui = $db->table('pendaftaran')
        ->where('id_kelas', $id)
        ->where('status_pembayaran', 'valid')
        ->countAllResults();

    // Hitung kapasitas yang masih tersedia
    $data['kelas']['kapasitas_tersedia'] = max(
        0,
        (int) $data['kelas']['kapasitas'] - $jumlahDisetujui
    );

    // Tampilkan ke view detail
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

    public function kbm()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (! $kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas Anda belum disetujui admin.');
        }

        $db = \Config\Database::connect();

        // 1. Ambil data jadwal berdasarkan kelas
        $jadwal = (new JadwalModel())
            ->select('jadwal.*, jadwal.absensi_dibuka')
            ->where('id_kelas', $kelas['id_kelas'])
            ->orderBy('pertemuan_ke', 'ASC')
            ->findAll();

        $absensiModel = new AbsensiModel();
        $jumlahHadir = 0;

        // 2. Cek status absensi per jadwal untuk user yang login
        foreach ($jadwal as &$item) {
            $idJadwal = $item['id_jadwal'] ?? $item['id'] ?? null;
            
            $absensi = null;
            if ($idJadwal) {
                $absensi = $absensiModel
    ->where('id_jadwal_kelas', $idJadwal)
    ->where('id_user', $this->userId())
    ->first();
            }

            $item['absensi'] = $absensi;

            if (($absensi['status'] ?? null) === 'hadir') {
                $jumlahHadir++;
            }
        }
        unset($item);

        // 3. Hitung total pertemuan dan persentase kehadiran
        $totalPertemuan = count($jadwal);
        $persentaseKehadiran = $totalPertemuan > 0
            ? round(($jumlahHadir / $totalPertemuan) * 100)
            : 0;

        // 4. Cek apakah peserta sudah mengisi angket evaluasi
        $sudahIsiAngket = (bool) $db->table('angket_penilaian')
            ->where('id_peserta', $this->userId())
            ->where('id_kelas', $kelas['id_kelas'])
            ->get()
            ->getRow();

        // 5. Cek status kelulusan / ketersediaan sertifikat (sesuaikan dengan logika tabel sertifikat/ujian Anda)
        // Contoh sederhana: bisa diatur true/false atau mengecek ke tabel kelulusan/nilai
        $sertifikatAcademy = false; // Ubah menjadi logika pengecekan database jika sudah ada tabelnya

        // 6. Kirim semua variabel yang dibutuhkan ke view 'peserta/kbm'
        return view('peserta/kbm', [
            'kelas'               => $kelas,
            'jadwal'              => $jadwal,
            'jumlahHadir'         => $jumlahHadir,
            'totalPertemuan'      => $totalPertemuan,
            'persentaseKehadiran' => $persentaseKehadiran,
            'sudahIsiAngket'      => $sudahIsiAngket,
            'sertifikatAcademy'   => $sertifikatAcademy, // <-- Ditambahkan di sini
        ]);
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


public function prosesAbsenGps()
{
    $json = $this->request->getJSON();
$userLat = $json->latitude ?? null;
$userLng = $json->longitude ?? null;
$idJadwal = $json->id_jadwal ?? null;

if (!$userLat || !$userLng || !$idJadwal) {
    return $this->response->setJSON([
        'status' => false,
        'message' => 'Data koordinat tidak lengkap.'
    ]);
}

$db = \Config\Database::connect();

// 1. Ambil titik koordinat dan radius dari tabel jadwal
$jadwal = $db->table('jadwal')
    ->select('latitude, longitude, radius_meter')
    ->where('id_jadwal', $idJadwal)
    ->get()
    ->getRowArray();

if (!$jadwal || empty($jadwal['latitude']) || empty($jadwal['longitude'])) {
    return $this->response->setJSON([
        'status' => false,
        'message' => 'Absen gagal! Koordinat GPS untuk jadwal ini belum diatur oleh admin.'
    ]);
}

$targetLat = $jadwal['latitude'];
$targetLng = $jadwal['longitude'];
$maxRadius = $jadwal['radius_meter'] ?? 100;

// 2. Hitung jarak menggunakan Haversine
$jarak = $this->hitungJarakHaversine(
    $userLat,
    $userLng,
    $targetLat,
    $targetLng
);

// 3. Validasi radius
if ($jarak > $maxRadius) {
    return $this->response->setJSON([
        'status' => false,
        'message' => 'Absen gagal! Anda berada di luar radius kelas (Jarak Anda: ' . round($jarak) . ' meter dari lokasi).'
    ]);
}

// 4. Cek apakah sudah pernah absen
$cekAbsen = $db->table('absensi')
    ->where('id_jadwal_kelas', $idJadwal)
    ->where('id_user', $this->userId())
    ->get()
    ->getRowArray();

if ($cekAbsen) {
    return $this->response->setJSON([
        'status' => false,
        'message' => 'Anda sudah melakukan absensi sebelumnya.'
    ]);
}

// 5. Simpan absensi
$db->table('absensi')->insert([
    'id_jadwal_kelas' => $idJadwal,
    'id_user'         => $this->userId(),
    'status'          => 'hadir',
    'waktu_absen'     => date('Y-m-d H:i:s')
]);

return $this->response->setJSON([
    'status' => true,
    'message' => 'Absensi berhasil dicatat! Selamat belajar.'
]);

}

public function prosesAbsen(int $idJadwal)
{
    // 1. Ambil data jadwal & koordinat
    $jadwal = $this->db->table('jadwal')
        ->where('id_jadwal', $idJadwal)
        ->get()
        ->getRowArray();

    if (!$jadwal) {
        return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
    }

    if (empty($jadwal['latitude']) || empty($jadwal['longitude'])) {
        return redirect()->back()->with(
            'error',
            'Lokasi absensi untuk sesi ini belum diatur oleh mentor.'
        );
    }

    // 2. Tangkap koordinat GPS peserta
    $userLat = $this->request->getPost('user_latitude');
    $userLng = $this->request->getPost('user_longitude');

    if (!$userLat || !$userLng) {
        return redirect()->back()->with(
            'error',
            'Gagal mendeteksi lokasi GPS Anda. Pastikan izin lokasi (GPS) di perangkat Anda aktif.'
        );
    }

    // 3. Hitung jarak
    $jarakMeter = $this->hitungJarakGPS(
        $userLat,
        $userLng,
        $jadwal['latitude'],
        $jadwal['longitude']
    );

    $radiusMaksimal = (int) ($jadwal['radius_meter'] ?? 100);

    // 4. Validasi radius
    if ($jarakMeter > $radiusMaksimal) {
        return redirect()->back()->with(
            'error',
            'Anda berada di luar radius lokasi pelatihan! Jarak Anda sekitar ' .
            round($jarakMeter) .
            ' meter dari titik pusat (Maksimal ' .
            $radiusMaksimal .
            ' meter).'
        );
    }

    // 5. Simpan absensi jika valid
    $idUser = $this->userId();

    $cekAbsen = $this->db->table('absensi')
        ->where('id_jadwal_kelas', $idJadwal)
        ->where('id_user', $idUser)
        ->get()
        ->getRowArray();

    if ($cekAbsen) {
        return redirect()->back()->with(
            'error',
            'Anda sudah melakukan absensi pada sesi ini.'
        );
    }

    $this->db->table('absensi')->insert([
        'id_jadwal_kelas' => $idJadwal,
        'id_user'         => $idUser,
        'status'          => 'hadir',
        'waktu_absen'     => date('Y-m-d H:i:s')
    ]);

    return redirect()->back()->with(
        'success',
        'Absensi berhasil! Kehadiran Anda telah tercatat.'
    );
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
        $db = \Config\Database::connect();
        $isOnline = false;

        $cleanMetode = strtolower(trim((string) $metodePembelajaran));
        $cleanNama   = strtolower(trim((string) $namaLokasi));

        if ($cleanMetode === 'online' || str_contains($cleanNama, 'online') || str_contains($cleanNama, 'daring')) {
            $isOnline = true;
        }

        // 1. Pencocokan langsung dari tabel lokasi_pelatihan jika tabel ada
        if ($db->tableExists('lokasi_pelatihan')) {
            if (!empty($namaLokasi)) {
                $exact = $db->table('lokasi_pelatihan')
                    ->where('nama_lokasi', trim($namaLokasi))
                    ->get()
                    ->getRowArray();
                if ($exact) {
                    if ($isOnline) {
                        $exact['is_online'] = 1;
                    }
                    return $exact;
                }
            }

            // 2. Pencocokan kata kunci lokasi pelatihan di tabel
            $allLokasi = $db->table('lokasi_pelatihan')->get()->getResultArray();
            foreach ($allLokasi as $lok) {
                $dbName = strtolower($lok['nama_lokasi']);
                if (
                    (!empty($cleanNama) && (str_contains($cleanNama, 'sedayu') || str_contains($cleanNama, 'kampus utama') || str_contains($cleanNama, 'bandut')) && (str_contains($dbName, 'sedayu') || str_contains($dbName, 'kampus utama'))) ||
                    (!empty($cleanNama) && (str_contains($cleanNama, 'glagahsari') || str_contains($cleanNama, 'umbulharjo') || str_contains($cleanNama, 'cabang')) && str_contains($dbName, 'glagahsari')) ||
                    (!empty($cleanNama) && (str_contains($cleanNama, 'magelang') || str_contains($cleanNama, 'sawitan')) && str_contains($dbName, 'magelang')) ||
                    (!empty($cleanNama) && (str_contains($cleanNama, 'surakarta') || str_contains($cleanNama, 'solo')) && (str_contains($dbName, 'surakarta') || str_contains($dbName, 'solo')))
                ) {
                    if ($isOnline) {
                        $lok['is_online'] = 1;
                    }
                    return $lok;
                }
            }
        }

        // 3. Fallback jika kelas Online
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

        // 4. Fallback lokasi offline: Kampus Utama Creativemu (Sedayu, Bantul)
        return [
            'nama_lokasi'  => $namaLokasi ?: 'Kampus Utama Creativemu',
            'alamat'       => 'Jl. Gn. Bulu No 89, RT.34, Bandut Lor, Argorejo, Sedayu, Bantul, Yogyakarta',
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

        // Jika belum ada jadwal ujian di database untuk kelas ini, buat entri ujian akhir standar
        if (empty($ujian)) {
            $ujian = [
                [
                    'id_ujian'    => 1,
                    'id_kelas'    => $kelas['id_kelas'],
                    'judul_ujian' => 'Ujian Akhir ' . ($kelas['nama_kelas'] ?? 'Pelatihan'),
                    'keterangan'  => 'Ujian akhir untuk mengukur pemahaman materi pelatihan ' . ($kelas['nama_kelas'] ?? '') . '.',
                    'deadline'    => null,
                ]
            ];
        }

        // Ambil riwayat nilai peserta untuk setiap ujian
        foreach ($ujian as &$item) {
            $nilaiRow = $db->table('nilai_ujian')
                ->where('id_user', $this->userId())
                ->groupStart()
                    ->where('id_ujian', $item['id_ujian'])
                    ->orWhere('id_kelas', $kelas['id_kelas'])
                ->groupEnd()
                ->orderBy('id_nilai_ujian', 'DESC')
                ->get()
                ->getRowArray();

            $item['nilai_record'] = $nilaiRow;

            // Logika kelulusan & remidi
            if ($nilaiRow) {
                $nilaiAwal    = isset($nilaiRow['nilai_awal']) ? (float) $nilaiRow['nilai_awal'] : (float) $nilaiRow['nilai'];
                $nilaiRemidi  = isset($nilaiRow['nilai_remidi']) && $nilaiRow['nilai_remidi'] !== null ? (float) $nilaiRow['nilai_remidi'] : null;
                $nilaiTerbaru = ($nilaiRemidi !== null) ? $nilaiRemidi : $nilaiAwal;

                $item['sudah_ujian']    = true;
                $item['nilai_awal']     = $nilaiAwal;
                $item['nilai_remidi']   = $nilaiRemidi;
                $item['nilai_terbaru']  = $nilaiTerbaru;
                $item['is_lulus']       = ($nilaiTerbaru >= 70);
                $item['status_teks']    = ($nilaiTerbaru >= 70) ? 'LULUS' : 'BELUM LULUS — REMIDI';
                // Tombol remidi HANYA boleh muncul jika nilai < 70%. Peserta dengan nilai >= 70% TIDAK boleh melihat tombol remidi.
                $item['bisa_remidi']    = ($nilaiTerbaru < 70);
            } else {
                $item['sudah_ujian']    = false;
                $item['nilai_awal']     = null;
                $item['nilai_remidi']   = null;
                $item['nilai_terbaru']  = null;
                $item['is_lulus']       = false;
                $item['status_teks']    = 'Belum Dikerjakan';
                $item['bisa_remidi']    = false;
            }

            // Ambil jawaban jika ada upload tugas/file
            $item['jawaban'] = $db->table('jawaban_ujian')
                ->where('id_ujian', $item['id_ujian'])
                ->where('id_user', $this->userId())
                ->get()
                ->getRowArray();
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
        ->select('pendaftaran.*, kelas.nama_kelas, kelas.deskripsi, kelas.tipe_kelas, kelas.lokasi_media, kelas.tanggal_mulai_kelas, kelas.jumlah_pertemuan, kelas.ringkasan, kelas.thumbnail, mentor.nama_mentor')
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
            ->where('id_user', $userId)
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
                'id_user'          => $userId,
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
            ->where('id_user', $this->userId())
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

        $isLulus    = ($nilaiTerbaru >= 70);
        $statusTeks = $isLulus ? 'LULUS' : 'BELUM LULUS';
        $bisaRemidi = !$isLulus; // HANYA jika nilai < 70%

        return view('peserta/hasil_ujian', [
            'kelas'           => $kelas,
            'nilaiRow'        => $nilaiRow,
            'nilaiAwal'       => $nilaiAwal,
            'nilaiRemidi'     => $nilaiRemidi,
            'nilaiTerbaru'    => $nilaiTerbaru,
            'isLulus'         => $isLulus,
            'statusTeks'      => $statusTeks,
            'bisaRemidi'      => $bisaRemidi,
            'isRemidiApplied' => ($nilaiRemidi !== null),
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
                ->where('id_user', $this->userId())
                ->orderBy('id_nilai_ujian', 'DESC')
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
            $idJadwal = (int) ($item['id_jadwal'] ?? 0);
            $item['absensi'] = $absensiModel
                ->where('id_user', $this->userId())
                ->groupStart()
                    ->where('id_jadwal_kelas', $idJadwal)
                ->groupEnd()
                ->first();
        }

        return view('peserta/absensi', [
            'pendaftaran' => $pendaftaran,
            'lokasiInfo'  => $lokasiInfo,
            'jadwal'      => $jadwal,
        ]);
    }

    public function simpanAbsensi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $idJadwal = (int) ($this->request->getPost('id_jadwal') ?? $this->request->getPost('id_jadwal_kelas'));
        if (!$idJadwal) {
            return redirect()->back()->with('error', 'Jadwal pertemuan tidak ditemukan.');
        }

        $pendaftaran = $this->approvedEnrollment();
        if (!$pendaftaran) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Anda belum memiliki kelas yang disetujui.');
        }

        // Pastikan jadwal pertemuan memang milik kelas peserta
        $jadwal = (new JadwalModel())
            ->where('id_jadwal', $idJadwal)
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal pertemuan tidak valid untuk kelas Anda.');
        }

        $absensiModel = new AbsensiModel();

        // 1. Cegah absen ganda
        $sudahAbsen = $absensiModel
            ->where('id_user', $this->userId())
            ->groupStart()
                ->where('id_jadwal', $idJadwal)
                ->orWhere('id_jadwal_kelas', $idJadwal)
            ->groupEnd()
            ->first();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi untuk pertemuan ini.');
        }

        $pilihanStatus = strtolower(trim((string) $this->request->getPost('status_absen')));
        if (!in_array($pilihanStatus, ['hadir', 'tidak hadir', 'tidak_hadir'], true)) {
            $pilihanStatus = 'hadir';
        }

        $waktuSekarang = date('Y-m-d H:i:s');

        // 2. PILIHAN TIDAK HADIR
        if ($pilihanStatus === 'tidak hadir' || $pilihanStatus === 'tidak_hadir') {
            $absensiModel->insert([
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

            return redirect()->back()->with('success', 'Status TIDAK HADIR berhasil disimpan.');
        }

        // 3. PILIHAN HADIR
        $lokasiInfo = $this->resolveLokasiPelatihan(
            $pendaftaran['lokasi_pelatihan'] ?? null,
            $pendaftaran['metode_pembelajaran'] ?? null
        );

        // A. KELAS ONLINE: Tidak memerlukan validasi fisik GPS
        if (!empty($lokasiInfo['is_online'])) {
            $absensiModel->insert([
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

            return redirect()->back()->with('success', 'Absensi HADIR kelas online berhasil disimpan.');
        }

        // B. KELAS OFFLINE: Wajib validasi GPS browser
        $gpsError = $this->request->getPost('gps_error');
        $userLat  = $this->request->getPost('latitude');
        $userLng  = $this->request->getPost('longitude');

        if ($gpsError || $userLat === null || $userLng === null || $userLat === '' || $userLng === '') {
            return redirect()->back()->with(
                'error',
                'Absensi hadir membutuhkan izin lokasi/GPS untuk memastikan Anda berada di tempat pelatihan.'
            );
        }

        $targetLat       = (float) $lokasiInfo['latitude'];
        $targetLng       = (float) $lokasiInfo['longitude'];
        $radiusToleransi = (int) ($lokasiInfo['radius_meter'] ?? 100);

        // Hitung jarak Haversine di server
        $jarakMeter = round($this->hitungJarakGPS($userLat, $userLng, $targetLat, $targetLng));

        // Validasi jarak terhadap batas toleransi radius
        if ($jarakMeter > $radiusToleransi) {
            return redirect()->back()->with(
                'error',
                'Anda berada di luar area tempat pelatihan. Absensi hadir tidak dapat dilakukan. (Jarak Anda: ' . $jarakMeter . ' meter dari ' . esc($lokasiInfo['nama_lokasi']) . ', batas toleransi: ' . $radiusToleransi . ' meter).'
            );
        }

        // Simpan data absensi HADIR beserta bukti audit lokasi
        $absensiModel->insert([
            'id_jadwal'       => $idJadwal,
            'id_jadwal_kelas' => $idJadwal,
            'id_user'         => $this->userId(),
            'status'          => 'hadir',
            'latitude'        => (float) $userLat,
            'longitude'       => (float) $userLng,
            'jarak'           => $jarakMeter,
            'waktu_absen'     => $waktuSekarang,
            'created_at'      => $waktuSekarang,
            'updated_at'      => $waktuSekarang,
        ]);

        return redirect()->back()->with(
            'success',
            'Absensi HADIR berhasil dicatat! Anda terverifikasi di area pelatihan (' . $jarakMeter . ' meter dari titik pusat).'
        );
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

        $item['absensi'] = $absensiModel
            ->where('id_jadwal', $item['id_jadwal'])
            ->where('id_user', $this->userId())
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

        $absensi = $db->table('absensi')
    ->where(
        'id_jadwal_kelas',
        $item['id_jadwal']
    )
    ->where(
        'id_user',
        $userId
    )
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
    $sertifikatAcademy = false;

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

        'sertifikatAcademy'   => $sertifikatAcademy,
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
