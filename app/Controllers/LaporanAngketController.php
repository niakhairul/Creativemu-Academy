<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaporanAngketModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanAngketController extends BaseController
{
    protected $db;
    protected $laporanAngketModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->laporanAngketModel = new LaporanAngketModel();
    }

    /**
     * Verifikasi hak akses (Hanya Admin dan Mentor)
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
     * Ambil data mentor yang sedang login
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

        $availableYears = $this->laporanAngketModel->getFilterYears();
        $defaultYear = !empty($availableYears) ? (int) $availableYears[0] : (int) date('Y');

        $tahun = $this->request->getGet('tahun');
        $tahun = (!empty($tahun) && is_numeric($tahun)) ? (int) $tahun : $defaultYear;

        $bulan = $this->request->getGet('bulan');
        $bulan = (!empty($bulan) && is_numeric($bulan)) ? (int) $bulan : (int) date('n');

        $idMentor = $this->request->getGet('id_mentor') ?: 'all';
        $idKelas  = $this->request->getGet('id_kelas') ?: 'all';
        $kategori = $this->request->getGet('kategori') ?: 'all';

        $mentorLogin = $this->getMentorData();
        if ($mentorLogin && session()->get('role') === 'mentor') {
            // Jika role mentor, batasi hanya melihat datanya
            $idMentor = (string) $mentorLogin['id_mentor'];
        }

        return [
            'periode'   => $periode,
            'tahun'     => $tahun,
            'bulan'     => $bulan,
            'id_mentor' => $idMentor,
            'id_kelas'  => $idKelas,
            'kategori'  => $kategori,
        ];
    }

    /**
     * Halaman Utama Laporan Angket Mentor
     */
    public function index()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters     = $this->parseFilters();
        $role        = strtolower((string) session()->get('role'));
        $mentorLogin = $this->getMentorData();

        $years      = $this->laporanAngketModel->getFilterYears();
        $mentors    = $this->laporanAngketModel->getFilterMentors();
        $classes    = $this->laporanAngketModel->getFilterClasses();
        $categories = $this->laporanAngketModel->getFilterCategories();

        $stats         = $this->laporanAngketModel->getRingkasanStats($filters);
        $angketList    = $this->laporanAngketModel->getLaporanAngketList($filters);
        $indikatorList = $this->laporanAngketModel->getPenilaianPerIndikator($filters);
        $rankingList   = $this->laporanAngketModel->getRankingMentor($filters);
        $chartData     = $this->laporanAngketModel->getChartData($filters);

        $selectedMentorId = (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') 
            ? (int) $filters['id_mentor'] 
            : null;
        $komentarList = $this->laporanAngketModel->getDetailKomentar($selectedMentorId, $filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $data = [
            'title'         => 'Laporan Angket Mentor & Evaluasi Kepuasan',
            'role'          => $role,
            'isMentor'      => ($role === 'mentor'),
            'mentorLogin'   => $mentorLogin,
            'filters'       => $filters,
            'periodeText'   => $periodeText,
            'years'         => $years,
            'mentors'       => $mentors,
            'classes'       => $classes,
            'categories'    => $categories,
            'stats'         => $stats,
            'angketList'    => $angketList,
            'indikatorList' => $indikatorList,
            'rankingList'   => $rankingList,
            'chartData'     => $chartData,
            'komentarList'  => $komentarList,
            'bulanNames'    => $bulanNames,
        ];

        return view('admin/laporan/laporan_angket', $data);
    }

    /**
     * Endpoint AJAX untuk modal detail komentar & ulasan peserta per mentor
     */
    public function detailKomentarAjax($idMentor = null)
    {
        if ($redirect = $this->checkAccess()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        $id = $idMentor ? (int) $idMentor : (int) $this->request->getGet('id_mentor');
        $filters = $this->parseFilters();

        $mentorRow = null;
        if ($id > 0) {
            $mentorRow = $this->db->table('mentor')->where('id_mentor', $id)->get()->getRowArray();
        }

        $comments = $this->laporanAngketModel->getDetailKomentar($id > 0 ? $id : null, $filters);

        return $this->response->setJSON([
            'status'   => 'success',
            'mentor'   => $mentorRow ? [
                'nama'     => $mentorRow['nama_mentor'],
                'nip'      => $mentorRow['nip'] ?: '-',
                'keahlian' => $mentorRow['keahlian'] ?: 'Instruktur Ahli',
            ] : null,
            'total'    => count($comments),
            'comments' => $comments,
        ]);
    }

    /**
     * Export Laporan Angket Mentor ke Excel (.xlsx resmi via PhpSpreadsheet)
     */
    public function exportExcel()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters       = $this->parseFilters();
        $stats         = $this->laporanAngketModel->getRingkasanStats($filters);
        $angketList    = $this->laporanAngketModel->getLaporanAngketList($filters);
        $indikatorList = $this->laporanAngketModel->getPenilaianPerIndikator($filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $filename = 'Laporan_Angket_Mentor_CreativeMU_' . str_replace(' ', '_', $periodeText) . '_' . date('Ymd_His') . '.xlsx';

        if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Laporan Angket Mentor');

            // 1. Judul Laporan (Header Lembaga)
            $sheet->setCellValue('A1', 'LAPORAN HASIL ANGKET & EVALUASI KEPUASAN MENTOR');
            $sheet->setCellValue('A2', 'CreativeMU Academy - Lembaga Pendidikan & Pelatihan Kejuruan Terpadu');
            $sheet->setCellValue('A3', 'Periode Evaluasi: ' . $periodeText . ' | Tanggal Ekspor: ' . date('d-m-Y H:i') . ' WIB');

            $sheet->mergeCells('A1:H1');
            $sheet->mergeCells('A2:H2');
            $sheet->mergeCells('A3:H3');

            $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('22133C');
            $sheet->getStyle('A2')->getFont()->setSize(11)->setItalic(true)->getColor()->setRGB('5931A0');
            $sheet->getStyle('A3')->getFont()->setSize(10)->setBold(true)->getColor()->setRGB('555555');
            $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // 2. Ringkasan Statistik
            $sheet->setCellValue('A5', 'RINGKASAN METRIK EVALUASI ANGKET');
            $sheet->getStyle('A5')->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('22133C');

            $summaryHeaders = ['Total Mentor Dinilai', 'Total Responden Peserta', 'Rata-rata Nilai Angket', 'Persentase Kepuasan'];
            $cols = ['A', 'C', 'E', 'G'];
            $colEnds = ['B', 'D', 'F', 'H'];

            for ($i = 0; $i < 4; $i++) {
                $sheet->mergeCells($cols[$i] . '6:' . $colEnds[$i] . '6');
                $sheet->mergeCells($cols[$i] . '7:' . $colEnds[$i] . '7');
                $sheet->setCellValue($cols[$i] . '6', $summaryHeaders[$i]);
            }

            $sheet->setCellValue('A7', $stats['total_mentor_dinilai'] . ' Mentor');
            $sheet->setCellValue('C7', $stats['total_responden'] . ' Peserta Responden');
            $sheet->setCellValue('E7', number_format($stats['avg_nilai_angket'], 2) . ' / 5.00');
            $sheet->setCellValue('G7', number_format($stats['persen_kepuasan'], 1) . '%');

            $sheet->getStyle('A6:H6')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A6:H6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('5931A0');
            $sheet->getStyle('A6:H7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A6:H7')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CCCCCC');

            // 3. Tabel Skor per Indikator
            $rowIndikator = 9;
            $sheet->setCellValue('A' . $rowIndikator, 'SKOR RATA-RATA PER INDIKATOR ANGKET');
            $sheet->getStyle('A' . $rowIndikator)->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('22133C');

            $rowIndHeader = $rowIndikator + 1;
            $sheet->setCellValue('A' . $rowIndHeader, 'No');
            $sheet->setCellValue('B' . $rowIndHeader, 'Indikator / Aspek Evaluasi');
            $sheet->mergeCells('B' . $rowIndHeader . ':E' . $rowIndHeader);
            $sheet->setCellValue('F' . $rowIndHeader, 'Kategori');
            $sheet->setCellValue('G' . $rowIndHeader, 'Skor (1-5)');
            $sheet->setCellValue('H' . $rowIndHeader, 'Kepuasan (%)');

            $sheet->getStyle('A' . $rowIndHeader . ':H' . $rowIndHeader)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A' . $rowIndHeader . ':H' . $rowIndHeader)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('4B2382');
            $sheet->getStyle('A' . $rowIndHeader . ':H' . $rowIndHeader)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $currIndRow = $rowIndHeader + 1;
            $indNo = 1;
            foreach ($indikatorList as $ind) {
                $sheet->setCellValue('A' . $currIndRow, $indNo++);
                $sheet->setCellValue('B' . $currIndRow, $ind['judul']);
                $sheet->mergeCells('B' . $currIndRow . ':E' . $currIndRow);
                $sheet->setCellValue('F' . $currIndRow, $ind['kategori']);
                $sheet->setCellValue('G' . $currIndRow, number_format($ind['nilai'], 2));
                $sheet->setCellValue('H' . $currIndRow, ($ind['persentase'] / 100));

                $sheet->getStyle('H' . $currIndRow)->getNumberFormat()->setFormatCode('0.0%');
                $sheet->getStyle('A' . $currIndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F' . $currIndRow . ':H' . $currIndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $currIndRow++;
            }
            $sheet->getStyle('A' . $rowIndHeader . ':H' . ($currIndRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('DDDDDD');

            // 4. Tabel Utama Rekap Penilaian Mentor
            $rowTableStart = $currIndRow + 2;
            $sheet->setCellValue('A' . ($rowTableStart - 1), 'TABEL REKAPITULASI PENILAIAN ANGKET MENTOR');
            $sheet->getStyle('A' . ($rowTableStart - 1))->getFont()->setSize(11)->setBold(true)->getColor()->setRGB('22133C');

            $mainHeaders = [
                'No', 'Nama Mentor', 'Kategori Pelatihan', 'Kelas Diampu',
                'Jml Responden', 'Nilai Rata-rata', 'Persentase Kepuasan', 'Predikat'
            ];

            $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            for ($k = 0; $k < count($mainHeaders); $k++) {
                $sheet->setCellValue($colLetters[$k] . $rowTableStart, $mainHeaders[$k]);
            }

            $sheet->getStyle('A' . $rowTableStart . ':H' . $rowTableStart)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sheet->getStyle('A' . $rowTableStart . ':H' . $rowTableStart)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('22133C');
            $sheet->getStyle('A' . $rowTableStart . ':H' . $rowTableStart)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension($rowTableStart)->setRowHeight(28);

            $currentRow = $rowTableStart + 1;
            $no = 1;

            foreach ($angketList as $m) {
                $sheet->setCellValue('A' . $currentRow, $no++);
                $sheet->setCellValue('B' . $currentRow, $m['nama_mentor']);
                $sheet->setCellValue('C' . $currentRow, $m['pelatihan']);
                $sheet->setCellValue('D' . $currentRow, $m['kelas']);
                $sheet->setCellValue('E' . $currentRow, $m['jumlah_responden']);
                $sheet->setCellValue('F' . $currentRow, number_format($m['nilai_rata'], 2));
                $sheet->setCellValue('G' . $currentRow, ($m['persen_kepuasan'] / 100));
                $sheet->setCellValue('H' . $currentRow, $m['predikat']);

                $sheet->getStyle('G' . $currentRow)->getNumberFormat()->setFormatCode('0.0%');

                $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $currentRow)->getFont()->setBold(true);
                $sheet->getStyle('E' . $currentRow . ':H' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($no % 2 === 0) {
                    $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F6FD');
                }

                $sheet->getRowDimension($currentRow)->setRowHeight(22);
                $currentRow++;
            }

            $lastRow = $currentRow - 1;
            if ($lastRow >= $rowTableStart) {
                $sheet->getStyle('A' . $rowTableStart . ':H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('D0C9E8');
            }

            foreach (range('A', 'H') as $col) {
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

        // Fallback CSV jika library belum terpasang
        $this->exportCsvFallback($filename, $periodeText, $stats, $angketList);
    }

    /**
     * Fallback CSV exporter
     */
    private function exportCsvFallback($filename, $periodeText, $stats, $angketList)
    {
        $csvFilename = str_replace('.xlsx', '.csv', $filename);
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $csvFilename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, ['CREATIVEMU ACADEMY - LAPORAN ANGKET MENTOR & KEPUASAN PESERTA']);
        fputcsv($output, ['Periode Laporan', $periodeText]);
        fputcsv($output, ['Tanggal Ekspor', date('d-m-Y H:i:s')]);
        fputcsv($output, []);

        fputcsv($output, ['=== RINGKASAN METRIK EVALUASI ANGKET ===']);
        fputcsv($output, ['Total Mentor Dinilai', $stats['total_mentor_dinilai']]);
        fputcsv($output, ['Total Responden Peserta', $stats['total_responden']]);
        fputcsv($output, ['Rata-rata Nilai Angket', $stats['avg_nilai_angket'] . ' / 5.00']);
        fputcsv($output, ['Persentase Kepuasan', $stats['persen_kepuasan'] . '%']);
        fputcsv($output, []);

        fputcsv($output, ['No', 'Nama Mentor', 'Kategori Pelatihan', 'Kelas Diampu', 'Jml Responden', 'Nilai Rata-rata', 'Persentase Kepuasan', 'Predikat']);
        $no = 1;
        foreach ($angketList as $m) {
            fputcsv($output, [
                $no++,
                $m['nama_mentor'],
                $m['pelatihan'],
                $m['kelas'],
                $m['jumlah_responden'],
                number_format($m['nilai_rata'], 2),
                $m['persen_kepuasan'] . '%',
                $m['predikat'],
            ]);
        }

        fclose($output);
        exit();
    }

    /**
     * Tampilan Cetak / PDF Bersih Laporan Angket Mentor
     */
    public function cetak()
    {
        if ($redirect = $this->checkAccess()) {
            return $redirect;
        }

        $filters       = $this->parseFilters();
        $stats         = $this->laporanAngketModel->getRingkasanStats($filters);
        $angketList    = $this->laporanAngketModel->getLaporanAngketList($filters);
        $indikatorList = $this->laporanAngketModel->getPenilaianPerIndikator($filters);
        $rankingList   = $this->laporanAngketModel->getRankingMentor($filters);
        $selectedMentorId = (!empty($filters['id_mentor']) && $filters['id_mentor'] !== 'all') 
            ? (int) $filters['id_mentor'] 
            : null;
        $komentarList  = $this->laporanAngketModel->getDetailKomentar($selectedMentorId, $filters);

        $bulanNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $periodeText = ($filters['periode'] === 'bulanan')
            ? ($bulanNames[$filters['bulan']] ?? 'Bulan ' . $filters['bulan']) . ' ' . $filters['tahun']
            : 'Tahun ' . $filters['tahun'];

        $data = [
            'title'         => 'Cetak Laporan Angket Mentor - CreativeMU Academy',
            'periodeText'   => $periodeText,
            'filters'       => $filters,
            'stats'         => $stats,
            'angketList'    => $angketList,
            'indikatorList' => $indikatorList,
            'rankingList'   => $rankingList,
            'komentarList'  => $komentarList,
            'printedBy'     => session()->get('nama') ?: (session()->get('role') === 'admin' ? 'Administrator' : 'Mentor'),
            'printDate'     => date('d F Y, H:i'),
        ];

        return view('admin/laporan/cetak_laporan_angket', $data);
    }
}
