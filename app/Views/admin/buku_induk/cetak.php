<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @page {
            size: landscape;
            margin: 10mm;
        }

        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 8.5pt;
            color: #111;
            background: #fff;
            margin: 0;
            padding: 10px;
        }

        .header-kop {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            border-bottom: 2.5px solid #22133c;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .header-kop img {
            max-height: 65px;
            width: auto;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 16pt;
            font-weight: 800;
            color: #22133c;
            letter-spacing: 0.5px;
        }

        .kop-text h4 {
            margin: 2px 0 4px;
            font-size: 11pt;
            font-weight: 700;
            color: #794bc4;
        }

        .kop-text p {
            margin: 0;
            font-size: 8pt;
            color: #555;
        }

        .report-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 12px;
            font-size: 8pt;
        }

        .report-meta .title-section h3 {
            margin: 0 0 3px 0;
            font-size: 12pt;
            font-weight: 700;
            color: #22133c;
            text-transform: uppercase;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.8pt;
            margin-bottom: 20px;
        }

        .report-table th, .report-table td {
            border: 1px solid #777;
            padding: 5px 6px;
            vertical-align: middle;
        }

        .report-table th {
            background-color: #f1ecfa;
            color: #22133c;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            font-size: 7.4pt;
        }

        .report-table tbody tr:nth-child(even) {
            background-color: #faf9fd;
        }

        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: 700; }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 220px;
        }

        .signature-line {
            margin-top: 65px;
            border-bottom: 1px solid #333;
            font-weight: 700;
        }

        .no-print-bar {
            background: #22133c;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .no-print-bar button {
            background: #794bc4;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            margin-left: 8px;
        }

        @media print {
            .no-print-bar { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <!-- BAR CETAK (Hanya tampil di layar) -->
    <div class="no-print-bar">
        <div>
            <i class="fas fa-print me-2"></i> <strong>Pratinjau Cetak Buku Induk Peserta</strong>
        </div>
        <div>
            <button onclick="window.print()"><i class="fas fa-print me-1"></i> Cetak Dokumen</button>
            <button onclick="window.close()" style="background: #555;"><i class="fas fa-times me-1"></i> Tutup</button>
        </div>
    </div>

    <!-- KOP SURAT -->
    <div class="header-kop">
        <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy">
        <div class="kop-text">
            <h2>CREATIVEMU ACADEMY</h2>
            <h4>LEMBAGA PELATIHAN KERJA & PENGEMBANGAN TEKNOLOGI</h4>
            <p>Pusat Pelatihan Desain, Pemrograman, Digital Marketing, dan Vokasi Bersertifikasi</p>
            <p>Website: creativemuacademy.com | Email: info@creativemu.id | Telp: (021) 8899-7766</p>
        </div>
    </div>

    <!-- META DATA LAPORAN -->
    <div class="report-meta">
        <div class="title-section">
            <h3>Buku Induk Peserta Pelatihan</h3>
            <div><strong>Filter Periode:</strong> <?= esc($periodeLabel); ?></div>
        </div>
        <div class="text-end">
            <div><strong>Dicetak Oleh:</strong> <?= esc($printedBy); ?></div>
            <div><strong>Tanggal Cetak:</strong> <?= esc($printedAt); ?></div>
            <div><strong>Total Peserta:</strong> <?= count($pesertaList); ?> Orang</div>
        </div>
    </div>

    <!-- TABEL DATA BUKU INDUK -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th>NIS</th>
                <th>Nama Peserta</th>
                <th>Tgl Masuk</th>
                <th>Kelas</th>
                <th>Tgl Selesai</th>
                <th>Status Sertifikat</th>
                <th>Diterima</th>
                <th>Kategori</th>
                <th>Pilihan Kelas</th>
                <th>Metode</th>
                <th>Jenis</th>
                <th>Lokasi</th>
                <th>Pendidikan</th>
                <th>Status</th>
                <th>No. WhatsApp</th>
                <th>Gender</th>
                <th>Tempat, Tgl Lahir</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pesertaList)): ?>
                <tr>
                    <td colspan="19" class="text-center" style="padding: 20px;">Tidak ada data peserta untuk filter yang dipilih.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($pesertaList as $p): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center fw-bold"><?= esc($p['nis']); ?></td>
                        <td class="fw-bold"><?= esc($p['nama_peserta']); ?></td>
                        <td class="text-center"><?= !empty($p['tanggal_masuk']) && $p['tanggal_masuk'] !== '-' ? date('d/m/Y', strtotime($p['tanggal_masuk'])) : '-'; ?></td>
                        <td><?= esc($p['nama_kelas']); ?></td>
                        <td class="text-center"><?= !empty($p['tanggal_selesai_kelas']) && $p['tanggal_selesai_kelas'] !== '-' ? date('d/m/Y', strtotime($p['tanggal_selesai_kelas'])) : '-'; ?></td>
                        <td class="text-center"><?= esc($p['status_sertifikat']); ?></td>
                        <td class="text-center"><?= esc($p['diterima']); ?></td>
                        <td><?= esc($p['kategori_kelas']); ?></td>
                        <td><?= esc($p['pilihan_kelas']); ?></td>
                        <td class="text-center"><?= ucfirst(esc($p['metode'])); ?></td>
                        <td class="text-center"><?= ucfirst(esc($p['jenis_kelas'])); ?></td>
                        <td><?= esc($p['lokasi_pelatihan']); ?></td>
                        <td><?= esc($p['pendidikan_terakhir']); ?></td>
                        <td class="text-center"><?= ucfirst(esc($p['status_peserta'])); ?></td>
                        <td><?= esc($p['no_whatsapp']); ?></td>
                        <td class="text-center"><?= esc($p['jenis_kelamin']); ?></td>
                        <td><?= esc($p['tempat_tanggal_lahir']); ?></td>
                        <td><?= esc($p['alamat']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="signature-section">
        <div class="signature-box">
            <div>Mengetahui,</div>
            <div>Pimpinan CreativeMU Academy</div>
            <div class="signature-line">Drs. M. Fajar Siddiq, M.Kom.</div>
            <div style="font-size: 7.5pt; color: #555;">Direktur Pelatihan</div>
        </div>
        <div class="signature-box">
            <div>Diverifikasi oleh,</div>
            <div>Bagian Administrasi & Akademik</div>
            <div class="signature-line"><?= esc($printedBy); ?></div>
            <div style="font-size: 7.5pt; color: #555;">Staf Administrasi Buku Induk</div>
        </div>
    </div>

</body>
</html>
