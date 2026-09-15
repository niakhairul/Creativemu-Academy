<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Cetak Laporan Mentor'); ?></title>
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
            margin-bottom: 30px;
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

        /* TANDA TANGAN */
        .ttd-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
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
            <strong><i class="fas fa-print me-2"></i> Pratinjau Cetak Laporan Mentor</strong>
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
            <h3>Laporan Kinerja & Evaluasi Mentor Pelatihan</h3>
            <div class="periode">Periode: <?= esc($periodeText); ?></div>
            <div class="small text-muted mt-1">Dicetak pada: <?= esc($printDate); ?> WIB | Oleh: <?= esc($printedBy); ?></div>
        </div>

        <!-- 3. RINGKASAN STATISTIK -->
        <h6 class="fw-bold mb-2">I. Ringkasan Statistik Kinerja Mentor</h6>
        <table class="stats-table">
            <tr>
                <th>Total Mentor Dievaluasi</th>
                <td><strong><?= number_format($stats['total_mentor']); ?></strong> Orang</td>
                <th>Rata-rata Keaktifan Sesi</th>
                <td><strong><?= number_format($stats['avg_keaktifan'], 1); ?>%</strong></td>
            </tr>
            <tr>
                <th>Rata-rata Tingkat Kehadiran</th>
                <td><strong class="text-success"><?= number_format($stats['avg_kehadiran'], 1); ?>%</strong></td>
                <th>Rata-rata Keterlambatan</th>
                <td><strong class="text-danger"><?= number_format($stats['avg_keterlambatan'], 1); ?>%</strong></td>
            </tr>
            <tr>
                <th>Rata-rata Nilai Angket Siswa</th>
                <td colspan="3"><strong class="text-primary"><?= number_format($stats['avg_angket'], 2); ?> / 5.00</strong> (Skala Kepuasan Peserta)</td>
            </tr>
        </table>

        <!-- 4. TABEL UTAMA LAPORAN MENTOR -->
        <h6 class="fw-bold mb-2">II. Rekapitulasi Data Kinerja & Evaluasi Pengajar</h6>
        <table class="table-data">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th>Nama Mentor</th>
                    <th>Pelatihan</th>
                    <th>Kelas Diampu</th>
                    <th width="9%">Keaktifan</th>
                    <th width="9%">Kehadiran</th>
                    <th width="9%">Keterlambatan</th>
                    <th width="10%">Nilai Angket</th>
                    <th width="9%">Skor Akhir</th>
                    <th width="11%">Predikat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mentorList)): ?>
                    <?php $no = 1; foreach ($mentorList as $m): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><strong><?= esc($m['nama_mentor']); ?></strong><br><small class="text-muted">NIP: <?= esc($m['nip']); ?></small></td>
                        <td><?= esc($m['pelatihan']); ?></td>
                        <td><?= esc($m['kelas']); ?></td>
                        <td class="text-center"><?= $m['persen_keaktifan']; ?>%</td>
                        <td class="text-center"><strong><?= $m['persen_kehadiran']; ?>%</strong></td>
                        <td class="text-center"><?= $m['persen_keterlambatan']; ?>%</td>
                        <td class="text-center"><?= number_format($m['nilai_angket'], 2); ?></td>
                        <td class="text-center fw-bold text-primary"><?= number_format($m['skor_performa'], 1); ?>%</td>
                        <td class="text-center"><strong><?= $m['predikat']; ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="10" class="text-center py-3 text-muted">Tidak ada data mentor pada periode yang dipilih.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- 5. LEMBAR PENGESAHAN / TANDA TANGAN -->
        <div class="ttd-section">
            <div class="ttd-box">
                <div>Yogyakarta, <?= date('d F Y'); ?></div>
                <div class="fw-bold">Pimpinan CreativeMU Academy</div>
                <div class="ttd-space"></div>
                <div class="fw-bold text-decoration-underline">( Dr. H. Arifin Wicaksono, M.Kom )</div>
                <div class="small text-muted">NIP: 19820415 200812 1 002</div>
            </div>
        </div>

    </div>

</body>
</html>
