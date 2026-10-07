<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KBM Kelas - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* 4 Warna Sesuai Dashboard */
            --color-purple: #7b5af6;
            --color-pink: #df6be0;
            --color-orange: #e2a048;
            --color-cyan: #5ac5e8;
            
            --purple-deep: #5b3fd6;
            --purple-mid: #7b5af6;
            --purple-light: #9a7fff;
            --purple-soft: #f3f0ff;
            --purple-soft2: #e4deff;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(123, 90, 246, 0.4) transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(120deg, #f7f5ff, #f2efff, #faf8ff, #f0ebff);
            background-size: 300% 300%;
            animation: bgFlow 22s ease infinite;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 14px;
            zoom: 1;
        }

        @keyframes bgFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar disamakan persis dengan Dashboard (lebar 220px) */
        .sidebar {
            width: 220px;
            background:
                radial-gradient(circle at 15% 12%, rgba(90, 197, 232, 0.2) 0%, rgba(255, 255, 255, 0) 45%),
                linear-gradient(165deg, #5b3fd6 0%, #7b5af6 35%, #df6be0 75%, #5ac5e8 100%);
            background-size: 200% 200%, 220% 220%;
            animation: sidebarGlow 14s ease infinite;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px 14px;
            box-shadow: 6px 0 34px rgba(123, 90, 246, 0.25);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.035) 0px,
                rgba(255, 255, 255, 0.035) 2px,
                transparent 2px,
                transparent 14px
            );
            pointer-events: none;
        }

        @keyframes sidebarGlow {
            0% { background-position: 0% 0%, 0% 0%; }
            50% { background-position: 100% 100%, 100% 100%; }
            100% { background-position: 0% 0%, 0% 0%; }
        }

        .sidebar-brand {
            font-size: 16px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 9px 12px;
            border-radius: 9px;
            font-weight: 500;
            font-size: 13.5px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(226, 160, 72, 0.4), rgba(90, 197, 232, 0.3));
            box-shadow: 0 6px 18px rgba(20, 5, 60, 0.2), inset 3px 0 0 var(--color-orange);
        }

        .sidebar-menu a i {
            font-size: 15px;
            margin-right: 9px;
        }

        /* Menyesuaikan margin konten utama dengan lebar sidebar (220px) */
        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 24px 28px;
            width: calc(100% - 220px);
        }

        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(123, 90, 246, 0.05);
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(123, 90, 246, 0.12) !important;
        }

        .materi-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(123, 90, 246, 0.15);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(123, 90, 246, 0.04);
        }

        .materi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(123, 90, 246, 0.1);
            border-color: rgba(123, 90, 246, 0.35);
        }

        .materi-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(90, 197, 232, 0.15);
            color: var(--color-cyan);
            font-size: 16px;
        }

        .materi-badge {
            display: inline-block;
            width: fit-content;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(223, 107, 224, 0.15);
            color: var(--color-pink);
            font-size: 11px;
            font-weight: 700;
        }

        /* Nav Tabs disamakan nuansanya dengan Dashboard */
        .nav-tabs {
            border-bottom: none;
            gap: 6px;
        }

        .nav-tabs .nav-link {
            border: 1px solid rgba(123, 90, 246, 0.2);
            color: #64748b;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: var(--color-purple);
            background: var(--purple-soft);
            border-color: rgba(123, 90, 246, 0.4);
        }

        .nav-tabs .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--color-purple) 0%, var(--color-pink) 100%);
            border-color: transparent;
            box-shadow: 0 4px 16px rgba(123, 90, 246, 0.25);
        }

        .exam-status-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(243,240,255,0.6) 100%);
            border: 1px solid rgba(123, 90, 246, 0.15);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .exam-status-card:hover {
            box-shadow: 0 8px 24px rgba(123, 90, 246, 0.1);
            border-color: rgba(123, 90, 246, 0.35);
        }

        .mobile-topbar { display: none; width: 100%; }
        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(15, 7, 35, 0.55); backdrop-filter: blur(4px); z-index: 1040; }
        .sidebar-backdrop.show { display: block; }
        .sidebar-close-btn { 
            display: none; 
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 15px;
        }

        @media (max-width: 991.98px) {
            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #5b3fd6 0%, #7b5af6 100%);
                color: white;
                padding: 12px 16px;
                position: sticky;
                top: 0;
                z-index: 990;
                box-shadow: 0 4px 18px rgba(123, 90, 246, 0.25);
            }
            .app-wrapper { flex-direction: column; }
            .sidebar {
                width: 260px;
                max-width: 82vw;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
            }
            .sidebar.show { transform: translateX(0); }
            .sidebar-close-btn { display: flex; }
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 16px 12px !important;
            }
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 6px;
            }
            .nav-tabs .nav-link { white-space: nowrap; }
        }
    </style>
