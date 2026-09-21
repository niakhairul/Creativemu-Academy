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
    <!-- FontAwesome untuk ikon pelengkap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #fdf4ff 100%);
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        /* Layout Utama dengan Sidebar */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar dengan Gradasi Ungu Modern */
.sidebar {
    width: 260px;
    background:
        radial-gradient(circle at 15% 12%, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0) 45%),
        linear-gradient(165deg, #4a2fc9 0%, #7440e6 32%, #9257f2 60%, #b678f5 100%);
    background-size: 200% 200%, 220% 220%;
    animation: sidebarGlow 14s ease infinite;
    color: white;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 24px 20px;
    box-shadow: 6px 0 34px rgba(116, 64, 230, 0.35);
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
    0% {
        background-position: 0% 0%, 0% 0%;
    }

    50% {
        background-position: 100% 100%, 100% 100%;
    }

    100% {
        background-position: 0% 0%, 0% 0%;
    }
}

.sidebar::before {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;
    width: 180px;
    height: 180px;
    background: radial-gradient(
        circle,
        rgba(255,255,255,0.18) 0%,
        rgba(255,255,255,0) 70%
    );
    border-radius: 50%;
    pointer-events: none;
    animation: floatBlob 8s ease-in-out infinite;
}

@keyframes floatBlob {
    0%, 100% {
        transform: translateY(0) scale(1);
    }

    50% {
        transform: translateY(20px) scale(1.08);
    }
}

.sidebar-brand {
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}

.sidebar-brand i {
    animation: brandPulse 3s ease-in-out infinite;
}

@keyframes brandPulse {
    0%, 100% {
        transform: scale(1) rotate(0deg);
    }

    50% {
        transform: scale(1.12) rotate(-4deg);
    }
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
    z-index: 1;
}

.sidebar-menu li {
    margin-bottom: 8px;
    opacity: 0;
    transform: translateX(-12px);
    animation: menuSlideIn 0.5s ease forwards;
}

.sidebar-menu li:nth-child(1) {
    animation-delay: 0.05s;
}

.sidebar-menu li:nth-child(2) {
    animation-delay: 0.12s;
}

.sidebar-menu li:nth-child(3) {
    animation-delay: 0.19s;
}

.sidebar-menu li:nth-child(4) {
    animation-delay: 0.26s;
}

.sidebar-menu li:nth-child(5) {
    animation-delay: 0.33s;
}

@keyframes menuSlideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.75);
    text-decoration: none;
    padding: 12px 16px;
    border-radius: 12px;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.sidebar-menu a::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.15),
        transparent
    );
    transition: left 0.6s ease;
}

