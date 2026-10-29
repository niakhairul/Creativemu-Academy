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

    return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))->with('success', 'Pendaftaran berhasil! Silakan menunggu validasi admin.');
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
        $db = \Config\Database::connect();
        $email = trim((string)($this->request->getGet('email') ?? ''));
        $prefill = [];

        if (!empty($email)) {
            $cleanEmail = strtolower($email);
            $p = null;
            if ($db->tableExists('pendaftaran')) {
                $p = $db->table('pendaftaran')
                    ->where('LOWER(email)', $cleanEmail)
                    ->orderBy('id_pendaftaran', 'DESC')
                    ->get()
                    ->getRowArray();
            }

            if ($p) {
                $prefill = [
                    'nama'          => $p['nama'] ?? '',
                    'email'         => $p['email'] ?? $email,
                    'no_hp'         => $p['no_hp'] ?? '',
                    'jenis_kelamin' => $p['jenis_kelamin'] ?? '',
                ];
            } else {
                $u = $db->table('users')
                    ->where('LOWER(email)', $cleanEmail)
                    ->get()
                    ->getRowArray();
                if ($u) {
                    $prefill = [
                        'nama'          => $u['nama'] ?? '',
                        'email'         => $u['email'] ?? $email,
                        'no_hp'         => $u['no_hp'] ?? '',
                        'jenis_kelamin' => $u['jenis_kelamin'] ?? '',
                    ];
                }
            }
        }

        return view('auth/register', ['prefill' => $prefill]);
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

    private function approvedEnrollment(?int $idKelas = null)
    {
        $userId = $this->userId();
        if (!$userId) {
            return null;
        }

        $builder = (new PendaftaranModel())->where('id_users', $userId);
        if ($idKelas) {
            $builder->where('id_kelas', $idKelas);
        }

        $rows = $builder->orderBy('id_pendaftaran', 'DESC')->findAll();
        if (empty($rows)) {
            $userEmail = session()->get('email');
            if ($userEmail) {
                $builderEmail = (new PendaftaranModel())->where('email', $userEmail);
                if ($idKelas) {
                    $builderEmail->where('id_kelas', $idKelas);
                }
                $rows = $builderEmail->orderBy('id_pendaftaran', 'DESC')->findAll();
            }
        }

        if (empty($rows)) {
            return null;
        }

        $apiService = new \App\Services\LaravelApiService();
        $laravelClassesMap = $this->fetchLaravelClassesMap($apiService);

        foreach ($rows as $row) {
            $enriched = $this->syncAndEnrichPendaftaranRow($row, $laravelClassesMap, $apiService);
            if (!empty($enriched['bisa_diakses'])) {
                return $enriched;
            }
        }

        return null;
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

        // 1. Ambil semua pendaftaran milik peserta yang sedang login (keamanan: isolasi per user)
        $semuaPendaftaran = [];
        if ($userId) {
            $semuaPendaftaran = $pendaftaranModel
                ->where('id_users', $userId)
                ->orderBy('id_pendaftaran', 'DESC')
                ->findAll();
        }

        if (empty($semuaPendaftaran) && $userEmail) {
            $semuaPendaftaran = $pendaftaranModel
                ->where('email', $userEmail)
                ->orderBy('id_pendaftaran', 'DESC')
                ->findAll();
        }

        if (!empty($semuaPendaftaran)) {
            usort($semuaPendaftaran, function($a, $b) {
                $aValid = in_array(strtolower($a['status_pembayaran'] ?? ''), ['valid', 'disetujui', 'lunas'], true) ? 0 : 1;
                $bValid = in_array(strtolower($b['status_pembayaran'] ?? ''), ['valid', 'disetujui', 'lunas'], true) ? 0 : 1;
                if ($aValid !== $bValid) {
                    return $aValid <=> $bValid;
                }
                return ($b['id_pendaftaran'] ?? 0) <=> ($a['id_pendaftaran'] ?? 0);
            });
        }

        // 2. Hubungkan dengan API Laravel sebagai Single Source of Truth & sinkronkan status
        $apiService = new \App\Services\LaravelApiService();
        $laravelClassesMap = $this->fetchLaravelClassesMap($apiService);

        foreach ($semuaPendaftaran as &$p) {
            $p = $this->syncAndEnrichPendaftaranRow($p, $laravelClassesMap, $apiService);
        }
        unset($p);

        // 3. Tentukan kelas aktif yang dipilih peserta
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
                // Utamakan kelas yang sudah siap diakses sebagai default kelas aktif
                foreach ($semuaPendaftaran as $p) {
                    if (!empty($p['bisa_diakses'])) {
                        $pendaftaran = $p;
                        break;
                    }
                }
            }
            if (!$pendaftaran) {
                $pendaftaran = $semuaPendaftaran[0];
            }
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
            $userData['email'] = $session->get('email') ?? ($pendaftaran['email'] ?? null) ?? null;
        }
        if (empty($userData['no_hp']) && !empty($pendaftaran['no_hp'])) {
            $userData['no_hp'] = $pendaftaran['no_hp'];
        }

        // Resolusi nomor induk siswa (NIS) peserta hanya dari pendaftaran yang sudah disetujui
        $nisPeserta = null;
        if (!empty($pendaftaran['nis']) && $pendaftaran['nis'] !== '-' && in_array($pendaftaran['kode_status'] ?? '', ['disetujui', 'siap_diakses'], true)) {
            $nisPeserta = trim($pendaftaran['nis']);
        }

        if (!$nisPeserta && !empty($semuaPendaftaran)) {
            foreach ($semuaPendaftaran as $sp) {
                if (!empty($sp['nis']) && $sp['nis'] !== '-' && in_array($sp['kode_status'] ?? '', ['disetujui', 'siap_diakses'], true)) {
                    $nisPeserta = trim($sp['nis']);
                    break;
                }
            }
        }

        if ($nisPeserta) {
            $userData['nis'] = $nisPeserta;
        }

        // 4. Ambil Jadwal Kelas dari API Laravel HANYA jika kelas aktif sudah disetujui dan siap diakses
        $list_jadwal = [];
        $isPrivatAktif = (strtolower(trim((string)($pendaftaran['jenis_kelas'] ?? ''))) === 'privat');
        if ($pendaftaran && !empty($pendaftaran['id_kelas']) && !empty($pendaftaran['bisa_diakses']) && (!$isPrivatAktif || ($pendaftaran['kode_status'] ?? '') === 'siap_diakses')) {
            $idKelasAktif = (int) $pendaftaran['id_kelas'];
            $kelasAktifDetail = $laravelClassesMap[$idKelasAktif] ?? $pendaftaran;

            $namaMentor = $pendaftaran['nama_mentor'] ?? ($kelasAktifDetail['nama_mentor'] ?? null);
            $lokasiJadwal = $pendaftaran['lokasi_pelatihan'] ?? ($kelasAktifDetail['lokasi_media'] ?? null);
            if ($lokasiJadwal === '' || $lokasiJadwal === '-') {
                $lokasiJadwal = null;
            }

            // Ambil seluruh sesi pelatihan dari data resmi Laravel & sinkronisasi
            $allSessions = $this->getSessionsAndAttendanceForClass($idKelasAktif, (int)$userId, $kelasAktifDetail);

            foreach ($allSessions as $sess) {
                $pertemuanKe = $sess['pertemuan_ke'] ?? null;
                $materiSess = (!empty($sess['materi']) && $sess['materi'] !== '-') ? $sess['materi'] : ($kelasAktifDetail['nama_kelas'] ?? $pendaftaran['nama_kelas'] ?? ('Pertemuan ' . $pertemuanKe));

                $statusSess = 'Terjadwal';
                if (!empty($sess['absensi'])) {
                    $statusSess = 'Selesai';
                } elseif (!empty($sess['absensi_dibuka']) && (int)$sess['absensi_dibuka'] === 1) {
                    $statusSess = 'Dibuka';
                }

                $list_jadwal[] = [
                    'nama_kelas'      => $pendaftaran['nama_kelas'] ?? null,
                    'materi'          => $materiSess,
                    'nama_mentor'     => $namaMentor,
                    'tanggal_mulai'   => (!empty($sess['tanggal_kbm']) && $sess['tanggal_kbm'] !== '-') ? $sess['tanggal_kbm'] : null,
                    'tanggal_selesai' => null,
                    'jam_mulai'       => (!empty($sess['waktu_mulai']) && $sess['waktu_mulai'] !== '-') ? substr($sess['waktu_mulai'], 0, 5) : null,
                    'jam_selesai'     => (!empty($sess['waktu_selesai']) && $sess['waktu_selesai'] !== '-') ? substr($sess['waktu_selesai'], 0, 5) : null,
                    'pertemuan'       => $pertemuanKe,
                    'lokasi'          => $lokasiJadwal,
                    'status'          => $statusSess,
                ];
            }
        }

        // ==========================================
        // 5. LOGIKA CEK STATUS KELULUSAN & NOTIFIKASI
        // ==========================================
        $db = \Config\Database::connect();
        $notifikasiAngket = false;
        $statusUjianKeluar = false;
        $isLulus = false;

        $effectiveUserId = (int) ($userId ?? ($userData['id_users'] ?? session()->get('id_users') ?? session()->get('id_user') ?? session()->get('id') ?? 0));

        if ($pendaftaran && !empty($pendaftaran['id_kelas']) && $effectiveUserId > 0) {
            $idKelasAktif = (int) $pendaftaran['id_kelas'];

            // 1. Ambil rekaman nilai ujian untuk user dan kelas ini
            $cekNilaiList = $db->table('nilai_ujian')
                ->where('id_users', $effectiveUserId)
                ->where('id_kelas', $idKelasAktif)
                ->orderBy('id_nilai_ujian', 'DESC')
                ->get()
                ->getResultArray();

            // 2. Jika tidak ada rekaman dengan id_kelas spesifik, cek berdasarkan tabel ujian
            if (empty($cekNilaiList)) {
                $semuaUjian = $db->table('ujian')
                    ->where('id_kelas', $idKelasAktif)
                    ->get()
                    ->getResultArray();

                if (!empty($semuaUjian)) {
                    foreach ($semuaUjian as $u) {
                        $nRow = $db->table('nilai_ujian')
                            ->where('id_users', $effectiveUserId)
                            ->where('id_ujian', $u['id_ujian'])
                            ->get()
                            ->getRowArray();
                        if ($nRow) {
                            $cekNilaiList[] = $nRow;
                        }
                    }
                }
            }

            if (!empty($cekNilaiList)) {
                $lulusSemua = true;
                $adaNilai = false;

                foreach ($cekNilaiList as $cekNilai) {
                    $statusKelulusanDb = strtolower(trim((string)($cekNilai['status_kelulusan'] ?? '')));
                    $nilaiAkhir = $cekNilai['nilai_remidi'] ?? $cekNilai['nilai_awal'] ?? $cekNilai['nilai'] ?? null;

                    // Nilai dianggap keluar jika status sudah ditentukan admin (bukan 'menunggu') atau ada nilai numerik > 0
                    if (($statusKelulusanDb !== 'menunggu' && $statusKelulusanDb !== '') || ($nilaiAkhir !== null && (float)$nilaiAkhir > 0)) {
                        $adaNilai = true;
                    }

                    if ($statusKelulusanDb === 'lulus' || ((float)$nilaiAkhir >= 70 && !in_array($statusKelulusanDb, ['tidak lulus', 'remidi', 'menunggu'], true))) {
                        // lulus untuk item ini
                    } else {
                        $lulusSemua = false;
                    }
                }

                $isRemidi = false;
                foreach ($cekNilaiList as $cekNilai) {
                    $statusKelulusanDb = strtolower(trim((string)($cekNilai['status_kelulusan'] ?? '')));
                    if ($statusKelulusanDb === 'remidi') {
                        $isRemidi = true;
                        break;
                    }
                }

                if ($adaNilai) {
                    $statusUjianKeluar = true;
                    $isLulus = $lulusSemua && !$isRemidi;

                    if ($isLulus) {
                        $sudahAngket = $db->table('angket_penilaian')
                            ->where('id_peserta', $effectiveUserId)
                            ->where('id_kelas', $idKelasAktif)
                            ->get()
                            ->getRow();

                        if (!$sudahAngket) {
                            $sudahJawabanAngket = $db->table('jawaban_angket ja')
                                ->join('angket_pertanyaan ap', 'ap.id_angket_pertanyaan = ja.id_pertanyaan', 'inner')
                                ->where('ja.id_siswa', $effectiveUserId)
                                ->where('(ap.id_kelas IS NULL OR ap.id_kelas = ' . (int)$idKelasAktif . ')', null, false)
                                ->countAllResults();

                            if ($sudahJawabanAngket === 0) {
                                $notifikasiAngket = true;
                            }
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
            'statusUjianKeluar'   => $statusUjianKeluar,
            'is_lulus'            => $isLulus,
            'is_remidi'           => $isRemidi ?? false,
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
    protected function formatKelasFromApi(array $k): array
    {
        // Instruktur asli dari Laravel
        $namaMentor = null;
        if (!empty($k['trainers']) && is_array($k['trainers']) && isset($k['trainers'][0]['name'])) {
            $namaMentor = trim($k['trainers'][0]['name']);
        }

        // Lokasi Media dari respons Laravel API
        $lokasiMedia = null;
        if (!empty($k['lokasi_media']) && is_string($k['lokasi_media'])) {
            $lokasiMedia = trim($k['lokasi_media']);
        } elseif (!empty($k['location_media']) && is_string($k['location_media'])) {
            $lokasiMedia = trim($k['location_media']);
        } elseif (!empty($k['location'])) {
            if (is_array($k['location'])) {
                $lokasiMedia = trim($k['location']['media'] ?? $k['location']['name'] ?? $k['location']['lokasi'] ?? '');
            } elseif (is_string($k['location'])) {
                $lokasiMedia = trim($k['location']);
            }
        } elseif (!empty($k['schedule']['location']) && is_string($k['schedule']['location'])) {
            $lokasiMedia = trim($k['schedule']['location']);
        }
        if ($lokasiMedia === '' || $lokasiMedia === '-') {
            $lokasiMedia = null;
        }

        // Deskripsi & Ringkasan asli
        $deskripsi = (!empty($k['description']) && trim($k['description']) !== '-') ? trim($k['description']) : null;
        $ringkasan = (!empty($k['summary']) && trim($k['summary']) !== '-') ? trim($k['summary']) : null;

        // Foto / Thumbnail asli
        $foto = !empty($k['thumbnail']) ? $k['thumbnail'] : (!empty($k['image']) ? $k['image'] : (!empty($k['foto']) ? $k['foto'] : null));

        // Kategori asli
        $kategori = !empty($k['training']['name']) ? $k['training']['name'] : (!empty($k['category']) ? $k['category'] : null);

        // Pertemuan, Kapasitas, Harga, Tanggal
        $meetings = $k['schedule']['meetings'] ?? $k['sessions_count'] ?? $k['meetings'] ?? null;
        $kapasitas = $k['capacity']['target_students'] ?? $k['capacity'] ?? null;
        $kapasitasTersedia = $k['capacity']['remaining_students'] ?? $k['available_capacity'] ?? $kapasitas;
        $hargaReguler = $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? null;
        $hargaPrivat = $k['pricing']['price'] ?? $k['private_price'] ?? null;
        $startDate = (!empty($k['schedule']['start_date']) && $k['schedule']['start_date'] !== '-') ? $k['schedule']['start_date'] : ($k['start_date'] ?? null);
        $endDate = (!empty($k['schedule']['end_date']) && $k['schedule']['end_date'] !== '-') ? $k['schedule']['end_date'] : ($k['end_date'] ?? null);
        $startTime = (!empty($k['schedule']['start_time']) && $k['schedule']['start_time'] !== '-') ? substr((string)$k['schedule']['start_time'], 0, 5) : null;
        $endTime = (!empty($k['schedule']['end_time']) && $k['schedule']['end_time'] !== '-') ? substr((string)$k['schedule']['end_time'], 0, 5) : null;

        $rawJenis = strtolower(trim((string) ($k['training']['jenis_kelas'] ?? $k['jenis_kelas'] ?? $k['type'] ?? '')));
        $jenisKelas = in_array($rawJenis, ['private', 'privat'], true) ? 'Privat' : 'Reguler';

        return [
            'id_kelas'              => $k['id'],
            'nama_kelas'            => $k['name'] ?? null,
            'thumbnail'             => $foto,
            'foto'                  => $foto,
            'kategori'              => $kategori,
            'training_id'           => $k['training']['id'] ?? ($k['training_id'] ?? null),
            'region_id'             => $k['region_id'] ?? ($k['training']['region_id'] ?? null),
            'status'                => $k['status'] ?? null,
            'deskripsi'             => $deskripsi,
            'ringkasan'             => $ringkasan,
            'nama_mentor'           => $namaMentor,
            'jumlah_pertemuan'      => $meetings,
            'kapasitas'             => $kapasitas,
            'kapasitas_tersedia'    => $kapasitasTersedia,
            'harga_reguler'         => $hargaReguler,
            'harga_privat'          => $hargaPrivat,
            'tanggal_mulai_kelas'   => $startDate,
            'tanggal_selesai_kelas' => $endDate,
            'waktu_mulai'           => $startTime,
            'waktu_selesai'         => $endTime,
            'jenis_kelas'           => $jenisKelas,
            'tipe_kelas'            => $jenisKelas,
            'lokasi_media'          => $lokasiMedia,
            'metode_pembelajaran'   => $k['schedule']['method'] ?? $k['method'] ?? null,
            'registrants'           => is_array($k['registrants'] ?? null) ? $k['registrants'] : [],
            'detail_loaded'         => array_key_exists('registrants', $k),
        ];
    }

    /**
     * Menemukan kelas Private yang sesuai di Laravel API
     * Mengikuti instruksi kantor: "membuat atau menemukan kelas Private yang sesuai di Laravel"
     */
    public function findMatchingPrivateClassInLaravel(?int $trainingId, string $trainingName = '', ?\App\Services\LaravelApiService $apiService = null): ?int
    {
        $apiService = $apiService ?? new \App\Services\LaravelApiService();
        try {
            $res = $apiService->request('GET', 'classes', ['include_private' => 'true']);
            $classes = $res['data'] ?? $res ?? [];
            if (!empty($classes) && is_array($classes)) {
                $trainingNameLower = strtolower(trim($trainingName));
                foreach ($classes as $c) {
                    $cJenis = strtolower(trim((string)($c['jenis_kelas'] ?? '')));
                    $cTrainingId = (int)($c['training']['id'] ?? ($c['training_id'] ?? 0));
                    $cName = strtolower(trim((string)($c['name'] ?? '')));

                    if (in_array($cJenis, ['private', 'privat'], true)) {
                        if ($trainingId && $cTrainingId === $trainingId) {
                            return (int)$c['id'];
                        }
                        if ($trainingNameLower !== '' && str_contains($cName, $trainingNameLower)) {
                            return (int)$c['id'];
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            log_message('warning', 'Gagal mencari kelas private di Laravel: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Mengambil peta seluruh kelas dari API Laravel (id_kelas => formatted class)
     */
    public function fetchLaravelClassesMap(?\App\Services\LaravelApiService $apiService = null): array
    {
        $apiService = $apiService ?? new \App\Services\LaravelApiService();
        $map = [];
        try {
            $response = $apiService->request('GET', 'classes', ['include_private' => 'true']);
            $kelasApi = $response['data'] ?? $response ?? [];
            if (!empty($kelasApi) && is_array($kelasApi)) {
                foreach ($kelasApi as $k) {
                    if (isset($k['id'])) {
                        $map[(int)$k['id']] = $this->formatKelasFromApi($k);
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('warning', 'Gagal memuat daftar kelas Laravel: ' . $e->getMessage());
        }
        return $map;
    }

    /**
     * Menyinkronkan data pendaftaran peserta (Reguler / Privat) ke API Laravel secara idempotent
     * menggunakan external_registration_id = REG-{id_pendaftaran} tanpa membuat data ganda.
     * Mengikuti instruksi integrasi kelas Private ke Laravel:
     * - Membaca data.id / clas_id / class_id sebagai ID kelas baru
     * - Tidak mengirim registrant ke ID kelas template jika pembuatan kelas privat gagal
     * - Memastikan X-API-KEY dan header JSON dikirim via LaravelApiService::requestWithResponse
     */
    public function syncRegistrationToLaravel(array $pendaftaran, ?array $kelas = null, ?\App\Services\LaravelApiService $apiService = null): array
    {
        $apiService = $apiService ?? new \App\Services\LaravelApiService();
        $idPendaftaran = (int) ($pendaftaran['id_pendaftaran'] ?? 0);
        if ($idPendaftaran <= 0) {
            return $pendaftaran;
        }

        $idKelas = (int) ($pendaftaran['id_kelas'] ?? 0);
        $extId = 'REG-' . $idPendaftaran;
        $jenisKelas = trim((string) ($pendaftaran['jenis_kelas'] ?? ($kelas['jenis_kelas'] ?? 'Reguler')));
        $isPrivat = in_array(strtolower($jenisKelas), ['privat', 'private'], true);

        // Jika bukan privat dan id_kelas belum ada, tidak bisa sync
        if (!$isPrivat && $idKelas <= 0) {
            return $pendaftaran;
        }

        $ciStatus = strtolower(trim((string) ($pendaftaran['status_pendaftaran'] ?? 'menunggu')));
        $ciPay    = strtolower(trim((string) ($pendaftaran['status_pembayaran'] ?? 'pending')));

        $isAccAdmin = in_array($ciStatus, ['disetujui', 'approved', 'diterima'], true) && in_array($ciPay, ['valid', 'lunas', 'paid', 'terkonfirmasi'], true);

        // =========================================================================
        // 1. ALUR KELAS PRIVAT:
        // Setiap pendaftaran siswa kelas Private yang tersimpan di CI4 membuat / mencari
        // kelas Private di Laravel, menyambungkan siswa sebagai registrant, dan menyimpan ID kelas.
        // =========================================================================
        if ($isPrivat) {
            $studentId = !empty($pendaftaran['nis']) ? trim((string)$pendaftaran['nis']) : null;
            $price     = (float) ($pendaftaran['harga_privat'] ?? $kelas['harga_privat'] ?? 1500000);
            $trainingId = !empty($kelas['training_id']) ? (int)$kelas['training_id'] : null;

            // Region ID: ambil dari data kelas/training atau default region Laravel (1)
            $regionId = !empty($kelas['region_id'])
                ? (int)$kelas['region_id']
                : (!empty($pendaftaran['region_id'])
                    ? (int)$pendaftaran['region_id']
                    : (int)env('LARAVEL_DEFAULT_REGION_ID', 1));

            // Jika id_kelas belum ada di Laravel untuk pendaftaran privat ini, buat/temukan via private-class-requests
            if ($idKelas <= 0) {
                $customMateri = trim((string) ($pendaftaran['custom_materi'] ?? ''));
                $materiPrivat = trim((string) ($pendaftaran['materi_privat'] ?? ''));
                $isCustom = in_array(strtolower($materiPrivat), ['custom materi', 'custom', 'lainnya'], true);
                $trainingName = ($isCustom && $customMateri !== '')
                    ? $customMateri
                    : ($materiPrivat !== ''
                        ? $materiPrivat
                        : ($customMateri !== ''
                            ? $customMateri
                            : ($pendaftaran['pilihan_pelatihan'] ?? $kelas['nama_kelas'] ?? 'Materi Privat')));

                $privPayload = [
                    'external_registration_id' => $extId,
                    'training_id'              => $trainingId,
                    'training_name'            => $trainingName,
                    'student_id'               => $studentId,
                    'name'                     => trim((string)($pendaftaran['nama'] ?? '')),
                    'email'                    => !empty($pendaftaran['email']) ? trim((string)$pendaftaran['email']) : null,
                    'phone'                    => !empty($pendaftaran['no_hp']) ? trim((string)$pendaftaran['no_hp']) : null,
                    'price'                    => $price,
                    'region_id'                => $regionId > 0 ? $regionId : 1,
                ];

                $resPriv = $apiService->requestWithResponse('POST', 'private-class-requests', $privPayload);

                $newClasId = null;
                if (!empty($resPriv['body']) && is_array($resPriv['body'])) {
                    $bodyData = $resPriv['body']['data'] ?? $resPriv['body'];
                    if (is_array($bodyData)) {
                        $newClasId = $bodyData['id'] ?? $bodyData['clas_id'] ?? $bodyData['class_id'] ?? null;
                    }
                }

                if (is_numeric($newClasId) && (int)$newClasId > 0) {
                    $idKelas = (int)$newClasId;
                    $pendaftaran['id_kelas'] = $idKelas;
                    \Config\Database::connect()->table('pendaftaran')
                        ->where('id_pendaftaran', $idPendaftaran)
                        ->update(['id_kelas' => $idKelas]);
                } else {
                    // Jika endpoint private-class-requests gagal, jangan lanjut mengirim registrant ke ID template
                    log_message('warning', "LaravelApiService: Gagal membuat/menemukan kelas privat Laravel untuk pendaftaran ID #{$idPendaftaran} (extId: {$extId}): " . ($resPriv['message'] ?? 'Respons tidak memuat ID kelas'));
                    $pendaftaran['sync_status'] = 'failed';
                    return $pendaftaran;
                }
            }

            // Status registrant di Laravel untuk kelas privat
            $regStatus = 'pending';
            if (in_array($ciStatus, ['ditolak', 'rejected', 'batal'], true) || in_array($ciPay, ['rejected', 'ditolak'], true)) {
                $regStatus = 'rejected';
            } elseif (in_array($ciStatus, ['disetujui', 'approved', 'diterima'], true)) {
                $regStatus = 'approved';
            }

            $payStatus = 'pending';
            if (in_array($ciPay, ['rejected', 'ditolak', 'failed'], true)) {
                $payStatus = 'rejected';
            } elseif (in_array($ciPay, ['valid', 'lunas', 'paid', 'terkonfirmasi'], true)) {
                $payStatus = 'paid';
            }

            $regPrivPayload = [
                'external_registration_id' => $extId,
                'student_id'               => $studentId,
                'nis'                      => $studentId,
                'name'                     => trim((string)($pendaftaran['nama'] ?? '')),
                'email'                    => !empty($pendaftaran['email']) ? trim((string)$pendaftaran['email']) : null,
                'phone'                    => !empty($pendaftaran['no_hp']) ? trim((string)$pendaftaran['no_hp']) : null,
                'status'                   => $regStatus,
                'payment_status'           => $payStatus,
                'registered_at'            => $pendaftaran['created_at'] ?? date('Y-m-d H:i:s'),
                'metadata'                 => [
                    'id_pendaftaran'      => $idPendaftaran,
                    'id_users'            => $pendaftaran['id_users'] ?? null,
                    'jenis_kelas'         => 'Private',
                    'kategori_kelas'      => 'Reguler',
                    'metode_pembelajaran' => $pendaftaran['metode_pembelajaran'] ?? null,
                    'lokasi_pelatihan'    => $pendaftaran['lokasi_pelatihan'] ?? null,
                    'metode_pembayaran'   => $pendaftaran['metode_pembayaran'] ?? null,
                    'status_pembayaran'   => $pendaftaran['status_pembayaran'] ?? 'pending',
                    'status_pendaftaran'  => $pendaftaran['status_pendaftaran'] ?? 'Menunggu',
                ],
            ];

            $resReg = $apiService->requestWithResponse('POST', 'classes/' . $idKelas . '/registrants', $regPrivPayload);
            if ($resReg['success']) {
                $pendaftaran['sync_status'] = 'synced';
            } else {
                $pendaftaran['sync_status'] = 'failed';
                log_message('warning', "LaravelApiService: Gagal menyambungkan registrant kelas privat #{$idKelas} (extId: {$extId}): " . ($resReg['message'] ?? 'HTTP Error'));
            }

            return $pendaftaran;
        }

        // =========================================================================
        // 2. ALUR KELAS REGULER:
        // Sinkronkan ke endpoint registrants kelas di Laravel
        // =========================================================================
        $regStatus = 'pending';
        if (in_array($ciStatus, ['ditolak', 'rejected', 'batal'], true) || in_array($ciPay, ['rejected', 'ditolak'], true)) {
            $regStatus = 'rejected';
        } elseif (in_array($ciStatus, ['disetujui', 'approved', 'diterima'], true)) {
            $regStatus = 'approved';
        }

        $payStatus = 'pending';
        if (in_array($ciPay, ['rejected', 'ditolak', 'failed'], true)) {
            $payStatus = 'rejected';
        } elseif (in_array($ciPay, ['valid', 'lunas', 'paid', 'terkonfirmasi'], true)) {
            $payStatus = 'paid';
        }

        $studentId = !empty($pendaftaran['nis']) ? trim((string)$pendaftaran['nis']) : null;
        $regPayload = [
            'external_registration_id' => $extId,
            'student_id'               => $studentId,
            'nis'                      => $studentId,
            'name'                     => trim((string)($pendaftaran['nama'] ?? '')),
            'email'                    => !empty($pendaftaran['email']) ? trim((string)$pendaftaran['email']) : null,
            'phone'                    => !empty($pendaftaran['no_hp']) ? trim((string)$pendaftaran['no_hp']) : null,
            'status'                   => $regStatus,
            'payment_status'           => $payStatus,
            'registered_at'            => $pendaftaran['created_at'] ?? date('Y-m-d H:i:s'),
            'metadata'                 => [
                'id_pendaftaran'      => $idPendaftaran,
                'id_users'            => $pendaftaran['id_users'] ?? null,
                'jenis_kelas'         => 'Reguler',
                'kategori_kelas'      => $pendaftaran['kategori_kelas'] ?? ($kelas['kategori'] ?? null),
                'metode_pembelajaran' => $pendaftaran['metode_pembelajaran'] ?? null,
                'lokasi_pelatihan'    => $pendaftaran['lokasi_pelatihan'] ?? null,
                'metode_pembayaran'   => $pendaftaran['metode_pembayaran'] ?? null,
                'status_pembayaran'   => $pendaftaran['status_pembayaran'] ?? 'pending',
                'status_pendaftaran'  => $pendaftaran['status_pendaftaran'] ?? 'Menunggu',
            ],
        ];

        $resReg = $apiService->requestWithResponse('POST', 'classes/' . $idKelas . '/registrants', $regPayload);
        if ($resReg['success']) {
            $pendaftaran['sync_status'] = 'synced';
        } else {
            $pendaftaran['sync_status'] = 'failed';
        }

        return $pendaftaran;
    }

    /**
     * Memperkaya baris pendaftaran dengan data kelas & jadwal asli dari Laravel.
     */
    public function syncAndEnrichPendaftaranRow(array $row, array &$laravelClassesMap, ?\App\Services\LaravelApiService $apiService = null): array
    {
        $apiService = $apiService ?? new \App\Services\LaravelApiService();
        $db = \Config\Database::connect();

        $idPendaftaran = (int) ($row['id_pendaftaran'] ?? 0);
        $idKelas       = (int) ($row['id_kelas'] ?? 0);
        $extId         = 'REG-' . $idPendaftaran;

        if ($idKelas > 0 && (!isset($laravelClassesMap[$idKelas]) || (is_array($laravelClassesMap[$idKelas]) && empty($laravelClassesMap[$idKelas]['detail_loaded'])))) {
            if (isset($laravelClassesMap[$idKelas]) && $laravelClassesMap[$idKelas] === false) {
                // Kelas tidak ada di Laravel, skip request
            } else {
                try {
                    $resSingle = $apiService->request('GET', 'classes/' . $idKelas);
                    $singleData = $resSingle['data'] ?? $resSingle ?? null;
                    if (!empty($singleData) && is_array($singleData) && isset($singleData['id'])) {
                        $laravelClassesMap[$idKelas] = $this->formatKelasFromApi($singleData);
                    } else {
                        $laravelClassesMap[$idKelas] = false;
                    }
                } catch (\Exception $eSingle) {
                    $laravelClassesMap[$idKelas] = false;
                }
            }
        }

        $cData = ($idKelas > 0 && isset($laravelClassesMap[$idKelas]) && is_array($laravelClassesMap[$idKelas])) ? $laravelClassesMap[$idKelas] : null;

        // Cari data registrant untuk pendaftaran ini di dalam kelas Laravel saat ini
        $laravelReg = null;
        if ($cData && !empty($cData['registrants']) && is_array($cData['registrants'])) {
            foreach ($cData['registrants'] as $regItem) {
                if (($regItem['external_registration_id'] ?? '') === $extId) {
                    $laravelReg = $regItem;
                    break;
                }
            }
            if (!$laravelReg && !empty($row['email'])) {
                foreach ($cData['registrants'] as $regItem) {
                    $regExt = $regItem['external_registration_id'] ?? '';
                    if (($regExt === '' || $regExt === $extId) && strtolower(trim((string)($regItem['email'] ?? ''))) === strtolower(trim((string)$row['email']))) {
                        $laravelReg = $regItem;
                        break;
                    }
                }
            }
        }

        // JIKA TIDAK DITEMUKAN DI KELAS SAAT INI:
        // Cek apakah Admin Laravel telah membuat kelas baru atau menempatkan peserta ke kelas privat lain di Laravel
        if (!$laravelReg && !empty($laravelClassesMap)) {
            foreach ($laravelClassesMap as $cId => &$otherCData) {
                if ((int)$cId === $idKelas) {
                    continue;
                }
                // Jika kelas lain belum memuat daftar registrants, muat detailnya dari Laravel API
                if (empty($otherCData['detail_loaded'])) {
                    try {
                        $resOther = $apiService->request('GET', 'classes/' . $cId);
                        $singleOther = $resOther['data'] ?? $resOther ?? null;
                        if (!empty($singleOther) && is_array($singleOther) && isset($singleOther['id'])) {
                            $otherCData = $this->formatKelasFromApi($singleOther);
                        }
                    } catch (\Exception $eO) {
                        // ignore
                    }
                }

                if (!empty($otherCData['registrants']) && is_array($otherCData['registrants'])) {
                    foreach ($otherCData['registrants'] as $rItem) {
                        if (($rItem['external_registration_id'] ?? '') === $extId) {
                            $laravelReg = $rItem;
                            $idKelas = (int)$cId;
                            $row['id_kelas'] = $idKelas;
                            $cData = $otherCData;
                            if ($idPendaftaran > 0) {
                                $db->table('pendaftaran')->where('id_pendaftaran', $idPendaftaran)->update(['id_kelas' => $idKelas]);
                            }
                            break 2;
                        }
                    }
                }
            }
            unset($otherCData);
        }

        $jenisKelas = trim((string) ($row['jenis_kelas'] ?? ($cData['jenis_kelas'] ?? 'Reguler')));
        $isPrivat   = (strtolower($jenisKelas) === 'privat' || strtolower($jenisKelas) === 'private');
        $row['jenis_kelas'] = $isPrivat ? 'Privat' : 'Reguler';

        // Jika kelas ditemukan di Laravel namun pendaftaran ini belum tercatat di registrants Laravel (atau kelas privat belum diajukan), sinkronkan otomatis
        if (!$isPrivat && $cData && !empty($cData['detail_loaded']) && !$laravelReg && $idPendaftaran > 0) {
            $row = $this->syncRegistrationToLaravel($row, $cData, $apiService);
            $newIdKelas = (int) ($row['id_kelas'] ?? 0);
            if ($newIdKelas > 0 && $newIdKelas !== $idKelas) {
                $idKelas = $newIdKelas;
                try {
                    $resNew = $apiService->request('GET', 'classes/' . $idKelas);
                    $newData = $resNew['data'] ?? $resNew ?? null;
                    if (!empty($newData) && is_array($newData) && isset($newData['id'])) {
                        $laravelClassesMap[$idKelas] = $this->formatKelasFromApi($newData);
                    }
                } catch (\Exception $eNew) {
                    // ignore
                }
                $cData = $laravelClassesMap[$idKelas] ?? $cData;
            }
        }

        $laravelClassStatus = strtolower(trim((string) ($cData['status'] ?? '')));
        $laravelRegStatus   = strtolower(trim((string) ($laravelReg['status'] ?? '')));
        $laravelPayStatus   = strtolower(trim((string) ($laravelReg['payment_status'] ?? '')));

        $ciStatusPendaftaran = strtolower(trim((string) ($row['status_pendaftaran'] ?? '')));
        $ciPayStatus         = strtolower(trim((string) ($row['status_pembayaran'] ?? '')));

        // 0. SINKRONISASI STATUS PEMBAYARAN DARI RECORD VALID LAIN MILIK PESERTA ATAU DARI LARAVEL
        // Jika status pembayaran baris ini masih pending, periksa apakah ada pendaftaran valid untuk peserta ini di kelas yang sama
        if (!in_array($ciPayStatus, ['valid', 'disetujui', 'lunas'], true) && $idKelas > 0 && (!empty($row['email']) || !empty($row['id_users']))) {
            $builderCek = $db->table('pendaftaran')
                ->where('id_kelas', $idKelas)
                ->whereIn('status_pembayaran', ['valid', 'disetujui', 'lunas']);
            if (!empty($row['id_users'])) {
                $builderCek->where('id_users', (int)$row['id_users']);
            } else {
                $builderCek->where('email', $row['email']);
            }
            $existingValid = $builderCek->get()->getRowArray();
            if ($existingValid) {
                $ciPayStatus = 'valid';
                $row['status_pembayaran'] = 'valid';
                if (empty($row['status_pendaftaran']) || $row['status_pendaftaran'] === 'Menunggu') {
                    $row['status_pendaftaran'] = $existingValid['status_pendaftaran'] ?? 'Disetujui';
                    $ciStatusPendaftaran = strtolower(trim((string)$row['status_pendaftaran']));
                }
                if (empty($row['nis']) && !empty($existingValid['nis'])) {
                    $row['nis'] = $existingValid['nis'];
                }
                if ($idPendaftaran > 0) {
                    $db->table('pendaftaran')->where('id_pendaftaran', $idPendaftaran)->update([
                        'status_pembayaran'  => 'valid',
                        'status_pendaftaran' => $row['status_pendaftaran'],
                        'nis'                => $row['nis'] ?? null,
                    ]);
                }
            }
        }

        // Jika di Laravel status registrant adalah paid / confirmed / approved, pembayaran di CI4 otomatis valid
        if (in_array($laravelPayStatus, ['paid', 'valid', 'lunas'], true) || in_array($laravelRegStatus, ['confirmed', 'approved', 'active'], true)) {
            if (!in_array($ciPayStatus, ['valid', 'disetujui', 'lunas'], true)) {
                $ciPayStatus = 'valid';
                $row['status_pembayaran'] = 'valid';
                if ($idPendaftaran > 0) {
                    $db->table('pendaftaran')->where('id_pendaftaran', $idPendaftaran)->update([
                        'status_pembayaran' => 'valid',
                    ]);
                }
            }
        }

        // 1. Cek apakah ditolak di Laravel atau di CI4
        $rejectedInLaravel = in_array($laravelClassStatus, ['rejected', 'ditolak', 'cancelled', 'canceled'], true)
            || in_array($laravelRegStatus, ['rejected', 'ditolak', 'cancelled', 'canceled'], true)
            || in_array($laravelPayStatus, ['rejected', 'ditolak', 'failed'], true);

        $rejectedInCi = in_array($ciStatusPendaftaran, ['ditolak', 'rejected', 'batal'], true)
            || in_array($ciPayStatus, ['rejected', 'ditolak', 'failed', 'gagal'], true);

        if ($rejectedInLaravel && !$rejectedInCi && $idPendaftaran > 0) {
            $db->table('pendaftaran')->where('id_pendaftaran', $idPendaftaran)->update([
                'status_pendaftaran' => 'Ditolak',
                'status_pembayaran'  => 'rejected',
            ]);
            $row['status_pendaftaran'] = 'Ditolak';
            $row['status_pembayaran']  = 'rejected';
            $ciStatusPendaftaran = 'ditolak';
            $ciPayStatus = 'rejected';
        }

        $isRejected = ($rejectedInLaravel || $rejectedInCi);
        $isApproved = false;

        $hasValidPayment = in_array($ciPayStatus, ['valid', 'lunas', 'disetujui'], true)
            || in_array($laravelPayStatus, ['paid', 'valid', 'lunas'], true);

        // Kapan kelas privat dianggap disetujui Admin Laravel:
        // Kelas privat diakui telah disetujui HANYA JIKA kelas di Laravel berjenis Privat dan telah disetujui oleh Admin Laravel
        $cJenis = strtolower(trim((string)($cData['jenis_kelas'] ?? '')));
        $isLaravelPrivateClass = in_array($cJenis, ['privat', 'private'], true);
        $isClassApprovedInLaravel = in_array($laravelClassStatus, ['approved', 'active', 'ongoing', 'berjalan', 'completed', 'disetujui'], true);
        $isRegApprovedInLaravel = in_array($laravelRegStatus, ['approved', 'active', 'disetujui'], true);

        $approvedByLaravel = false;
        if ($isPrivat) {
            if ($isLaravelPrivateClass && ($isClassApprovedInLaravel || $isRegApprovedInLaravel)) {
                $approvedByLaravel = true;
            }
        } else {
            $approvedByLaravel = ($isClassApprovedInLaravel || $isRegApprovedInLaravel);
        }

        if (!$isRejected) {
            if ($isPrivat) {
                // Untuk Kelas Privat:
                // Pembayaran disetujui Admin CI4 ($hasValidPayment) DAN Kelas privat disetujui Admin Laravel ($approvedByLaravel)
                if ($hasValidPayment && $approvedByLaravel) {
                    $isApproved = true;
                } else {
                    $isApproved = false;
                }
            } else {
                // Untuk Kelas Reguler:
                $approvedInLaravel = in_array($laravelRegStatus, ['approved', 'active', 'disetujui'], true)
                    || in_array($laravelPayStatus, ['paid', 'valid', 'lunas', 'approved'], true)
                    || !empty($laravelReg['student_id']);
                $approvedInCi = in_array($ciStatusPendaftaran, ['disetujui', 'approved', 'diterima'], true);

                $isApproved = ($approvedInLaravel || $approvedInCi);
            }

            // Sinkronkan persetujuan ke database CI4 jika sudah disetujui
            if ($isApproved && $idPendaftaran > 0) {
                $updateFields = [];
                if ($ciStatusPendaftaran !== 'disetujui') {
                    $updateFields['status_pendaftaran'] = 'Disetujui';
                    $row['status_pendaftaran'] = 'Disetujui';
                }
                if (!in_array($ciPayStatus, ['valid', 'lunas'], true)) {
                    $updateFields['status_pembayaran'] = 'valid';
                    $row['status_pembayaran'] = 'valid';
                }
                if (empty($row['nis']) || $row['nis'] === '-') {
                    $createdDate = !empty($row['created_at']) ? $row['created_at'] : 'now';
                    $nisBaru = (new \App\Models\BukuIndukModel())->generateNis(date('ym', strtotime($createdDate)));
                    $updateFields['nis'] = $nisBaru;
                    $row['nis'] = $nisBaru;
                }
                if (!empty($updateFields)) {
                    $db->table('pendaftaran')->where('id_pendaftaran', $idPendaftaran)->update($updateFields);
                }
                if ($laravelReg && $laravelRegStatus === 'pending') {
                    $this->syncRegistrationToLaravel($row, $cData, $apiService);
                }
            }
        }

        // 2. Perkaya informasi kelas dari Laravel API
        $row['nama_kelas'] = !empty($cData['nama_kelas'])
            ? $cData['nama_kelas']
            : (!empty($row['nama_kelas'])
                ? $row['nama_kelas']
                : (!empty($row['pilihan_kelas'])
                    ? $row['pilihan_kelas']
                    : (!empty($row['pilihan_pelatihan']) ? $row['pilihan_pelatihan'] : ('Kelas #' . $idKelas))));

        $row['kategori_kelas'] = !empty($cData['kategori'])
            ? $cData['kategori']
            : (!empty($row['kategori_kelas']) ? $row['kategori_kelas'] : 'Pelatihan');

        $row['nama_mentor'] = !empty($cData['nama_mentor'])
            ? $cData['nama_mentor']
            : (!empty($row['nama_mentor']) ? $row['nama_mentor'] : null);

        $row['jumlah_pertemuan'] = $cData['jumlah_pertemuan'] ?? ($row['jumlah_pertemuan'] ?? null);
        $row['waktu_mulai']      = $cData['waktu_mulai'] ?? null;
        $row['waktu_selesai']    = $cData['waktu_selesai'] ?? null;
        $row['tanggal_selesai_kelas'] = $cData['tanggal_selesai_kelas'] ?? null;
        $row['metode_pembelajaran'] = !empty($row['metode_pembelajaran'])
            ? $row['metode_pembelajaran']
            : ($cData['metode_pembelajaran'] ?? 'offline');
        $row['lokasi_pelatihan'] = !empty($row['lokasi_pelatihan'])
            ? $row['lokasi_pelatihan']
            : ($cData['lokasi_media'] ?? null);
        $row['laravel_class_status'] = $laravelClassStatus ?: null;

        // 3. Cek ketersediaan jadwal resmi
        $rawTanggalMulai = null;
        if ($isPrivat) {
            // Untuk kelas privat, jadwal HANYA diambil jika kelas di Laravel memang kelas privat yang sudah disetujui
            if ($isLaravelPrivateClass && !empty($cData['tanggal_mulai_kelas']) && !in_array($cData['tanggal_mulai_kelas'], ['-', '0000-00-00', 'none'], true)) {
                $rawTanggalMulai = $cData['tanggal_mulai_kelas'];
            }
        } else {
            // Untuk kelas reguler
            if (!empty($cData['tanggal_mulai_kelas']) && !in_array($cData['tanggal_mulai_kelas'], ['-', '0000-00-00', 'none'], true)) {
                $rawTanggalMulai = $cData['tanggal_mulai_kelas'];
            } elseif (!empty($row['tanggal_mulai_kelas']) && !in_array($row['tanggal_mulai_kelas'], ['-', '0000-00-00'], true)) {
                $rawTanggalMulai = $row['tanggal_mulai_kelas'];
            }
        }
        $jadwalTersedia = (!empty($rawTanggalMulai) && $rawTanggalMulai !== 'none');

        // 4. Tentukan status tegas & hak akses ruang KBM
        if ($isRejected) {
            $row['kode_status']         = 'ditolak';
            $row['label_status']        = 'Ditolak';
            $row['bisa_diakses']        = false;
            $row['jadwal_tersedia']     = false;
            $row['tanggal_mulai_kelas'] = null;
            $row['jadwal_kelas']        = null;
            $row['keterangan_status']   = !empty($row['alasan_penolakan'])
                ? ('Alasan penolakan: ' . $row['alasan_penolakan'])
                : 'Pendaftaran atau pengajuan kelas tidak disetujui oleh Admin.';
        } elseif ($isPrivat) {
            // ==========================================
            // LOGIKA KHUSUS KELAS PRIVAT:
            // Jadwal kelas privat HANYA muncul setelah disetujui Admin Laravel!
            // ==========================================
            if (!$hasValidPayment) {
                // Admin CI4 belum menyetujui pembayaran
                $row['kode_status']         = 'menunggu';
                $row['label_status']        = 'Menunggu Persetujuan Admin';
                $row['bisa_diakses']        = false;
                $row['jadwal_tersedia']     = false;
                $row['tanggal_mulai_kelas'] = null;
                $row['jadwal_kelas']        = null;
                $row['keterangan_status']   = 'Permintaan kelas privat sedang ditinjau. Menunggu validasi pembayaran oleh Admin.';
            } elseif (!$approvedByLaravel) {
                // Admin CI4 SUDAH menyetujui pembayaran, tetapi Admin Laravel BELUM menyetujui kelas privat
                $row['kode_status']         = 'sedang_disiapkan';
                $row['label_status']        = 'Menunggu Persetujuan Admin SIM CMU';
                $row['bisa_diakses']        = false;
                $row['jadwal_tersedia']     = false;
                $row['tanggal_mulai_kelas'] = null; // JADWAL TIDAK MUNCUL
                $row['jadwal_kelas']        = null;
                $row['keterangan_status']   = 'Pembayaran telah disetujui oleh Admin Creativemu. Jadwal kelas privat akan muncul setelah disetujui dan diterbitkan oleh Admin di sistem SIM CMU.';
            } elseif (!$jadwalTersedia) {
                // Admin Laravel SUDAH menyetujui kelas privat, TETAPI belum menetapkan jadwal pelatihan
                $row['kode_status']         = 'disetujui';
                $row['label_status']        = 'Disetujui (Menunggu Jadwal)';
                $row['bisa_diakses']        = false;
                $row['jadwal_tersedia']     = false;
                $row['tanggal_mulai_kelas'] = null;
                $row['jadwal_kelas']        = null;
                $row['keterangan_status']   = 'Kelas privat telah disetujui oleh Admin SIM CMU. Menunggu penetapan jadwal pelaksanaan kelas oleh instruktur.';
            } else {
                // Admin Laravel SUDAH menyetujui DAN jadwal pelatihan resmi sudah diterbitkan di Laravel
                $row['kode_status']         = 'siap_diakses';
                $row['label_status']        = 'Siap Diakses';
                $row['bisa_diakses']        = true;
                $row['jadwal_tersedia']     = true;
                $row['tanggal_mulai_kelas'] = $rawTanggalMulai;
                $row['jadwal_kelas']        = $rawTanggalMulai;
                $row['keterangan_status']   = 'Kelas privat telah disetujui dan jadwal pelatihan sudah tersedia. Anda dapat langsung masuk ke ruang belajar.';
            }
        } else {
            // ==========================================
            // LOGIKA KELAS REGULER:
            // ==========================================
            if (!$isApproved) {
                $row['kode_status']         = 'menunggu';
                $row['label_status']        = 'Menunggu Persetujuan';
                $row['bisa_diakses']        = false;
                $row['jadwal_tersedia']     = false;
                $row['tanggal_mulai_kelas'] = null;
                $row['jadwal_kelas']        = null;
                $row['keterangan_status']   = 'Pendaftaran Anda sedang dalam proses verifikasi oleh Admin. Akses kelas akan dibuka setelah disetujui.';
            } elseif (!$jadwalTersedia) {
                $row['kode_status']         = 'disetujui';
                $row['label_status']        = 'Disetujui';
                $row['bisa_diakses']        = false;
                $row['jadwal_tersedia']     = false;
                $row['tanggal_mulai_kelas'] = null;
                $row['jadwal_kelas']        = null;
                $row['keterangan_status']   = 'Pendaftaran kelas telah disetujui. Menunggu jadwal pelaksanaan kelas diterbitkan oleh Admin sebelum kelas dapat diakses.';
            } else {
                $row['kode_status']         = 'siap_diakses';
                $row['label_status']        = 'Siap Diakses';
                $row['bisa_diakses']        = true;
                $row['jadwal_tersedia']     = true;
                $row['tanggal_mulai_kelas'] = $rawTanggalMulai;
                $row['jadwal_kelas']        = $rawTanggalMulai;
                $row['keterangan_status']   = 'Kelas telah disetujui dan jadwal pelatihan sudah tersedia. Anda dapat langsung masuk ke ruang belajar.';
            }
        }

        return $row;
    }

    /**
     * Mengambil seluruh sesi pertemuan dan riwayat kehadiran peserta resmi dari Laravel & sinkronisasi lokal
     * Menghasilkan status absensi: Belum Dibuka (0), Absensi Dibuka (1), atau Sudah Absen
     */
    protected function getSessionsAndAttendanceForClass(int $idKelas, int $userId, $kelas = null): array
    {
        $kelas = is_array($kelas) ? $kelas : null;
        $db = \Config\Database::connect();
        $apiService = new \App\Services\LaravelApiService();
        $jadwal = [];

        // 1. Ambil detail kelas dari Laravel jika belum tersedia
        $sched = [];
        $totalMeetings = 0;
        if (!empty($kelas['jumlah_pertemuan'])) {
            $totalMeetings = (int)$kelas['jumlah_pertemuan'];
        }

        try {
            $resDetail = $apiService->request('GET', 'classes/' . $idKelas);
            $kelasApi = $resDetail['data'] ?? $resDetail ?? null;
            if (!empty($kelasApi) && is_array($kelasApi)) {
                $sched = $kelasApi['schedule'] ?? [];
                if ($totalMeetings < 1 && !empty($sched['meetings'])) {
                    $totalMeetings = (int)$sched['meetings'];
                }
            }
        } catch (\Exception $e) {
            log_message('warning', 'Gagal memuat detail kelas Laravel: ' . $e->getMessage());
        }

        // 2. Coba panggil sessions resmi dari Laravel
        $laravelSessions = [];
        try {
            $resSessions = $apiService->request('GET', 'classes/' . $idKelas . '/sessions');
            if (!empty($resSessions['data']) && is_array($resSessions['data'])) {
                $laravelSessions = $resSessions['data'];
            }
        } catch (\Exception $eSess) {
            log_message('info', 'Laravel sessions endpoint unavailable: ' . $eSess->getMessage());
        }

        // 3. Status Absensi Instruktur dari API Laravel (Dokumen Integrasi Bagian 11)
        // Endpoint: GET /api/classes/{idKelas}/trainer-attendance
        $trainerAttendance = [];
        try {
            $trainerAttendance = $apiService->getTrainerAttendance($idKelas);
        } catch (\Throwable $eAtt) {
            log_message('info', 'Laravel trainer-attendance error: ' . $eAtt->getMessage());
        }

        $isTrainerCheckedIn   = !empty($trainerAttendance['checked_in']);
        $trainerAttData       = $trainerAttendance['attendance'] ?? [];
        $trainerSessionNumber = !empty($trainerAttData['session_number']) ? (int)$trainerAttData['session_number'] : 0;
        $trainerAttDate       = !empty($trainerAttData['attendance_date']) ? $trainerAttData['attendance_date'] : date('Y-m-d');

        // 4. Ambil data jadwal lokal untuk pemetaan ID jadwal lokal & status buka jika tabel lokal ada
        $localMap = [];
        if ($db->tableExists('jadwal')) {
            $existingJadwal = $db->table('jadwal')
                ->where('id_kelas', $idKelas)
                ->orderBy('pertemuan_ke', 'ASC')
                ->get()
                ->getResultArray();
            foreach ($existingJadwal as $jRow) {
                $localMap[(int)($jRow['pertemuan_ke'] ?? 0)] = $jRow;
            }
        }

        if (!empty($laravelSessions)) {
            foreach ($laravelSessions as $sess) {
                $num = (int)($sess['session_number'] ?? $sess['meeting_number'] ?? $sess['pertemuan'] ?? 1);
                $localRow = $localMap[$num] ?? null;
                $idJadwal = $localRow['id_jadwal'] ?? ($sess['id'] ?? $num);

                $sessDate = (!empty($sess['date']) && $sess['date'] !== '-') ? $sess['date'] : null;
                $isTrainerActiveHere = false;
                if ($isTrainerCheckedIn) {
                    if ($trainerSessionNumber > 0 && $num === $trainerSessionNumber) {
                        $isTrainerActiveHere = true;
                    } elseif ($trainerSessionNumber === 0 && !empty($sessDate) && $sessDate === $trainerAttDate) {
                        $isTrainerActiveHere = true;
                    } elseif ($trainerSessionNumber === 0 && empty($sessDate) && $num === 1) {
                        $isTrainerActiveHere = true;
                    }
                }

                // Status absensi dibuka otomatis saat instruktur membuka di Laravel
                $absensiDibuka = (
                    $isTrainerActiveHere ||
                    !empty($sess['is_attendance_open']) ||
                    !empty($sess['is_open']) ||
                    in_array(strtolower($sess['status'] ?? ''), ['open', 'opened', 'dibuka', 'aktif'], true) ||
                    (!empty($localRow['absensi_dibuka']) && (int)$localRow['absensi_dibuka'] === 1)
                ) ? 1 : 0;

                // Cek absensi peserta (dari Laravel attendance atau database lokal)
                $userAbsensi = null;
                if (!empty($sess['attendance']) && is_array($sess['attendance'])) {
                    $userAbsensi = $sess['attendance'];
                }
                if (!$userAbsensi && $idJadwal && $db->tableExists('absensi')) {
                    $userAbsensi = $db->table('absensi')
                        ->where('id_user', $userId)
                        ->groupStart()
                            ->where('id_jadwal', $idJadwal)
                            ->orWhere('id_jadwal_kelas', $idJadwal)
                        ->groupEnd()
                        ->get()
                        ->getRowArray();
                }

                $jadwal[] = [
                    'id_jadwal'       => $idJadwal,
                    'pertemuan_ke'    => $num,
                    'materi'          => (!empty($sess['topic']) && $sess['topic'] !== '-') ? $sess['topic'] : ((!empty($sess['title']) && $sess['title'] !== '-') ? $sess['title'] : null),
                    'tanggal_kbm'     => (!empty($sess['date']) && $sess['date'] !== '-') ? $sess['date'] : null,
                    'waktu_mulai'     => (!empty($sess['start_time']) && $sess['start_time'] !== '-') ? substr($sess['start_time'], 0, 5) : null,
                    'waktu_selesai'   => (!empty($sess['end_time']) && $sess['end_time'] !== '-') ? substr($sess['end_time'], 0, 5) : null,
                    'absensi'         => $userAbsensi,
                    'absensi_dibuka'  => $absensiDibuka,
                ];
            }
        } else {
            // Jika endpoint sessions belum ada / error 500 di Laravel,
            // bangkitkan seluruh sesi pertemuan dari jumlah_pertemuan resmi Laravel
            if ($totalMeetings < 1) {
                if (!empty($kelas['jumlah_pertemuan'])) {
                    $totalMeetings = (int)$kelas['jumlah_pertemuan'];
                } elseif (!empty($localMap)) {
                    $totalMeetings = max(array_keys($localMap));
                } else {
                    $totalMeetings = 10;
                }
            }

            $waktuMulai = (!empty($sched['start_time']) && $sched['start_time'] !== '-') ? substr($sched['start_time'], 0, 5) : null;
            $waktuSelesai = (!empty($sched['end_time']) && $sched['end_time'] !== '-') ? substr($sched['end_time'], 0, 5) : null;
            
            $tglMulai = (!empty($sched['start_date']) && $sched['start_date'] !== '-') ? $sched['start_date'] : null;
            if (empty($tglMulai) && !empty($kelas['tanggal_mulai_kelas']) && $kelas['tanggal_mulai_kelas'] !== '-') {
                $tglMulai = $kelas['tanggal_mulai_kelas'];
            }
            if (empty($tglMulai) && !empty($kelas['start_date']) && $kelas['start_date'] !== '-') {
                $tglMulai = $kelas['start_date'];
            }
            if (empty($tglMulai) && !empty($kelas['tanggal_mulai']) && $kelas['tanggal_mulai'] !== '-') {
                $tglMulai = $kelas['tanggal_mulai'];
            }

            // Jika masih kosong, cari dari tabel pendaftaran
            if (empty($tglMulai) && $db->tableExists('pendaftaran')) {
                $pendaftaranDb = $db->table('pendaftaran')
                    ->where('id_kelas', $idKelas)
                    ->orderBy('id_pendaftaran', 'DESC')
                    ->get()
                    ->getRowArray();
                if (!empty($pendaftaranDb['tanggal_mulai_kelas']) && $pendaftaranDb['tanggal_mulai_kelas'] !== '-') {
                    $tglMulai = $pendaftaranDb['tanggal_mulai_kelas'];
                } elseif (!empty($pendaftaranDb['created_at'])) {
                    $tglMulai = date('Y-m-d', strtotime($pendaftaranDb['created_at']));
                }
            }

            // Fallback absolut tanggal awal agar tidak pernah null
            if (empty($tglMulai) || $tglMulai === '-') {
                $tglMulai = date('Y-m-d');
            }

            $tglSelesai = (!empty($sched['end_date']) && $sched['end_date'] !== '-') ? $sched['end_date'] : null;
            if (empty($tglSelesai) && !empty($kelas['end_date']) && $kelas['end_date'] !== '-') {
                $tglSelesai = $kelas['end_date'];
            }

            for ($i = 1; $i <= $totalMeetings; $i++) {
                // Kalkulasi tanggal presisi untuk pertemuan ke-$i
                $tglPertemuan = $tglMulai;
                if (!empty($tglMulai)) {
                    $startTs = strtotime($tglMulai);
                    if (!empty($tglSelesai) && $totalMeetings > 1) {
                        $endTs = strtotime($tglSelesai);
                        $diffDays = round(($endTs - $startTs) / 86400);
                        if ($diffDays > 0) {
                            $step = $diffDays / ($totalMeetings - 1);
                            $offset = round(($i - 1) * $step);
                            $tglPertemuan = date('Y-m-d', strtotime('+' . $offset . ' days', $startTs));
                        } else {
                            $tglPertemuan = date('Y-m-d', strtotime('+' . ($i - 1) . ' days', $startTs));
                        }
                    } elseif ($totalMeetings > 1) {
                        $tglPertemuan = date('Y-m-d', strtotime('+' . ($i - 1) . ' days', $startTs));
                    }
                }

                if (empty($tglPertemuan) || $tglPertemuan === '-') {
                    $tglPertemuan = date('Y-m-d', strtotime('+' . ($i - 1) . ' days'));
                }

                $waktuMulaiDb = !empty($waktuMulai) ? $waktuMulai : '08:00:00';
                $waktuSelesaiDb = !empty($waktuSelesai) ? $waktuSelesai : '10:00:00';

                if (!isset($localMap[$i]) && $db->tableExists('jadwal')) {
                    try {
                        $db->table('jadwal')->insert([
                            'id_kelas'        => $idKelas,
                            'pertemuan_ke'    => $i,
                            'tanggal_kbm'     => $tglPertemuan,
                            'waktu_mulai'     => $waktuMulaiDb,
                            'waktu_selesai'   => $waktuSelesaiDb,
                            'materi'          => 'Pertemuan ' . $i,
                            'absensi_dibuka'  => 0,
                            'radius_meter'    => 100,
                            'created_at'      => date('Y-m-d H:i:s'),
                        ]);
                        $newId = $db->insertID();
                    } catch (\Throwable $eInsert) {
                        log_message('error', 'Gagal insert jadwal lokal: ' . $eInsert->getMessage());
                        $newId = $i;
                    }
                    $localMap[$i] = [
                        'id_jadwal'       => $newId,
                        'id_kelas'        => $idKelas,
                        'pertemuan_ke'    => $i,
                        'tanggal_kbm'     => $tglPertemuan,
                        'waktu_mulai'     => $waktuMulai,
                        'waktu_selesai'   => $waktuSelesai,
                        'materi'          => 'Pertemuan ' . $i,
                        'absensi_dibuka'  => 0,
                    ];
                } elseif (isset($localMap[$i]) && $db->tableExists('jadwal')) {
                    if (empty($localMap[$i]['tanggal_kbm']) || $localMap[$i]['tanggal_kbm'] === '-' || ($i > 1 && $localMap[$i]['tanggal_kbm'] === $tglMulai && $tglPertemuan !== $tglMulai)) {
                        try {
                            $db->table('jadwal')->where('id_jadwal', $localMap[$i]['id_jadwal'])->update(['tanggal_kbm' => $tglPertemuan]);
                            $localMap[$i]['tanggal_kbm'] = $tglPertemuan;
                        } catch (\Throwable $eUpd) {
                            // ignore
                        }
                    }
                }

                $localRow = $localMap[$i] ?? [];
                $idJadwal = (int)($localRow['id_jadwal'] ?? $i);
                $tglFinal = (!empty($localRow['tanggal_kbm']) && $localRow['tanggal_kbm'] !== '-') ? $localRow['tanggal_kbm'] : $tglPertemuan;

                $isTrainerActiveHere = false;
                if ($isTrainerCheckedIn) {
                    if ($trainerSessionNumber > 0 && $i === $trainerSessionNumber) {
                        $isTrainerActiveHere = true;
                    } elseif ($trainerSessionNumber === 0 && !empty($tglFinal) && $tglFinal === $trainerAttDate) {
                        $isTrainerActiveHere = true;
                    } elseif ($trainerSessionNumber === 0 && $i === 1 && $tglFinal === date('Y-m-d')) {
                        $isTrainerActiveHere = true;
                    }
                }

                $absensiDibuka = (
                    $isTrainerActiveHere ||
                    (!empty($localRow['absensi_dibuka']) && (int)$localRow['absensi_dibuka'] === 1)
                ) ? 1 : 0;

                // Jika instruktur sudah absen di Laravel, sinkronkan juga status buka ke database lokal
                if ($isTrainerActiveHere && $idJadwal && empty($localRow['absensi_dibuka']) && $db->tableExists('jadwal')) {
                    try {
                        $db->table('jadwal')->where('id_jadwal', $idJadwal)->update(['absensi_dibuka' => 1]);
                    } catch (\Throwable $eSync) {}
                }

                // Cek absensi peserta untuk pertemuan ini
                $userAbsensi = null;
                if ($db->tableExists('absensi')) {
                    $userAbsensi = $db->table('absensi')
                        ->where('id_user', $userId)
                        ->groupStart()
                            ->where('id_jadwal', $idJadwal)
                            ->orWhere('id_jadwal_kelas', $idJadwal)
                        ->groupEnd()
                        ->get()
                        ->getRowArray();
                }

                $jadwal[] = [
                    'id_jadwal'       => $idJadwal,
                    'pertemuan_ke'    => $i,
                    'materi'          => (!empty($localRow['materi']) && $localRow['materi'] !== '-') ? $localRow['materi'] : ('Pertemuan ' . $i),
                    'tanggal_kbm'     => $tglFinal,
                    'waktu_mulai'     => (!empty($localRow['waktu_mulai']) && $localRow['waktu_mulai'] !== '-') ? substr($localRow['waktu_mulai'], 0, 5) : $waktuMulai,
                    'waktu_selesai'   => (!empty($localRow['waktu_selesai']) && $localRow['waktu_selesai'] !== '-') ? substr($localRow['waktu_selesai'], 0, 5) : $waktuSelesai,
                    'absensi'         => $userAbsensi,
                    'absensi_dibuka'  => $absensiDibuka,
                ];
            }
        }

        return $jadwal;
    }

    public function pendaftaran($id_kelas = null)
    {
        $db = \Config\Database::connect();
        $sessionRole = session()->get('role');
        $userId = ($sessionRole === 'admin') ? null : (method_exists($this, 'userId') ? $this->userId() : session()->get('id_users'));
        $jenisParam = trim((string) $this->request->getGet('jenis'));
        $isModePrivat = (strtolower($jenisParam) === 'privat');

        // 1. Ambil riwayat kelas yang sudah diambil oleh user ini (kecuali yang ditolak)
        $takenClassIds = [];
        if ($userId) {
            $takenBuilder = $db->table('pendaftaran')
                ->select('id_kelas')
                ->where('id_users', $userId)
                ->whereNotIn('status_pendaftaran', ['Ditolak', 'ditolak', 'Rejected', 'rejected']);

            if ($isModePrivat) {
                $takenBuilder->where('jenis_kelas', 'Privat');
            }

            $takenRows = $takenBuilder->get()->getResultArray();
            $takenClassIds = array_map('intval', array_filter(array_column($takenRows, 'id_kelas')));
        }

        // 2. Ambil seluruh kelas dari API Laravel
        $apiService = new \App\Services\LaravelApiService();
        $availableClasses = [];
        $allApiClassesMap = [];
        try {
            $response = $apiService->request('GET', 'classes');
            $kelasApi = $response['data'] ?? $response ?? [];
            if (!empty($kelasApi) && is_array($kelasApi)) {
                foreach ($kelasApi as $k) {
                    if (!isset($k['id'])) {
                        continue;
                    }
                    $formatted = $this->formatKelasFromApi($k);
                    $allApiClassesMap[(int)$k['id']] = $formatted;
                    if (empty($takenClassIds) || !in_array((int)$k['id'], $takenClassIds, true)) {
                        $availableClasses[] = $formatted;
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal memuat kelas dari API: ' . $e->getMessage());
        }

        // Ambil daftar materi resmi khusus kelas privat dari API Laravel
        $materiPrivatList = [];
        $materiApiError = null;
        try {
            $responseMateri = $apiService->request('GET', 'trainings');
            $trainingsData = $responseMateri['data'] ?? $responseMateri ?? [];
            if (!empty($trainingsData) && is_array($trainingsData)) {
                $seenNames = [];
                foreach ($trainingsData as $t) {
                    $name = trim($t['name'] ?? '');
                    if ($name !== '' && !isset($seenNames[$name])) {
                        $seenNames[$name] = true;
                        $materiPrivatList[] = [
                            'id'          => $t['id'] ?? null,
                            'nama_materi' => $name,
                            'deskripsi'   => $t['description'] ?? null,
                            'status'      => $t['status'] ?? 'active',
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            $materiApiError = 'Gagal memuat data materi resmi dari API Laravel: ' . $e->getMessage();
            log_message('error', $materiApiError);
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

        // Jika $id_kelas diberikan di URL namun belum ada di $availableClasses, coba ambil detailnya
        if ($id_kelas !== null && (!empty($allApiClassesMap[(int)$id_kelas]) || empty($availableClasses))) {
            $targetId = (int) $id_kelas;
            if ($userId && in_array($targetId, $takenClassIds, true)) {
                return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                    ->with('error', 'Anda sudah terdaftar di kelas tersebut. Silakan pilih kelas lainnya.');
            }
            $foundInAvailable = false;
            foreach ($availableClasses as $ac) {
                if ((int)$ac['id_kelas'] === $targetId) {
                    $foundInAvailable = true;
                    break;
                }
            }
            if (!$foundInAvailable) {
                if (isset($allApiClassesMap[$targetId])) {
                    $availableClasses[] = $allApiClassesMap[$targetId];
                } else {
                    try {
                        $resSingle = $apiService->request('GET', 'classes/' . $targetId);
                        $kSingle = $resSingle['data'] ?? $resSingle ?? null;
                        if (!empty($kSingle) && isset($kSingle['id'])) {
                            $availableClasses[] = $this->formatKelasFromApi($kSingle);
                        }
                    } catch (\Exception $eSingle) {
                        // Abaikan jika tidak ditemukan
                    }
                }
            }
        }

        // Penentuan kelas terpilih untuk Reguler atau default materi untuk Privat
        $selectedKelas = null;
        if ($isModePrivat) {
            $defaultMateri = !empty($materiPrivatList[0]['nama_materi']) ? $materiPrivatList[0]['nama_materi'] : 'Pelatihan Privat';
            $selectedKelas = [
                'id_kelas'            => '',
                'id'                  => '',
                'nama_kelas'          => $defaultMateri,
                'kategori'            => 'Privat',
                'tipe_kelas'          => 'offline',
                'harga_reguler'       => 0,
                'harga_privat'        => 1500000,
                'nama_mentor'         => 'Ditentukan Admin Laravel',
                'tanggal_mulai_kelas' => '-',
                'jumlah_pertemuan'    => '1',
                'kapasitas'           => 1,
                'kapasitas_tersedia'  => 1,
            ];
        } else {
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

            if ($id_kelas !== null) {
                if ($userId && in_array((int)$id_kelas, $takenClassIds, true)) {
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
                    $selectedKelas = $availableClasses[0];
                }
            } else {
                $selectedKelas = $availableClasses[0];
            }
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

        $defaultJenisKelas = $jenisParam ?: ($selectedKelas['jenis_kelas'] ?? 'Reguler');

        $data = [
            'title'              => 'Formulir Pendaftaran Pelatihan - Creativemu Academy',
            'kelas'              => $selectedKelas,
            'kelasList'          => $availableClasses,
            'user'               => $userData,
            'isStatusLocked'     => $userData['status_locked'],
            'materiPrivatList'   => $materiPrivatList,
            'materiApiError'     => $materiApiError,
            'defaultJenisKelas'  => $defaultJenisKelas,
            'lokasiPelatihan'    => $db->table('lokasi_pelatihan')
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
        // 1. CEK PILIHAN PELATIHAN / KELAS & CEK DUPLIKASI
        // =========================================================
        $jenisKelas = trim((string) $this->request->getPost('jenis_kelas')) ?: 'Reguler';
        $isPrivat   = (strtolower($jenisKelas) === 'privat');
        $jenisKelas = $isPrivat ? 'Privat' : 'Reguler';

        $idKelas = $this->request->getPost('id_kelas');
        $idKelas = (is_numeric($idKelas) && (int)$idKelas > 0) ? (int)$idKelas : null;

        $materiPrivatPost = $this->request->getPost('materi_privat');
        $customMateri     = trim((string)$this->request->getPost('custom_materi'));
        $materiPrivatStr  = null;
        if (!empty($materiPrivatPost)) {
            if (is_array($materiPrivatPost)) {
                $materiPrivatStr = implode(', ', array_filter(array_map('trim', $materiPrivatPost)));
            } else {
                $materiPrivatStr = trim((string)$materiPrivatPost);
            }
        }

        if ($isPrivat) {
            // ALUR KELAS PRIVAT:
            // Siswa HANYA memilih materi pelatihan privat, bukan memilih kelas.
            // id_kelas bernilai null dan kelas privat belum dibuat di Laravel.
            $idKelas = null;
            if (empty($materiPrivatStr)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Silakan pilih materi pelatihan privat yang ingin Anda ikuti.');
            }

            if ($materiPrivatStr === 'Custom Materi' || strtolower($materiPrivatStr) === 'custom materi') {
                $namaMateriFinal = ($customMateri !== '') ? $customMateri : 'Custom Materi';
            } else {
                $namaMateriFinal = $materiPrivatStr;
            }
            $kelas = [
                'id_kelas'            => null,
                'nama_kelas'          => $namaMateriFinal,
                'kategori'            => 'Privat',
                'tipe_kelas'          => 'offline',
                'kapasitas'           => 1,
                'tanggal_mulai_kelas' => null,
            ];

            // Cek duplikasi pendaftaran privat untuk user ini
            $sessionRole = session()->get('role');
            $userId = ($sessionRole === 'admin') ? null : (method_exists($this, 'userId') ? $this->userId() : session()->get('id_users'));
            if ($userId) {
                $dupPriv = $db->table('pendaftaran')
                    ->where('id_users', $userId)
                    ->where('jenis_kelas', 'Privat')
                    ->where('pilihan_pelatihan', $namaMateriFinal)
                    ->whereNotIn('status_pendaftaran', ['Ditolak', 'ditolak', 'Rejected', 'rejected'])
                    ->countAllResults();
                if ($dupPriv > 0) {
                    return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                        ->with('error', 'Anda sudah mengajukan pendaftaran kelas privat untuk materi ini. Silakan tunggu proses verifikasi admin.');
                }
            }
        } else {
            // ALUR KELAS REGULER:
            // Wajib memilih kelas aktif
            if (!$idKelas) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Silakan pilih kelas pelatihan terlebih dahulu.');
            }

            $sessionRole = session()->get('role');
            $userId = ($sessionRole === 'admin') ? null : (method_exists($this, 'userId') ? $this->userId() : session()->get('id_users'));
            if ($userId) {
                $dupBuilder = $db->table('pendaftaran')
                    ->where('id_users', $userId)
                    ->where('id_kelas', $idKelas)
                    ->whereNotIn('status_pendaftaran', ['Ditolak', 'ditolak', 'Rejected', 'rejected'])
                    ->where('jenis_kelas !=', 'Privat');

                if ($dupBuilder->countAllResults() > 0) {
                    return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                        ->with('error', 'Anda sudah terdaftar di kelas ini. Tidak dapat mendaftarkan kelas yang sama dua kali.');
                }
            }

            // Ambil data kelas dari API Laravel sebagai sumber utama (atau fallback database lokal)
            $kelas = null;
            try {
                $apiService = new \App\Services\LaravelApiService();
                $resApi = $apiService->request('GET', 'classes/' . $idKelas);
                $kApi = $resApi['data'] ?? $resApi ?? [];
                if (!empty($kApi) && isset($kApi['id'])) {
                    $kelas = $this->formatKelasFromApi($kApi);
                }
            } catch (\Exception $e) {
                log_message('error', 'Gagal memuat kelas dari API pada simpanPendaftaran: ' . $e->getMessage());
            }

            if (!$kelas) {
                $kelas = $db->table('kelas')
                    ->where('id_kelas', $idKelas)
                    ->get()
                    ->getRowArray();
            }

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
        }
        // =========================================================
        // 2. AMBIL & SANITASI INPUT FORM (TANPA UPLOAD PAS FOTO)
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
            $pendidikanTerakhir = $pendidikanTerakhir !== '' ? $pendidikanTerakhir : trim((string) ($lastRegistration['pendidikan_terakhir'] ?? 'Sma/Smk/Sederajat'));
            $asalInstansi       = $asalInstansi !== '' ? $asalInstansi : trim((string) ($lastRegistration['asal_instansi'] ?? '-'));
            $semester           = $semester !== '' ? $semester : trim((string) ($lastRegistration['semester'] ?? 'Tidak Berlaku'));
        }

        // Pastikan nilai ENUM valid di database
        if (!in_array($jenisKelamin, ['Laki-laki', 'Perempuan'], true)) {
            $jenisKelamin = (stripos($jenisKelamin, 'perempuan') !== false) ? 'Perempuan' : 'Laki-laki';
        }
        $validPendidikan = ['Smp/Sederajat', 'Sma/Smk/Sederajat', 'D3/D4', 'S1', 'S2'];
        if (!in_array($pendidikanTerakhir, $validPendidikan, true)) {
            $pendidikanTerakhir = 'Sma/Smk/Sederajat';
        }

        // Validasi field utama yang wajib diisi
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
        // 3. UPLOAD FILE BUKTI PEMBAYARAN (JIKA TRANSFER)
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
            $targetDir = rtrim(FCPATH, '\\/') . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'bukti';
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0777, true);
            }
            $namaBukti = $fileBukti->getRandomName();
            $fileBukti->move($targetDir, $namaBukti);
        }

        // =========================================================
        // 4. CEK RELASI AKUN PESERTA & SIMPAN DATA PENDAFTARAN
        // =========================================================
        // Jika user belum login, cek apakah akun dengan email ini sudah pernah terdaftar di users
        if (!$userId && !empty($email)) {
            $userFound = $db->table('users')
                ->where('LOWER(email)', strtolower($email))
                ->get()
                ->getRowArray();
            if ($userFound) {
                $userId = (int) $userFound['id_users'];
            } else {
                // Jangan buat akun dummy di tabel users agar calon peserta dapat mendaftarkan
                // akunnya sendiri secara mandiri melalui halaman Registrasi Akun.
                $userId = null;
            }
        }

        $tanggalMulaiPost = trim((string)$this->request->getPost('tanggal_mulai_kelas'));
        if ($isPrivat && !empty($tanggalMulaiPost)) {
            $tanggalMulaiDb = $tanggalMulaiPost;
        } elseif (!$isPrivat && !empty($kelas['tanggal_mulai_kelas']) && $kelas['tanggal_mulai_kelas'] !== '-') {
            $tanggalMulaiDb = $kelas['tanggal_mulai_kelas'];
        } else {
            $tanggalMulaiDb = !empty($tanggalMulaiPost) ? $tanggalMulaiPost : date('Y-m-d');
        }

        // Tangkap Jam Pelatihan untuk Kelas Privat
        $jamPelatihanPost = trim((string)$this->request->getPost('jam_pelatihan'));
        $customJamPost    = trim((string)$this->request->getPost('custom_jam'));
        $jamFinal         = null;
        if ($isPrivat) {
            if ($jamPelatihanPost === 'Custom' || strtolower($jamPelatihanPost) === 'custom') {
                $jamFinal = ($customJamPost !== '') ? $customJamPost : 'Custom Jam Pilihan';
            } else {
                $jamFinal = $jamPelatihanPost ?: null;
            }
        }

        $dataPendaftaran = [
            'nis'                 => null,
            'id_users'            => $userId,
            'id_kelas'            => $idKelas,
            'nama'                => $nama,
            'email'               => $email,
            'no_hp'               => $noHp,
            'alamat'              => $alamat ?: '-',
            'ttl'                 => $ttl ?: '-',
            'jenis_kelamin'       => $jenisKelamin,
            'pendidikan_terakhir' => $pendidikanTerakhir,
            'asal_instansi'       => $asalInstansi ?: '-',
            'semester'            => $semester ?: 'Tidak Berlaku',
            'pas_foto'            => null,
            'status'              => $statusPeserta,
            'status_pendaftaran'  => 'Menunggu',
            'lokasi_pelatihan'    => $lokasiPelatihan,
            'pilihan_pelatihan'   => $kelas['nama_kelas'],
            'jenis_kelas'         => $jenisKelas,
            'metode_pembelajaran' => $metodePembelajaran,
            'pilihan_kelas'       => $kelas['nama_kelas'],
            'kategori_kelas'      => $kategoriKelas,
            'tanggal_mulai_kelas' => $tanggalMulaiDb,
            'jam_pelatihan'       => $jamFinal,
            'metode_pembayaran'   => $metodePembayaran,
            'bukti_pembayaran'    => $namaBukti,
            'status_pembayaran'   => 'pending',
            'alasan_penolakan'    => null,
            'persetujuan_syarat'  => $this->request->getPost('persetujuan_syarat') ? 1 : 0,
        ];

        // Pemrosesan pilihan materi privat & custom materi
        $materiPrivatPost = $this->request->getPost('materi_privat');
        $materiPrivatStr  = null;
        if (!empty($materiPrivatPost)) {
            if (is_array($materiPrivatPost)) {
                $materiPrivatStr = implode(', ', array_filter(array_map('trim', $materiPrivatPost)));
            } else {
                $materiPrivatStr = trim((string)$materiPrivatPost);
            }
        }
        $customMateri = trim((string)$this->request->getPost('custom_materi'));

        if (!$isPrivat) {
            $materiPrivatStr = null;
            $customMateri    = null;
        }

        if ($db->fieldExists('materi_privat', 'pendaftaran')) {
            $dataPendaftaran['materi_privat'] = $materiPrivatStr;
        }
        if ($db->fieldExists('custom_materi', 'pendaftaran')) {
            $dataPendaftaran['custom_materi'] = $customMateri ?: null;
        }
        if ($db->fieldExists('sumber_informasi', 'pendaftaran')) {
            $dataPendaftaran['sumber_informasi'] = $sumberInformasi;
        }

        try {
            $insertId = $pendaftaranModel->insert($dataPendaftaran);
            if ($insertId) {
                $savedRow = $dataPendaftaran;
                $savedRow['id_pendaftaran'] = (int) $insertId;

                // Sinkronisasi data pendaftaran ke Laravel API secara idempotent
                if (!empty($savedRow['id_pendaftaran'])) {
                    $this->syncRegistrationToLaravel($savedRow, $kelas);
                }

                $successMsg = $isPrivat
                    ? 'Permintaan pendaftaran Kelas Privat "' . $kelas['nama_kelas'] . '" berhasil dikirim dan berstatus Menunggu Persetujuan. Silakan menunggu verifikasi dan validasi Admin. Setelah pendaftaran disetujui, silakan lakukan registrasi akun terlebih dahulu untuk membuat kata sandi Anda.'
                    : 'Pendaftaran kelas "' . $kelas['nama_kelas'] . '" berhasil dikirim! Silakan menunggu verifikasi dan validasi Admin. Setelah pendaftaran disetujui, silakan lakukan registrasi akun terlebih dahulu untuk membuat kata sandi Anda.';

                // Selalu arahkan ke halaman Daftar Kelas setelah mengirim formulir pendaftaran
                return redirect()
                    ->to(base_url('pelatihan/daftar-kelas'))
                    ->with('success', $successMsg);
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

            $existingUser = $db->table('users')->where('LOWER(email)', strtolower($pendaftaran['email']))->get()->getRowArray();
            if ($existingUser) {
                $userId = (int) $existingUser['id_users'];
            } else {
                $lastUser = $db->table('users')->selectMax('id_users')->get()->getRowArray();
                $nextId   = ((int) ($lastUser['id_users'] ?? 0)) + 1;

                $db->table('users')->insert([
                    'id_users'      => $nextId,
                    'nama'          => $pendaftaran['nama'],
                    'email'         => $pendaftaran['email'],
                    'no_hp'         => $pendaftaran['no_hp'],
                    'jenis_kelamin' => $pendaftaran['jenis_kelamin'],
                    'password'      => password_hash('123456', PASSWORD_DEFAULT),
                    'role'          => 'peserta',
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
                $userId = $nextId;
            }

            $nisBaru = $pendaftaran['nis'] ?: (new \App\Models\BukuIndukModel())->generateNis(date('ym', strtotime($pendaftaran['created_at'] ?? 'now')));
            $isPrivat = (strtolower(trim((string)($pendaftaran['jenis_kelas'] ?? ''))) === 'privat');

            // Cek status kelas di Laravel
            $apiService = new \App\Services\LaravelApiService();
            $laravelClassStatus = '';
            $kelasData = null;
            if (!empty($pendaftaran['id_kelas'])) {
                try {
                    $resK = $apiService->request('GET', 'classes/' . $pendaftaran['id_kelas']);
                    $rawK = $resK['data'] ?? $resK ?? null;
                    if (!empty($rawK) && is_array($rawK)) {
                        $kelasData = $this->formatKelasFromApi($rawK);
                        $laravelClassStatus = strtolower(trim((string)($kelasData['status'] ?? '')));
                    }
                } catch (\Exception $eK) {
                    // Abaikan jika gagal
                }
            }

            // Jika Kelas Privat dan di Laravel belum disetujui, pembayaran divalidasi namun status pendaftaran tetap Menunggu
            $isApprovedInLaravel = false;
            if ($isPrivat && !empty($kelasData['registrants']) && is_array($kelasData['registrants'])) {
                foreach ($kelasData['registrants'] as $r) {
                    if (($r['external_registration_id'] ?? '') === 'REG-' . $id_pendaftaran) {
                        $isApprovedInLaravel = in_array(strtolower(trim((string)($r['status'] ?? ''))), ['approved', 'active', 'confirmed', 'disetujui'], true);
                        break;
                    }
                }
            }
            $statusPendaftaranBaru = ($isPrivat && !$isApprovedInLaravel) ? 'Menunggu' : 'Disetujui';

            $updateData = [
                'id_users'           => $userId,
                'status_pembayaran'  => 'valid',
                'status_pendaftaran' => $statusPendaftaranBaru,
                'nis'                => $nisBaru,
            ];
            $pendaftaranModel->update($id_pendaftaran, $updateData);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Pendaftaran gagal disetujui.');
            }

            $db->transCommit();

            // Sinkronkan persetujuan ke API Laravel
            $pendaftaranUpdated = array_merge($pendaftaran, $updateData);
            $pendaftaranUpdated['status_pendaftaran'] = 'Disetujui';
            $this->syncRegistrationToLaravel($pendaftaranUpdated, $kelasData, $apiService);

            if ($statusPendaftaranBaru === 'Menunggu') {
                return redirect()->back()->with('success', 'Pembayaran divalidasi & NIS dibuat (' . $nisBaru . '). Status kelas peserta menunggu persetujuan pembuatan Kelas Privat di sistem Laravel.');
            }

            return redirect()->back()->with('success', 'Pendaftaran disetujui, disinkronkan ke Laravel, dan NIS berhasil dibuat: ' . $nisBaru);
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Pendaftaran gagal disetujui: ' . $e->getMessage());
        }
    }

    public function status()
    {
        $keyword = $this->request->getGet('keyword') ?? $this->request->getPost('keyword');
        $pendaftaran = [];

        if ($keyword) {
            $pendaftaran = (new \App\Models\PendaftaranModel())
                ->select('pendaftaran.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
                ->groupStart()
                    ->where('pendaftaran.email', $keyword)
                    ->orWhere('pendaftaran.no_hp', $keyword)
                ->groupEnd()
                ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
                ->findAll();
        }

        return view('peserta/status_pendaftaran', [
            'pendaftaran' => $pendaftaran,
            'keyword'     => $keyword
        ]);
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
        $userId = $this->userId();
        $userEmail = session()->get('email');

        $kelasSaya = $pendaftaranModel
            ->where('id_users', $userId)
            ->orderBy('id_pendaftaran', 'DESC')
            ->findAll();

        if (empty($kelasSaya) && !empty($userEmail)) {
            $kelasSaya = $pendaftaranModel
                ->where('email', $userEmail)
                ->orderBy('id_pendaftaran', 'DESC')
                ->findAll();
        }

        if (!empty($kelasSaya)) {
            usort($kelasSaya, function($a, $b) {
                $aValid = in_array(strtolower($a['status_pembayaran'] ?? ''), ['valid', 'disetujui', 'lunas'], true) ? 0 : 1;
                $bValid = in_array(strtolower($b['status_pembayaran'] ?? ''), ['valid', 'disetujui', 'lunas'], true) ? 0 : 1;
                if ($aValid !== $bValid) {
                    return $aValid <=> $bValid;
                }
                return ($b['id_pendaftaran'] ?? 0) <=> ($a['id_pendaftaran'] ?? 0);
            });
        }

        $apiService = new \App\Services\LaravelApiService();
        $laravelClassesMap = $this->fetchLaravelClassesMap($apiService);

        $summary = [
            'total'        => 0,
            'siap_diakses' => 0,
            'disetujui'    => 0,
            'menunggu'     => 0,
            'ditolak'      => 0,
        ];

        $kelasSayaUnik = [];
        $seenKelas = [];
        foreach ($kelasSaya as &$row) {
            $row = $this->syncAndEnrichPendaftaranRow($row, $laravelClassesMap, $apiService);
            $idKelasK = $row['id_kelas'] ?? $row['id'] ?? null;
            $kKey = $idKelasK !== null ? 'kelas_' . $idKelasK : md5(json_encode($row));
            if (!isset($seenKelas[$kKey])) {
                $seenKelas[$kKey] = true;
                $kelasSayaUnik[] = $row;
                $summary['total']++;
                $kode = $row['kode_status'] ?? 'menunggu';
                if (isset($summary[$kode])) {
                    $summary[$kode]++;
                }
            }
        }
        unset($row);
        $kelasSaya = $kelasSayaUnik;

        $data['kelas']       = $kelasSaya;
        $data['daftarKelas'] = $kelasSaya;
        $data['summary']     = $summary;

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
                    $data['kelas'][] = $this->formatKelasFromApi($k);
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
     * Halaman Khusus Kelas Privat
     * Mengambil dan menampilkan kelas dengan sumber data dari API Laravel
     */
    public function kelasPrivat()
    {
        // Kelas privat tidak memiliki daftar kelas jadi di awal.
        // Siswa langsung diarahkan ke form pendaftaran untuk memilih materi pelatihan privat.
        return redirect()->to(base_url('pelatihan/pendaftaran?jenis=Privat'));
    }

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

        if (!$id_pendaftaran) {
            $semuaPendUser = $db->table('pendaftaran')
                ->where('id_users', $userId)
                ->orderBy('id_pendaftaran', 'DESC')
                ->get()
                ->getResultArray();

            if (!empty($semuaPendUser)) {
                usort($semuaPendUser, function($a, $b) {
                    $aValid = in_array(strtolower($a['status_pembayaran'] ?? ''), ['valid', 'disetujui', 'lunas'], true) ? 0 : 1;
                    $bValid = in_array(strtolower($b['status_pembayaran'] ?? ''), ['valid', 'disetujui', 'lunas'], true) ? 0 : 1;
                    if ($aValid !== $bValid) {
                        return $aValid <=> $bValid;
                    }
                    return ($b['id_pendaftaran'] ?? 0) <=> ($a['id_pendaftaran'] ?? 0);
                });
                $id_pendaftaran = $semuaPendUser[0]['id_pendaftaran'];
            } else {
                return redirect()->to(base_url('peserta/dashboard'))->with('error', 'Belum ada data pendaftaran yang ditemukan.');
            }
        }

        $detail = $db->table('pendaftaran')
            ->where('id_pendaftaran', $id_pendaftaran)
            ->where('id_users', $userId)
            ->get()
            ->getRowArray();

        if (!$detail) {
            return redirect()->to(base_url('peserta/dashboard'))->with('error', 'Data detail pendaftaran dengan ID ' . $id_pendaftaran . ' tidak ditemukan.');
        }

        $apiService = new \App\Services\LaravelApiService();
        $laravelClassesMap = $this->fetchLaravelClassesMap($apiService);
        $detail = $this->syncAndEnrichPendaftaranRow($detail, $laravelClassesMap, $apiService);

        $data = [
            'title'  => 'Detail Status Pendaftaran - Creativemu Academy',
            'detail' => $detail,
            'user'   => (new \App\Models\UserModel())->find($userId),
        ];

        return view('peserta/detail_pendaftaran', $data);
    }

    // Method untuk halaman KBM (Absen, Materi, Ujian, Sertifikat, Angket)
    public function kelas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $userId = $this->userId();
        $idKelas = $this->request->getGet('id_kelas');
        $db = \Config\Database::connect();
        $pendaftaranModel = new \App\Models\PendaftaranModel();

        // 1. Ambil pendaftaran kelas milik peserta yang sedang login (isolasi user)
        $pendaftaranBuilder = $pendaftaranModel->where('id_users', $userId);
        if (!empty($idKelas)) {
            $pendaftaranBuilder->where('id_kelas', $idKelas);
        }
        $pendaftaran = $pendaftaranBuilder->orderBy('id_pendaftaran', 'DESC')->first();

        if (!$pendaftaran) {
            return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                ->with('error', 'Anda tidak memiliki akses ke kelas tersebut.');
        }

        // 2. Sinkronkan & periksa status persetujuan dan ketersediaan jadwal dari API Laravel
        $apiService = new \App\Services\LaravelApiService();
        $laravelClassesMap = $this->fetchLaravelClassesMap($apiService);
        $pendaftaran = $this->syncAndEnrichPendaftaranRow($pendaftaran, $laravelClassesMap, $apiService);

        // Proteksi akses kelas: hanya boleh dibuka jika sudah disetujui DAN jadwal kelas tersedia
        if (empty($pendaftaran['bisa_diakses'])) {
            $kodeStatus = $pendaftaran['kode_status'] ?? 'menunggu';
            if ($kodeStatus === 'ditolak') {
                $errMsg = 'Akses ditolak: Pendaftaran Anda pada kelas "' . ($pendaftaran['nama_kelas'] ?? 'ini') . '" tidak disetujui.';
            } elseif ($kodeStatus === 'sedang_disiapkan') {
                $errMsg = 'Pembayaran sah dan materi telah disetujui Admin. Kelas privat "' . ($pendaftaran['nama_kelas'] ?? 'ini') . '" saat ini sedang disiapkan oleh tim instruktur di SIM CMU. Ruang belajar KBM akan otomatis terbuka setelah kelas siap.';
            } elseif ($kodeStatus === 'disetujui') {
                $errMsg = 'Pendaftaran kelas "' . ($pendaftaran['nama_kelas'] ?? 'ini') . '" sudah disetujui, namun jadwal kelas belum tersedia. Ruang belajar akan dibuka setelah jadwal diterbitkan.';
            } else {
                $errMsg = 'Kelas "' . ($pendaftaran['nama_kelas'] ?? 'ini') . '" belum dapat diakses karena masih berstatus Menunggu Persetujuan Admin.';
            }
            return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                ->with('error', $errMsg);
        }

        $idKelas = (int) $pendaftaran['id_kelas'];
        $kelas = (!empty($laravelClassesMap[$idKelas]) && is_array($laravelClassesMap[$idKelas]))
            ? $laravelClassesMap[$idKelas]
            : [
                'id_kelas'            => $idKelas,
                'nama_kelas'          => $pendaftaran['nama_kelas'] ?? 'Kelas Pelatihan',
                'nama_mentor'         => $pendaftaran['nama_mentor'] ?? null,
                'thumbnail'           => null,
                'foto'                => null,
                'kategori'            => $pendaftaran['kategori_kelas'] ?? null,
                'status'              => 'active',
                'deskripsi'           => null,
                'ringkasan'           => null,
                'jumlah_pertemuan'    => $pendaftaran['jumlah_pertemuan'] ?? null,
                'kapasitas'           => null,
                'kapasitas_tersedia'  => null,
                'harga_reguler'       => null,
                'harga_privat'        => null,
                'tanggal_mulai_kelas' => $pendaftaran['tanggal_mulai_kelas'] ?? null,
                'jenis_kelas'         => $pendaftaran['jenis_kelas'] ?? null,
                'tipe_kelas'          => $pendaftaran['metode_pembelajaran'] ?? null,
                'lokasi_media'        => $pendaftaran['lokasi_pelatihan'] ?? null,
                'metode_pembelajaran' => $pendaftaran['metode_pembelajaran'] ?? null,
            ];

        $kelas['nis'] = $pendaftaran['nis'] ?? null;
        $kelas['id_pendaftaran'] = $pendaftaran['id_pendaftaran'] ?? null;
        $kelas['id_users'] = $pendaftaran['id_users'] ?? null;
        $kelas['student_id'] = !empty($pendaftaran['nis']) && $pendaftaran['nis'] !== '-' ? (string)$pendaftaran['nis'] : null;

        // 3. Ambil data seluruh sesi dan absensi resmi dari Laravel & sinkronisasi peserta
        $jadwal = $this->getSessionsAndAttendanceForClass((int)$idKelas, (int)$userId, $kelas);

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

            $lulusSemua = true;
            $sudahAdaNilai = false;

            foreach ($ujian as &$itemUjian) {
                $hasilUjian = $db->table('nilai_ujian')
                    ->where('id_users', (int)$this->userId())
                    ->groupStart()
                        ->where('id_ujian', $itemUjian['id_ujian'])
                        ->orWhere('id_kelas', $kelas['id_kelas'])
                    ->groupEnd()
                    ->orderBy('id_nilai_ujian', 'DESC')
                    ->get()
                    ->getRowArray();

                $itemUjian['nilai_record'] = $hasilUjian;

                // Cek apakah nilai sudah diinput/diverifikasi oleh admin
                $hasScoreRecord = $hasilUjian && (
                    (isset($hasilUjian['nilai_awal']) && $hasilUjian['nilai_awal'] !== null) || 
                    (isset($hasilUjian['nilai_remidi']) && $hasilUjian['nilai_remidi'] !== null) ||
                    (isset($hasilUjian['nilai']) && $hasilUjian['nilai'] !== null) ||
                    (!empty($hasilUjian['status_kelulusan']) && strtolower(trim((string)$hasilUjian['status_kelulusan'])) !== 'menunggu')
                );

                if ($hasScoreRecord) {
                    $statusKelulusanDb = strtolower(trim((string)($hasilUjian['status_kelulusan'] ?? '')));
                    $nilaiAwal = isset($hasilUjian['nilai_awal']) && $hasilUjian['nilai_awal'] !== null 
                        ? (float)$hasilUjian['nilai_awal'] 
                        : (isset($hasilUjian['nilai']) && $hasilUjian['nilai'] !== null ? (float)$hasilUjian['nilai'] : null);
                    $nilaiRemidi = isset($hasilUjian['nilai_remidi']) && $hasilUjian['nilai_remidi'] !== null 
                        ? (float)$hasilUjian['nilai_remidi'] 
                        : null;
                    $nilaiTerbaru = ($nilaiRemidi !== null) 
                        ? $nilaiRemidi 
                        : ($nilaiAwal !== null ? $nilaiAwal : 0);

                    if ($statusKelulusanDb !== 'menunggu' || (float)$nilaiTerbaru > 0) {
                        $sudahAdaNilai = true;
                    }

                    $itemUjian['nilai_awal'] = $nilaiAwal;
                    $itemUjian['nilai_remidi'] = $nilaiRemidi;
                    $itemUjian['nilai_terbaru'] = $nilaiTerbaru;
                    $itemUjian['status_kelulusan'] = !empty($statusKelulusanDb) ? $statusKelulusanDb : ($nilaiTerbaru >= 70 ? 'lulus' : 'belum');
                    $itemUjian['status_remidi'] = strtolower(trim((string)($hasilUjian['status_remidi'] ?? '')));
                    $itemUjian['id_nilai_ujian'] = $hasilUjian['id_nilai_ujian'] ?? null;

                    if (strtolower($itemUjian['status_kelulusan']) === 'lulus') {
                        $itemUjian['is_lulus'] = true;
                    } else {
                        $itemUjian['is_lulus'] = false;
                        $lulusSemua = false;
                    }
                } else {
                    $itemUjian['nilai_awal'] = null;
                    $itemUjian['nilai_remidi'] = null;
                    $itemUjian['nilai_terbaru'] = null;
                    $itemUjian['status_kelulusan'] = 'menunggu';
                    $itemUjian['status_remidi'] = 'belum';
                    $itemUjian['id_nilai_ujian'] = null;
                    $itemUjian['is_lulus'] = false;
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

        // ==========================================
        // Cek Pengisian Angket
        // ==========================================
        $sudah_isi_angket = false;
        if ($kelas) {
            $sudah_isi_angket = (bool) $db->table('angket_penilaian')
                ->where('id_peserta', (int)$this->userId())
                ->where('id_kelas', $kelas['id_kelas'])
                ->get()
                ->getRow();

            if (!$sudah_isi_angket) {
                $sudahIsiCheck = $db->table('jawaban_angket ja')
                    ->join('angket_pertanyaan ap', 'ap.id_angket_pertanyaan = ja.id_pertanyaan', 'inner')
                    ->where('ja.id_siswa', (int)$this->userId())
                    ->where('(ap.id_kelas IS NULL OR ap.id_kelas = ' . (int)$kelas['id_kelas'] . ')', null, false)
                    ->countAllResults();
                $sudah_isi_angket = ($sudahIsiCheck > 0);
            }
        }

        $sertifikatTerbit = null;

        if ($kelas) {
            $sertifikatTerbit = (new SertifikatModel())
                ->where('id_user', (int)$this->userId())
                ->where('id_kelas', $kelas['id_kelas'])
                ->first();
        }

        $status_kelulusan = '';
        $status_remidi = '';
        $id_nilai_ujian = null;
        
        if (!empty($ujian)) {
            foreach ($ujian as $itemUjian) {
                if (!empty($itemUjian['nilai_record'])) {
                    $status_kelulusan = strtolower(trim((string)($itemUjian['nilai_record']['status_kelulusan'] ?? '')));
                    $status_remidi = strtolower(trim((string)($itemUjian['nilai_record']['status_remidi'] ?? '')));
                    $id_nilai_ujian = $itemUjian['nilai_record']['id_nilai_ujian'] ?? null;
                    break;
                }
            }
        }

        if ($status_kelulusan === '' && $kelas) {
            $directNilai = $db->table('nilai_ujian')
                ->where('id_users', (int)$this->userId())
                ->where('id_kelas', (int)$kelas['id_kelas'])
                ->orderBy('id_nilai_ujian', 'DESC')
                ->get()
                ->getRowArray();
            if ($directNilai) {
                $status_kelulusan = strtolower(trim((string)($directNilai['status_kelulusan'] ?? '')));
                $status_remidi = strtolower(trim((string)($directNilai['status_remidi'] ?? '')));
                $id_nilai_ujian = $directNilai['id_nilai_ujian'] ?? null;
            }
        }
        
        // Syarat Angket Muncul & Bisa Diisi:
        // HANYA TERBUKA jika peserta dinyatakan LULUS oleh Admin.
        // Jika nilai berstatus remidi, tidak lulus, atau masih menunggu, angket TIDAK TERBUKA.
        $bisa_isi_angket = false;
        if ($status_kelulusan === 'lulus' || ($semua_ujian_lulus && !in_array($status_kelulusan, ['remidi', 'tidak lulus', 'menunggu'], true))) {
            $bisa_isi_angket = true;
        }

        // Ambil info berkas nilai dari instruktur via API Laravel
        $grade_file_result = null;
        if ($kelas && !empty($kelas['id_kelas'])) {
            $studentIdentifier = !empty($pendaftaran['nis']) && $pendaftaran['nis'] !== '-'
                ? (string) $pendaftaran['nis']
                : (!empty($pendaftaran['student_id'])
                    ? (string) $pendaftaran['student_id']
                    : (!empty($kelas['nis']) && $kelas['nis'] !== '-'
                        ? (string) $kelas['nis']
                        : (!empty($kelas['student_id'])
                            ? (string) $kelas['student_id']
                            : (!empty($pendaftaran['id_pendaftaran'])
                                ? 'REG-' . $pendaftaran['id_pendaftaran']
                                : (string) ($pendaftaran['id_users'] ?? session()->get('id_users') ?? '')))));

            try {
                $apiService = new \App\Services\LaravelApiService();
                $grade_file_result = $apiService->getGradeFiles((int)$kelas['id_kelas'], $studentIdentifier);

                if (($grade_file_result['status_code'] ?? 0) === 403) {
                    $fallbackCandidates = array_unique(array_filter([
                        !empty($pendaftaran['nis']) && $pendaftaran['nis'] !== '-' ? (string)$pendaftaran['nis'] : null,
                        !empty($pendaftaran['id_pendaftaran']) ? 'REG-' . $pendaftaran['id_pendaftaran'] : null,
                        (string)($pendaftaran['id_users'] ?? session()->get('id_users') ?? ''),
                    ]));
                    foreach ($fallbackCandidates as $candidate) {
                        if ($candidate !== $studentIdentifier) {
                            $retry = $apiService->getGradeFiles((int)$kelas['id_kelas'], $candidate);
                            if (!empty($retry['file']) || ($retry['status_code'] ?? 0) === 200) {
                                $grade_file_result = $retry;
                                break;
                            }
                        }
                    }
                }
            } catch (\Throwable $eGf) {
                log_message('warning', 'Pelatihan::kelas getGradeFiles error: ' . $eGf->getMessage());
            }
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
    'grade_file_result'   => $grade_file_result,
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

            $data['kelas'] = $this->formatKelasFromApi($k);

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
        if ($id_kelas) {
            $_GET['id_kelas'] = $id_kelas;
        }
        return $this->kelas();
    }

    public function ikutRemidi()
{
    $id_nilai_ujian = $this->request->getGet('id') ?: $this->request->getGet('id_nilai_ujian');
    $db = \Config\Database::connect();

    if (!$id_nilai_ujian) {
        $id_ujian = $this->request->getGet('id_ujian');
        if ($id_ujian) {
            $row = $db->table('nilai_ujian')
                ->where('id_users', (int)$this->userId())
                ->where('id_ujian', (int)$id_ujian)
                ->orderBy('id_nilai_ujian', 'DESC')
                ->get()
                ->getRowArray();
            if ($row) {
                $id_nilai_ujian = $row['id_nilai_ujian'];
            }
        }
    }
    
    if ($id_nilai_ujian) {
        $db->table('nilai_ujian')->where('id_nilai_ujian', $id_nilai_ujian)->update([
            'status_remidi' => 'bersedia',
            'updated_at'    => date('Y-m-d H:i:s'),
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

    // Ambil kelas yang diikuti peserta (yang sudah disetujui dan jadwal tersedia)
    $kelas = $this->approvedEnrollment();

    // Kalau peserta belum punya kelas yang siap diakses
    if (!$kelas) {
        return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
            ->with('error', 'Akses materi belum dibuka. Pastikan kelas Anda sudah disetujui dan jadwal kelas tersedia.');
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

        $pendaftaran = $this->approvedEnrollment((int)($jadwal['id_kelas'] ?? 0));

        if (!$pendaftaran) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Akses absensi ditolak. Kelas Anda belum disetujui atau jadwal belum tersedia.'
            ]);
        }

        if (!$jadwal) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Jadwal sesi kelas tidak ditemukan.'
            ]);
        }

        if ((int)($jadwal['absensi_dibuka'] ?? 0) !== 1) {
            $idKelasJadwal = (int) ($jadwal['id_kelas'] ?? 0);
            $dibukaByTrainer = false;
            if ($idKelasJadwal > 0) {
                try {
                    $apiService = new \App\Services\LaravelApiService();
                    $attCheck = $apiService->getTrainerAttendance($idKelasJadwal, (int)($jadwal['pertemuan_ke'] ?? 1));
                    if (!empty($attCheck['checked_in'])) {
                        $dibukaByTrainer = true;
                        if (!empty($jadwal['id_jadwal'])) {
                            $db->table('jadwal')->where('id_jadwal', $jadwal['id_jadwal'])->update(['absensi_dibuka' => 1]);
                        }
                    }
                } catch (\Throwable $eChk) {}
            }
            if (!$dibukaByTrainer) {
                return $this->response->setJSON([
                    'status'  => false,
                    'message' => 'Sesi absensi untuk pertemuan ini belum dibuka oleh mentor/admin.'
                ]);
            }
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
        $radiusToleransi = (int) (!empty($jadwal['radius_meter']) ? $jadwal['radius_meter'] : ($lokasiInfo['radius_meter'] ?? 300));

        // Normalisasi titik presisi Kantor Pusat (-7.812551, 110.268521) jika tabel jadwal masih menyimpan titik lama
        if (abs($targetLat - (-7.818933)) < 0.001) {
            $targetLat = -7.812551;
            $targetLng = 110.268521;
        }
        $radiusToleransi = max($radiusToleransi, 300);

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

        $pendaftaran = $this->approvedEnrollment((int)($jadwal['id_kelas'] ?? 0));

        if (!$pendaftaran) {
            return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))->with('error', 'Anda belum memiliki akses ke kelas yang disetujui untuk sesi ini.');
        }

        // 2. Cek apakah absensi dibuka
        $absensiDibuka = (int) ($jadwal['absensi_dibuka'] ?? 0);
        if ($absensiDibuka !== 1) {
            $idKelasJadwal = (int) ($jadwal['id_kelas'] ?? 0);
            if ($idKelasJadwal > 0) {
                try {
                    $apiService = new \App\Services\LaravelApiService();
                    $attCheck = $apiService->getTrainerAttendance($idKelasJadwal, (int)($jadwal['pertemuan_ke'] ?? 1));
                    if (!empty($attCheck['checked_in'])) {
                        $absensiDibuka = 1;
                        if (!empty($jadwal['id_jadwal'])) {
                            $db->table('jadwal')->where('id_jadwal', $jadwal['id_jadwal'])->update(['absensi_dibuka' => 1]);
                        }
                    }
                } catch (\Throwable $eChk) {}
            }
        }
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
        $radiusToleransi = (int) (!empty($jadwal['radius_meter']) ? $jadwal['radius_meter'] : ($lokasiInfo['radius_meter'] ?? 300));

        // Normalisasi titik presisi Kantor Pusat (-7.812551, 110.268521) jika tabel jadwal masih menyimpan titik lama
        if (abs($targetLat - (-7.818933)) < 0.001) {
            $targetLat = -7.812551;
            $targetLng = 110.268521;
        }
        $radiusToleransi = max($radiusToleransi, 300);

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
                'latitude'     => -7.812551,
                'longitude'    => 110.268521,
                'radius_meter' => 300,
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
            'latitude'     => -7.812551,
            'longitude'    => 110.268521,
            'radius_meter' => 300,
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
            return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))->with('error', 'Akses tugas belum dibuka. Pastikan kelas Anda sudah disetujui dan jadwal tersedia.');
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
            return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                ->with('error', 'Akses ujian belum dibuka. Pastikan kelas Anda sudah disetujui dan jadwal tersedia.');
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

            $hasScoreInDb = $nilaiRow && (
                (isset($nilaiRow['nilai']) && $nilaiRow['nilai'] !== null) ||
                (isset($nilaiRow['nilai_awal']) && $nilaiRow['nilai_awal'] !== null) ||
                (isset($nilaiRow['nilai_remidi']) && $nilaiRow['nilai_remidi'] !== null) ||
                (!empty($nilaiRow['status_kelulusan']) && strtolower(trim((string)$nilaiRow['status_kelulusan'])) !== 'menunggu')
            );

            if ($hasScoreInDb) {
                $nilaiAwal    = (isset($nilaiRow['nilai_awal']) && $nilaiRow['nilai_awal'] !== null) 
                    ? (float) $nilaiRow['nilai_awal'] 
                    : ((isset($nilaiRow['nilai']) && $nilaiRow['nilai'] !== null) ? (float) $nilaiRow['nilai'] : null);
                $nilaiRemidi  = (isset($nilaiRow['nilai_remidi']) && $nilaiRow['nilai_remidi'] !== null) ? (float) $nilaiRow['nilai_remidi'] : null;
                $nilaiTerbaru = ($nilaiRemidi !== null) ? $nilaiRemidi : ($nilaiAwal !== null ? $nilaiAwal : 0.0);

                $dbStatusKelulusan = strtolower(trim((string)($nilaiRow['status_kelulusan'] ?? 'menunggu')));
                $dbStatusRemidi    = strtolower(trim((string)($nilaiRow['status_remidi'] ?? 'belum')));
                
                $item['has_score']     = true;
                $item['sudah_ujian']   = true;
                $item['nilai_awal']    = $nilaiAwal;
                $item['nilai_remidi']  = $nilaiRemidi;
                $item['nilai_terbaru'] = $nilaiTerbaru;
                $item['status_admin']  = $dbStatusKelulusan;
                $item['status_remidi'] = $dbStatusRemidi;

                $skorTampil = ($nilaiTerbaru !== null) ? (int)$nilaiTerbaru : 0;

                if ($dbStatusKelulusan === 'lulus') {
                    $item['is_lulus']          = true;
                    $item['status_teks']       = 'LULUS';
                    $item['status_badge']      = 'success';
                    $item['keterangan_status'] = "Selamat! Anda dinyatakan Lulus oleh Admin dengan perolehan nilai {$skorTampil}%.";
                    $item['bisa_remidi']       = false;
                } elseif ($dbStatusKelulusan === 'tidak lulus') {
                    $item['is_lulus']          = false;
                    $item['status_teks']       = 'TIDAK LULUS';
                    $item['status_badge']      = 'danger';
                    $item['keterangan_status'] = "Anda dinyatakan Tidak Lulus oleh Admin (Nilai: {$skorTampil}%).";
                    $item['bisa_remidi']       = false;
                } elseif ($dbStatusKelulusan === 'remidi') {
                    $item['is_lulus']          = false;
                    $item['status_teks']       = 'REMIDI';
                    $item['status_badge']      = 'warning';
                    if ($nilaiRemidi !== null) {
                        $item['keterangan_status'] = "Nilai ujian awal: " . (int)$nilaiAwal . "% dan Nilai remidi: " . (int)$nilaiRemidi . "%. Status remidi: Anda perlu perbaikan nilai sesuai evaluasi admin.";
                    } else {
                        $item['keterangan_status'] = "Nilai ujian Anda saat ini ({$skorTampil}%) belum memenuhi standar kelulusan minimum (70%). Anda memerlukan remidi sesuai keputusan Admin.";
                    }
                    $item['bisa_remidi']       = false;
                } else {
                    // Status 'menunggu' atau belum ditentukan oleh admin
                    $item['is_lulus']          = false;
                    $item['status_teks']       = 'MENUNGGU KONFIRMASI ADMIN';
                    $item['status_badge']      = 'secondary';
                    $item['keterangan_status'] = 'Hasil ujian masih menunggu konfirmasi admin.';
                    $item['bisa_remidi']       = false;
                }
            } else {
                $item['has_score']         = false;
                $item['sudah_ujian']       = false;
                $item['nilai_awal']        = null;
                $item['nilai_remidi']      = null;
                $item['nilai_terbaru']     = null;
                $item['status_admin']      = 'menunggu';
                $item['status_remidi']     = 'belum';
                $item['is_lulus']          = false;
                $item['status_teks']       = 'MENUNGGU KONFIRMASI ADMIN';
                $item['status_badge']      = 'secondary';
                $item['keterangan_status'] = 'Hasil masih menunggu penilaian instruktur dan konfirmasi admin.';
                $item['bisa_remidi']       = false;
            }
        }

        // Mengambil informasi file nilai dari API Laravel
        $studentIdentifier = !empty($kelas['nis']) && $kelas['nis'] !== '-'
            ? (string) $kelas['nis']
            : (!empty($kelas['student_id'])
                ? (string) $kelas['student_id']
                : (!empty($kelas['id_pendaftaran'])
                    ? 'REG-' . $kelas['id_pendaftaran']
                    : (string) ($kelas['id_users'] ?? session()->get('id_users') ?? '')));

        $apiService = new \App\Services\LaravelApiService();
        $gradeFileResult = $apiService->getGradeFiles((int)($kelas['id_kelas'] ?? 0), $studentIdentifier);

        if (($gradeFileResult['status_code'] ?? 0) === 403) {
            $fallbackCandidates = array_unique(array_filter([
                !empty($kelas['nis']) && $kelas['nis'] !== '-' ? (string)$kelas['nis'] : null,
                !empty($kelas['id_pendaftaran']) ? 'REG-' . $kelas['id_pendaftaran'] : null,
                (string)($kelas['id_users'] ?? session()->get('id_users') ?? ''),
            ]));
            foreach ($fallbackCandidates as $candidate) {
                if ($candidate !== $studentIdentifier) {
                    $retry = $apiService->getGradeFiles((int)($kelas['id_kelas'] ?? 0), $candidate);
                    if (!empty($retry['file']) || ($retry['status_code'] ?? 0) === 200) {
                        $gradeFileResult = $retry;
                        break;
                    }
                }
            }
        }

        return view('peserta/ujian', [
            'title'             => 'Ujian Peserta',
            'kelas'             => $kelas,
            'ujian'             => $ujian,
            'grade_file_result' => $gradeFileResult,
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

    // Ambil pendaftaran peserta yang sudah disetujui dan siap diakses
    $kelas = $this->approvedEnrollment();

    if (!$kelas) {
        return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
            ->with('error', 'Kelas peserta belum disetujui atau jadwal belum tersedia.');
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

        return redirect()->to(base_url('pelatihan/ujian'))
            ->with('info', 'Nilai ujian dinilai langsung oleh Instruktur. Anda hanya dapat melihat nilai akhir dan status kelulusan.');
    }

    public function submitUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return redirect()->to(base_url('pelatihan/ujian'))
            ->with('info', 'Nilai ujian dan status kelulusan ditentukan langsung oleh Instruktur dan Admin.');
    }

    public function unduhFileNilai($idKelas, $gradeFileId = null)
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment((int)$idKelas);
        if (!$kelas) {
            return redirect()->to(base_url('pelatihan/ujian'))
                ->with('error', 'Anda tidak memiliki akses ke kelas ini.');
        }

        $studentIdentifier = !empty($kelas['nis']) && $kelas['nis'] !== '-'
            ? (string) $kelas['nis']
            : (!empty($kelas['student_id'])
                ? (string) $kelas['student_id']
                : (!empty($kelas['id_pendaftaran'])
                    ? 'REG-' . $kelas['id_pendaftaran']
                    : (string) ($kelas['id_users'] ?? session()->get('id_users') ?? '')));

        $apiService = new \App\Services\LaravelApiService();

        $fileInfo = $apiService->getGradeFiles((int)$idKelas, $studentIdentifier);

        if (($fileInfo['status_code'] ?? 0) === 403) {
            $fallbackCandidates = array_unique(array_filter([
                !empty($kelas['nis']) && $kelas['nis'] !== '-' ? (string)$kelas['nis'] : null,
                !empty($kelas['id_pendaftaran']) ? 'REG-' . $kelas['id_pendaftaran'] : null,
                (string)($kelas['id_users'] ?? session()->get('id_users') ?? ''),
            ]));
            foreach ($fallbackCandidates as $candidate) {
                if ($candidate !== $studentIdentifier) {
                    $retry = $apiService->getGradeFiles((int)$idKelas, $candidate);
                    if (!empty($retry['file']) || ($retry['status_code'] ?? 0) === 200) {
                        $fileInfo = $retry;
                        $studentIdentifier = $candidate;
                        break;
                    }
                }
            }
        }

        $fileName = $fileInfo['file']['file_name'] ?? ('file-nilai-kelas-' . $idKelas . '.pdf');

        if (empty($gradeFileId)) {
            if ($fileInfo['status_code'] === 403) {
                return redirect()->back()
                    ->with('error', 'Akses berkas nilai belum dibuka oleh server pusat (Status 403: Akun siswa belum terverifikasi di server Laravel).');
            }
            if (empty($fileInfo['file']) || empty($fileInfo['file']['id'])) {
                return redirect()->back()
                    ->with('error', $fileInfo['message'] ?? 'Berkas nilai belum tersedia atau belum disetujui admin di server pusat.');
            }
            $gradeFileId = $fileInfo['file']['id'];
        }

        $downloadResult = $apiService->downloadGradeFile((int)$idKelas, $gradeFileId, $studentIdentifier);

        if (!$downloadResult['success'] && ($downloadResult['status_code'] ?? 0) === 403 && !empty($fallbackCandidates)) {
            foreach ($fallbackCandidates as $candidate) {
                if ($candidate !== $studentIdentifier) {
                    $retryDl = $apiService->downloadGradeFile((int)$idKelas, $gradeFileId, $candidate);
                    if (!empty($retryDl['success'])) {
                        $downloadResult = $retryDl;
                        break;
                    }
                }
            }
        }

        if (!$downloadResult['success']) {
            if ($downloadResult['status_code'] === 403) {
                return redirect()->back()
                    ->with('error', 'Akses unduh ditolak oleh server pusat (Status 403: Siswa belum terdaftar/terverifikasi).');
            }
            if ($downloadResult['status_code'] === 404) {
                return redirect()->back()
                    ->with('error', 'Berkas nilai tidak ditemukan atau belum disetujui admin di server pusat.');
            }
            return redirect()->back()
                ->with('error', $downloadResult['message'] ?? 'Gagal mengunduh berkas nilai.');
        }

        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $fileName);

        return $this->response
            ->setHeader('Content-Type', $downloadResult['content_type'])
            ->setHeader('Content-Disposition', 'attachment; filename="' . $safeName . '"')
            ->setBody($downloadResult['body']);
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

    /**
     * Memeriksa apakah peserta telah dinyatakan LULUS pada kelas tertentu.
     * Mengembalikan status kelulusan dan apakah peserta berhak mengakses angket/sertifikat.
     */
    protected function getPesertaKelulusanStatus(int $idPeserta, int $idKelas): array
    {
        $db = \Config\Database::connect();
        $nilaiList = $db->table('nilai_ujian')
            ->where('id_users', $idPeserta)
            ->where('id_kelas', $idKelas)
            ->orderBy('id_nilai_ujian', 'DESC')
            ->get()
            ->getResultArray();

        if (empty($nilaiList)) {
            $semuaUjian = $db->table('ujian')
                ->where('id_kelas', $idKelas)
                ->get()
                ->getResultArray();

            if (!empty($semuaUjian)) {
                foreach ($semuaUjian as $u) {
                    $nRow = $db->table('nilai_ujian')
                        ->where('id_users', $idPeserta)
                        ->where('id_ujian', $u['id_ujian'])
                        ->orderBy('id_nilai_ujian', 'DESC')
                        ->get()
                        ->getRowArray();
                    if ($nRow) {
                        $nilaiList[] = $nRow;
                    }
                }
            }
        }

        $isLulus = false;
        $isRemidi = false;
        $adaNilai = false;
        $statusUtama = 'menunggu';

        if (!empty($nilaiList)) {
            $lulusSemua = true;
            foreach ($nilaiList as $nl) {
                $st = strtolower(trim((string)($nl['status_kelulusan'] ?? '')));
                if ($st === 'remidi') {
                    $isRemidi = true;
                    $lulusSemua = false;
                } elseif ($st !== 'lulus') {
                    $lulusSemua = false;
                }
                if ($st !== '' && $st !== 'menunggu') {
                    $adaNilai = true;
                }
            }
            $isLulus = $lulusSemua && $adaNilai;
            $statusUtama = strtolower(trim((string)($nilaiList[0]['status_kelulusan'] ?? 'menunggu')));
        }

        return [
            'is_lulus'    => $isLulus,
            'is_remidi'   => $isRemidi,
            'ada_nilai'   => $adaNilai,
            'status'      => $statusUtama,
        ];
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
                ->orWhere('pendaftaran.status_pendaftaran', 'Disetujui')
                ->orWhere('pendaftaran.status', 'Aktif')
            ->groupEnd()
            ->first();

        if (!$pendaftaran) {
            $pendaftaran = $this->approvedEnrollment((int)$id_kelas);
        }
    } else {
        $pendaftaran = $this->approvedEnrollment();
    }

    if (! $pendaftaran) {
        return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas tidak ditemukan.');
    }

    $idPeserta = $this->userId();
    $idKelas = (int) $pendaftaran['id_kelas'];

    // Cek kelayakan status kelulusan peserta: Angket HANYA boleh dibuka jika dinyatakan LULUS oleh Admin
    $statusCheck = $this->getPesertaKelulusanStatus($idPeserta, $idKelas);
    if (!$statusCheck['is_lulus']) {
        if ($statusCheck['is_remidi'] || $statusCheck['status'] === 'remidi') {
            $pesan = 'Angket belum dapat diakses karena status ujian Anda masih Remidi. Angket hanya terbuka setelah dinyatakan Lulus.';
        } elseif ($statusCheck['status'] === 'tidak lulus') {
            $pesan = 'Angket tidak dapat diakses karena status ujian Anda dinyatakan Tidak Lulus.';
        } else {
            $pesan = 'Angket belum dapat diakses. Menu angket terbuka otomatis setelah nilai ujian Anda diumumkan dan dinyatakan Lulus.';
        }
        return redirect()->to(base_url('pelatihan/kelas?id_kelas=' . $idKelas))->with('error', $pesan);
    }

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

    // Ambil daftar kelas resmi dari API Laravel
    $apiService = new \App\Services\LaravelApiService();
    $classesRes = $apiService->request('GET', 'classes');
    $rawClasses = $classesRes['data'] ?? $classesRes ?? [];

    $semuaKelas = [];
    if (!empty($rawClasses) && is_array($rawClasses)) {
        foreach ($rawClasses as $c) {
            $cId = (int) ($c['id'] ?? 0);
            $cName = trim((string) ($c['name'] ?? ''));
            if ($cId > 0 && $cName !== '') {
                $semuaKelas[] = [
                    'id_kelas'   => $cId,
                    'nama_kelas' => $cName,
                    'status'     => $c['status'] ?? 'aktif',
                    'start_date' => $c['schedule']['start_date'] ?? $c['start_date'] ?? null,
                ];
            }
        }
    }

    // Fallback ke tabel kelas lokal jika API Laravel offline atau kosong
    if (empty($semuaKelas)) {
        $semuaKelas = $this->db
            ->table('kelas')
            ->where('status', 'aktif')
            ->get()
            ->getResultArray();
    }

    // Ambil seluruh kelas yang dimiliki / diikuti oleh peserta saat ini
    $kelasSaya = (new \App\Models\PendaftaranModel())
        ->select('pendaftaran.id_kelas, kelas.nama_kelas, kelas.tanggal_mulai_kelas')
        ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
        ->where('pendaftaran.id_users', $idPeserta)
        ->groupStart()
            ->where('pendaftaran.status', 'Disetujui')
            ->orWhere('pendaftaran.status_pembayaran', 'valid')
            ->orWhere('pendaftaran.status_pendaftaran', 'Disetujui')
            ->orWhere('pendaftaran.status', 'Aktif')
        ->groupEnd()
        ->findAll();

    // Jika nama kelas pendaftaran saat ini kosong atau belum sinkron, lengkapi dari data kelas
    if (empty($pendaftaran['nama_kelas']) && !empty($semuaKelas)) {
        foreach ($semuaKelas as $sk) {
            if ((int)$sk['id_kelas'] === $idKelas) {
                $pendaftaran['nama_kelas'] = $sk['nama_kelas'];
                break;
            }
        }
    }

    // Pastikan pertanyaan bulan menampilkan opsi Januari sampai Desember
    $semuaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    foreach ($pertanyaan as &$qItem) {
        if (stripos($qItem['pertanyaan'], 'bulan') !== false || stripos($qItem['pertanyaan'], 'periode') !== false || (int)$qItem['id_angket_pertanyaan'] === 24) {
            $qItem['opsi_jawaban'] = json_encode($semuaBulan);
        }
    }
    unset($qItem);

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
        'kelasSaya'   => $kelasSaya,
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

        $idKelasPost = (int) ($this->request->getPost('kelas_id') ?? $this->request->getPost('id_kelas') ?? 0);
        $pendaftaran = $this->approvedEnrollment($idKelasPost > 0 ? $idKelasPost : null);
        if (!$pendaftaran && $idKelasPost > 0) {
            $pendaftaran = (new \App\Models\PendaftaranModel())
                ->where('id_users', $idPeserta)
                ->where('id_kelas', $idKelasPost)
                ->first();
        }

        if (!$pendaftaran) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $idKelas = (int) $pendaftaran['id_kelas'];

        // Cek kelayakan status ujian peserta: Angket HANYA boleh disimpan jika dinyatakan LULUS
        $statusCheck = $this->getPesertaKelulusanStatus($idPeserta, $idKelas);
        if (!$statusCheck['is_lulus']) {
            $pesan = ($statusCheck['is_remidi'] || $statusCheck['status'] === 'remidi')
                ? 'Anda belum dapat mengisi angket karena status ujian Anda masih Remidi. Angket hanya terbuka setelah dinyatakan Lulus.'
                : 'Anda belum dapat mengisi angket karena status ujian Anda belum dinyatakan Lulus.';
            return redirect()->to(base_url('pelatihan/kelas?id_kelas=' . $idKelas))->with('error', $pesan);
        }

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

        // Ambil jadwal & absensi dari API Laravel (Single Source of Truth) & sinkronisasi
        $jadwal = $this->getSessionsAndAttendanceForClass((int)$pendaftaran['id_kelas'], (int)$this->userId(), $pendaftaran);

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

