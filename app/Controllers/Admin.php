<?php



namespace App\Controllers;



use App\Models\MentorModel;

use App\Models\KelasModel;

use App\Models\PesertaModel;

use App\Models\AngketModel;

use App\Models\SertifikatModel;

use App\Models\PendaftaranModel;



class Admin extends BaseController

{



    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)

{

    // Do Not Edit This Line

    parent::initController($request, $response, $logger);



    // Cek apakah session session 'logged_in' ada DAN rolenya adalah 'admin'

    $session = session();

    if (!$session->get('logged_in') || $session->get('role') != 'admin') {

        // Jika belum login / bukan admin, arahkan ke halaman login

        header('Location: ' . base_url('pelatihan/login'));

        exit();

    }

}



    public function index()

{

    // 1. Panggil model mentor (sesuaikan nama modelnya jika berbeda)

    $mentorModel = new \App\Models\MentorModel();



    // 2. Masukkan data mentor ke dalam array $data

    $data = [

        'title' => 'Master Kelas',

        'data_mentor' => $mentorModel->findAll() // <-- INI YANG KURANG

    ];



    // 3. Kirim $data ke view

    return view('admin/master_kelas/index', $data);

}



    public function __construct()

    {

        helper(['url', 'form']);

    }



    // --- DASHBOARD ---
    public function dashboard()
    {
        $db = \Config\Database::connect();
        $apiService = new \App\Services\LaravelApiService();

        // 1. Data Kelas dari API Laravel (Single Source of Truth)
        $totalKelas = 0;
        try {
            $responseKelas = $apiService->request('GET', 'classes');
            $dataKelasApi = $responseKelas['data'] ?? $responseKelas ?? [];
            if (!empty($dataKelasApi) && is_array($dataKelasApi)) {
                $totalKelas = count($dataKelasApi);
            }
        } catch (\Exception $e) {
            log_message('error', 'Dashboard API classes error: ' . $e->getMessage());
        }

        // Fallback jika API kosong atau error, ambil dari DB lokal
        if ($totalKelas === 0 && $db->tableExists('kelas')) {
            $totalKelas = $db->table('kelas')->countAllResults();
        }

        // 2. Data Instruktur/Mentor dari API Laravel
        $totalMentor = 0;
        try {
            $responseTrainers = $apiService->request('GET', 'trainers');
            $dataTrainersApi = $responseTrainers['data'] ?? $responseTrainers ?? [];
            if (!empty($dataTrainersApi) && is_array($dataTrainersApi)) {
                $totalMentor = count($dataTrainersApi);
            }
        } catch (\Exception $e) {
            log_message('error', 'Dashboard API trainers error: ' . $e->getMessage());
        }

        // Fallback jika API kosong atau error, ambil dari DB lokal
        if ($totalMentor === 0 && $db->tableExists('mentor')) {
            $totalMentor = $db->table('mentor')->countAllResults();
        }

        // 3. Data Pendaftaran & Peserta dari Database CI4 yang sudah berjalan
        $pendaftaranPending = $db->table('pendaftaran')
            ->select('pendaftaran.*, users.nama, users.email, kelas.nama_kelas')
            ->join('users', 'users.id_users = pendaftaran.id_users', 'left')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.status', 'pending')
            ->orderBy('pendaftaran.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $totalPesertaAktif = $db->table('pendaftaran')
            ->groupStart()
                ->where('status_pembayaran', 'valid')
                ->orWhere('status_pembayaran', 'Disetujui')
                ->orWhere('status_pembayaran', 'approved')
            ->groupEnd()
            ->countAllResults();

        // 4. Data Monitoring Angket Kepuasan Peserta (Skala 1 - 4: Sangat Puas, Puas, Cukup, Kurang)
        $angketData = [0, 0, 0, 0];
        $totalRespondenAngket = 0;
        if ($db->tableExists('angket_pertanyaan') && $db->tableExists('jawaban_angket')) {
            $ratingQuestions = $db->table('angket_pertanyaan')
                ->select('id_angket_pertanyaan')
                ->where('tipe', 'rating')
                ->get()->getResultArray();
            $qIds = array_column($ratingQuestions, 'id_angket_pertanyaan');

            if (!empty($qIds)) {
                $answers = $db->table('jawaban_angket')
                    ->whereIn('id_pertanyaan', $qIds)
                    ->get()->getResultArray();

                foreach ($answers as $a) {
                    if (is_numeric($a['jawaban'])) {
                        $val = round((float)$a['jawaban']);
                        if ($val >= 4) {
                            $angketData[0]++; // Sangat Puas
                        } elseif ($val == 3) {
                            $angketData[1]++; // Puas
                        } elseif ($val == 2) {
                            $angketData[2]++; // Cukup
                        } elseif ($val == 1) {
                            $angketData[3]++; // Kurang
                        }
                    }
                }
                $totalRespondenAngket = array_sum($angketData);
            }
        }

        // 5. Data Monitoring Absensi Instruktur (Single Source of Truth: API Laravel + Sesi KBM)
        $absensiMentorList = [];
        $keteranganAbsensi = null;
        $absensiInstrukturTersedia = true;
        $absensiLabels = [];
        $absensiData = [];

        try {
            $responseAbsensi = $apiService->request('GET', 'trainer-attendances');
            if (!empty($responseAbsensi) && is_array($responseAbsensi) && !isset($responseAbsensi['message']) && (isset($responseAbsensi['data']) || isset($responseAbsensi[0]))) {
                $rawList = $responseAbsensi['data'] ?? $responseAbsensi;
                if (is_array($rawList) && !empty($rawList)) {
                    foreach ($rawList as $item) {
                        $absensiMentorList[] = [
                            'nama_mentor'      => $item['trainer_name'] ?? $item['trainer']['name'] ?? $item['nama_mentor'] ?? $item['nama'] ?? 'Instruktur',
                            'tanggal'          => $item['date'] ?? $item['tanggal'] ?? null,
                            'status_kehadiran' => $item['status'] ?? $item['status_kehadiran'] ?? 'Hadir',
                            'keterangan'       => $item['notes'] ?? $item['keterangan'] ?? null,
                            'raw_date'         => $item['date'] ?? $item['tanggal'] ?? null
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('notice', 'Dashboard API trainer attendances error: ' . $e->getMessage());
        }

        // Jika endpoint API trainer-attendances belum terisi, bangun dari penugasan kelas trainer Laravel & riwayat KBM
        if (empty($absensiMentorList)) {
            $classTrainerMap = [];
            if (!empty($dataKelasApi) && is_array($dataKelasApi)) {
                foreach ($dataKelasApi as $c) {
                    $cid = (int)$c['id'];
                    if (!empty($c['trainers']) && is_array($c['trainers'])) {
                        $classTrainerMap[$cid] = $c['trainers'][0]['name'] ?? 'Instruktur';
                    }
                }
            }
            // Mapping kelas & mentor Laravel
            $classTrainerMap[2]   = $classTrainerMap[2] ?? 'Nur Arifin';
            $classTrainerMap[192] = $classTrainerMap[192] ?? 'Nur Arifin';
            $classTrainerMap[183] = $classTrainerMap[183] ?? 'Khafid Anwar';
            $classTrainerMap[3]   = $classTrainerMap[3] ?? 'Hasan';
            $classTrainerMap[174] = $classTrainerMap[174] ?? 'Rizki Setiawan';
            $classTrainerMap[191] = $classTrainerMap[191] ?? 'Nabiel';
            $classTrainerMap[193] = $classTrainerMap[193] ?? 'Nabiel';

            if ($db->tableExists('jadwal')) {
                $jadwalList = $db->table('jadwal')
                    ->select('id_jadwal, id_kelas, pertemuan_ke, tanggal_kbm, waktu_mulai')
                    ->orderBy('tanggal_kbm', 'DESC')
                    ->orderBy('waktu_mulai', 'DESC')
                    ->get()->getResultArray();

                $hadirJadwalIds = [];
                if ($db->tableExists('absensi')) {
                    $allAbsensi = $db->table('absensi')
                        ->groupStart()
                            ->where('status', 'hadir')
                            ->orWhere('status', 'terlambat')
                            ->orWhere('status', 'Hadir')
                        ->groupEnd()
                        ->get()->getResultArray();
                    $hadirJadwalIds = array_unique(array_filter(array_merge(
                        array_column($allAbsensi, 'id_jadwal'),
                        array_column($allAbsensi, 'id_jadwal_kelas')
                    )));
                }

                foreach ($jadwalList as $j) {
                    $isHadir = in_array($j['id_jadwal'], $hadirJadwalIds);
                    $trainerName = $classTrainerMap[$j['id_kelas']] ?? null;
                    if (!$trainerName && $db->tableExists('kelas')) {
                        $kRow = $db->table('kelas')->where('id_kelas', $j['id_kelas'])->get()->getRowArray();
                        if ($kRow && !empty($kRow['id_mentor']) && $db->tableExists('mentor')) {
                            $mRow = $db->table('mentor')->where('id_mentor', $kRow['id_mentor'])->get()->getRowArray();
                            $trainerName = $mRow['nama_mentor'] ?? null;
                        }
                    }

                    if ($trainerName && $isHadir) {
                        $absensiMentorList[] = [
                            'nama_mentor'      => $trainerName,
                            'tanggal'          => !empty($j['tanggal_kbm']) ? date('d M Y', strtotime($j['tanggal_kbm'])) : '-',
                            'status_kehadiran' => 'Hadir',
                            'raw_date'         => $j['tanggal_kbm']
                        ];
                    }
                }
            }
        }

        // Siapkan data diagram absensi instruktur
        if (!empty($absensiMentorList)) {
            $absensiInstrukturTersedia = true;
            $keteranganAbsensi = null;

            // Agregasi kehadiran per tanggal sesi KBM
            $dateGroups = [];
            foreach ($absensiMentorList as $row) {
                $d = !empty($row['raw_date']) ? date('d M', strtotime($row['raw_date'])) : ($row['tanggal'] ?? '-');
                $dateGroups[$d] = ($dateGroups[$d] ?? 0) + 1;
            }
            $absensiLabels = array_reverse(array_keys($dateGroups));
            $absensiData   = array_reverse(array_values($dateGroups));
        } else {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $curM = (int)date('n');
            for ($i = max(1, $curM - 5); $i <= $curM; $i++) {
                $absensiLabels[] = $months[$i - 1];
                $absensiData[]   = 0;
            }
        }

        $data = [
            'title'                       => 'Dashboard',
            'total_kelas'                 => $totalKelas,
            'total_mentor'                => $totalMentor,
            'total_peserta'               => $totalPesertaAktif,
            'pending_validasi'            => $db->table('pendaftaran')->where('status', 'pending')->countAllResults(),
            'pendaftaran_pending'         => $pendaftaranPending,
            'angket_data'                 => $angketData,
            'total_responden_angket'      => $totalRespondenAngket,
            'absensi_instruktur_tersedia' => $absensiInstrukturTersedia,
            'absensi_mentor_list'         => $absensiMentorList,
            'keterangan_absensi'          => $keteranganAbsensi,
            'absensi_labels'              => $absensiLabels,
            'absensi_data'                => $absensiData
        ];

        return view('admin/dashboard', $data);
    }

    // --- MASTER KELAS ---

    // --- MASTER KELAS ---

    public function masterKelas()
    {
        $apiService = new \App\Services\LaravelApiService();
        $kelasData = [];
        try {
            $response = $apiService->request('GET', 'classes');
            $kelasApi = $response['data'] ?? $response ?? [];
            if (!empty($kelasApi) && is_array($kelasApi)) {
                foreach ($kelasApi as $k) {
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

                    $kelasData[] = [
                        'id_kelas'            => $k['id'],
                        'nama_kelas'          => $k['name'] ?? null,
                        'thumbnail'           => $foto,
                        'foto'                => $foto,
                        'kategori'            => $kategori,
                        'status'              => $k['status'] ?? null,
                        'deskripsi'           => $deskripsi,
                        'ringkasan'           => $ringkasan,
                        'nama_mentor'         => $namaMentor,
                        'jumlah_pertemuan'    => $meetings,
                        'kapasitas'           => $kapasitas,
                        'kapasitas_tersedia'  => $kapasitasTersedia,
                        'harga_reguler'       => $hargaReguler,
                        'harga_privat'        => $hargaPrivat,
                        'tanggal_mulai_kelas' => $startDate,
                        'jenis_kelas'         => $k['jenis_kelas'] ?? $k['type'] ?? null,
                        'tipe_kelas'          => $k['jenis_kelas'] ?? $k['type'] ?? null,
                        'lokasi_media'        => $lokasiMedia,
                        'metode_pembelajaran' => $k['schedule']['method'] ?? $k['method'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal memuat master kelas dari API Laravel: ' . $e->getMessage());
        }

        $mentorModel = new \App\Models\MentorModel();

        $data = [
            'title'  => 'Master Kelas',
            'kelas'  => $kelasData,
            'mentor' => $mentorModel->findAll()
        ];

        return view('admin/master_kelas/index', $data);
    }



    public function simpanKelas()

    {

        $allPostData = $this->request->getPost();



        // Cek apakah id_mentor ada dalam data yang dikirim

        if (!isset($allPostData['id_mentor']) || empty($allPostData['id_mentor'])) {

            die("Error: 'id_mentor' tidak ditemukan atau kosong. Data yang diterima: " . print_r($allPostData, true));

        }



        $kelasModel = new KelasModel();



        $fileThumbnail = $this->request->getFile('foto');

        $namaThumbnail = 'default-kelas.jpg';



        if ($fileThumbnail && $fileThumbnail->isValid() && !$fileThumbnail->hasMoved()) {

            $namaThumbnail = $fileThumbnail->getRandomName();

            $fileThumbnail->move('uploads/kelas/', $namaThumbnail);

        }



        $data = [

    'id_mentor'           => $this->request->getPost('id_mentor'),

    'kategori'            => $this->request->getPost('kategori'),

    'nama_kelas'          => $this->request->getPost('nama_kelas'),

    'deskripsi'           => $this->request->getPost('deskripsi'),

    'kapasitas'           => $this->request->getPost('kapasitas'),

    'jumlah_pertemuan'    => $this->request->getPost('jumlah_pertemuan'),

    'harga_reguler'       => $this->request->getPost('harga_reguler'),

    'harga_privat'        => $this->request->getPost('harga_privat'),

    'tanggal_mulai_kelas' => $this->request->getPost('tanggal_mulai_kelas'),

    'ringkasan'           => $this->request->getPost('ringkasan'),

    'status'              => $this->request->getPost('status'),

    'tipe_kelas'          => $this->request->getPost('tipe_kelas'),

    'lokasi_pelatihan'    => $this->request->getPost('lokasi_pelatihan') ?: '-',

    'thumbnail'           => $namaThumbnail,

];



        if (!$kelasModel->insert($data)) {

            dd($kelasModel->errors());

        }



        return redirect()->to('/admin/master-kelas')->with('success', 'Master kelas berhasil ditambahkan!');

    }



    public function editKelas($id)

    {

        $kelasModel  = new KelasModel();

        $mentorModel = new MentorModel();



        $data = [

            'title'  => 'Edit Kelas',

            'kelas'  => $kelasModel->find($id),

            'mentor' => $mentorModel->findAll()

        ];



        return view('admin/master_kelas/edit', $data);

    }



    public function updateKelas($id)

    {

        $kelasModel = new KelasModel();



        $idMentor = $this->request->getPost('id_mentor');

        if (empty($idMentor)) {

            return redirect()->back()->withInput()->with('error', 'Silakan pilih mentor pengampu terlebih dahulu!');

        }



        // Ambil data kelas lama untuk pengecekan gambar

        $kelasLama = $kelasModel->find($id);



        $data = [

    'nama_kelas'          => $this->request->getPost('nama_kelas'),

    'id_mentor'           => $this->request->getPost('id_mentor'),

    'kategori'            => $this->request->getPost('kategori'),

    'tipe_kelas'          => $this->request->getPost('tipe_kelas'),

    'harga_reguler'       => $this->request->getPost('harga_reguler'),

    'harga_privat'        => $this->request->getPost('harga_privat'),

    'jumlah_pertemuan'    => $this->request->getPost('jumlah_pertemuan'),

    'kapasitas'           => $this->request->getPost('kapasitas'),

    'tanggal_mulai_kelas' => $this->request->getPost('tanggal_mulai_kelas'),

    'ringkasan'           => $this->request->getPost('ringkasan'),

    'deskripsi'           => $this->request->getPost('deskripsi'),

    'status'              => $this->request->getPost('status'),

    'lokasi_pelatihan'    => $this->request->getPost('lokasi_pelatihan') ?: '-',

];



        // Cek apakah ada file foto/thumbnail banner baru yang di-upload

        $fileThumbnail = $this->request->getFile('foto');

        if ($fileThumbnail && $fileThumbnail->isValid() && !$fileThumbnail->hasMoved()) {

            $namaThumbnail = $fileThumbnail->getRandomName();

            $fileThumbnail->move('uploads/kelas/', $namaThumbnail);



            $data['thumbnail'] = $namaThumbnail;



            if (!empty($kelasLama['thumbnail']) && $kelasLama['thumbnail'] != 'default-kelas.jpg') {

                $pathLama = 'uploads/kelas/' . $kelasLama['thumbnail'];

                if (file_exists($pathLama)) {

                    unlink($pathLama);

                }

            }

        }



        $kelasModel->update($id, $data);



        return redirect()->to('/admin/master-kelas')->with('success', 'Data kelas berhasil diperbarui!');

    }



    public function jadwalKelas($id_kelas)
    {
        $db = \Config\Database::connect();

        // Ambil data kelas dari API Laravel
        $apiService = new \App\Services\LaravelApiService();
        $kelas = [];
        try {
            $response = $apiService->request('GET', 'classes/' . $id_kelas);
            $kelasApi = $response['data'] ?? $response ?? [];
            if (!empty($kelasApi) && isset($kelasApi['id'])) {
                $kelas = [
                    'id_kelas' => $kelasApi['id'],
                    'nama_kelas' => $kelasApi['name'] ?? 'Kelas',
                    'id_mentor' => (!empty($kelasApi['trainers']) && isset($kelasApi['trainers'][0]['id'])) ? $kelasApi['trainers'][0]['id'] : null,
                    'nama_mentor' => (!empty($kelasApi['trainers']) && isset($kelasApi['trainers'][0]['name'])) ? $kelasApi['trainers'][0]['name'] : 'Mentor Belum Ditentukan',
                ];
            }
        } catch (\Exception $e) {
            // Abaikan agar ditangkap empty() di bawah
        }

        if (empty($kelas)) {
            // Fallback ke tabel kelas lokal jika data tidak ada di API
            $kelas = $db->table('kelas')->where('id_kelas', $id_kelas)->get()->getRowArray();
            if (empty($kelas)) {
                return redirect()->to(base_url('admin/master-kelas'))->with('error', 'Data kelas tidak ditemukan di sistem.');
            }
        }



    // Cari nama mentor secara otomatis dan aman

    $nama_mentor = 'Mentor Belum Ditentukan';

    if (!empty($kelas['id_mentor'])) {

        $listTable = ['mentor', 'users', 'admin', 'pengguna'];

        $listKolomNama = ['nama', 'nama_lengkap', 'username', 'nama_mentor', 'fullname'];

        $listPK = ['id_mentor', 'id', 'id_user', 'user_id'];



        $ketemu = false;

        foreach ($listTable as $tableName) {

            if ($ketemu) break;

            if ($db->tableExists($tableName)) {

                foreach ($listPK as $pk) {

                    if ($ketemu) break;

                    foreach ($listKolomNama as $colName) {

                        try {

                            $cekMentor = $db->table($tableName)

                                            ->select($colName)

                                            ->where($pk, $kelas['id_mentor'])

                                            ->get()

                                            ->getRowArray();



                            if ($cekMentor && !empty($cekMentor[$colName])) {

                                $nama_mentor = $cekMentor[$colName];

                                $ketemu = true;

                                break;

                            }

                        } catch (\Exception $e) {

                            continue;

                        }

                    }

                }

            }

        }

    }



    // Masukkan nama mentor ke array kelas

    $kelas['nama_mentor'] = $nama_mentor;



    // Ambil data jadwal berdasarkan id_kelas

    $jadwal = $db->table('jadwal')

                 ->where('id_kelas', $id_kelas)

                 ->orderBy('pertemuan_ke', 'ASC')

                 ->get()

                 ->getResultArray();



    $data = [

        'title'        => 'Jadwal Kelas: ' . ($kelas['nama_kelas'] ?? ''),

        'detail_kelas' => $kelas,

        'jadwal'       => $jadwal

    ];



    return view('admin/master_kelas/jadwal', $data);

}



    public function simpanJadwal()

    {

        $db = \Config\Database::connect();

        $id_kelas = $this->request->getPost('id_kelas');



        if (empty($id_kelas)) {

            return redirect()->back()->with('error', 'ID Kelas tidak ditemukan.');

        }



        $data = [

            'id_kelas'          => $id_kelas,

            'pertemuan_ke'      => $this->request->getPost('pertemuan_ke'),

            'tanggal_kbm'       => $this->request->getPost('tanggal_kbm'),

            'waktu_mulai'       => $this->request->getPost('waktu_mulai'),

            'waktu_selesai'     => $this->request->getPost('waktu_selesai'),

            'materi' => '',

            'ruangan_atau_link' => $this->request->getPost('ruangan_atau_link'),



        ];



        $db->table('jadwal')->insert($data);



        return redirect()->to(base_url('admin/master-kelas/jadwal/' . $id_kelas))->with('pesan', 'Jadwal berhasil ditambahkan');

    }



    public function bukaAbsen($id_jadwal)

    {

        $db = \Config\Database::connect();



        // Buat token acak 4 digit angka

        $token = rand(1000, 9999);



        $data = [

            'absensi_dibuka' => 1,

            'token_absen'    => $token

        ];



        $db->table('jadwal')->where('id_jadwal', $id_jadwal)->update($data);



        // Ambil data jadwal untuk redirect kembali ke halaman detail kelas

        $jadwal = $db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();



        return redirect()->to(base_url('admin/master-kelas/jadwal/' . $jadwal['id_kelas']))

                         ->with('success', 'Absensi dibuka! Token untuk pertemuan ini: ' . $token);

    }



    public function tutupAbsen($id_jadwal)

    {

        $db = \Config\Database::connect();



        $data = [

            'absensi_dibuka' => 1,

            'token_absen'    => null

        ];



        $db->table('jadwal')->where('id_jadwal', $id_jadwal)->update($data);

        $jadwal = $db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();



        return redirect()->to(base_url('admin/master-kelas/jadwal/' . $jadwal['id_kelas']))

                         ->with('success', 'Absensi berhasil ditutup.');

    }



    public function prosesAbsen()

    {

        $db = \Config\Database::connect();

        $id_jadwal = $this->request->getPost('id_jadwal');

        $tokenInput = $this->request->getPost('token_absen');

        $id_user = session()->get('id_users'); // ID peserta yang sedang login



        // Ambil data jadwal

        $jadwal = $db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();



        // 1. Cek apakah absensi sedang dibuka

        if (!$jadwal || $jadwal['absensi_dibuka'] != 1) {

            return redirect()->back()->with('error', 'Maaf, absensi untuk pertemuan ini belum dibuka oleh mentor.');

        }



        // 2. Cek apakah token yang dimasukkan sesuai

        if ($jadwal['token_absen'] !== $tokenInput) {

            return redirect()->back()->with('error', 'Token absensi salah! Silakan tanyakan token terbaru ke mentor.');

        }



        // 3. Cek apakah peserta sudah pernah absen di jadwal ini sebelumnya

        $sudahAbsen = $db->table('absensi')

                         ->where('id_jadwal', $id_jadwal)

                         ->where('id_user', $id_user)

                         ->countAllResults();



        if ($sudahAbsen > 0) {

            return redirect()->back()->with('error', 'Anda sudah melakukan absensi untuk pertemuan ini.');

        }



        // 4. Simpan data absensi jika semua valid

        $db->table('absensi')->insert([

            'id_jadwal'        => $id_jadwal,

            'id_user'          => $id_user,

            'waktu_absen'      => date('Y-m-d H:i:s'),

            'status_kehadiran' => 'Hadir'

        ]);



        return redirect()->back()->with('success', 'Berhasil absen! Kehadiran Anda telah dicatat.');

    }



    public function monitoringAbsensi()

    {

        $db = \Config\Database::connect();

        $absensi = $db->table('absensi')

            ->select('absensi.*, users.nama AS nama_mentor, kelas.nama_kelas, jadwal.pertemuan_ke, jadwal.materi, jadwal.tanggal_kbm')

            ->join('users', 'users.id_users = absensi.id_user', 'inner')

            ->join('mentor', 'mentor.id_users = users.id_users', 'inner')

            ->join('jadwal', 'jadwal.id_jadwal = absensi.id_jadwal', 'inner')

            ->join('kelas', 'kelas.id_kelas = jadwal.id_kelas', 'inner')

            ->orderBy('absensi.waktu_absen', 'DESC')

            ->get()->getResultArray();



        return view('admin/monitoring_absensi', ['title' => 'Monitoring Absensi Mentor', 'absensi' => $absensi]);

    }



public function updateJadwal($id_jadwal)

{

    $db = \Config\Database::connect();

    $jadwalLama = $db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();



    $data = [

        'pertemuan_ke'      => $this->request->getPost('pertemuan_ke'),

        'tanggal_kbm'       => $this->request->getPost('tanggal_kbm'),

        'waktu_mulai'       => $this->request->getPost('waktu_mulai'),

        'waktu_selesai'     => $this->request->getPost('waktu_selesai'),

        'ruangan_atau_link' => $this->request->getPost('ruangan_atau_link'),

    ];



    $db->table('jadwal')->where('id_jadwal', $id_jadwal)->update($data);



    return redirect()->to(base_url('admin/master-kelas/jadwal/' . $jadwalLama['id_kelas']))->with('success', 'Jadwal pertemuan berhasil diperbarui.');

}



public function hapusJadwal($id_jadwal)

{

    $db = \Config\Database::connect();



    $jadwal = $db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();



    if ($jadwal) {

        $id_kelas = $jadwal['id_kelas'];



        if (!empty($jadwal['file_pdf']) && file_exists('uploads/materi/' . $jadwal['file_pdf'])) {

            @unlink('uploads/materi/' . $jadwal['file_pdf']);

        }



        $db->table('jadwal')->where('id_jadwal', $id_jadwal)->delete();



        return redirect()->to(base_url('admin/master-kelas/jadwal/' . $id_kelas))->with('success', 'Jadwal berhasil dihapus.');

    }



    return redirect()->back()->with('error', 'Data jadwal tidak ditemukan.');

}



    public function mentor()
    {
        $apiService = new \App\Services\LaravelApiService();
        $mentorData = [];
        $totalAktif = 0;
        try {
            $response = $apiService->request('GET', 'trainers');
            $trainersApi = $response['data'] ?? $response ?? [];
            if (!empty($trainersApi) && is_array($trainersApi)) {
                foreach ($trainersApi as $t) {
                    $status = strtolower($t['status'] ?? 'active') === 'active' ? 'Aktif' : 'Nonaktif';
                    if ($status === 'Aktif') $totalAktif++;
                    
                    // Pemetaan NIP resmi dari API Laravel
                    $nipRaw = $t['nip'] ?? $t['employee_id'] ?? $t['nip_instruktur'] ?? $t['nomor_induk'] ?? $t['id'] ?? null;
                    $nip = (!empty($nipRaw) && $nipRaw !== '-') ? trim((string)$nipRaw) : null;
                    
                    $phoneRaw = $t['phone'] ?? $t['telepon'] ?? $t['no_hp'] ?? null;
                    $telepon = (!empty($phoneRaw) && $phoneRaw !== '-') ? trim((string)$phoneRaw) : null;
                    
                    $mentorData[] = [
                        'id_mentor'   => $t['id'] ?? 0,
                        'nip'         => $nip,
                        'nama_mentor' => $t['name'] ?? $t['nama'] ?? 'Instruktur',
                        'email'       => (!empty($t['email']) && $t['email'] !== '-') ? trim((string)$t['email']) : null,
                        'telepon'     => $telepon,
                        'status'      => $status,
                        'cv'          => $t['cv'] ?? '',
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal memuat mentor dari API Laravel: ' . $e->getMessage());
        }

        $data = [
            'title'       => 'Manajemen Mentor - Panel Admin',
            'mentor'      => $mentorData,
            'total_aktif' => $totalAktif
        ];

        return view('admin/mentor/index', $data);
    }



public function simpan()

{

    $db = \Config\Database::connect();

    $nama = trim((string) $this->request->getPost('nama_mentor'));

    $email = strtolower(trim((string) $this->request->getPost('email')));

    $password = (string) $this->request->getPost('password');

    $konfirmasiPassword = (string) $this->request->getPost('konfirmasi_password');



    if ($nama === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {

        return redirect()->back()->withInput()->with('error', 'Nama dan alamat email yang valid wajib diisi.');

    }



    if (strlen($password) < 8) {

        return redirect()->back()->withInput()->with('error', 'Password mentor minimal terdiri dari 8 karakter.');

    }



    if ($password !== $konfirmasiPassword) {

        return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak sama.');

    }



    if ($db->table('users')->where('email', $email)->countAllResults() > 0) {

        return redirect()->back()->withInput()->with('error', 'Email tersebut sudah terdaftar sebagai akun pengguna.');

    }



    $data = [

        'nama_mentor' => $nama,

        'email'       => $email,

        'telepon'     => $this->request->getPost('telepon'),

        'keahlian'    => $this->request->getPost('keahlian'),

        'pengalaman'  => $this->request->getPost('pengalaman'),

        'bio'         => $this->request->getPost('bio'),

        'status'      => $this->request->getPost('status'),

    ];



    $fileCv = $this->request->getFile('cv');



    if ($fileCv && $fileCv->isValid() && ! $fileCv->hasMoved()) {

        if (! is_dir(FCPATH . 'uploads/cv')) {

            mkdir(FCPATH . 'uploads/cv', 0755, true);

        }



        $namaFileCv = $fileCv->getRandomName();

        $fileCv->move(FCPATH . 'uploads/cv', $namaFileCv);

        $data['cv'] = $namaFileCv;

    }



    $db->transStart();



    $lastUser = $db->table('users')

        ->selectMax('id_users')

        ->get()

        ->getRowArray();



    $idUserBaru = ((int) ($lastUser['id_users'] ?? 0)) + 1;



    $db->table('users')->insert([

        'id_users'    => $idUserBaru,

        'nama'        => $nama,

        'email'       => $email,

        'password'    => password_hash($password, PASSWORD_DEFAULT),

        'role'        => 'mentor',

        'no_hp'       => $this->request->getPost('telepon'),

        'created_at'  => date('Y-m-d H:i:s'),

        'updated_at'  => date('Y-m-d H:i:s'),

    ]);



    $data['id_users'] = $idUserBaru;



    $db->table('mentor')->insert($data);



    $db->transComplete();



    if (! $db->transStatus()) {

        return redirect()->back()

            ->withInput()

            ->with('error', 'Akun instruktur gagal dibuat. Silakan coba kembali.');

    }



    return redirect()

        ->to(base_url('admin/mentor'))

        ->with('success', 'Akun login dan profil instruktur berhasil ditambahkan.');

}



    public function updateMentor($id)

    {

        $mentorModel = new MentorModel();

        $db = \Config\Database::connect();

        $mentor = $mentorModel->find($id);

        if (! $mentor) {

            return redirect()->to(base_url('admin/mentor'))->with('error', 'Data instruktur tidak ditemukan.');

        }



        $nama = trim((string) $this->request->getPost('nama_mentor'));

        $email = strtolower(trim((string) $this->request->getPost('email')));

        $password = (string) $this->request->getPost('password');

        $konfirmasiPassword = (string) $this->request->getPost('konfirmasi_password');

        if ($nama === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {

            return redirect()->back()->withInput()->with('error', 'Nama dan alamat email yang valid wajib diisi.');

        }

        if ($password !== '' && strlen($password) < 8) {

            return redirect()->back()->withInput()->with('error', 'Password baru minimal terdiri dari 8 karakter.');

        }

        if ($password !== $konfirmasiPassword) {

            return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak sama.');

        }



        $akun = ! empty($mentor['id_users']) ? $db->table('users')->where('id_users', $mentor['id_users'])->get()->getRowArray() : null;

        $akunMentor = $akun && $akun['role'] === 'mentor';

        $emailDipakai = $db->table('users')->where('email', $email);

        if ($akunMentor) $emailDipakai->where('id_users !=', $akun['id_users']);

        if ($emailDipakai->countAllResults() > 0) {

            return redirect()->back()->withInput()->with('error', 'Email tersebut sudah dipakai akun lain.');

        }

        if (! $akunMentor && $password === '') {

            return redirect()->back()->withInput()->with('error', 'Password baru wajib diisi untuk membuat akun login instruktur ini.');

        }





$data = [

    'nip'         => $this->request->getPost('nip'),

    'nama_mentor' => $nama,

    'email'       => $email,

    'telepon'     => $this->request->getPost('telepon'),

    'keahlian'    => $this->request->getPost('keahlian'),

    'pengalaman'  => $this->request->getPost('pengalaman'),

    'bio'         => $this->request->getPost('bio'),

    'status'      => $this->request->getPost('status'),

];



        $fileCv = $this->request->getFile('cv');

        if ($fileCv && $fileCv->isValid() && !$fileCv->hasMoved()) {

            if (! is_dir(FCPATH . 'uploads/cv')) mkdir(FCPATH . 'uploads/cv', 0755, true);

            $namaFileCv = $fileCv->getRandomName();

            $fileCv->move(FCPATH . 'uploads/cv', $namaFileCv);

            $data['cv'] = $namaFileCv;

        }



        $db->transStart();

        if ($akunMentor) {

            $dataAkun = ['nama' => $nama, 'email' => $email, 'no_hp' => $this->request->getPost('telepon'), 'updated_at' => date('Y-m-d H:i:s')];

            if ($password !== '') $dataAkun['password'] = password_hash($password, PASSWORD_DEFAULT);

            $db->table('users')->where('id_users', $akun['id_users'])->update($dataAkun);

        } else {

            $lastUser = $db->table('users')->selectMax('id_users')->get()->getRowArray();

            $idUserBaru = ((int) ($lastUser['id_users'] ?? 0)) + 1;

            $db->table('users')->insert(['id_users' => $idUserBaru, 'nama' => $nama, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => 'mentor', 'no_hp' => $this->request->getPost('telepon'), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);

            $data['id_users'] = $idUserBaru;

        }

        $db->table('mentor')->where('id_mentor', $id)->update($data);

        $db->transComplete();

        if (! $db->transStatus()) return redirect()->back()->withInput()->with('error', 'Perubahan instruktur gagal disimpan.');



        return redirect()->to(base_url('admin/mentor'))->with('success', 'Data profil dan akun login instruktur berhasil diperbarui.');

    }



    public function editMentor($id)

    {

        $mentorModel = new MentorModel();



        $data = [

            'title'  => 'Edit Instruktur',

            'mentor' => $mentorModel->find($id)

        ];



        return view('admin/mentor/edit', $data);

    }



    public function deleteMentor($id)

    {

        $mentorModel = new MentorModel();

        $db = \Config\Database::connect();

        $mentor = $mentorModel->find($id);

        if (!$mentor) {

            return redirect()->to(base_url('admin/mentor'))->with('error', 'Data instruktur tidak ditemukan.');

        }



        if (!empty($mentor['cv']) && file_exists(FCPATH . 'uploads/cv/' . $mentor['cv'])) {

            @unlink(FCPATH . 'uploads/cv/' . $mentor['cv']);

        }



        if (!empty($mentor['id_users'])) {

            $user = $db->table('users')->where('id_users', $mentor['id_users'])->get()->getRowArray();

            if ($user && $user['role'] === 'mentor') {

                $db->table('users')->where('id_users', $mentor['id_users'])->delete();

            }

        }



        $mentorModel->delete($id);



        return redirect()->to(base_url('admin/mentor'))->with('success', 'Data instruktur berhasil dihapus.');

    }



    public function absen()

    {

        $db = \Config\Database::connect();



        $data = [

            'title'  => 'Monitoring Absensi',

            'absen'  => $db->table('absen')

                        ->select('absen.*, kelas.nama_kelas')

                        ->join('kelas', 'kelas.id_kelas = absen.id_kelas', 'left')

                        ->get()

                        ->getResultArray()

        ];



        return view('admin/absen/index', $data);

    }



    public function dataPeserta()

    {

        $db = \Config\Database::connect();
        $apiService = new \App\Services\LaravelApiService();
        $apiService->syncParticipantsFromLaravel();

        $keyword         = trim((string) ($this->request->getGet('keyword') ?? ''));

        $idKelas         = trim((string) ($this->request->getGet('id_kelas') ?? ''));

        $filterStatus    = strtolower(trim((string) ($this->request->getGet('status') ?? '')));

        $tempatPelatihan = trim((string) ($this->request->getGet('tempat_pelatihan') ?? ''));

        $perPage         = 10;

        $page            = max(1, (int) ($this->request->getGet('page') ?? 1));

        $offset          = ($page - 1) * $perPage;



        $buildQuery = function () use ($db, $keyword, $idKelas, $filterStatus, $tempatPelatihan) {

            $builder = $db->table('pendaftaran')

                ->select('

                    pendaftaran.*,

                    COALESCE(NULLIF(pendaftaran.nama, ""), users.nama) AS nama_lengkap,

                    COALESCE(NULLIF(pendaftaran.no_hp, ""), users.no_hp) AS no_hp_terbaru,

                    COALESCE(NULLIF(pendaftaran.jenis_kelamin, ""), users.jenis_kelamin) AS gender_terbaru,

                    COALESCE(NULLIF(pendaftaran.email, ""), users.email) AS email_terbaru,

                    pendaftaran.nis AS resolved_nis,

                    kelas.nama_kelas,

                    kelas.kategori AS kategori_kelas_master,

                    COALESCE(NULLIF(kelas.lokasi_pelatihan, "-"), NULLIF(kelas.lokasi_pelatihan, ""), pendaftaran.lokasi_pelatihan, "-") AS tempat_pelatihan

                ')

                ->join('users', 'users.id_users = pendaftaran.id_users', 'left')

                ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left');



            if ($keyword !== '') {

                $builder->groupStart()

                    ->like('pendaftaran.nama', $keyword)

                    ->orLike('users.nama', $keyword)

                    ->orLike('pendaftaran.nis', $keyword)

                    ->orLike('pendaftaran.no_hp', $keyword)

                    ->orLike('users.no_hp', $keyword)

                    ->orLike('pendaftaran.email', $keyword)

                    ->orLike('kelas.nama_kelas', $keyword)

                ->groupEnd();

            }



            if ($idKelas !== '') {

                $builder->where('pendaftaran.id_kelas', $idKelas);

            }



            // Perbaikan Filter Tempat Pelatihan agar lebih fleksibel

            if ($tempatPelatihan !== '') {

                $builder->groupStart()

                    ->where('kelas.lokasi_pelatihan', $tempatPelatihan)

                    ->orWhere('pendaftaran.lokasi_pelatihan', $tempatPelatihan)

                ->groupEnd();

            }



            if ($filterStatus !== '') {

                if (in_array($filterStatus, ['aktif', 'valid', 'disetujui'], true)) {

                    $builder->groupStart()

                        ->where('pendaftaran.status_pembayaran', 'valid')

                        ->orWhere('pendaftaran.status_pendaftaran', 'Disetujui')

                        ->orWhere('pendaftaran.status', 'Disetujui')

                    ->groupEnd();

                } elseif (in_array($filterStatus, ['menunggu', 'pending'], true)) {

                    $builder->groupStart()

                        ->where('pendaftaran.status_pembayaran', 'pending')

                        ->orWhere('pendaftaran.status_pendaftaran', 'Menunggu')

                        ->orWhere('pendaftaran.status', 'Pending')

                    ->groupEnd();

                } elseif (in_array($filterStatus, ['ditolak', 'rejected'], true)) {

                    $builder->groupStart()

                        ->where('pendaftaran.status_pembayaran', 'rejected')

                        ->orWhere('pendaftaran.status_pendaftaran', 'Ditolak')

                        ->orWhere('pendaftaran.status', 'Ditolak')

                    ->groupEnd();

                }

            }



            return $builder;

        };



        $totalRows = $buildQuery()->countAllResults();

        $totalPages = max(1, (int) ceil($totalRows / $perPage));

        if ($page > $totalPages) {

            $page = $totalPages;

            $offset = ($page - 1) * $perPage;

        }



        $peserta = $buildQuery()

            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')

            ->limit($perPage, $offset)

            ->get()

            ->getResultArray();



        $kelasList = $db->table('kelas')

            ->select('id_kelas, nama_kelas')

            ->orderBy('nama_kelas', 'ASC')

            ->get()

            ->getResultArray();



        // Ambil opsi tempat pelatihan unik yang tidak kosong dari kelas dan pendaftaran
        $locKelas = $db->table('kelas')
            ->select('lokasi_pelatihan')
            ->where('lokasi_pelatihan IS NOT NULL')
            ->where('lokasi_pelatihan !=', '')
            ->where('lokasi_pelatihan !=', '-')
            ->groupBy('lokasi_pelatihan')
            ->get()
            ->getResultArray();
        $locPendaftaran = $db->table('pendaftaran')
            ->select('lokasi_pelatihan')
            ->where('lokasi_pelatihan IS NOT NULL')
            ->where('lokasi_pelatihan !=', '')
            ->where('lokasi_pelatihan !=', '-')
            ->groupBy('lokasi_pelatihan')
            ->get()
            ->getResultArray();

        $allLocs = array_values(array_unique(array_filter(array_merge(
            array_column($locKelas, 'lokasi_pelatihan'),
            array_column($locPendaftaran, 'lokasi_pelatihan')
        ))));
        sort($allLocs);
        $tempatPelatihanOptions = array_map(fn($loc) => ['lokasi_pelatihan' => $loc], $allLocs);





        $summaryRows = $db->table('pendaftaran')

            ->select('status_pembayaran, status_pendaftaran, status')

            ->get()

            ->getResultArray();



        $summary = ['total' => count($summaryRows), 'disetujui' => 0, 'menunggu' => 0, 'ditolak' => 0];

        foreach ($summaryRows as $row) {

            $statusGabungan = strtolower(($row['status_pembayaran'] ?? '') . ' ' . ($row['status_pendaftaran'] ?? '') . ' ' . ($row['status'] ?? ''));

            if (str_contains($statusGabungan, 'valid') || str_contains($statusGabungan, 'disetujui')) {

                $summary['disetujui']++;

            } elseif (str_contains($statusGabungan, 'rejected') || str_contains($statusGabungan, 'ditolak')) {

                $summary['ditolak']++;

            } else {

                $summary['menunggu']++;

            }

        }



        $data = [

            'title'               => 'Data Peserta - Panel Admin',

            'peserta'             => $peserta,

            'kelasList'           => $kelasList,

            'tempatPelatihanList' => $tempatPelatihanOptions, // <-- Kirim ke view agar dropdown terisi

            'keyword'             => $keyword,

            'selectedKelas'       => $idKelas,

            'selectedStatus'      => $filterStatus,

            'selectedTempat'      => $tempatPelatihan,      // <-- Agar nilai select tidak reset saat difilter

            'summary'             => $summary,

            'pagination'          => [

                'page'       => $page,

                'perPage'    => $perPage,

                'totalRows'  => $totalRows,

                'totalPages' => $totalPages,

                'offset'     => $offset,

            ],

        ];



        return view('admin/data_peserta/index', $data);

    }



    public function pendaftaran()

    {
        $apiService = new \App\Services\LaravelApiService();
        $apiService->syncParticipantsFromLaravel();

        $pendaftaranModel = new PendaftaranModel();



        $data = [

            'title' => 'Data Pendaftaran Peserta',

            'pendaftaran' => $pendaftaranModel

                ->select('pendaftaran.*, users.nama, users.email, users.no_hp, kelas.nama_kelas')

                ->join('users', 'users.id_users = pendaftaran.id_users', 'left')

                ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')

                ->orderBy('pendaftaran.id_pendaftaran', 'DESC')

                ->findAll()

        ];



        return view('admin/pendaftaran/index', $data);

    }



    public function validasi()
    {
        $apiService = new \App\Services\LaravelApiService();
        $apiService->syncParticipantsFromLaravel();

        $db = \Config\Database::connect();
        $keyword = trim((string) ($this->request->getGet('keyword') ?? ''));
        $status = strtolower(trim((string) ($this->request->getGet('status') ?? '')));
        $idKelas = trim((string) ($this->request->getGet('id_kelas') ?? ''));
        $bulan = trim((string) ($this->request->getGet('bulan') ?? ''));

        // Ambil daftar kelas dari API Laravel (Single Source of Truth)
        $pelatihanCtrl = new \App\Controllers\Pelatihan();
        $laravelClassesMap = $pelatihanCtrl->fetchLaravelClassesMap($apiService);

        $matchingLaravelClassIds = [];
        if ($keyword !== '' && !empty($laravelClassesMap)) {
            foreach ($laravelClassesMap as $cid => $cInfo) {
                $haystack = strtolower(($cInfo['nama_kelas'] ?? '') . ' ' . ($cInfo['kategori'] ?? '') . ' ' . ($cInfo['kode_kelas'] ?? ''));
                if (str_contains($haystack, strtolower($keyword))) {
                    $matchingLaravelClassIds[] = (int) $cid;
                }
            }
        }

        // Konfigurasi Paginasi (10 data per halaman)
        $perPage = 10;
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $offset = ($page - 1) * $perPage;

        $buildQuery = function () use ($db, $keyword, $status, $idKelas, $bulan, $matchingLaravelClassIds) {
            $builder = $db->table('pendaftaran')
                ->select('pendaftaran.*, kelas.nama_kelas AS nama_kelas_lokal, kelas.tanggal_mulai_kelas AS tanggal_mulai_master')
                ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left');

            if ($keyword !== '') {
                $builder->groupStart()
                    ->like('pendaftaran.nama', $keyword)
                    ->orLike('pendaftaran.email', $keyword)
                    ->orLike('pendaftaran.no_hp', $keyword)
                    ->orLike('pendaftaran.nis', $keyword)
                    ->orLike('pendaftaran.pilihan_kelas', $keyword)
                    ->orLike('pendaftaran.pilihan_pelatihan', $keyword)
                    ->orLike('pendaftaran.kategori_kelas', $keyword)
                    ->orLike('pendaftaran.materi_privat', $keyword)
                    ->orLike('pendaftaran.custom_materi', $keyword)
                    ->orLike('kelas.nama_kelas', $keyword);
                if (!empty($matchingLaravelClassIds)) {
                    $builder->orWhereIn('pendaftaran.id_kelas', $matchingLaravelClassIds);
                }
                $builder->groupEnd();
            }

            if ($idKelas !== '') {
                $builder->where('pendaftaran.id_kelas', $idKelas);
            }

            if ($bulan !== '') {
                $builder->where('MONTH(pendaftaran.created_at)', (int) $bulan);
            }

            if ($status !== '') {
                if (in_array($status, ['disetujui', 'valid'], true)) {
                    $builder->where('pendaftaran.status_pendaftaran', 'Disetujui');
                } elseif (in_array($status, ['ditolak', 'rejected'], true)) {
                    $builder->groupStart()
                        ->where('pendaftaran.status_pembayaran', 'rejected')
                        ->orWhere('pendaftaran.status_pendaftaran', 'Ditolak')
                    ->groupEnd();
                } elseif (in_array($status, ['menunggu', 'pending'], true)) {
                    $builder->groupStart()
                        ->where('pendaftaran.status_pendaftaran', 'Menunggu')
                        ->orWhere('pendaftaran.status_pendaftaran IS NULL', null, false)
                        ->orWhere('pendaftaran.status_pendaftaran', '')
                    ->groupEnd();
                }
            }

            return $builder;
        };

        // Hitung total data untuk paginasi
        $totalRows = $buildQuery()->countAllResults(false);
        $totalPages = max(1, (int) ceil($totalRows / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $perPage;
        }

        // Ambil data sesuai halaman aktif (limit 10)
        $pendaftaran = $buildQuery()
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        // Sinkronkan & perkaya setiap baris dengan data kelas Laravel
        foreach ($pendaftaran as &$row) {
            $row = $pelatihanCtrl->syncAndEnrichPendaftaranRow($row, $laravelClassesMap, $apiService);
        }
        unset($row);

        // Susun opsi dropdown filter kelas dari Laravel API
        $kelasList = [];
        foreach ($laravelClassesMap as $cid => $cInfo) {
            $kelasList[] = [
                'id_kelas'   => $cid,
                'nama_kelas' => $cInfo['nama_kelas'] ?? ('Kelas #' . $cid),
            ];
        }
        usort($kelasList, static fn($a, $b) => strcasecmp($a['nama_kelas'] ?? '', $b['nama_kelas'] ?? ''));

        // Hitung ringkasan keseluruhan status pendaftaran
        $summaryRows = $db->table('pendaftaran')
            ->select('status_pembayaran, status_pendaftaran, status, jenis_kelas')
            ->get()
            ->getResultArray();
        $summary = ['total' => count($summaryRows), 'menunggu' => 0, 'disetujui' => 0, 'ditolak' => 0];
        foreach ($summaryRows as $sRow) {
            $stDaftar = strtolower(trim((string) ($sRow['status_pendaftaran'] ?? '')));
            $stBayar  = strtolower(trim((string) ($sRow['status_pembayaran'] ?? '')));
            if (in_array($stDaftar, ['ditolak', 'rejected', 'batal'], true) || in_array($stBayar, ['rejected', 'ditolak'], true)) {
                $summary['ditolak']++;
            } elseif (in_array($stDaftar, ['disetujui', 'approved', 'diterima'], true)) {
                $summary['disetujui']++;
            } else {
                $summary['menunggu']++;
            }
        }

        $data = [
            'title'       => 'Validasi Pendaftaran - Panel Admin',
            'pendaftaran' => $pendaftaran,
            'kelasList'   => $kelasList,
            'filters'     => [
                'keyword'  => $keyword,
                'status'   => $status,
                'id_kelas' => $idKelas,
                'bulan'    => $bulan,
            ],
            'summary'     => $summary,
            'pagination'  => [
                'page'       => $page,
                'perPage'    => $perPage,
                'totalRows'  => $totalRows,
                'totalPages' => $totalPages,
                'offset'     => $offset,
            ],
        ];

        return view('admin/validasi/index', $data);
    }

    public function updateValidasi($id_pendaftaran, $aksi)
    {
        if (!in_array($aksi, ['setuju', 'tolak'], true)) {
            return redirect()->to(base_url('admin/validasi'))
                ->with('error', 'Aksi tidak valid: ' . $aksi);
        }

        $db = \Config\Database::connect();
        $apiService = new \App\Services\LaravelApiService();
        $pelatihanCtrl = new \App\Controllers\Pelatihan();
        $laravelClassesMap = $pelatihanCtrl->fetchLaravelClassesMap($apiService);

        $db->transBegin();

        try {
            $pendaftaran = $db->query(
                'SELECT * FROM pendaftaran WHERE id_pendaftaran = ? FOR UPDATE',
                [(int) $id_pendaftaran]
            )->getRowArray();

            if (!$pendaftaran) {
                $db->transRollback();
                return redirect()->to(base_url('admin/validasi'))
                    ->with('error', 'Data pendaftaran tidak ditemukan.');
            }

            $idKelas = (int) ($pendaftaran['id_kelas'] ?? 0);
            $kelasInfo = $laravelClassesMap[$idKelas] ?? null;
            if ($idKelas > 0 && !$kelasInfo) {
                try {
                    $resSingle = $apiService->request('GET', 'classes/' . $idKelas);
                    $singleData = $resSingle['data'] ?? $resSingle ?? null;
                    if (!empty($singleData) && is_array($singleData)) {
                        $rawJenis = strtolower(trim((string) ($singleData['training']['jenis_kelas'] ?? $singleData['jenis_kelas'] ?? $singleData['type'] ?? '')));
                        $kelasInfo = [
                            'id_kelas'     => $singleData['id'] ?? $idKelas,
                            'kode_kelas'   => $singleData['code'] ?? '',
                            'nama_kelas'   => $singleData['name'] ?? '',
                            'kategori'     => $singleData['training']['name'] ?? 'Pelatihan',
                            'training_id'  => $singleData['training_id'] ?? ($singleData['training']['id'] ?? null),
                            'jenis_kelas'  => in_array($rawJenis, ['private', 'privat'], true) ? 'Privat' : 'Reguler',
                            'status'       => strtolower($singleData['status'] ?? 'active'),
                            'harga_privat' => (float) ($singleData['pricing']['price'] ?? $singleData['price'] ?? 0),
                        ];
                    }
                } catch (\Exception $eSingle) {
                    // ignore
                }
            }

            $isPrivat = strtolower(trim((string) ($pendaftaran['jenis_kelas'] ?? ($kelasInfo['jenis_kelas'] ?? 'Reguler')))) === 'privat';
            $laravelClassStatus = strtolower(trim((string) ($kelasInfo['status'] ?? '')));

            // Jika Kelas Privat belum disetujui di Laravel, status pendaftaran tetap Menunggu Persetujuan Admin Laravel
            $waitingLaravelPrivateApproval = false;
            if ($aksi === 'setuju' && $isPrivat) {
                $laravelRegSt = '';
                if (!empty($kelasInfo['registrants']) && is_array($kelasInfo['registrants'])) {
                    foreach ($kelasInfo['registrants'] as $r) {
                        if (($r['external_registration_id'] ?? '') === 'REG-' . $id_pendaftaran) {
                            $laravelRegSt = strtolower(trim((string)($r['status'] ?? '')));
                            break;
                        }
                    }
                }
                $isApprovedInLaravel = in_array($laravelRegSt, ['approved', 'active', 'confirmed', 'disetujui'], true);
                if (!$isApprovedInLaravel) {
                    $waitingLaravelPrivateApproval = true;
                }
            }

            $nisBaru = $pendaftaran['nis'] ?? null;

            $dataUpdate = [
                'status_pembayaran'  => $aksi === 'setuju' ? 'valid' : 'rejected',
                'status_pendaftaran' => $aksi === 'setuju'
                    ? ($waitingLaravelPrivateApproval ? 'Menunggu' : 'Disetujui')
                    : 'Ditolak',
                'status'             => $aksi === 'setuju'
                    ? ($waitingLaravelPrivateApproval ? 'Pending' : 'Disetujui')
                    : 'Ditolak',
            ];

            // Jika disetujui, gunakan satu NIS yang sama untuk peserta dengan email yang sama
            if ($aksi === 'setuju') {
                $pendaftaranLama = $db->table('pendaftaran')
                    ->select('nis')
                    ->where('email', $pendaftaran['email'] ?? null)
                    ->where('nis IS NOT NULL', null, false)
                    ->where('nis !=', '')
                    ->orderBy('id_pendaftaran', 'ASC')
                    ->get()
                    ->getRowArray();

                if (!empty($pendaftaranLama['nis'])) {
                    $nisBaru = $pendaftaranLama['nis'];
                } else {
                    $nisBaru = $this->generateNisPendaftaran($db, $pendaftaran);
                }

                // Samakan NIS hanya untuk kolom nis (tanpa mengubah status_pendaftaran kelas lain)
                $db->table('pendaftaran')
                    ->where('email', $pendaftaran['email'] ?? null)
                    ->update(['nis' => $nisBaru]);

                $dataUpdate['nis'] = $nisBaru;

                // Pastikan akun peserta di tabel users sudah tersedia dan terhubung ke id_users
                $userIdPendaftaran = $pendaftaran['id_users'] ?? null;
                if (empty($userIdPendaftaran) && !empty($pendaftaran['email'])) {
                    $userFound = $db->table('users')
                        ->where('LOWER(email)', strtolower($pendaftaran['email']))
                        ->get()
                        ->getRowArray();
                    if ($userFound) {
                        $userIdPendaftaran = (int) $userFound['id_users'];
                    } else {
                        $lastUser = $db->table('users')->selectMax('id_users')->get()->getRowArray();
                        $nextId   = ((int) ($lastUser['id_users'] ?? 0)) + 1;

                        $insertUser = [
                            'id_users'      => $nextId,
                            'nama'          => $pendaftaran['nama'] ?? 'Peserta',
                            'email'         => $pendaftaran['email'],
                            'no_hp'         => $pendaftaran['no_hp'] ?? '',
                            'jenis_kelamin' => $pendaftaran['jenis_kelamin'] ?? 'Laki-laki',
                            'password'      => password_hash('123456', PASSWORD_DEFAULT),
                            'role'          => 'peserta',
                            'created_at'    => date('Y-m-d H:i:s'),
                            'updated_at'    => date('Y-m-d H:i:s'),
                        ];
                        if ($db->fieldExists('asal_instansi', 'users') && !empty($pendaftaran['asal_instansi'])) {
                            $insertUser['asal_instansi'] = $pendaftaran['asal_instansi'];
                        }
                        if ($db->fieldExists('semester', 'users') && !empty($pendaftaran['semester'])) {
                            $insertUser['semester'] = $pendaftaran['semester'];
                        }

                        $db->table('users')->insert($insertUser);
                        $userIdPendaftaran = $nextId;
                    }

                    $dataUpdate['id_users'] = $userIdPendaftaran;

                    $db->table('pendaftaran')
                        ->where('email', $pendaftaran['email'])
                        ->where('id_users IS NULL', null, false)
                        ->update(['id_users' => $userIdPendaftaran]);
                }
            }

            $db->table('pendaftaran')
                ->where('id_pendaftaran', $id_pendaftaran)
                ->update($dataUpdate);

            if ($db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to(base_url('admin/validasi'))
                    ->with('error', 'Database gagal diperbarui.');
            }

            $db->transCommit();

            // Sinkronkan status pendaftaran ke API Laravel
            $pendaftaranSync = array_merge($pendaftaran, $dataUpdate);
            $pelatihanCtrl->syncRegistrationToLaravel($pendaftaranSync, $kelasInfo ?? [], $apiService);

        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to(base_url('admin/validasi'))
                ->with('error', 'Validasi gagal diproses: ' . $e->getMessage());
        }

        if (!empty($pendaftaran['email']) && empty($waitingLaravelPrivateApproval)) {
            $email = \Config\Services::email();
            $email->setTo($pendaftaran['email']);
            $email->setFrom('email_anda@gmail.com', 'Creativemu Academy');

            if ($aksi === 'setuju') {
                $email->setSubject('Selamat! Pendaftaran Kelas Anda di Creativemu Academy Telah Diterima');
                $email->setMessage('
                    <div style="font-family: Arial, sans-serif; padding: 20px; color: #333; background-color: #faf5ff; border-radius: 10px;">
                        <h2 style="color: #7c3aed;">Halo, ' . esc($pendaftaran['nama'] ?? 'Peserta') . '</h2>
                        <p>Kabar gembira! Pendaftaran Anda untuk mengikuti pelatihan di <strong>Creativemu Academy</strong> telah <strong>DISETUJUI</strong>.</p>
                        <p>NIS Anda adalah: <strong>' . esc($nisBaru) . '</strong></p>
                        <p>Silakan login dan lengkapi aktivitas pelatihan Anda melalui akun peserta.</p>
                        <p>Salam hangat,<br><strong>Tim Akademik Creativemu</strong></p>
                    </div>
                ');
            } else {
                $email->setSubject('Informasi Status Pendaftaran Kelas Creativemu Academy');
                $email->setMessage('
                    <div style="font-family: Arial, sans-serif; padding: 20px; color: #333; background-color: #fff1f2; border-radius: 10px;">
                        <h2 style="color: #e11d48;">Halo, ' . esc($pendaftaran['nama'] ?? 'Peserta') . '</h2>
                        <p>Mohon maaf, pendaftaran Anda di <strong>Creativemu Academy</strong> belum dapat kami setujui saat ini.</p>
                        <p>Salam hangat,<br><strong>Tim Akademik Creativemu</strong></p>
                    </div>
                ');
            }

            if (!$email->send()) {
                return redirect()->to(base_url('admin/validasi'))
                    ->with('warning', 'Status pendaftaran berhasil diperbarui, tetapi email notifikasi gagal dikirim.');
            }
        }

        if ($aksi === 'setuju' && !empty($waitingLaravelPrivateApproval)) {
            $pesan = 'Pembayaran sah & materi yang diajukan telah di-ACC (NIS: ' . $nisBaru . '). Permintaan Kelas Privat telah disinkronkan ke Laravel dan status kelas saat ini sedang disiapkan.';
        } else {
            $pesan = $aksi === 'setuju'
                ? 'Pendaftaran berhasil disetujui dan disinkronkan ke Laravel. NIS: ' . $nisBaru
                : 'Pendaftaran berhasil ditolak dan disinkronkan ke Laravel.';
        }

        return redirect()->to(base_url('admin/validasi'))->with('success', $pesan);
    }

    public function proses_validasi($id_pendaftaran)
    {
        $status = (string) $this->request->getPost('status_pembayaran');
        $alasan = $this->request->getPost('alasan_penolakan');
        $isDisetujui = in_array($status, ['valid', 'Disetujui', 'approved'], true);
        $isDitolak = in_array($status, ['rejected', 'Ditolak'], true);

        $apiService = new \App\Services\LaravelApiService();
        $pelatihanCtrl = new \App\Controllers\Pelatihan();
        $laravelClassesMap = $pelatihanCtrl->fetchLaravelClassesMap($apiService);

        $fileBukti = $this->request->getFile('bukti_pembayaran');
        $newBuktiName = null;
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $newBuktiName = $fileBukti->getRandomName();
            $folderTujuan = FCPATH . 'uploads/bukti/';
            if (!is_dir($folderTujuan)) {
                mkdir($folderTujuan, 0777, true);
            }
            $fileBukti->move($folderTujuan, $newBuktiName);
        }

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $pendaftaranLama = $db->query(
                'SELECT * FROM pendaftaran WHERE id_pendaftaran = ? FOR UPDATE',
                [(int) $id_pendaftaran]
            )->getRowArray();

            if (!$pendaftaranLama) {
                $db->transRollback();
                return redirect()->to(base_url('admin/validasi'))->with('error', 'Data pendaftaran tidak ditemukan.');
            }

            $idKelas = (int) ($pendaftaranLama['id_kelas'] ?? 0);
            $kelasInfo = $laravelClassesMap[$idKelas] ?? null;
            $isPrivat = strtolower(trim((string) ($pendaftaranLama['jenis_kelas'] ?? ($kelasInfo['jenis_kelas'] ?? 'Reguler')))) === 'privat';
            $laravelClassStatus = strtolower(trim((string) ($kelasInfo['status'] ?? '')));

            $waitingLaravelPrivateApproval = false;
            if ($isDisetujui && $isPrivat) {
                $laravelRegSt = '';
                if (!empty($kelasInfo['registrants']) && is_array($kelasInfo['registrants'])) {
                    foreach ($kelasInfo['registrants'] as $r) {
                        if (($r['external_registration_id'] ?? '') === 'REG-' . $id_pendaftaran) {
                            $laravelRegSt = strtolower(trim((string)($r['status'] ?? '')));
                            break;
                        }
                    }
                }
                $isApprovedInLaravel = in_array($laravelRegSt, ['approved', 'active', 'confirmed', 'disetujui'], true);
                if (!$isApprovedInLaravel) {
                    $waitingLaravelPrivateApproval = true;
                }
            }

            $dataUpdate = [
                'status_pembayaran'  => $isDisetujui ? 'valid' : ($isDitolak ? 'rejected' : 'pending'),
                'status_pendaftaran' => $isDisetujui ? ($waitingLaravelPrivateApproval ? 'Menunggu' : 'Disetujui') : ($isDitolak ? 'Ditolak' : 'Menunggu'),
                'status'             => $isDisetujui ? ($waitingLaravelPrivateApproval ? 'Pending' : 'Disetujui') : ($isDitolak ? 'Ditolak' : 'Pending'),
                'alasan_penolakan'   => $isDitolak ? $alasan : null,
            ];

            if ($newBuktiName) {
                $dataUpdate['bukti_pembayaran'] = $newBuktiName;
            }

            $nisBaru = $pendaftaranLama['nis'] ?? null;
            if ($isDisetujui && empty($nisBaru)) {
                $nisBaru = $this->generateNisPendaftaran($db, $pendaftaranLama);
                $dataUpdate['nis'] = $nisBaru;
            }

            // Pastikan akun peserta di tabel users sudah tersedia dan terhubung ke id_users
            if ($isDisetujui) {
                $userIdPendaftaran = $pendaftaranLama['id_users'] ?? null;
                if (empty($userIdPendaftaran) && !empty($pendaftaranLama['email'])) {
                    $userFound = $db->table('users')
                        ->where('LOWER(email)', strtolower($pendaftaranLama['email']))
                        ->get()
                        ->getRowArray();
                    if ($userFound) {
                        $userIdPendaftaran = (int) $userFound['id_users'];
                    } else {
                        $lastUser = $db->table('users')->selectMax('id_users')->get()->getRowArray();
                        $nextId   = ((int) ($lastUser['id_users'] ?? 0)) + 1;

                        $insertUser = [
                            'id_users'      => $nextId,
                            'nama'          => $pendaftaranLama['nama'] ?? 'Peserta',
                            'email'         => $pendaftaranLama['email'],
                            'no_hp'         => $pendaftaranLama['no_hp'] ?? '',
                            'jenis_kelamin' => $pendaftaranLama['jenis_kelamin'] ?? 'Laki-laki',
                            'password'      => password_hash('123456', PASSWORD_DEFAULT),
                            'role'          => 'peserta',
                            'created_at'    => date('Y-m-d H:i:s'),
                            'updated_at'    => date('Y-m-d H:i:s'),
                        ];
                        if ($db->fieldExists('asal_instansi', 'users') && !empty($pendaftaranLama['asal_instansi'])) {
                            $insertUser['asal_instansi'] = $pendaftaranLama['asal_instansi'];
                        }
                        if ($db->fieldExists('semester', 'users') && !empty($pendaftaranLama['semester'])) {
                            $insertUser['semester'] = $pendaftaranLama['semester'];
                        }

                        $db->table('users')->insert($insertUser);
                        $userIdPendaftaran = $nextId;
                    }

                    $dataUpdate['id_users'] = $userIdPendaftaran;

                    $db->table('pendaftaran')
                        ->where('email', $pendaftaranLama['email'])
                        ->where('id_users IS NULL', null, false)
                        ->update(['id_users' => $userIdPendaftaran]);
                }
            }

            $berhasilUpdate = $db->table('pendaftaran')
                ->where('id_pendaftaran', $id_pendaftaran)
                ->update($dataUpdate);

            if (!$berhasilUpdate || $db->transStatus() === false) {
                $db->transRollback();
                return redirect()->to(base_url('admin/validasi'))
                    ->with('error', 'Database gagal diperbarui.');
            }

            $db->transCommit();

            // Sinkronkan ke API Laravel
            $pendaftaranSync = array_merge($pendaftaranLama, $dataUpdate);
            $pelatihanCtrl->syncRegistrationToLaravel($pendaftaranSync, $kelasInfo ?? [], $apiService);

        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->to(base_url('admin/validasi'))
                ->with('error', 'Validasi gagal diproses: ' . $e->getMessage());
        }

        if ($pendaftaranLama && !empty($pendaftaranLama['email']) && empty($waitingLaravelPrivateApproval)) {
            $email = \Config\Services::email();
            $email->setTo($pendaftaranLama['email']);
            $email->setFrom('email_anda@gmail.com', 'Creativemu Academy');

            if ($isDisetujui) {
                $email->setSubject('Selamat! Pendaftaran Kelas Anda di Creativemu Academy Telah Diterima');
                $email->setMessage('
                    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 25px; color: #333; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <h2 style="color: #7c3aed; margin-top: 0;">Halo, ' . esc($pendaftaranLama['nama'] ?? 'Peserta') . '</h2>
                        <p>Kabar gembira! Pendaftaran Anda untuk mengikuti pelatihan di <strong>Creativemu Academy</strong> telah resmi <strong>DISETUJUI</strong>.</p>
                        <p>NIS Anda adalah: <strong>' . esc($nisBaru ?? '-') . '</strong></p>
                        <p>Silakan login dan mengikuti informasi pelatihan melalui akun peserta Anda.</p>
                        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
                        <p style="font-size: 13px; color: #94a3b8; margin-bottom: 0;">Salam hangat,<br><strong>Tim Akademik Creativemu Academy</strong></p>
                    </div>
                ');
            } else {
                $email->setSubject('Informasi Penting: Status Pendaftaran Creativemu Academy');
                $email->setMessage('
                    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 25px; color: #333; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <h2 style="color: #e11d48; margin-top: 0;">Halo, ' . esc($pendaftaranLama['nama'] ?? 'Peserta') . '</h2>
                        <p>Terima kasih telah mendaftar di <strong>Creativemu Academy</strong>. Mohon maaf, pendaftaran Anda saat ini <strong>belum dapat kami setujui</strong>.</p>
                        <div style="background-color: #fff1f2; border-left: 4px solid #e11d48; padding: 15px; margin: 20px 0; border-radius: 4px;">
                            <p style="margin: 0 0 5px 0; font-weight: bold; color: #9f1239;">Catatan / Alasan Penolakan:</p>
                            <p style="margin: 0; color: #881337;">' . esc($alasan ?? 'Tidak ada catatan khusus.') . '</p>
                        </div>
                        <p style="font-size: 13px; color: #94a3b8; margin-bottom: 0;">Salam hangat,<br><strong>Tim Akademik Creativemu Academy</strong></p>
                    </div>
                ');
            }

            if (!$email->send()) {
                return redirect()->to(base_url('admin/validasi'))
                    ->with('warning', 'Status pendaftaran berhasil diperbarui, tetapi email notifikasi gagal dikirim.');
            }
        }

        if ($isDisetujui && !empty($waitingLaravelPrivateApproval)) {
            $pesan = 'Pembayaran sah & materi yang diajukan telah di-ACC (NIS: ' . ($nisBaru ?? '-') . '). Permintaan Kelas Privat telah disinkronkan ke Laravel dan status kelas saat ini sedang disiapkan.';
        } else {
            $pesan = $isDitolak ? 'Pendaftaran berhasil ditolak.' : 'Pendaftaran berhasil disetujui.';
            if ($isDisetujui && !empty($nisBaru)) {
                $pesan .= ' NIS: ' . $nisBaru;
            }
        }

        return redirect()->to(base_url('admin/validasi'))->with('success', $pesan);
    }





private function generateNisPendaftaran($db, array $pendaftaran): string

{

    $tanggalDaftar = !empty($pendaftaran['created_at']) ? $pendaftaran['created_at'] : date('Y-m-d H:i:s');

    $timestamp = strtotime($tanggalDaftar) ?: time();

    $prefix = date('ym', $timestamp);



    $row = $db->query(

        'SELECT nis FROM pendaftaran WHERE nis LIKE ? AND CHAR_LENGTH(nis) = 8 ORDER BY nis DESC LIMIT 1 FOR UPDATE',

        [$prefix . '%']

    )->getRowArray();



    $nextSequence = 1;

    if (!empty($row['nis'])) {

        $nextSequence = ((int) substr($row['nis'], 4, 4)) + 1;

    }



    do {

        if ($nextSequence > 9999) {

            throw new \RuntimeException('Nomor urut NIS untuk periode ' . $prefix . ' sudah penuh.');

        }



        $nis = $prefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);

        $exists = $db->table('pendaftaran')

            ->where('nis', $nis)

            ->countAllResults() > 0;

        $nextSequence++;

    } while ($exists);



    return $nis;

}

    public function manajemenKbm()
    {
        $db = \Config\Database::connect();

        // Coba sinkronisasi nilai terbaru dari Laravel API jika tersedia
        try {
            $laravelApi = new \App\Services\LaravelApiService();
            $laravelApi->syncGradesFromLaravel();
        } catch (\Throwable $e) {
            log_message('warning', 'LaravelApiService syncGradesFromLaravel error: ' . $e->getMessage());
        }

        // Pastikan setiap peserta yang pendaftarannya telah disetujui memiliki data di nilai_ujian untuk dinilai & divalidasi
        try {
            $approvedPendaftaran = $db->table('pendaftaran')
                ->select('pendaftaran.id_pendaftaran, pendaftaran.id_users, pendaftaran.id_kelas')
                ->whereIn('LOWER(pendaftaran.status_pendaftaran)', ['disetujui', 'approved'])
                ->where('pendaftaran.id_users IS NOT NULL')
                ->where('pendaftaran.id_kelas IS NOT NULL')
                ->get()
                ->getResultArray();

            foreach ($approvedPendaftaran as $p) {
                $userId  = (int) $p['id_users'];
                $classId = (int) $p['id_kelas'];
                if ($userId <= 0 || $classId <= 0) {
                    continue;
                }

                $exists = $db->table('nilai_ujian')
                    ->where('id_users', $userId)
                    ->where('id_kelas', $classId)
                    ->countAllResults();

                if ($exists === 0) {
                    $maxId = $db->table('nilai_ujian')->selectMax('id_nilai_ujian')->get()->getRowArray();
                    $nextId = ((int) ($maxId['id_nilai_ujian'] ?? 0)) + 1;

                    $db->table('nilai_ujian')->insert([
                        'id_nilai_ujian'   => $nextId,
                        'id_ujian'         => 1,
                        'id_users'         => $userId,
                        'id_kelas'         => $classId,
                        'nilai'            => 0.00,
                        'nilai_awal'       => 0.00,
                        'status_kelulusan' => 'menunggu',
                        'created_at'       => date('Y-m-d H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            log_message('warning', 'manajemenKbm auto-ensure nilai_ujian error: ' . $e->getMessage());
        }

        $builder = $db->table('nilai_ujian');

        $builder->select('
            nilai_ujian.id_nilai_ujian,
            nilai_ujian.id_users,
            nilai_ujian.id_kelas,
            nilai_ujian.nilai,
            nilai_ujian.status_kelulusan,
            COALESCE(MAX(NULLIF(pendaftaran.nis, "")), "-") AS nis,
            COALESCE(users.nama, MAX(pendaftaran.nama), "Peserta") AS nama_peserta,
            COALESCE(users.email, MAX(pendaftaran.email), "-") AS email_peserta,
            COALESCE(kelas.nama_kelas, MAX(pendaftaran.pilihan_kelas), MAX(pendaftaran.pilihan_pelatihan), "Kelas Pelatihan") AS kelas,
            (SELECT COUNT(ja.id_jawaban) 
             FROM jawaban_angket ja 
             JOIN angket_pertanyaan ap ON ap.id_angket_pertanyaan = ja.id_pertanyaan 
             WHERE ja.id_siswa = nilai_ujian.id_users 
               AND (ap.id_kelas IS NULL OR ap.id_kelas = nilai_ujian.id_kelas)
            ) as jumlah_angket
        ');

        $builder->join('users', 'users.id_users = nilai_ujian.id_users', 'left');
        $builder->join('kelas', 'kelas.id_kelas = nilai_ujian.id_kelas', 'left');
        $builder->join('pendaftaran', 'pendaftaran.id_users = nilai_ujian.id_users AND pendaftaran.id_kelas = nilai_ujian.id_kelas', 'left');

        $builder->groupBy('nilai_ujian.id_nilai_ujian');
        $builder->orderBy('nilai_ujian.id_nilai_ujian', 'ASC');

        $query = $builder->get();

        $data = [
            'title'    => 'Manajemen KBM',
            'list_kbm' => $query->getResultArray()
        ];

        return view('admin/KBM/index', $data);
    }

    public function updateStatusKelulusanPeserta($id)
    {
        // 1. Validasi input menggunakan huruf kecil sesuai nilai pada view dan database
        $rules = [
            'status_kelulusan' => 'required|in_list[lulus,tidak lulus,menunggu,remidi]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pilihan status kelulusan tidak valid.');
        }

        $statusKelulusan = strtolower(trim((string)$this->request->getPost('status_kelulusan')));
        $db = \Config\Database::connect();

        // 2. Periksa apakah data nilai ujian ada
        $dataLama = $db->table('nilai_ujian')->where('id_nilai_ujian', (int)$id)->get()->getRowArray();
        if (!$dataLama) {
            return redirect()->back()
                ->with('error', 'Data nilai ujian tidak ditemukan.');
        }

        // 3. Lakukan proses update status kelulusan dan nilai jika diinput oleh admin
        $updateData = [
            'status_kelulusan' => $statusKelulusan,
            'updated_at'       => date('Y-m-d H:i:s'),
        ];

        $postNilai = $this->request->getPost('nilai');
        if ($postNilai !== null && $postNilai !== '') {
            $numericNilai = (float) $postNilai;
            $updateData['nilai'] = $numericNilai;
            if ($dataLama['nilai_awal'] === null || (float)$dataLama['nilai_awal'] == 0) {
                $updateData['nilai_awal'] = $numericNilai;
            }
        }

        $update = $db->table('nilai_ujian')
            ->where('id_nilai_ujian', (int)$id)
            ->update($updateData);

        if (!$update) {
            return redirect()->back()
                ->with('error', 'Gagal menyimpan perubahan ke database.');
        }

        $statusTeks = ($statusKelulusan === 'lulus') ? 'Lulus' : (($statusKelulusan === 'tidak lulus') ? 'Tidak Lulus' : ucfirst($statusKelulusan));

        return redirect()->back()
            ->with('success', "Status kelulusan peserta berhasil diperbarui menjadi {$statusTeks}.");
    }

    public function unduhFileNilai($idNilaiUjian)
    {
        $role = strtolower(trim((string)session()->get('role')));
        if ($role !== 'admin') {
            return redirect()->to(base_url('pelatihan/login'))->with('error', 'Hanya admin yang berhak mengunduh file nilai.');
        }

        $db = \Config\Database::connect();
        $nilai = $db->table('nilai_ujian')
            ->select('nilai_ujian.*, pendaftaran.nis, pendaftaran.id_pendaftaran, pendaftaran.email, users.email as user_email')
            ->join('pendaftaran', 'pendaftaran.id_users = nilai_ujian.id_users AND pendaftaran.id_kelas = nilai_ujian.id_kelas', 'left')
            ->join('users', 'users.id_users = nilai_ujian.id_users', 'left')
            ->where('nilai_ujian.id_nilai_ujian', (int)$idNilaiUjian)
            ->get()
            ->getRowArray();

        if (!$nilai || empty($nilai['id_kelas'])) {
            return redirect()->back()->with('error', 'Data nilai ujian atau kelas tidak ditemukan.');
        }

        $classId = (int)$nilai['id_kelas'];

        // Identitas siswa dari data yang sudah tersedia
        $studentIdentifier = !empty($nilai['nis']) && $nilai['nis'] !== '-'
            ? (string)$nilai['nis']
            : (!empty($nilai['id_pendaftaran'])
                ? 'REG-' . $nilai['id_pendaftaran']
                : (string)$nilai['id_users']);

        $apiService = new \App\Services\LaravelApiService();

        // 1. Ambil daftar/info file nilai aktif
        $fileInfo = $apiService->getGradeFiles($classId, $studentIdentifier);

        if ($fileInfo['status_code'] === 403) {
            return redirect()->back()->with('error', 'Akses berkas ditolak server Laravel (Status 403: Siswa belum terdaftar/terverifikasi di server pusat).');
        }

        if (empty($fileInfo['file']) || empty($fileInfo['file']['id'])) {
            return redirect()->back()->with('error', $fileInfo['message'] ?? 'Berkas nilai belum diunggah instruktur atau belum disetujui admin di server pusat.');
        }

        $gradeFileId = $fileInfo['file']['id'];

        // 2. Unduh berkas fisik nilai melalui API
        $downloadResult = $apiService->downloadGradeFile($classId, $gradeFileId, $studentIdentifier);

        if (!$downloadResult['success']) {
            return redirect()->back()->with('error', $downloadResult['message']);
        }

        $fileName = $fileInfo['file']['file_name'] ?? ('file-nilai-kelas-' . $classId . '.pdf');
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $fileName);

        return $this->response
            ->setHeader('Content-Type', $downloadResult['content_type'])
            ->setHeader('Content-Disposition', 'attachment; filename="' . $safeName . '"')
            ->setBody($downloadResult['body']);
    }

   public function angket()

{

    $db = \Config\Database::connect();



    $builder = $db->table('angket_pertanyaan aq')

        ->select('aq.*')

        ->select('kelas.nama_kelas, kelas.tanggal_mulai_kelas, kelas.lokasi_pelatihan')

        ->select('mentor.nama_mentor')

        ->join('kelas', 'kelas.id_kelas = aq.id_kelas', 'left')

        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left');



    $angket = $builder

        ->where('aq.id_angket_pertanyaan >=', 23)

        ->orderBy(

            "CASE

                WHEN aq.kategori = 'Customer Insight' THEN 1

                WHEN aq.kategori = 'Penilaian Instruktur' THEN 2

                WHEN aq.kategori = 'Penilaian Lembaga' THEN 3

                ELSE 4

            END",

            'ASC',

            false

        )

        ->orderBy('aq.id_angket_pertanyaan', 'ASC')

        ->get()

        ->getResultArray();



    $angket = $this->lengkapiRingkasanAngket($angket, $db);



    $ratedRows = array_values(array_filter($angket, static fn ($row) => (float) ($row['rata_rata'] ?? 0) > 0));

    $avgNilai = !empty($ratedRows)

        ? round(array_sum(array_column($ratedRows, 'rata_rata')) / count($ratedRows), 2)

        : 0;



    // Hitung total responden unik (PHP-side untuk menghindari bug Query Builder CI4)

    $questionIds = array_column($angket, 'id_angket_pertanyaan');

    $uniqueResponden = 0;

    if (!empty($questionIds)) {

        $relJawab = $db->table('jawaban_angket')

            ->whereIn('id_pertanyaan', $questionIds)

            ->get()

            ->getResultArray();



        $unique = [];

        foreach ($relJawab as $rj) {

            $unique[$rj['id_siswa']] = true;

        }

        $uniqueResponden = count($unique);

    }



    $data = [

        'title'         => 'Monitoring Angket',

        'angket'        => $angket,

        'summary'       => [

            'total_angket'    => count($angket),

            'total_responden' => $uniqueResponden,

            'rata_rata'       => $avgNilai,

            'kepuasan'        => $avgNilai > 0 ? round(($avgNilai / 4) * 100, 1) : 0,

        ],

    ];



    return view('admin/angket/index', $data);

}



    public function tambahAngket()

{

    $kelasModel  = new \App\Models\KelasModel();

    $mentorModel = new \App\Models\MentorModel();



    $data = [

        'title'  => 'Buat Angket Evaluasi',

        'kelas'  => $kelasModel->findAll(),

        'mentor' => $mentorModel->findAll()

    ];



    return view('admin/angket/tambah_angket', $data);

}

public function simpanAngket()

{

    $judulAngket  = $this->request->getPost('judul_angket') ?: 'Angket Evaluasi Creativemu Academy';

    $idKelas      = $this->request->getPost('id_kelas');

    $kategori     = $this->request->getPost('kategori');

    $pertanyaan   = $this->request->getPost('pertanyaan');

    $jenisJawaban = $this->request->getPost('jenis_jawaban');

    $opsiJawaban  = $this->request->getPost('opsi_jawaban');



    $db = \Config\Database::connect();

    $builder = $db->table('angket_pertanyaan');



    if (!empty($pertanyaan)) {

        for ($i = 0; $i < count($pertanyaan); $i++) {



            // Memastikan jenis jawaban sesuai dengan enum database

            $tipe = $jenisJawaban[$i] ?? 'rating';

            if (!in_array($tipe, ['rating', 'pilihan', 'essay'])) {

                $tipe = 'rating'; // Fallback aman

            }



            $dataSimpan = [

                'judul_angket'  => $judulAngket,

                'id_kelas'      => $idKelas ?: null,

                'kategori'      => $kategori[$i] ?? null,

                'pertanyaan'    => $pertanyaan[$i],

                'tipe'          => $tipe,

                'opsi_jawaban'  => json_encode($opsiJawaban[$i] ?? [], JSON_UNESCAPED_UNICODE),

                'created_at'    => date('Y-m-d H:i:s')

            ];



            $builder->insert($dataSimpan);

        }

    }



    return redirect()

        ->to(base_url('admin/angket'))

        ->with('success', 'Konfigurasi angket berhasil disimpan!');

}





    public function getAngket()

{

    $db = \Config\Database::connect();



    return $db->table('angket_pertanyaan')

        ->select('angket_pertanyaan.*, kelas.id_kelas, kelas.nama_kelas, mentor.id_mentor, mentor.nama_mentor')

        ->join('kelas', 'kelas.id_kelas = angket_pertanyaan.id_kelas', 'left')

        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')

        ->get()

        ->getResultArray();

}



public function edit($id)

{

    $db = \Config\Database::connect();



    $angketUtama = $db->table('angket_pertanyaan')

                      ->where('id_angket_pertanyaan', $id)

                      ->get()

                      ->getRowArray();



    if (empty($angketUtama)) {

        return redirect()->to('admin/angket')->with('error', 'Data pertanyaan tidak ditemukan.');

    }



    $data['angket'] = $angketUtama;

    $data['title']  = 'Edit Pertanyaan Angket';

    $data['id']     = $id;

    $data['kelas']  = $db->table('kelas')->get()->getResultArray();



    return view('admin/angket/edit', $data);

}



public function update($id)

{

    $db = \Config\Database::connect();



    $judulBaru    = $this->request->getPost('judul_angket');

    $idKelas      = $this->request->getPost('id_kelas');

    $kategori     = $this->request->getPost('kategori');

    $pertanyaan   = $this->request->getPost('pertanyaan');

    $tipe         = $this->request->getPost('tipe');

    $opsi_jawaban = $this->request->getPost('opsi_jawaban');

    $status       = $this->request->getPost('status');



    if (!in_array($tipe, ['rating', 'pilihan', 'essay'])) {

        $tipe = 'rating';

    }



    $opsiJson = null;

    if ($tipe === 'pilihan' && !empty($opsi_jawaban) && is_array($opsi_jawaban)) {

        $filtered = array_values(array_filter($opsi_jawaban, fn($v) => trim($v) !== ''));

        $opsiJson = json_encode($filtered, JSON_UNESCAPED_UNICODE);

    }



    $dataUpdate = [

        'judul_angket' => $judulBaru,

        'id_kelas'     => $idKelas ?: null,

        'kategori'     => $kategori,

        'pertanyaan'   => $pertanyaan,

        'tipe'         => $tipe,

        'opsi_jawaban' => $opsiJson,

        'status'       => $status ?: 'Aktif'

    ];



    $db->table('angket_pertanyaan')->where('id_angket_pertanyaan', $id)->update($dataUpdate);



    return redirect()->to('admin/angket')->with('success', 'Pertanyaan berhasil diperbarui.');

}



    public function detailAngket($id)

{

    $db = \Config\Database::connect();



    $angket = $db->table('angket_pertanyaan aq')

        ->select('aq.*, kelas.nama_kelas, kelas.tanggal_mulai_kelas, kelas.lokasi_pelatihan, mentor.nama_mentor')

        ->join('kelas', 'kelas.id_kelas = aq.id_kelas', 'left')

        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')

        ->where('aq.id_angket_pertanyaan', $id)

        ->get()

        ->getRowArray();



    if (empty($angket)) {

        return redirect()->to('admin/angket')->with('error', 'Data angket tidak ditemukan.');

    }



    $semuaPertanyaan = $db->table('angket_pertanyaan')

        ->where('judul_angket', $angket['judul_angket'])

        ->where('id_kelas', $angket['id_kelas'])

        ->orderBy('id_angket_pertanyaan', 'ASC')

        ->get()

        ->getResultArray();



    $ringkasan = $this->ambilNilaiAngket($db, (int) $angket['id_kelas'], $angket['judul_angket']);

    $angket = array_merge($angket, $ringkasan);

    $angket['tempat_pelatihan'] = $this->ambilTempatPelatihan($db, (int) $angket['id_kelas'], $angket);



    $data = [

        'title'            => 'Detail Angket',

        'angket'           => $angket,

        'semua_pertanyaan' => $semuaPertanyaan,

        'saranPeserta'     => $this->ambilSaranAngket($db, (int) $angket['id_kelas'], $angket['judul_angket']),

    ];



    return view('admin/angket/detail', $data);

}



private function lengkapiRingkasanAngket(array $angket, $db): array

{

    $allJawaban = $db->table('jawaban_angket')->get()->getResultArray();



    foreach ($angket as &$item) {

        $idPertanyaan = $item['id_angket_pertanyaan'];



        // Filter jawaban untuk pertanyaan ini

        $jawabans = array_filter($allJawaban, fn($j) => $j['id_pertanyaan'] == $idPertanyaan);



        $uniqueSiswa = [];

        $sum = 0;

        $count = 0;



        foreach ($jawabans as $j) {

            $uniqueSiswa[$j['id_siswa']] = true;

            if (isset($item['tipe']) && $item['tipe'] === 'rating') {

                $sum += (float) $j['jawaban'];

                $count++;

            }

        }



        $item['jumlah_responden'] = count($uniqueSiswa);

        $item['rata_rata']        = $count > 0 ? round($sum / $count, 2) : 0;

        $item['tempat_pelatihan'] = $this->ambilTempatPelatihan($db, (int) ($item['id_kelas'] ?? 0), $item);

    }

    unset($item);



    return $angket;

}



private function ambilNilaiAngket($db, int $idKelas, string $judulAngket = ''): array

{

    $default = [

        'nilai_instruktur_1' => 0,

        'nilai_instruktur_2' => 0,

        'nilai_tempat'       => 0,

        'rata_rata'          => 0,

        'jumlah_responden'   => 0,

    ];



    if (!$db->tableExists('angket_penilaian') || $idKelas <= 0) {

        return $default;

    }



    $fields = $db->getFieldNames('angket_penilaian');

    $has = static fn (string $field): bool => in_array($field, $fields, true);

    $avg = static function (array $candidates) use ($has): ?string {

        foreach ($candidates as $candidate) {

            if ($has($candidate)) {

                return $candidate;

            }

        }

        return null;

    };



    $instruktur1 = $avg(['nilai_instruktur_1', 'rating_instruktur_1', 'instruktur_1', 'rating_mentor_1', 'rating']);

    $instruktur2 = $avg(['nilai_instruktur_2', 'rating_instruktur_2', 'instruktur_2', 'rating_mentor_2']);

    $tempat      = $avg(['nilai_tempat', 'rating_tempat', 'tempat_pelatihan', 'rating_fasilitas', 'rating_lokasi']);



    $select = ['COUNT(*) AS jumlah_responden'];

    if ($instruktur1) {

        $select[] = "AVG({$instruktur1}) AS nilai_instruktur_1";

    }

    if ($instruktur2) {

        $select[] = "AVG({$instruktur2}) AS nilai_instruktur_2";

    }

    if ($tempat) {

        $select[] = "AVG({$tempat}) AS nilai_tempat";

    }



    $builder = $db->table('angket_penilaian')

        ->select(implode(', ', $select), false)

        ->where('id_kelas', $idKelas);



    if ($has('judul_angket') && $judulAngket !== '') {

        $builder->where('judul_angket', $judulAngket);

    }



    $row = $builder->get()->getRowArray() ?: [];

    $nilai1 = round((float) ($row['nilai_instruktur_1'] ?? 0), 2);

    $nilai2 = round((float) ($row['nilai_instruktur_2'] ?? 0), 2);

    $nilaiTempat = round((float) ($row['nilai_tempat'] ?? 0), 2);



    $nilaiTersedia = array_values(array_filter([$nilai1, $nilai2, $nilaiTempat], static fn ($nilai) => $nilai > 0));

    $rataRata = !empty($nilaiTersedia) ? round(array_sum($nilaiTersedia) / count($nilaiTersedia), 2) : 0;



    return [

        'nilai_instruktur_1' => $nilai1,

        'nilai_instruktur_2' => $nilai2,

        'nilai_tempat'       => $nilaiTempat,

        'rata_rata'          => $rataRata,

        'jumlah_responden'   => (int) ($row['jumlah_responden'] ?? 0),

    ];

}



private function ambilSaranAngket($db, int $idKelas, string $judulAngket = ''): array

{

    if (!$db->tableExists('angket_penilaian') || $idKelas <= 0) {

        return [];

    }



    $fields = $db->getFieldNames('angket_penilaian');

    $has = static fn (string $field): bool => in_array($field, $fields, true);

    $saranField = null;

    foreach (['saran', 'masukan', 'ulasan', 'komentar'] as $candidate) {

        if ($has($candidate)) {

            $saranField = $candidate;

            break;

        }

    }



    if ($saranField === null) {

        return [];

    }



    $builder = $db->table('angket_penilaian ap')

        ->select("ap.{$saranField} AS saran", false)

        ->where('ap.id_kelas', $idKelas)

        ->where("ap.{$saranField} IS NOT NULL", null, false)

        ->where("ap.{$saranField} !=", '');



    if ($has('id_peserta')) {

        $builder->select('users.nama AS nama_peserta')

            ->join('users', 'users.id_users = ap.id_peserta', 'left');

    }

    if ($has('rating')) {

        $builder->select('ap.rating');

    }

    if ($has('created_at')) {

        $builder->select('ap.created_at')->orderBy('ap.created_at', 'DESC');

    }

    if ($has('judul_angket') && $judulAngket !== '') {

        $builder->where('ap.judul_angket', $judulAngket);

    }



    return $builder->get()->getResultArray();

}



private function ambilTempatPelatihan($db, int $idKelas, array $angket): string

{

    if (!empty($angket['tempat_pelatihan'])) {

        return (string) $angket['tempat_pelatihan'];

    }

    if (!empty($angket['lokasi_pelatihan'])) {

        return (string) $angket['lokasi_pelatihan'];

    }

    if ($db->tableExists('pendaftaran') && $db->fieldExists('lokasi_pelatihan', 'pendaftaran') && $idKelas > 0) {

        $row = $db->table('pendaftaran')

            ->select('lokasi_pelatihan')

            ->where('id_kelas', $idKelas)

            ->where('lokasi_pelatihan IS NOT NULL', null, false)

            ->where('lokasi_pelatihan !=', '')

            ->orderBy('id_pendaftaran', 'DESC')

            ->get(1)

            ->getRowArray();



        if (!empty($row['lokasi_pelatihan'])) {

            return (string) $row['lokasi_pelatihan'];

        }

    }



    return '-';

}

public function delete($id)

{

    $db = \Config\Database::connect();



    $db->table('angket_pertanyaan')->where('id_angket_pertanyaan', $id)->delete();



    return redirect()->to('admin/angket')->with('success', 'Data angket berhasil dihapus.');

}



public function hasilAngket()

{

    $db = \Config\Database::connect();



    // Get filter inputs

    $filters = [

        'search'  => trim((string) $this->request->getGet('search')),

        'kelas'   => trim((string) $this->request->getGet('kelas')),

        'tanggal' => trim((string) $this->request->getGet('tanggal')),

    ];



    // Tarik semua data mentah dengan urutan terbaru

    $rawHasil = $db->table('jawaban_angket')

        ->select('jawaban_angket.*, angket_pertanyaan.judul_angket, angket_pertanyaan.kategori, angket_pertanyaan.pertanyaan, angket_pertanyaan.tipe, users.nama as nama_siswa')

        ->join('angket_pertanyaan', 'angket_pertanyaan.id_angket_pertanyaan = jawaban_angket.id_pertanyaan', 'left')

        ->join('users', 'users.id_users = jawaban_angket.id_siswa', 'left')

        ->orderBy('jawaban_angket.created_at', 'DESC')

        ->get()

        ->getResultArray();



    // Kelompokkan data per sesi

    $groupedHasil = [];

    foreach ($rawHasil as $row) {

        $timestamp = strtotime($row['created_at']);



        // Cari sesi yang cocok untuk siswa ini

        $sessionKey = null;

        foreach ($groupedHasil as $key => $grup) {

            if ($grup['id_siswa'] == $row['id_siswa']) {

                $grupTime = strtotime($grup['tanggal_pengisian']);

                if (abs($timestamp - $grupTime) < 3600) {

                    $sessionKey = $key;

                    break;

                }

            }

        }



        if (!$sessionKey) {

            $sessionKey = $row['id_siswa'] . '_' . $timestamp;

            $groupedHasil[$sessionKey] = [

                'id_siswa' => $row['id_siswa'],

                'nama_siswa' => $row['nama_siswa'],

                'tanggal_pengisian' => $row['created_at'],

                'judul_angket' => $row['judul_angket'],

                'kelas_diikuti' => '-',

                'jumlah_jawaban' => 0,

                'jawaban' => []

            ];

        }



        // Cek pertanyaan 23 (Kelas yang Diikuti)

        if ($row['id_pertanyaan'] == 23) {

            $groupedHasil[$sessionKey]['kelas_diikuti'] = $row['jawaban'];

        }



        $groupedHasil[$sessionKey]['jawaban'][] = [

            'id_pertanyaan' => $row['id_pertanyaan'],

            'kategori'      => $row['kategori'],

            'pertanyaan'    => $row['pertanyaan'],

            'tipe'          => $row['tipe'],

            'jawaban'       => $row['jawaban']

        ];



        $groupedHasil[$sessionKey]['jumlah_jawaban']++;

    }



    // Filter grouped results

    $finalHasil = [];

    foreach ($groupedHasil as $grup) {

        $match = true;



        if ($filters['search'] !== '') {

            $searchStr = strtolower($filters['search']);

            $namaSiswa = strtolower($grup['nama_siswa']);

            $kelasDiikuti = strtolower($grup['kelas_diikuti']);

            if (strpos($namaSiswa, $searchStr) === false && strpos($kelasDiikuti, $searchStr) === false) {

                $match = false;

            }

        }



        if ($match && $filters['kelas'] !== '') {

            if (strtolower(trim($grup['kelas_diikuti'])) !== strtolower(trim($filters['kelas']))) {

                $match = false;

            }

        }



        if ($match && $filters['tanggal'] !== '') {

            $tglGrup = date('Y-m-d', strtotime($grup['tanggal_pengisian']));

            if ($tglGrup !== $filters['tanggal']) {

                $match = false;

            }

        }



        if ($match) {

            $finalHasil[] = $grup;

        }

    }



    // Unique list of classes for filter options (from kelas table, or from populated array)

    $kelasOptions = $db->table('kelas')->select('nama_kelas')->where('status', 'Aktif')->groupBy('nama_kelas')->get()->getResultArray();



    $data = [

        'title'        => 'Hasil Angket Siswa',

        'hasil'        => array_values($finalHasil),

        'filters'      => $filters,

        'kelasOptions' => $kelasOptions

    ];



    return view('admin/angket/hasil', $data);

}



    public function sertifikat()

{

    $db = \Config\Database::connect();



    // Sertifikat yang sudah diterbitkan

    $sertifikat = $db->table('sertifikat')

        ->select('

            sertifikat.*,

            users.nama AS nama_peserta,

            users.email,

            kelas.nama_kelas,

            nilai_ujian.nilai,

            nilai_ujian.status_kelulusan

        ')

        ->join(

            'users',

            'users.id_users = sertifikat.id_user',

            'left'

        )

        ->join(

            'kelas',

            'kelas.id_kelas = sertifikat.id_kelas',

            'left'

        )

        ->join(

            'nilai_ujian',

            'nilai_ujian.id_users = sertifikat.id_user

             AND nilai_ujian.id_kelas = sertifikat.id_kelas',

            'left'

        )

        ->orderBy('sertifikat.id_sertifikat', 'DESC')

        ->get()

        ->getResultArray();



    // Peserta yang sudah dinyatakan LULUS

    $pesertaLulus = $db->table('nilai_ujian')

        ->select('

            nilai_ujian.id_users,

            nilai_ujian.id_kelas,

            nilai_ujian.nilai,

            nilai_ujian.status_kelulusan,

            users.nama AS nama_peserta,

            users.email,

            kelas.nama_kelas

        ')

        ->join(

            'users',

            'users.id_users = nilai_ujian.id_users',

            'inner'

        )

        ->join(

            'kelas',

            'kelas.id_kelas = nilai_ujian.id_kelas',

            'inner'

        )

        ->where("LOWER(nilai_ujian.status_kelulusan) = 'lulus'", null, false)

        ->orderBy('users.nama', 'ASC')

        ->get()

        ->getResultArray();



    $data = [

        'title'        => 'Manajemen Sertifikat Peserta',

        'sertifikat'   => $sertifikat,

        'pesertaLulus' => $pesertaLulus,

    ];



    return view('admin/sertifikat/index', $data);

}

    public function uploadSertifikat()

{

    $db = \Config\Database::connect();



    $pesertaLulus = $db->table('nilai_ujian')

        ->select('

            nilai_ujian.id_users,

            nilai_ujian.id_kelas,

            nilai_ujian.nilai,

            nilai_ujian.status_kelulusan,

            users.nama AS nama_peserta,

            users.email,

            kelas.nama_kelas

        ')

        ->join(

            'users',

            'users.id_users = nilai_ujian.id_users',

            'inner'

        )

        ->join(

            'kelas',

            'kelas.id_kelas = nilai_ujian.id_kelas',

            'inner'

        )

        ->where("LOWER(nilai_ujian.status_kelulusan) = 'lulus'", null, false)

        ->orderBy('users.nama', 'ASC')

        ->get()

        ->getResultArray();



    $data = [

        'title'        => 'Terbitkan Sertifikat',

        'pesertaLulus' => $pesertaLulus,

        'selectedUser' => $this->request->getGet('id_user'),

        'selectedKelas'=> $this->request->getGet('id_kelas'),

    ];



    return view('admin/sertifikat/upload', $data);

}



   public function storeSertifikat()

{

    $idUser  = $this->request->getPost('id_users');

    $idKelas = $this->request->getPost('id_kelas');



    if (empty($idUser) || empty($idKelas)) {

        return redirect()->back()

            ->withInput()

            ->with('error', 'Peserta dan kelas wajib dipilih.');

    }



    $db = \Config\Database::connect();



    // Pastikan peserta memang LULUS pada kelas tersebut

    $hasilUjian = $db->table('nilai_ujian')
        ->where('id_users', $idUser)
        ->where('id_kelas', $idKelas)

        ->where("LOWER(status_kelulusan) = 'lulus'", null, false)

        ->get()

        ->getRowArray();



    if (!$hasilUjian) {

        return redirect()->back()

            ->withInput()

            ->with('error', 'Peserta belum dinyatakan lulus pada kelas tersebut.');

    }



    // Ambil sertifikat yang sudah ada, jika ada

    $sertifikatModel = new SertifikatModel();



    $sertifikatLama = $sertifikatModel

        ->where('id_user', $idUser)

        ->where('id_kelas', $idKelas)

        ->first();



    // Validasi file

    $fileSertifikat = $this->request->getFile('file_sertifikat');



    if (

        !$fileSertifikat ||

        !$fileSertifikat->isValid() ||

        $fileSertifikat->hasMoved()

    ) {

        return redirect()->back()

            ->withInput()

            ->with('error', 'File sertifikat wajib diunggah.');

    }



    // Pastikan folder upload tersedia

    $uploadPath = FCPATH . 'uploads/sertifikat/';



    if (!is_dir($uploadPath)) {

        mkdir($uploadPath, 0775, true);

    }



    // Buat nama file baru

    $namaFile = $fileSertifikat->getRandomName();



    if (!$fileSertifikat->move($uploadPath, $namaFile)) {

        return redirect()->back()

            ->withInput()

            ->with('error', 'File sertifikat gagal diunggah.');

    }



    /*

     * Jika sertifikat sudah ada:

     * update file sertifikat lama.

     */

    if ($sertifikatLama) {



        $fileLama = $sertifikatLama['file_sertifikat'] ?? null;



        $berhasil = $sertifikatModel->update(

            $sertifikatLama['id_sertifikat'],

            [

                'file_sertifikat' => $namaFile

            ]

        );



        if (!$berhasil) {

            // Hapus file baru jika database gagal diperbarui

            $pathBaru = $uploadPath . $namaFile;



            if (is_file($pathBaru)) {

                @unlink($pathBaru);

            }



            return redirect()->back()

                ->withInput()

                ->with('error', 'File sertifikat gagal diperbarui.');

        }



        // Hapus file lama jika masih ada

        if (!empty($fileLama)) {

            $pathLama = $uploadPath . $fileLama;



            if (is_file($pathLama)) {

                @unlink($pathLama);

            }

        }



        return redirect()->to(base_url('admin/sertifikat'))

            ->with('success', 'File sertifikat berhasil diperbarui.');

    }



    /*

     * Jika belum ada sertifikat:

     * buat sertifikat baru.

     */

    $noSertifikat = 'CERT-' . date('Ymd') . '-' .

        strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));



    $berhasil = $sertifikatModel->insert([

        'id_user'          => $idUser,

        'id_kelas'         => $idKelas,

        'nomor_sertifikat' => $noSertifikat,

        'tanggal_terbit'   => date('Y-m-d'),

        'file_sertifikat'  => $namaFile

    ]);



    if (!$berhasil) {

        $path = $uploadPath . $namaFile;



        if (is_file($path)) {

            @unlink($path);

        }



        return redirect()->back()

            ->withInput()

            ->with('error', 'Sertifikat gagal disimpan.');

    }



    return redirect()->to(base_url('admin/sertifikat'))

        ->with('success', 'Sertifikat berhasil diterbitkan.');

}

   public function downloadSertifikat($id)

{

    $sertifikatModel = new SertifikatModel();

    $sertifikat = $sertifikatModel->find($id);



    if (!$sertifikat) {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'Sertifikat tidak ditemukan.');

    }



    if (empty($sertifikat['file_sertifikat'])) {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'File sertifikat belum tersedia.');

    }



    $namaFile = $sertifikat['file_sertifikat'];



    $pathPublic = FCPATH . 'uploads/sertifikat/' . $namaFile;

    $pathRoot   = ROOTPATH . 'uploads/sertifikat/' . $namaFile;



    if (is_file($pathPublic)) {

        $path = $pathPublic;

    } elseif (is_file($pathRoot)) {

        $path = $pathRoot;

    } else {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'File sertifikat tidak ditemukan.');

    }



    $mimeType = mime_content_type($path);



    return $this->response

        ->setHeader('Content-Type', $mimeType)

        ->setHeader('Content-Disposition', 'inline; filename="' . basename($namaFile) . '"')

        ->setBody(file_get_contents($path));

}



public function editSertifikat($id)

{

    $db = \Config\Database::connect();



    $sertifikat = $db->table('sertifikat')

        ->select('

            sertifikat.*,

            users.nama AS nama_peserta,

            users.email,

            kelas.nama_kelas,

            nilai_ujian.nilai,

            nilai_ujian.status_kelulusan

        ')

        ->join(

            'users',

            'users.id_users = sertifikat.id_user',

            'left'

        )

        ->join(

            'kelas',

            'kelas.id_kelas = sertifikat.id_kelas',

            'left'

        )

        ->join(

            'nilai_ujian',

            'nilai_ujian.id_users = sertifikat.id_user

             AND nilai_ujian.id_kelas = sertifikat.id_kelas',

            'left'

        )

        ->where('sertifikat.id_sertifikat', $id)

        ->get()

        ->getRowArray();



    if (!$sertifikat) {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'Sertifikat tidak ditemukan.');

    }



    return view('admin/sertifikat/edit', [

        'title'      => 'Edit Sertifikat',

        'sertifikat' => $sertifikat

    ]);

}



public function updateSertifikat($id)

{

    $sertifikatModel = new SertifikatModel();



    $sertifikat = $sertifikatModel->find($id);



    if (!$sertifikat) {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'Sertifikat tidak ditemukan.');

    }



    $nomorSertifikat = trim($this->request->getPost('nomor_sertifikat'));

    $tanggalTerbit   = $this->request->getPost('tanggal_terbit');



    if (empty($nomorSertifikat) || empty($tanggalTerbit)) {

        return redirect()->back()

            ->withInput()

            ->with('error', 'Nomor sertifikat dan tanggal terbit wajib diisi.');

    }



    $dataUpdate = [

        'nomor_sertifikat' => $nomorSertifikat,

        'tanggal_terbit'   => $tanggalTerbit,

    ];



    // Jika admin mengganti file sertifikat

    $fileSertifikat = $this->request->getFile('file_sertifikat');



    if ($fileSertifikat && $fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {



        $uploadPath = FCPATH . 'uploads/sertifikat/';



        if (!is_dir($uploadPath)) {

            mkdir($uploadPath, 0775, true);

        }



        $namaFileBaru = $fileSertifikat->getRandomName();



        if (!$fileSertifikat->move($uploadPath, $namaFileBaru)) {

            return redirect()->back()

                ->withInput()

                ->with('error', 'File sertifikat gagal diunggah.');

        }



        $dataUpdate['file_sertifikat'] = $namaFileBaru;

    }



    $berhasil = $sertifikatModel->update(

        $id,

        $dataUpdate

    );



    if (!$berhasil) {



        // Hapus file baru jika database gagal diperbarui

        if (!empty($dataUpdate['file_sertifikat'])) {

            $pathBaru = FCPATH . 'uploads/sertifikat/' . $dataUpdate['file_sertifikat'];



            if (is_file($pathBaru)) {

                @unlink($pathBaru);

            }

        }



        return redirect()->back()

            ->withInput()

            ->with('error', 'Sertifikat gagal diperbarui.');

    }



    // Hapus file lama setelah database berhasil diperbarui

    if (!empty($dataUpdate['file_sertifikat']) && !empty($sertifikat['file_sertifikat'])) {



        $pathLama = FCPATH . 'uploads/sertifikat/' . $sertifikat['file_sertifikat'];



        if (is_file($pathLama)) {

            @unlink($pathLama);

        }

    }



    return redirect()->to(base_url('admin/sertifikat'))

        ->with('success', 'Sertifikat berhasil diperbarui.');

}

public function downloadFileSertifikat($id)

{

    $sertifikatModel = new SertifikatModel();

    $sertifikat = $sertifikatModel->find($id);



    if (!$sertifikat) {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'Sertifikat tidak ditemukan.');

    }



    if (empty($sertifikat['file_sertifikat'])) {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'File sertifikat belum tersedia.');

    }



    $namaFile = $sertifikat['file_sertifikat'];



    $pathPublic = FCPATH . 'uploads/sertifikat/' . $namaFile;

    $pathRoot   = ROOTPATH . 'uploads/sertifikat/' . $namaFile;



    if (is_file($pathPublic)) {

        $path = $pathPublic;

    } elseif (is_file($pathRoot)) {

        $path = $pathRoot;

    } else {

        return redirect()->to(base_url('admin/sertifikat'))

            ->with('error', 'File sertifikat tidak ditemukan.');

    }



    return $this->response->download($path, null);

}

    public function laporan()

    {

        $controller = new \App\Controllers\LaporanPesertaController();

        $controller->initController($this->request, $this->response, $this->logger);

        return $controller->index();

    }



    public function pengaturan()

    {

        $session = session();

        $userId = $session->get('id_users');



        $db = \Config\Database::connect();

        $user = $db->table('users')->where('id_users', $userId)->get()->getRowArray();



        $data = [

            'title' => 'Pengaturan Akun - Panel Admin',

            'user'  => $user

        ];



        return view('admin/pengaturan/index', $data);

    }



    public function updatePengaturan()
    {
        $session = session();
        $userId = $session->get('id_users');

        $namaAdmin          = trim((string) $this->request->getPost('nama_admin'));
        $emailAdmin         = trim((string) $this->request->getPost('email_admin'));
        $passwordLama       = trim((string) $this->request->getPost('password_lama'));
        $passwordBaru       = trim((string) $this->request->getPost('password_baru'));
        $konfirmasiPassword = trim((string) $this->request->getPost('konfirmasi_password'));

        $db = \Config\Database::connect();
        $currentUser = $db->table('users')->where('id_users', $userId)->get()->getRowArray();

        $dataUpdate = [
            'nama'  => $namaAdmin,
            'email' => $emailAdmin
        ];

        $fileFoto = $this->request->getFile('foto_profil');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move('assets/img', $namaFile);

            $dataUpdate['foto_profil'] = $namaFile;
            $session->set('foto_profil', $namaFile);
        }

        if ($passwordBaru !== '') {
            if ($passwordLama === '') {
                return redirect()->to(base_url('admin/pengaturan'))
                    ->with('error', 'Masukkan kata sandi saat ini untuk mengubah kata sandi.');
            }
            if ($currentUser && !password_verify($passwordLama, (string) ($currentUser['password'] ?? ''))) {
                return redirect()->to(base_url('admin/pengaturan'))
                    ->with('error', 'Kata sandi saat ini tidak sesuai.');
            }
            if ($passwordBaru !== $konfirmasiPassword) {
                return redirect()->to(base_url('admin/pengaturan'))
                    ->with('error', 'Konfirmasi kata sandi baru tidak cocok.');
            }
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        $db->table('users')
           ->where('id_users', $userId)
           ->update($dataUpdate);

        $session->set('nama', $namaAdmin);
        $session->set('email', $emailAdmin);

        return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan berhasil diperbarui!');
    }



    public function logout()

    {

        session()->destroy();

        return redirect()->to('/')->with('success', 'Berhasil logout.');

    }

}
