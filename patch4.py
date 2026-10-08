import re
with open('app/Controllers/pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace pendaftaran local query
pendaftaran_old = """        // 2. Ambil seluruh kelas yang berstatus aktif dari database
        $kelasBuilder = $db->table('kelas')
            ->select('kelas.*, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('LOWER(kelas.status)', 'aktif');

        // Jika user login, kecualikan kelas yang sudah pernah didaftarkan
        if (!empty($takenClassIds)) {
            $kelasBuilder->whereNotIn('kelas.id_kelas', $takenClassIds);
        }

        $availableClasses = $kelasBuilder->orderBy('kelas.id_kelas', 'DESC')->get()->getResultArray();"""

pendaftaran_new = """        // 2. Ambil seluruh kelas yang berstatus aktif dari API Laravel
        $apiService = new \\App\\Services\\LaravelApiService();
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
        } catch (\\Exception $e) {
            log_message('error', 'Gagal memuat kelas dari API: ' . $e->getMessage());
        }"""
content = content.replace(pendaftaran_old, pendaftaran_new)

# Replace simpanPendaftaran local query
simpanPendaftaran_old = """        $kelas = $db->table('kelas')
            ->select('kelas.*, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('id_kelas', $idKelas)
            ->get()->getRowArray();"""

simpanPendaftaran_new = """        $apiService = new \\App\\Services\\LaravelApiService();
        $kelas = null;
        try {
            $response = $apiService->request('GET', 'classes/' . $idKelas);
            $k = $response['data'] ?? $response ?? null;
            if ($k && isset($k['id'])) {
                $kelas = [
                    'id_kelas' => $k['id'],
                    'kapasitas' => $k['capacity']['target_students'] ?? $k['capacity'] ?? 0,
                    'harga_reguler' => $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? 0,
                    'harga_privat' => $k['pricing']['price'] ?? $k['private_price'] ?? 0,
                    'jenis_kelas' => $k['type'] ?? 'Reguler',
                ];
            }
        } catch (\\Exception $e) {}"""
content = content.replace(simpanPendaftaran_old, simpanPendaftaran_new)

with open('app/Controllers/pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)
