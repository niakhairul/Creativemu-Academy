<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Creativemu Academy</title>
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

        /* Sidebar diperkecil ukurannya menjadi 220px (Sesuai Dashboard) */
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

        /* Menyesuaikan margin konten utama dengan lebar sidebar baru (220px) */
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

        .bg-purple-soft {
            background: linear-gradient(135deg, var(--purple-soft) 0%, var(--purple-soft2) 100%);
        }
        
        .text-purple-custom {
            color: var(--color-purple);
        }

        .mobile-topbar {
            display: none;
            width: 100%;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 7, 35, 0.55);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }

        .sidebar-backdrop.show {
            display: block;
        }

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

            .app-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 260px;
                max-width: 82vw;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-close-btn {
                display: flex;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 16px 12px !important;
            }
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
        <i class="bi bi-person-circle"></i> <?= esc($user['nama'] ?? 'Peserta') ?>
    </a>
</div>

<!-- BACKDROP SIDEBAR MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebarMenu">
    <div class="d-flex align-items-center justify-content-between pb-2.5 mb-2.5 border-bottom border-white border-opacity-10">
        <a href="#" class="sidebar-brand text-decoration-none d-flex align-items-center mb-0 pb-0 border-0">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Logo" class="rounded-3 me-2 shadow-sm object-fit-cover" style="width: 28px; height: 28px;">
            <div>
                <span class="fs-6 fw-bold d-block text-white lh-1" style="font-size: 14.5px !important;">Creativemu</span>
                <span style="font-size: 9.5px; letter-spacing: 0.5px; color: var(--color-cyan);">ACADEMY</span>
            </div>
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarClose" aria-label="Tutup Menu">
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
            <a href="<?= base_url('pelatihan/kelas') ?>"><i class="bi bi-mortarboard-fill" style="color: var(--color-orange);"></i> KBM</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/pengaturan') ?>" class="active"><i class="bi bi-gear-fill" style="color: var(--color-cyan);"></i> Pengaturan</a>
        </li>
        <li class="mt-3">
            <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </li>
    </ul>
</nav>

<div class="app-wrapper">
    <div class="main-content">
        <div class="container-fluid py-1">

            <!-- NOTIFIKASI -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-3 py-2 small">
                    <strong>✅ Berhasil!</strong> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-3 py-2 small">
                    <strong>⚠️ Perhatian!</strong> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- KARTU PROFIL UTAMA -->
            <div class="card shadow-sm border-0 rounded-4 mb-3 hover-card">
                <div class="card-body p-3.5">

                    <?php
                    if (!empty($user['foto_profil'])) {
                        $fotoUrl = base_url('uploads/profil/' . $user['foto_profil']);
                    } elseif (!empty($pendaftaran['pas_foto'])) {
                        $fotoUrl = base_url('uploads/foto/' . $pendaftaran['pas_foto']);
                    } else {
                        $fotoUrl = base_url('assets/img/logo creativemu academy.jpg');
                    }
                    ?>

                    <!-- HEADER PROFIL -->
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-center gap-3 mb-3">
                        <!-- FOTO -->
                        <div class="flex-shrink-0">
                            <img src="<?= $fotoUrl ?>" class="rounded-circle shadow-sm border" width="85" height="85" style="object-fit: cover;">
                        </div>

                        <!-- NAMA DAN STATUS -->
                        <div class="text-center text-md-start flex-grow-1">
                            <h4 class="fw-bold mb-1" style="color: var(--color-purple); font-size: 20px;"><?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?></h4>
                            <p class="text-muted mb-2" style="font-size: 13px;">Peserta CreativeMU Academy</p>
                            <?php if (!empty($pendaftaran['status'])): ?>
                                <span class="badge px-2.5 py-1 rounded-pill text-white" style="font-size: 11px; background: linear-gradient(135deg, var(--color-purple), var(--color-pink));"><?= esc($pendaftaran['status']) ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- TOMBOL EDIT -->
                        <div>
                            <a href="<?= base_url('pelatihan/edit-profil') ?>" class="btn btn-sm text-white rounded-pill px-3 py-1.5 shadow-sm fw-bold" style="font-size: 12px; background: linear-gradient(135deg, var(--color-purple), var(--color-pink));">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </a>
                        </div>
                    </div>

                    <hr class="my-3 border-purple border-opacity-10">

                    <!-- INFORMASI PRIBADI -->
                    <h5 class="fw-bold mb-3" style="color: var(--color-purple); font-size: 15px;">Informasi Pribadi</h5>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Nama Lengkap</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Email</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['email'] ?? $user['email'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Nomor HP / WhatsApp</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['no_hp'] ?? $user['no_hp'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Jenis Kelamin</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Tempat, Tanggal Lahir</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['ttl'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Pendidikan Terakhir</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['pendidikan_terakhir'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Asal Sekolah / Kampus</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($user['asal_sekolah'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Lokasi Pelatihan</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['lokasi_pelatihan'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 11px;">Alamat Lengkap</small>
                                <span class="fw-semibold text-dark" style="font-size: 13px;"><?= esc($pendaftaran['alamat'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PENGATURAN KEAMANAN AKUN -->
            <div class="card shadow-sm border-0 rounded-4 hover-card">
                <div class="card-body p-3.5 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--color-purple); font-size: 15px;">Keamanan Akun</h5>
                        <p class="text-muted mb-2 mb-md-0" style="font-size: 13px;">Kelola kata sandi untuk menjaga keamanan akun Anda.</p>
                    </div>
                    <a href="<?= base_url('pelatihan/ubah-password') ?>" class="btn btn-sm rounded-pill px-3 py-1.5 text-white fw-bold shadow-sm text-nowrap" style="font-size: 12px; background-color: var(--color-orange); border: none;">
                        <i class="bi bi-shield-lock-fill me-1"></i> Ubah Password
                    </a>
                </div>
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

    const navLinks = sidebar ? sidebar.querySelectorAll('li a') : [];
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });
});
</script>
</body>
</html>