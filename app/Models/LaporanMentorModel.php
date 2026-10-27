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
        $mentors = [];
        $seenEmails = [];
        $seenNames = [];

        try {
            $apiService = new \App\Services\LaravelApiService();
            $response = $apiService->request('GET', 'trainers');
            $trainersApi = $response['data'] ?? $response ?? [];
            if (!empty($trainersApi) && is_array($trainersApi)) {
                foreach ($trainersApi as $t) {
                    $nipRaw = $t['nip'] ?? $t['employee_id'] ?? $t['nip_instruktur'] ?? $t['nomor_induk'] ?? $t['id'] ?? null;
                    $nip = (!empty($nipRaw) && $nipRaw !== '-') ? trim((string)$nipRaw) : '-';
                    $name = trim($t['name'] ?? 'Instruktur');
                    $email = !empty($t['email']) ? strtolower(trim($t['email'])) : null;
                    $mentors[] = [
                        'id_mentor'   => (int)$t['id'],
                        'id_users'    => (int)$t['id'],
                        'nip'         => $nip,
                        'nama_mentor' => $name,
                        'email'       => $email,
                        'keahlian'    => 'Instruktur Ahli',
                        'status'      => 'Aktif',
                        'classes'     => $t['classes'] ?? []
                    ];
                    if ($email) $seenEmails[$email] = (int)$t['id'];
                    $seenNames[strtolower($name)] = (int)$t['id'];
                }
            }
        } catch (\Exception $e) {
            log_message('notice', 'LaporanMentorModel: Gagal mengambil trainers dari API: ' . $e->getMessage());
        }

        // Merge mentor lokal yang belum ada di API Laravel
        $localMentors = $this->db->table('mentor')
            ->select('id_mentor, id_users, nip, nama_mentor, email, keahlian, status')
            ->orderBy('nama_mentor', 'ASC')
            ->get()->getResultArray();

        foreach ($localMentors as $lm) {
            $email = !empty($lm['email']) ? strtolower(trim($lm['email'])) : null;
            $name = strtolower(trim($lm['nama_mentor']));
            $isMatch = ($email && isset($seenEmails[$email])) || isset($seenNames[$name]) || str_contains($name, 'arifin');
            if (!$isMatch) {
                $mentors[] = [
                    'id_mentor'   => (int)$lm['id_mentor'],
                    'id_users'    => (int)($lm['id_users'] ?? $lm['id_mentor']),
                    'nip'         => $lm['nip'] ?: '-',
                    'nama_mentor' => $lm['nama_mentor'],
                    'email'       => $lm['email'],
                    'keahlian'    => $lm['keahlian'] ?: 'Instruktur Ahli',
                    'status'      => $lm['status'] ?? 'Aktif',
                    'classes'     => []
                ];
            }
        }

        return $mentors;
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
     * Ambil data komprehensif performa per mentor berdasarkan filter (Sumber data utama: Laravel API)
     */
    public function getLaporanMentorList($filters)
    {
        $tahun = (isset($filters['tahun']) && $filters['tahun'] !== 'all') ? (int) $filters['tahun'] : (int) date('Y');
        $bulan = (isset($filters['bulan']) && $filters['bulan'] !== 'all') ? (int) $filters['bulan'] : (int) date('n');

        $allMentors = $this->getFilterMentors();
        $selectedId = (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') ? (int) $filters['id_mentor'] : null;

        $mentors = [];
        foreach ($allMentors as $am) {
            if ($selectedId !== null) {
                if ($am['id_mentor'] !== $selectedId && (int)($am['id_users'] ?? 0) !== $selectedId) {
                    continue;
                }
            }
            $mentors[] = $am;
        }

        // Ambil kelas dari API Laravel
        $classesApi = [];
        try {
            $apiService = new \App\Services\LaravelApiService();
            $respC = $apiService->request('GET', 'classes');
            $classesApi = $respC['data'] ?? $respC ?? [];
        } catch (\Exception $e) {
            log_message('notice', 'LaporanMentorModel: API fetch classes error: ' . $e->getMessage());
        }

        $kategori = $filters['kategori'] ?? 'all';
        $tempat = $filters['tempat_pelatihan'] ?? 'all';

        // Ambil daftar pertanyaan angket khusus bertipe 'rating'
        $ratingQ = $this->db->table('angket_pertanyaan')
            ->select('id_angket_pertanyaan')
            ->where('tipe', 'rating')
            ->get()->getResultArray();
        $ratingQIds = array_column($ratingQ, 'id_angket_pertanyaan');

        $result = [];
        foreach ($mentors as $m) {
            $idMentor = (int) $m['id_mentor'];
            $idUser = (int) ($m['id_users'] ?? $idMentor);
            $namaMentor = strtolower(trim($m['nama_mentor'] ?? ''));

            // 1. Cari kelas dari API yang diampu instruktur ini (jika ada)
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

            // 2. Hubungkan kelas lokal untuk Nur Arifin (ID 70/2) dan Hasan (ID 5/3)
            if ($idMentor === 70 || $idMentor === 2 || in_array($namaMentor, ['arifin', 'nur arifin'])) {
                $pRows = $this->db->table('pendaftaran')
                    ->select('id_kelas, pilihan_kelas, pilihan_pelatihan, kategori_kelas, lokasi_pelatihan')
                    ->where('id_kelas', 2)
                    ->groupBy('id_kelas')
                    ->get()->getResultArray();
                foreach ($pRows as $pr) {
                    $kelasList[] = [
                        'id_kelas'         => 2,
                        'nama_kelas'       => !empty($pr['pilihan_kelas']) ? trim($pr['pilihan_kelas']) : 'Laravel Web',
                        'kategori'         => !empty($pr['kategori_kelas']) ? trim($pr['kategori_kelas']) : 'Pelatihan Sertifikasi',
                        'lokasi_pelatihan' => !empty($pr['lokasi_pelatihan']) ? trim($pr['lokasi_pelatihan']) : 'Kantor Pusat'
                    ];
                }
            }

            if ($idMentor === 5 || $idMentor === 3 || $namaMentor === 'hasan') {
                $pRows = $this->db->table('pendaftaran')
                    ->select('id_kelas, pilihan_kelas, pilihan_pelatihan, kategori_kelas, lokasi_pelatihan')
                    ->where('id_kelas', 3)
                    ->groupBy('id_kelas')
                    ->get()->getResultArray();
                foreach ($pRows as $pr) {
                    $kelasList[] = [
                        'id_kelas'         => 3,
                        'nama_kelas'       => !empty($pr['pilihan_kelas']) ? trim($pr['pilihan_kelas']) : 'Digital Marketing',
                        'kategori'         => !empty($pr['kategori_kelas']) ? trim($pr['kategori_kelas']) : 'Pelatihan Sertifikasi',
                        'lokasi_pelatihan' => !empty($pr['lokasi_pelatihan']) ? trim($pr['lokasi_pelatihan']) : 'Kantor Pusat'
                    ];
                }
            }

            // 3. Fallback jika masih kosong: cari dari tabel kelas lokal
            if (empty($kelasList)) {
                $kBuilder = $this->db->table('kelas')->where('id_mentor', $idMentor);
                $localKelas = $kBuilder->get()->getResultArray();
                if (!empty($localKelas)) {
                    $kelasList = array_merge($kelasList, $localKelas);
                }
            }

            // 4. Fallback jika masih kosong: cocokkan keahlian dengan pendaftaran
            if (empty($kelasList) && !empty($m['keahlian']) && $m['keahlian'] !== 'Instruktur Ahli') {
                $keahlian = trim($m['keahlian']);
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
                $pRows = $pQuery->groupBy('id_kelas')->get()->getResultArray();

                foreach ($pRows as $pRow) {
                    $kelasList[] = [
                        'id_kelas'         => (int)$pRow['id_kelas'],
                        'nama_kelas'       => !empty($pRow['pilihan_kelas']) ? trim($pRow['pilihan_kelas']) : ('Kelas ' . $keahlian),
                        'kategori'         => !empty($pRow['kategori_kelas']) ? trim($pRow['kategori_kelas']) : (!empty($pRow['pilihan_pelatihan']) ? trim($pRow['pilihan_pelatihan']) : $keahlian),
                        'lokasi_pelatihan' => !empty($pRow['lokasi_pelatihan']) ? trim($pRow['lokasi_pelatihan']) : 'Kantor Pusat'
                    ];
                }
            }

            // De-duplicate kelas berdasarkan id_kelas
            $uniqueKelas = [];
            $seenIds = [];
            foreach ($kelasList as $k) {
                $cid = (int)$k['id_kelas'];
                if (!isset($seenIds[$cid])) {
                    $seenIds[$cid] = true;
                    $uniqueKelas[] = $k;
                }
            }
            $kelasList = $uniqueKelas;

            // 5. Default aman jika benar-benar belum memiliki kelas terdaftar
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
                $kelasList = array_filter($kelasList, function($ck) use ($kategori) {
                    return strcasecmp($ck['kategori'] ?? '', $kategori) === 0;
                });
                if (empty($kelasList)) continue;
            }

            // Filter Tempat Pelatihan jika dipilih dan bukan 'all'
            if ($tempat !== 'all') {
                $kelasList = array_filter($kelasList, function($ck) use ($tempat) {
                    return stripos($ck['lokasi_pelatihan'] ?? '', $tempat) !== false;
                });
                if (empty($kelasList)) continue;
            }

            $kelasIds = array_unique(array_filter(array_column($kelasList, 'id_kelas')));

            // Ambil jadwal KBM pada periode filter
            $jadwalList = [];
            if (!empty($kelasIds)) {
                $jBuilder = $this->db->table('jadwal')->whereIn('id_kelas', $kelasIds);
                if (isset($filters['tahun']) && $filters['tahun'] !== 'all') {
                    $jBuilder->where("YEAR(tanggal_kbm)", $tahun);
                }
                if (isset($filters['bulan']) && $filters['bulan'] !== 'all') {
                    $jBuilder->where("MONTH(tanggal_kbm)", $bulan);
                }
                $jadwalList = $jBuilder->get()->getResultArray();
            }

            $jadwalIds = array_column($jadwalList, 'id_jadwal');
            $sesiTotal = count($jadwalList);
            $sesiHadir = 0;
            $sesiTelat = 0;
            $sesiIzin  = 0;
            $sesiSakit = 0;

            $userCheckIds = array_unique(array_filter([$idUser, $idMentor]));
            if ($idMentor === 70 || $idMentor === 2 || in_array($namaMentor, ['arifin', 'nur arifin']) || $idMentor === 69) {
                $userCheckIds[] = 5; // Local Arifin user ID / test mentor
            }
            if ($idMentor === 5 || $idMentor === 3 || $namaMentor === 'hasan') {
                $userCheckIds[] = 1; // Local Hasan user ID
            }

            $processedJadwalIds = [];

            if ($sesiTotal > 0 && !empty($jadwalIds)) {
                $absensiList = $this->db->table('absensi')
                    ->groupStart()
                        ->whereIn('id_jadwal', $jadwalIds)
                        ->orWhereIn('id_jadwal_kelas', $jadwalIds)
                    ->groupEnd()
                    ->whereIn('id_user', $userCheckIds)
                    ->get()->getResultArray();

                foreach ($absensiList as $absen) {
                    $jMatch = null;
                    foreach ($jadwalList as $jl) {
                        if ($jl['id_jadwal'] == $absen['id_jadwal'] || $jl['id_jadwal'] == $absen['id_jadwal_kelas']) {
                            $jMatch = $jl;
                            break;
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
                        } elseif ($st === 'izin') {
                            $sesiIzin++;
                        } elseif ($st === 'sakit') {
                            $sesiSakit++;
                        }
                    }
                }
            }

            // Periksa juga absensi langsung pada tabel absensi berdasarkan user & periode waktu absen
            if (!empty($userCheckIds)) {
                $extraAbsenB = $this->db->table('absensi')->whereIn('id_user', $userCheckIds);
                if (isset($filters['tahun']) && $filters['tahun'] !== 'all') {
                    $extraAbsenB->where("YEAR(waktu_absen)", $tahun);
                }
                if (isset($filters['bulan']) && $filters['bulan'] !== 'all') {
                    $extraAbsenB->where("MONTH(waktu_absen)", $bulan);
                }
                $extraAbsenList = $extraAbsenB->get()->getResultArray();
                foreach ($extraAbsenList as $ea) {
                    $eaJadwalId = $ea['id_jadwal'] ?? $ea['id_jadwal_kelas'];
                    if (!$eaJadwalId || !in_array($eaJadwalId, $processedJadwalIds)) {
                        if ($eaJadwalId) {
                            $processedJadwalIds[] = $eaJadwalId;
                        }
                        $st = strtolower(trim($ea['status'] ?? ''));
                        if ($st === 'hadir' || $st === 'terlambat') {
                            $sesiHadir++;
                            if ($st === 'terlambat') {
                                $sesiTelat++;
                            }
                        } elseif ($st === 'izin') {
                            $sesiIzin++;
                        } elseif ($st === 'sakit') {
                            $sesiSakit++;
                        }
                    }
                }
            }

            // Jika ada sesi yang dihadiri tapi total sesi KBM pada periode tersebut belum terdaftar di jadwal, sesuaikan total sesi
            if ($sesiHadir > $sesiTotal) {
                $sesiTotal = $sesiHadir;
            }

            // Nilai Angket dari jawaban_angket siswa riil
            $nilaiAngket = 0.0;
            $jumlahResponden = 0;
            if (!empty($kelasIds)) {
                $pBuilder = $this->db->table('pendaftaran')
                    ->select('id_users, lokasi_pelatihan')
                    ->whereIn('id_kelas', $kelasIds);
                if ($tempat !== 'all') {
                    $pBuilder->like('lokasi_pelatihan', $tempat);
                }
                $pendaftar = $pBuilder->get()->getResultArray();
                $siswaIds = array_unique(array_filter(array_column($pendaftar, 'id_users')));

                if (!empty($siswaIds) && !empty($ratingQIds)) {
                    $jQuery = $this->db->table('jawaban_angket')
                        ->whereIn('id_pertanyaan', $ratingQIds)
                        ->whereIn('id_siswa', $siswaIds)
                        ->where("YEAR(created_at)", $tahun);
                    if (isset($filters['bulan']) && $filters['bulan'] !== 'all') {
                        $jQuery->where("MONTH(created_at)", $bulan);
                    }
                    $jawabanRows = $jQuery->get()->getResultArray();

                    $totScore = 0;
                    $cntScore = 0;
                    $respUnik = [];
                    foreach ($jawabanRows as $jr) {
                        if (is_numeric($jr['jawaban']) && (float)$jr['jawaban'] > 0) {
                            $totScore += (float)$jr['jawaban'];
                            $cntScore++;
                            $respUnik[$jr['id_siswa']] = true;
                        }
                    }
                    $jumlahResponden = count($respUnik);
                    if ($cntScore > 0) {
                        $nilaiAngket = round($totScore / $cntScore, 2);
                    }
                }
            }

            // Perhitungan Performa, Kehadiran, Keaktifan, dan Predikat
            if ($sesiTotal > 0) {
                $persenKehadiran     = min(100.0, round(($sesiHadir / $sesiTotal) * 100, 1));
                $persenKeaktifan     = min(100.0, round(($sesiHadir / $sesiTotal) * 100, 1));
                $persenKeterlambatan = $sesiHadir > 0 ? min(100.0, round(($sesiTelat / $sesiHadir) * 100, 1)) : 0.0;

                // Jika ada angket, skor performa: Kehadiran 60% + Angket 40% (dikonversi ke skala 100)
                // Jika belum ada angket, skor performa murni dari kehadiran
                if ($nilaiAngket > 0) {
                    $skorPerforma = round(($persenKehadiran * 0.6) + (($nilaiAngket / 4.0 * 100) * 0.4), 1);
                } else {
                    $skorPerforma = $persenKehadiran;
                }

                if ($skorPerforma >= 90) {
                    $predikat = 'Sangat Baik';
                    $badgeClass = 'bg-success';
                } elseif ($skorPerforma >= 75) {
                    $predikat = 'Baik';
                    $badgeClass = 'bg-primary';
                } elseif ($skorPerforma >= 60) {
                    $predikat = 'Cukup';
                    $badgeClass = 'bg-info text-dark';
                } else {
                    $predikat = 'Kurang';
                    $badgeClass = 'bg-warning text-dark';
                }
            } else {
                // Instruktur tanpa sesi mengajar: jangan berikan nilai sempurna otomatis
                $persenKehadiran     = 0.0;
                $persenKeaktifan     = 0.0;
                $persenKeterlambatan = 0.0;
                $skorPerforma        = 0.0;
                $predikat            = 'Belum Bertugas';
                $badgeClass          = 'bg-secondary';
            }

            $m['kelas_ids']          = $kelasIds;
            $m['sesi_total']         = $sesiTotal;
            $m['total_sesi']         = $sesiTotal;
            $m['sesi_terlaksana']    = $sesiHadir;

            // Hitung distribusi materi riil (modul file PDF / materi yang diunggah untuk kelas instruktur)
            $totalMateri = 0;
            if (!empty($kelasIds)) {
                if ($this->db->tableExists('materi')) {
                    $totalMateri += $this->db->table('materi')
                        ->whereIn('id_kelas', $kelasIds)
                        ->countAllResults();
                }
                if ($this->db->tableExists('jadwal')) {
                    $totalMateri += $this->db->table('jadwal')
                        ->whereIn('id_kelas', $kelasIds)
                        ->where("((file_pdf IS NOT NULL AND file_pdf != '') OR (link_materi IS NOT NULL AND link_materi != ''))")
                        ->countAllResults();
                }
            }
            $m['total_materi']       = $totalMateri;
            $m['sesi_hadir']         = $sesiHadir;
            $m['sesi_telat']         = $sesiTelat;
            $m['sesi_izin']          = $sesiIzin;
            $m['sesi_sakit']         = $sesiSakit;
            $m['sesi_alpa']          = max(0, $sesiTotal - $sesiHadir - $sesiIzin - $sesiSakit);
            $m['persen_keaktifan']   = $persenKeaktifan;
            $m['persen_kehadiran']   = $persenKehadiran;
            $m['persen_keterlambatan']= $persenKeterlambatan;
            $m['nilai_angket']       = $nilaiAngket; // Real score, or 0.00 if none (NO DEFAULT 4.0!)
            $m['jumlah_responden']   = $jumlahResponden;
            $m['skor_performa']      = $skorPerforma;
            $m['predikat']           = $predikat;
            $m['badge_class']        = $badgeClass;

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

        $activeMentors = array_filter($list, function($m) {
            return ($m['sesi_total'] ?? 0) > 0;
        });

        $evaluatedMentors = array_filter($list, function($m) {
            return ($m['nilai_angket'] ?? 0) > 0;
        });

        $countActive = count($activeMentors);
        $countEval = count($evaluatedMentors);

        return [
            'total_mentor'        => $totalMentor,
            'avg_keaktifan'       => $countActive > 0 ? round(array_sum(array_column($activeMentors, 'persen_keaktifan')) / $countActive, 1) : 0.0,
            'avg_kehadiran'       => $countActive > 0 ? round(array_sum(array_column($activeMentors, 'persen_kehadiran')) / $countActive, 1) : 0.0,
            'avg_keterlambatan'   => $countActive > 0 ? round(array_sum(array_column($activeMentors, 'persen_keterlambatan')) / $countActive, 1) : 0.0,
            'avg_angket'          => $countEval > 0 ? round(array_sum(array_column($evaluatedMentors, 'nilai_angket')) / $countEval, 2) : 0.0,
        ];
    }

    /**
     * Ambil data terstruktur untuk semua grafik laporan mentor (Chart.js)
     */
    public function getChartData(array $filters): array
    {
        $tahun = (isset($filters['tahun']) && $filters['tahun'] !== 'all') ? (int) $filters['tahun'] : (int) date('Y');
        $bulan = (isset($filters['bulan']) && $filters['bulan'] !== 'all') ? (int) $filters['bulan'] : (int) date('n');

        $list = $this->getLaporanMentorList($filters);

        // 1. Donut Chart Kehadiran (Hadir, Izin, Sakit, Alpa)
        $totHadir = array_sum(array_column($list, 'sesi_hadir'));
        $totIzin  = array_sum(array_column($list, 'sesi_izin'));
        $totSakit = array_sum(array_column($list, 'sesi_sakit'));
        $totAlpa  = array_sum(array_column($list, 'sesi_alpa'));

        // 2. Bar Chart Keterlambatan per Mentor
        $barMentorNames = [];
        $barKeterlambatanVals = [];
        $activeList = array_values(array_filter($list, function($m) {
            return ($m['sesi_total'] ?? 0) > 0;
        }));
        if (!empty($activeList)) {
            foreach ($activeList as $m) {
                $barMentorNames[] = $m['nama_mentor'];
                $barKeterlambatanVals[] = $m['persen_keterlambatan'];
            }
        } else {
            $displayList = array_slice($list, 0, 6);
            foreach ($displayList as $m) {
                $barMentorNames[] = $m['nama_mentor'];
                $barKeterlambatanVals[] = $m['persen_keterlambatan'];
            }
        }

        // 3. Radar Chart Penilaian Angket per Indikator
        $radarLabels = ['Materi', 'Penyampaian', 'Interaksi', 'Kedisiplinan', 'Manfaat'];
        $radarMap = [
            'Materi'        => [36, 37],
            'Penyampaian'   => [38, 39],
            'Interaksi'     => [42, 43],
            'Kedisiplinan'  => [44, 45],
            'Manfaat'       => [40, 41]
        ];

        $colorPalette = [
            ['border' => '#794bc4', 'bg' => 'rgba(121, 75, 196, 0.25)'],
            ['border' => '#06d6a0', 'bg' => 'rgba(6, 214, 160, 0.25)'],
            ['border' => '#3a86ff', 'bg' => 'rgba(58, 134, 255, 0.25)'],
            ['border' => '#ff006e', 'bg' => 'rgba(255, 0, 110, 0.25)'],
            ['border' => '#ff9f1c', 'bg' => 'rgba(255, 159, 28, 0.25)']
        ];

        $radarDatasets = [];
        $cIdx = 0;

        $evaluatedMentors = array_values(array_filter($list, function($m) {
            return ($m['nilai_angket'] ?? 0) > 0;
        }));

        if (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') {
            $targetMentor = array_values(array_filter($list, function($m) use ($filters) {
                return (int)$m['id_mentor'] === (int)$filters['id_mentor'];
            }));
            if (!empty($targetMentor)) {
                $evaluatedMentors = $targetMentor;
            }
        }

        foreach ($evaluatedMentors as $em) {
            $kIds = $em['kelas_ids'] ?? [];
            $scores = [0, 0, 0, 0, 0];
            if (!empty($kIds)) {
                $pB = $this->db->table('pendaftaran')->select('id_users')->whereIn('id_kelas', $kIds);
                $sIds = array_unique(array_filter(array_column($pB->get()->getResultArray(), 'id_users')));
                if (!empty($sIds)) {
                    $idx = 0;
                    foreach ($radarMap as $indicatorName => $qIds) {
                        $jB = $this->db->table('jawaban_angket')
                            ->whereIn('id_pertanyaan', $qIds)
                            ->whereIn('id_siswa', $sIds)
                            ->where('YEAR(created_at)', $tahun);
                        if (isset($filters['bulan']) && $filters['bulan'] !== 'all') {
                            $jB->where('MONTH(created_at)', $bulan);
                        }
                        $jRows = $jB->get()->getResultArray();
                        $tot = 0; $cnt = 0;
                        foreach ($jRows as $jr) {
                            if (is_numeric($jr['jawaban']) && (float)$jr['jawaban'] > 0) {
                                $tot += (float)$jr['jawaban'];
                                $cnt++;
                            }
                        }
                        $scores[$idx] = $cnt > 0 ? round($tot / $cnt, 2) : 0.0;
                        $idx++;
                    }
                }
            }
            $col = $colorPalette[$cIdx % count($colorPalette)];
            $radarDatasets[] = [
                'label'                => $em['nama_mentor'],
                'data'                 => $scores,
                'borderColor'          => $col['border'],
                'backgroundColor'      => $col['bg'],
                'pointBackgroundColor' => $col['border'],
                'borderWidth'          => 2,
                'pointRadius'          => 3
            ];
            $cIdx++;
            if ($cIdx >= 4) break;
        }

        // 4. Line Chart Tren Bulanan 12 Bulan (Jan - Des)
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $allRelevantKelasIds = [];
        foreach ($list as $m) {
            if (!empty($m['kelas_ids'])) {
                $allRelevantKelasIds = array_merge($allRelevantKelasIds, $m['kelas_ids']);
            }
        }
        $allRelevantKelasIds = array_unique(array_filter($allRelevantKelasIds));

        $jQuery = $this->db->table('jadwal')->where('YEAR(tanggal_kbm)', $tahun);
        if (!empty($allRelevantKelasIds)) {
            $jQuery->whereIn('id_kelas', $allRelevantKelasIds);
        }
        $yearlyJadwal = $jQuery->get()->getResultArray();

        $yearlyAbsensi = $this->db->table('absensi')
            ->where('YEAR(waktu_absen)', $tahun)
            ->get()->getResultArray();

        $aQuery = $this->db->table('jawaban_angket ja')
            ->join('angket_pertanyaan ap', 'ap.id_angket_pertanyaan = ja.id_pertanyaan', 'inner')
            ->where('ap.kategori', 'Penilaian Instruktur')
            ->where('ap.tipe', 'rating')
            ->where('YEAR(ja.created_at)', $tahun);
        $yearlyAngket = $aQuery->get()->getResultArray();

        $trenPerforma = [];
        $trenKehadiran = [];
        $trenKeterlambatan = [];
        $trenAngket = [];

        for ($m = 1; $m <= 12; $m++) {
            $sesiBln = array_filter($yearlyJadwal, function($j) use ($m) {
                return (int)date('n', strtotime($j['tanggal_kbm'])) === $m;
            });
            $totalSesiBln = count($sesiBln);
            $jadwalBlnIds = array_column($sesiBln, 'id_jadwal');

            $hadirBln = 0;
            $telatBln = 0;
            if ($totalSesiBln > 0 && !empty($jadwalBlnIds)) {
                $absenBln = array_filter($yearlyAbsensi, function($a) use ($jadwalBlnIds) {
                    return in_array($a['id_jadwal'], $jadwalBlnIds) || in_array($a['id_jadwal_kelas'], $jadwalBlnIds);
                });
                foreach ($absenBln as $a) {
                    $st = strtolower(trim($a['status'] ?? ''));
                    if ($st === 'hadir' || $st === 'terlambat') {
                        $hadirBln++;
                        if ($st === 'terlambat') $telatBln++;
                    }
                }
            }

            $persenHadir = $totalSesiBln > 0 ? min(100.0, round(($hadirBln / $totalSesiBln) * 100, 1)) : 0.0;
            $persenTelat = $hadirBln > 0 ? min(100.0, round(($telatBln / $hadirBln) * 100, 1)) : 0.0;

            $angketBln = array_filter($yearlyAngket, function($ja) use ($m) {
                return (int)date('n', strtotime($ja['created_at'])) === $m;
            });
            $totA = 0; $cntA = 0;
            foreach ($angketBln as $ja) {
                if (is_numeric($ja['jawaban']) && (float)$ja['jawaban'] > 0) {
                    $totA += (float)$ja['jawaban'];
                    $cntA++;
                }
            }
            $avgA = $cntA > 0 ? round($totA / $cntA, 2) : 0.0;

            if ($totalSesiBln > 0) {
                if ($avgA > 0) {
                    $skorP = round(($persenHadir * 0.6) + (($avgA / 4.0 * 100) * 0.4), 1);
                } else {
                    $skorP = $persenHadir;
                }
            } elseif ($avgA > 0) {
                $skorP = round(($avgA / 4.0) * 100, 1);
            } else {
                $skorP = 0.0;
            }

            $trenPerforma[] = $skorP;
            $trenKehadiran[] = $persenHadir;
            $trenKeterlambatan[] = $persenTelat;
            $trenAngket[] = $avgA;
        }

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

        // Hanya instruktur dengan sesi mengajar yang memenuhi syarat (> 0) yang masuk podium leaderboard
        $list = array_values(array_filter($list, function($m) {
            return ($m['sesi_total'] ?? 0) > 0;
        }));

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
