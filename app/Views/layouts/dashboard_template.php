<!DOCTYPE html>
<html lang="id">

<head>

    <?= $this->include('layouts/header') ?>

    <style>
        :root {
            --purple-deep: #5b3fd6;
            --purple-mid: #7c5cfa;
            --purple-light: #8b6cf0;
            --purple-soft: #efeaff;
            --purple-soft2: #dcd0ff;
            --purple-glow: rgba(124, 92, 250, 0.35);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(124, 92, 250, 0.4) transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(
                120deg,
                #f8f6ff,
                #f1ecff,
                #faf5ff,
                #eee8ff
            );
            background-size: 300% 300%;
            animation: bgFlow 22s ease infinite;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        @keyframes bgFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* =========================
           LAYOUT UTAMA
        ========================= */

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR PESERTA TERBARU
        ========================= */

        .sidebar {
            width: 260px;
            background:
                radial-gradient(
                    circle at 15% 12%,
                    rgba(255, 255, 255, 0.14) 0%,
                    rgba(255, 255, 255, 0) 45%
                ),
                linear-gradient(
                    165deg,
                    #4a2fc9 0%,
                    #7440e6 32%,
                    #9257f2 60%,
                    #b678f5 100%
                );
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

        @keyframes sidebarGlow {
            0% {
                background-position: 0% 50%, 0% 50%;
            }

            50% {
                background-position: 100% 50%, 100% 50%;
            }

            100% {
                background-position: 0% 50%, 0% 50%;
            }
        }

        /* Garis diagonal seperti tampilan terbaru */

        .sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0.12;
            background-image: repeating-linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.35) 0,
                rgba(255, 255, 255, 0.35) 1px,
                transparent 1px,
                transparent 12px
            );
        }

        .sidebar > * {
            position: relative;
            z-index: 1;
        }

        /* =========================
           BRAND
        ========================= */

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
        }

        .sidebar-brand:hover {
            color: white;
        }

        /* =========================
           MENU
        ========================= */

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 8px;
        }

        .sidebar li a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.78);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar li a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar li a.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar li a i {
            font-size: 1.2rem;
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* Tombol keluar */

        .sidebar li.logout {
            margin-top: 42px;
        }

        .sidebar li.logout a {
            color: #ef4444;
            background: rgba(220, 38, 38, 0.08);
        }

        .sidebar li.logout a:hover {
            color: #ef4444;
            background: rgba(220, 38, 38, 0.15);
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
            min-height: 100vh;
            box-sizing: border-box;
        }

        /* =========================
           CARD UMUM
        ========================= */

        .card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.05);
        }

        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(109, 40, 217, 0.12) !important;
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

            .dashboard-wrapper {
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

            .content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 20px 14px;
            }
        }

        @media (max-width: 575.98px) {
            .content {
                padding: 16px 12px;
            }
        }
    </style>

</head>

<body>

<!-- TOPBAR KHUSUS MOBILE -->
<div class="mobile-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-link text-white p-0 fs-3 text-decoration-none lh-1" id="sidebarToggle" aria-label="Buka Menu">
            <i class="bi bi-list"></i>
        </button>
        <span class="fw-bold fs-6 tracking-wide">
            <i class="bi bi-mortarboard-fill text-warning me-1"></i> Creativemu
        </span>
    </div>
    <a href="<?= base_url('pelatihan/pengaturan') ?>" class="text-white text-decoration-none small d-flex align-items-center gap-1 bg-white bg-opacity-20 px-2 py-1 rounded-pill">
        <i class="bi bi-person-circle"></i> Peserta
    </a>
</div>

<!-- BACKDROP UNTUK MENUTUP SIDEBAR DI MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- =========================
     SIDEBAR PESERTA
========================== -->
<div class="sidebar" id="sidebarMenu">

    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-15">
        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand p-0 m-0 border-0">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i>
            Creativemu
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarClose" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul>

        <!-- Dashboard -->
        <li>
            <a href="<?= base_url('peserta/dashboard') ?>"
               class="<?= uri_string() === 'peserta/dashboard' ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>
        </li>

        <!-- Daftar Kelas Saya -->
        <li>
            <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>"
               class="<?= uri_string() === 'pelatihan/daftar-kelas-peserta' ? 'active' : '' ?>">
                <i class="bi bi-journals"></i>
                Daftar Kelas Saya
            </a>
        </li>

        <!-- KBM -->
        <li>
            <a href="<?= base_url('pelatihan/kbm') ?>"
               class="<?= uri_string() === 'pelatihan/kbm' ? 'active' : '' ?>">
                <i class="bi bi-mortarboard-fill"></i>
                KBM
            </a>
        </li>

        <!-- Pengaturan -->
        <li>
            <a href="<?= base_url('pelatihan/pengaturan') ?>"
               class="<?= uri_string() === 'pelatihan/pengaturan' ? 'active' : '' ?>">
                <i class="bi bi-gear-fill"></i>
                Pengaturan
            </a>
        </li>

        <!-- Keluar -->
        <li class="logout">
            <a href="<?= base_url('logout') ?>">
                <i class="bi bi-box-arrow-left"></i>
                Keluar
            </a>
        </li>

    </ul>

</div>

<div class="dashboard-wrapper">

    <!-- =========================
         CONTENT
    ========================== -->

    <div class="content">

        <?= $this->renderSection('content') ?>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Tutup saat link di-klik pada layar kecil
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