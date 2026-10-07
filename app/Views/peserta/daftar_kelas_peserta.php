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

        /* Sidebar Sesuai Dashboard (220px dengan efek glow) */
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

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        /* Menu Aktif dipindah ke Daftar Kelas Saya */
        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(226, 160, 72, 0.4), rgba(90, 197, 232, 0.3));
            color: white;
            transform: translateX(4px);
            box-shadow: 0 6px 18px rgba(20, 5, 60, 0.2), inset 3px 0 0 var(--color-orange);
        }

        .sidebar-menu a i {
            font-size: 15px;
            margin-right: 9px;
        }

        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 20px 24px;
            width: calc(100% - 220px);
        }

        /* Hero Header Banner ala Dashboard */
        .header-banner {
            background: linear-gradient(165deg, #7b5af6 0%, #df6be0 50%, #5ac5e8 100%);
            background-size: 220% 220%;
            animation: headerGlow 10s ease infinite;
            border-radius: 14px;
            color: white;
            padding: 18px 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(123, 90, 246, 0.25);
            border-left: 4px solid #df6be0;
        }

        @keyframes headerGlow {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }

        /* Override row spacing to group cards closely in center */
        .header-banner ~ .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
        }
        
        /* Remove strict bootstrap col widths to let cards dictate size */
        .header-banner ~ .row > [class*="col-"] {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: 100% !important;
            padding: 0 !important;
        }

        /* Course Card Style Sesuai Tema Dashboard */
        .course-card {
            border: 1px solid rgba(123, 90, 246, 0.1);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 4px 12px rgba(123, 90, 246, 0.04);
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            width: 380px;
            max-width: 100%;
        }

        .course-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 90, 246, 0.1) !important;
            border-color: rgba(123, 90, 246, 0.2);
        }

        .card-img-wrapper {
            position: relative;
            height: 110px;
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .course-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-img-overlay-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            display: flex;
            gap: 4px;
        }

        .custom-badge {
            background: rgba(91, 63, 214, 0.85);
            backdrop-filter: blur(4px);
            color: white;
            font-weight: 600;
            font-size: 9.5px;
            padding: 2px 7px;
            border-radius: 8px;
        }

        .badge-category {
            background: var(--purple-soft);
            color: var(--color-purple);
            font-weight: 700;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 4px;
            font-size: 11.5px;
        }

        .info-item i {
            font-size: 12px;
            margin-right: 6px;
            color: var(--color-purple);
            margin-top: 2px;
        }

        .btn-kbm {
            background: linear-gradient(135deg, var(--color-purple), var(--color-pink));
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px;
            font-weight: 700;
            font-size: 12px;
            box-shadow: 0 4px 10px rgba(123, 90, 246, 0.15);
            transition: all 0.25s ease;
        }

        .btn-kbm:hover {
            color: white;
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(123, 90, 246, 0.25);
        }

        .btn-tambah-kelas {
            background: #ffffff;
            color: var(--color-orange);
            font-weight: 700;
            font-size: 12.5px;
            padding: 7px 16px;
            border-radius: 50rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.25s ease;
        }

        .btn-tambah-kelas:hover {
            background: var(--purple-soft);
            color: var(--color-orange);
            transform: translateY(-1px);
        }

        .empty-state-card {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 14px;
            border: 1.5px dashed rgba(123, 90, 246, 0.2);
            padding: 30px 20px;
        }

        /* Mobile Topbar & Responsive */
        .mobile-topbar { display: none; width: 100%; }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 7, 35, 0.55);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }
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
        }
    </style>
</head>
<body>

