<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaporanMentorModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanMentorController extends BaseController
{
    protected $db;
    protected $laporanMentorModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->laporanMentorModel = new LaporanMentorModel();
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
        $bulan = $this->request->getGet('bulan');
        $bulan = (!empty($bulan) && is_numeric($bulan)) ? (int) $bulan : (int) date('n');

        $idMentor = $this->request->getGet('id_mentor') ?: 'all';
        $kategori = $this->request->getGet('kategori') ?: 'all';
        $tempatPelatihan = $this->request->getGet('tempat_pelatihan') ?: 'all';

        $mentorLogin = $this->getMentorData();
        if ($mentorLogin && session()->get('role') === 'mentor') {
            $idMentor = (string) $mentorLogin['id_mentor'];
        }

        return [
            'periode'          => 'bulanan',
            'tahun'            => (int) date('Y'),
            'bulan'            => $bulan,
            'id_mentor'        => $idMentor,
            'kategori'         => $kategori,
            'tempat_pelatihan' => $tempatPelatihan,
        ];
    }

    /**
     * Halaman Utama Laporan Mentor
     */
    public function index()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters = $this->parseFilters();
        $role = strtolower((string) session()->get('role'));
        $mentorLogin = $this->getMentorData();

        $years      = $this->laporanMentorModel->getFilterYears();
        $mentors    = $this->laporanMentorModel->getFilterMentors();
        $categories = $this->laporanMentorModel->getFilterCategories();
        $tempatList = $this->laporanMentorModel->getFilterTempatPelatihan();

        $stats       = $this->laporanMentorModel->getRingkasanStats($filters);
        $mentorList  = $this->laporanMentorModel->getLaporanMentorList($filters);
        $rankingList = $this->laporanMentorModel->getRankingMentor($filters);
        $chartData   = $this->laporanMentorModel->getChartData($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun'];

        $data = [
            'title'        => 'Laporan Instruktur & Kinerja Pengajar',
            'role'         => $role,
            'isMentor'     => ($role === 'mentor'),
            'mentorLogin'  => $mentorLogin,
            'filters'      => $filters,
            'periodeText'  => $periodeText,
            'years'        => $years,
            'mentors'      => $mentors,
            'categories'   => $categories,
            'tempatList'   => $tempatList,
            'stats'        => $stats,
            'mentorList'   => $mentorList,
            'rankingList'  => $rankingList,
            'chartData'    => $chartData,
            'bulanNames'   => $bulanNames,
        ];

        return view('admin/laporan/laporan_mentor', $data);
    }

    /**
     * Endpoint AJAX untuk modal detail mentor
     */
    public function detailAjax($id)
    {
        if ($redirect = $this->checkAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        $filters = $this->parseFilters();
        $detail = $this->laporanMentorModel->getDetailMentor((int) $id, $filters);

        if (!$detail) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Data instruktur tidak ditemukan']);
        }

        return $this->response->setJSON($detail);
    }

    /**
     * Export Laporan Mentor ke Excel (.xlsx asli menggunakan PhpSpreadsheet)
     */
    public function exportExcel()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters    = $this->parseFilters();
        $stats      = $this->laporanMentorModel->getRingkasanStats($filters);
        $mentorList = $this->laporanMentorModel->getLaporanMentorList($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun'];

        $filename = 'Laporan_Instruktur_CreativeMU_' . str_replace(' ', '_', $periodeText) . '_' . date('Ymd_His') . '.xlsx';

        // Gunakan PhpSpreadsheet jika tersedia
        if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Laporan Instruktur');

            // 1. Judul Laporan (Header Resmi)
            $sheet->setCellValue('A1', 'LAPORAN INSTRUKTUR & KINERJA PENGAJAR');
            $sheet->setCellValue('A2', 'CreativeMU Academy - Lembaga Pendidikan & Pelatihan Kejuruan Terpadu');
            $sheet->setCellValue('A3', 'Periode Laporan: ' . $periodeText . ' | Tanggal Ekspor: ' . date('d-m-Y H:i'));

            $sheet->mergeCells('A1:J1');
            $sheet->mergeCells('A2:J2');
            $sheet->mergeCells('A3:J3');

            $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('22133C');
            $sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true)->getColor()->setRGB('5931A0');
            $sheet->getStyle('A3')->getFont()->setSize(10)->setBold(true)->getColor()->setRGB('555555');

            $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // 2. Ringkasan Statistik
            $sheet->setCellValue('A5', 'RINGKASAN STATISTIK INSTRUKTUR');
            $sheet->getStyle('A5')->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('22133C');

            $summaryHeaders = ['Total Instruktur', 'Rata-rata Keaktifan', 'Rata-rata Kehadiran', 'Rata-rata Keterlambatan', 'Rata-rata Angket'];
            $colLetter = 'A';
            foreach ($summaryHeaders as $h) {
                $sheet->setCellValue($colLetter . '6', $h);
                $colLetter++;
            }

            $sheet->setCellValue('A7', $stats['total_mentor'] . ' Orang');
            $sheet->setCellValue('B7', number_format($stats['avg_keaktifan'], 1) . '%');
            $sheet->setCellValue('C7', number_format($stats['avg_kehadiran'], 1) . '%');
            $sheet->setCellValue('D7', number_format($stats['avg_keterlambatan'], 1) . '%');
            $sheet->setCellValue('E7', number_format($stats['avg_angket'], 2) . ' / 5.00');

            // Style Ringkasan
            $sheet->getStyle('A6:E6')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A6:E6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5931A0');
            $sheet->getStyle('A6:E7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A6:E7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CCCCCC');

            // 3. Tabel Utama Laporan Instruktur
            $rowStart = 10;
            $tableHeaders = [
                'No', 'Nama Instruktur', 'Kategori Pelatihan', 'Kelas Diampu', 'Tempat Pelatihan',
                'Keaktifan (%)', 'Kehadiran (%)', 'Keterlambatan (%)', 'Nilai Angket', 'Predikat'
            ];

            $colLetter = 'A';
            foreach ($tableHeaders as $th) {
                $sheet->setCellValue($colLetter . $rowStart, $th);
                $colLetter++;
            }

            $sheet->getStyle('A' . $rowStart . ':J' . $rowStart)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A' . $rowStart . ':J' . $rowStart)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('22133C');
            $sheet->getStyle('A' . $rowStart . ':J' . $rowStart)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension($rowStart)->setRowHeight(28);

            $currentRow = $rowStart + 1;
            $no = 1;

            foreach ($mentorList as $m) {
                $sheet->setCellValue('A' . $currentRow, $no++);
                $sheet->setCellValue('B' . $currentRow, $m['nama_mentor']);
                $sheet->setCellValue('C' . $currentRow, $m['pelatihan']);
                $sheet->setCellValue('D' . $currentRow, $m['kelas']);
                $sheet->setCellValue('E' . $currentRow, $m['tempat_pelatihan'] ?? '-');
                $sheet->setCellValue('F' . $currentRow, ($m['persen_keaktifan'] / 100));
                $sheet->setCellValue('G' . $currentRow, ($m['persen_kehadiran'] / 100));
                $sheet->setCellValue('H' . $currentRow, ($m['persen_keterlambatan'] / 100));
                $sheet->setCellValue('I' . $currentRow, number_format($m['nilai_angket'], 2));
                $sheet->setCellValue('J' . $currentRow, $m['predikat']);

                // Number formatting
                $sheet->getStyle('F' . $currentRow)->getNumberFormat()->setFormatCode('0.0%');
                $sheet->getStyle('G' . $currentRow)->getNumberFormat()->setFormatCode('0.0%');
                $sheet->getStyle('H' . $currentRow)->getNumberFormat()->setFormatCode('0.0%');

                // Alignments
                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
                $sheet->getStyle('E' . $currentRow . ':I' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Alternating zebra row background
                if ($no % 2 === 0) {
                    $sheet->getStyle('A' . $currentRow . ':I' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F6FD');
                }

                $sheet->getRowDimension($currentRow)->setRowHeight(22);
                $currentRow++;
            }

            // Garis Border Tabel
            $lastRow = $currentRow - 1;
            if ($lastRow >= $rowStart) {
                $sheet->getStyle('A' . $rowStart . ':I' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D0C9E8');
            }

            // Auto width untuk setiap kolom A-I
            foreach (range('A', 'I') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Freeze pane pada header tabel
            $sheet->freezePane('A11');

            // Page setup landscape dan margin
            $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
            $sheet->getPageSetup()->setFitToPage(true);
            $sheet->getPageSetup()->setFitToWidth(1);
            $sheet->getPageSetup()->setFitToHeight(0);

            // Footer halaman
            $sheet->getHeaderFooter()->setOddFooter('&R Halaman &P dari &N | CreativeMU Academy');

            // Output stream XLSX
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        }

        // Fallback jika PhpSpreadsheet belum siap
        $this->exportCsvFallback($filename, $periodeText, $stats, $mentorList);
    }

    /**
     * Fallback CSV exporter jika library XLSX tidak tersedia
     */
    private function exportCsvFallback($filename, $periodeText, $stats, $mentorList)
    {
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $csvFilename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM

        fputcsv($output, ['CREATIVEMU ACADEMY - LAPORAN MENTOR & KINERJA PENGAJAR']);
        fputcsv($output, ['Periode Laporan', $periodeText]);
        fputcsv($output, ['Tanggal Ekspor', date('d-m-Y H:i:s')]);
        fputcsv($output, []);

        fputcsv($output, ['=== RINGKASAN STATISTIK MENTOR ===']);
        fputcsv($output, ['Total Mentor', $stats['total_mentor']]);
        fputcsv($output, ['Rata-rata Keaktifan', $stats['avg_keaktifan'] . '%']);
        fputcsv($output, ['Rata-rata Kehadiran', $stats['avg_kehadiran'] . '%']);
        fputcsv($output, ['Rata-rata Keterlambatan', $stats['avg_keterlambatan'] . '%']);
        fputcsv($output, ['Rata-rata Angket', $stats['avg_angket']]);
        fputcsv($output, []);

        fputcsv($output, ['No', 'Nama Mentor', 'Kategori Pelatihan', 'Kelas Diampu', 'Tempat Pelatihan', 'Keaktifan (%)', 'Kehadiran (%)', 'Keterlambatan (%)', 'Nilai Angket', 'Predikat']);
        $no = 1;
        foreach ($mentorList as $m) {
            fputcsv($output, [
                $no++,
                $m['nama_mentor'],
                $m['pelatihan'],
                $m['kelas'],
                $m['tempat_pelatihan'] ?? '-',
                $m['persen_keaktifan'] . '%',
                $m['persen_kehadiran'] . '%',
                $m['persen_keterlambatan'] . '%',
                number_format($m['nilai_angket'], 2),
                $m['predikat'],
            ]);
        }

        fclose($output);
        exit();
    }

    /**
     * Tampilan Cetak / PDF Bersih Laporan Mentor
     */
    public function cetak()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters    = $this->parseFilters();
        $stats      = $this->laporanMentorModel->getRingkasanStats($filters);
        $mentorList = $this->laporanMentorModel->getLaporanMentorList($filters);
        $rankingList= $this->laporanMentorModel->getRankingMentor($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun'];

        $data = [
            'title'        => 'Cetak Laporan Instruktur - CreativeMU Academy',
            'periodeText'  => $periodeText,
            'filters'      => $filters,
            'stats'        => $stats,
            'mentorList'   => $mentorList,
            'rankingList'  => $rankingList,
            'printedBy'    => session()->get('nama') ?: (session()->get('role') === 'admin' ? 'Administrator' : 'Instruktur'),
            'printDate'    => date('d F Y, H:i'),
        ];

        return view('admin/laporan/cetak_laporan_mentor', $data);
    }
}
