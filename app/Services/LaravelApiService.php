<?php

namespace App\Services;

class LaravelApiService
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        // Mengambil URL dan API Key dari file .env dengan env() agar lebih aman
        $this->apiUrl = rtrim(env('LARAVEL_API_URL', ''), "'\" ");
        $this->apiKey = trim(env('LARAVEL_API_KEY', ''), "'\" ");
        
        // Pastikan baseURI berakhiran slash agar Guzzle/CI4 tidak menghilangkan segmen terakhir (/api)
        if (!empty($this->apiUrl) && substr($this->apiUrl, -1) !== '/') {
            $this->apiUrl .= '/';
        }
        
        // Memanggil HTTP Client bawaan CI4
        $this->client = \Config\Services::curlrequest([
            'baseURI' => $this->apiUrl,
        ]);
    }

    /**
     * Melakukan HTTP request ke API Laravel dan mengembalikan detail status HTTP beserta body response.
     * Digunakan agar pemanggil dapat membaca status HTTP (200, 201, 401, 422, 500) dan menangani error secara tepat.
     *
     * @param string $method GET, POST, PUT, DELETE, PATCH
     * @param string $endpoint Rute API (misal: 'private-class-requests', 'classes')
     * @param array $data Data JSON atau Query Params
     * @return array [
     *     'status'      => int (HTTP status code, atau 0 jika network/exception),
     *     'success'     => bool (true jika HTTP status 2xx),
     *     'data'        => mixed (konten dari $decoded['data'] atau $decoded),
     *     'body'        => array (seluruh $decoded),
     *     'error_type'  => string ('none', 'unauthenticated', 'validation', 'server', 'client', 'network'),
     *     'message'     => string
     * ]
     */
    public function requestWithResponse(string $method, string $endpoint, array $data = []): array
    {
        $method = strtoupper(trim($method));
        $endpoint = ltrim(trim($endpoint), '/');

        // Menyiapkan Header wajib dan batasan timeout agar tidak hang
        $options = [
            'timeout'         => 5, // Batas waktu maksimal 5 detik
            'connect_timeout' => 3, // Maksimal 3 detik untuk koneksi
            'headers'         => [
                'X-API-KEY'    => $this->apiKey,
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent'   => 'Creativemu-Academy-CI4/1.0',
            ],
            'http_errors'     => false, // Mencegah lemparan exception pada 4xx/5xx agar response body tetap dapat diurai
        ];

        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $options['json'] = $data;
        } elseif ($method === 'GET' && !empty($data)) {
            $options['query'] = $data;
        }

        try {
            $response = $this->client->request($method, $endpoint, $options);
            $statusCode = (int) $response->getStatusCode();
            $rawBody = (string) $response->getBody();
            $decoded = json_decode($rawBody, true);
            if (!is_array($decoded)) {
                $decoded = [];
            }

            $isSuccess = ($statusCode >= 200 && $statusCode < 300);
            $errorType = 'none';

            if (!$isSuccess) {
                if ($statusCode === 401 || $statusCode === 403) {
                    $errorType = 'unauthenticated';
                } elseif ($statusCode === 422) {
                    $errorType = 'validation';
                } elseif ($statusCode >= 500) {
                    $errorType = 'server';
                } else {
                    $errorType = 'client';
                }

                // Logging aman: Jangan pernah mencatat API Key atau kredensial rahasia di log
                $errMsg = $decoded['message'] ?? ('HTTP status ' . $statusCode);
                log_message('warning', "LaravelApiService [{$method} {$endpoint}] returned HTTP {$statusCode}: {$errMsg}");
            }

            $message = $decoded['message'] ?? ($isSuccess ? 'Success' : ('HTTP ' . $statusCode));

            return [
                'status'     => $statusCode,
                'success'    => $isSuccess,
                'data'       => $decoded['data'] ?? $decoded,
                'body'       => $decoded,
                'error_type' => $errorType,
                'message'    => $message,
            ];
        } catch (\Throwable $e) {
            // Logging aman tanpa mengekspos API key
            log_message('warning', "LaravelApiService request [{$method} {$endpoint}] network exception: " . $e->getMessage());
            return [
                'status'     => 0,
                'success'    => false,
                'data'       => null,
                'body'       => [],
                'error_type' => 'network',
                'message'    => 'Koneksi ke Laravel API gagal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Fungsi utama untuk melakukan request ke API Laravel (Kompatibel penuh dengan pemanggil lama)
     */
    public function request($method, $endpoint, $data = [])
    {
        $res = $this->requestWithResponse($method, $endpoint, $data);
        return !empty($res['body']) ? $res['body'] : [];
    }

    /**
     * Sinkronisasi data peserta dan kelas dari Laravel API ke database lokal CI4 secara idempotent.
     * Menggunakan cache/throttle 30 detik agar navigasi dan filter halaman tetap cepat.
     *
     * @param bool $force Jika true, abaikan throttle dan lakukan sinkronisasi langsung
     * @return array Ringkasan jumlah kelas dan peserta yang disinkronkan
     */
    public function syncParticipantsFromLaravel(bool $force = false): array
    {
        $cacheKey = 'laravel_participants_last_synced';
        if (!$force) {
            $lastSynced = cache($cacheKey);
            if ($lastSynced !== null && (time() - (int)$lastSynced) < 30) {
                return ['status' => 'skipped', 'message' => 'Synced recently within 30s'];
            }
        }

        $classesRes = $this->request('GET', 'classes');
        $classes = $classesRes['data'] ?? $classesRes ?? [];
        if (empty($classes) || !is_array($classes)) {
            return ['status' => 'error', 'message' => 'Empty classes response from Laravel API'];
        }

        $db = \Config\Database::connect();
        $bukuIndukModel = new \App\Models\BukuIndukModel();

        $syncedClasses = 0;
        $syncedParticipants = 0;

        foreach ($classes as $c) {
            $classId = (int) ($c['id'] ?? 0);
            if ($classId <= 0) {
                continue;
            }

            $className = trim((string) ($c['name'] ?? ('Kelas #' . $classId)));
            $category  = trim((string) ($c['training']['name'] ?? $c['category'] ?? $c['jenis_kelas'] ?? 'Umum'));
            $location  = null;
            if (!empty($c['lokasi_media']) && is_string($c['lokasi_media'])) {
                $location = trim($c['lokasi_media']);
            } elseif (!empty($c['location_media']) && is_string($c['location_media'])) {
                $location = trim($c['location_media']);
            } elseif (!empty($c['location'])) {
                if (is_array($c['location'])) {
                    $location = trim($c['location']['media'] ?? $c['location']['name'] ?? '');
                } elseif (is_string($c['location'])) {
                    $location = trim($c['location']);
                }
            }
            if ($location === '-' || $location === '') {
                $location = null;
            }

            $startDate = (!empty($c['schedule']['start_date']) && $c['schedule']['start_date'] !== '-')
                ? $c['schedule']['start_date']
                : ($c['start_date'] ?? null);

            // 1. Sinkronkan ke tabel 'kelas' lokal agar semua JOIN SQL di modul Admin langsung terisi
            $existingKelas = $db->table('kelas')->where('id_kelas', $classId)->get()->getRowArray();
            $kelasData = [
                'id_kelas'            => $classId,
                'nama_kelas'          => $className,
                'kategori'            => $category,
                'lokasi_pelatihan'    => $location,
                'tanggal_mulai_kelas' => $startDate,
                'status'              => $c['status'] ?? 'Aktif',
                'updated_at'          => date('Y-m-d H:i:s'),
            ];

            if ($existingKelas) {
                $db->table('kelas')->where('id_kelas', $classId)->update($kelasData);
            } else {
                $kelasData['created_at'] = date('Y-m-d H:i:s');
                $db->table('kelas')->insert($kelasData);
            }
            $syncedClasses++;

            // 2. Ambil detail kelas untuk mendapatkan daftar registrants
            $detailRes = $this->request('GET', 'classes/' . $classId);
            $cDetail = $detailRes['data'] ?? $detailRes ?? [];
            $registrants = $cDetail['registrants'] ?? [];

            if (empty($registrants) || !is_array($registrants)) {
                continue;
            }

            foreach ($registrants as $reg) {
                $meta = $reg['metadata'] ?? [];
                $rawEmail = !empty($reg['email']) ? trim($reg['email']) : null;
                $name = !empty($reg['name']) ? trim($reg['name']) : 'Peserta';
                $email = $rawEmail ?: ('peserta_' . ($reg['id'] ?? uniqid()) . '@peserta.local');
                $phone = !empty($reg['phone']) ? trim($reg['phone']) : '-';
                $alamat = !empty($meta['alamat']) ? $meta['alamat'] : '-';
                $ttl = !empty($meta['ttl']) ? $meta['ttl'] : '-';
                $gender = !empty($meta['jenis_kelamin']) ? $meta['jenis_kelamin'] : 'Laki-laki';

                // Match atau buat user di tabel 'users'
                $userId = null;
                if (!empty($meta['id_users'])) {
                    $uRow = $db->table('users')->where('id_users', (int)$meta['id_users'])->get()->getRowArray();
                    if ($uRow) {
                        $userId = (int) $uRow['id_users'];
                    }
                }
                if (!$userId && $rawEmail) {
                    $uRow = $db->table('users')->where('email', $rawEmail)->get()->getRowArray();
                    if ($uRow) {
                        $userId = (int) $uRow['id_users'];
                    }
                }
                if (!$userId && !empty($name) && $name !== 'Peserta Test') {
                    $uRow = $db->table('users')->where('nama', $name)->get()->getRowArray();
                    if ($uRow) {
                        $userId = (int) $uRow['id_users'];
                    }
                }

                if (!$userId && $rawEmail) {
                    $maxUser = $db->table('users')->selectMax('id_users')->get()->getRowArray();
                    $nextUserId = ((int) ($maxUser['id_users'] ?? 0)) + 1;
                    $newUserData = [
                        'id_users'   => $nextUserId,
                        'nama'       => substr($name, 0, 50),
                        'email'      => substr($rawEmail, 0, 30),
                        'password'   => password_hash('123456', PASSWORD_BCRYPT),
                        'role'       => 'peserta',
                        'no_hp'      => $phone,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    if ($db->fieldExists('is_registered', 'users')) {
                        $newUserData['is_registered'] = 0;
                    }
                    $db->table('users')->insert($newUserData);
                    $userId = $nextUserId;
                }

                // Tentukan status pendaftaran & pembayaran
                $regStatusRaw = strtolower(trim((string)($reg['status'] ?? 'pending')));
                $payStatusRaw = strtolower(trim((string)($reg['payment_status'] ?? 'pending')));

                $statusPendaftaran = 'Menunggu';
                if (in_array($regStatusRaw, ['approved', 'disetujui', 'diterima', 'active'], true)) {
                    $statusPendaftaran = 'Disetujui';
                } elseif (in_array($regStatusRaw, ['rejected', 'ditolak', 'batal'], true)) {
                    $statusPendaftaran = 'Ditolak';
                }

                $statusPembayaran = 'pending';
                if (in_array($payStatusRaw, ['paid', 'valid', 'lunas', 'disetujui'], true)) {
                    $statusPembayaran = 'valid';
                } elseif (in_array($payStatusRaw, ['rejected', 'ditolak', 'failed'], true)) {
                    $statusPembayaran = 'rejected';
                }

                $statusPeserta = ($statusPendaftaran === 'Disetujui') ? 'Aktif' : (($statusPendaftaran === 'Ditolak') ? 'Tidak Aktif' : 'Menunggu');

                // Cari pendaftaran yang cocok berdasarkan (id_kelas, email) atau (id_kelas, nama)
                $existingPendaftaran = null;
                if ($rawEmail) {
                    $pRow = $db->table('pendaftaran')
                        ->where('id_kelas', $classId)
                        ->where('email', $rawEmail)
                        ->get()->getRowArray();
                    if ($pRow) {
                        $existingPendaftaran = $pRow;
                    }
                }
                if (!$existingPendaftaran && !empty($name) && $name !== 'Peserta Test') {
                    $pRow = $db->table('pendaftaran')
                        ->where('id_kelas', $classId)
                        ->where('nama', $name)
                        ->get()->getRowArray();
                    if ($pRow) {
                        $existingPendaftaran = $pRow;
                    }
                }
                if (!$existingPendaftaran && !empty($reg['external_registration_id']) && $reg['external_registration_id'] !== 'VALIDATION-TEST') {
                    if (preg_match('/^REG-(\d+)$/', $reg['external_registration_id'], $m)) {
                        $pRow = $db->table('pendaftaran')
                            ->where('id_pendaftaran', (int)$m[1])
                            ->where('id_kelas', $classId)
                            ->get()->getRowArray();
                        if ($pRow && (strtolower($pRow['email']) === strtolower($email) || strtolower($pRow['nama']) === strtolower($name))) {
                            $existingPendaftaran = $pRow;
                        }
                    }
                }

                // Kelola NIS agar unik per peserta
                $nis = !empty($reg['nis']) ? trim($reg['nis']) : ($existingPendaftaran['nis'] ?? null);
                if (!empty($nis)) {
                    $builderCheck = $db->table('pendaftaran')->where('nis', $nis);
                    if ($existingPendaftaran) {
                        $builderCheck->where('id_pendaftaran !=', $existingPendaftaran['id_pendaftaran']);
                    }
                    if ($builderCheck->countAllResults() > 0) {
                        $nis = $bukuIndukModel->generateNis();
                    }
                } else {
                    $nis = $bukuIndukModel->generateNis();
                }

                $lokasiPelatihan    = $meta['lokasi_pelatihan'] ?? $location;
                $jenisKelas         = $meta['jenis_kelas'] ?? $c['jenis_kelas'] ?? 'Reguler';
                $kategoriKelas      = $meta['kategori_kelas'] ?? $category;
                $metodePembelajaran = $meta['metode_pembelajaran'] ?? 'offline';
                $metodePembayaran   = $meta['metode_pembayaran'] ?? 'COD';

                if ($existingPendaftaran) {
                    $updateData = [
                        'nama'                => $name,
                        'email'               => $email,
                        'no_hp'               => $phone,
                        'status_pendaftaran'  => $statusPendaftaran,
                        'status_pembayaran'   => $statusPembayaran,
                        'status'              => $statusPeserta,
                        'lokasi_pelatihan'    => $lokasiPelatihan ?: $existingPendaftaran['lokasi_pelatihan'],
                        'jenis_kelas'         => $jenisKelas ?: $existingPendaftaran['jenis_kelas'],
                        'kategori_kelas'      => $kategoriKelas ?: $existingPendaftaran['kategori_kelas'],
                        'metode_pembelajaran' => $metodePembelajaran ?: $existingPendaftaran['metode_pembelajaran'],
                        'metode_pembayaran'   => $metodePembayaran ?: $existingPendaftaran['metode_pembayaran'],
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ];
                    if (!empty($nis) && empty($existingPendaftaran['nis'])) {
                        $updateData['nis'] = $nis;
                    }
                    if ($userId && empty($existingPendaftaran['id_users'])) {
                        $updateData['id_users'] = $userId;
                    }
                    $db->table('pendaftaran')->where('id_pendaftaran', $existingPendaftaran['id_pendaftaran'])->update($updateData);
                } else {
                    $insertData = [
                        'id_kelas'            => $classId,
                        'id_users'            => $userId,
                        'nama'                => $name,
                        'email'               => $email,
                        'no_hp'               => $phone,
                        'nis'                 => $nis,
                        'status_pendaftaran'  => $statusPendaftaran,
                        'status_pembayaran'   => $statusPembayaran,
                        'status'              => $statusPeserta,
                        'lokasi_pelatihan'    => $lokasiPelatihan,
                        'jenis_kelas'         => $jenisKelas,
                        'kategori_kelas'      => $kategoriKelas,
                        'pilihan_kelas'       => $className,
                        'pilihan_pelatihan'   => $className,
                        'metode_pembelajaran' => $metodePembelajaran,
                        'metode_pembayaran'   => $metodePembayaran,
                        'alamat'              => $alamat,
                        'ttl'                 => $ttl,
                        'jenis_kelamin'       => $gender,
                        'pendidikan_terakhir' => 'Sma/Smk/Sederajat',
                        'created_at'          => !empty($reg['registered_at']) ? $reg['registered_at'] : date('Y-m-d H:i:s'),
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ];
                    $db->table('pendaftaran')->insert($insertData);
                }
                $syncedParticipants++;
            }
        }

        try {
            cache()->save($cacheKey, time(), 30);
        } catch (\Throwable $e) {
            // Abaikan jika cache driver tidak aktif
        }

        return [
            'status'       => 'success',
            'classes'      => $syncedClasses,
            'participants' => $syncedParticipants,
        ];
    }

    /**
     * Sinkronisasi nilai ujian peserta dari API Laravel ke database lokal CI4 (tabel nilai_ujian).
     * Laravel menjadi sumber utama nilai ujian. Admin di CI4 menentukan status kelulusan.
     *
     * @param bool $force Jika true, abaikan throttle 30 detik
     * @return array Ringkasan proses sinkronisasi
     */
    public function syncGradesFromLaravel(bool $force = false): array
    {
        $cacheKey = 'laravel_grades_last_synced';
        if (!$force) {
            $lastSynced = cache($cacheKey);
            if ($lastSynced !== null && (time() - (int)$lastSynced) < 30) {
                return ['status' => 'skipped', 'message' => 'Synced recently within 30s'];
            }
        }

        $classesRes = $this->request('GET', 'classes');
        $classes = $classesRes['data'] ?? $classesRes ?? [];
        if (empty($classes) || !is_array($classes)) {
            return ['status' => 'error', 'message' => 'Empty classes response from Laravel API'];
        }

        $db = \Config\Database::connect();
        $syncedScores = 0;

        foreach ($classes as $c) {
            $classId = (int) ($c['id'] ?? 0);
            if ($classId <= 0) {
                continue;
            }

            // Ambil detail kelas untuk membaca registrants
            $detailRes = $this->request('GET', 'classes/' . $classId);
            $cDetail = $detailRes['data'] ?? $detailRes ?? [];
            $registrants = $cDetail['registrants'] ?? [];

            if (empty($registrants) || !is_array($registrants)) {
                continue;
            }

            foreach ($registrants as $reg) {
                $meta = $reg['metadata'] ?? [];

                // Cek kemungkinan field nilai ujian yang dikirim dari Laravel
                $rawScore = $reg['score']
                    ?? $reg['grade']
                    ?? $reg['exam_score']
                    ?? $reg['nilai']
                    ?? $meta['score']
                    ?? $meta['grade']
                    ?? $meta['exam_score']
                    ?? $meta['nilai']
                    ?? $meta['nilai_ujian']
                    ?? null;

                if ($rawScore === null || $rawScore === '') {
                    continue;
                }

                $numericScore = (float) $rawScore;

                // Cari user ID peserta berdasarkan metadata, email, atau nama
                $userId = null;
                if (!empty($meta['id_users'])) {
                    $uRow = $db->table('users')->where('id_users', (int)$meta['id_users'])->get()->getRowArray();
                    if ($uRow) {
                        $userId = (int) $uRow['id_users'];
                    }
                }
                if (!$userId && !empty($reg['email'])) {
                    $uRow = $db->table('users')->where('email', trim($reg['email']))->get()->getRowArray();
                    if ($uRow) {
                        $userId = (int) $uRow['id_users'];
                    }
                }
                if (!$userId && !empty($reg['name'])) {
                    $uRow = $db->table('users')->where('nama', trim($reg['name']))->get()->getRowArray();
                    if ($uRow) {
                        $userId = (int) $uRow['id_users'];
                    }
                }

                if (!$userId) {
                    continue;
                }

                // Cek apakah data nilai_ujian untuk peserta dan kelas ini sudah ada
                $existingNilai = $db->table('nilai_ujian')
                    ->where('id_users', $userId)
                    ->where('id_kelas', $classId)
                    ->get()
                    ->getRowArray();

                if ($existingNilai) {
                    // Update nilai dari Laravel tanpa mengubah status kelulusan yang sudah ditentukan Admin
                    $updatePayload = [
                        'nilai'      => $numericScore,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    if ($existingNilai['nilai_awal'] === null) {
                        $updatePayload['nilai_awal'] = $numericScore;
                    }
                    $db->table('nilai_ujian')
                        ->where('id_nilai_ujian', $existingNilai['id_nilai_ujian'])
                        ->update($updatePayload);
                } else {
                    // Buat baris nilai_ujian baru dengan status default 'menunggu' (menunggu konfirmasi admin)
                    $maxNilai = $db->table('nilai_ujian')->selectMax('id_nilai_ujian')->get()->getRowArray();
                    $nextNilaiId = ((int) ($maxNilai['id_nilai_ujian'] ?? 0)) + 1;

                    $insertPayload = [
                        'id_nilai_ujian'   => $nextNilaiId,
                        'id_ujian'         => 1,
                        'id_users'         => $userId,
                        'id_kelas'         => $classId,
                        'nilai'            => $numericScore,
                        'nilai_awal'       => $numericScore,
                        'status_kelulusan' => 'menunggu',
                        'created_at'       => date('Y-m-d H:i:s'),
                        'updated_at'       => date('Y-m-d H:i:s'),
                    ];
                    $db->table('nilai_ujian')->insert($insertPayload);
                }

                $syncedScores++;
            }
        }

        try {
            cache()->save($cacheKey, time(), 30);
        } catch (\Throwable $e) {
            // Abaikan jika cache tidak aktif
        }

        return [
            'status'        => 'success',
            'synced_scores' => $syncedScores,
        ];
    }

    /**
     * Mengambil informasi file nilai dari API Laravel untuk kelas dan siswa tertentu.
     * Endpoint: GET /api/classes/{class_id}/grade-files?student_id={student_id}
     *
     * @param int $classId
     * @param string $studentId
     * @return array
     */
    public function getGradeFiles(int $classId, string $studentId): array
    {
        if ($classId <= 0 || trim($studentId) === '') {
            return [
                'status'      => 'error',
                'status_code' => 400,
                'file'        => null,
                'message'     => 'Parameter class_id dan student_id wajib diisi.',
            ];
        }

        $options = [
            'timeout'         => 5,
            'connect_timeout' => 3,
            'headers'         => [
                'X-API-KEY'    => $this->apiKey,
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'query'           => [
                'student_id' => $studentId,
            ],
            'http_errors'     => false,
        ];

        try {
            $response = $this->client->request('GET', "classes/{$classId}/grade-files", $options);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();
            $decoded = json_decode($body, true);

            if ($statusCode === 200 && is_array($decoded) && !empty($decoded['success'])) {
                $fileData = $decoded['data']['file'] ?? null;
                return [
                    'status'      => 'success',
                    'status_code' => 200,
                    'data'        => $decoded['data'] ?? [],
                    'file'        => $fileData,
                    'message'     => $decoded['message'] ?? 'Berhasil memuat berkas nilai.',
                ];
            }

            if ($statusCode === 403) {
                return [
                    'status'      => 'forbidden',
                    'status_code' => 403,
                    'file'        => null,
                    'message'     => $decoded['message'] ?? 'Akses ditolak: Siswa belum terverifikasi pada kelas ini di server pusat.',
                ];
            }

            if ($statusCode === 404) {
                return [
                    'status'      => 'not_found',
                    'status_code' => 404,
                    'file'        => null,
                    'message'     => $decoded['message'] ?? 'Berkas nilai tidak ditemukan di server pusat.',
                ];
            }

            return [
                'status'      => 'error',
                'status_code' => $statusCode,
                'file'        => null,
                'message'     => $decoded['message'] ?? ("Respons server Laravel: HTTP " . $statusCode),
            ];
        } catch (\Throwable $e) {
            log_message('warning', "LaravelApiService::getGradeFiles [class={$classId}, student={$studentId}] error: " . $e->getMessage());
            return [
                'status'      => 'error',
                'status_code' => 0,
                'file'        => null,
                'message'     => 'Koneksi ke server pusat timeout atau gagal: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Mengunduh berkas fisik nilai dari API Laravel untuk kelas, file, dan siswa tertentu.
     * Endpoint: GET /api/classes/{class_id}/grade-files/{grade_file_id}/download?student_id={student_id}
     *
     * @param int $classId
     * @param int|string $gradeFileId
     * @param string $studentId
     * @return array
     */
    public function downloadGradeFile(int $classId, $gradeFileId, string $studentId): array
    {
        if ($classId <= 0 || trim((string)$gradeFileId) === '' || trim($studentId) === '') {
            return [
                'success'     => false,
                'status_code' => 400,
                'message'     => 'Parameter class_id, grade_file_id, dan student_id wajib diisi.',
            ];
        }

        $options = [
            'timeout'         => 30, // 30 detik untuk download file binary
            'connect_timeout' => 5,
            'headers'         => [
                'X-API-KEY' => $this->apiKey,
                'Accept'    => '*/*',
            ],
            'query'           => [
                'student_id' => $studentId,
            ],
            'http_errors'     => false,
        ];

        try {
            $endpoint = "classes/{$classId}/grade-files/{$gradeFileId}/download";
            $response = $this->client->request('GET', $endpoint, $options);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody();

            if ($statusCode === 200) {
                // Cek apakah konten berupa JSON error (misal success: false)
                $decoded = json_decode($body, true);
                if (is_array($decoded) && isset($decoded['success']) && !$decoded['success']) {
                    return [
                        'success'     => false,
                        'status_code' => 400,
                        'message'     => $decoded['message'] ?? 'Gagal mengunduh file nilai.',
                    ];
                }

                $contentType = $response->getHeaderLine('Content-Type') ?: 'application/octet-stream';
                $disposition = $response->getHeaderLine('Content-Disposition');

                return [
                    'success'      => true,
                    'status_code'  => 200,
                    'body'         => $body,
                    'content_type' => $contentType,
                    'disposition'  => $disposition,
                ];
            }

            $decoded = json_decode($body, true);
            $msg = $decoded['message'] ?? ("Gagal mengunduh berkas dari server pusat (HTTP " . $statusCode . ").");

            return [
                'success'     => false,
                'status_code' => $statusCode,
                'message'     => $msg,
            ];
        } catch (\Throwable $e) {
            log_message('warning', "LaravelApiService::downloadGradeFile [class={$classId}, file={$gradeFileId}, student={$studentId}] error: " . $e->getMessage());
            return [
                'success'     => false,
                'status_code' => 0,
                'message'     => 'Koneksi unduh berkas ke server pusat terputus: ' . $e->getMessage(),
            ];
        }
    }
/**
     * Mengecek status absensi trainer dari API Laravel (Dokumen Integrasi Bagian 11)
     * Endpoint: GET /api/classes/{class_id}/trainer-attendance
     *
     * @param int $classId ID kelas Laravel
     * @param int|null $sessionNumber Nomor sesi pertemuan (opsional)
     * @return array [
     *     'success'     => bool,
     *     'checked_in'  => bool,
     *     'checked_out' => bool,
     *     'attendance'  => array|null,
     *     'raw'         => array
     * ]
     */
    public function getTrainerAttendance(int $classId, ?int $sessionNumber = null): array
    {
        if ($classId <= 0) {
            return [
                'success'     => false,
                'checked_in'  => false,
                'checked_out' => false,
                'attendance'  => null,
                'raw'         => [],
            ];
        }

        $query = [];
        if ($sessionNumber !== null && $sessionNumber > 0) {
            $query['session_number'] = $sessionNumber;
        }

        $res = $this->requestWithResponse('GET', 'classes/' . $classId . '/trainer-attendance', $query);
        $body = $res['body'] ?? [];

        return [
            'success'     => !empty($body['success']),
            'checked_in'  => !empty($body['checked_in']),
            'checked_out' => !empty($body['checked_out']),
            'attendance'  => $body['attendance'] ?? null,
            'raw'         => $body,
        ];
    }

    /**
     * Sinkronisasi data kehadiran / absensi instruktur dari API Laravel ke database lokal CI4.
     * Menggunakan endpoint: GET /api/classes/{class_id}/trainer-attendance
     *
     * @param bool $force Jika true, abaikan throttle 30 detik
     * @return array
     */
    public function syncTrainerAttendanceFromLaravel(bool $force = false): array
    {
        $cacheKey = 'laravel_trainer_attendance_last_synced';
        if (!$force) {
            $lastSynced = cache($cacheKey);
            if ($lastSynced !== null && (time() - (int)$lastSynced) < 30) {
                return ['status' => 'skipped', 'message' => 'Synced recently within 30s'];
            }
        }

        $classesRes = $this->request('GET', 'classes');
        $classes = $classesRes['data'] ?? $classesRes ?? [];
        if (empty($classes) || !is_array($classes)) {
            return ['status' => 'error', 'message' => 'Empty classes response from Laravel API'];
        }

        $db = \Config\Database::connect();
        $syncedCount = 0;

        foreach ($classes as $c) {
            $classId = (int) ($c['id'] ?? 0);
            if ($classId <= 0) {
                continue;
            }

            try {
                $attCheck = $this->getTrainerAttendance($classId);
            } catch (\Throwable $eAtt) {
                continue;
            }

            if (empty($attCheck['checked_in']) && empty($attCheck['attendance'])) {
                continue;
            }

            $attData = $attCheck['attendance'] ?? [];
            $sessionNum = !empty($attData['session_number']) ? (int)$attData['session_number'] : 1;
            $attDate = !empty($attData['attendance_date']) ? $attData['attendance_date'] : date('Y-m-d');
            $checkInTime = !empty($attData['check_in_time']) ? $attData['check_in_time'] : date('H:i:s');
            $statusRaw = strtolower(trim((string)($attData['status'] ?? 'hadir')));
            $statusAbsen = (in_array($statusRaw, ['terlambat', 'late'], true)) ? 'terlambat' : 'hadir';

            $waktuAbsen = $attDate . ' ' . (strlen($checkInTime) === 5 ? ($checkInTime . ':00') : $checkInTime);

            // Identifikasi trainer dari kelas ini
            $trainers = $c['trainers'] ?? [];
            $targetUserIds = [];

            if (!empty($trainers) && is_array($trainers)) {
                foreach ($trainers as $trainerItem) {
                    $trainerEmail = !empty($trainerItem['email']) ? strtolower(trim($trainerItem['email'])) : null;
                    $trainerName = !empty($trainerItem['name']) ? trim($trainerItem['name']) : null;
                    $trainerIdLaravel = (int)($trainerItem['id'] ?? 0);

                    if ($trainerEmail) {
                        $uRow = $db->table('users')->where('LOWER(email)', $trainerEmail)->get()->getRowArray();
                        if ($uRow) {
                            $targetUserIds[] = (int)$uRow['id_users'];
                        }
                    }
                    if ($trainerName) {
                        $uRow = $db->table('users')->where('LOWER(nama)', strtolower($trainerName))->get()->getRowArray();
                        if ($uRow) {
                            $targetUserIds[] = (int)$uRow['id_users'];
                        }
                    }
                    $mRow = null;
                    if ($trainerEmail) {
                        $mRow = $db->table('mentor')->where('LOWER(email)', $trainerEmail)->get()->getRowArray();
                    }
                    if (!$mRow && $trainerName) {
                        $mRow = $db->table('mentor')->where('LOWER(nama_mentor)', strtolower($trainerName))->get()->getRowArray();
                    }
                    if ($mRow) {
                        if (!empty($mRow['id_users'])) $targetUserIds[] = (int)$mRow['id_users'];
                        $targetUserIds[] = (int)$mRow['id_mentor'];
                    }
                    if ($trainerIdLaravel > 0) {
                        $targetUserIds[] = $trainerIdLaravel;
                    }
                }
            }

            $targetUserIds = array_unique(array_filter($targetUserIds));
            if (empty($targetUserIds)) {
                continue;
            }

            // Pastikan baris jadwal untuk kelas & sesi ini ada di database CI4
            $jadwalRow = $db->table('jadwal')
                ->where('id_kelas', $classId)
                ->where('pertemuan_ke', $sessionNum)
                ->get()
                ->getRowArray();

            if (!$jadwalRow) {
                // Buat baris jadwal otomatis jika belum ada
                $db->table('jadwal')->insert([
                    'id_kelas'        => $classId,
                    'pertemuan_ke'    => $sessionNum,
                    'tanggal_kbm'     => $attDate,
                    'waktu_mulai'     => '08:00:00',
                    'waktu_selesai'   => '10:00:00',
                    'materi'          => 'Pertemuan ' . $sessionNum,
                    'absensi_dibuka'  => 1,
                    'radius_meter'    => 100,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
                $idJadwal = (int)$db->insertID();
            } else {
                $idJadwal = (int)$jadwalRow['id_jadwal'];
                // Update tanggal kbm jika kosong atau berbeda
                if (empty($jadwalRow['tanggal_kbm']) || $jadwalRow['tanggal_kbm'] === '-') {
                    $db->table('jadwal')->where('id_jadwal', $idJadwal)->update([
                        'tanggal_kbm'    => $attDate,
                        'absensi_dibuka' => 1,
                    ]);
                }
            }

            foreach ($targetUserIds as $uId) {
                $existingAbsen = $db->table('absensi')
                    ->groupStart()
                        ->where('id_jadwal', $idJadwal)
                        ->orWhere('id_jadwal_kelas', $idJadwal)
                    ->groupEnd()
                    ->where('id_user', $uId)
                    ->get()
                    ->getRowArray();

                if (!$existingAbsen) {
                    $db->table('absensi')->insert([
                        'id_jadwal'       => $idJadwal,
                        'id_jadwal_kelas' => $idJadwal,
                        'id_user'         => $uId,
                        'status'          => $statusAbsen,
                        'waktu_absen'     => $waktuAbsen,
                        'created_at'      => $waktuAbsen,
                        'updated_at'      => $waktuAbsen,
                    ]);
                    $syncedCount++;
                } else {
                    if (strtolower($existingAbsen['status']) !== $statusAbsen) {
                        $db->table('absensi')->where('id_absensi', $existingAbsen['id_absensi'])->update([
                            'status'     => $statusAbsen,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        $syncedCount++;
                    }
                }
            }
        }

        try {
            cache()->save($cacheKey, time(), 30);
        } catch (\Throwable $eCache) {}

        return ['status' => 'success', 'synced' => $syncedCount];
    }
}
