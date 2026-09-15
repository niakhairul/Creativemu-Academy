<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?= esc($title ?? 'Pilih Kelas Pelatihan Tambahan - Creativemu Academy') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-purple: #7a3ff2;
            --dark-purple: #5b3fd6;
            --deep-purple: #4a2fc9;
            --light-purple: #efeaff;
            --purple-card-bg: #faf8ff;
            --purple-border: #dfd2f7;
            --bg-body: #f8f6ff;
            --text-main: #20143f;
            --text-muted: #64567d;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-body); 
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .app-wrapper { 
            display: flex; 
            min-height: 100vh; 
        }

        /* Sidebar Styling */
        .sidebar { 
            width: 270px; 
            background:
                radial-gradient(circle at 15% 12%, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0) 45%),
                linear-gradient(165deg, #4a2fc9 0%, #7440e6 32%, #9257f2 60%, #b678f5 100%);
            color: white; 
            position: fixed; 
            top: 0; 
            bottom: 0; 
            left: 0; 
            padding: 24px; 
            z-index: 100; 
            box-shadow: 4px 0 25px rgba(116, 64, 230, 0.25);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-brand { 
            font-size: 1.35rem; 
            font-weight: 800; 
            color: white; 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            padding-bottom: 20px; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.15); 
            margin-bottom: 24px; 
            letter-spacing: -0.5px;
        }

        .sidebar-menu { 
            list-style: none; 
            padding: 0; 
            margin: 0; 
        }

        .sidebar-menu li { 
            margin-bottom: 10px; 
        }

        .sidebar-menu a { 
            display: flex; 
            align-items: center; 
            color: rgba(255, 255, 255, 0.82); 
            text-decoration: none; 
            padding: 12px 16px; 
            border-radius: 14px; 
            font-weight: 600; 
            transition: all 0.3s ease; 
        }

        .sidebar-menu a:hover, .sidebar-menu a.active { 
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.28), rgba(255, 255, 255, 0.12));
            color: white; 
            transform: translateX(6px); 
            box-shadow: 0 6px 18px rgba(20, 5, 60, 0.25), inset 3px 0 0 #ffd166;
        }

        .sidebar-menu a i { 
            font-size: 1.25rem; 
            margin-right: 14px; 
        }

        /* Main Content Styling */
        .main-content { 
            flex: 1; 
            margin-left: 270px; 
            padding: 40px; 
            box-sizing: border-box;
            width: calc(100% - 270px);
        }

        /* Header Banner Card */
        .header-banner {
            background: linear-gradient(135deg, var(--dark-purple) 0%, var(--primary-purple) 100%);
            border-radius: 24px;
            color: white;
            padding: 34px 38px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(124, 92, 250, 0.2);
            margin-bottom: 30px;
        }

        .header-banner::after {
            content: '';
            position: absolute;
            right: -30px;
            bottom: -50px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
            pointer-events: none;
        }

        /* Nav Back Button */
        .btn-back-link {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            color: #ffffff;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 7px 16px;
            border-radius: 50rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 14px;
            transition: all 0.2s ease;
        }

        .btn-back-link:hover {
            background: #ffffff;
            color: var(--dark-purple);
            transform: translateX(-3px);
        }

        /* Course Card Modern Styling */
        .course-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            transition: all 0.28s ease;
            box-shadow: 0 6px 20px rgba(124, 92, 250, 0.06);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1.5px solid var(--purple-border);
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 36px rgba(124, 92, 250, 0.16);
            border-color: #bfa5f2;
        }

        .card-img-wrapper {
            position: relative;
            overflow: hidden;
            height: 195px;
            background-color: #f1edfa;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .course-card:hover .card-img-top {
            transform: scale(1.06);
        }

        .card-img-overlay-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .custom-badge {
            background: rgba(30, 20, 63, 0.84);
            backdrop-filter: blur(6px);
            color: white;
            font-weight: 600;
            font-size: 0.74rem;
            padding: 5px 12px;
            border-radius: 30px;
            letter-spacing: 0.3px;
        }

        .badge-category {
            background: var(--light-purple);
            color: var(--primary-purple);
            font-weight: 700;
        }

        .badge-status-aktif {
            position: absolute;
            top: 14px;
            right: 14px;
            background: rgba(16, 185, 129, 0.9);
            backdrop-filter: blur(6px);
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 30px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 0.88rem;
        }

        .info-item i {
            font-size: 1.05rem;
            margin-right: 10px;
            margin-top: 2px;
            flex-shrink: 0;
            color: var(--primary-purple);
        }

        /* Price Box */
        .price-box {
            background: #faf8fe;
            border: 1px solid var(--purple-border);
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }

        /* Action Button */
        .btn-pilih-kelas {
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            padding: 12px 18px;
            font-weight: 700;
            font-size: 0.96rem;
            letter-spacing: 0.2px;
            transition: all 0.25s ease;
            box-shadow: 0 6px 16px rgba(124, 92, 250, 0.28);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-pilih-kelas:hover {
            background: linear-gradient(135deg, var(--dark-purple) 0%, var(--deep-purple) 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(124, 92, 250, 0.38);
        }

        /* Empty State */
        .empty-state-card {
            background: white;
            border-radius: 24px;
            border: 2px dashed rgba(122, 63, 242, 0.25);
            padding: 60px 24px;
        }

        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar { 
                display: none; 
            }
            .main-content { 
                margin-left: 0; 
                padding: 20px 16px; 
                width: 100%; 
            }
            .header-banner { 
                padding: 26px 20px; 
                border-radius: 18px; 
            }
        }

        @media (max-width: 575.98px) {
            .header-banner {
                padding: 22px 16px;
            }
            .header-banner h1 {
                font-size: 1.6rem !important;
            }
        }
    </style>
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar Peserta -->
    <nav class="sidebar">
        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-purple-light"></i> Creativemu
        </a>
        <ul class="sidebar-menu">
            <li><a href="<?= base_url('peserta/dashboard') ?>"><i class="bi bi-grid-fill"></i> Dashboard</a></li>
            <li><a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>" class="active"><i class="bi bi-journals"></i> Daftar Kelas Saya</a></li>
            <li><a href="<?= base_url('pelatihan/kbm') ?>"><i class="bi bi-mortarboard-fill"></i> KBM</a></li>
            <li><a href="<?= base_url('pelatihan/pengaturan') ?>"><i class="bi bi-gear-fill"></i> Pengaturan</a></li>
            <li class="mt-5"><a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a></li>
        </ul>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="container-fluid px-0">
            
            <!-- Hero Header Banner -->
            <div class="header-banner">
                <div class="position-relative" style="z-index: 2;">
                    <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>" class="btn-back-link">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kelas Saya
                    </a>
                    
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-1">
                        <div>
                            <span class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 0.8rem;">
                                <i class="bi bi-plus-circle-fill me-1"></i> Penambahan Kelas Pelatihan
                            </span>
                            <h1 class="fw-extrabold mb-1" style="font-size: 2.1rem; font-weight: 800; color: #ffffff;">
                                Pilih Kelas Pelatihan Tambahan
                            </h1>
                            <p class="mb-0 text-white-50" style="font-size: 1rem; max-width: 720px;">
                                Silakan pilih kelas baru yang ingin Anda ikuti. Kelas yang sudah Anda ikuti otomatis tidak ditampilkan dalam daftar ini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger flex-shrink-0"></i>
                    <div><?= session()->getFlashdata('error') ?></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Grid Daftar Kelas yang Sedang Dibuka (3 Kolom Desktop, 2 Tablet, 1 Mobile) -->
            <div class="row g-4">
                <?php if (!empty($kelas) && is_array($kelas)): ?>
                    <?php foreach ($kelas as $k): ?>
                        <?php 
                            $fotoPelatihan = !empty($k['thumbnail']) && file_exists(FCPATH . 'uploads/kelas/' . $k['thumbnail']) 
                                ? base_url('uploads/kelas/' . $k['thumbnail']) 
                                : base_url('assets/img/default-class.jpg');

                            $isOnline = strtolower($k['tipe_kelas'] ?? '') === 'online';
                            $lokasi = $isOnline ? 'Online (Virtual Class)' : (!empty($k['lokasi_media']) && $k['lokasi_media'] !== '-' ? $k['lokasi_media'] : 'Kantor Pusat Yogyakarta');
                        ?>
                        <div class="col-xl-4 col-md-6 col-12">
                            <div class="course-card">
                                
                                <!-- Foto / Thumbnail Kelas -->
                                <div class="card-img-wrapper">
                                    <img src="<?= $fotoPelatihan ?>" class="card-img-top" alt="<?= esc($k['nama_kelas']) ?>" onerror="this.onerror=null; this.src='<?= base_url('assets/img/default-class.jpg') ?>';">
                                    
                                    <div class="card-img-overlay-badge">
                                        <span class="custom-badge badge-category">
                                            <i class="bi bi-tag-fill me-1"></i> <?= esc($k['kategori'] ?? 'Pelatihan') ?>
                                        </span>
                                        <span class="custom-badge">
                                            <i class="bi <?= $isOnline ? 'bi-broadcast' : 'bi-building' ?> me-1"></i>
                                            <?= esc(ucfirst($k['tipe_kelas'] ?? 'Offline')) ?>
                                        </span>
                                    </div>

                                    <span class="badge-status-aktif">
                                        <i class="bi bi-check-circle-fill me-1"></i> Dibuka
                                    </span>
                                </div>

                                <!-- Konten Card Body -->
                                <div class="card-body p-4 d-flex flex-column">
                                    <h4 class="fw-bold mb-3" style="color: var(--dark-purple); font-size: 1.22rem; line-height: 1.35;">
                                        <?= esc($k['nama_kelas']) ?>
                                    </h4>

                                    <div class="mb-3 flex-grow-1">
                                        <!-- Mentor -->
                                        <div class="info-item">
                                            <i class="bi bi-person-badge-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.74rem;">Mentor Pengampu</span>
                                                <strong class="text-dark"><?= esc($k['nama_mentor'] ?? 'Mentor Ahli') ?></strong>
                                            </div>
                                        </div>

                                        <!-- Tempat / Kota Pelatihan -->
                                        <div class="info-item">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.74rem;">Kota / Tempat Pelatihan</span>
                                                <strong class="text-dark"><?= esc($lokasi) ?></strong>
                                            </div>
                                        </div>

                                        <!-- Metode Pembelajaran -->
                                        <div class="info-item">
                                            <i class="bi bi-laptop-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.74rem;">Metode Pembelajaran</span>
                                                <strong class="text-dark text-capitalize"><?= esc($k['tipe_kelas'] ?? 'Offline') ?></strong>
                                            </div>
                                        </div>

                                        <!-- Tanggal Mulai -->
                                        <div class="info-item">
                                            <i class="bi bi-calendar-check-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.74rem;">Mulai Pelatihan</span>
                                                <strong class="text-dark"><?= esc($k['tanggal_mulai_kelas'] ?? 'Sesuai Jadwal') ?></strong>
                                            </div>
                                        </div>

                                        <!-- Jumlah Pertemuan -->
                                        <div class="info-item mb-0">
                                            <i class="bi bi-layers-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.74rem;">Jumlah Pertemuan</span>
                                                <strong class="text-dark"><?= esc($k['jumlah_pertemuan'] ?? '6') ?> Sesi Pelatihan</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kotak Harga Investasi -->
                                    <div class="price-box">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="text-muted" style="font-size: 0.76rem;">Reguler:</small>
                                            <strong class="text-success" style="font-size: 0.92rem;">
                                                Rp <?= number_format($k['harga_reguler'] ?? 0, 0, ',', '.') ?>
                                            </strong>
                                        </div>
                                        <?php if (!empty($k['harga_privat'])): ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted" style="font-size: 0.76rem;">Privat:</small>
                                            <strong style="color: var(--primary-purple); font-size: 0.92rem;">
                                                Rp <?= number_format($k['harga_privat'], 0, ',', '.') ?>
                                            </strong>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Tombol Pilih Kelas -->
                                    <a href="<?= base_url('pelatihan/pendaftaran/' . $k['id_kelas']) ?>" class="btn btn-pilih-kelas w-100">
                                        <span>Pilih Kelas Ini</span>
                                        <i class="bi bi-arrow-right-circle-fill ms-2 fs-5"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- State Jika Semua Kelas Sudah Diambil / Belum Ada Kelas Buka -->
                    <div class="col-12">
                        <div class="empty-state-card text-center">
                            <div class="mb-3">
                                <span class="p-4 rounded-circle bg-light d-inline-block" style="color: var(--primary-purple);">
                                    <i class="bi bi-check-all fs-1"></i>
                                </span>
                            </div>
                            <h4 class="fw-bold" style="color: var(--dark-purple);">Tidak Ada Kelas Tambahan yang Tersedia</h4>
                            <p class="text-muted mb-4 mx-auto" style="max-width: 480px;">
                                Anda telah terdaftar di seluruh program kelas pelatihan aktif yang sedang dibuka saat ini, atau belum ada program kelas baru yang dibuka oleh pengelola.
                            </p>
                            <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>" class="btn btn-pilih-kelas px-4 py-2 d-inline-flex">
                                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Kelas Saya
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
