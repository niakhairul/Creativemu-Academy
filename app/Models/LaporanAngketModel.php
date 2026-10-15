<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanAngketModel extends Model
{
    protected $table      = 'angket_penilaian';
    protected $primaryKey = 'id_angket';

    /**
     * Ambil daftar tahun unik untuk filter
     */
    public function getFilterYears(): array
    {
        $years = [];

        // Ambil tahun dari jawaban_angket
        if ($this->db->tableExists('jawaban_angket')) {
            $rows = $this->db->table('jawaban_angket')
                ->select('YEAR(created_at) as tahun', false)
                ->distinct()
                ->where('created_at IS NOT NULL')
                ->get()->getResultArray();
            foreach ($rows as $r) {
                if (!empty($r['tahun'])) $years[] = (int) $r['tahun'];
            }
        }

        if ($this->db->tableExists('angket_penilaian')) {
            $rows = $this->db->table('angket_penilaian')
                ->select('YEAR(created_at) as tahun', false)
                ->distinct()
                ->where('created_at IS NOT NULL')
                ->get()->getResultArray();
            foreach ($rows as $r) {
                if (!empty($r['tahun'])) $years[] = (int) $r['tahun'];
            }
        }

        if ($this->db->tableExists('pendaftaran')) {
            $pRows = $this->db->table('pendaftaran')
                ->select('YEAR(created_at) as tahun', false)
                ->distinct()
                ->where('created_at IS NOT NULL')
                ->get()->getResultArray();
            foreach ($pRows as $r) {
                if (!empty($r['tahun'])) $years[] = (int) $r['tahun'];
            }
        }

        $currentYear = (int) date('Y');
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
        }

        // Tambahkan tahun sebelumnya agar admin bisa menguji laporan kosong sesuai permintaan
        if (!in_array($currentYear - 1, $years)) {
            $years[] = $currentYear - 1;
        }

        $years = array_unique($years);
        rsort($years);

        return array_values($years);
    }

    /**
     * Ambil master list mentor untuk dropdown (Utamakan Laravel API dan gabungkan data lokal)
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
            log_message('notice', 'LaporanAngketModel: Gagal mengambil trainers dari API: ' . $e->getMessage());
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
     * Ambil kelas dan kategori untuk dropdown filter (Utamakan Laravel API)
     */
    public function getFilterClasses(): array
    {
        try {
            $apiService = new \App\Services\LaravelApiService();
            $response = $apiService->request('GET', 'classes');
            $classesApi = $response['data'] ?? $response ?? [];
            if (!empty($classesApi) && is_array($classesApi)) {
                $result = [];
                foreach ($classesApi as $item) {
                    $namaMentor = null;
                    if (!empty($item['trainers']) && is_array($item['trainers'])) {
                        $namaMentor = $item['trainers'][0]['name'] ?? null;
                    }
                    $kategori = $item['training']['name'] ?? $item['jenis_kelas'] ?? 'Umum';
                    $result[] = [
                        'id_kelas'         => $item['id'],
                        'nama_kelas'       => $item['name'],
                        'kategori'         => $kategori,
                        'lokasi_pelatihan' => $item['location'] ?? '-',
                        'nama_mentor'      => $namaMentor
                    ];
                }
                if (!empty($result)) {
                    return $result;
                }
            }
        } catch (\Exception $e) {
            log_message('notice', 'LaporanAngketModel: Gagal mengambil classes dari API: ' . $e->getMessage());
        }

        return $this->db->table('kelas')
            ->select('kelas.id_kelas, kelas.nama_kelas, kelas.kategori, kelas.lokasi_pelatihan, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil daftar kategori pelatihan unik (Utamakan Laravel API)
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
            log_message('notice', 'LaporanAngketModel: Gagal mengambil categories dari API: ' . $e->getMessage());
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
     * Ambil daftar tempat pelatihan dari Master Kelas
     */

        private function getMatchedPendaftaranLocations(int $idLokasi): array
    {
        $master = $this->db->table('lokasi_pelatihan')->where('id_lokasi', $idLokasi)->get()->getRowArray();
        if (!$master) return [];

        $pendaftaran = $this->db->table('pendaftaran')->select('lokasi_pelatihan')->distinct()->get()->getResultArray();
        $matched = [];

        $normM = preg_replace('/[^a-z0-9]/', '', strtolower($master['nama_lokasi']));
        $prefixM = substr($normM, 0, 15);

        foreach ($pendaftaran as $p) {
            if (empty($p['lokasi_pelatihan'])) continue;
            $raw = $p['lokasi_pelatihan'];
            $normP = preg_replace('/[^a-z0-9]/', '', strtolower($raw));

            if (strpos($normP, $normM) !== false || strpos($normM, $normP) !== false) {
                $matched[] = $raw;
            } elseif (strlen($prefixM) >= 15 && substr($normP, 0, 15) === $prefixM) {
                $matched[] = $raw;
            }
        }
        return $matched;
    }

    public function getFilterTempatPelatihan(): array
    {
        $rows = $this->db->table('lokasi_pelatihan')
            ->select('id_lokasi, nama_lokasi')
            ->whereNotIn('id_lokasi', [4, 5])
            ->orderBy('id_lokasi', 'ASC')
            ->get()->getResultArray();
        $cleanArr = [];
        foreach($rows as $row) {
            $cleanArr[$row['id_lokasi']] = $row['nama_lokasi'];
        }
        return $cleanArr;
    }

    /**
     * Ambil rekapitulasi data evaluasi angket per mentor
     */
    public function getLaporanAngketList(array $filters): array
    {
        $mentors = $this->getFilterMentors();

        if (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') {
            $idFilter = (int)$filters['id_mentor'];
            $mentors = array_filter($mentors, function($m) use ($idFilter) {
                return (int)$m['id_mentor'] === $idFilter || (int)($m['id_users'] ?? 0) === $idFilter;
            });
        }

        $tahun = !empty($filters['tahun']) ? (int) $filters['tahun'] : (int) date('Y');
        $bulan = !empty($filters['bulan']) ? (int) $filters['bulan'] : (int) date('n');
        $kategori = $filters['kategori'] ?? 'all';
        $idKelas = $filters['id_kelas'] ?? 'all';
        $tempat = $filters['tempat_pelatihan'] ?? 'all';

        // Load classes from Laravel API
        $classesApi = [];
        try {
            $apiService = new \App\Services\LaravelApiService();
            $res = $apiService->request('GET', 'classes');
            $classesApi = $res['data'] ?? $res ?? [];
        } catch (\Exception $e) {
            log_message('notice', 'LaporanAngketModel: Gagal mengambil classes dari API: ' . $e->getMessage());
        }

        // Ambil ID pertanyaan dengan tipe 'rating'
        $ratingQuestions = $this->db->table('angket_pertanyaan')->select('id_angket_pertanyaan')->where('tipe', 'rating')->get()->getResultArray();
        $ratingQIds = array_column($ratingQuestions, 'id_angket_pertanyaan');

        $reportList = [];

        foreach ($mentors as $mentor) {
            $idMentor = (int)$mentor['id_mentor'];
            $idUser = (int)($mentor['id_users'] ?? $idMentor);
            $namaMentor = strtolower(trim($mentor['nama_mentor'] ?? ''));
            $emailMentor = strtolower(trim($mentor['email'] ?? ''));
            $trainerClassIds = !empty($mentor['classes']) && is_array($mentor['classes']) ? $mentor['classes'] : [];

            // 1. Cari kelas dari API
            $kelasList = [];
            if (!empty($classesApi) && is_array($classesApi)) {
                foreach ($classesApi as $item) {
                    $matched = false;
                    if (in_array((int)$item['id'], $trainerClassIds)) {
                        $matched = true;
                    } elseif (!empty($item['trainers']) && is_array($item['trainers'])) {
                        foreach ($item['trainers'] as $tr) {
                            if ((isset($tr['id']) && ((int)$tr['id'] === $idMentor || (int)$tr['id'] === $idUser)) ||
                                (!empty($tr['email']) && !empty($emailMentor) && strtolower(trim($tr['email'])) === $emailMentor) ||
                                (!empty($tr['name']) && strtolower(trim($tr['name'])) === $namaMentor)) {
                                $matched = true;
                                break;
                            }
                        }
                    }
                    if ($matched) {
                        $cat = $item['training']['name'] ?? $item['jenis_kelas'] ?? 'Umum';
                        $loc = !empty($item['schedule']['location']) ? $item['schedule']['location'] : (!empty($item['location']) ? $item['location'] : 'Kantor Pusat');
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
            if (empty($kelasList) && !empty($mentor['keahlian']) && $mentor['keahlian'] !== 'Instruktur Ahli') {
                $keahlian = trim($mentor['keahlian']);
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
                $keahlian = !empty($mentor['keahlian']) ? trim($mentor['keahlian']) : 'Pelatihan Kejuruan';
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

            // Filter ID Kelas jika dipilih dan bukan 'all'
            if ($idKelas !== 'all') {
                $kelasList = array_filter($kelasList, function($ck) use ($idKelas) {
                    return (int)($ck['id_kelas'] ?? 0) === (int)$idKelas;
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

            $totalPeserta = 0;
            $siswaIds = [];
            $studentLocationMap = [];

            if (!empty($kelasIds)) {
                $pB = $this->db->table('pendaftaran')
                    ->select('id_users, lokasi_pelatihan')
                    ->whereIn('id_kelas', $kelasIds);
                if ($tempat !== 'all') {
                    $pB->like('lokasi_pelatihan', $tempat);
                }
                $pendaftar = $pB->get()->getResultArray();
                $totalPeserta = count($pendaftar);
                $siswaIds = array_unique(array_filter(array_column($pendaftar, 'id_users')));
                foreach ($pendaftar as $p) {
                    if (!empty($p['id_users'])) {
                        $studentLocationMap[$p['id_users']] = $p['lokasi_pelatihan'] ?? '';
                    }
                }
            }

            $nilaiRata = 0.0;
            $jumlahResponden = 0;
            $countPusat = 0;
            $countCabang = 0;
            $countPerwakilan = 0;

            if (!empty($siswaIds) && !empty($ratingQIds)) {
                $jQuery = $this->db->table('jawaban_angket')
                    ->whereIn('id_pertanyaan', $ratingQIds)
                    ->whereIn('id_siswa', $siswaIds)
                    ->where("YEAR(created_at)", $tahun);

                if (!empty($bulan) && $bulan !== 'all') {
                    $jQuery->where("MONTH(created_at)", $bulan);
                }

                $jawabanDb = $jQuery->get()->getResultArray();

                $totalScore = 0;
                $countScore = 0;
                $respondenUnik = [];

                foreach ($jawabanDb as $j) {
                    if (is_numeric($j['jawaban']) && (float)$j['jawaban'] > 0) {
                        $totalScore += (float)$j['jawaban'];
                        $countScore++;
                        $respondenUnik[$j['id_siswa']] = true;
                    }
                }

                $jumlahResponden = count($respondenUnik);
                if ($countScore > 0) {
                    $nilaiRata = round($totalScore / $countScore, 2);
                }

                foreach (array_keys($respondenUnik) as $sid) {
                    $loc = strtolower(trim($studentLocationMap[$sid] ?? ''));
                    if (str_contains($loc, 'pusat')) {
                        $countPusat++;
                    } elseif (str_contains($loc, 'cabang')) {
                        $countCabang++;
                    } elseif (str_contains($loc, 'perwakilan')) {
                        $countPerwakilan++;
                    } else {
                        $countPusat++;
                    }
                }
            }

            if ($jumlahResponden > 0 && $nilaiRata > 0) {
                $persenKepuasan = round(($nilaiRata / 4.0) * 100, 1);
                if ($persenKepuasan >= 90) {
                    $predikat = 'Sangat Baik';
                    $badgeClass = 'bg-success';
                } elseif ($persenKepuasan >= 80) {
                    $predikat = 'Baik';
                    $badgeClass = 'bg-primary';
                } elseif ($persenKepuasan >= 70) {
                    $predikat = 'Cukup';
                    $badgeClass = 'bg-warning text-dark';
                } else {
                    $predikat = 'Kurang';
                    $badgeClass = 'bg-danger';
                }
            } else {
                $nilaiRata = 0.00;
                $persenKepuasan = 0.0;
                $predikat = 'Belum Ada Penilaian';
                $badgeClass = 'bg-secondary';
            }

            $namaKelasUtama = !empty($kelasList[0]['nama_kelas']) ? $kelasList[0]['nama_kelas'] : 'Kelas Pelatihan';
            $kelasStr = $namaKelasUtama . (count($kelasList) > 1 ? ' (+' . (count($kelasList) - 1) . ')' : '');
            $pelatihanStr = !empty($kelasList[0]['kategori']) ? $kelasList[0]['kategori'] : (!empty($mentor['keahlian']) ? $mentor['keahlian'] : 'Pelatihan Kejuruan');
            $tempatPelatihanStr = !empty($kelasList[0]['lokasi_pelatihan']) ? $kelasList[0]['lokasi_pelatihan'] : 'Kantor Pusat';

            $reportList[] = [
                'id_mentor'            => $idMentor,
                'nip'                  => $mentor['nip'] ?? '-',
                'nama_mentor'          => $mentor['nama_mentor'],
                'keahlian'             => $mentor['keahlian'] ?: 'Instruktur Ahli',
                'pelatihan'            => $pelatihanStr,
                'kelas'                => $kelasStr,
                'kelas_ids'            => $kelasIds,
                'tempat_pelatihan'     => $tempatPelatihanStr,
                'responden_pusat'      => $countPusat,
                'responden_cabang'     => $countCabang,
                'responden_perwakilan' => $countPerwakilan,
                'total_peserta'        => $totalPeserta,
                'jumlah_responden'     => $jumlahResponden,
                'nilai_rata'           => $nilaiRata,
                'persen_kepuasan'      => $persenKepuasan,
                'predikat'             => $predikat,
                'badge_class'          => $badgeClass,
            ];
        }

        return $reportList;
    }

    /**
     * Hitung 4 Kartu Metrik Ringkasan Statistik
     */
    public function getRingkasanStats(array $filters): array
    {
        $list = $this->getLaporanAngketList($filters);
        $totalMentor = count($list);

        if ($totalMentor === 0) {
            return [
                'total_mentor_dinilai' => 0,
                'total_responden'      => 0,
                'avg_nilai_angket'     => 0.0,
                'persen_kepuasan'      => 0.0,
            ];
        }

        $evaluated = array_filter($list, function($m) {
            return ($m['jumlah_responden'] ?? 0) > 0 && ($m['nilai_rata'] ?? 0) > 0;
        });
        $countEval = count($evaluated);
        $totalResponden = array_sum(array_column($list, 'jumlah_responden'));

        $avgNilai = $countEval > 0 ? round(array_sum(array_column($evaluated, 'nilai_rata')) / $countEval, 2) : 0.0;
        $avgKepuasan = $countEval > 0 ? round(array_sum(array_column($evaluated, 'persen_kepuasan')) / $countEval, 1) : 0.0;

        return [
            'total_mentor_dinilai' => $countEval,
            'total_responden'      => $totalResponden,
            'avg_nilai_angket'     => $avgNilai,
            'persen_kepuasan'      => $avgKepuasan,
        ];
    }

    /**
     * Ambil skor rata-rata per indikator pertanyaan angket dari database
     */
    public function getPenilaianPerIndikator(array $filters): array
    {
        $pertanyaanDB = $this->db->table('angket_pertanyaan')
            ->select('id_angket_pertanyaan, pertanyaan, kategori, tipe')
            ->where('status', 'Aktif')
            ->get()->getResultArray();

        $indikator = [];

        if (!empty($pertanyaanDB)) {
            $siswaFilter = [];
            $hasFilter = false;

            if (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') {
                $hasFilter = true;
                $mentorList = $this->getLaporanAngketList(['id_mentor' => (int)$filters['id_mentor']]);
                if (!empty($mentorList)) {
                    $kIds = $mentorList[0]['kelas_ids'] ?? [];
                    if (!empty($kIds)) {
                        $pBuilder = $this->db->table('pendaftaran')->select('id_users')->whereIn('id_kelas', $kIds);
                        if (!empty($filters['tempat_pelatihan']) && $filters['tempat_pelatihan'] !== 'all') {
                            $pBuilder->like('lokasi_pelatihan', $filters['tempat_pelatihan']);
                        }
                        $siswaM = $pBuilder->get()->getResultArray();
                        $siswaFilter = array_unique(array_filter(array_column($siswaM, 'id_users')));
                    }
                }
            } elseif (!empty($filters['tempat_pelatihan']) && $filters['tempat_pelatihan'] !== 'all') {
                $hasFilter = true;
                $pBuilder = $this->db->table('pendaftaran')->select('id_users')->like('lokasi_pelatihan', $filters['tempat_pelatihan']);
                $siswaM = $pBuilder->get()->getResultArray();
                $siswaFilter = array_unique(array_filter(array_column($siswaM, 'id_users')));
            }

            foreach ($pertanyaanDB as $p) {
                $label = (strlen($p['pertanyaan']) > 65) ? substr($p['pertanyaan'], 0, 62) . '...' : $p['pertanyaan'];

                $jB = $this->db->table('jawaban_angket')->where('id_pertanyaan', $p['id_angket_pertanyaan']);
                if ($hasFilter) {
                    if (!empty($siswaFilter)) {
                        $jB->whereIn('id_siswa', $siswaFilter);
                    } else {
                        $jB->where('id_siswa', -1); // Tidak ada siswa yang cocok
                    }
                }

                $tahun = !empty($filters['tahun']) ? (int)$filters['tahun'] : (int)date('Y');
                $jB->where("YEAR(created_at)", $tahun);

                $bulan = !empty($filters['bulan']) ? (int)$filters['bulan'] : (int)date('n');
                if (!empty($bulan) && $bulan !== 'all') {
                    $jB->where("MONTH(created_at)", $bulan);
                }

                $jawabanRaw = $jB->get()->getResultArray();

                $totalS = 0;
                $countS = 0;
                foreach ($jawabanRaw as $jr) {
                    if ($p['tipe'] === 'rating') {
                        if (is_numeric($jr['jawaban']) && (float)$jr['jawaban'] > 0) {
                            $totalS += (float)$jr['jawaban'];
                            $countS++;
                        }
                    } else {
                        if (trim($jr['jawaban']) !== '') {
                            $countS++;
                        }
                    }
                }

                $score = ($countS > 0 && $p['tipe'] === 'rating') ? round($totalS / $countS, 2) : 0;

                $indikator[] = [
                    'id'         => $p['id_angket_pertanyaan'],
                    'judul'      => $label,
                    'kategori'   => ucfirst($p['kategori']),
                    'tipe'       => $p['tipe'],
                    'count'      => $countS,
                    'nilai'      => $score,
                    'persentase' => $p['tipe'] === 'rating' ? round(($score / 4.0) * 100, 1) : null,
                ];
            }
        }

        return $indikator;
    }

    /**
     * Ambil data terstruktur untuk semua grafik laporan angket (Chart.js)
     */
    public function getChartData(array $filters): array
    {
        $indikatorList = $this->getPenilaianPerIndikator($filters);

        // 1. Grafik Nilai per Indikator (Bar Chart)
        $barLabels = [];
        $barData   = [];
        foreach ($indikatorList as $ind) {
            $shortLabel = (strlen($ind['judul']) > 28) ? substr($ind['judul'], 0, 25) . '...' : $ind['judul'];
            $barLabels[] = $shortLabel;
            $barData[]   = $ind['nilai'];
        }

        // 2. Grafik Tren Perkembangan Nilai Angket Bulanan (12 Bulan)
        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $tahun = !empty($filters['tahun']) ? (int)$filters['tahun'] : (int)date('Y');

        $ratingQ = $this->db->table('angket_pertanyaan')->select('id_angket_pertanyaan')->where('tipe', 'rating')->get()->getResultArray();
        if (!empty($ratingQ)) {
            $ratingQIds = array_column($ratingQ, 'id_angket_pertanyaan');

            $jawabanData = $this->db->table('jawaban_angket')
                ->select('MONTH(created_at) as bulan, jawaban')
                ->whereIn('id_pertanyaan', $ratingQIds)
                ->where("YEAR(created_at)", $tahun)
                ->get()->getResultArray();

            $monthlyTotals = array_fill(1, 12, ['sum' => 0, 'count' => 0]);
            foreach ($jawabanData as $j) {
                if (is_numeric($j['jawaban'])) {
                    $bln = (int)$j['bulan'];
                    if ($bln >= 1 && $bln <= 12) {
                        $monthlyTotals[$bln]['sum'] += (float)$j['jawaban'];
                        $monthlyTotals[$bln]['count']++;
                    }
                }
            }

            $trenBulanan = [];
            for ($i = 1; $i <= 12; $i++) {
                if ($monthlyTotals[$i]['count'] > 0) {
                    $avg = $monthlyTotals[$i]['sum'] / $monthlyTotals[$i]['count'];
                    $trenBulanan[] = round($avg, 2);
                } else {
                    $trenBulanan[] = null;
                }
            }
        } else {
            $trenBulanan = array_fill(0, 12, null);
        }

        // 3. Grafik Distribusi Tingkat Kepuasan Peserta (Donut Chart)
        $list = $this->getLaporanAngketList($filters);
        $sangatPuas = 0;
        $puas = 0;
        $cukup = 0;
        $kurang = 0;

        foreach ($list as $m) {
            if (($m['jumlah_responden'] ?? 0) > 0 && ($m['nilai_rata'] ?? 0) > 0) {
                if ($m['persen_kepuasan'] >= 90) {
                    $sangatPuas++;
                } elseif ($m['persen_kepuasan'] >= 80) {
                    $puas++;
                } elseif ($m['persen_kepuasan'] >= 70) {
                    $cukup++;
                } else {
                    $kurang++;
                }
            }
        }

        return [
            'chart_indikator' => [
                'labels' => $barLabels,
                'data'   => $barData,
            ],
            'chart_tren_bulanan' => [
                'labels' => $monthLabels,
                'data'   => $trenBulanan,
            ],
            'chart_kepuasan_donut' => [
                'labels' => ['Sangat Puas (≥90%)', 'Puas (80-89%)', 'Cukup (70-79%)', 'Kurang (<70%)'],
                'data'   => [$sangatPuas, $puas, $cukup, $kurang],
            ],
        ];
    }

    /**
     * Ambil ranking mentor berdasarkan nilai angket tertinggi
     */
    public function getRankingMentor(array $filters): array
    {
        $list = $this->getLaporanAngketList($filters);

        // Hanya sertakan instruktur yang telah memiliki evaluasi riil
        $list = array_values(array_filter($list, function ($m) {
            return ($m['jumlah_responden'] ?? 0) > 0 && ($m['nilai_rata'] ?? 0) > 0;
        }));

        usort($list, function ($a, $b) {
            if ($b['nilai_rata'] == $a['nilai_rata']) {
                return $b['jumlah_responden'] <=> $a['jumlah_responden'];
            }
            return $b['nilai_rata'] <=> $a['nilai_rata'];
        });
        return $list;
    }

    /**
     * Ambil komentar, ulasan, kritik, dan saran peserta
     */
    public function getDetailKomentar(?int $idMentor = null, array $filters = []): array
    {
        $comments = [];

        $builder = $this->db->table('jawaban_angket')
            ->select('jawaban_angket.jawaban as ulasan, jawaban_angket.created_at, jawaban_angket.id_siswa, pendaftaran.pilihan_kelas, pendaftaran.lokasi_pelatihan, users.nama as nama_peserta')
            ->join('angket_pertanyaan', 'angket_pertanyaan.id_angket_pertanyaan = jawaban_angket.id_pertanyaan', 'inner')
            ->join('users', 'users.id_users = jawaban_angket.id_siswa', 'left')
            ->join('pendaftaran', 'pendaftaran.id_users = jawaban_angket.id_siswa', 'left')
            ->where('angket_pertanyaan.tipe', 'essay')
            ->where('jawaban_angket.jawaban IS NOT NULL')
            ->where("TRIM(jawaban_angket.jawaban) != ''")
            ->where("TRIM(jawaban_angket.jawaban) != '-'")
            ->where("LOWER(TRIM(jawaban_angket.jawaban)) != 'tidak ada'")
            ->where("LOWER(TRIM(jawaban_angket.jawaban)) != 'tdak ada'")
            ->where("LOWER(TRIM(jawaban_angket.jawaban)) != 'tidak'")
            ->groupBy('jawaban_angket.id_jawaban');

        $tahun = !empty($filters['tahun']) ? (int)$filters['tahun'] : (int)date('Y');
        $builder->where("YEAR(jawaban_angket.created_at)", $tahun);

        $bulan = !empty($filters['bulan']) ? (int)$filters['bulan'] : (int)date('n');
        if (!empty($bulan) && $bulan !== 'all') {
            $builder->where("MONTH(jawaban_angket.created_at)", $bulan);
        }

        if ($idMentor !== null && $idMentor > 0) {
            $mentorList = $this->getLaporanAngketList(['id_mentor' => $idMentor]);
            if (!empty($mentorList)) {
                $kIds = $mentorList[0]['kelas_ids'] ?? [];
                if (!empty($kIds)) {
                    $builder->whereIn('pendaftaran.id_kelas', $kIds);
                } else {
                    $builder->where('pendaftaran.id_kelas', -1);
                }
            }
        }

        $rows = $builder->orderBy('jawaban_angket.created_at', 'DESC')->get()->getResultArray();

        foreach ($rows as $r) {
            $comments[] = [
                'nama_peserta' => $r['nama_peserta'] ?: 'Peserta Pelatihan',
                'nama_mentor'  => 'Instruktur',
                'nama_kelas'   => $r['pilihan_kelas'] ?: 'Kelas Pelatihan',
                'rating'       => 0,
                'ulasan'       => $r['ulasan'],
                'tanggal'      => !empty($r['created_at']) ? date('d M Y', strtotime($r['created_at'])) : date('d M Y'),
            ];
        }

        return $comments;
    }
}