</head>

<body>

<!-- TOPBAR KHUSUS MOBILE -->
<div class="mobile-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-link text-white p-0 fs-3 text-decoration-none lh-1 shadow-none" id="sidebarToggle" aria-label="Buka Menu">
            <i class="bi bi-list"></i>
        </button>
        <span class="fw-bold fs-6">
            <i class="bi bi-mortarboard-fill me-1" style="color: var(--color-orange);"></i> Creativemu
        </span>
    </div>
    <a href="<?= base_url('pelatihan/pengaturan') ?>" class="text-white text-decoration-none small d-flex align-items-center gap-1 bg-white bg-opacity-20 px-2.5 py-1 rounded-pill" style="font-size: 13px;">
        <i class="bi bi-person-circle"></i> Peserta
    </a>
</div>

<!-- BACKDROP SIDEBAR MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR (DISAMAKAN PERSIS DENGAN DASHBOARD) -->
<nav class="sidebar" id="sidebarMenu">
    <div class="d-flex align-items-start justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-10 position-relative">
        <a href="#" class="sidebar-brand text-decoration-none d-flex flex-column align-items-center w-100 mb-0 pb-0 border-0 text-center">
            <div style="background-color: #ffffff; border-radius: 14px; padding: 10px 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.22); width: 90%; margin: 0 auto 12px auto;">
                <img src="<?= base_url('assets/img/logo_creativemu_admin.png'); ?>" alt="Logo" style="width: 100%; max-width: 180px; height: auto; max-height: 55px; object-fit: contain; display: block;">
            </div>
            <div>
                <span class="fs-6 fw-bold d-block text-white lh-1 mb-1">Creativemu</span>
                <span style="font-size: 9.5px; letter-spacing: 1px; color: var(--color-cyan); font-weight: 600;">ACADEMY</span>
            </div>
        </a>
        <button type="button" class="sidebar-close-btn mt-1 position-absolute end-0" style="top: 0;" id="sidebarClose" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?= base_url('peserta/dashboard') ?>"><i class="bi bi-grid-fill" style="color: var(--color-cyan);"></i> Dashboard</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>"><i class="bi bi-journals" style="color: var(--color-pink);"></i> Daftar Kelas Saya</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/kelas') ?>" class="active"><i class="bi bi-mortarboard-fill" style="color: var(--color-orange);"></i> KBM</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/pengaturan') ?>"><i class="bi bi-gear-fill" style="color: var(--color-cyan);"></i> Pengaturan</a>
        </li>
        <li class="mt-3">
            <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </li>
    </ul>
</nav>

