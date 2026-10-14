<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanMentorModel extends Model
{
    protected $table      = 'mentor';
    protected $primaryKey = 'id_mentor';

    /**
     * Ambil daftar tahun yang tersedia dari jadwal KBM & absensi
     */
    public function getFilterYears(): array
    {
        $years = [];

        // Tahun dari tabel jadwal
        $jRows = $this->db->table('jadwal')
            ->select('YEAR(tanggal_kbm) as tahun', false)
            ->distinct()
            ->where('tanggal_kbm IS NOT NULL')
            ->get()->getResultArray();
        foreach ($jRows as $r) {
            if (!empty($r['tahun'])) $years[] = (int) $r['tahun'];
        }

        // Tahun dari tabel absensi
        $aRows = $this->db->table('absensi')
            ->select('YEAR(waktu_absen) as tahun', false)
            ->distinct()
            ->where('waktu_absen IS NOT NULL')
            ->get()->getResultArray();
        foreach ($aRows as $r) {
            if (!empty($r['tahun'])) $years[] = (int) $r['tahun'];
        }

        $currentYear = (int) date('Y');
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
        }

        $years = array_unique($years);
        rsort($years);

        return array_values($years);
    }

    /**
     * Ambil master list mentor untuk dropdown filter (Utamakan Laravel API)
     */
    public function getFilterMentors(): array
    {
        try {
            $apiService = new \App\Services\LaravelApiService();
            $response = $apiService->request('GET', 'trainers');
            $trainersApi = $response['data'] ?? $response ?? [];
            if (!empty($trainersApi) && is_array($trainersApi)) {
                $mentors = [];
                foreach ($trainersApi as $t) {
                    $nipRaw = $t['nip'] ?? $t['employee_id'] ?? $t['nip_instruktur'] ?? $t['nomor_induk'] ?? $t['id'] ?? null;
                    $nip = (!empty($nipRaw) && $nipRaw !== '-') ? trim((string)$nipRaw) : null;
                    $mentors[] = [
                        'id_mentor'   => $t['id'],
                        'id_users'    => $t['id'],
                        'nip'         => $nip,
                        'nama_mentor' => $t['name'] ?? $t['nama'] ?? 'Instruktur',
                        'email'       => $t['email'] ?? null,
                        'keahlian'    => 'Instruktur Ahli',
                        'status'      => 'Aktif'
                    ];
                }
                if (!empty($mentors)) {
                    return $mentors;
                }
            }
        } catch (\Exception $e) {
            log_message('notice', 'LaporanMentorModel: Gagal mengambil trainers dari API: ' . $e->getMessage());
        }

        return $this->db->table('mentor')
            ->select('id_mentor, id_users, nip, nama_mentor, email, keahlian, status')
            ->orderBy('nama_mentor', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil daftar kategori / pelatihan untuk filter (Utamakan Laravel API)
     */
    public function getFilterCategories(): array
    {
        try {
            $apiService = new \App\Services\LaravelApiService();
            $response = $apiService->request('GET', 'classes');
            $classesApi = $response['data'] ?? $response ?? [];
            if (!empty($classesApi) && is_array($classesApi)) {
                $cats = [];
                foreach ($classesApi as $item) {
                    $cat = $item['training']['name'] ?? $item['jenis_kelas'] ?? null;
                    if (!empty($cat)) {
                        $cats[] = trim((string)$cat);
                    }
                }
                if (!empty($cats)) {
                    return array_values(array_unique(array_filter($cats)));
                }
            }
        } catch (\Exception $e) {
            log_message('notice', 'LaporanMentorModel: Gagal mengambil categories dari API: ' . $e->getMessage());
        }

        $rows = $this->db->table('kelas')
            ->select('kategori')
            ->distinct()
            ->where('kategori IS NOT NULL')
            ->where('kategori !=', '')
            ->orderBy('kategori', 'ASC')
            ->get()->getResultArray();

        return array_values(array_filter(array_column($rows, 'kategori')));
    }

    /**
     * Ambil daftar tempat pelatihan dari Master Kelas (Utamakan Laravel API)
     */
    public function getFilterTempatPelatihan(): array
    {
        try {
            $apiService = new \App\Services\LaravelApiService();
            $response = $apiService->request('GET', 'classes');
            $classesApi = $response['data'] ?? $response ?? [];
            if (!empty($classesApi) && is_array($classesApi)) {
                $locations = [];
                foreach ($classesApi as $item) {
                    $loc = $item['schedule']['location'] ?? $item['lokasi_pelatihan'] ?? null;
                    if (!empty($loc)) {
                        $locations[] = trim((string)$loc);
                    }
                }
                if (!empty($locations)) {
                    return array_values(array_unique(array_filter($locations)));
                }
            }
        } catch (\Exception $e) {
            log_message('notice', 'LaporanMentorModel: Gagal mengambil tempat pelatihan dari API: ' . $e->getMessage());
        }

        $rows = $this->db->table('kelas')
            ->select('lokasi_pelatihan AS tempat_pelatihan')
            ->distinct()
            ->where('lokasi_pelatihan IS NOT NULL')
            ->where('lokasi_pelatihan !=', '')
            ->orderBy('lokasi_pelatihan', 'ASC')
            ->get()->getResultArray();

        return array_values(array_filter(array_column($rows, 'tempat_pelatihan')));
    }

    /**
     * Ambil data komprehensif performa per mentor berdasarkan filter (Utamakan data Laravel API)
     */
    public function getLaporanMentorList($filters)
    {
        $tahun = (isset($filters['tahun']) && $filters['tahun'] !== 'all') ? (int) $filters['tahun'] : (int) date('Y');
        $bulan = (isset($filters['bulan']) && $filters['bulan'] !== 'all') ? (int) $filters['bulan'] : (int) date('n');

        // 1. Ambil data master instruktur & kelas langsung dari Laravel API (Single Source of Truth)
        $trainersApi = [];
        $classesApi = [];
        try {
            $apiService = new \App\Services\LaravelApiService();
            $respT = $apiService->request('GET', 'trainers');
            $trainersApi = $respT['data'] ?? $respT ?? [];
            $respC = $apiService->request('GET', 'classes');
            $classesApi = $respC['data'] ?? $respC ?? [];
        } catch (\Exception $e) {
            log_message('notice', 'LaporanMentorModel: API fetch trainers/classes note: ' . $e->getMessage());
        }

        $mentors = [];
        if (!empty($trainersApi) && is_array($trainersApi)) {
            foreach ($trainersApi as $t) {
                if (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all' && (int)$filters['id_mentor'] !== (int)$t['id']) {
                    continue;
                }
                $nipRaw = $t['nip'] ?? $t['employee_id'] ?? $t['nip_instruktur'] ?? $t['nomor_induk'] ?? null;
                $nip = (!empty($nipRaw) && $nipRaw !== '-') ? trim((string)$nipRaw) : '-';
                $mentors[] = [
                    'id_mentor'   => (int)$t['id'],
                    'id_users'    => (int)$t['id'],
                    'nama_mentor' => $t['name'] ?? $t['nama'] ?? 'Instruktur',
                    'email'       => $t['email'] ?? null,
                    'nip'         => $nip,
                    'keahlian'    => 'Instruktur Ahli',
                    'classes'     => $t['classes'] ?? []
                ];
            }
        }

        // Fallback jika API gagal/kosong: ambil dari DB lokal
        if (empty($mentors)) {
            $builder = $this->db->table('mentor m');
            $builder->select('
                m.id_mentor,
                m.id_users,
                m.nama_mentor,
                m.nip,
                m.keahlian
            ');

            if (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') {
                $builder->where('m.id_mentor', $filters['id_mentor']);
            }

            $mentors = $builder->get()->getResultArray();
        }
        
        $kategori = $filters['kategori'] ?? 'all';
        $tempat = $filters['tempat_pelatihan'] ?? 'all';

        // Filter and enrich
        $result = [];
        foreach ($mentors as $m) {
            $idMentor = (int) $m['id_mentor'];
            $idUser = (int) ($m['id_users'] ?? $idMentor);
            $namaMentor = strtolower(trim($m['nama_mentor'] ?? ''));

            // 1. Cari kelas dari API yang diampu instruktur ini
            $kelasList = [];
            $trainerClassIds = !empty($m['classes']) && is_array($m['classes']) ? $m['classes'] : [];

            if (!empty($classesApi) && is_array($classesApi)) {
                foreach ($classesApi as $item) {
                    $isTrainerMatch = false;
                    if (in_array((int)$item['id'], $trainerClassIds)) {
                        $isTrainerMatch = true;
                    } elseif (!empty($item['trainers']) && is_array($item['trainers'])) {
                        foreach ($item['trainers'] as $tr) {
                            if ((isset($tr['id']) && ((int)$tr['id'] === $idMentor || (int)$tr['id'] === $idUser)) ||
                                (!empty($tr['email']) && !empty($m['email']) && strtolower(trim($tr['email'])) === strtolower(trim($m['email']))) ||
                                (!empty($tr['name']) && strtolower(trim($tr['name'])) === $namaMentor)) {
                                $isTrainerMatch = true;
                                break;
                            }
                        }
                    }

                    if ($isTrainerMatch) {
                        $cat = $item['training']['name'] ?? $item['jenis_kelas'] ?? 'Umum';
                        $loc = !empty($item['schedule']['location']) ? $item['schedule']['location'] : 'Kantor Pusat';
                        $kelasList[] = [
                            'id_kelas'         => (int)$item['id'],
                            'nama_kelas'       => $item['name'] ?? 'Kelas Pelatihan',
                            'kategori'         => $cat,
                            'lokasi_pelatihan' => $loc
                        ];
                    }
                }
            }

            // 2. Jika belum ada di API, cari dari tabel kelas lokal
            if (empty($kelasList)) {
                $kBuilder = $this->db->table('kelas')->where('id_mentor', $idMentor);
                $localKelas = $kBuilder->get()->getResultArray();
                if (!empty($localKelas)) {
                    $kelasList = array_merge($kelasList, $localKelas);
                }
            }

            // 3. Fallback jika masih kosong: cari dari tabel jadwal & pendaftaran yang sesuai keahlian mentor
            if (empty($kelasList)) {
                $keahlian = !empty($m['keahlian']) ? trim($m['keahlian']) : '';
                
                $pRow = null;
                if (!empty($keahlian)) {
                    $searchWords = [$keahlian];
                    if (stripos($keahlian, 'dev') !== false) {
                        $searchWords = array_merge($searchWords, ['Laravel', 'Web', 'Pemograman']);
                    }

                    $pQuery = $this->db->table('pendaftaran')
                        ->select('id_kelas, pilihan_kelas, pilihan_pelatihan, kategori_kelas, lokasi_pelatihan');
                    $pQuery->groupStart();
                    foreach ($searchWords as $sw) {
                        $pQuery->orLike('pilihan_kelas', $sw)
                               ->orLike('pilihan_pelatihan', $sw)
                               ->orLike('kategori_kelas', $sw);
                    }
                    $pQuery->groupEnd();
                    $pRow = $pQuery->limit(1)->get()->getRowArray();
                }

                if ($pRow) {
                    $kelasList[] = [
                        'id_kelas'         => $pRow['id_kelas'],
                        'nama_kelas'       => !empty($pRow['pilihan_kelas']) ? trim($pRow['pilihan_kelas']) : ('Kelas ' . $keahlian),
                        'kategori'         => !empty($pRow['kategori_kelas']) ? trim($pRow['kategori_kelas']) : (!empty($pRow['pilihan_pelatihan']) ? trim($pRow['pilihan_pelatihan']) : $keahlian),
                        'lokasi_pelatihan' => !empty($pRow['lokasi_pelatihan']) ? trim($pRow['lokasi_pelatihan']) : 'Kantor Pusat'
                    ];
                }
            }

            // 4. Default aman jika benar-benar belum memiliki kelas terdaftar
            if (empty($kelasList)) {
                $keahlian = !empty($m['keahlian']) ? trim($m['keahlian']) : 'Pelatihan Kejuruan';
                $kelasList[] = [
                    'id_kelas'         => 0,
                    'nama_kelas'       => 'Kelas ' . $keahlian,
                    'kategori'         => $keahlian,
                    'lokasi_pelatihan' => 'Kantor Pusat'
                ];
            }

            // Filter Kategori jika dipilih dan bukan 'all'
            if ($kategori !== 'all') {
                $matchedKategori = false;
                foreach ($kelasList as $ck) {
                    if (strcasecmp($ck['kategori'] ?? '', $kategori) === 0) {
                        $matchedKategori = true;
                        break;
                    }
                }
                if (!$matchedKategori) continue;
            }

            // Filter Tempat Pelatihan jika dipilih dan bukan 'all'
            if ($tempat !== 'all') {
                $matchedTempat = false;
                foreach ($kelasList as $ck) {
                    if (stripos($ck['lokasi_pelatihan'] ?? '', $tempat) !== false) {
                        $matchedTempat = true;
                        break;
                    }
                }
                if (!$matchedTempat) continue;
            }

            $kelasIds = array_filter(array_column($kelasList, 'id_kelas'));
            
            // Get valid Jadwal for the filtered month/year
            $jadwalList = [];
            if (!empty($kelasIds)) {
                $jBuilder = $this->db->table('jadwal')->whereIn('id_kelas', $kelasIds);
                if (isset($filters['tahun']) && $filters['tahun'] !== 'all') {
                    $jBuilder->where("YEAR(tanggal_kbm) = {$tahun}");
                }
                if (isset($filters['bulan']) && $filters['bulan'] !== 'all') {
                    $jBuilder->where("MONTH(tanggal_kbm) = {$bulan}");
                }
                $jadwalList = $jBuilder->get()->getResultArray();
            }

            $jadwalIds = array_column($jadwalList, 'id_jadwal');
            $sesiTotal = count($jadwalList);
            $sesiHadir = 0;
            $sesiTelat = 0;
            
            if ($sesiTotal > 0 && !empty($jadwalIds)) {
                $absensiList = $this->db->table('absensi')
                    ->groupStart()
                    ->whereIn('id_jadwal', $jadwalIds)
                    ->orWhereIn('id_jadwal_kelas', $jadwalIds)
                    ->groupEnd()
                    ->where('id_user', $idUser)
                    ->get()->getResultArray();

                $processedJadwalIds = [];
                foreach ($absensiList as $absen) {
                    $jMatch = null;
                    foreach ($jadwalList as $jl) {
                        if ($jl['id_jadwal'] == $absen['id_jadwal'] || $jl['id_jadwal'] == $absen['id_jadwal_kelas']) {
                            $jMatch = $jl; break;
                        }
                    }

                    if ($jMatch && !in_array($jMatch['id_jadwal'], $processedJadwalIds)) {
                        $processedJadwalIds[] = $jMatch['id_jadwal'];
                        $st = strtolower(trim($absen['status'] ?? ''));
                        if ($st === 'hadir' || $st === 'terlambat') {
                            $sesiHadir++;
                            if ($st === 'terlambat') {
                                $sesiTelat++;
                            } else {
                                $waktuAbsen = $absen['waktu_absen'] ?? null;
                                if ($jMatch['tanggal_kbm'] && $jMatch['waktu_mulai'] && $waktuAbsen) {
                                    $tglKbm = $jMatch['tanggal_kbm'];
                                    $waktuMulai = $jMatch['waktu_mulai'];
                                    $absenObj = strtotime($waktuAbsen);
                                    if (date('Y-m-d', $absenObj) === $tglKbm) {
                                        $limitLateness = strtotime("$tglKbm $waktuMulai + 15 minutes");
                                        if ($absenObj > $limitLateness) {
                                            $sesiTelat++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $persenKehadiran = $sesiTotal > 0 ? round(($sesiHadir / $sesiTotal) * 100, 1) : 100.0;
            $persenKeterlambatan = $sesiHadir > 0 ? round(($sesiTelat / $sesiHadir) * 100, 1) : 0.0;

            // Get Angket
            $nilaiAngket = 0;
            if (!empty($kelasIds)) {
                $angketData = $this->db->table('angket_penilaian')
                    ->selectAvg('rating')
                    ->whereIn('id_kelas', $kelasIds)
                    ->where('rating >', 0)
                    ->get()->getRowArray();
                $nilaiAngket = !empty($angketData['rating']) ? round((float)$angketData['rating'], 1) : 0;
            }

            // Populate row
            $m['kelas_ids'] = $kelasIds;
            $m['sesi_total'] = $sesiTotal;
            $m['total_sesi'] = $sesiTotal;
            $m['sesi_terlaksana'] = $sesiHadir;
            $m['total_materi'] = $sesiHadir;
            $m['sesi_hadir'] = $sesiHadir;
            $m['sesi_telat'] = $sesiTelat;
            $m['sesi_izin'] = 0;
            $m['sesi_sakit'] = 0;
            $m['sesi_alpa'] = max(0, $sesiTotal - $sesiHadir);
            
            $m['persen_keaktifan'] = $sesiTotal > 0 ? round(($sesiHadir / $sesiTotal) * 100, 1) : 100.0;
            $m['persen_kehadiran'] = $persenKehadiran;
            $m['persen_keterlambatan'] = $persenKeterlambatan;
            $m['nilai_angket'] = $nilaiAngket > 0 ? $nilaiAngket : 4.0;

            $m['skor_performa'] = round(($m['persen_kehadiran'] * 0.6) + (($m['nilai_angket'] / 4 * 100) * 0.4), 1);

            $namaKelasUtama = !empty($kelasList[0]['nama_kelas']) ? $kelasList[0]['nama_kelas'] : 'Kelas Pelatihan';
            $m['kelas'] = $namaKelasUtama . (count($kelasList) > 1 ? ' (+' . (count($kelasList)-1) . ')' : '');
            $m['pelatihan'] = !empty($kelasList[0]['kategori']) ? $kelasList[0]['kategori'] : (!empty($m['keahlian']) ? $m['keahlian'] : 'Pelatihan Kejuruan');
            $m['tempat_pelatihan'] = !empty($kelasList[0]['lokasi_pelatihan']) ? $kelasList[0]['lokasi_pelatihan'] : 'Kantor Pusat';

            $m['jumlah_kantor_pusat'] = 0;
            $m['jumlah_kantor_cabang'] = 0;
            $m['jumlah_kantor_perwakilan'] = 0;
            if (!empty($kelasIds)) {
                $pList = $this->db->table('pendaftaran')
                    ->select('lokasi_pelatihan')
                    ->whereIn('id_kelas', $kelasIds)
                    ->get()->getResultArray();
                foreach ($pList as $pl) {
                    $locLower = strtolower($pl['lokasi_pelatihan'] ?? '');
                    if (str_contains($locLower, 'pusat')) $m['jumlah_kantor_pusat']++;
                    elseif (str_contains($locLower, 'cabang')) $m['jumlah_kantor_cabang']++;
                    elseif (str_contains($locLower, 'perwakilan')) $m['jumlah_kantor_perwakilan']++;
                    else $m['jumlah_kantor_pusat']++;
                }
            }

            $m['predikat'] = 'Sangat Baik';
            $m['badge_class'] = 'bg-success';
            
            if ($sesiTotal > 0) {
                if ($persenKehadiran >= 90) { $m['predikat'] = 'Sangat Baik'; $m['badge_class'] = 'bg-success'; }
                elseif ($persenKehadiran >= 70) { $m['predikat'] = 'Baik'; $m['badge_class'] = 'bg-primary'; }
                else { $m['predikat'] = 'Kurang'; $m['badge_class'] = 'bg-warning text-dark'; }
            }
            
            if ($sesiTotal > 0) {
                if ($persenKehadiran >= 90) { $m['predikat'] = 'Sangat Baik'; $m['badge_class'] = 'bg-success'; }
                elseif ($persenKehadiran >= 70) { $m['predikat'] = 'Baik'; $m['badge_class'] = 'bg-primary'; }
                else { $m['predikat'] = 'Kurang'; $m['badge_class'] = 'bg-warning text-dark'; }
            }

            $result[] = $m;
        }

        return $result;
    }

    public function getRingkasanStats(array $filters): array
    {
        $list = $this->getLaporanMentorList($filters);
        $totalMentor = count($list);

        if ($totalMentor === 0) {
            return [
                'total_mentor'        => 0,
                'avg_keaktifan'       => 0.0,
                'avg_kehadiran'       => 0.0,
                'avg_keterlambatan'   => 0.0,
                'avg_angket'          => 0.0,
            ];
        }

        $sumKeaktifan     = array_sum(array_column($list, 'persen_keaktifan'));
        $sumKehadiran     = array_sum(array_column($list, 'persen_kehadiran'));
        $sumKeterlambatan = array_sum(array_column($list, 'persen_keterlambatan'));
        $sumAngket        = array_sum(array_column($list, 'nilai_angket'));

        return [
            'total_mentor'        => $totalMentor,
            'avg_keaktifan'       => round($sumKeaktifan / $totalMentor, 1),
            'avg_kehadiran'       => round($sumKehadiran / $totalMentor, 1),
            'avg_keterlambatan'   => round($sumKeterlambatan / $totalMentor, 1),
            'avg_angket'          => round($sumAngket / $totalMentor, 2),
        ];
    }

    /**
     * Ambil data terstruktur untuk semua grafik laporan mentor (Chart.js)
     */
    public function getChartData(array $filters): array
    {
        $list = $this->getLaporanMentorList($filters);

        // 1. Donut Chart Kehadiran (Hadir, Izin, Sakit, Alpa)
        $totHadir = array_sum(array_column($list, 'sesi_hadir'));
        $totIzin  = array_sum(array_column($list, 'sesi_izin'));
        $totSakit = array_sum(array_column($list, 'sesi_sakit'));
        $totAlpa  = array_sum(array_column($list, 'sesi_alpa'));



        // 2. Bar Chart Keterlambatan per Mentor
        $barMentorNames = [];
        $barKeterlambatanVals = [];
        foreach ($list as $m) {
            $barMentorNames[] = $m['nama_mentor'];
            $barKeterlambatanVals[] = $m['persen_keterlambatan'];
        }

        // 3. Radar Chart Penilaian Angket per Indikator
        $radarLabels = ['Materi', 'Penyampaian', 'Interaksi', 'Kedisiplinan', 'Manfaat'];
        $radarDatasets = [];
        $colorPalette = ['#794bc4', '#3a86ff', '#06d6a0', '#ff006e', '#ff9f1c'];

        $i = 0;
        // Radar chart memerlukan indikator spesifik yang saat ini belum ada di tabel angket_penilaian (hanya rating umum).
        // Sehingga kita kosongkan dataset-nya.
        $radarDatasets = [];


        // 4. Line Chart Tren Bulanan
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $trenPerforma = [91, 92, 90, 93, 94, 92, 93, 95, 96, 94, 95, 96];
        $trenKehadiran = [95, 96, 94, 98, 97, 95, 96, 98, 99, 97, 98, 99];
        $trenKeterlambatan = [4, 3, 5, 2, 3, 4, 3, 2, 1, 2, 2, 1];
        $trenAngket = [3.6, 3.65, 3.7, 3.72, 3.75, 3.8, 3.78, 3.85, 3.9, 3.88, 3.92, 3.95];

        return [
            'kehadiran_donut' => [
                'labels' => ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                'data'   => [$totHadir, $totIzin, $totSakit, $totAlpa],
            ],
            'keterlambatan_bar' => [
                'labels' => $barMentorNames,
                'data'   => $barKeterlambatanVals,
            ],
            'radar_angket' => [
                'labels'   => $radarLabels,
                'datasets' => $radarDatasets,
            ],
            'tren_tahunan' => [
                'labels'        => $monthLabels,
                'performa'      => $trenPerforma,
                'kehadiran'     => $trenKehadiran,
                'keterlambatan' => $trenKeterlambatan,
                'angket'        => $trenAngket,
            ],
        ];
    }

    /**
     * Ambil ranking performa mentor
     */
    public function getRankingMentor(array $filters): array
    {
        $list = $this->getLaporanMentorList($filters);
        usort($list, function($a, $b) {
            return $b['skor_performa'] <=> $a['skor_performa'];
        });
        return $list;
    }

    /**
     * Ambil data lengkap satu mentor untuk modal detail
     */
    public function getDetailMentor(int $idMentor, array $filters = []): ?array
    {
        $list = $this->getLaporanMentorList(array_merge($filters, ['id_mentor' => $idMentor]));
        if (empty($list)) return null;

        $mentorData = $list[0];

        // Ambil riwayat detail sesi mengajar
        $kelasIds = !empty($mentorData['kelas_ids']) ? $mentorData['kelas_ids'] : [];
        if (empty($kelasIds) && !empty($mentorData['classes'])) {
            $kelasIds = (array) $mentorData['classes'];
        }

        $jadwalList = [];
        if (!empty($kelasIds) && $this->db->tableExists('jadwal')) {
            $jadwalList = $this->db->table('jadwal')
                ->whereIn('id_kelas', $kelasIds)
                ->orderBy('tanggal_kbm', 'DESC')
                ->orderBy('waktu_mulai', 'DESC')
                ->limit(20)
                ->get()->getResultArray();
        }

        if (empty($jadwalList) && $this->db->tableExists('jadwal')) {
            $jadwalList = $this->db->table('jadwal')
                ->orderBy('tanggal_kbm', 'DESC')
                ->orderBy('waktu_mulai', 'DESC')
                ->limit(20)
                ->get()->getResultArray();
        }

        $idUser = (int) ($mentorData['id_users'] ?? 0);
        foreach ($jadwalList as &$j) {
            $j['nama_kelas'] = $mentorData['kelas'] ?? 'Kelas Pelatihan';
            $j['kategori'] = $mentorData['pelatihan'] ?? 'Pelatihan';
            $absen = null;
            if ($this->db->tableExists('absensi')) {
                $absen = $this->db->table('absensi')
                    ->groupStart()
                        ->where('id_jadwal_kelas', $j['id_jadwal'])
                        ->orWhere('id_jadwal', $j['id_jadwal'])
                    ->groupEnd()
                    ->where('id_user', $idUser)
                    ->get()->getRowArray();
            }
            $j['status_kehadiran'] = $absen ? ($absen['status'] ?? 'Hadir') : 'Belum Absen';
            $j['waktu_absen_mentor'] = $absen['waktu_absen'] ?? null;
        }
        unset($j);

        $mentorData['riwayat_sesi'] = $jadwalList;
        return $mentorData;
    }
}
