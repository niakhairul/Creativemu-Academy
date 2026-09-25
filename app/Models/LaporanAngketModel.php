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
     * Ambil master list mentor untuk dropdown
     */
    public function getFilterMentors(): array
    {
        return $this->db->table('mentor')
            ->select('id_mentor, id_users, nip, nama_mentor, email, keahlian, status')
            ->orderBy('nama_mentor', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil kelas dan kategori untuk dropdown filter
     */
    public function getFilterClasses(): array
    {
        return $this->db->table('kelas')
            ->select('kelas.id_kelas, kelas.nama_kelas, kelas.kategori, kelas.lokasi_media, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil daftar kategori pelatihan unik
     */
    public function getFilterCategories(): array
    {
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
        $mBuilder = $this->db->table('mentor')
            ->select('mentor.*, users.nama as nama_user, users.email as email_user')
            ->join('users', 'users.id_users = mentor.id_users', 'left');

        if (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') {
            $mBuilder->where('mentor.id_mentor', (int) $filters['id_mentor']);
        }

        $mentors = $mBuilder->orderBy('mentor.nama_mentor', 'ASC')->get()->getResultArray();

        $tahun = !empty($filters['tahun']) ? (int) $filters['tahun'] : (int) date('Y');
        $periode = $filters['periode'] ?? 'tahunan';
        $bulan = !empty($filters['bulan']) ? (int) $filters['bulan'] : (int) date('n');
        $kategori = $filters['kategori'] ?? 'all';
        $idKelas = $filters['id_kelas'] ?? 'all';
        $tempatPelatihan = $filters['tempat_pelatihan'] ?? 'all';

        $reportList = [];

        $matchedMaster1 = $this->getMatchedPendaftaranLocations(1);
        $matchedMaster2 = $this->getMatchedPendaftaranLocations(2);
        $matchedMaster3 = $this->getMatchedPendaftaranLocations(3);

        foreach ($mentors as $mentor) {
            $idMentor = (int) $mentor['id_mentor'];

            $kBuilder = $this->db->table('kelas')->where('id_mentor', $idMentor);
            if ($kategori !== 'all') {
                $kBuilder->where('kategori', $kategori);
            }
            if ($idKelas !== 'all') {
                $kBuilder->where('id_kelas', (int) $idKelas);
            }
            // Hapus filter lokasi dari kelas (karena salah)
            $kelasList = $kBuilder->get()->getResultArray();

            if (($kategori !== 'all' || $idKelas !== 'all') && empty($kelasList)) {
                continue;
            }

            $kelasIds = array_column($kelasList, 'id_kelas');
            $namaKelasArr = array_column($kelasList, 'nama_kelas');
            $kategoriArr = array_unique(array_filter(array_column($kelasList, 'kategori')));

            $totalPeserta = 0;
            if (!empty($kelasIds)) {
                $pBuilder = $this->db->table('pendaftaran')
                    ->whereIn('id_kelas', $kelasIds);
                // Kita TIDAK memfilter pendaftaran.created_at untuk menentukan peserta, karena yang difilter adalah jawaban_angket.created_at
                if ($tempatPelatihan !== 'all' && !empty($tempatPelatihan)) {
                    $matchedLokasi = $this->getMatchedPendaftaranLocations((int)$tempatPelatihan);
                    if (!empty($matchedLokasi)) {
                        $pBuilder->whereIn('lokasi_pelatihan', $matchedLokasi);
                    } else {
                        $pBuilder->where('lokasi_pelatihan', 'TIDAK_ADA_YANG_COCOK');
                    }
                }
                $totalPeserta = $pBuilder->countAllResults();
            }

            $nilaiRata = 0.0;
            $jumlahResponden = 0;
            $count_pusat = 0;
            $count_cabang = 0;
            $count_perwakilan = 0;

            if (!empty($kelasIds)) {
                $pB = $this->db->table('pendaftaran')->select('id_users, lokasi_pelatihan')->whereIn('id_kelas', $kelasIds);
                if ($tempatPelatihan !== 'all' && !empty($tempatPelatihan)) {
                    $matchedLokasi = $this->getMatchedPendaftaranLocations((int)$tempatPelatihan);
                    if (!empty($matchedLokasi)) {
                        $pB->whereIn('lokasi_pelatihan', $matchedLokasi);
                    } else {
                        $pB->where('lokasi_pelatihan', 'TIDAK_ADA_YANG_COCOK');
                    }
                }
                $pendaftar = $pB->get()->getResultArray();
                $siswaIds = array_filter(array_column($pendaftar, 'id_users'));

                $studentLocationMap = [];
                foreach($pendaftar as $p) {
                    $studentLocationMap[$p['id_users']] = $p['lokasi_pelatihan'];
                }

                if (!empty($siswaIds)) {
                    $ratingQuestions = $this->db->table('angket_pertanyaan')->select('id_angket_pertanyaan')->where('tipe', 'rating')->get()->getResultArray();
                    $ratingQIds = array_column($ratingQuestions, 'id_angket_pertanyaan');

                    if (!empty($ratingQIds)) {
                        $jawabanDbQuery = $this->db->table('jawaban_angket')
                            ->whereIn('id_pertanyaan', $ratingQIds)
                            ->whereIn('id_siswa', $siswaIds)
                            ->where("YEAR(created_at)", $tahun);

                        if ($periode === 'bulanan' && $bulan) {
                            $jawabanDbQuery->where("MONTH(created_at)", $bulan);
                        }

                        $jawabanDb = $jawabanDbQuery->get()->getResultArray();

                        $totalScore = 0;
                        $count = 0;
                        $respondenUnik = [];

                        foreach($jawabanDb as $j) {
                            if (is_numeric($j['jawaban'])) {
                                $totalScore += (float)$j['jawaban'];
                                $count++;
                                $respondenUnik[$j['id_siswa']] = true;
                            }
                        }

                        $jumlahResponden = count($respondenUnik);
                        if ($count > 0) {
                            $nilaiRata = round($totalScore / $count, 2);
                        }

                        foreach(array_keys($respondenUnik) as $sid) {
                            $loc = $studentLocationMap[$sid] ?? '';
                            $loc = strtoupper(trim($loc));
                            if (empty($loc)) continue;

                            if (in_array($loc, $matchedMaster1)) {
                                $count_pusat++;
                            } elseif (in_array($loc, $matchedMaster2)) {
                                $count_cabang++;
                            } elseif (in_array($loc, $matchedMaster3)) {
                                $count_perwakilan++;
                            }
                        }
                    }
                }
            }

            $persenKepuasan = ($nilaiRata / 4.0) * 100;

            if ($jumlahResponden > 0) {
                if ($persenKepuasan >= 90) {
                    $predikat = 'Sangat Baik';
                    $badgeClass = 'success';
                } elseif ($persenKepuasan >= 80) {
                    $predikat = 'Baik';
                    $badgeClass = 'primary';
                } elseif ($persenKepuasan >= 70) {
                    $predikat = 'Cukup';
                    $badgeClass = 'warning';
                } else {
                    $predikat = 'Kurang';
                    $badgeClass = 'danger';
                }
            } else {
                $predikat = '-';
                $badgeClass = 'secondary';
            }

            // JIKA TAHUN INI TIDAK ADA RESPONDEN SAMA SEKALI, LEWATI MENTOR INI DARI TABEL REKAP!
            if ($jumlahResponden === 0) {
                continue;
            }

            $reportList[] = [
                'id_mentor'         => $idMentor,
                'nip'               => $mentor['nip'] ?? '-',
                'nama_mentor'       => $mentor['nama_user'] ?? $mentor['nama_mentor'],
                'keahlian'          => $mentor['keahlian'] ?: 'Instruktur Ahli',
                'pelatihan'         => !empty($kategoriArr) ? implode(', ', $kategoriArr) : '-',
                'kelas'             => !empty($namaKelasArr) ? implode(', ', $namaKelasArr) : 'Belum Ada Kelas',
                'tempat_pelatihan'  => '-',
                'responden_pusat'   => $count_pusat,
                'responden_cabang'  => $count_cabang,
                'responden_perwakilan' => $count_perwakilan,
                'total_peserta'     => $totalPeserta,
                'jumlah_responden'  => $jumlahResponden,
                'nilai_rata'        => $nilaiRata,
                'persen_kepuasan'   => $persenKepuasan,
                'predikat'          => $predikat,
                'badge_class'       => $badgeClass,
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

        $totalResponden = array_sum(array_column($list, 'jumlah_responden'));
        $avgNilai       = array_sum(array_column($list, 'nilai_rata')) / $totalMentor;
        $avgKepuasan    = array_sum(array_column($list, 'persen_kepuasan')) / $totalMentor;

        return [
            'total_mentor_dinilai' => $totalMentor,
            'total_responden'      => $totalResponden,
            'avg_nilai_angket'     => round($avgNilai, 2),
            'persen_kepuasan'      => round($avgKepuasan, 1),
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
            if (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') {
                $kelasMentor = $this->db->table('kelas')->select('id_kelas')->where('id_mentor', (int)$filters['id_mentor'])->get()->getResultArray();
                $kelasIds = array_column($kelasMentor, 'id_kelas');
                if (!empty($kelasIds)) {
                    $pBuilder = $this->db->table('pendaftaran')->select('id_users')->whereIn('id_kelas', $kelasIds);
                    if (!empty($filters['tempat_pelatihan']) && $filters['tempat_pelatihan'] !== 'all') {
                        $matchedLokasi = $this->getMatchedPendaftaranLocations((int)$filters['tempat_pelatihan']);
                        if (!empty($matchedLokasi)) {
                            $pBuilder->whereIn('lokasi_pelatihan', $matchedLokasi);
                        } else {
                            $pBuilder->where('lokasi_pelatihan', 'TIDAK_ADA_YANG_COCOK');
                        }
                    }
                    $siswaM = $pBuilder->get()->getResultArray();
                    $siswaFilter = array_filter(array_column($siswaM, 'id_users'));
                }
            } else {
                if (!empty($filters['tempat_pelatihan']) && $filters['tempat_pelatihan'] !== 'all') {
                    $pBuilder = $this->db->table('pendaftaran')->select('id_users');
                    $matchedLokasi = $this->getMatchedPendaftaranLocations((int)$filters['tempat_pelatihan']);
                    if (!empty($matchedLokasi)) {
                        $pBuilder->whereIn('lokasi_pelatihan', $matchedLokasi);
                    } else {
                        $pBuilder->where('lokasi_pelatihan', 'TIDAK_ADA_YANG_COCOK');
                    }
                    $siswaM = $pBuilder->get()->getResultArray();
                    $siswaFilter = array_filter(array_column($siswaM, 'id_users'));
                }
            }

            foreach ($pertanyaanDB as $p) {
                $label = (strlen($p['pertanyaan']) > 65) ? substr($p['pertanyaan'], 0, 62) . '...' : $p['pertanyaan'];

                $jB = $this->db->table('jawaban_angket')->where('id_pertanyaan', $p['id_angket_pertanyaan']);
                if (!empty($siswaFilter)) {
                    $jB->whereIn('id_siswa', $siswaFilter);
                } elseif ((!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') || (!empty($filters['tempat_pelatihan']) && $filters['tempat_pelatihan'] !== 'all')) {
                    $jB->where('id_siswa', 0); // Pastikan tidak ada data yang diambil jika difilter
                }

                // Tambahkan filter tahun untuk indikator
                $tahun = !empty($filters['tahun']) ? (int)$filters['tahun'] : (int)date('Y');
                $jB->where("YEAR(created_at)", $tahun);

                $periode = $filters['periode'] ?? 'tahunan';
                $bulan = !empty($filters['bulan']) ? (int)$filters['bulan'] : (int)date('n');
                if ($periode === 'bulanan' && $bulan) {
                    $jB->where("MONTH(created_at)", $bulan);
                }

                $jawabanRaw = $jB->get()->getResultArray();

                $totalS = 0;
                $countS = 0;
                foreach($jawabanRaw as $jr) {
                    if ($p['tipe'] === 'rating') {
                        if (is_numeric($jr['jawaban'])) {
                            $totalS += (float)$jr['jawaban'];
                            $countS++;
                        }
                    } else {
                        if (trim($jr['jawaban']) !== '') {
                            $countS++;
                        }
                    }
                }

                $score = ($countS > 0 && $p['tipe'] === 'rating') ? ($totalS / $countS) : 0;

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

        // Removed fake donut data

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
            ->select('jawaban_angket.jawaban as ulasan, jawaban_angket.created_at, users.nama as nama_peserta, kelas.nama_kelas, mentor.nama_mentor, kelas.id_mentor')
            ->join('angket_pertanyaan', 'angket_pertanyaan.id_angket_pertanyaan = jawaban_angket.id_pertanyaan', 'inner')
            ->join('users', 'users.id_users = jawaban_angket.id_siswa', 'left')
            ->join('pendaftaran', 'pendaftaran.id_users = jawaban_angket.id_siswa', 'left')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
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

        $periode = $filters['periode'] ?? 'tahunan';
        $bulan = !empty($filters['bulan']) ? (int)$filters['bulan'] : (int)date('n');
        if ($periode === 'bulanan' && $bulan) {
            $builder->where("MONTH(jawaban_angket.created_at)", $bulan);
        }

        if ($idMentor !== null && $idMentor > 0) {
            $builder->where('kelas.id_mentor', $idMentor);
        }

        $rows = $builder->orderBy('jawaban_angket.created_at', 'DESC')->get()->getResultArray();

        foreach ($rows as $r) {
            $comments[] = [
                'nama_peserta' => $r['nama_peserta'] ?: 'Peserta Pelatihan',
                'nama_mentor'  => $r['nama_mentor'] ?: 'Instruktur',
                'nama_kelas'   => $r['nama_kelas'] ?: 'Kelas Pelatihan',
                'rating'       => 0,
                'ulasan'       => $r['ulasan'],
                'tanggal'      => !empty($r['created_at']) ? date('d M Y', strtotime($r['created_at'])) : date('d M Y'),
            ];
        }

        return $comments;
    }
}
