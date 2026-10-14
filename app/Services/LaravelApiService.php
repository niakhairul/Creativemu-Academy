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
     * Fungsi utama untuk melakukan request ke API Laravel
     */
    public function request($method, $endpoint, $data = [])
    {
        // Menyiapkan Header wajib dan batasan timeout agar tidak hang/loading lama
        $options = [
            'timeout'         => 3, // Maksimal 3 detik agar page tidak hang jika server luar lambat
            'connect_timeout' => 2, // Maksimal 2 detik untuk koneksi
            'headers'         => [
                'X-API-KEY'    => $this->apiKey,
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'http_errors'     => false // Mencegah error bawaan CI4 agar response 404/422/500 dari Laravel tetap bisa dibaca
        ];

        // Jika ada data yang mau dikirim (biasanya untuk POST)
        if ($method === 'POST' || $method === 'PUT') {
            $options['json'] = $data;
        } 
        // Jika ada parameter query (biasanya untuk GET)
        else if ($method === 'GET' && !empty($data)) {
            $options['query'] = $data;
        }

        try {
            // Mengeksekusi request ke endpoint (misalnya: /classes)
            $response = $this->client->request($method, $endpoint, $options);
            
            // Mengubah hasil JSON dari Laravel menjadi Array PHP
            $decoded = json_decode($response->getBody(), true);
            return is_array($decoded) ? $decoded : [];
        } catch (\Throwable $e) {
            log_message('warning', "LaravelApiService request [{$method} {$endpoint}] failed: " . $e->getMessage());
            return [];
        }
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
                        'password'   => password_hash('12345678', PASSWORD_BCRYPT),
                        'role'       => 'peserta',
                        'no_hp'      => $phone,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
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

                // Kelola NIS agar unik per peserta yang disetujui
                $nis = !empty($reg['nis']) ? trim($reg['nis']) : ($existingPendaftaran['nis'] ?? null);
                if (!empty($nis)) {
                    $builderCheck = $db->table('pendaftaran')->where('nis', $nis);
                    if ($existingPendaftaran) {
                        $builderCheck->where('id_pendaftaran !=', $existingPendaftaran['id_pendaftaran']);
                    }
                    if ($builderCheck->countAllResults() > 0) {
                        $nis = $bukuIndukModel->generateNis();
                    }
                } elseif ($statusPendaftaran === 'Disetujui') {
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
}
