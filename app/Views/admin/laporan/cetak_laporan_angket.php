<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Cetak Laporan Angket Mentor'); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            background-color: #f8f9fa;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .print-container {
            max-width: 1000px;
            margin: 25px auto;
            background: #fff;
            padding: 40px 50px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-radius: 8px;
        }

        /* KOP SURAT */
        .kop-surat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 3px double #000;
            margin-bottom: 25px;
        }
        .kop-logo {
            width: 130px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            flex: 1;
            padding: 0 20px;
        }
        .kop-text h2 {
            font-size: 18pt;
            font-weight: 800;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1a0933;
        }
        .kop-text h4 {
            font-size: 13pt;
            font-weight: 600;
            margin: 2px 0 6px 0;
            color: #4b2382;
        }
        .kop-text p {
            font-size: 9pt;
            margin: 0;
            line-height: 1.3;
            color: #333;
        }

        /* HEADER LAPORAN */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .report-header h3 {
            font-size: 14pt;
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .report-header .periode {
            font-size: 11pt;
            font-weight: 600;
            color: #444;
        }

        /* STATISTIK RINGKAS */
        .stats-table {
            width: 100%;
            margin-bottom: 25px;
            border: 1px solid #333;
        }
        .stats-table td, .stats-table th {
            padding: 8px 12px;
            font-size: 10pt;
            border: 1px solid #bbb;
        }
        .stats-table th {
            background-color: #f1edf9;
            font-weight: 700;
            width: 25%;
        }

        /* DATA TABLE */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table-data th, .table-data td {
            border: 1px solid #333;
            padding: 7px 10px;
            font-size: 10pt;
        }
        .table-data thead th {
            background-color: #ebe5f7;
            text-align: center;
            font-weight: 700;
        }

        /* ULASAN BOX */
        .review-card {
            border-left: 3px solid #794bc4;
            background: #faf8fd;
            padding: 8px 12px;
            margin-bottom: 8px;
            font-size: 9.5pt;
        }

        /* TANDA TANGAN */
        .ttd-section {
            margin-top: 35px;
            display: flex;
            justify-content: flex-end;
            page-break-inside: avoid;
        }
        .ttd-box {
            text-align: center;
            width: 260px;
        }
        .ttd-space {
            height: 75px;
        }

        /* FLOATING ACTION BAR */
        .no-print-bar {
            background: #22133c;
            color: #fff;
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        /* MEDIA PRINT */
        @page { size: A4 landscape; margin: 12mm; }
        @media print {
            body { background: #fff !important; }
            .no-print-bar { display: none !important; }
            .print-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- FLOATING ACTION BAR FOR SCREEN ONLY -->
    <div class="no-print-bar">
        <div>
            <strong><i class="fas fa-print me-2"></i> Pratinjau Cetak Laporan Angket Mentor</strong>
            <span class="ms-2 opacity-75 small">(Format Siap Cetak & Export PDF)</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-light fw-bold text-dark px-3">
                <i class="fas fa-print me-1"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="btn btn-sm btn-outline-light px-3">
                <i class="fas fa-times me-1"></i> Tutup
            </button>
        </div>
    </div>

    <!-- PRINT CONTAINER -->
    <div class="print-container">

        <!-- 1. KOP SURAT RESMI -->
        <div class="kop-surat">
            <div>
                <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Logo" class="kop-logo">
            </div>
            <div class="kop-text">
                <h2>CreativeMU Academy</h2>
                <h4>Lembaga Pendidikan & Pelatihan Kejuruan Terpadu</h4>
                <p>
                    Jl. Pemuda No. 45, Kompleks Edukasi Mandiri, Indonesia<br>
                    Website: creativemuacademy.com | Email: info@creativemu.id | Telp: (0274) 889922
                </p>
            </div>
        </div>

        <!-- 2. HEADER LAPORAN -->
        <div class="report-header">
            <h3>Laporan Hasil Angket & Evaluasi Kepuasan Mentor</h3>
            <div class="periode">Periode: <?= esc($periodeText); ?></div>
            <div class="small text-muted mt-1">Dicetak pada: <?= esc($printDate); ?> WIB | Oleh: <?= esc($printedBy); ?></div>
        </div>

        <!-- 3. RINGKASAN STATISTIK -->
        <h6 class="fw-bold mb-2">I. Ringkasan Metrik Evaluasi Angket</h6>
        <table class="stats-table">
            <tr>
                <th>Total Mentor Dinilai</th>
                <td><strong><?= number_format($stats['total_mentor_dinilai']); ?></strong> Orang</td>
                <th>Total Responden Peserta</th>
                <td><strong><?= number_format($stats['total_responden']); ?></strong> Responden</td>
            </tr>
            <tr>
                <th>Rata-rata Nilai Angket</th>
                <td><strong class="text-primary"><?= number_format($stats['avg_nilai_angket'], 2); ?> / 4.00</strong></td>
                <th>Persentase Kepuasan Peserta</th>
                <td><strong class="text-success"><?= number_format($stats['persen_kepuasan'], 1); ?>%</strong></td>
            </tr>
        </table>

        <!-- 4. TABEL SKOR PER INDIKATOR -->
        <h6 class="fw-bold mb-2">II. Penilaian Rata-rata Berdasarkan Indikator Angket</h6>
        <table class="table-data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Indikator / Aspek Pertanyaan Evaluasi</th>
                    <th width="18%">Kategori</th>
                    <th width="15%">Skor (Skala 1-4)</th>
                    <th width="15%">Persentase Kepuasan</th>
                </tr>
            </thead>
            <tbody>
                <?php $iNo = 1; foreach ($indikatorList as $ind): ?>
                <tr>
                    <td class="text-center"><?= $iNo++; ?></td>
                    <td><?= esc($ind['judul']); ?></td>
                    <td class="text-center"><span class="badge bg-secondary text-white"><?= esc($ind['kategori']); ?></span></td>
                    <?php if (($ind['tipe'] ?? 'rating') === 'rating'): ?>
                        <td class="text-center fw-bold"><?= number_format($ind['nilai'], 2); ?></td>
                        <td class="text-center fw-bold text-success"><?= number_format($ind['persentase'], 1); ?>%</td>
                    <?php else: ?>
                        <td class="text-center text-muted fst-italic">Teks/Pilihan</td>
                        <td class="text-center fw-bold text-info"><?= isset($ind['count']) ? number_format($ind['count']) : 0; ?> Respons</td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 5. TABEL UTAMA REKAPITULASI PENILAIAN MENTOR -->
        <h6 class="fw-bold mb-2">III. Rekapitulasi Hasil Penilaian Angket per Mentor</h6>
        <table class="table-data">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th>Nama Mentor</th>
                    <th>Pelatihan</th>
                    <th>Kelas Diampu</th>
                    <th>Kantor Pusat</th>
                      <th>Kantor Cabang</th>
                      <th>Kantor<br>Perwakilan</th>
                    <th width="12%">Responden</th>
                    <th width="12%">Nilai Rata-rata</th>
                    <th width="12%">Kepuasan (%)</th>
                    <th width="12%">Predikat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($angketList)): ?>
                    <?php $no = 1; foreach ($angketList as $m): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td>
                            <strong><?= esc($m['nama_mentor']); ?></strong><br>
                            <small class="text-muted">NIP: <?= esc($m['nip']); ?></small>
                        </td>
                        <td><?= esc($m['pelatihan']); ?></td>
                        <td><?= esc($m['kelas']); ?></td>
                        <td class="text-center"><?= $m['responden_pusat'] ?: '-'; ?></td>
                          <td class="text-center"><?= $m['responden_cabang'] ?: '-'; ?></td>
                          <td class="text-center"><?= $m['responden_perwakilan'] ?: '-'; ?></td>
                        <td class="text-center"><?= number_format($m['jumlah_responden']); ?> Orang</td>
                        <td class="text-center fw-bold text-primary"><?= number_format($m['nilai_rata'], 2); ?> / 4.00</td>
                        <td class="text-center fw-bold text-success"><?= number_format($m['persen_kepuasan'], 1); ?>%</td>
                        <td class="text-center"><strong><?= esc($m['predikat']); ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="11" class="text-center py-4 text-muted">Tidak ada data pada filter yang dipilih.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- 6. ULASAN & KOMENTAR KUALITATIF PESERTA -->
        <?php if (!empty($komentarList)): ?>
        <h6 class="fw-bold mb-2 mt-4">IV. Ulasan Kualitatif, Kritik & Saran Peserta Pelatihan</h6>
        <div class="mb-3">
            <?php foreach (array_slice($komentarList, 0, 5) as $rev): ?>
            <div class="review-card">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div>
                        <strong><?= esc($rev['nama_peserta']); ?></strong>
                        <span class="text-muted">(Kelas: <?= esc($rev['nama_kelas']); ?> | Mentor: <?= esc($rev['nama_mentor']); ?>)</span>
                    </div>
                    <div>
                        <?php if ($rev['rating'] > 0): ?>
                            <span class="text-warning fw-bold"><i class="fas fa-star"></i> <?= number_format($rev['rating'], 1); ?>/4.0</span>
                        <?php else: ?>
                            <span class="text-info fw-bold"><i class="fas fa-comment-dots"></i> Feedback</span>
                        <?php endif; ?>
                        <span class="text-muted ms-2 small"><?= esc($rev['tanggal']); ?></span>
                    </div>
                </div>
                <div class="fst-italic text-dark">"<?= esc($rev['ulasan']); ?>"</div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- 7. LEMBAR PENGESAHAN / TANDA TANGAN -->
        <?= view('admin/laporan/components/ttd_pimpinan'); ?>

    </div>

</body>
</html>
