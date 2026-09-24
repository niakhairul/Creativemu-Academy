<?php
$filters = $filters ?? ['search' => '', 'mentor' => '', 'kelas' => '', 'tanggal' => ''];
$summary = $summary ?? ['total_angket' => 0, 'total_responden' => 0, 'rata_rata' => 0, 'kepuasan' => 0];

if (!function_exists('rating_stars_admin_angket')) {
    function rating_stars_admin_angket($nilai): string
    {
        $nilai = max(0, min(4, (float) $nilai));
        $full = (int) round($nilai);
        return str_repeat('&#9733;', $full) . str_repeat('&#9734;', 4 - $full);
    }
}
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
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --dark-purple: #1e0f33;
            --light-purple: #f4f0fc;
            --soft-border: #eadffb;
            --muted-text: #817796;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Poppins', sans-serif; background: #f7f5fd; color: #2f2442; overflow-x: hidden; }
        #sidebar { width: 275px; height: 100vh; position: fixed; inset: 0 auto 0 0; background: var(--sidebar-bg); color: var(--sidebar-text); z-index: 1000; overflow-y: auto; box-shadow: 8px 0 28px rgba(34, 19, 60, .12); }
        #sidebar .sidebar-header { padding: 22px 18px; background: rgba(0,0,0,.22); text-align: center; }
        #sidebar .sidebar-header img { width: 230px; max-width: 100%; height: 92px; object-fit: cover; border-radius: 10px; }
        #sidebar .nav { padding: 18px 12px 28px; }
        #sidebar .nav-link { color: var(--sidebar-text); padding: 12px 16px; display: flex; align-items: center; gap: 12px; border-radius: 12px; margin-bottom: 6px; font-weight: 500; font-size: .92rem; transition: .2s ease; }
        #sidebar .nav-link i { width: 21px; text-align: center; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background: var(--sidebar-active-gradient); color: #fff; box-shadow: 0 8px 20px rgba(121,75,196,.28); }
        #main-content { margin-left: 275px; padding: 32px; min-height: 100vh; }
        .top-navbar, .panel, .metric-card, .survey-mobile-card { background: #fff; border: 1px solid rgba(121,75,196,.08); box-shadow: 0 14px 34px rgba(64,36,105,.06); }
        .top-navbar { border-radius: 18px; padding: 22px 26px; display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 24px; }
        .page-title { font-size: clamp(1.25rem, 2vw, 1.75rem); color: var(--dark-purple); font-weight: 800; margin: 0; }
        .page-subtitle { color: var(--muted-text); margin: 6px 0 0; font-size: .92rem; }
        .admin-profile { display: flex; align-items: center; gap: 12px; min-width: max-content; }
        .admin-profile img { width: 46px; height: 46px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-purple); }
        .admin-info h6 { margin: 0; color: var(--dark-purple); font-weight: 700; font-size: .92rem; }
        .admin-info small { color: var(--muted-text); }
        .metrics-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 20px; }
        .metric-card { border-radius: 16px; padding: 18px; position: relative; overflow: hidden; }
        .metric-card:after { content: ''; position: absolute; right: -34px; top: -40px; width: 100px; height: 100px; border-radius: 50%; background: rgba(121,75,196,.09); }
        .metric-icon { width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; color: #fff; background: var(--sidebar-active-gradient); margin-bottom: 13px; }
        .metric-label { color: var(--muted-text); font-size: .82rem; font-weight: 600; margin-bottom: 4px; }
        .metric-value { color: var(--dark-purple); font-size: 1.55rem; font-weight: 800; line-height: 1.1; }
        .panel { border-radius: 18px; padding: 22px; margin-bottom: 20px; }
        .filter-grid { display: grid; grid-template-columns: 1.3fr 1fr 1fr .85fr auto; gap: 12px; align-items: end; }
        .form-label { color: var(--dark-purple); font-size: .78rem; font-weight: 700; }
        .form-control, .form-select { border-radius: 12px; border-color: var(--soft-border); min-height: 44px; font-size: .9rem; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-purple); box-shadow: 0 0 0 .2rem rgba(121,75,196,.15); }
        .btn-purple { background: var(--sidebar-active-gradient); color: #fff; border: none; border-radius: 12px; min-height: 44px; padding: 10px 16px; font-weight: 700; }
        .btn-purple:hover { color: #fff; filter: brightness(.98); transform: translateY(-1px); }
        .btn-soft { background: var(--light-purple); color: var(--primary-purple); border: 1px solid var(--soft-border); border-radius: 12px; min-height: 44px; padding: 10px 15px; font-weight: 700; }
        .table-wrap { overflow-x: auto; }
        .table { margin: 0; vertical-align: middle; }
        .table thead th { background: #faf8ff; color: #5931a0; border-bottom: 1px solid var(--soft-border); padding: 14px 16px; font-size: .82rem; text-transform: uppercase; letter-spacing: .02em; white-space: nowrap; }
        .table tbody td { padding: 16px; border-bottom: 1px solid #f0eafb; color: #443652; }
        .survey-title { color: var(--dark-purple); font-weight: 800; margin-bottom: 4px; }
        .meta-text { color: var(--muted-text); font-size: .84rem; }
        .rating-stars { color: #f5b301; letter-spacing: 1px; white-space: nowrap; font-size: 1.02rem; }
        .rating-score { color: var(--dark-purple); font-weight: 800; }
        .empty-state { text-align: center; padding: 48px 18px; color: var(--muted-text); }
        .mobile-list { display: none; }
        .survey-mobile-card { border-radius: 16px; padding: 17px; margin-bottom: 14px; }
        .mobile-row { display: flex; justify-content: space-between; gap: 12px; border-top: 1px solid #f0eafb; padding-top: 10px; margin-top: 10px; }
        .mobile-row span:first-child { color: var(--muted-text); font-size: .78rem; font-weight: 700; }
        .mobile-row span:last-child { text-align: right; font-weight: 600; color: var(--dark-purple); }
        @media (max-width: 1100px) { .metrics-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .filter-actions { grid-column: 1 / -1; display: flex; gap: 10px; } }
        @media (max-width: 768px) {
    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 275px;
        height: 100vh;
        z-index: 1100;
        display: none;
        overflow-y: auto;
    }

    #sidebar.show {
        display: block;
    }

    #sidebar .sidebar-header {
        padding: 14px;
    }

    #sidebar .sidebar-header img {
        width: 190px;
        height: 74px;
    }

    #sidebar .nav {
        padding: 12px;
        display: block;
    }

    #sidebar .nav-link {
        margin-bottom: 6px;
        min-height: 46px;
        font-size: .82rem;
        padding: 10px 12px;
    }

    #main-content {
        margin-left: 0;
        padding: 18px;
    }

    .top-navbar {
        align-items: flex-start;
        flex-direction: column;
        padding: 18px;
        border-radius: 14px;
    }

    .admin-profile {
        width: 100%;
    }

    .metrics-grid,
    .filter-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .desktop-table {
        display: none;
    }

    .mobile-list {
        display: block;
    }

    .panel {
        padding: 16px;
        border-radius: 14px;
    }
}
        @media (max-width: 430px) { #sidebar .nav { grid-template-columns: 1fr; } .filter-actions { grid-template-columns: 1fr; } .metric-value { font-size: 1.35rem; } }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
        </div>

        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">
                    <i class="fas fa-chart-pie me-3"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">
                    <i class="fas fa-book me-3"></i> Master Kelas
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/mentor'); ?>" class="nav-link">
                    <i class="fas fa-chalkboard-user me-3"></i> Instruktur
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">
                    <i class="fas fa-users me-3"></i> Data Peserta
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                    <i class="fas fa-clipboard-check me-3"></i> Validasi Pendaftaran
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link">
                    <i class="fas fa-book-open me-3"></i> Buku Induk
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link active">
                    <i class="fas fa-award me-3"></i> Angket
                </a>

                <ul class="nav flex-column ms-3 mt-1">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/hasil_angket'); ?>" class="nav-link py-2" style="font-size: 0.9rem;">
                            <i class="fas fa-poll-h me-2"></i> Hasil Angket
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link">
                    <i class="fas fa-certificate me-3"></i> Sertifikat
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
                    <i class="fas fa-file-lines me-3"></i> Laporan
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link">
                    <i class="fas fa-gear me-3"></i> Pengaturan
                </a>
            </li>

            <li class="nav-item mt-4">
                <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                    <i class="fas fa-right-from-bracket me-3"></i> Logout
                </a>
            </li>
        </ul>
        </ul>
    </nav>

    <main id="main-content">
        <section class="top-navbar">
            <div>
                <h1 class="page-title">Monitoring Angket</h1>
                <p class="page-subtitle">Ringkasan evaluasi instruktur, tempat pelatihan, dan saran peserta.</p>
            </div>
            <div class="admin-profile">
                <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                <div class="admin-info">
                    <h6><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6>
                    <small>Administrator</small>
                </div>
            </div>
        </section>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success border-0 rounded-4"><i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success'); ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger border-0 rounded-4"><i class="fas fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error'); ?></div>
        <?php endif; ?>

        <section class="metrics-grid">
            <div class="metric-card"><div class="metric-icon"><i class="fas fa-clipboard-list"></i></div><div class="metric-label">Jumlah Angket</div><div class="metric-value"><?= number_format((int) $summary['total_angket']); ?></div></div>
            <div class="metric-card"><div class="metric-icon"><i class="fas fa-users"></i></div><div class="metric-label">Responden</div><div class="metric-value"><?= number_format((int) $summary['total_responden']); ?></div></div>
            <div class="metric-card"><div class="metric-icon"><i class="fas fa-star"></i></div><div class="metric-label">Rata-rata Nilai</div><div class="metric-value"><?= number_format((float) $summary['rata_rata'], 2); ?>/4</div></div>
            <div class="metric-card"><div class="metric-icon"><i class="fas fa-chart-line"></i></div><div class="metric-label">Kepuasan</div><div class="metric-value"><?= number_format((float) $summary['kepuasan'], 1); ?>%</div></div>
        </section>

        <section class="panel">

        </section>

        <section class="panel">
            <div class="d-flex justify-content-between align-items-center gap-3 mb-3 flex-wrap">
                <div>
                    <h2 class="h5 fw-bold mb-1" style="color: var(--dark-purple);">Daftar Angket</h2>
                    <div class="meta-text">Judul | Mentor | Kelas | Tempat | Rata-rata | Lihat Detail</div>
                </div>
                <a href="<?= base_url('admin/angket/tambah_angket'); ?>" class="btn btn-purple"><i class="fas fa-plus me-2"></i>Buat Angket</a>
            </div>

            <div class="desktop-table table-wrap">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Pertanyaan</th>
                            <th>Jenis Jawaban</th>
                            <th>Statistik Hasil</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (!empty($angket)) : ?>
        <?php foreach ($angket as $no => $item): ?>
            <tr>
                <!-- No -->
                <td><?= $no + 1; ?></td>

                <!-- Kategori -->
                <td>
                    <span class="badge bg-light text-primary">
                        <?= esc($item['kategori'] ?? '-'); ?>
                    </span>
                </td>

                <!-- Pertanyaan -->
                <td>
                    <strong>
                        <?= esc($item['pertanyaan'] ?? '-'); ?>
                    </strong>
                </td>

                <!-- Jenis Jawaban -->
                <td>
                    <?php
                    $tipe = $item['tipe'] ?? '-';

                    $jenisJawaban = match ($tipe) {
                        'rating' => 'RATING',
                        'pilihan' => 'PILIHAN GANDA',
                        'essay' => 'ESSAY',
                        default => strtoupper((string) $tipe),
                    };
                    ?>

                    <span class="badge bg-light text-dark">
                        <?= esc($jenisJawaban); ?>
                    </span>
                </td>

                <!-- Statistik Hasil -->
                <td>
                    <strong>
                        <?= (int) ($item['jumlah_responden'] ?? 0); ?>
                        Responden
                    </strong>

                    <?php if (($item['tipe'] ?? '') === 'rating'): ?>
                        <br>
                        <span class="rating-stars">
                            <?= rating_stars_admin_angket($item['rata_rata'] ?? 0); ?>
                        </span>
                        <br>
                        <strong>
                            <?= number_format((float) ($item['rata_rata'] ?? 0), 1); ?>/4
                        </strong>
                    <?php endif; ?>
                </td>

                <!-- Status -->
                <td>
                    <?php $status = $item['status'] ?? 'aktif'; ?>

                    <span class="badge bg-success">
                        <?= esc(strtoupper((string) $status)); ?>
                    </span>
                </td>

                <!-- Aksi -->
                <td class="text-end">
    <div class="d-flex justify-content-end gap-2">
        <a href="<?= base_url('admin/angket/edit/' . ($item['id_angket_pertanyaan'] ?? 0)); ?>"
           class="btn btn-soft">
            <i class="fas fa-edit me-1"></i>Edit
        </a>

        <a href="<?= base_url('admin/angket/delete/' . ($item['id_angket_pertanyaan'] ?? 0)); ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Apakah Anda yakin ingin menghapus angket ini?');">
            <i class="fas fa-trash me-1"></i>Delete
        </a>
    </div>
</td>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="7">
                <div class="empty-state">
                    <i class="fas fa-inbox fa-2x mb-3"></i>
                    <div>Belum ada data angket sesuai filter.</div>
                </div>
            </td>
        </tr>
    <?php endif; ?>
</tbody>
                </table>
            </div>

            <div class="mobile-list">
                <?php if (!empty($angket)) : ?>
                    <?php foreach ($angket as $item): ?>
                        <article class="survey-mobile-card">
                            <div class="survey-title"><?= esc($item['judul_angket'] ?? 'Angket Evaluasi'); ?></div>
                            <div class="meta-text mb-2"><?= (int) ($item['jumlah_responden'] ?? 0); ?> responden</div>
                            <div class="mobile-row"><span>Mentor</span><span><?= esc($item['nama_mentor'] ?? '-'); ?></span></div>
                            <div class="mobile-row"><span>Kelas</span><span><?= esc($item['nama_kelas'] ?? '-'); ?></span></div>
                            <div class="mobile-row"><span>Tempat</span><span><?= esc($item['tempat_pelatihan'] ?? '-'); ?></span></div>
                            <?php if (($item['tipe'] ?? '') === 'rating'): ?>
                            <div class="mobile-row"><span>Rata-rata</span><span><span class="rating-stars"><?= rating_stars_admin_angket($item['rata_rata'] ?? 0); ?></span> <?= number_format((float) ($item['rata_rata'] ?? 0), 1); ?>/4</span></div>
                            <?php endif; ?>
                            <a href="<?= base_url('admin/angket/detail/' . ($item['id_angket_pertanyaan'] ?? 0)); ?>" class="btn btn-purple w-100 mt-3"><i class="fas fa-eye me-2"></i>Lihat Detail</a>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="empty-state"><i class="fas fa-inbox fa-2x mb-3"></i><div>Belum ada data angket sesuai filter.</div></div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
