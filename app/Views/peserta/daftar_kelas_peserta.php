<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas Saya - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Latar Belakang Utama: Ungu Pastel Sangat Muda & Cerah */
            --bg-main: #f4f0ff;
            --primary-purple: #7c3aed;
            --text-main: #2e1065;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }

        /* Pola Titik-Titik (Polka Dot / Dotted Pattern) di Latar Belakang */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(#d8b4fe 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.6;
            z-index: 0;
            pointer-events: none;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        /* Sidebar: Gradasi Ungu yang Disamakan dengan Dashboard */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #581c87 0%, #7c3aed 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px;
            box-shadow: 4px 0 20px rgba(124, 58, 237, 0.1);
            overflow-y: auto;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu a i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        /* Header Kotak Konten */
        .page-header-box {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(216, 180, 254, 0.4);
            border-radius: 24px;
            padding: 2rem 2.5rem;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.04);
        }

        /* Kartu Kelas Putih Bersih dengan Aksen Ungu */
        .custom-card {
            background: #ffffff;
            border: 1px solid rgba(216, 180, 254, 0.5);
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.06);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }

        .custom-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #c084fc, #7c3aed);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .custom-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.15);
            border-color: #a855f7;
        }

        .custom-card:hover::before {
            opacity: 1;
        }

        /* Thumbnail Gambar Kelas di Kartu */
        .card-img-top-wrapper {
            position: relative;
            height: 160px;
            overflow: hidden;
            background-color: #f3e8ff;
        }

        .card-img-top-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .custom-card:hover .card-img-top-wrapper img {
            transform: scale(1.05);
        }

        /* Badge Kategori */
        .badge-category {
            background-color: #f3e8ff;
            color: #7c3aed;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 50px;
        }

        /* Tombol Utama Ungu */
        .btn-custom-primary {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: #fff;
            border-radius: 50px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.3);
            transition: all 0.3s ease;
        }

        .btn-custom-primary:hover {
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: #fff;
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.5);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- SIDEBAR -->
    <nav class="sidebar">
        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4"></i> Creativemu
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('peserta/dashboard') ?>"><i class="bi bi-grid-fill"></i> Dashboard</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/daftar-kelas') ?>"><i class="bi bi-journals"></i> Daftar Kelas</a>
            </li>
            <li>
                <a href="#"><i class="bi bi-calendar-check"></i> Absensi</a>
            </li>
            <li>
                <a href="#"><i class="bi bi-journal-text"></i> Tugas</a>
            </li>
            <li>
                <a href="#"><i class="bi bi-award"></i> Sertifikat</a>
            </li>
            <li>
                <a href="#"><i class="bi bi-person-badge"></i> Profil Saya</a>
            </li>
            <li class="mt-5">
                <a href="<?= base_url('auth/logout') ?>" class="text-danger-subtle bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </li>
        </ul>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header Halaman -->
            <div class="page-header-box mb-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1" style="color: #2e1065;">Daftar Kelas Saya</h2>
                    <p class="text-muted mb-0">Pantau status pendaftaran serta akses ruang belajar pelatihan Anda dengan mudah.</p>
                </div>
                <div>
                    <span class="badge badge-category fs-6 py-2 px-4 shadow-sm border border-purple border-opacity-10">
                        <i class="bi bi-journal-bookmark-fill me-1"></i> Total Diikuti: <?= count($kelas ?? []) ?> Kelas
                    </span>
                </div>
            </div>

            <!-- Notifikasi Flash -->
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 text-success bg-white" role="alert" style="border-left: 5px solid #10b981 !important;">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Grid Kartu Kelas -->
            <div class="row">
                <?php if (empty($kelas)): ?>
                    <div class="col-12">
                        <div class="custom-card text-center py-5">
                            <div class="card-body py-5">
                                <span class="p-4 rounded-circle d-inline-block mb-3 shadow-sm" style="background: #f3e8ff;">
                                    <i class="bi bi-journal-x fs-1" style="color: #8b5cf6;"></i>
                                </span>
                                <h4 class="fw-bold" style="color: #2e1065;">Belum Ada Kelas Aktif</h4>
                                <p class="text-muted mb-4">Anda belum terdaftar ke dalam kelas pelatihan manapun saat ini.</p>
                                <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-custom-primary shadow-sm">
                                    <i class="bi bi-search me-2"></i> Jelajahi Katalog Kelas
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($kelas as $row): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="custom-card h-100 d-flex flex-column justify-content-between">
                                <!-- Thumbnail Gambar Kelas (Mendukung berbagai nama field database) -->
                                <div class="card-img-top-wrapper">
                                    <?php 
                                        $imgFile = $row['gambar'] ?? $row['foto_kelas'] ?? $row['banner'] ?? '';
                                    ?>
                                    <?php if (!empty($imgFile)): ?>
                                        <img src="<?= base_url('uploads/kelas/' . $imgFile) ?>" alt="<?= esc($row['nama_kelas']) ?>">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/img/default-kelas.jpg') ?>" alt="Default Kelas">
                                    <?php endif; ?>
                                </div>

                                <div class="p-4 d-flex flex-column flex-grow-1 justify-content-between">
                                    <div>
                                        <!-- Header Badge Kategori & NIS / Status -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge badge-category small">
                                                <?= esc($row['kategori_kelas'] ?? $row['kategori'] ?? 'Pelatihan') ?>
                                            </span>
                                            
                                            <?php if (!empty($row['nis'])): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1 rounded-pill small fw-bold">
                                                    NIS: <?= esc($row['nis']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1 rounded-pill small fw-bold">
                                                    Menunggu NIS
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Nama Kelas -->
                                        <h4 class="fw-bold mb-2" style="color: #2e1065;"><?= esc($row['nama_kelas']) ?></h4>
                                        <p class="text-muted small mb-4">
                                            <?php 
                                                $deskripsi = strip_tags($row['deskripsi'] ?? 'Tidak ada deskripsi singkat.');
                                                echo (strlen($deskripsi) > 75) ? substr($deskripsi, 0, 75) . '...' : $deskripsi;
                                            ?>
                                        </p>

                                        <hr class="text-muted opacity-25">

                                        <!-- Informasi Detail (Mentor, Tempat, Metode, Mulai, Status Validasi) -->
                                        <ul class="list-unstyled small text-muted mb-4">
                                            <li class="mb-2">
                                                <i class="bi bi-person-badge me-2 fs-6" style="color: #8b5cf6;"></i> Mentor: 
                                                <strong style="color: #2e1065;"><?= esc($row['nama_mentor'] ?? 'Belum ditentukan') ?></strong>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-laptop me-2 fs-6" style="color: #8b5cf6;"></i> Metode: 
                                                <strong style="color: #2e1065;"><?= esc($row['metode'] ?? 'Disesuaikan') ?></strong>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-geo-alt-fill me-2 fs-6" style="color: #8b5cf6;"></i> Tempat: 
                                                <strong style="color: #2e1065;"><?= esc($row['tempat'] ?? 'Online / Disesuaikan') ?></strong>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-calendar-event me-2 fs-6" style="color: #8b5cf6;"></i> Mulai Kelas: 
                                                <strong style="color: #2e1065;"><?= esc($row['tanggal_mulai_kelas'] ?? '-') ?></strong>
                                            </li>
                                            <li>
                                                <i class="bi bi-shield-check me-2 fs-6" style="color: #8b5cf6;"></i> Status: 
                                                <strong class="text-<?= ((($row['status'] ?? 'Pending') == 'Disetujui')) ? 'success' : 'warning' ?>">
                                                    <?= esc($row['status'] ?? 'Pending') ?>
                                                </strong>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Tombol Aksi (Tombol Masuk / Validasi Admin) -->
                                    <div class="d-grid gap-2">
                                        <?php if (strtolower($row['status'] ?? '') == 'disetujui'): ?>
                                            <a href="<?= base_url('pelatihan/kbm') ?>" class="btn btn-custom-primary shadow-sm">
                                                <i class="bi bi-play-circle-fill me-1"></i> Masuk Ruang Belajar
                                            </a>
                                        <?php else: ?>
            
                                        <?php endif; ?>
                                        <a href="<?= base_url('pelatihan/detail/' . $row['id_kelas']) ?>" class="btn btn-outline-secondary btn-sm rounded-pill border-0 text-muted py-2">
                                            Lihat Detail Kurikulum
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>