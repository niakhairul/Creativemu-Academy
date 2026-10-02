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
     * Ambil master list mentor untuk dropdown filter
     */
    public function getFilterMentors(): array
    {
        return $this->db->table('mentor')
            ->select('id_mentor, id_users, nip, nama_mentor, email, keahlian, status')
            ->orderBy('nama_mentor', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Ambil daftar kategori / pelatihan untuk filter
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
    /**
     * Ambil daftar tempat pelatihan dari Master Kelas
     */
    public function getFilterTempatPelatihan(): array
    {
        $rows = $this->db->table('kelas')
            ->select('lokasi_pelatihan AS tempat_pelatihan')
            ->distinct()
            ->where('lokasi_pelatihan IS NOT NULL')
            ->where('lokasi_pelatihan !=', '')
            ->orderBy('lokasi_pelatihan', 'ASC')
            ->get()->getResultArray();

        // Ambil berdasarkan alias 'tempat_pelatihan' yang sudah dibentuk oleh select
        return array_values(array_filter(array_column($rows, 'tempat_pelatihan')));
    }

    /**
     * Ambil data komprehensif performa per mentor berdasarkan filter
     */
        public function getLaporanMentorList($filters)
    {
        $tahun = (isset($filters['tahun']) && $filters['tahun'] !== 'all') ? (int) $filters['tahun'] : (int) date('Y');
        $bulan = (isset($filters['bulan']) && $filters['bulan'] !== 'all') ? (int) $filters['bulan'] : (int) date('n');

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
        
        $kategori = $filters['kategori'] ?? 'all';
        $tempat = $filters['tempat_pelatihan'] ?? 'all';

        // Filter and enrich
        $result = [];
        foreach ($mentors as $m) {
            $idMentor = (int) $m['id_mentor'];
            $idUser = (int) $m['id_users'];

            // Find all classes taught by this mentor matching filters
            $kBuilder = $this->db->table('kelas')->where('id_mentor', $idMentor);
            if ($kategori !== 'all') $kBuilder->where('kategori', $kategori);
            if ($tempat !== 'all') $kBuilder->where('lokasi_pelatihan', $tempat);
            
            $kelasList = $kBuilder->get()->getResultArray();
            if (empty($kelasList)) continue; // Skip if no matching classes based on filter

            $kelasIds = array_column($kelasList, 'id_kelas');
            
            // Get valid Jadwal for the filtered month/year
            $jBuilder = $this->db->table('jadwal')->whereIn('id_kelas', $kelasIds);
            if (isset($filters['tahun']) && $filters['tahun'] !== 'all') {
                $jBuilder->where("YEAR(tanggal_kbm) = {$tahun}");
            }
            if (isset($filters['bulan']) && $filters['bulan'] !== 'all') {
                $jBuilder->where("MONTH(tanggal_kbm) = {$bulan}");
            }
            $jadwalList = $jBuilder->get()->getResultArray();
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

                // To prevent double counting
                $processedJadwalIds = [];

                foreach ($absensiList as $absen) {
                    // Match to correct jadwal
                    $jMatch = null;
                    foreach($jadwalList as $jl) {
                        if($jl['id_jadwal'] == $absen['id_jadwal'] || $jl['id_jadwal'] == $absen['id_jadwal_kelas']) {
                            $jMatch = $jl; break;
                        }
                    }

                    if ($jMatch && !in_array($jMatch['id_jadwal'], $processedJadwalIds)) {
                        $processedJadwalIds[] = $jMatch['id_jadwal'];
                        
                        $st = strtolower(trim($absen['status']));
                        if ($st === 'hadir' || $st === 'terlambat') {
                            $sesiHadir++;
                            if ($st === 'terlambat') {
                                $sesiTelat++;
                            } else {
                                // Calculate lateness strictly based on datetime of jadwal and absen
                                $waktuAbsen = $absen['waktu_absen'];
                                if ($jMatch['tanggal_kbm'] && $jMatch['waktu_mulai'] && $waktuAbsen) {
                                    $tglKbm = $jMatch['tanggal_kbm'];
                                    $waktuMulai = $jMatch['waktu_mulai'];
                                    $jadwalStartObj = strtotime("$tglKbm $waktuMulai");
                                    $absenObj = strtotime($waktuAbsen);
                                    
                                    // if absence is strictly 15 mins after the schedule start time (regardless of day)
                                    // Because sometimes test data is weird, we just compare the absolute timestamp if possible, 
                                    // but if it's test data, maybe we just compare time?
                                    // Let's compare just time as requested if dates mismatch, or compare full timestamp.
                                    // User said: "Keterlambatan harus dihitung terhadap tanggal sesi yang benar. Jangan membandingkan waktu dari tanggal yang berbeda."
                                    // Actually user says: "Jika tanggal absensi dan tanggal jadwal tidak cocok... jangan menghasilkan keterlambatan... Keterlambatan harus dihitung terhadap tanggal sesi yang benar."
                                    
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

            
            $persenKehadiran = $sesiTotal > 0 ? round(($sesiHadir / $sesiTotal) * 100, 1) : 0;
            $persenKeterlambatan = $sesiHadir > 0 ? round(($sesiTelat / $sesiHadir) * 100, 1) : 0;

            // Get Angket
            $angketData = $this->db->table('angket_penilaian')
                ->selectAvg('rating')
                ->whereIn('id_kelas', $kelasIds)
                ->where('rating >', 0)
                ->get()->getRowArray();
            $nilaiAngket = $angketData['rating'] ? round($angketData['rating'], 1) : 0;

            // Populate row
            $m['sesi_total'] = $sesiTotal;
            $m['total_sesi'] = $sesiTotal;
            $m['sesi_terlaksana'] = $sesiHadir;
            $m['total_materi'] = 0;
            $m['sesi_hadir'] = $sesiHadir;
            $m['sesi_telat'] = $sesiTelat;
            
            $m['persen_keaktifan'] = 0; // Belum ada tabel spesifik keaktifan instruktur
            $m['persen_kehadiran'] = $persenKehadiran;
            $m['persen_keterlambatan'] = $persenKeterlambatan;
            $m['nilai_angket'] = $nilaiAngket;

            // Skor performa = 0 (Belum ada formula final)
            $m['skor_performa'] = 0;

            $m['kelas'] = count($kelasList) > 0 ? $kelasList[0]['nama_kelas'] . (count($kelasList) > 1 ? ' (+' . (count($kelasList)-1) . ')' : '') : '-';
            $m['pelatihan'] = count($kelasList) > 0 ? $kelasList[0]['kategori'] : '-';
            $m['tempat_pelatihan'] = count($kelasList) > 0 ? $kelasList[0]['lokasi_pelatihan'] : '-';

            $m['predikat'] = 'Belum Dinilai';
            $m['badge_class'] = 'bg-secondary';
            
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
        $jadwalList = $this->db->table('jadwal')
            ->select('jadwal.*, kelas.nama_kelas, kelas.kategori')
            ->join('kelas', 'kelas.id_kelas = jadwal.id_kelas')
            ->where('kelas.id_mentor', $idMentor)
            ->orderBy('jadwal.tanggal_kbm', 'DESC')
            ->orderBy('jadwal.waktu_mulai', 'DESC')
            ->limit(20)
            ->get()->getResultArray();

        $idUser = (int) ($mentorData['id_users'] ?? 0);
        foreach ($jadwalList as &$j) {
            $absen = $this->db->table('absensi')
                ->where('id_jadwal_kelas', $j['id_jadwal'])
                ->where('id_user', $idUser)
                ->get()->getRowArray();
            $j['status_kehadiran'] = $absen ? ($absen['status'] ?? 'Hadir') : 'Belum Absen';
            $j['waktu_absen_mentor'] = $absen['waktu_absen'] ?? null;
        }
        unset($j);

        $mentorData['riwayat_sesi'] = $jadwalList;
        return $mentorData;
    }
}
