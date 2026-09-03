<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas Saya - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #7c3aed;
            --dark-purple: #581c87;
            --deep-purple: #2e1065;
            --light-purple: #f3e8ff;
            --bg-body: #faf5ff;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-body); 
            color: #334155;
        }

        .app-wrapper { 
            display: flex; 
            min-height: 100vh; 
        }

        /* Sidebar Styling */
        .sidebar { 
            width: 270px; 
            background: linear-gradient(180deg, var(--dark-purple) 0%, var(--primary-purple) 100%); 
            color: white; 
            position: fixed; 
            top: 0; 
            bottom: 0; 
            left: 0; 
            padding: 24px; 
            z-index: 100; 
            box-shadow: 4px 0 25px rgba(124, 58, 237, 0.1);
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
            color: rgba(255, 255, 255, 0.8); 
            text-decoration: none; 
            padding: 12px 16px; 
            border-radius: 14px; 
            font-weight: 600; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        .sidebar-menu a:hover, .sidebar-menu a.active { 
            background: rgba(255, 255, 255, 0.15); 
            color: white; 
            transform: translateX(6px); 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
        }

        /* Hero Header Card */
        .header-banner {
            background: linear-gradient(135deg, var(--dark-purple) 0%, var(--primary-purple) 100%);
            border-radius: 24px;
            color: white;
            padding: 35px 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.2);
        }

        .header-banner::after {
            content: '';
            position: absolute;
            right: -30px;
            bottom: -50px;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        /* Course Card Modern Styling */
        .course-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid rgba(124, 58, 237, 0.08);
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(124, 58, 237, 0.15);
            border-color: rgba(124, 58, 237, 0.3);
        }

        .card-img-wrapper {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .course-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-img-overlay-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .custom-badge {
            background: rgba(46, 16, 101, 0.85);
            backdrop-filter: blur(4px);
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 30px;
            letter-spacing: 0.3px;
        }

        .badge-category {
            background: var(--light-purple);
            color: var(--primary-purple);
            font-weight: 700;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 12px;
            font-size: 0.875rem;
        }

        .info-item i {
            font-size: 1rem;
            margin-right: 10px;
            margin-top: 1px;
            flex-shrink: 0;
            color: var(--primary-purple);
        }

        /* Button Styling */
        .btn-kbm {
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .btn-kbm:hover {
            background: linear-gradient(135deg, var(--dark-purple) 0%, var(--deep-purple) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(124, 58, 237, 0.35);
        }

        /* Empty State */
        .empty-state-card {
            background: white;
            border-radius: 24px;
            border: 2px dashed rgba(124, 58, 237, 0.2);
            padding: 60px 20px;
        }
    </style>
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="#" class="sidebar-brand">
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid px-0">
            
            <!-- Hero Header Banner -->
            <div class="header-banner mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 0.8rem;">
                            <i class="bi bi-journal-check me-1"></i> Area Pembelajaran Aktif
                        </span>
                        <h1 class="fw-extrabold mb-2" style="font-size: 2.2rem; font-weight: 800; color: #ffffff;">Daftar Kelas Saya</h1>
                        <p class="mb-0 text-white-50" style="font-size: 1.05rem;">Kelola, pantau, dan akses kelas pelatihan interaktif yang sedang Anda ikuti di Creativemu Academy.</p>
                    </div>
                </div>
            </div>

            <!-- List Card Kelas -->
            <div class="row g-4">
                <?php if (!empty($kelas) && is_array($kelas)): ?>
                    <?php foreach ($kelas as $k): ?>
                        <div class="col-xl-4 col-md-6">
                            <div class="course-card">
                                
                                <!-- Foto / Thumbnail Kelas dengan Efek & Badge Overlay -->
                                <div class="card-img-wrapper">
                                    <?php 
                                        $fotoPelatihan = !empty($k['thumbnail']) && file_exists(FCPATH . 'uploads/kelas/' . $k['thumbnail']) 
                                            ? base_url('uploads/kelas/' . $k['thumbnail']) 
                                            : (!empty($k['pas_foto']) ? base_url('uploads/foto/' . $k['pas_foto']) : 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=600&auto=format&fit=crop'); 
                                    ?>
                                    <img src="<?= $fotoPelatihan ?>" class="card-img-top" alt="Thumbnail Kelas">
                                    
                                    <div class="card-img-overlay-badge">
                                        <span class="custom-badge badge-category">
                                            <i class="bi bi-tag-fill me-1"></i> <?= esc($k['kategori_kelas'] ?? 'Umum') ?>
                                        </span>
                                        <span class="custom-badge">
                                            <?= esc($k['jenis_kelas'] ?? 'Reguler') ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Card Body Content -->
                                <div class="card-body p-4 d-flex flex-column">
                                    <h4 class="fw-bold mb-3" style="color: var(--deep-purple); font-size: 1.25rem; line-height: 1.4;">
                                        <?= esc($k['nama_kelas'] ?? $k['pilihan_pelatihan']) ?>
                                    </h4>

                                    <div class="mb-4 flex-grow-1">
                                        <!-- Mentor -->
                                        <div class="info-item">
                                            <i class="bi bi-person-badge-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.75rem;">Mentor Pengampu</span>
                                                <strong class="text-dark"><?= esc($k['nama_mentor'] ?? 'Belum Ditentukan') ?></strong>
                                            </div>
                                        </div>

                                        <!-- Lokasi -->
                                        <div class="info-item">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.75rem;">Tempat / Lokasi Pelatihan</span>
                                                <strong class="text-dark"><?= esc($k['lokasi_pelatihan'] ?? '-') ?></strong>
                                            </div>
                                        </div>

                                        <!-- Metode -->
                                        <div class="info-item">
                                            <i class="bi bi-laptop-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.75rem;">Metode Pembelajaran</span>
                                                <strong class="text-dark text-capitalize"><?= esc($k['metode_pembelajaran'] ?? '-') ?></strong>
                                            </div>
                                        </div>

                                        <!-- Tanggal Mulai -->
                                        <div class="info-item mb-0">
                                            <i class="bi bi-calendar-check-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.75rem;">Mulai Pelatihan</span>
                                                <strong class="text-dark"><?= esc($k['tanggal_mulai_kelas'] ?? '-') ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="mt-auto pt-2">
                                        <a href="<?= base_url('pelatihan/kbm') ?>" class="btn btn-kbm w-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-mortarboard-fill me-2 fs-5"></i> Masuk Ruang KBM
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty State Modern -->
                    <div class="col-12">
                        <div class="empty-state-card text-center">
                            <div class="mb-3">
                                <span class="p-4 rounded-circle bg-light d-inline-block text-purple" style="color: var(--primary-purple);">
                                    <i class="bi bi-journal-x fs-1"></i>
                                </span>
                            </div>
                            <h4 class="fw-bold" style="color: var(--deep-purple);">Belum Ada Kelas yang Diambil</h4>
                            <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">Anda belum terdaftar di kelas pelatihan apapun. Silakan pilih kelas terlebih dahulu untuk mulai belajar.</p>
                            <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-kbm px-5 py-3 rounded-pill d-inline-flex align-items-center">
                                <i class="bi bi-plus-circle-fill me-2"></i> Pilih Kelas Sekarang
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