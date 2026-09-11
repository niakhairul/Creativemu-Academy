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
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .sidebar {
                width: 220px;
            }

            .content {
                margin-left: 220px;
                width: calc(100% - 220px);
                padding: 20px;
            }

            .sidebar-brand {
                font-size: 1.15rem;
            }

            .sidebar li a {
                padding: 11px 13px;
            }
        }

        @media (max-width: 576px) {

            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
                width: calc(100% - 200px);
                padding: 15px;
            }
        }
    </style>

</head>

<body>

<div class="dashboard-wrapper">

    <!-- =========================
         SIDEBAR PESERTA
    ========================== -->

    <div class="sidebar">

        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i>
            Creativemu
        </a>

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


    <!-- =========================
         CONTENT
    ========================== -->

    <div class="content">

        <?= $this->renderSection('content') ?>

    </div>

</div>

</body>

</html>