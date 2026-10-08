<?php
$file = 'app/Controllers/pelatihan.php';
$c = file_get_contents($file);

$funcDaftarKelas = <<<'EOT'
public function daftarKelas()
    {
        $apiService = new \App\Services\LaravelApiService();
        $kelasData = [];
        
        try {
            $response = $apiService->request('GET', 'classes');
            $responseData = $response['data'] ?? $response ?? [];
            
            if (is_array($responseData)) {
                foreach ($responseData as $k) {
                    $kelasData[] = [
                        'id_kelas' => $k['id'] ?? null,
                        'nama_kelas' => $k['name'] ?? 'Kelas dari SIM',
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
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal mengambil data kelas dari Laravel API: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal memuat daftar kelas dari server utama. Silakan coba lagi nanti.');
        }

        $data['kelas'] = $kelasData;

        return view('peserta/daftar_kelas', $data);
    }
EOT;

$funcDetailKelas = <<<'EOT'
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
EOT;

$funcPendaftaran = <<<'EOT'
public function pendaftaran($id_kelas = null)
    {
        $db = \Config\Database::connect();
        $userId = method_exists($this, 'userId') ? $this->userId() : session()->get('id_users');

        $takenClassIds = [];
        if ($userId) {
            $takenRows = $db->table('pendaftaran')
                ->select('id_kelas')
                ->where('id_users', $userId)
                ->get()
                ->getResultArray();
            $takenClassIds = array_filter(array_column($takenRows, 'id_kelas'));
        }

        $apiService = new \App\Services\LaravelApiService();
        $availableClasses = [];
        try {
            $response = $apiService->request('GET', 'classes');
            $responseData = $response['data'] ?? $response ?? [];
            if (is_array($responseData)) {
                foreach ($responseData as $k) {
                    $id_kelas_api = $k['id'] ?? null;
                    if (!$id_kelas_api) continue;

                    if ($userId && in_array((int)$id_kelas_api, array_map('intval', $takenClassIds))) {
                        continue;
                    }

                    $mapped = [
                        'id_kelas' => $id_kelas_api,
                        'nama_kelas' => $k['name'] ?? 'Kelas',
                        'kategori' => $k['training']['name'] ?? $k['category'] ?? 'Umum',
                        'kategori_kelas' => $k['training']['name'] ?? $k['category'] ?? 'Umum',
                        'nama_mentor' => (!empty($k['trainers']) && isset($k['trainers'][0]['name'])) ? $k['trainers'][0]['name'] : 'Mentor Creativemu',
                        'kapasitas' => $k['capacity']['target_students'] ?? $k['capacity'] ?? 0,
                        'tanggal_mulai_kelas' => $k['schedule']['start_date'] ?? $k['start_date'] ?? date('Y-m-d'),
                        'jenis_kelas' => $k['type'] ?? 'Reguler',
                        'harga_reguler' => $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? 0,
                        'harga_privat' => $k['pricing']['price'] ?? $k['private_price'] ?? 0,
                        'metode_pembelajaran' => $k['method'] ?? $k['type'] ?? 'offline'
                    ];

                    $capacity = (int)$mapped['kapasitas'];
                    $terisi = $k['capacity']['enrolled_students'] ?? 0;
                    $sisa_kuota = max(0, $capacity - $terisi);

                    $mapped['sisa_kuota'] = $sisa_kuota;
                    $availableClasses[] = $mapped;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Gagal memuat data pendaftaran dari API: ' . $e->getMessage());
        }

        $data['kelas'] = $availableClasses;
        $data['selected_id_kelas'] = $id_kelas;

        return view('peserta/form_pendaftaran', $data);
    }
EOT;

$funcSimpanPendaftaran = <<<'EOT'
public function simpanPendaftaran()
    {
        $db = \Config\Database::connect();
        $pendaftaranModel = new \App\Models\PendaftaranModel();

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

        $apiService = new \App\Services\LaravelApiService();
        $kelas = null;
        try {
            $response = $apiService->request('GET', 'classes/' . $idKelas);
            $k = $response['data'] ?? $response ?? null;
            if ($k && isset($k['id'])) {
                $kelas = [
                    'id_kelas' => $k['id'],
                    'kapasitas' => $k['capacity']['target_students'] ?? 0,
                    'harga_reguler' => $k['pricing']['price'] ?? 0,
                    'harga_privat' => $k['pricing']['price'] ?? 0,
                    'tanggal_mulai_kelas' => $k['schedule']['start_date'] ?? date('Y-m-d')
                ];
            }
        } catch (\Exception $e) {}

        if (!$kelas) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data kelas pelatihan tidak ditemukan dari server API.');
        }

        $pendaftarAktif = $db->table('pendaftaran')
            ->where('id_kelas', $idKelas)
            ->whereIn('status', ['Menunggu', 'Disetujui'])
            ->countAllResults();

        $kapasitasKelas = (int)$kelas['kapasitas'];
        if ($kapasitasKelas > 0 && $pendaftarAktif >= $kapasitasKelas) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon maaf, kelas ini sudah penuh.');
        }

        $jenisKelas = $this->request->getPost('jenis_kelas');
        if (!in_array($jenisKelas, ['Reguler', 'Privat'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pilih jenis kelas yang valid (Reguler atau Privat).');
        }

        if ($jenisKelas == 'Reguler') {
            $hargaStr = $kelas['harga_reguler'];
        } else {
            $hargaStr = $kelas['harga_privat'];
        }

        $hargaStr = preg_replace('/[^0-9]/', '', $hargaStr);
        $totalBayar = (int) $hargaStr;

        $metodePembayaran = $this->request->getPost('metode_pembayaran');
        if (!in_array($metodePembayaran, ['Transfer Bank', 'E-Wallet', 'Tunai'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Silakan pilih metode pembayaran yang valid.');
        }

        $catatan = $this->request->getPost('catatan');
        $namaLengkap = $this->request->getPost('nama_lengkap');
        $noHp = $this->request->getPost('no_hp');
        $domisili = $this->request->getPost('domisili');

        $dataPendaftaran = [
            'id_users'           => $userId, 
            'id_kelas'           => $idKelas,
            'jenis_kelas'        => $jenisKelas,
            'metode_pembayaran'  => $metodePembayaran,
            'total_bayar'        => $totalBayar,
            'status'             => 'Menunggu',
            'catatan'            => $catatan,
            'nama_pendaftar'     => $namaLengkap,
            'no_hp_pendaftar'    => $noHp,
            'domisili_pendaftar' => $domisili,
            'status_pembayaran'  => 'pending',
            'tanggal_daftar'     => date('Y-m-d H:i:s'),
        ];

        $bukti = $this->request->getFile('bukti_pembayaran');
        if ($bukti && $bukti->isValid() && !$bukti->hasMoved()) {
            $newName = $bukti->getRandomName();
            $bukti->move(FCPATH . 'uploads/bukti_pembayaran', $newName);
            $dataPendaftaran['bukti_pembayaran'] = $newName;
        }

        if ($pendaftaranModel->insert($dataPendaftaran)) {
            if ($userId) {
                return redirect()->to(base_url('pelatihan/daftar-kelas-peserta'))
                    ->with('success', 'Pendaftaran berhasil dikirim. Menunggu konfirmasi admin.');
            } else {
                return redirect()->to(base_url('pelatihan/daftar-kelas'))
                    ->with('success', 'Pendaftaran berhasil. Silakan cek status secara berkala dengan fitur Cek Status.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pendaftaran.');
        }
    }
EOT;

$c = preg_replace('/public function daftarKelas\(\).*?return view\(\'peserta\/daftar_kelas\', \$data\);\s*\}/s', $funcDaftarKelas, $c, 1);
$c = preg_replace('/public function detailKelas\(\$id = null\).*?return view\(\'peserta\/detail_kelas\', \$data\);\s*\}/s', $funcDetailKelas, $c, 1);
$c = preg_replace('/public function pendaftaran\(\$id_kelas = null\).*?return view\(\'peserta\/form_pendaftaran\', \$data\);\s*\}/s', $funcPendaftaran, $c, 1);
$c = preg_replace('/public function simpanPendaftaran\(\).*?\}\s*\n\s*\/\*\*/s', $funcSimpanPendaftaran . "\n\n    /**", $c, 1);

file_put_contents($file, $c);
echo "Done";

