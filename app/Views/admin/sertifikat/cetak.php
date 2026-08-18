<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat - <?= esc($sertifikat['nama_siswa']) ?></title>
    <style>
        body { font-family: 'Georgia', serif; text-align: center; padding: 50px; background: #fff; }
        .cert-container { border: 10px solid #1a365d; padding: 40px; margin: auto; max-width: 800px; }
        h1 { font-size: 42px; color: #1a365d; margin-bottom: 10px; }
        h2 { font-size: 28px; color: #2b6cb0; text-decoration: underline; }
        p { font-size: 18px; color: #4a5568; line-height: 1.6; }
        .no-cert { font-size: 14px; color: #718096; margin-top: 30px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()" style="margin-bottom: 20px; padding: 10px 20px; cursor: pointer;">Cetak Dokumen</button>
    
    <div class="cert-container">
        <h1>SERTIFIKAT KELULUSAN</h1>
        <p>Diberikan Kepada:</p>
        <h2><?= esc($sertifikat['nama_siswa']) ?></h2>
        <p>Atas kelulusannya pada pelatihan kelas:</p>
        <h3><?= esc($sertifikat['nama_kelas']) ?></h3>
        <br>
        <p>Diterbitkan Tanggal: <strong><?= date('d F Y', strtotime($sertifikat['tanggal_terbit'])) ?></strong></p>
        <div class="no-cert">No. Sertifikat: <?= esc($sertifikat['nomor_sertifikat']) ?></div>
    </div>
</body>
</html>