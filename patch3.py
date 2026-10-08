import re

with open('app/Controllers/pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

daftarKelas_new = """    public function daftarKelas()
    {
        $apiService = new \\App\\Services\\LaravelApiService();
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
        } catch (\\Exception $e) {
            log_message('error', 'Gagal mengambil data kelas dari API Laravel: ' . $e->getMessage());
            $data['kelas'] = [];
            $data['api_error'] = true;
        }

        return view('peserta/daftar_kelas', $data);
    }"""

# We just find the function bounds using a simple search
def replace_func(func_name, new_code, text):
    start = text.find(f"    public function {func_name}(")
    if start == -1:
        start = text.find(f"    public function {func_name}()")
    
    if start != -1:
        # Find the matching closing brace
        brace_count = 0
        in_func = False
        end = -1
        for i in range(start, len(text)):
            if text[i] == '{':
                brace_count += 1
                in_func = True
            elif text[i] == '}':
                brace_count -= 1
            
            if in_func and brace_count == 0:
                end = i + 1
                break
        
        if end != -1:
            return text[:start] + new_code + text[end:]
    return text

content = replace_func('daftarKelas', daftarKelas_new, content)

detailKelas_new = """    public function detailKelas($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'ID Kelas tidak valid.');
        }

        $apiService = new \\App\\Services\\LaravelApiService();
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
        } catch (\\Exception $e) {
            log_message('error', 'Gagal memuat detail kelas dari API: ' . $e->getMessage());
            return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'Sistem gagal menghubungi server utama.');
        }

        return view('peserta/detail_kelas', $data);
    }"""

content = replace_func('detailKelas', detailKelas_new, content)

with open('app/Controllers/pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)