<div class="app-wrapper">
    <div class="main-content">
        <div class="container-fluid py-1">

            <!-- NOTIFIKASI FLASH -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 small" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3 small" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- KARTU INFORMASI KELAS -->
            <div class="card mb-3 shadow-sm hover-card" style="border-left: 4px solid var(--color-orange) !important;">
                <div class="card-body p-3.5">
                    <?php if (isset($kelas) && $kelas): ?>
                        <div class="row align-items-center">
                            <div class="col-lg-9">
                                <span class="badge px-2.5 py-1 rounded-pill mb-1 fw-bold" style="background: rgba(223, 107, 224, 0.15); color: var(--color-pink); font-size: 11px;">
                                    Kelas Aktif
                                </span>
                                <h3 class="fw-bold mb-1 text-dark" style="font-size: 18px;">
                                    <?= esc($kelas['nama_kelas'] ?? 'Kelas Pelatihan') ?>
                                </h3>
                            </div>
                            <div class="col-lg-3 text-lg-end mt-2 mt-lg-0">
                                <div class="d-inline-flex align-items-center gap-2 bg-white px-3 py-1.5 rounded-pill border border-purple border-opacity-25 shadow-sm">
                                    <div class="text-white rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; background: var(--color-purple);">
                                        <i class="bi bi-person-fill" style="font-size: 11px;"></i>
                                    </div>
                                    <div class="text-start">
                                        <span class="d-block text-muted" style="font-size: 9.5px; text-transform: uppercase; font-weight: 700;">Mentor</span>
                                        <span class="fw-bold text-dark" style="font-size: 13px;"><?= esc($kelas['nama_mentor'] ?? '-') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0 rounded-3 border-0 small">
                            <i class="bi bi-exclamation-circle me-2"></i> Anda belum terdaftar di kelas manapun.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php $activeTab = session()->getFlashdata('active_tab') ?? 'materi'; ?>
            <!-- NAV TABS -->
            <ul class="nav nav-tabs mb-3" id="kelasTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $activeTab === 'materi' ? 'active' : '' ?>" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi" type="button" role="tab">
                        <i class="fa-solid fa-book-open me-1.5" style="color: var(--color-cyan);"></i> Materi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $activeTab === 'absensi' ? 'active' : '' ?>" id="absensi-tab" data-bs-toggle="tab" data-bs-target="#absensi" type="button" role="tab">
                        <i class="fa-solid fa-map-location-dot me-1.5" style="color: var(--color-pink);"></i> Absensi & GPS
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ujian-tab" data-bs-toggle="tab" data-bs-target="#ujian" type="button" role="tab">
                        <i class="fa-solid fa-award me-1.5" style="color: var(--color-orange);"></i> Ujian & Nilai
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="angket-tab" data-bs-toggle="tab" data-bs-target="#angket" type="button" role="tab">
                        <i class="fa-solid fa-clipboard-check me-1.5" style="color: var(--color-purple);"></i> Angket Evaluasi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sertifikat-tab" data-bs-toggle="tab" data-bs-target="#sertifikat" type="button" role="tab">
                        <i class="fa-solid fa-certificate me-1.5" style="color: var(--color-cyan);"></i> Sertifikat
                    </button>
                </li>
            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content" id="kelasTabContent">

                <!-- ================= TAB 1 : MATERI ================= -->
                <div class="tab-pane fade <?= $activeTab === 'materi' ? 'show active' : '' ?>" id="materi" role="tabpanel">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body p-3.5">
                            <h5 class="fw-bold text-dark mb-1" style="color: var(--color-purple); font-size: 15px;">Modul & Dokumen Pembelajaran</h5>
                            <p class="text-muted small mb-3" style="font-size: 13px;">Unduh bahan bacaan materi yang telah disediakan oleh mentor pengampu.</p>

                            <?php if (!empty($materi)): ?>
                                <div class="row g-3">
                                    <?php foreach ($materi as $item): ?>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="materi-card">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <div class="materi-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                                                    <div>
                                                        <span class="materi-badge">Modul</span>
                                                    </div>
                                                </div>
                                                <h5 class="fw-bold mt-2 text-dark" style="font-size: 13.5px;"><?= esc($item['judul_materi'] ?? '-') ?></h5>
                                                <p class="text-muted mb-3" style="font-size: 12px;"><?= esc($item['deskripsi'] ?? 'Tidak ada deskripsi materi.') ?></p>
                                                <div class="materi-footer mt-auto pt-2 border-top border-light">
                                                    <?php if (!empty($item['file_materi'])): ?>
                                                        <a href="<?= base_url('uploads/materi/' . $item['file_materi']) ?>" target="_blank" class="btn btn-sm w-100 rounded-pill fw-semibold text-white shadow-sm" style="background: var(--color-purple); font-size: 12px;">
                                                            Unduh Modul <i class="bi bi-download ms-1"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small fst-italic" style="font-size: 12px;">File belum tersedia</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="text-muted fs-2 mb-2"><i class="bi bi-folder2-open opacity-50"></i></div>
                                    <h6 class="fw-bold text-dark" style="font-size: 14px;">Belum Ada Materi</h6>
                                    <p class="text-muted small" style="font-size: 13px;">Mentor belum mengunggah materi pembelajaran.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 2 : ABSENSI ================= -->
                <div class="tab-pane fade <?= $activeTab === 'absensi' ? 'show active' : '' ?>" id="absensi" role="tabpanel">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body p-3.5">
                            <h5 class="fw-bold text-dark mb-1" style="color: var(--color-purple); font-size: 15px;">Daftar Kehadiran & Sesi KBM</h5>
                            <p class="text-muted small mb-3" style="font-size: 13px;">Lakukan absensi menggunakan validasi GPS perangkat saat sesi kelas dibuka.</p>

                            <?php if (!empty($jadwal)): ?>
                                <div class="row g-3">
                                    <?php foreach ($jadwal as $item): ?>
                                        <?php
                                            $absensi = $item['absensi'] ?? null;
                                            $statusAbsensi = $absensi['status'] ?? null;
                                            $idJadwalItem = $item['id_jadwal'] ?? $item['id_jadwal_kelas'] ?? '';
                                            $absensiDibuka = 1; 
                                        ?>
                                        <div class="col-lg-6">
                                            <div class="materi-card">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="materi-badge">Pertemuan <?= esc($item['pertemuan_ke'] ?? '-') ?></span>
                                                    <?php if ($statusAbsensi === 'hadir'): ?>
                                                        <span class="badge bg-success bg-opacity-15 text-success px-2.5 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Hadir
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted px-2.5 py-1 rounded-pill fw-semibold border" style="font-size: 11px;">
                                                            <i class="bi bi-clock me-1"></i> Belum Absen
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <h5 class="fw-bold mt-1 mb-1 text-dark" style="font-size: 13.5px;">Sesi Pertemuan Ke-<?= esc($item['pertemuan_ke'] ?? '-') ?></h5>
                                                <p class="text-muted mb-1" style="font-size: 12px;">
                                                    <i class="bi bi-clock me-1" style="color: var(--color-orange);"></i>
                                                    Jam Absensi: <?= !empty($item['waktu_mulai']) ? date('H.i', strtotime($item['waktu_mulai'])) : '--.--' ?> - <?= !empty($item['waktu_selesai']) ? date('H.i', strtotime($item['waktu_selesai'])) : '--.--' ?>
                                                </p>
                                                  <?php if ($statusAbsensi === 'hadir' && !empty($absensi['waktu_absen'])): ?>
                                                  <p class="text-success mb-2 fw-semibold" style="font-size: 12px;">
                                                      <i class="bi bi-check-circle me-1"></i>
                                                      Jam Presensi: <?= date('H.i', strtotime($absensi['waktu_absen'])) ?> WIB
                                                  </p>
                                                  <?php endif; ?>

                                                <?php if ($statusAbsensi === 'hadir'): ?>
                                                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-0 rounded-3 py-1.5 small fw-semibold" style="font-size: 12px;">
                                                        Kehadiran Anda telah tervalidasi sistem.
                                                    </div>
                                                <?php else: ?>
                                                    <?php if ($absensiDibuka == 1) : ?>
                                                        <form action="<?= base_url('pelatihan/prosesAbsen/' . $idJadwalItem); ?>" method="POST" class="mt-2">
                                                            <?= csrf_field(); ?>
                                                            <input type="hidden" name="user_latitude" class="user_latitude">
                                                            <input type="hidden" name="user_longitude" class="user_longitude">

                                                            <button type="submit" class="btn btn-absen w-100 rounded-pill fw-semibold text-white shadow-sm" style="background: var(--color-purple); font-size: 12px;" disabled>
                                                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                                                Mendeteksi Lokasi GPS...
                                                            </button>
                                                        </form>
                                                    <?php else : ?>
                                                        <span class="badge bg-secondary py-1.5 rounded-pill" style="font-size: 11px;">Absensi Belum Dibuka</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="text-muted fs-2 mb-2"><i class="bi bi-calendar-x opacity-50"></i></div>
                                    <h6 class="fw-bold text-dark" style="font-size: 14px;">Belum Ada Jadwal</h6>
                                    <p class="text-muted small" style="font-size: 13px;">Jadwal kelas belum diatur oleh mentor.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 3 : UJIAN ================= -->
