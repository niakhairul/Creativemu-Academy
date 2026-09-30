<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta - Creativemu Academy</title>
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
            background: linear-gradient(120deg, #f8f6ff, #f1ecff, #faf5ff, #eee8ff);
            background-size: 300% 300%;
            animation: bgFlow 22s ease infinite;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 0.9rem;
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
            0% { background-position: 0% 0%, 0% 0%; }
            50% { background-position: 100% 100%, 100% 100%; }
            100% { background-position: 0% 0%, 0% 0%; }
        }

        .sidebar-brand {
            font-size: 1.2rem;
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
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.88rem;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.28), rgba(255, 255, 255, 0.12));
            box-shadow: 0 6px 18px rgba(20, 5, 60, 0.28), inset 3px 0 0 #ffd166;
        }

        .sidebar-menu a i {
            font-size: 1.1rem;
            margin-right: 10px;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 24px 28px;
            width: calc(100% - 260px);
        }

        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(124, 92, 250, 0.06);
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(124, 92, 250, 0.12) !important;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(124, 92, 250, 0.12);
        }

        .bg-purple-soft {
            background: linear-gradient(135deg, var(--purple-soft) 0%, var(--purple-soft2) 100%);
        }
        
        .text-purple-custom {
            color: var(--purple-mid);
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
            width: 32px;
            height: 32px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
        }

        .status-registration-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .registration-item {
            border: 1px solid rgba(124, 92, 250, 0.15);
            border-radius: 12px;
            background: #fff;
            padding: 12px 14px;
            transition: all 0.25s ease;
        }

        .registration-item:hover {
            transform: translateY(-2px);
            border-color: rgba(124, 92, 250, 0.35);
            box-shadow: 0 6px 16px rgba(124, 92, 250, 0.08);
        }

        .registration-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #efeaff, #ddd2ff);
            color: #6847df;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .registration-status {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .status-approved { color: #138a62; background: #e1f7ed; }
        .status-pending { color: #d88a00; background: #fff1d5; }
        .status-rejected { color: #d94b4b; background: #ffe2e2; }

        @media (max-width: 991.98px) {
            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #4a2fc9 0%, #7440e6 100%);
                color: white;
                padding: 12px 16px;
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
            <i class="bi bi-mortarboard-fill text-warning me-1"></i> Creativemu
        </span>
    </div>
    <a href="<?= base_url('pelatihan/pengaturan') ?>" class="text-white text-decoration-none small d-flex align-items-center gap-1 bg-white bg-opacity-20 px-2.5 py-1 rounded-pill">
        <i class="bi bi-person-circle"></i> <?= esc($user['nama'] ?? 'Peserta') ?>
    </a>
</div>

<!-- BACKDROP SIDEBAR MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebarMenu">
    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-10">
        <a href="#" class="sidebar-brand text-decoration-none d-flex align-items-center mb-0 pb-0 border-0">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Logo" class="rounded-3 me-2 shadow-sm object-fit-cover" style="width: 32px; height: 32px;">
            <div>
                <span class="fs-6 fw-bold d-block text-white lh-1">Creativemu</span>
                <span class="text-white-50" style="font-size: 0.6rem; letter-spacing: 0.5px;">ACADEMY</span>
            </div>
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarClose" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
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
        <li class="mt-3">
            <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </li>
    </ul>
</nav>

<div class="app-wrapper">
    <div class="main-content">
        <div class="container-fluid py-1">

            <!-- HEADER SAMBUTAN & DROPDOWN PILIHAN KELAS AKTIF -->
            <div class="row mb-3 align-items-center">
                <div class="col-lg-7 mb-2 mb-lg-0">
                    <h4 class="fw-bold mb-1" style="color: #5b3fd6; font-size: 1.35rem;">
                        Halo, <?= esc($user['nama'] ?? 'Peserta') ?> 👋
                    </h4>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">
                        Selamat datang kembali di dashboard peserta pelatihan.
                    </p>
                </div>
                
                <!-- DROPDOWN PILL PILIHAN KELAS AKTIF -->
                <div class="col-lg-5 text-lg-end">
                    <?php if (!empty($semua_kelas_peserta) && count($semua_kelas_peserta) > 0): ?>
                        <div class="dropdown d-inline-block w-100" style="max-width: 280px;">
                            <button class="btn bg-white border border-purple border-opacity-25 rounded-pill dropdown-toggle w-100 text-start px-3 py-1.5 shadow-sm d-flex align-items-center justify-content-between" type="button" id="dropdownKelasAktif" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="text-truncate d-flex align-items-center" style="font-size: 0.82rem;">
                                    <i class="bi bi-mortarboard-fill text-purple-custom me-2"></i> 
                                    <span class="text-muted me-1">Kelas:</span>
                                    <strong class="text-dark"><?= esc($pendaftaran['nama_kelas'] ?? 'Pilih Kelas') ?></strong>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 w-100 mt-2" aria-labelledby="dropdownKelasAktif">
                                <li><h6 class="dropdown-header text-uppercase text-muted" style="font-size: 0.65rem;">Ganti Kelas Aktif:</h6></li>
                                <?php foreach ($semua_kelas_peserta as $kp): ?>
                                    <li>
                                        <a class="dropdown-item rounded-2 py-1.5 small <?= (isset($pendaftaran['id_kelas']) && $pendaftaran['id_kelas'] == $kp['id_kelas']) ? 'active bg-purple-soft text-purple-custom fw-bold' : 'text-dark' ?>" 
                                           href="<?= base_url('peserta/dashboard?id_kelas=' . $kp['id_kelas']) ?>" style="font-size: 0.82rem;">
                                            <i class="bi bi-check2-circle me-1"></i><?= esc($kp['nama_kelas']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- KOTAK STATISTIK 4 KOLOM -->
            <div class="row mb-3">
                <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-3.5 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0" style="color: #5b3fd6; font-size: 1.2rem;"><?= $pendaftaran ? 1 : 0 ?></h4>
                                <p class="text-dark mb-0 fw-bold" style="font-size: 0.82rem;">Kelas Aktif</p>
                                <span class="text-muted" style="font-size: 0.7rem;">Sedang berlangsung</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-3.5 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-calendar-check-fill fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0" style="color: #5b3fd6; font-size: 1.2rem;"><?= esc($total_kehadiran ?? '0') ?>%</h4>
                                <p class="text-dark mb-0 fw-bold" style="font-size: 0.82rem;">Kehadiran</p>
                                <span class="text-muted" style="font-size: 0.7rem;">Total Kehadiran</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-3.5 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-clipboard-check-fill fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0" style="color: #5b3fd6; font-size: 1.2rem;"><?= esc($total_tugas ?? '0') ?></h4>
                                <p class="text-dark mb-0 fw-bold" style="font-size: 0.82rem;">Tugas</p>
                                <span class="text-muted" style="font-size: 0.7rem;">Belum Dikumpulkan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-3.5 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-award-fill fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0" style="color: #5b3fd6; font-size: 1.2rem;"><?= esc($total_sertifikat ?? '0') ?></h4>
                                <p class="text-dark mb-0 fw-bold" style="font-size: 0.82rem;">Sertifikat</p>
                                <span class="text-muted" style="font-size: 0.7rem;">Telah Diperoleh</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROFIL & STATUS PENDAFTARAN -->
            <div class="row mb-3">
                <!-- PROFIL SAYA -->
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-3.5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-2.5">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </div>
                                <h5 class="fw-bold mb-0" style="color: #5b3fd6; font-size: 1rem;">Profil Saya</h5>
                            </div>

                            <table class="table table-borderless align-middle mb-0" style="font-size: 0.85rem;">
                                <tr>
                                    <td width="110" class="text-muted fw-semibold py-1.5">NIS</td>
                                    <td class="fw-bold py-1.5">: <span class="badge bg-purple-soft text-purple-custom px-2 py-0.5"><?= esc($user['nis'] ?? $pendaftaran['nis'] ?? '-') ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold py-1.5">Nama</td>
                                    <td class="fw-bold text-dark py-1.5">: <?= esc($user['nama'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold py-1.5">Email</td>
                                    <td class="fw-bold text-dark py-1.5">: <?= esc($user['email'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold py-1.5">No HP</td>
                                    <td class="fw-bold text-dark py-1.5">: <?= esc($user['no_hp'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- STATUS PENDAFTARAN MULTI KELAS -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-3.5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-2.5">
                                    <i class="bi bi-clipboard-check fs-5"></i>
                                </div>
                                <h5 class="fw-bold mb-0" style="color: #5b3fd6; font-size: 1rem;">Status Pendaftaran</h5>
                            </div>

                            <?php
                                $dataPendaftaran = [];
                                if (!empty($semua_pendaftaran) && is_array($semua_pendaftaran)) {
                                    $dataPendaftaran = $semua_pendaftaran;
                                } elseif (!empty($semua_kelas_peserta) && is_array($semua_kelas_peserta)) {
                                    $dataPendaftaran = $semua_kelas_peserta;
                                } elseif (!empty($pendaftaran) && is_array($pendaftaran)) {
                                    $dataPendaftaran = [$pendaftaran];
                                }

                                $dataPendaftaranUnik = [];
                                $kelasSudahAda = [];
                                foreach ($dataPendaftaran as $item) {
                                    $idKelasItem = $item['id_kelas'] ?? $item['id'] ?? null;
                                    $keyKelas = $idKelasItem !== null ? 'kelas_' . $idKelasItem : md5(json_encode($item));
                                    if (!isset($kelasSudahAda[$keyKelas])) {
                                        $dataPendaftaranUnik[] = $item;
                                        $kelasSudahAda[$keyKelas] = true;
                                    }
                                }
                                $dataPendaftaran = $dataPendaftaranUnik;
                            ?>

                            <?php if (empty($dataPendaftaran)): ?>
                                <div class="text-center py-3">
                                    <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill mb-2" style="font-size: 0.75rem;">Belum Mendaftar</span>
                                    <p class="text-muted small mb-2">Anda belum terdaftar di kelas pelatihan apapun.</p>
                                    <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-sm text-white fw-bold px-3 py-1.5 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #7c5cfa, #5b3fd6); font-size: 0.8rem;">
                                        Pilih Kelas Sekarang
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="status-registration-list" style="max-height: 160px; overflow-y: auto;">
                                    <?php foreach ($dataPendaftaran as $kelasItem): ?>
                                        <?php
                                            $namaKelasItem = $kelasItem['nama_kelas'] ?? 'Kelas Pelatihan';
                                            $jadwalItem = $kelasItem['jadwal_kelas'] ?? $kelasItem['tanggal_mulai_kelas'] ?? '-';
                                            $statusDaftarItem = strtolower(trim($kelasItem['status_pendaftaran'] ?? ''));
                                            $statusBayarItem = strtolower(trim($kelasItem['status_pembayaran'] ?? ''));

                                            if (in_array($statusDaftarItem, ['disetujui', 'approved', 'diterima'], true) || in_array($statusBayarItem, ['terkonfirmasi', 'valid', 'lunas'], true)) {
                                                $labelStatus = 'Disetujui'; $statusClass = 'status-approved'; $statusIcon = 'bi-check-circle-fill';
                                            } elseif (in_array($statusDaftarItem, ['ditolak', 'rejected', 'batal'], true)) {
                                                $labelStatus = 'Ditolak'; $statusClass = 'status-rejected'; $statusIcon = 'bi-x-circle-fill';
                                            } else {
                                                $labelStatus = 'Menunggu'; $statusClass = 'status-pending'; $statusIcon = 'bi-clock-fill';
                                            }
                                        ?>
                                        <div class="registration-item py-2 px-3">
                                            <div class="d-flex align-items-center justify-content-between gap-2">
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <div class="registration-icon" style="width: 34px; height: 34px; min-width: 34px; font-size: 0.9rem;">
                                                        <i class="bi bi-laptop-fill"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.82rem;"><?= esc($namaKelasItem) ?></h6>
                                                        <span class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-calendar-event me-1"></i> Mulai: <?= esc($jadwalItem) ?></span>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="registration-status <?= $statusClass ?> d-inline-block mb-0.5">
                                                        <i class="bi <?= $statusIcon ?> me-0.5"></i> <?= $labelStatus ?>
                                                    </span>
                                                    <div>
                                                        <?php 
                                                        $idPendaftaranItem = $kelasItem['id_pendaftaran'] ?? ''; 
                                                        ?>
                                                        <a href="<?= base_url('pelatihan/detailPendaftaran/' . $idPendaftaranItem) ?>" class="text-decoration-none fw-bold text-purple-custom" style="font-size: 0.7rem;">
                                                            Detail &rarr;
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

            <!-- JADWAL PELATIHAN & PENGUMUMAN -->
            <div class="row">
                <!-- JADWAL PELATIHAN -->
                <div class="col-lg-7 mb-3">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-3.5">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-2.5">
                                    <i class="bi bi-calendar-range fs-5"></i>
                                </div>
                                <h5 class="fw-bold mb-0" style="color: #5b3fd6; font-size: 1rem;">Jadwal Pelatihan & Materi</h5>
                            </div>

                            <?php if (!empty($list_jadwal) && is_array($list_jadwal)): ?>
                                <div class="d-flex flex-column gap-2.5">
                                    <?php foreach ($list_jadwal as $jdl): ?>
                                        <div class="p-3 border border-purple border-opacity-25 rounded-3 bg-white shadow-sm">
                                            <div class="d-flex justify-content-between align-items-center mb-1.5">
                                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.85rem;"><?= esc($jdl['materi'] ?? 'Materi Pertemuan'); ?></h6>
                                                <span class="badge bg-purple-soft text-purple-custom px-2 py-0.5" style="font-size: 0.7rem;"><?= esc($jdl['status'] ?? 'Terjadwal'); ?></span>
                                            </div>
                                            <p class="text-muted mb-0.5" style="font-size: 0.78rem;"><i class="bi bi-calendar-event text-purple-custom me-1.5"></i><strong>Tanggal:</strong> <?= esc($jdl['tanggal_kbm'] ?? '-'); ?></p>
                                            <p class="text-muted mb-0" style="font-size: 0.78rem;"><i class="bi bi-clock text-purple-custom me-1.5"></i><strong>Waktu:</strong> <?= esc($jdl['waktu_mulai'] ?? '-') ?> - <?= esc($jdl['waktu_selesai'] ?? '-') ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <div class="text-muted mb-2"><i class="bi bi-calendar-x fs-2 opacity-50"></i></div>
                                    <p class="text-muted small mb-0">Belum ada jadwal pelatihan atau materi yang dikirimkan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: PENGUMUMAN & KETENTUAN KEHADIRAN -->
                <div class="col-lg-5 mb-3">
                    <div class="d-flex flex-column gap-3">
                        
                        <!-- BLOK PENGUMUMAN / NOTIFIKASI KELUARNYA NILAI UJIAN (MUNCUL UNTUK KEDUANYA JIKA NILAI KELUAR) -->
                        <?php if (!empty($statusUjianKeluar)): ?>
                            <?php if (!empty($is_lulus) && $is_lulus === true): ?>
                                <!-- Kondisi Lulus: Mengisi Angket Evaluasi -->
                                <div class="p-3 rounded-3 border-0 position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-left: 4px solid #f59e0b !important;">
                                    <div class="d-flex align-items-start gap-2.5">
                                        <div class="text-warning fs-5 lh-1 mt-0.5"><i class="fas fa-bullhorn"></i></div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Pengisian Angket Kelulusan!</h6>
                                            <p class="text-muted mb-2" style="font-size: 0.78rem; line-height: 1.4;">
                                                Selamat! Nilai ujian Anda telah keluar dan dinyatakan <strong class="text-dark">LULUS</strong>. Segera isi formulir angket evaluasi untuk membuka akses sertifikat.
                                            </p>
                                            <a href="<?= base_url('pelatihan/kelas?id_kelas=' . ($pendaftaran['id_kelas'] ?? '')) ?>" class="btn btn-warning btn-sm text-dark fw-bold px-3 py-1 rounded-pill shadow-sm" style="font-size: 0.75rem; background-color: #f59e0b; border: none;">
                                                <i class="fas fa-clipboard-list me-1"></i> Isi Angket Sekarang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Kondisi Belum Lulus: Nilai Sudah Keluar Tanpa Angket -->
                                <div class="p-3 rounded-3 border-0 position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 4px solid #3b82f6 !important;">
                                    <div class="d-flex align-items-start gap-2.5">
                                        <div class="text-primary fs-5 lh-1 mt-0.5"><i class="fas fa-info-circle"></i></div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Informasi Nilai Ujian</h6>
                                            <p class="text-muted mb-2" style="font-size: 0.78rem; line-height: 1.4;">
                                                Nilai ujian Anda sudah keluar. Silakan periksa hasil lengkap pada menu kelas untuk melihat detail pencapaian Anda.
                                            </p>
                                            <a href="<?= base_url('pelatihan/kelas?id_kelas=' . ($pendaftaran['id_kelas'] ?? '')) ?>" class="btn btn-primary btn-sm text-white fw-bold px-3 py-1 rounded-pill shadow-sm" style="font-size: 0.75rem; background-color: #3b82f6; border: none;">
                                                <i class="fas fa-book-open me-1"></i> Cek Nilai di Kelas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- KETENTUAN KEHADIRAN (Card Kecil Profesional) -->
                        <div class="p-3 border border-light rounded-3 bg-white shadow-sm">
                            <div class="d-flex align-items-start gap-2.5">
                                <div class="text-primary fs-5 lh-1 mt-0.5">
                                    <i class="bi bi-info-circle-fill"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.85rem;">Ketentuan Kehadiran</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.78rem; line-height: 1.4;">
                                        Pastikan selalu melakukan absensi pada setiap sesi pertemuan agar persentase kehadiran memenuhi syarat minimal 80%.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
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
});
</script>
</body>
</html>