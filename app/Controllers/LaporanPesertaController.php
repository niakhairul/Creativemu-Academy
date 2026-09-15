<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaporanPesertaModel;

class LaporanPesertaController extends BaseController
{
    protected $db;
    protected $laporanModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->laporanModel = new LaporanPesertaModel();
    }

    /**
     * Memeriksa hak akses user (hanya admin dan mentor)
     */
    private function checkAccess()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $role = strtolower((string) $session->get('role'));
        if ($role !== 'admin' && $role !== 'mentor') {
            return redirect()->to(base_url('pelatihan/dashboard'))->with('error', 'Akses ditolak. Fitur Laporan hanya dapat diakses oleh Admin atau Mentor.');
        }

        return null;
    }

    /**
     * Ambil data mentor login jika user adalah mentor
     */
    private function getMentorData(): ?array
    {
        $session = session();
        $userId = (int) $session->get('id_users');
        if (strtolower((string) $session->get('role')) !== 'mentor') {
            return null;
        }

        $mentor = null;
        if ($this->db->fieldExists('id_users', 'mentor')) {
            $mentor = $this->db->table('mentor')->where('id_users', $userId)->get()->getRowArray();
        }
        if (!$mentor && $this->db->fieldExists('id_user', 'mentor')) {
            $mentor = $this->db->table('mentor')->where('id_user', $userId)->get()->getRowArray();
        }

        return $mentor;
    }

    /**
     * Ekstrak parameter filter dari request GET
     */
    private function parseFilters(): array
    {
        $periode = $this->request->getGet('periode') ?: 'tahunan';
        if (!in_array($periode, ['tahunan', 'bulanan'])) {
            $periode = 'tahunan';
        }

        $availableYears = $this->laporanModel->getFilterYears();
        $defaultYear = !empty($availableYears) ? (int) $availableYears[0] : (int) date('Y');

        $tahun = $this->request->getGet('tahun');
        $tahun = (!empty($tahun) && is_numeric($tahun)) ? (int) $tahun : $defaultYear;

        $bulan = $this->request->getGet('bulan');
        $bulan = (!empty($bulan) && is_numeric($bulan)) ? (int) $bulan : (int) date('n');

        $idKelas = $this->request->getGet('id_kelas') ?: 'all';
        $kategori = $this->request->getGet('kategori') ?: 'all';
        $statusKelulusan = $this->request->getGet('status_kelulusan') ?: 'all';
        $q = trim((string) ($this->request->getGet('q') ?: ''));

        $mentor = $this->getMentorData();
        $idMentor = null;
        // Jika mentor login, batasi data ke kelas miliknya jika diinginkan, atau beri akses kelasnya
        if ($mentor) {
            $idMentor = (int) $mentor['id_mentor'];
        }

        return [
            'periode'          => $periode,
            'tahun'            => $tahun,
            'bulan'            => $bulan,
            'id_kelas'         => $idKelas,
            'kategori'         => $kategori,
            'status_kelulusan' => $statusKelulusan,
            'q'                => $q,
            'id_mentor'        => $idMentor,
        ];
    }

    /**
     * Halaman Utama Laporan Peserta
     */
    public function index()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters = $this->parseFilters();
        $mentor = $this->getMentorData();
        $idMentor = $mentor ? (int) $mentor['id_mentor'] : null;

        $years      = $this->laporanModel->getFilterYears();
        $classes    = $this->laporanModel->getFilterClasses($idMentor);
        $categories = $this->laporanModel->getFilterCategories();

        $stats       = $this->laporanModel->getRingkasanStats($filters);
        $infoKelas   = $this->laporanModel->getInformasiPelatihan($filters);
        $rekapKelas  = $this->laporanModel->getRekapPerKelas($filters);
        $chartData   = $this->laporanModel->getChartData($filters);
        $detailList  = $this->laporanModel->getDetailPeserta($filters);

        $role = strtolower((string) session()->get('role'));

        $data = [
            'title'        => 'Laporan Data Peserta',
            'role'         => $role,
            'isMentor'     => ($role === 'mentor'),
            'mentor'       => $mentor,
            'filters'      => $filters,
            'years'        => $years,
            'classes'      => $classes,
            'categories'   => $categories,
            'stats'        => $stats,
            'infoKelas'    => $infoKelas,
            'rekapKelas'   => $rekapKelas,
            'chartData'    => $chartData,
            'detailList'   => $detailList,
            'totalDetail'  => count($detailList),
        ];

        return view('admin/laporan/laporan_peserta', $data);
    }

    /**
     * Export Laporan ke format Excel / CSV
     */
    public function exportExcel()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters    = $this->parseFilters();
        $stats      = $this->laporanModel->getRingkasanStats($filters);
        $rekapKelas = $this->laporanModel->getRekapPerKelas($filters);
        $detailList = $this->laporanModel->getDetailPeserta($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $filename = 'Laporan_Peserta_CreativeMU_' . str_replace(' ', '_', $periodeText) . '_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM agar Excel menampilkan huruf dan tanda baca dengan benar
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header Laporan
        fputcsv($output, ['CREATIVEMU ACADEMY - LAPORAN REKAPITULASI PESERTA PELATIHAN']);
        fputcsv($output, ['Periode Laporan', $periodeText]);
        fputcsv($output, ['Tanggal Ekspor', date('d-m-Y H:i:s')]);
        fputcsv($output, ['Diekspor Oleh', session()->get('nama') ?: session()->get('role')]);
        fputcsv($output, []); // Baris kosong

        // Ringkasan Statistik
        fputcsv($output, ['=== RINGKASAN STATISTIK ===']);
        fputcsv($output, ['Total Peserta', $stats['total_peserta']]);
        fputcsv($output, ['Total Kelas', $stats['total_kelas']]);
        fputcsv($output, ['Peserta Laki-laki', $stats['peserta_laki']]);
        fputcsv($output, ['Peserta Perempuan', $stats['peserta_perempuan']]);
        fputcsv($output, ['Peserta Lulus', $stats['peserta_lulus']]);
        fputcsv($output, ['Peserta Tidak Lulus', $stats['peserta_tidak_lulus']]);
        fputcsv($output, []); // Baris kosong

        // Rekapitulasi Per Kelas
        fputcsv($output, ['=== REKAPITULASI PER KELAS ===']);
        fputcsv($output, ['No', 'Nama Kelas', 'Kategori Pelatihan', 'Jumlah Peserta', 'Laki-laki', 'Perempuan', 'Lulus', 'Tidak Lulus']);
        $noK = 1;
        foreach ($rekapKelas as $rk) {
            fputcsv($output, [
                $noK++,
                $rk['nama_kelas'],
                $rk['kategori'],
                $rk['jumlah_peserta'],
                $rk['laki_laki'],
                $rk['perempuan'],
                $rk['lulus'],
                $rk['tidak_lulus']
            ]);
        }
        fputcsv($output, []); // Baris kosong

        // Detail Data Peserta
        fputcsv($output, ['=== DETAIL DATA PESERTA ===']);
        fputcsv($output, ['No', 'NIS / ID', 'Nama Peserta', 'Gender', 'Email', 'No. WhatsApp', 'Kelas', 'Kategori Pelatihan', 'Tanggal Daftar', 'Status Kelulusan']);
        $noP = 1;
        foreach ($detailList as $p) {
            fputcsv($output, [
                $noP++,
                $p['resolved_nis'],
                $p['nama_peserta'],
                $p['resolved_gender'],
                $p['resolved_email'] ?? '-',
                $p['resolved_no_hp'] ?? '-',
                $p['nama_kelas'] ?? '-',
                $p['kategori'] ?? '-',
                !empty($p['tanggal_daftar']) ? date('d-m-Y H:i', strtotime($p['tanggal_daftar'])) : '-',
                $p['status_kelulusan']
            ]);
        }

        fclose($output);
        exit();
    }

    /**
     * Tampilan Cetak / PDF Bersih
     */
    public function cetak()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters    = $this->parseFilters();
        $stats      = $this->laporanModel->getRingkasanStats($filters);
        $rekapKelas = $this->laporanModel->getRekapPerKelas($filters);
        $detailList = $this->laporanModel->getDetailPeserta($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $data = [
            'title'        => 'Cetak Laporan Peserta - CreativeMU Academy',
            'periodeText'  => $periodeText,
            'filters'      => $filters,
            'stats'        => $stats,
            'rekapKelas'   => $rekapKelas,
            'detailList'   => $detailList,
            'printedBy'    => session()->get('nama') ?: (session()->get('role') === 'admin' ? 'Administrator' : 'Mentor'),
            'printDate'    => date('d F Y, H:i'),
        ];

        return view('admin/laporan/cetak_laporan', $data);
    }
}