<div class="tab-pane fade" id="ujian" role="tabpanel">
    <div class="card shadow-sm hover-card">
        <div class="card-body p-3.5 p-lg-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
                <div>
                    <span class="badge text-white px-2.5 py-1 rounded-pill fw-bold mb-1 shadow-sm" style="background: var(--color-purple); font-size: 11px;">
                        <i class="fas fa-award me-1" style="color: var(--color-orange);"></i> EVALUASI AKHIR KOMPETENSI
                    </span>
                    <h4 class="fw-bold text-dark mb-1" style="color: var(--color-purple); font-size: 18px;">Status Ujian & Hasil Kelulusan</h4>
                    <p class="text-muted small mb-0" style="font-size: 13px;">Standar minimum nilai kelulusan pelatihan adalah <strong>70</strong>. Nilai dan keterangan akan tampil setelah diunggah/divalidasi oleh admin.</p>
                </div>
            </div>

            <?php if (!empty($ujian) && is_array($ujian)): ?>
                <div class="row g-3">
                    <?php foreach ($ujian as $u): ?>
                        <div class="col-12">
                            <div class="exam-status-card">
                                <div class="row align-items-center">
                                    <div class="col-lg-5 mb-3 mb-lg-0">
                                        <div class="d-flex align-items-start gap-2.5">
                                            <div class="rounded-3 p-2 text-white shadow-sm" style="background: var(--color-purple);">
                                                <i class="bi bi-file-earmark-check-fill fs-5"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold text-dark mb-1" style="font-size: 14px;"><?= esc($u['judul_ujian'] ?? 'Ujian Pelatihan'); ?></h5>
                                                <p class="text-muted mb-0" style="font-size: 12px;"><?= esc($u['keterangan'] ?? 'Ujian kompetensi materi pelatihan.'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 text-center mb-3 mb-lg-0 border-start border-end">
                                        <span class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Skor Anda</span>
                                        <span class="fs-4 fw-bold" style="color: var(--color-purple);">
                                            <?= (isset($u['nilai_terbaru']) && $u['nilai_terbaru'] !== null) ? esc($u['nilai_terbaru']) : '-' ?>
                                        </span>
                                    </div>
                                    <div class="col-lg-3 text-center mb-3 mb-lg-0">
                                        <span class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Status Kelulusan</span>
                                        
                                        <?php if (!isset($u['nilai_terbaru']) || $u['nilai_terbaru'] === null): ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                <i class="fas fa-clock me-1"></i> MENUNGGU PENILAIAN ADMIN
                                            </span>
                                        <?php elseif (!empty($u['is_lulus']) || (float)$u['nilai_terbaru'] >= 70): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                <i class="fas fa-check-circle me-1"></i> LULUS / KOMPETEN
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                <i class="fas fa-times-circle me-1"></i> REMIDI / BELUM LULUS
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-lg-2 text-end">
                                        <?php if (!isset($u['nilai_terbaru']) || $u['nilai_terbaru'] === null): ?>
                                            <button class="btn btn-sm w-100 rounded-pill fw-semibold text-muted bg-light border shadow-sm py-1.5" style="font-size: 12px;" disabled>
                                                <i class="bi bi-hourglass-split me-1"></i> Belum Dinilai
                                            </button>
                                        <?php elseif (!empty($u['is_lulus']) || (float)$u['nilai_terbaru'] >= 70): ?>
                                            <span class="text-success small fw-bold d-inline-flex align-items-center gap-1" style="font-size: 12px;">
                                                <i class="fas fa-check-circle"></i> Selesai
                                            </span>
                                        <?php else: ?>
                                            <a href="<?= base_url('pelatihan/ikut-remidi?id_ujian=' . $u['id_ujian']); ?>"
                                               class="btn btn-sm w-100 rounded-pill fw-bold text-white shadow-sm py-1.5" style="font-size: 12px; background: var(--color-orange); border: none;"
                                               onclick="return confirm('Apakah Anda yakin ingin mengambil ujian remidi?');">
                                                <i class="fas fa-redo me-1"></i> Ikut Remidi
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Fallback jika struktur tabel ujian tunggal -->
                <?php 
                    $skorNilai = (isset($nilai_ujian) && $nilai_ujian !== '-') ? $nilai_ujian : '-';
                    $isLulus = is_numeric($skorNilai) && ((float)$skorNilai >= 70);
                ?>
                <div class="row g-3 align-items-center">
                    <div class="col-lg-5">
                        <div class="p-4 rounded-4 text-center position-relative overflow-hidden shadow-sm border border-purple border-opacity-25 bg-white">
                            <span class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 11px; letter-spacing: 1px;">Skor Akhir Ujian</span>
                            <div class="display-4 fw-bold mb-2" style="color: var(--color-purple); font-weight: 800;">
                                <?= esc($skorNilai); ?>
                            </div>
                            <div class="d-inline-flex align-items-center gap-1 px-3 py-1 rounded-pill bg-light text-muted small border shadow-sm" style="font-size: 12px;">
                                <i class="bi bi-shield-check" style="color: var(--color-orange);"></i> Standar Minimum: <strong class="text-dark">70</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="p-4 rounded-4 h-100 d-flex flex-column justify-content-center bg-white border border-purple border-opacity-25 shadow-sm">
                            <?php 
                                $isLulus = (isset($status_kelulusan) && $status_kelulusan === 'lulus') || (empty($status_kelulusan) && is_numeric($skorNilai) && ((float)$skorNilai >= 70));
                                $isRemidi = (isset($status_kelulusan) && $status_kelulusan === 'remidi');
                                $isMenunggu = (isset($status_kelulusan) && $status_kelulusan === 'menunggu') || ($skorNilai === '-' && empty($status_kelulusan));
                            ?>
                            <?php if ($isMenunggu): ?>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-3 p-3 bg-secondary bg-opacity-10 text-secondary fs-4"><i class="bi bi-clock-history"></i></div>
                                    <div>
                                        <span class="badge bg-secondary bg-opacity-15 text-secondary mb-1 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 10px;">MENUNGGU PENILAIAN</span>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 14px;">Menunggu Konfirmasi Admin</h5>
                                        <p class="text-muted mb-0" style="font-size: 12px;">Nilai dan status keterangan ujian Anda akan muncul di sini setelah diperiksa dan diinput oleh administrator/mentor.</p>
                                    </div>
                                </div>
                            <?php elseif ($isLulus): ?>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-3 p-3 bg-success bg-opacity-10 text-success fs-4"><i class="bi bi-patch-check-fill"></i></div>
                                    <div>
                                        <span class="badge bg-success bg-opacity-15 text-success mb-1 px-2.5 py-1 rounded-pill fw-bold" style="font-size: 10px;"><i class="fas fa-check-circle me-1"></i> STATUS: KOMPETEN / LULUS</span>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 14px;">Selamat, Anda Dinyatakan Lulus!</h5>
                                        <p class="text-muted mb-0" style="font-size: 12px;">Nilai Anda telah divalidasi admin. Silakan lanjutkan ke menu <strong>Angket Evaluasi</strong>.</p>
                                    </div>
                                </div>
                            <?php elseif ($isRemidi): ?>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-3 p-3 bg-warning bg-opacity-10 text-warning fs-4"><i class="bi bi-arrow-repeat"></i></div>
                                    <div>
                                        <span class="badge bg-warning bg-opacity-15 text-warning mb-1 px-2.5 py-1 rounded-pill fw-bold" style="font-size: 10px;"><i class="fas fa-exclamation-triangle me-1"></i> STATUS: REMIDI</span>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 14px;">Ujian Perlu Diremidi</h5>
                                        <p class="text-muted mb-2" style="font-size: 12px;">Admin telah memutuskan Anda perlu mengikuti remidi. Silakan konfirmasi kesediaan Anda.</p>
                                        <?php if(isset($status_remidi) && $status_remidi === 'bersedia'): ?>
                                            <div class="alert alert-success py-1 px-2 mb-0" style="font-size: 11px;"><i class="fas fa-check-circle me-1"></i> Anda telah mengkonfirmasi kesediaan mengikuti remidi.</div>
                                        <?php else: ?>
                                            <a href="<?= base_url('pelatihan/ikut-remidi?id=' . ($id_nilai_ujian ?? '')) ?>" class="btn btn-warning btn-sm text-dark fw-bold rounded-pill" style="font-size: 11px;">Bersedia Remidi</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="d-flex align-items-start gap-3">
                                    <div class="rounded-3 p-3 bg-danger bg-opacity-10 text-danger fs-4"><i class="bi bi-exclamation-octagon-fill"></i></div>
                                    <div>
                                        <span class="badge bg-danger bg-opacity-15 text-danger mb-1 px-2.5 py-1 rounded-pill fw-bold" style="font-size: 10px;"><i class="fas fa-times-circle me-1"></i> STATUS: TIDAK LULUS</span>
                                        <h5 class="fw-bold text-dark mb-1" style="font-size: 14px;">Belum Memenuhi Batas Kelulusan</h5>
                                        <p class="text-muted mb-0" style="font-size: 12px;">Nilai Anda belum memenuhi standar kelulusan.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

                <!-- ================= TAB 4 : ANGKET EVALUASI ================= -->
                <div class="tab-pane fade" id="angket" role="tabpanel">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body p-3.5">
                            <?php if (!empty($bisa_isi_angket) && $bisa_isi_angket): ?>
                                <?php if (!$sudah_isi_angket): ?>
                                    <div class="p-3.5 rounded-4 bg-white border border-purple border-opacity-25 shadow-sm">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-white rounded-3 p-2.5 fs-4 shadow-sm" style="background: var(--color-orange);"><i class="bi bi-clipboard2-check"></i></div>
                                            <div>
                                                <h5 class="fw-bold mb-1" style="color: var(--color-purple); font-size: 15px;">Formulir Angket Evaluasi Tersedia</h5>
                                                <p class="text-muted mb-2.5" style="font-size: 13px;">Nilai ujian Anda sudah lulus. Silakan isi kuesioner evaluasi pelatihan.</p>
                                                <a href="<?= base_url('pelatihan/angket?id_kelas=' . $kelas['id_kelas']) ?>" class="btn px-3.5 py-1.5 rounded-pill fw-semibold text-white shadow-sm" style="background: var(--color-purple); font-size: 12px;">
                                                    <i class="fas fa-file-alt me-1"></i> Isi Angket Penilaian
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-0 py-2.5 small" style="font-size: 13px;">
                                        <i class="fas fa-check-circle me-2"></i> Terima kasih! Anda telah mengisi angket evaluasi pelatihan ini.
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-light border border-purple border-opacity-15 shadow-sm rounded-4 mb-0 py-4 text-center">
                                    <i class="fas fa-info-circle fs-3 mb-2 d-block" style="color: var(--color-orange);"></i>
                                    <h6 class="fw-bold text-dark" style="font-size: 14px;">Angket Belum Dapat Diakses</h6>
                                    <p class="text-muted small mb-0" style="font-size: 13px;">Menu angket akan terbuka otomatis apabila nilai ujian Anda telah keluar dan dinyatakan <strong>Lulus</strong>.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ================= TAB 5 : SERTIFIKAT ================= -->
                <div class="tab-pane fade" id="sertifikat" role="tabpanel">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body p-3.5">
                            <h5 class="fw-bold mb-2 text-dark" style="color: var(--color-purple); font-size: 15px;">Sertifikat Kelulusan Pelatihan</h5>

                            <?php if (!empty($sertifikatTerbit)): ?>
                                <div class="p-3.5 rounded-4 border mb-3 shadow-sm" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-color: rgba(226, 160, 72, 0.3) !important;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="text-white rounded-3 p-2.5 fs-4 shadow-sm" style="background: var(--color-orange);"><i class="bi bi-award-fill"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-1" style="color: #92400e; font-size: 14px;">Sertifikat Resmi Telah Diterbitkan!</h6>
                                            <p class="text-muted mb-0" style="font-size: 12px;">Anda berhak mengunduh sertifikat digital kelulusan program ini.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3" style="font-size: 13px;">
                                    <div class="col-md-6 mb-1.5">
                                        <span class="text-muted d-block" style="font-size: 11px;">Nomor Sertifikat</span>
                                        <strong class="text-dark"><?= esc($sertifikatTerbit['nomor_sertifikat'] ?? '-') ?></strong>
                                    </div>
                                    <div class="col-md-6 mb-1.5">
                                        <span class="text-muted d-block" style="font-size: 11px;">Tanggal Terbit</span>
                                        <strong class="text-dark"><?= !empty($sertifikatTerbit['tanggal_terbit']) ? date('d-m-Y', strtotime($sertifikatTerbit['tanggal_terbit'])) : '-' ?></strong>
                                    </div>
                                </div>

                                <?php if (!empty($sertifikatTerbit['file_sertifikat'])): ?>
                                    <a href="<?= base_url('pelatihan/download-sertifikat/' . $sertifikatTerbit['id_sertifikat']) ?>" class="btn px-3.5 py-1.5 rounded-pill fw-semibold text-white shadow-sm" style="background: var(--color-purple); font-size: 12px;">
                                        <i class="fas fa-download me-1"></i> Unduh File Sertifikat
                                    </a>
                                <?php else: ?>
                                    <div class="alert alert-warning border-0 small py-2">File sertifikat belum diunggah oleh administrator.</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="text-muted fs-2 mb-2"><i class="bi bi-shield-lock opacity-50"></i></div>
                                    <h6 class="fw-bold text-dark" style="font-size: 14px;">Sertifikat Belum Tersedia</h6>
                                    <p class="text-muted small mb-0" style="font-size: 13px;">Sertifikat akan otomatis muncul setelah Anda menyelesaikan seluruh tahapan pelatihan dan diterbitkan admin.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script GPS & Sidebar Mobile -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    console.log("[GPS Flow] Halaman dibuka, menyiapkan event listener pada tombol absen...");
    const forms = document.querySelectorAll('form[action*="prosesAbsen"]');

    forms.forEach(form => {
        const btnAbsen = form.querySelector('.btn-absen');
        const inputLat = form.querySelector('.user_latitude');
        const inputLon = form.querySelector('.user_longitude');

        if (btnAbsen) {
            // Aktifkan tombol pada load karena kita akan mendeteksi saat diklik
            btnAbsen.removeAttribute('disabled');
            btnAbsen.innerHTML = "<i class='bi bi-geo-alt-fill me-1'></i> Kirim Absen Sekarang";

            btnAbsen.addEventListener('click', function(e) {
                e.preventDefault(); // Tunda submit form
                console.log("[GPS Flow] Tombol diklik, memulai deteksi lokasi...");
                
                // Ubah status tombol jadi mendeteksi
                btnAbsen.disabled = true;
                btnAbsen.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Mendeteksi Lokasi GPS...';

                if (navigator.geolocation) {
                    console.log("[GPS Flow] Geolocation didukung. Memanggil getCurrentPosition...");
                    let locationFound = false;

                    const fallbackTimer = setTimeout(function() {
                        if (!locationFound) {
                            console.log("[GPS Flow] Error: Timeout 15 detik tercapai, GPS gagal didapat.");
                            btnAbsen.className = "btn btn-danger btn-absen w-100 rounded-pill fw-semibold";
                            btnAbsen.style.fontSize = "12px";
                            btnAbsen.innerText = "Gagal Mendeteksi GPS (Timeout)";
                            alert('Pencarian lokasi terlalu lama. Pastikan GPS aktif dan izin diberikan.');
                        }
                    }, 15000);

                    navigator.geolocation.getCurrentPosition(function(position) {
                        console.log("[GPS Flow] Success callback terpanggil!");
                        locationFound = true;
                        clearTimeout(fallbackTimer);
                        
                        const userLat = position.coords.latitude;
                        const userLon = position.coords.longitude;
                        console.log("[GPS Flow] Latitude/Longitude diterima: " + userLat + ", " + userLon);
                        
                        if (inputLat) inputLat.value = userLat;
                        if (inputLon) inputLon.value = userLon;
                        
                        btnAbsen.innerHTML = "<i class='bi bi-check-circle-fill me-1'></i> Lokasi Ditemukan, Menyimpan...";
                        console.log("[GPS Flow] Mengirim data absensi ke backend...");
                        form.submit(); // Submit form setelah lokasi didapat
                        
                    }, function(error) {
                        console.log("[GPS Flow] Error callback terpanggil! Kode error: " + error.code);
                        locationFound = true;
                        clearTimeout(fallbackTimer);
                        
                        btnAbsen.className = "btn btn-danger btn-absen w-100 rounded-pill fw-semibold";
                        btnAbsen.style.fontSize = "12px";
                        btnAbsen.innerText = "Gagal Mendeteksi GPS";
                        
                        let errorMsg = 'Gagal mendeteksi lokasi. Pastikan izin GPS perangkat Anda aktif!';
                        if(error.code === 1) errorMsg = 'Izin lokasi ditolak oleh browser/pengguna.';
                        if(error.code === 2) errorMsg = 'Sinyal lokasi tidak tersedia.';
                        if(error.code === 3) errorMsg = 'Waktu permintaan lokasi habis (timeout).';
                        alert(errorMsg);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });
                } else {
                    console.log("[GPS Flow] Error: Browser tidak mendukung Geolocation.");
                    btnAbsen.className = "btn btn-secondary btn-absen w-100 rounded-pill fw-semibold";
                    btnAbsen.style.fontSize = "12px";
                    btnAbsen.innerText = "GPS Tidak Didukung";
                }
            });
        }
    });

    // Toggle Sidebar Mobile
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebarMenu');
    const backdrop = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('show');
        if (backdrop) backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
});
</script>

</body>
</html>