<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuIndukModel extends Model
{
    protected $table            = 'pendaftaran';
    protected $primaryKey       = 'id_pendaftaran';
    protected $allowedFields    = [
        'id_kelas',
        'id_users',
        'nama',
        'email',
        'no_hp',
        'alamat',
        'ttl',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'pas_foto',
        'status',
        'lokasi_pelatihan',
        'pilihan_pelatihan',
        'jenis_kelas',
        'metode_pembelajaran',
        'pilihan_kelas',
        'kategori_kelas',
        'tanggal_mulai_kelas',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'alasan_penolakan',
        'persetujuan_syarat',
        'nis',
        'status_pendaftaran',
        'updated_at',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Generate NIS otomatis berurutan per bulan dengan format: YYYYMMXXX
     * Contoh: 202609001, 202609002
     *
     * @param string|null $yearMonth Format YYYYMM, default bulan saat ini
     * @return string
     */
    public function generateNis(?string $yearMonth = null): string
    {
        if (empty($yearMonth) || strlen($yearMonth) !== 6) {
            $yearMonth = date('Ym');
        }

        // Cari NIS tertinggi yang diawali dengan $yearMonth
        $row = $this->db->table('pendaftaran')
            ->select('nis')
            ->where("nis LIKE '{$yearMonth}%'")
            ->where('LENGTH(nis) >=', 9)
            ->orderBy('nis', 'DESC')
            ->get()
            ->getRowArray();

        if ($row && !empty($row['nis'])) {
            $lastSequence = (int) substr($row['nis'], 6);
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $yearMonth . str_pad((string) $nextSequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Normalisasi / backfill semua data NIS yang kosong atau berformat lama
     * Menghasilkan urutan per bulan YYYYMMXXX tanpa duplikasi
     *
     * @return array Rekap hasil update
     */
    public function backfillNis(): array
    {
        $all = $this->db->table('pendaftaran')
            ->select('id_pendaftaran, created_at, nis')
            ->orderBy('created_at', 'ASC')
            ->orderBy('id_pendaftaran', 'ASC')
            ->get()
            ->getResultArray();

        $counterByMonth = [];
        $updated = [];

        foreach ($all as $item) {
            $createdAt = !empty($item['created_at']) ? $item['created_at'] : date('Y-m-d H:i:s');
            $ym = date('Ym', strtotime($createdAt));

            if (!isset($counterByMonth[$ym])) {
                $counterByMonth[$ym] = 1;
            } else {
                $counterByMonth[$ym]++;
            }

            $correctNis = $ym . str_pad((string) $counterByMonth[$ym], 3, '0', STR_PAD_LEFT);

            // Update jika NIS belum sesuai atau masih format lama
            if ($item['nis'] !== $correctNis) {
                $this->db->table('pendaftaran')
                    ->where('id_pendaftaran', $item['id_pendaftaran'])
                    ->update(['nis' => $correctNis]);

                $updated[] = [
                    'id_pendaftaran' => $item['id_pendaftaran'],
                    'old_nis'        => $item['nis'],
                    'new_nis'        => $correctNis,
                ];
            }
        }

        return [
            'total'   => count($all),
            'updated' => count($updated),
            'details' => $updated,
        ];
    }

    /**
     * Base query builder untuk Buku Induk
     */
    private function buildBaseQuery(array $filters = [])
    {
        $builder = $this->db->table('pendaftaran')
            ->select('
                pendaftaran.id_pendaftaran,
                pendaftaran.id_kelas,
                pendaftaran.id_users,
                COALESCE(NULLIF(pendaftaran.nis, ""), CONCAT("NIS-", pendaftaran.id_pendaftaran)) AS nis,
                COALESCE(NULLIF(pendaftaran.nama, ""), users.nama, "-") AS nama_peserta,
                COALESCE(pendaftaran.tanggal_mulai_kelas, DATE(pendaftaran.created_at)) AS tanggal_masuk,
                kelas.nama_kelas,
                COALESCE(
                    (SELECT MAX(j.tanggal_kbm) FROM jadwal j WHERE j.id_kelas = pendaftaran.id_kelas),
                    (SELECT MAX(DATE(jk.tanggal_kbm)) FROM jadwal_kelas jk WHERE jk.id_kelas = pendaftaran.id_kelas),
                    "-"
                ) AS tanggal_selesai_kelas,
                CASE 
                    WHEN (s.id_sertifikat IS NOT NULL OR nu.is_lulus = 1) THEN "Selesai"
                    ELSE "Menunggu"
                END AS status_sertifikat,
                COALESCE(pendaftaran.status_pendaftaran, pendaftaran.status, "Menunggu") AS diterima,
                COALESCE(NULLIF(pendaftaran.kategori_kelas, ""), kelas.kategori, "-") AS kategori_kelas,
                COALESCE(NULLIF(pendaftaran.pilihan_kelas, ""), kelas.nama_kelas, "-") AS pilihan_kelas,
                COALESCE(NULLIF(pendaftaran.metode_pembelajaran, ""), "Offline") AS metode,
                COALESCE(NULLIF(pendaftaran.jenis_kelas, ""), kelas.tipe_kelas, "Reguler") AS jenis_kelas,
                COALESCE(NULLIF(pendaftaran.lokasi_pelatihan, ""), kelas.lokasi_media, "-") AS lokasi_pelatihan,
                COALESCE(NULLIF(pendaftaran.pendidikan_terakhir, ""), "-") AS pendidikan_terakhir,
                COALESCE(NULLIF(pendaftaran.status, ""), "Aktif") AS status_peserta,
                COALESCE(NULLIF(pendaftaran.no_hp, ""), users.no_hp, "-") AS no_whatsapp,
                COALESCE(NULLIF(pendaftaran.jenis_kelamin, ""), users.jenis_kelamin, "-") AS jenis_kelamin,
                COALESCE(NULLIF(pendaftaran.ttl, ""), "-") AS tempat_tanggal_lahir,
                COALESCE(NULLIF(pendaftaran.alamat, ""), "-") AS alamat,
                pendaftaran.created_at,
                pendaftaran.status_pembayaran,
                s.nomor_sertifikat,
                s.file_sertifikat
            ')
            ->join('users', 'users.id_users = pendaftaran.id_users', 'left')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('sertifikat s', '(s.id_user = pendaftaran.id_users AND s.id_kelas = pendaftaran.id_kelas) OR s.id_peserta = pendaftaran.id_pendaftaran', 'left')
            ->join('(
                SELECT 
                    id_user, 
                    id_kelas,
                    MAX(CASE WHEN LOWER(status_kelulusan) = "lulus" OR nilai >= 70 THEN 1 ELSE 0 END) AS is_lulus
                FROM nilai_ujian
                GROUP BY id_user, id_kelas
            ) nu', 'nu.id_user = pendaftaran.id_users AND nu.id_kelas = pendaftaran.id_kelas', 'left');

        // Filter Tahun
        if (!empty($filters['tahun']) && $filters['tahun'] !== 'all') {
            $builder->where('YEAR(pendaftaran.created_at)', (int) $filters['tahun']);
        }

        // Filter Bulan
        if (!empty($filters['bulan']) && $filters['bulan'] !== 'all') {
            $builder->where('MONTH(pendaftaran.created_at)', (int) $filters['bulan']);
        }

        // Filter Kelas
        if (!empty($filters['id_kelas']) && $filters['id_kelas'] !== 'all') {
            $builder->where('pendaftaran.id_kelas', (int) $filters['id_kelas']);
        }

        // Filter Kategori Kelas
        if (!empty($filters['kategori']) && $filters['kategori'] !== 'all') {
            $builder->groupStart()
                ->where('kelas.kategori', $filters['kategori'])
                ->orWhere('pendaftaran.kategori_kelas', $filters['kategori'])
            ->groupEnd();
        }

        // Filter Status Sertifikat
        if (!empty($filters['status_sertifikat']) && $filters['status_sertifikat'] !== 'all') {
            if ($filters['status_sertifikat'] === 'Selesai') {
                $builder->groupStart()
                    ->where('s.id_sertifikat IS NOT NULL', null, false)
                    ->orWhere('nu.is_lulus', 1)
                ->groupEnd();
            } else {
                $builder->groupStart()
                    ->where('s.id_sertifikat IS NULL', null, false)
                    ->where('(nu.is_lulus IS NULL OR nu.is_lulus = 0)', null, false)
                ->groupEnd();
            }
        }

        // Filter Status Diterima / Validasi
        if (!empty($filters['status_diterima']) && $filters['status_diterima'] !== 'all') {
            $val = strtolower($filters['status_diterima']);
            if (in_array($val, ['disetujui', 'valid', 'approved'])) {
                $builder->groupStart()
                    ->where('LOWER(pendaftaran.status_pendaftaran)', 'disetujui')
                    ->orWhere('LOWER(pendaftaran.status)', 'disetujui')
                    ->orWhere('LOWER(pendaftaran.status_pembayaran)', 'valid')
                ->groupEnd();
            } elseif (in_array($val, ['menunggu', 'pending'])) {
                $builder->groupStart()
                    ->where('LOWER(pendaftaran.status_pendaftaran)', 'menunggu')
                    ->orWhere('LOWER(pendaftaran.status)', 'pending')
                    ->orWhere('LOWER(pendaftaran.status_pembayaran)', 'pending')
                ->groupEnd();
            } elseif (in_array($val, ['ditolak', 'rejected'])) {
                $builder->groupStart()
                    ->where('LOWER(pendaftaran.status_pendaftaran)', 'ditolak')
                    ->orWhere('LOWER(pendaftaran.status_pembayaran)', 'rejected')
                ->groupEnd();
            }
        }

        // Filter Status Peserta
        if (!empty($filters['status_peserta']) && $filters['status_peserta'] !== 'all') {
            $builder->where('LOWER(pendaftaran.status)', strtolower($filters['status_peserta']));
        }

        // Filter Keyword (Search NIS, Nama, WhatsApp, Alamat)
        if (!empty($filters['keyword'])) {
            $kw = trim($filters['keyword']);
            $builder->groupStart()
                ->like('pendaftaran.nis', $kw)
                ->orLike('pendaftaran.nama', $kw)
                ->orLike('users.nama', $kw)
                ->orLike('pendaftaran.no_hp', $kw)
                ->orLike('users.no_hp', $kw)
                ->orLike('pendaftaran.alamat', $kw)
                ->orLike('kelas.nama_kelas', $kw)
            ->groupEnd();
        }

        return $builder;
    }

    /**
     * Ambil data Buku Induk berpaginasi
     */
    public function getBukuIndukList(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $builder = $this->buildBaseQuery($filters);

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'nis';
        $sortOrder = strtoupper($filters['sort_order'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';

        switch ($sortBy) {
            case 'nama':
                $builder->orderBy('nama_peserta', $sortOrder);
                break;
            case 'tanggal_masuk':
                $builder->orderBy('pendaftaran.created_at', $sortOrder);
                break;
            case 'kelas':
                $builder->orderBy('kelas.nama_kelas', $sortOrder);
                break;
            case 'nis':
            default:
                $builder->orderBy('pendaftaran.nis', $sortOrder);
                $builder->orderBy('pendaftaran.id_pendaftaran', $sortOrder);
                break;
        }

        return $builder->limit($limit, $offset)->get()->getResultArray();
    }

    /**
     * Hitung total baris untuk pagination
     */
    public function countBukuInduk(array $filters = []): int
    {
        $builder = $this->buildBaseQuery($filters);
        return $builder->countAllResults();
    }

    /**
     * Ringkasan Statistik Buku Induk
     */
    public function getStatistics(array $filters = []): array
    {
        // Hitung dari base query tanpa pagination
        $list = $this->buildBaseQuery($filters)->get()->getResultArray();

        $totalPeserta = count($list);
        $sertifikatSelesai = 0;
        $sertifikatMenunggu = 0;
        $pesertaDisetujui = 0;
        $kelasCountArr = [];

        foreach ($list as $row) {
            if ($row['status_sertifikat'] === 'Selesai') {
                $sertifikatSelesai++;
            } else {
                $sertifikatMenunggu++;
            }

            if (in_array(strtolower($row['diterima']), ['disetujui', 'valid', 'approved'], true)) {
                $pesertaDisetujui++;
            }

            if (!empty($row['nama_kelas'])) {
                $kelasCountArr[$row['nama_kelas']] = true;
            }
        }

        return [
            'total_peserta'        => $totalPeserta,
            'sertifikat_selesai'   => $sertifikatSelesai,
            'sertifikat_menunggu'  => $sertifikatMenunggu,
            'peserta_disetujui'    => $pesertaDisetujui,
            'total_kelas'          => count($kelasCountArr),
        ];
    }

    /**
     * Ambil detail tunggal Buku Induk
     */
    public function getDetailBukuInduk(int $idPendaftaran): ?array
    {
        $list = $this->buildBaseQuery(['keyword' => ''])->where('pendaftaran.id_pendaftaran', $idPendaftaran)->get()->getResultArray();
        return !empty($list) ? $list[0] : null;
    }

    /**
     * Ambil opsi filter tahun
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
     * Ambil opsi filter kelas
     */
    public function getFilterClasses(): array
    {
        return $this->db->table('kelas')
            ->select('id_kelas, nama_kelas, kategori')
            ->orderBy('nama_kelas', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil opsi filter kategori
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
}
