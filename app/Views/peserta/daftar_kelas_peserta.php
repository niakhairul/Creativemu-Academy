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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
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
            box-sizing: border-box;
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
            padding: 22px 26px 40px;
            width: calc(100% - 220px);
        }

        /* Hero Header Banner */
        .header-banner {
            background: linear-gradient(135deg, #5b3fd6 0%, #7b5af6 48%, #df6be0 100%);
            border-radius: 18px;
            color: white;
            padding: 22px 26px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(91, 63, 214, 0.22);
        }

        .header-banner::before {
            content: '';
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.16) 0%, rgba(255,255,255,0) 70%);
            top: -80px;
            right: -60px;
            pointer-events: none;
        }

        .btn-tambah-kelas {
            background: #ffffff;
            color: var(--purple-deep);
            font-weight: 700;
            font-size: 13px;
            padding: 10px 20px;
            border-radius: 50rem;
            box-shadow: 0 6px 16px rgba(20, 10, 60, 0.16);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
            border: none;
        }

        .btn-tambah-kelas:hover {
            background: var(--purple-soft);
            color: var(--purple-deep);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(20, 10, 60, 0.22);
        }

        /* Summary & Filter Bar */
        .filter-toolbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(123, 90, 246, 0.14);
            border-radius: 16px;
            padding: 14px 18px;
            box-shadow: 0 4px 16px rgba(123, 90, 246, 0.05);
        }

        .status-tab-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .status-tab-btn {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 12.5px;
            padding: 7px 14px;
            border-radius: 50rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .status-tab-btn:hover {
            border-color: #cbd5e1;
            background: #f1f5f9;
            color: #1e293b;
        }

        .status-tab-btn.active {
            background: linear-gradient(135deg, var(--purple-deep), var(--color-purple));
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(123, 90, 246, 0.25);
        }

        .status-tab-count {
            background: rgba(15, 23, 42, 0.08);
            color: inherit;
            font-size: 11px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 50rem;
        }

        .status-tab-btn.active .status-tab-count {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
        }

        .search-input-wrap {
            position: relative;
        }

        .search-input-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 13px;
        }

        .search-input-wrap .form-control {
            padding-left: 34px;
            border-radius: 50rem;
            font-size: 12.5px;
            border-color: #e2e8f0;
            height: 36px;
        }

        .search-input-wrap .form-control:focus {
            border-color: var(--color-purple);
            box-shadow: 0 0 0 3px rgba(123, 90, 246, 0.12);
        }

        /* Modern Course Card */
        .course-card {
            border: 1px solid rgba(123, 90, 246, 0.14);
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 6px 20px rgba(30, 41, 59, 0.05);
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            position: relative;
        }

        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(123, 90, 246, 0.12);
            border-color: rgba(123, 90, 246, 0.28);
        }

        .card-status-strip {
            height: 5px;
            width: 100%;
        }
        .strip-siap_diakses { background: linear-gradient(90deg, #10b981, #059669); }
        .strip-disetujui { background: linear-gradient(90deg, #0ea5e9, #2563eb); }
        .strip-menunggu { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .strip-ditolak { background: linear-gradient(90deg, #ef4444, #dc2626); }

        .card-header-clean {
            padding: 16px 18px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        /* Status Badges (4 Kondisi) */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 50rem;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }
        .status-pill-siap_diakses {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .status-pill-disetujui {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .status-pill-menunggu {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .status-pill-ditolak {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* Category & Type Badges */
        .badge-kategori {
            background: var(--purple-soft);
            color: var(--purple-deep);
            font-weight: 700;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-jenis-privat {
            background: linear-gradient(135deg, #6d28d9, #9333ea);
            color: #ffffff;
            font-weight: 700;
            font-size: 10.5px;
            padding: 3.5px 9px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .badge-jenis-reguler {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 700;
            font-size: 10.5px;
            padding: 3.5px 9px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .course-title {
            font-size: 15.5px;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.35;
            margin: 10px 0 4px;
        }

        /* Info Grid inside Card */
        .course-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
        }

        .course-info-box {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            min-width: 0;
        }

        .course-info-box.full-span {
            grid-column: 1 / -1;
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
        }

        .info-icon-circle {
            width: 28px;
            height: 28px;
            min-width: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            background: #ffffff;
            color: var(--color-purple);
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);
        }

        .info-label {
            font-size: 10.5px;
            color: #64748b;
            font-weight: 600;
            display: block;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .info-val {
            font-size: 12px;
            color: #1e293b;
            font-weight: 700;
            display: block;
            line-height: 1.3;
            word-break: break-word;
        }

        /* Status Notice Box */
        .status-notice-box {
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 11.5px;
            line-height: 1.4;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 12px;
        }
        .notice-menunggu {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }
        .notice-disetujui {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }
        .notice-ditolak {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* Action Buttons */
        .btn-kbm {
            background: linear-gradient(135deg, var(--purple-deep), var(--color-purple));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 14px;
            font-weight: 700;
            font-size: 12.5px;
            box-shadow: 0 4px 12px rgba(91, 63, 214, 0.22);
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-kbm:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(91, 63, 214, 0.32);
        }

        .btn-detail-outline {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 9px 14px;
            font-weight: 700;
            font-size: 12.5px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-detail-outline:hover {
            background: var(--purple-soft);
            color: var(--purple-deep);
            border-color: rgba(123, 90, 246, 0.3);
        }

        .empty-state-card {
            background: rgba(255, 255, 255, 0.94);
            border-radius: 18px;
            border: 1.5px dashed rgba(123, 90, 246, 0.25);
            padding: 42px 24px;
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
                padding: 16px 14px 32px !important;
            }
            .header-banner {
                padding: 18px 20px;
            }
        }

        @media (max-width: 575.98px) {
            .course-info-grid {
                grid-template-columns: 1fr;
            }
            .course-info-box {
                border-bottom: 1px dashed #e2e8f0;
                padding-bottom: 8px;
            }
            .course-info-box:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }
            .course-info-box.full-span {
                border-top: none;
                padding-top: 0;
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
        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand text-decoration-none d-flex flex-column align-items-center w-100 mb-0 pb-0 border-0 text-center">
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

<?php
    $daftarKelas = (!empty($kelas) && is_array($kelas)) ? $kelas : [];
?>

<div class="app-wrapper">
    <div class="main-content">
        <div class="container-fluid px-0">
            
            <!-- Hero Header Banner -->
            <div class="header-banner mb-3">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8">
                        <span class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill mb-2 fw-semibold" style="font-size: 11px;">
                            <i class="bi bi-journals me-1"></i> Portal Kelas Pelatihan Peserta
                        </span>
                        <h4 class="fw-bold mb-1" style="font-size: 21px;">Daftar Kelas Saya</h4>
                        <p class="mb-0 text-white text-opacity-75" style="font-size: 13px;">
                            Pantau status persetujuan kelas reguler maupun privat, jadwal pelaksanaan, dan akses ruang belajar Anda.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="<?= base_url('pelatihan/pendaftaran') ?>" class="btn-tambah-kelas">
                            <i class="bi bi-plus-circle-fill" style="color: var(--color-purple);"></i> Daftar Kelas Baru
                        </a>
                    </div>
                </div>
            </div>

            <?php if (!empty($daftarKelas)): ?>
                <!-- Grid Card Kelas -->
                <div class="row g-3" id="kelasCardsGrid">
                    <?php foreach ($daftarKelas as $k): ?>
                        <?php
                            $idPendaftaran = (int) ($k['id_pendaftaran'] ?? 0);
                            $idKelas       = (int) ($k['id_kelas'] ?? 0);
                            $namaKelas     = $k['nama_kelas'] ?? $k['pilihan_kelas'] ?? $k['pilihan_pelatihan'] ?? 'Kelas Pelatihan';
                            $kategoriKelas = $k['kategori_kelas'] ?? 'Pelatihan';
                            $jenisKelas    = $k['jenis_kelas'] ?? 'Reguler';
                            $isPrivat      = strtolower(trim((string) $jenisKelas)) === 'privat';
                            $metodeBelajar = !empty($k['metode_pembelajaran']) && $k['metode_pembelajaran'] !== '-' ? ucfirst($k['metode_pembelajaran']) : 'Tatap Muka / Online';
                            $lokasiKelas   = !empty($k['lokasi_pelatihan']) && $k['lokasi_pelatihan'] !== '-' ? $k['lokasi_pelatihan'] : null;

                            $kodeStatus    = $k['kode_status'] ?? 'menunggu';
                            $labelStatus   = $k['label_status'] ?? 'Menunggu Persetujuan';
                            $bisaDiakses   = !empty($k['bisa_diakses']);

                            $statusIcon = 'bi-hourglass-split';
                            if ($kodeStatus === 'siap_diakses') {
                                $statusIcon = 'bi-play-circle-fill';
                            } elseif ($kodeStatus === 'disetujui') {
                                $statusIcon = 'bi-check-circle-fill';
                            } elseif ($kodeStatus === 'ditolak') {
                                $statusIcon = 'bi-x-circle-fill';
                            }

                            $jadwalTampil = null;
                            if ($bisaDiakses) {
                                if (!empty($k['jadwal_kelas']) && $k['jadwal_kelas'] !== '-') {
                                    $jadwalTampil = $k['jadwal_kelas'];
                                } elseif (!empty($k['tanggal_mulai_kelas']) && $k['tanggal_mulai_kelas'] !== '-') {
                                    $jadwalTampil = date('d M Y', strtotime($k['tanggal_mulai_kelas']));
                                }
                            }

                            $materiKhusus = !empty($k['custom_materi']) ? $k['custom_materi'] : (!empty($k['materi_privat']) ? $k['materi_privat'] : null);
                        ?>
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="course-card">
                                <div class="card-status-strip strip-<?= esc($kodeStatus) ?>"></div>

                                <!-- Card Header: Badges & Title -->
                                <div class="card-header-clean">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                        <div class="d-flex align-items-center flex-wrap gap-1.5">
                                            <span class="badge-kategori">
                                                <i class="bi bi-tag-fill"></i> <?= esc($kategoriKelas) ?>
                                            </span>
                                            <?php if ($isPrivat): ?>
                                                <span class="badge-jenis-privat">
                                                    <i class="bi bi-shield-lock-fill"></i> Privat
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-jenis-reguler">
                                                    <i class="bi bi-people-fill"></i> Reguler
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <span class="status-pill status-pill-<?= esc($kodeStatus) ?>">
                                            <i class="bi <?= $statusIcon ?>"></i> <?= esc($labelStatus) ?>
                                        </span>
                                    </div>

                                    <h5 class="course-title"><?= esc($namaKelas) ?></h5>

                                    <?php if ($isPrivat && !empty($materiKhusus)): ?>
                                        <div class="mt-1.5">
                                            <span class="badge bg-purple-soft text-dark border rounded-pill px-2.5 py-1 fw-semibold" style="font-size: 11px; background: #f5f3ff; border-color: #ddd6fe !important; color: #5b21b6 !important;">
                                                <i class="bi bi-journal-bookmark-fill me-1"></i> Materi: <?= esc($materiKhusus) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Card Body: Structured Information & Actions -->
                                <div class="card-body d-flex flex-column p-3">
                                    <div class="course-info-grid">
                                        <!-- Metode Pembelajaran -->
                                        <div class="course-info-box">
                                            <div class="info-icon-circle">
                                                <i class="bi bi-laptop"></i>
                                            </div>
                                            <div>
                                                <span class="info-label">Metode Belajar</span>
                                                <span class="info-val"><?= esc($metodeBelajar) ?></span>
                                            </div>
                                        </div>

                                        <!-- Mentor / Instruktur -->
                                        <div class="course-info-box">
                                            <div class="info-icon-circle">
                                                <i class="bi bi-person-badge"></i>
                                            </div>
                                            <div>
                                                <span class="info-label">Instruktur</span>
                                                <?php if (in_array($kodeStatus, ['siap_diakses', 'disetujui'], true)): ?>
                                                    <span class="info-val"><?= esc(!empty($k['nama_mentor']) && $k['nama_mentor'] !== '-' ? $k['nama_mentor'] : 'Sedang Dijadwalkan') ?></span>
                                                <?php elseif ($kodeStatus === 'menunggu'): ?>
                                                    <span class="info-val text-muted fw-normal fst-italic" style="font-size: 11.5px;">Menunggu persetujuan</span>
                                                <?php else: ?>
                                                    <span class="info-val text-muted fw-normal">-</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Jadwal Pelatihan (Hanya ditampilkan setelah disetujui) -->
                                        <div class="course-info-box full-span">
                                            <div class="info-icon-circle" style="color: var(--color-orange);">
                                                <i class="bi bi-calendar-week"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="info-label">Jadwal Pelatihan</span>
                                                <?php if ($bisaDiakses && !empty($jadwalTampil)): ?>
                                                    <span class="info-val text-success">
                                                        <i class="bi bi-calendar-check me-1"></i> <?= esc($jadwalTampil) ?>
                                                        <?php if (!empty($k['waktu_mulai']) && $k['waktu_mulai'] !== '-'): ?>
                                                            <span class="text-muted fw-semibold ms-1">(<?= esc(substr($k['waktu_mulai'], 0, 5)) ?> WIB)</span>
                                                        <?php endif; ?>
                                                    </span>
                                                <?php elseif ($kodeStatus === 'disetujui'): ?>
                                                    <span class="info-val text-primary fw-semibold" style="font-size: 11.5px;">
                                                        Menunggu penetapan jadwal oleh akademik
                                                    </span>
                                                <?php elseif ($kodeStatus === 'menunggu'): ?>
                                                    <span class="info-val text-muted fw-normal fst-italic" style="font-size: 11.5px;">
                                                        Ditampilkan setelah kelas disetujui
                                                    </span>
                                                <?php else: ?>
                                                    <span class="info-val text-muted fw-normal" style="font-size: 11.5px;">Tidak tersedia</span>
                                                <?php endif; ?>

                                                <?php if ($bisaDiakses && !empty($lokasiKelas)): ?>
                                                    <small class="text-muted d-block mt-0.5" style="font-size: 11px;">
                                                        <i class="bi bi-geo-alt me-1"></i><?= esc($lokasiKelas) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notice sesuai kondisi status -->
                                    <?php if ($kodeStatus === 'menunggu'): ?>
                                        <div class="status-notice-box notice-menunggu">
                                            <i class="bi bi-info-circle-fill mt-0.5"></i>
                                            <div>
                                                <?php if ($isPrivat): ?>
                                                    Permintaan <strong>Kelas Privat</strong> sedang menunggu persetujuan sistem. Jadwal dan akses ruang kelas akan dibuka setelah disetujui.
                                                <?php else: ?>
                                                    Pendaftaran Anda sedang dalam proses verifikasi. Jadwal dan akses kelas akan tampil setelah disetujui.
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php elseif ($kodeStatus === 'disetujui'): ?>
                                        <div class="status-notice-box notice-disetujui">
                                            <i class="bi bi-calendar2-check-fill mt-0.5"></i>
                                            <div>
                                                Pendaftaran telah <strong>Disetujui</strong>. Tombol masuk kelas akan otomatis aktif begitu jadwal kelas tersedia.
                                            </div>
                                        </div>
                                    <?php elseif ($kodeStatus === 'ditolak'): ?>
                                        <div class="status-notice-box notice-ditolak">
                                            <i class="bi bi-exclamation-octagon-fill mt-0.5"></i>
                                            <div>
                                                Mohon maaf, pengajuan pendaftaran kelas ini tidak disetujui sehingga kelas tidak dapat diakses.
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Action Button -->
                                    <?php if ($bisaDiakses): ?>
                                        <div class="mt-auto pt-2">
                                            <a href="<?= base_url('pelatihan/kbm?id_kelas=' . $idKelas) ?>" class="btn-kbm w-100">
                                                <i class="bi bi-mortarboard-fill"></i> Masuk Ruang KBM
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="empty-state-card text-center">
                    <div class="mb-3">
                        <span class="p-3 rounded-circle bg-white d-inline-block shadow-sm" style="color: var(--color-purple);">
                            <i class="bi bi-journal-x fs-2"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: var(--color-purple); font-size: 16px;">Belum Ada Kelas yang Diikuti</h5>
                    <p class="text-muted mb-3 mx-auto" style="max-width: 400px; font-size: 13px;">
                        Anda belum terdaftar di kelas pelatihan reguler maupun privat. Silakan daftar kelas terlebih dahulu untuk mulai belajar.
                    </p>
                    <a href="<?= base_url('pelatihan/pendaftaran') ?>" class="btn-kbm px-4 py-2.5 rounded-pill d-inline-flex align-items-center shadow-sm" style="font-size: 13px;">
                        <i class="bi bi-plus-circle-fill me-1"></i> Daftar Kelas Sekarang
                    </a>
                </div>
            <?php endif; ?>

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
        timer: 3800,
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
        title: 'Perhatian',
        text: '<?= addslashes(session()->getFlashdata('error')) ?>',
        showConfirmButton: false,
        timer: 3800,
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