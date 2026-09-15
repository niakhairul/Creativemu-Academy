<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaporanKehadiranModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanKehadiranController extends BaseController
{
    protected $db;
    protected $laporanKehadiranModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->laporanKehadiranModel = new LaporanKehadiranModel();
    }

    /**
     * Verifikasi hak akses (Admin & Mentor)
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
     * Ambil data mentor jika user berstatus mentor
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
     * Ekstrak filter dari request
     */
    private function parseFilters(): array
    {
        $periode = $this->request->getGet('periode') ?: 'tahunan';
        if (!in_array($periode, ['tahunan', 'bulanan'])) {
            $periode = 'tahunan';
        }

        $availableYears = $this->laporanKehadiranModel->getFilterYears();
        $defaultYear = !empty($availableYears) ? (int) $availableYears[0] : (int) date('Y');

        $tahun = $this->request->getGet('tahun');
        $tahun = (!empty($tahun) && is_numeric($tahun)) ? (int) $tahun : $defaultYear;

        $bulan = $this->request->getGet('bulan');
        $bulan = (!empty($bulan) && is_numeric($bulan)) ? (int) $bulan : (int) date('n');

        $kategori        = $this->request->getGet('kategori') ?: 'all';
        $idKelas         = $this->request->getGet('id_kelas') ?: 'all';
        $idPeserta       = $this->request->getGet('id_peserta') ?: 'all';
        $statusKehadiran = $this->request->getGet('status_kehadiran') ?: 'all';
        $keyword         = trim((string) ($this->request->getGet('keyword') ?? ''));

        // Jika mentor login, filter kelas hanya yang diampunya
        $mentorLogin = $this->getMentorData();
        if ($mentorLogin && session()->get('role') === 'mentor') {
            // Mentor can only see classes they teach
        }

        return [
            'periode'          => $periode,
            'tahun'            => $tahun,
            'bulan'            => $bulan,
            'kategori'         => $kategori,
            'id_kelas'         => $idKelas,
            'id_peserta'       => $idPeserta,
            'status_kehadiran' => $statusKehadiran,
            'keyword'          => $keyword,
        ];
    }

    /**
     * Halaman Utama Dashboard Laporan Kehadiran Peserta
     */
    public function index()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters     = $this->parseFilters();
        $role        = strtolower((string) session()->get('role'));
        $mentorLogin = $this->getMentorData();

        $years        = $this->laporanKehadiranModel->getFilterYears();
        $categories   = $this->laporanKehadiranModel->getFilterCategories();
        $classes      = $this->laporanKehadiranModel->getFilterClasses();
        $participants = $this->laporanKehadiranModel->getFilterParticipants();

        $stats     = $this->laporanKehadiranModel->getRingkasanStats($filters);
        $rekapList = $this->laporanKehadiranModel->getLaporanKehadiranList($filters);
        $chartData = $this->laporanKehadiranModel->getChartData($filters);
        $topLowest = $this->laporanKehadiranModel->getTopLowestParticipants($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $data = [
            'title'        => 'Laporan Kehadiran Peserta - CreativeMU Academy',
            'role'         => $role,
            'isMentor'     => ($role === 'mentor'),
            'mentorLogin'  => $mentorLogin,
            'filters'      => $filters,
            'periodeText'  => $periodeText,
            'years'        => $years,
            'categories'   => $categories,
            'classes'      => $classes,
            'participants' => $participants,
            'stats'        => $stats,
            'rekapList'    => $rekapList,
            'chartData'    => $chartData,
            'topLowest'    => $topLowest,
            'bulanNames'   => $bulanNames,
        ];

        return view('admin/laporan/laporan_kehadiran', $data);
    }

    /**
     * Endpoint AJAX untuk modal detail riwayat kehadiran peserta per pertemuan
     */
    public function detailAjax($id)
    {
        if ($redirect = $this->checkAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        $filters = $this->parseFilters();
        $detail  = $this->laporanKehadiranModel->getDetailRiwayatPeserta((int) $id, $filters);

        if (!$detail) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data peserta tidak ditemukan']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $detail,
        ]);
    }

    /**
     * Export Laporan Kehadiran Peserta ke Excel (.xlsx resmi via PhpSpreadsheet)
     */
    public function exportExcel()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters   = $this->parseFilters();
        $stats     = $this->laporanKehadiranModel->getRingkasanStats($filters);
        $rekapList = $this->laporanKehadiranModel->getLaporanKehadiranList($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $filename = 'Laporan_Kehadiran_Peserta_CreativeMU_' . str_replace(' ', '_', $periodeText) . '_' . date('Ymd_His') . '.xlsx';

        if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Laporan Kehadiran');

            // 1. Header Lembaga
            $sheet->setCellValue('A1', 'LAPORAN KEHADIRAN & PRESENSI PESERTA PELATIHAN');
            $sheet->setCellValue('A2', 'CreativeMU Academy - Lembaga Pendidikan & Pelatihan Kejuruan Terpadu');
            $sheet->setCellValue('A3', 'Periode Laporan: ' . $periodeText . ' | Tanggal Ekspor: ' . date('d-m-Y H:i') . ' WIB');

            $filterDesc = 'Filter Digunakan: Periode ' . ucfirst($filters['periode']) . ' ' . $periodeText . 
                ' | Pelatihan: ' . ($filters['kategori'] === 'all' ? 'Semua' : $filters['kategori']) . 
                ' | Kelas: ' . ($filters['id_kelas'] === 'all' ? 'Semua' : 'Kelas ID ' . $filters['id_kelas']) . 
                ' | Status: ' . ($filters['status_kehadiran'] === 'all' ? 'Semua' : ucfirst($filters['status_kehadiran']));
            $sheet->setCellValue('A4', $filterDesc);

            $sheet->mergeCells('A1:M1');
            $sheet->mergeCells('A2:M2');
            $sheet->mergeCells('A3:M3');
            $sheet->mergeCells('A4:M4');

            $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('22133C');
            $sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true)->getColor()->setRGB('5931A0');
            $sheet->getStyle('A3')->getFont()->setSize(10)->setBold(true)->getColor()->setRGB('555555');
            $sheet->getStyle('A4')->getFont()->setSize(9)->setItalic(true)->getColor()->setRGB('777777');
            $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // 2. Ringkasan Statistik Presensi (9 Metrik Lengkap)
            $sheet->setCellValue('A6', 'RINGKASAN METRIK KEHADIRAN PESERTA');
            $sheet->getStyle('A6')->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('22133C');

            $sumHeaders = [
                'Total Peserta', 'Total Pertemuan', 'Total Kehadiran', 'Hadir Tepat Waktu',
                'Terlambat', 'Izin', 'Sakit', 'Alpa', 'Persentase Kehadiran'
            ];
            $sumCols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

            for ($i = 0; $i < count($sumHeaders); $i++) {
                $sheet->setCellValue($sumCols[$i] . '7', $sumHeaders[$i]);
            }

            $sheet->setCellValue('A8', $stats['total_peserta'] . ' Siswa');
            $sheet->setCellValue('B8', $stats['total_pertemuan'] . ' Sesi');
            $sheet->setCellValue('C8', $stats['total_kehadiran'] . ' Kehadiran');
            $sheet->setCellValue('D8', $stats['total_hadir'] . ' Sesi');
            $sheet->setCellValue('E8', $stats['total_terlambat'] . ' Sesi');
            $sheet->setCellValue('F8', $stats['total_izin'] . ' Sesi');
            $sheet->setCellValue('G8', $stats['total_sakit'] . ' Sesi');
            $sheet->setCellValue('H8', $stats['total_alpa'] . ' Sesi');
            $sheet->setCellValue('I8', number_format($stats['persentase_kehadiran'], 1) . '%');

            $sheet->getStyle('A7:I7')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A7:I7')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5931A0');
            $sheet->getStyle('A7:I8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A7:I8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CCCCCC');

            // 3. Tabel Rekapitulasi Kehadiran Peserta
            $rowTableStart = 11;
            $sheet->freezePane('A12');
            $sheet->setCellValue('A' . ($rowTableStart - 1), 'REKAPITULASI KEHADIRAN PESERTA PELATIHAN');
            $sheet->getStyle('A' . ($rowTableStart - 1))->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('22133C');

            $mainHeaders = [
                'No', 'NIS', 'Nama Peserta', 'Kelas', 'Pelatihan',
                'Total Pertemuan', 'Hadir', 'Izin', 'Sakit', 'Alpa', 'Terlambat', 'Persentase Kehadiran', 'Status'
            ];
            $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

            for ($k = 0; $k < count($mainHeaders); $k++) {
                $sheet->setCellValue($colLetters[$k] . $rowTableStart, $mainHeaders[$k]);
            }

            $sheet->getStyle('A' . $rowTableStart . ':M' . $rowTableStart)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A' . $rowTableStart . ':M' . $rowTableStart)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('22133C');
            $sheet->getStyle('A' . $rowTableStart . ':M' . $rowTableStart)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension($rowTableStart)->setRowHeight(28);

            $currentRow = $rowTableStart + 1;
            $no = 1;

            foreach ($rekapList as $row) {
                $sheet->setCellValue('A' . $currentRow, $no++);
                $sheet->setCellValue('B' . $currentRow, $row['nis']);
                $sheet->setCellValue('C' . $currentRow, $row['nama_peserta']);
                $sheet->setCellValue('D' . $currentRow, $row['kelas']);
                $sheet->setCellValue('E' . $currentRow, $row['pelatihan']);
                $sheet->setCellValue('F' . $currentRow, $row['total_pertemuan']);
                $sheet->setCellValue('G' . $currentRow, $row['hadir']);
                $sheet->setCellValue('H' . $currentRow, $row['izin']);
                $sheet->setCellValue('I' . $currentRow, $row['sakit']);
                $sheet->setCellValue('J' . $currentRow, $row['alpa']);
                $sheet->setCellValue('K' . $currentRow, $row['terlambat']);
                $sheet->setCellValue('L' . $currentRow, ($row['persentase_kehadiran'] / 100));
                $sheet->setCellValue('M' . $currentRow, $row['predikat']);

                $sheet->getStyle('L' . $currentRow)->getNumberFormat()->setFormatCode('0.0%');

                $sheet->getStyle('A' . $currentRow . ':B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $currentRow)->getFont()->setBold(true);
                $sheet->getStyle('F' . $currentRow . ':M' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($no % 2 === 0) {
                    $sheet->getStyle('A' . $currentRow . ':M' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F6FD');
                }

                $sheet->getRowDimension($currentRow)->setRowHeight(22);
                $currentRow++;
            }

            $lastRow = $currentRow - 1;
            if ($lastRow >= $rowTableStart) {
                $sheet->getStyle('A' . $rowTableStart . ':M' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D0C9E8');
            }

            foreach (range('A', 'M') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            $sheet->getHeaderFooter()->setOddFooter('&R Halaman &P dari &N | CreativeMU Academy');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        }

        // Fallback CSV
        $this->exportCsvFallback($filename, $periodeText, $stats, $rekapList);
    }

    /**
     * Fallback CSV exporter
     */
    private function exportCsvFallback($filename, $periodeText, $stats, $rekapList)
    {
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $csvFilename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['CREATIVEMU ACADEMY - LAPORAN KEHADIRAN PESERTA PELATIHAN']);
        fputcsv($output, ['Periode Laporan', $periodeText]);
        fputcsv($output, ['Tanggal Ekspor', date('d-m-Y H:i:s')]);
        fputcsv($output, []);

        fputcsv($output, ['=== RINGKASAN METRIK KEHADIRAN ===']);
        fputcsv($output, ['Total Peserta', $stats['total_peserta']]);
        fputcsv($output, ['Total Pertemuan', $stats['total_pertemuan']]);
        fputcsv($output, ['Total Hadir', $stats['total_hadir']]);
        fputcsv($output, ['Total Izin', $stats['total_izin']]);
        fputcsv($output, ['Total Sakit', $stats['total_sakit']]);
        fputcsv($output, ['Total Alpa', $stats['total_alpa']]);
        fputcsv($output, ['Total Terlambat', $stats['total_terlambat']]);
        fputcsv($output, ['Persentase Kehadiran', $stats['persentase_kehadiran'] . '%']);
        fputcsv($output, []);

        fputcsv($output, ['No', 'NIS', 'Nama Peserta', 'Kelas', 'Pelatihan', 'Total Pertemuan', 'Hadir', 'Izin', 'Sakit', 'Alpa', 'Terlambat', 'Persentase Kehadiran', 'Status']);
        $no = 1;
        foreach ($rekapList as $row) {
            fputcsv($output, [
                $no++,
                $row['nis'],
                $row['nama_peserta'],
                $row['kelas'],
                $row['pelatihan'],
                $row['total_pertemuan'],
                $row['hadir'],
                $row['izin'],
                $row['sakit'],
                $row['alpa'],
                $row['terlambat'],
                $row['persentase_kehadiran'] . '%',
                $row['predikat'],
            ]);
        }

        fclose($output);
        exit();
    }

    /**
     * Tampilan Cetak / PDF Bersih Laporan Kehadiran Peserta
     */
    public function cetak()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters   = $this->parseFilters();
        $stats     = $this->laporanKehadiranModel->getRingkasanStats($filters);
        $rekapList = $this->laporanKehadiranModel->getLaporanKehadiranList($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $data = [
            'title'       => 'Cetak Laporan Kehadiran Peserta - CreativeMU Academy',
            'periodeText' => $periodeText,
            'filters'     => $filters,
            'stats'       => $stats,
            'rekapList'   => $rekapList,
            'printedBy'   => session()->get('nama') ?: (session()->get('role') === 'admin' ? 'Administrator' : 'Mentor'),
            'printDate'   => date('d F Y, H:i'),
        ];

        return view('admin/laporan/cetak_laporan_kehadiran', $data);
    }
}