<!-- TOPBAR MOBILE -->
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

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
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
            <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>" class="active"><i class="bi bi-journals" style="color: var(--color-pink);"></i> Daftar Kelas Saya</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/kelas') ?>"><i class="bi bi-mortarboard-fill" style="color: var(--color-orange);"></i> KBM</a>
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
            
            <!-- Hero Header Banner Sesuai Tema Dashboard -->
            <div class="header-banner mb-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 11px;">
                            <i class="bi bi-journal-check me-1"></i> Area Pembelajaran Aktif
                        </span>
                        <h4 class="fw-bold mb-1" style="font-size: 20px;">Daftar Kelas Saya</h4>
                        <p class="mb-0 text-white-50" style="font-size: 13px;">Kelola, pantau, dan akses kelas pelatihan interaktif yang sedang Anda ikuti di Creativemu Academy.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="<?= base_url('pelatihan/pendaftaran') ?>" class="btn btn-tambah-kelas shadow-sm">
                            <i class="bi bi-plus-circle-fill me-1.5" style="color: var(--color-purple);"></i> Tambah Kelas
                        </a>
                    </div>
                </div>
            </div>

            <!-- List Card Kelas -->
            <div class="row g-3">
                <?php if (!empty($kelas) && is_array($kelas)): ?>
                    <?php foreach ($kelas as $k): ?>
                        <div class="col-xl-4 col-md-6">
                            <div class="course-card">
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
                                        <span class="custom-badge"><?= esc($k['jenis_kelas'] ?? 'Reguler') ?></span>
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column" style="padding: 12px;">
                                    <h5 class="fw-bold" style="color: var(--color-purple); font-size: 13.5px; line-height: 1.3; margin-bottom: 8px;">
                                        <?= esc($k['nama_kelas'] ?? $k['pilihan_pelatihan']) ?>
                                    </h5>

                                    <div class="flex-grow-1" style="margin-bottom: 8px;">
                                        <div class="info-item">
                                            <i class="bi bi-person-badge-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 10px;">Mentor Pengampu</span>
                                                <strong class="text-dark" style="font-size: 11.5px;"><?= esc($k['nama_mentor'] ?? 'Belum Ditentukan') ?></strong>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 10px;">Tempat / Lokasi Pelatihan</span>
                                                <strong class="text-dark" style="font-size: 11.5px;"><?= esc($k['lokasi_pelatihan'] ?? '-') ?></strong>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="bi bi-laptop-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 10px;">Metode Pembelajaran</span>
                                                <strong class="text-dark text-capitalize" style="font-size: 11.5px;"><?= esc($k['metode_pembelajaran'] ?? '-') ?></strong>
                                            </div>
                                        </div>
                                        <div class="info-item mb-0">
                                            <i class="bi bi-calendar-check-fill"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 10px;">Mulai Pelatihan</span>
                                                <strong class="text-dark" style="font-size: 11.5px;"><?= esc($k['tanggal_mulai_kelas'] ?? '-') ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="<?= base_url('pelatihan/kbm?id_kelas=' . $k['id_kelas']) ?>" class="btn btn-kbm w-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-mortarboard-fill me-2 fs-6"></i> Masuk Ruang KBM
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="empty-state-card text-center">
                            <div class="mb-3">
                                <span class="p-3 rounded-circle bg-white d-inline-block shadow-sm" style="color: var(--color-purple);">
                                    <i class="bi bi-journal-x fs-2"></i>
                                </span>
                            </div>
                            <h5 class="fw-bold mb-1" style="color: var(--color-purple); font-size: 16px;">Belum Ada Kelas yang Diambil</h5>
                            <p class="text-muted mb-3 mx-auto" style="max-width: 380px; font-size: 13px;">Anda belum terdaftar di kelas pelatihan apapun. Silakan pilih kelas terlebih dahulu untuk mulai belajar.</p>
                            <a href="<?= base_url('pelatihan/pendaftaran') ?>" class="btn btn-kbm px-4 py-2 rounded-pill d-inline-flex align-items-center shadow-sm" style="font-size: 13px;">
                                <i class="bi bi-plus-circle-fill me-2"></i> Tambah Kelas Sekarang
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

<script>
document.addEventListener('DOMContentLoaded', function () {
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (session()->getFlashdata('success')): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= addslashes(session()->getFlashdata('success')) ?>',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#fff',
        color: '#1e293b',
        customClass: {
            popup: 'rounded-4 shadow-sm border'
        }
    });
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top',
        icon: 'error',
        title: 'Oops...',
        text: '<?= addslashes(session()->getFlashdata('error')) ?>',
        showConfirmButton: false,
        timer: 3500,
        timerProgressBar: true,
        background: '#fff',
        color: '#1e293b',
        customClass: {
            popup: 'rounded-4 shadow-sm border'
        }
    });
</script>
<?php endif; ?>
</body>
</html>