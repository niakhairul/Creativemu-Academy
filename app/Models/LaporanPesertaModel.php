<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanPesertaModel extends Model
{
    protected $table      = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';

    /**
     * Ambil daftar tahun pendaftaran yang tersedia di database
     */
    public function getFilterYears(): array
    {
        $rows = $this->db->table('pendaftaran')
            ->select('YEAR(created_at) as tahun', false)
            ->distinct()
            ->where('created_at IS NOT NULL')
            ->orderBy('tahun', 'DESC')
            ->get()
            ->getResultArray();

        $years = array_filter(array_column($rows, 'tahun'));
        $currentYear = (int) date('Y');

        if (!in_array($currentYear, $years)) {
            array_unshift($years, $currentYear);
        }

        return array_values(array_unique($years));
    }

    /**
     * Ambil daftar kelas untuk dropdown filter
     */
    public function getFilterClasses(?int $idMentor = null): array
    {
        $builder = $this->db->table('kelas')
            ->select('kelas.id_kelas, kelas.nama_kelas, kelas.kategori, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left');

        if ($idMentor !== null) {
            $builder->where('kelas.id_mentor', $idMentor);
        }

        return $builder->orderBy('kelas.nama_kelas', 'ASC')->get()->getResultArray();
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
            ->get()
            ->getResultArray();

        return array_values(array_filter(array_column($rows, 'kategori')));
    }

    /**
     * Bangun Query Builder dasar dengan filter aktif
     */
    private function buildFilteredBuilder(array $filters)
    {
        $builder = $this->db->table('pendaftaran')
            ->select("
                pendaftaran.id_pendaftaran,
                pendaftaran.id_kelas,
                pendaftaran.id_users,
                COALESCE(NULLIF(users.nama, ''), pendaftaran.nama) AS nama_peserta,
                COALESCE(NULLIF(pendaftaran.nis, ''), users.id_users, '-') AS resolved_nis,
                COALESCE(NULLIF(pendaftaran.jenis_kelamin, ''), users.jenis_kelamin, 'Laki-laki') AS resolved_gender,
                COALESCE(NULLIF(users.email, ''), pendaftaran.email) AS resolved_email,
                COALESCE(NULLIF(users.no_hp, ''), pendaftaran.no_hp) AS resolved_no_hp,
                pendaftaran.created_at AS tanggal_daftar,
                kelas.nama_kelas,
                kelas.kategori,
                kelas.tanggal_mulai_kelas,
                kelas.jumlah_pertemuan,
                kelas.kapasitas,
                kelas.status AS status_kelas,
                mentor.nama_mentor,
                CASE 
                    WHEN (s.id_sertifikat IS NOT NULL OR nu.is_lulus = 1) THEN 'Lulus'
                    WHEN nu.is_tidak_lulus = 1 THEN 'Tidak Lulus'
                    ELSE 'Dalam Proses'
                END AS status_kelulusan,
                nu.nilai_tertinggi
            ")
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->join('users', 'users.id_users = pendaftaran.id_users', 'left')
            ->join("(
                SELECT 
                    id_user, 
                    id_kelas,
                    MAX(CASE WHEN LOWER(status_kelulusan) = 'lulus' OR nilai >= 70 THEN 1 ELSE 0 END) AS is_lulus,
                    MAX(CASE WHEN LOWER(status_kelulusan) = 'belum_lulus' OR (nilai IS NOT NULL AND nilai < 70) THEN 1 ELSE 0 END) AS is_tidak_lulus,
                    MAX(nilai) AS nilai_tertinggi
                FROM nilai_ujian
                GROUP BY id_user, id_kelas
            ) nu", 'nu.id_user = pendaftaran.id_users AND nu.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('sertifikat s', 's.id_user = pendaftaran.id_users AND s.id_kelas = pendaftaran.id_kelas', 'left');

        // Filter Tahun
        $tahun = !empty($filters['tahun']) ? (int) $filters['tahun'] : (int) date('Y');
        $builder->where("YEAR(pendaftaran.created_at) = {$tahun}");

        // Filter Bulan (Jika jenis periode bulanan)
        if (!empty($filters['periode']) && $filters['periode'] === 'bulanan' && !empty($filters['bulan'])) {
            $bulan = (int) $filters['bulan'];
            $builder->where("MONTH(pendaftaran.created_at) = {$bulan}");
        }

        // Filter Kelas
        if (!empty($filters['id_kelas']) && $filters['id_kelas'] !== 'all') {
            $builder->where('pendaftaran.id_kelas', (int) $filters['id_kelas']);
        }

        // Filter Kategori / Pelatihan
        if (!empty($filters['kategori']) && $filters['kategori'] !== 'all') {
            $builder->where('kelas.kategori', $filters['kategori']);
        }

        // Filter Mentor jika akses mentor
        if (!empty($filters['id_mentor'])) {
            $builder->where('kelas.id_mentor', (int) $filters['id_mentor']);
        }

        // Filter Status Kelulusan
        if (!empty($filters['status_kelulusan']) && $filters['status_kelulusan'] !== 'all') {
            $statusKelulusan = strtolower($filters['status_kelulusan']);
            if ($statusKelulusan === 'lulus') {
                $builder->groupStart()
                    ->where('s.id_sertifikat IS NOT NULL')
                    ->orWhere('nu.is_lulus', 1)
                    ->groupEnd();
            } elseif ($statusKelulusan === 'tidak_lulus') {
                $builder->where('s.id_sertifikat IS NULL')
                    ->groupStart()
                    ->where('nu.is_lulus', 0)
                    ->orWhere('nu.is_lulus IS NULL')
                    ->groupEnd()
                    ->where('nu.is_tidak_lulus', 1);
            } elseif ($statusKelulusan === 'proses') {
                $builder->where('s.id_sertifikat IS NULL')
                    ->groupStart()
                    ->where('nu.is_lulus', 0)
                    ->orWhere('nu.is_lulus IS NULL')
                    ->groupEnd()
                    ->groupStart()
                    ->where('nu.is_tidak_lulus', 0)
                    ->orWhere('nu.is_tidak_lulus IS NULL')
                    ->groupEnd();
            }
        }

        // Pencarian teks (Search query)
        if (!empty($filters['q'])) {
            $q = trim($filters['q']);
            $builder->groupStart()
                ->like('pendaftaran.nama', $q)
                ->orLike('pendaftaran.nis', $q)
                ->orLike('pendaftaran.email', $q)
                ->orLike('pendaftaran.no_hp', $q)
                ->orLike('users.nama', $q)
                ->orLike('kelas.nama_kelas', $q)
                ->groupEnd();
        }

        return $builder;
    }

    /**
     * Hitung ringkasan statistik (6 Kartu Dashboard)
     */
    public function getRingkasanStats(array $filters): array
    {
        $allFilteredRows = $this->buildFilteredBuilder($filters)->get()->getResultArray();

        $totalPeserta      = count($allFilteredRows);
        $totalLaki         = 0;
        $totalPerempuan    = 0;
        $totalLulus        = 0;
        $totalTidakLulus   = 0;
        $kelasIds          = [];

        foreach ($allFilteredRows as $row) {
            $gender = strtolower(trim($row['resolved_gender'] ?? ''));
            if (str_contains($gender, 'perempuan') || str_contains($gender, 'wanita')) {
                $totalPerempuan++;
            } else {
                $totalLaki++;
            }

            $kelulusan = $row['status_kelulusan'] ?? 'Dalam Proses';
            if ($kelulusan === 'Lulus') {
                $totalLulus++;
            } elseif ($kelulusan === 'Tidak Lulus') {
                $totalTidakLulus++;
            }

            if (!empty($row['id_kelas'])) {
                $kelasIds[$row['id_kelas']] = true;
            }
        }

        // Hitung total kelas yang relevan
        $totalKelas = count($kelasIds);
        if ($totalKelas === 0) {
            // Jika tidak ada peserta, cek apakah ada kelas yang terdaftar sesuai filter kategori/mentor
            $kBuilder = $this->db->table('kelas');
            if (!empty($filters['id_kelas']) && $filters['id_kelas'] !== 'all') {
                $kBuilder->where('id_kelas', (int) $filters['id_kelas']);
            }
            if (!empty($filters['kategori']) && $filters['kategori'] !== 'all') {
                $kBuilder->where('kategori', $filters['kategori']);
            }
            if (!empty($filters['id_mentor'])) {
                $kBuilder->where('id_mentor', (int) $filters['id_mentor']);
            }
            $totalKelas = $kBuilder->countAllResults();
        }

        return [
            'total_peserta'      => $totalPeserta,
            'total_kelas'        => $totalKelas,
            'peserta_laki'       => $totalLaki,
            'peserta_perempuan'  => $totalPerempuan,
            'peserta_lulus'      => $totalLulus,
            'peserta_tidak_lulus'=> $totalTidakLulus,
        ];
    }

    /**
     * Ambil informasi ringkas pelatihan / kelas
     */
    public function getInformasiPelatihan(array $filters): array
    {
        $kBuilder = $this->db->table('kelas')
            ->select('kelas.*, mentor.nama_mentor')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left');

        if (!empty($filters['id_kelas']) && $filters['id_kelas'] !== 'all') {
            $kBuilder->where('kelas.id_kelas', (int) $filters['id_kelas']);
        }
        if (!empty($filters['kategori']) && $filters['kategori'] !== 'all') {
            $kBuilder->where('kelas.kategori', $filters['kategori']);
        }
        if (!empty($filters['id_mentor'])) {
            $kBuilder->where('kelas.id_mentor', (int) $filters['id_mentor']);
        }

        $kelasList = $kBuilder->orderBy('kelas.nama_kelas', 'ASC')->get()->getResultArray();

        // Hitung jumlah peserta terfilter untuk setiap kelas
        $tahun = !empty($filters['tahun']) ? (int) $filters['tahun'] : (int) date('Y');
        $periode = $filters['periode'] ?? 'tahunan';
        $bulan = !empty($filters['bulan']) ? (int) $filters['bulan'] : null;

        foreach ($kelasList as &$k) {
            $pBuilder = $this->db->table('pendaftaran')
                ->where('id_kelas', $k['id_kelas'])
                ->where("YEAR(created_at) = {$tahun}");

            if ($periode === 'bulanan' && $bulan !== null) {
                $pBuilder->where("MONTH(created_at) = {$bulan}");
            }

            $k['jumlah_peserta_filter'] = $pBuilder->countAllResults();
        }
        unset($k);

        return $kelasList;
    }

    /**
     * Rekapitulasi per kelas untuk tabel ringkasan
     */
    public function getRekapPerKelas(array $filters): array
    {
        $allFilteredRows = $this->buildFilteredBuilder($filters)->get()->getResultArray();
        $rekap = [];

        foreach ($allFilteredRows as $row) {
            $idKelas = (int) ($row['id_kelas'] ?? 0);
            $namaKelas = $row['nama_kelas'] ?? 'Tanpa Kelas';
            $kategori  = $row['kategori'] ?? '-';

            if (!isset($rekap[$idKelas])) {
                $rekap[$idKelas] = [
                    'id_kelas'        => $idKelas,
                    'nama_kelas'      => $namaKelas,
                    'kategori'        => $kategori,
                    'jumlah_peserta'  => 0,
                    'laki_laki'       => 0,
                    'perempuan'       => 0,
                    'lulus'           => 0,
                    'tidak_lulus'     => 0,
                    'dalam_proses'    => 0,
                ];
            }

            $rekap[$idKelas]['jumlah_peserta']++;

            $gender = strtolower(trim($row['resolved_gender'] ?? ''));
            if (str_contains($gender, 'perempuan') || str_contains($gender, 'wanita')) {
                $rekap[$idKelas]['perempuan']++;
            } else {
                $rekap[$idKelas]['laki_laki']++;
            }

            $statusK = $row['status_kelulusan'] ?? 'Dalam Proses';
            if ($statusK === 'Lulus') {
                $rekap[$idKelas]['lulus']++;
            } elseif ($statusK === 'Tidak Lulus') {
                $rekap[$idKelas]['tidak_lulus']++;
            } else {
                $rekap[$idKelas]['dalam_proses']++;
            }
        }

        return array_values($rekap);
    }

    /**
     * Ambil data terstruktur untuk 4 Grafik Chart.js
     */
    public function getChartData(array $filters): array
    {
        $allFilteredRows = $this->buildFilteredBuilder($filters)->get()->getResultArray();

        // 1. Grafik Batang: Peserta Per Kelas
        $kelasCount = [];
        foreach ($allFilteredRows as $row) {
            $namaKelas = $row['nama_kelas'] ?: 'Kelas Lainnya';
            $kelasCount[$namaKelas] = ($kelasCount[$namaKelas] ?? 0) + 1;
        }

        $barLabels = array_keys($kelasCount);
        $barValues = array_values($kelasCount);

        // Jika kosong, sediakan label kelas terdaftar agar grafik tidak kosong melompong
        if (empty($barLabels)) {
            $sampleKelas = $this->getFilterClasses($filters['id_mentor'] ?? null);
            foreach ($sampleKelas as $sk) {
                $barLabels[] = $sk['nama_kelas'];
                $barValues[] = 0;
            }
        }

        // 2. Grafik Donat: Gender Peserta
        $lakiCount = 0;
        $perempuanCount = 0;
        foreach ($allFilteredRows as $row) {
            $gender = strtolower(trim($row['resolved_gender'] ?? ''));
            if (str_contains($gender, 'perempuan') || str_contains($gender, 'wanita')) {
                $perempuanCount++;
            } else {
                $lakiCount++;
            }
        }

        // 3. Grafik Donat: Status Kelulusan
        $lulusCount = 0;
        $tidakLulusCount = 0;
        $prosesCount = 0;
        foreach ($allFilteredRows as $row) {
            $st = $row['status_kelulusan'] ?? 'Dalam Proses';
            if ($st === 'Lulus') {
                $lulusCount++;
            } elseif ($st === 'Tidak Lulus') {
                $tidakLulusCount++;
            } else {
                $prosesCount++;
            }
        }

        // 4. Grafik Garis: Tren Perkembangan Peserta
        $tahun = !empty($filters['tahun']) ? (int) $filters['tahun'] : (int) date('Y');
        $isBulanan = (!empty($filters['periode']) && $filters['periode'] === 'bulanan');

        if ($isBulanan && !empty($filters['bulan'])) {
            $bulan = (int) $filters['bulan'];
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
            
            // Kelompokkan per minggu agar rapi
            $lineLabels = ['Mg 1 (Tgl 1-7)', 'Mg 2 (Tgl 8-14)', 'Mg 3 (Tgl 15-21)', 'Mg 4 (Tgl 22-28)', 'Mg 5 (Tgl 29-' . $daysInMonth . ')'];
            $lineValues = [0, 0, 0, 0, 0];

            foreach ($allFilteredRows as $row) {
                if (!empty($row['tanggal_daftar'])) {
                    $day = (int) date('j', strtotime($row['tanggal_daftar']));
                    if ($day <= 7) {
                        $lineValues[0]++;
                    } elseif ($day <= 14) {
                        $lineValues[1]++;
                    } elseif ($day <= 21) {
                        $lineValues[2]++;
                    } elseif ($day <= 28) {
                        $lineValues[3]++;
                    } else {
                        $lineValues[4]++;
                    }
                }
            }
        } else {
            // Tahunan: 12 Bulan
            $lineLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $lineValues = array_fill(0, 12, 0);

            foreach ($allFilteredRows as $row) {
                if (!empty($row['tanggal_daftar'])) {
                    $m = (int) date('n', strtotime($row['tanggal_daftar']));
                    if ($m >= 1 && $m <= 12) {
                        $lineValues[$m - 1]++;
                    }
                }
            }
        }

        return [
            'bar_kelas' => [
                'labels' => $barLabels,
                'data'   => $barValues,
            ],
            'donut_gender' => [
                'labels' => ['Laki-laki', 'Perempuan'],
                'data'   => [$lakiCount, $perempuanCount],
            ],
            'donut_kelulusan' => [
                'labels' => ['Lulus', 'Tidak Lulus', 'Dalam Proses'],
                'data'   => [$lulusCount, $tidakLulusCount, $prosesCount],
            ],
            'line_tren' => [
                'labels' => $lineLabels,
                'data'   => $lineValues,
                'mode'   => $isBulanan ? 'bulanan' : 'tahunan',
            ],
        ];
    }

    /**
     * Ambil data detail peserta lengkap untuk tabel
     */
    public function getDetailPeserta(array $filters): array
    {
        return $this->buildFilteredBuilder($filters)
            ->orderBy('pendaftaran.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
