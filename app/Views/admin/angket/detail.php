<?php
if (!function_exists('rating_stars_admin_angket')) {
    function rating_stars_admin_angket($nilai): string
    {
        $nilai = max(0, min(5, (float) $nilai));
        $full = (int) round($nilai);
        return str_repeat('&#9733;', $full) . str_repeat('&#9734;', 5 - $full);
    }
}

$tanggalPelatihan = !empty($angket['tanggal_mulai_kelas']) ? date('d M Y', strtotime($angket['tanggal_mulai_kelas'])) : '-';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --sidebar-bg:#22133c; --sidebar-active-gradient:linear-gradient(135deg,#794bc4 0%,#5931a0 100%); --sidebar-text:#c8bfe7; --primary-purple:#794bc4; --dark-purple:#1e0f33; --light-purple:#f4f0fc; --soft-border:#eadffb; --muted-text:#817796; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:'Poppins',sans-serif; background:#f7f5fd; color:#2f2442; overflow-x:hidden; }
        #sidebar { width:275px; height:100vh; position:fixed; inset:0 auto 0 0; background:var(--sidebar-bg); z-index:1000; overflow-y:auto; box-shadow:8px 0 28px rgba(34,19,60,.12); }
        #sidebar .sidebar-header { padding:22px 18px; background:rgba(0,0,0,.22); text-align:center; }
        #sidebar .sidebar-header img { width:230px; max-width:100%; height:92px; object-fit:cover; border-radius:10px; }
        #sidebar .nav { padding:18px 12px 28px; }
        #sidebar .nav-link { color:var(--sidebar-text); padding:12px 16px; display:flex; align-items:center; gap:12px; border-radius:12px; margin-bottom:6px; font-weight:500; font-size:.92rem; transition:.2s ease; }
        #sidebar .nav-link i { width:21px; text-align:center; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background:var(--sidebar-active-gradient); color:#fff; box-shadow:0 8px 20px rgba(121,75,196,.28); }
        #main-content { margin-left:275px; padding:32px; min-height:100vh; }
        .top-navbar, .panel, .info-card, .score-card, .comment-card { background:#fff; border:1px solid rgba(121,75,196,.08); box-shadow:0 14px 34px rgba(64,36,105,.06); }
        .top-navbar { border-radius:18px; padding:22px 26px; display:flex; justify-content:space-between; align-items:center; gap:18px; margin-bottom:24px; }
        .page-title { font-size:clamp(1.25rem,2vw,1.75rem); color:var(--dark-purple); font-weight:800; margin:0; }
        .page-subtitle { color:var(--muted-text); margin:6px 0 0; font-size:.92rem; }
        .admin-profile { display:flex; align-items:center; gap:12px; min-width:max-content; }
        .admin-profile img { width:46px; height:46px; border-radius:50%; object-fit:cover; border:2px solid var(--primary-purple); }
        .admin-info h6 { margin:0; color:var(--dark-purple); font-weight:700; font-size:.92rem; }
        .admin-info small, .muted { color:var(--muted-text); }
        .btn-purple { background:var(--sidebar-active-gradient); color:#fff; border:none; border-radius:12px; min-height:42px; padding:10px 16px; font-weight:700; }
        .btn-purple:hover { color:#fff; filter:brightness(.98); }
        .btn-soft { background:var(--light-purple); color:var(--primary-purple); border:1px solid var(--soft-border); border-radius:12px; min-height:42px; padding:10px 15px; font-weight:700; }
        .panel { border-radius:18px; padding:24px; margin-bottom:20px; }
        .detail-grid { display:grid; grid-template-columns:1.35fr .65fr; gap:18px; align-items:start; }
        .info-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
        .info-card { border-radius:14px; padding:16px; box-shadow:none; }
        .info-label { color:var(--muted-text); font-size:.78rem; font-weight:800; text-transform:uppercase; letter-spacing:.03em; margin-bottom:6px; }
        .info-value { color:var(--dark-purple); font-weight:700; overflow-wrap:anywhere; }
        .score-stack { display:grid; gap:12px; }
        .score-card { border-radius:14px; padding:16px; box-shadow:none; }
        .score-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:8px; }
        .score-label { color:var(--dark-purple); font-weight:800; }
        .score-number { color:var(--primary-purple); font-weight:800; }
        .rating-stars { color:#f5b301; letter-spacing:1px; white-space:nowrap; font-size:1.08rem; }
        .avg-card { background:linear-gradient(135deg,#794bc4,#5931a0); color:#fff; border-radius:16px; padding:20px; }
        .avg-card .rating-stars { color:#ffd766; }
        .avg-value { font-size:2rem; line-height:1; font-weight:800; }
        .section-title { color:var(--dark-purple); font-weight:800; margin:0 0 14px; font-size:1.05rem; }
        .question-list { display:grid; gap:10px; margin:0; padding:0; list-style:none; }
        .question-item { display:grid; grid-template-columns:auto 1fr; gap:12px; padding:13px; border:1px solid #f0eafb; border-radius:12px; background:#fcfbff; }
        .question-number { width:30px; height:30px; border-radius:10px; display:grid; place-items:center; background:var(--light-purple); color:var(--primary-purple); font-weight:800; font-size:.82rem; }
        .question-category { display:inline-flex; width:max-content; max-width:100%; padding:4px 9px; border-radius:999px; background:#fff; border:1px solid var(--soft-border); color:var(--primary-purple); font-size:.72rem; font-weight:700; margin-bottom:5px; }
        .comment-card { border-radius:14px; padding:16px; margin-bottom:12px; box-shadow:none; }
        .comment-top { display:flex; justify-content:space-between; gap:12px; margin-bottom:8px; }
        .comment-name { color:var(--dark-purple); font-weight:800; }
        .empty-state { text-align:center; color:var(--muted-text); padding:34px 12px; }
        @media (max-width:992px) { .detail-grid { grid-template-columns:1fr; } }
        @media (max-width:768px) {
            #sidebar { position:relative; width:100%; height:auto; }
            #sidebar .sidebar-header { padding:14px; }
            #sidebar .sidebar-header img { width:190px; height:74px; }
            #sidebar .nav { padding:12px; display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:6px; }
            #sidebar .nav-link { margin:0; min-height:46px; font-size:.82rem; padding:10px 12px; }
            #main-content { margin-left:0; padding:18px; }
            .top-navbar { flex-direction:column; align-items:flex-start; padding:18px; border-radius:14px; }
            .admin-profile { width:100%; }
            .panel { padding:16px; border-radius:14px; }
            .info-grid { grid-template-columns:1fr; }
            .comment-top { flex-direction:column; }
        }
        @media (max-width:430px) { #sidebar .nav { grid-template-columns:1fr; } .avg-value { font-size:1.7rem; } }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>
    <nav id="sidebar">
        <div class="sidebar-header"><img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy"></div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">
                    <i class="fas fa-book"></i> <span>Master Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/mentor'); ?>" class="nav-link">
                    <i class="fas fa-chalkboard-user"></i> <span>Instruktur</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">
                    <i class="fas fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link">
                    <i class="fas fa-book-open"></i> <span>Buku Induk</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link active">
                    <i class="fas fa-award"></i> <span>Angket</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link">
                    <i class="fas fa-certificate"></i> <span>Sertifikat</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
                    <i class="fas fa-file-lines"></i> <span>Laporan</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link">
                    <i class="fas fa-gear"></i> <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                    <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <main id="main-content">
        <section class="top-navbar">
            <div>
                <h1 class="page-title">Detail Angket</h1>
                <p class="page-subtitle"><?= esc($angket['judul_angket'] ?? 'Angket Evaluasi'); ?></p>
            </div>
            <div class="admin-profile">
                <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                <div class="admin-info"><h6><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6><small>Administrator</small></div>
            </div>
        </section>

        <div class="mb-3"><a href="<?= base_url('admin/angket'); ?>" class="btn btn-soft"><i class="fas fa-arrow-left me-2"></i>Kembali</a></div>

        <section class="detail-grid">
            <div class="panel">
                <h2 class="section-title">Informasi Pelatihan</h2>
                <div class="info-grid">
                    <div class="info-card"><div class="info-label">Judul Angket</div><div class="info-value"><?= esc($angket['judul_angket'] ?? '-'); ?></div></div>
                    <div class="info-card"><div class="info-label">Nama Mentor</div><div class="info-value"><?= esc($angket['nama_mentor'] ?? '-'); ?></div></div>
                    <div class="info-card"><div class="info-label">Nama Kelas</div><div class="info-value"><?= esc($angket['nama_kelas'] ?? '-'); ?></div></div>
                    <div class="info-card"><div class="info-label">Tempat Pelatihan</div><div class="info-value"><?= esc($angket['tempat_pelatihan'] ?? '-'); ?></div></div>
                    <div class="info-card"><div class="info-label">Tanggal Pelatihan</div><div class="info-value"><?= esc($tanggalPelatihan); ?></div></div>
                    <div class="info-card"><div class="info-label">Responden</div><div class="info-value"><?= number_format((int) ($angket['jumlah_responden'] ?? 0)); ?> peserta</div></div>
                </div>
            </div>

            <aside class="score-stack">
                <div class="avg-card">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div><div class="small opacity-75 fw-bold mb-2">Rata-rata Nilai</div><div class="avg-value"><?= number_format((float) ($angket['rata_rata'] ?? 0), 2); ?>/5</div></div>
                        <i class="fas fa-star fa-2x opacity-75"></i>
                    </div>
                    <div class="rating-stars mt-3"><?= rating_stars_admin_angket($angket['rata_rata'] ?? 0); ?></div>
                </div>
                <div class="score-card"><div class="score-head"><span class="score-label">Instruktur 1</span><span class="score-number"><?= number_format((float) ($angket['nilai_instruktur_1'] ?? 0), 1); ?>/5</span></div><div class="rating-stars"><?= rating_stars_admin_angket($angket['nilai_instruktur_1'] ?? 0); ?></div></div>
                <div class="score-card"><div class="score-head"><span class="score-label">Instruktur 2</span><span class="score-number"><?= number_format((float) ($angket['nilai_instruktur_2'] ?? 0), 1); ?>/5</span></div><div class="rating-stars"><?= rating_stars_admin_angket($angket['nilai_instruktur_2'] ?? 0); ?></div></div>
                <div class="score-card"><div class="score-head"><span class="score-label">Tempat Pelatihan</span><span class="score-number"><?= number_format((float) ($angket['nilai_tempat'] ?? 0), 1); ?>/5</span></div><div class="rating-stars"><?= rating_stars_admin_angket($angket['nilai_tempat'] ?? 0); ?></div></div>
            </aside>
        </section>

        <section class="panel">
            <h2 class="section-title">Kumpulan Saran Peserta</h2>
            <?php if (!empty($saranPeserta)) : ?>
                <?php foreach ($saranPeserta as $saran): ?>
                    <article class="comment-card">
                        <div class="comment-top">
                            <div>
                                <div class="comment-name"><?= esc($saran['nama_peserta'] ?? 'Peserta'); ?></div>
                                <div class="muted small"><?= !empty($saran['created_at']) ? esc(date('d M Y', strtotime($saran['created_at']))) : 'Tanpa tanggal'; ?></div>
                            </div>
                            <?php if (isset($saran['rating'])) : ?>
                                <div><span class="rating-stars"><?= rating_stars_admin_angket($saran['rating']); ?></span> <strong><?= number_format((float) $saran['rating'], 1); ?>/5</strong></div>
                            <?php endif; ?>
                        </div>
                        <div><?= esc($saran['saran'] ?? '-'); ?></div>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="empty-state"><i class="fas fa-comment-slash fa-2x mb-3"></i><div>Belum ada saran atau masukan peserta untuk angket ini.</div></div>
            <?php endif; ?>
        </section>

        <section class="panel">
            <h2 class="section-title">Pertanyaan Angket</h2>
            <?php if (!empty($semua_pertanyaan)) : ?>
                <ul class="question-list">
                    <?php $no = 1; foreach ($semua_pertanyaan as $row) : ?>

    <?php
        $opsi = [];

        if (!empty($row['opsi_jawaban'])) {
            $decodedOpsi = json_decode($row['opsi_jawaban'], true);

            if (is_array($decodedOpsi)) {
                $opsi = $decodedOpsi;
            }
        }

        $jenisJawaban = $row['tipe'] ?? 'rating';

        $labelJenis = match ($jenisJawaban) {
            'pilihan' => 'Pilihan Ganda',
            'rating'  => 'Skala Penilaian',
            'essay'   => 'Jawaban Teks',
            default   => ucfirst($jenisJawaban)
        };
    ?>

    <li class="question-item">

        <div class="question-number">
            <?= $no++; ?>
        </div>

        <div>

            <div class="question-category">
                <?= esc($row['kategori'] ?? 'Umum'); ?>
            </div>

            <div class="fw-semibold mb-2">
                <?= esc($row['pertanyaan'] ?? '-'); ?>
            </div>

            <div class="small text-muted mb-2">
                <strong>Jenis Jawaban:</strong>
                <?= esc($labelJenis); ?>
            </div>

            <?php if ($jenisJawaban === 'pilihan' && !empty($opsi)) : ?>

                <div class="mt-2">

                    <div class="small fw-bold text-muted mb-2">
                        Pilihan Jawaban:
                    </div>

                    <div class="d-flex flex-column gap-2">

                        <?php foreach ($opsi as $pilihan) : ?>

                            <div class="border rounded px-3 py-2 bg-white">
                                <i class="far fa-circle me-2 text-muted"></i>
                                <?= esc($pilihan); ?>
                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </li>

<?php endforeach; ?>
                </ul>
            <?php else : ?>
                <div class="empty-state"><i class="fas fa-list-check fa-2x mb-3"></i><div>Tidak ada pertanyaan ditemukan.</div></div>
            <?php endif; ?>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