.sidebar-menu a:hover::before {
    left: 100%;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: rgba(255, 255, 255, 0.18);
    color: white;
    transform: translateX(6px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
}

.sidebar-menu a.active {
    background: linear-gradient(
        90deg,
        rgba(255, 255, 255, 0.28),
        rgba(255, 255, 255, 0.12)
    );
    box-shadow:
        0 6px 18px rgba(20, 5, 60, 0.28),
        inset 3px 0 0 #ffd166;
}

.sidebar-menu a i {
    font-size: 1.2rem;
    margin-right: 12px;
    transition: transform 0.3s ease;
}

.sidebar-menu a:hover i {
    transform: scale(1.15) rotate(-6deg);
}

        /* Konten Utama */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 24px;
            width: calc(100% - 250px);
        }

        /* Card yang Lebih Kompak dan Minimalis */
        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 6px 20px rgba(109, 40, 217, 0.04);
            transition: all 0.3s ease;
        }

        /* =========================
           RESPONSIVE & MOBILE DRAWER
        ========================= */
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        .sidebar-close-btn {
            display: none;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.2s ease;
        }
        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        @media (max-width: 991.98px) {
            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #4a2fc9 0%, #7440e6 100%);
                color: white;
                padding: 14px 18px;
                position: sticky;
                top: 0;
                z-index: 990;
                box-shadow: 0 4px 18px rgba(116, 64, 230, 0.25);
            }

            .app-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 280px;
                max-width: 82vw;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
                box-shadow: 10px 0 40px rgba(0, 0, 0, 0.35);
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
                max-width: 100% !important;
                padding: 20px 14px !important;
            }

            .top-navbar {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 14px 10px !important;
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
        <span class="fw-bold fs-6 tracking-wide">
            <i class="bi bi-mortarboard-fill text-warning me-1"></i> Creativemu
        </span>
    </div>
    <span class="text-white small bg-white bg-opacity-20 px-2 py-1 rounded-pill">
        <i class="bi bi-gear-fill"></i> Pengaturan
    </span>
</div>


<!-- BACKDROP UNTUK MENUTUP SIDEBAR DI MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<nav class="sidebar">
        <a href="#" class="sidebar-brand d-flex align-items-center">
            <!-- Menggunakan file gambar logo dari folder assets -->
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Logo Creativemu" class="rounded-3 me-2 shadow-sm object-fit-cover" style="width: 38px; height: 38px;">
            
            <div>
                <span class="fs-6 fw-bold d-block text-white lh-1">Creativemu</span>
                <span class="text-white-50" style="font-size: 0.65rem; letter-spacing: 0.5px;">ACADEMY</span>
            </div>
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('peserta/dashboard') ?>" class="active"><i class="bi bi-grid-fill"></i> Dashboard</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>"><i class="bi bi-journals"></i> Daftar Kelas Saya</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/kelas') ?>"><i class="bi bi-mortarboard-fill"></i> KBM</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>"><i class="bi bi-gear-fill"></i> Pengaturan</a>
            </li>
            <li class="mt-5">
                <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </li>
        </ul>
    </nav>

<div class="app-wrapper">
    <!-- PAGE CONTENT -->
    <div class="main-content">
        <!-- Navbar Atas Kompak -->
        <nav class="navbar navbar-expand-lg top-navbar mb-3 px-3 py-2">
            <div class="container-fluid px-0">
                <span class="navbar-brand mb-0 h6 fw-bold text-dark">Pengaturan Akun</span>
                <span class="text-muted fw-semibold" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-user-circle me-1 text-primary"></i> Peserta
                </span>
            </div>
        </nav>

        <!-- Konten Pengaturan -->
        <div class="container-fluid px-0">

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
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3 p-md-4">

                    <?php
                    if (!empty($user['foto_profil'])) {
                        $fotoUrl = base_url('uploads/profil/' . $user['foto_profil']);
                    } elseif (!empty($pendaftaran['pas_foto'])) {
                        $fotoUrl = base_url('uploads/foto/' . $pendaftaran['pas_foto']);
                    } else {
                        $fotoUrl = base_url('assets/img/logo creativemu academy.jpg');
                    }
                    ?>

                    <!-- HEADER PROFIL (Dibuat Lebih Rapat & Ringkas) -->
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-center gap-3 mb-3">
                        <!-- FOTO -->
                        <div class="flex-shrink-0">
                            <img src="<?= $fotoUrl ?>" class="rounded-circle shadow-sm border" width="85" height="85" style="object-fit: cover;">
                        </div>

                        <!-- NAMA DAN STATUS -->
                        <div class="text-center text-md-start flex-grow-1">
                            <h5 class="fw-bold mb-1" style="color: #4c1d95;"><?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?></h5>
                            <p class="text-muted small mb-2">Peserta CreativeMU Academy</p>
                            <?php if (!empty($pendaftaran['status'])): ?>
                                <span class="badge px-2.5 py-1 rounded-pill text-white" style="font-size: 0.75rem; background: linear-gradient(135deg, #7c3aed, #4c1d95);"><?= esc($pendaftaran['status']) ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- TOMBOL EDIT -->
                        <div>
                            <a href="<?= base_url('pelatihan/edit-profil') ?>" class="btn btn-sm text-white rounded-pill px-3 py-1.5 shadow-sm" style="font-size: 0.85rem; background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </a>
                        </div>
                    </div>

                    <hr class="my-3 border-purple border-opacity-10">

                    <!-- INFORMASI PRIBADI (Grid 3 Kolom atau 2 Kolom dengan Padding Lebih Kecil) -->
                    <h6 class="fw-bold mb-3" style="color: #4c1d95; font-size: 0.95rem;">Informasi Pribadi</h6>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Nama Lengkap</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Email</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['email'] ?? $user['email'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Nomor HP / WhatsApp</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['no_hp'] ?? $user['no_hp'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Jenis Kelamin</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Tempat, Tanggal Lahir</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['ttl'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Pendidikan Terakhir</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['pendidikan_terakhir'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Asal Sekolah / Kampus</small>
                                <span class="fw-semibold text-dark small"><?= esc($user['asal_sekolah'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Lokasi Pelatihan</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['lokasi_pelatihan'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Alamat Lengkap</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['alamat'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PENGATURAN KEAMANAN AKUN (Lebih Compact) -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #4c1d95; font-size: 0.95rem;">Keamanan Akun</h6>
                        <p class="text-muted small mb-2 mb-md-0">Kelola kata sandi untuk menjaga keamanan akun Anda.</p>
                    </div>
                    <a href="<?= base_url('pelatihan/ubah-password') ?>" class="btn btn-sm btn-warning rounded-pill px-3 py-1.5 text-dark fw-semibold shadow-sm text-nowrap" style="font-size: 0.85rem;">
                        <i class="bi bi-shield-lock-fill me-1"></i> Ubah Password
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Load Bootstrap JS Bundle -->
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