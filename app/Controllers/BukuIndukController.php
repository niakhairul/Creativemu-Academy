<?php

namespace App\Controllers;

use App\Models\BukuIndukModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BukuIndukController extends Controller
{
    protected $bukuIndukModel;

    public function __construct()
    {
        $this->bukuIndukModel = new BukuIndukModel();
    }

    /**
     * Memeriksa hak akses administrator
     */
    private function checkAdminAccess()
    {
        $role = session()->get('role');
        if (empty($role) || $role !== 'admin') {
            return redirect()->to(base_url('pelatihan/login'))->with('error', 'Akses ditolak. Silakan login sebagai administrator.');
        }
        return null;
    }

    /**
     * Halaman Utama Buku Induk
     */
    public function index()
    {
        if ($redirect = $this->checkAdminAccess()) {
            return $redirect;
        }

        // Tangkap parameter filter & search
        $filters = [
            'tahun'             => $this->request->getGet('tahun') ?? date('Y'),
            'bulan'             => $this->request->getGet('bulan') ?? 'all',
            'id_kelas'          => $this->request->getGet('id_kelas') ?? 'all',
            'kategori'          => $this->request->getGet('kategori') ?? 'all',
            'status_sertifikat' => $this->request->getGet('status_sertifikat') ?? 'all',
            'status_diterima'   => $this->request->getGet('status_diterima') ?? 'all',
            'status_peserta'    => $this->request->getGet('status_peserta') ?? 'all',
            'keyword'           => trim((string) ($this->request->getGet('keyword') ?? '')),
            'sort_by'           => $this->request->getGet('sort_by') ?? 'nis',
            'sort_order'        => $this->request->getGet('sort_order') ?? 'ASC',
        ];

        // Pagination
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        $totalData = $this->bukuIndukModel->countBukuInduk($filters);
        $totalPages = max(1, (int) ceil($totalData / $perPage));

        $pesertaList = $this->bukuIndukModel->getBukuIndukList($filters, $perPage, $offset);
        $statistics = $this->bukuIndukModel->getStatistics($filters);

        // Opsi Dropdown Filter
        $filterYears = $this->bukuIndukModel->getFilterYears();
        $filterClasses = $this->bukuIndukModel->getFilterClasses();
        $filterCategories = $this->bukuIndukModel->getFilterCategories();

        $data = [
            'title'            => 'Buku Induk Peserta - Panel Admin',
            'activeMenu'       => 'buku-induk',
            'pesertaList'      => $pesertaList,
            'statistics'       => $statistics,
            'filters'          => $filters,
            'filterYears'      => $filterYears,
            'filterClasses'    => $filterClasses,
            'filterCategories' => $filterCategories,
            'currentPage'      => $page,
            'perPage'          => $perPage,
            'totalPages'       => $totalPages,
            'totalData'        => $totalData,
        ];

        return view('admin/buku_induk/index', $data);
    }

    /**
     * Ajax Detail Peserta untuk Modal
     */
    public function detailAjax($id)
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(403);
        }

        $detail = $this->bukuIndukModel->getDetailBukuInduk((int) $id);
        if (!$detail) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data peserta tidak ditemukan'])->setStatusCode(404);
        }

        return $this->response->setJSON(['status' => 'success', 'data' => $detail]);
    }

    /**
     * Ajax Update Peserta Buku Induk
     */
    public function updateAjax($id)
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(403);
        }

        $id = (int) $id;
        $row = $this->bukuIndukModel->find($id);
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan'])->setStatusCode(404);
        }

        $dataUpdate = [
            'nis'                 => trim($this->request->getPost('nis') ?? $row['nis']),
            'nama'                => trim($this->request->getPost('nama') ?? $row['nama']),
            'no_hp'               => trim($this->request->getPost('no_hp') ?? $row['no_hp']),
            'jenis_kelamin'       => trim($this->request->getPost('jenis_kelamin') ?? $row['jenis_kelamin']),
            'ttl'                 => trim($this->request->getPost('ttl') ?? $row['ttl']),
            'alamat'              => trim($this->request->getPost('alamat') ?? $row['alamat']),
            'pendidikan_terakhir' => trim($this->request->getPost('pendidikan_terakhir') ?? $row['pendidikan_terakhir']),
            'status'              => trim($this->request->getPost('status') ?? $row['status']),
            'status_pendaftaran'  => trim($this->request->getPost('status_pendaftaran') ?? $row['status_pendaftaran']),
            'metode_pembelajaran' => trim($this->request->getPost('metode_pembelajaran') ?? $row['metode_pembelajaran']),
            'jenis_kelas'         => trim($this->request->getPost('jenis_kelas') ?? $row['jenis_kelas']),
            'lokasi_pelatihan'    => trim($this->request->getPost('lokasi_pelatihan') ?? $row['lokasi_pelatihan']),
        ];

        $this->bukuIndukModel->update($id, $dataUpdate);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Data Buku Induk peserta berhasil diperbarui.',
        ]);
    }

    /**
     * Ajax Tambah Peserta Buku Induk
     */
    public function storeAjax()
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(403);
        }

        $idKelas = (int) $this->request->getPost('id_kelas');
        $nama = trim((string) $this->request->getPost('nama'));

        if (empty($nama) || empty($idKelas)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama peserta dan kelas wajib diisi.'])->setStatusCode(400);
        }

        // NIS otomatis jika kosong
        $nis = trim((string) $this->request->getPost('nis'));
        if (empty($nis)) {
            $nis = $this->bukuIndukModel->generateNis(date('Ym'));
        }

        $dataInsert = [
            'id_kelas'            => $idKelas,
            'nis'                 => $nis,
            'nama'                => $nama,
            'email'               => trim((string) $this->request->getPost('email')),
            'no_hp'               => trim((string) $this->request->getPost('no_hp')),
            'jenis_kelamin'       => $this->request->getPost('jenis_kelamin') ?: 'Laki-laki',
            'ttl'                 => trim((string) $this->request->getPost('ttl')),
            'alamat'              => trim((string) $this->request->getPost('alamat')),
            'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir') ?: 'SMA/SMK',
            'status'              => $this->request->getPost('status') ?: 'Aktif',
            'status_pendaftaran'  => $this->request->getPost('status_pendaftaran') ?: 'Disetujui',
            'status_pembayaran'   => 'valid',
            'metode_pembelajaran' => $this->request->getPost('metode_pembelajaran') ?: 'Offline',
            'jenis_kelas'         => $this->request->getPost('jenis_kelas') ?: 'Reguler',
            'lokasi_pelatihan'    => trim((string) $this->request->getPost('lokasi_pelatihan')) ?: 'CreativeMU Training Center',
            'tanggal_mulai_kelas' => $this->request->getPost('tanggal_mulai_kelas') ?: date('Y-m-d'),
        ];

        $this->bukuIndukModel->insert($dataInsert);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Peserta baru berhasil ditambahkan ke Buku Induk dengan NIS: ' . $nis,
        ]);
    }

    /**
     * Export Native Excel (.xlsx) via PhpSpreadsheet
     */
    public function exportExcel()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $filters = [
            'tahun'             => $this->request->getGet('tahun') ?? 'all',
            'bulan'             => $this->request->getGet('bulan') ?? 'all',
            'id_kelas'          => $this->request->getGet('id_kelas') ?? 'all',
            'kategori'          => $this->request->getGet('kategori') ?? 'all',
            'status_sertifikat' => $this->request->getGet('status_sertifikat') ?? 'all',
            'status_diterima'   => $this->request->getGet('status_diterima') ?? 'all',
            'status_peserta'    => $this->request->getGet('status_peserta') ?? 'all',
            'keyword'           => trim((string) ($this->request->getGet('keyword') ?? '')),
            'sort_by'           => 'nis',
            'sort_order'        => 'ASC',
        ];

        // Ambil semua data sesuai filter (tanpa limit pagination)
        $dataList = $this->bukuIndukModel->getBukuIndukList($filters, 5000, 0);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Buku Induk Peserta');

        // 1. Header Judul Laporan
        $sheet->mergeCells('A1:S1');
        $sheet->setCellValue('A1', 'CREATIVEMU ACADEMY');
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('22133C');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:S2');
        $sheet->setCellValue('A2', 'BUKU INDUK PESERTA PELATIHAN');
        $sheet->getStyle('A2')->getFont()->setSize(13)->setBold(true)->getColor()->setRGB('794BC4');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A3:S3');
        $periodeText = 'Periode: ' . ($filters['tahun'] !== 'all' ? 'Tahun ' . $filters['tahun'] : 'Semua Tahun');
        if (!empty($filters['bulan']) && $filters['bulan'] !== 'all') {
            $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $periodeText .= ' - Bulan ' . ($namaBulan[(int)$filters['bulan']] ?? $filters['bulan']);
        }
        $periodeText .= ' | Dicetak: ' . date('d/m/Y H:i') . ' WIB';
        $sheet->setCellValue('A3', $periodeText);
        $sheet->getStyle('A3')->getFont()->setSize(9)->setItalic(true)->getColor()->setRGB('555555');
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 2. Kolom Header Tabel
        $headers = [
            'A5' => 'No',
            'B5' => 'NIS',
            'C5' => 'Nama Peserta',
            'D5' => 'Tanggal Masuk',
            'E5' => 'Kelas',
            'F5' => 'Tanggal Selesai',
            'G5' => 'Status Sertifikat',
            'H5' => 'Diterima',
            'I5' => 'Kategori Kelas',
            'J5' => 'Pilihan Kelas',
            'K5' => 'Metode',
            'L5' => 'Jenis Kelas',
            'M5' => 'Lokasi Pelatihan',
            'N5' => 'Pendidikan Terakhir',
            'O5' => 'Status',
            'P5' => 'No. WhatsApp',
            'Q5' => 'Jenis Kelamin',
            'R5' => 'Tempat, Tanggal Lahir',
            'S5' => 'Alamat',
        ];

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
        }

        // Style Header Kolom
        $headerRange = 'A5:S5';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('794BC4');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(5)->setRowHeight(28);

        // 3. Isi Data
        $rowIdx = 6;
        $no = 1;
        foreach ($dataList as $item) {
            $sheet->setCellValue('A' . $rowIdx, $no++);
            $sheet->setCellValueExplicit('B' . $rowIdx, (string) $item['nis'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowIdx, $item['nama_peserta']);
            $sheet->setCellValue('D' . $rowIdx, $item['tanggal_masuk']);
            $sheet->setCellValue('E' . $rowIdx, $item['nama_kelas']);
            $sheet->setCellValue('F' . $rowIdx, $item['tanggal_selesai_kelas']);
            $sheet->setCellValue('G' . $rowIdx, $item['status_sertifikat']);
            $sheet->setCellValue('H' . $rowIdx, $item['diterima']);
            $sheet->setCellValue('I' . $rowIdx, $item['kategori_kelas']);
            $sheet->setCellValue('J' . $rowIdx, $item['pilihan_kelas']);
            $sheet->setCellValue('K' . $rowIdx, ucfirst($item['metode']));
            $sheet->setCellValue('L' . $rowIdx, ucfirst($item['jenis_kelas']));
            $sheet->setCellValue('M' . $rowIdx, $item['lokasi_pelatihan']);
            $sheet->setCellValue('N' . $rowIdx, $item['pendidikan_terakhir']);
            $sheet->setCellValue('O' . $rowIdx, ucfirst($item['status_peserta']));
            $sheet->setCellValueExplicit('P' . $rowIdx, (string) $item['no_whatsapp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('Q' . $rowIdx, $item['jenis_kelamin']);
            $sheet->setCellValue('R' . $rowIdx, $item['tempat_tanggal_lahir']);
            $sheet->setCellValue('S' . $rowIdx, $item['alamat']);

            // Zebra striping
            if ($rowIdx % 2 === 1) {
                $sheet->getStyle("A{$rowIdx}:S{$rowIdx}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F5FE');
            }

            $sheet->getRowDimension($rowIdx)->setRowHeight(22);
            $rowIdx++;
        }

        $lastRow = max(6, $rowIdx - 1);

        // Border Tabel
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'DCD0FF'],
                ],
            ],
        ];
        $sheet->getStyle("A5:S{$lastRow}")->applyFromArray($borderStyle);

        // Alignment kolom tertentu
        $sheet->getStyle("A6:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D6:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("F6:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("K6:L{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("O6:Q{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Auto-fit kolom
        foreach (range('A', 'S') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Tulis & Download
        $filename = 'Buku_Induk_CreativeMU_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Cetak / Print View Resmi Buku Induk
     */
    public function cetak()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $filters = [
            'tahun'             => $this->request->getGet('tahun') ?? 'all',
            'bulan'             => $this->request->getGet('bulan') ?? 'all',
            'id_kelas'          => $this->request->getGet('id_kelas') ?? 'all',
            'kategori'          => $this->request->getGet('kategori') ?? 'all',
            'status_sertifikat' => $this->request->getGet('status_sertifikat') ?? 'all',
            'status_diterima'   => $this->request->getGet('status_diterima') ?? 'all',
            'status_peserta'    => $this->request->getGet('status_peserta') ?? 'all',
            'keyword'           => trim((string) ($this->request->getGet('keyword') ?? '')),
            'sort_by'           => 'nis',
            'sort_order'        => 'ASC',
        ];

        $pesertaList = $this->bukuIndukModel->getBukuIndukList($filters, 5000, 0);
        $statistics = $this->bukuIndukModel->getStatistics($filters);

        $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $periodeLabel = ($filters['tahun'] !== 'all' ? 'Tahun ' . $filters['tahun'] : 'Semua Tahun');
        if (!empty($filters['bulan']) && $filters['bulan'] !== 'all') {
            $periodeLabel .= ' - Bulan ' . ($namaBulan[(int)$filters['bulan']] ?? $filters['bulan']);
        }

        $data = [
            'title'        => 'Cetak Buku Induk Peserta - CreativeMU Academy',
            'pesertaList'  => $pesertaList,
            'statistics'   => $statistics,
            'filters'      => $filters,
            'periodeLabel' => $periodeLabel,
            'printedBy'    => session()->get('nama') ?: 'Administrator',
            'printedAt'    => date('d F Y, H:i') . ' WIB',
        ];

        return view('admin/buku_induk/cetak', $data);
    }
}
