<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Laporan Kehadiran Peserta - CreativeMU Academy'); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-purple: #794bc4;
            --primary-purple-rgb: 121, 75, 196;
            --dark-purple: #22133c;
            --light-purple: #f3effa;
            --accent-purple: #9b6bf7;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --card-shadow: 0 8px 24px rgba(121, 75, 196, 0.08);
            --card-hover-shadow: 0 14px 30px rgba(121, 75, 196, 0.16);
            --sidebar-bg: #22133c;
            --sidebar-text: #d8cde9;
            --border-soft: #ede8f5;
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: #f7f6fc;
            color: #2b2b2b;
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* SIDEBAR STYLING */
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            overflow-y: auto;
        }

        #sidebar .sidebar-header {
            padding: 24px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        #sidebar .sidebar-header img {
            width: 220px;
            height: 85px;
            object-fit: cover;
            border-radius: 10px;
            filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
        }

        #sidebar .nav {
            padding: 20px 14px;
        }

        #sidebar .nav-item {
            margin-bottom: 6px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 18px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        #sidebar .nav-link i {
            width: 28px;
            font-size: 1.15rem;
        }

        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        #sidebar .nav-link.active {
            color: #ffffff;
            background: var(--sidebar-active-gradient);
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.35);
            font-weight: 600;
        }

        /* SUBMENU STYLING */
        .submenu-item .nav-link {
            padding: 9px 15px 9px 38px !important;
            font-size: 0.88rem !important;
            border-radius: 10px !important;
        }
        .submenu-item .nav-link.active {
            background: rgba(121, 75, 196, 0.4) !important;
            color: #ffffff !important;
            font-weight: 600;
        }

        /* MAIN CONTENT AREA */
        #main-content {
            margin-left: 275px;
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* TOP NAVBAR */
        .top-navbar {
            background: #ffffff;
            padding: 20px 28px;
            border-radius: 18px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-soft);
        }

        /* CARDS MODERN */
        .custom-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--card-shadow);
            padding: 24px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }
        .custom-card:hover {
            box-shadow: var(--card-hover-shadow);
        }

        /* STAT METRIC CARDS */
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--card-shadow);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-hover-shadow);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }
        .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #22133c;
            line-height: 1.1;
        }

        /* THEMES FOR STAT CARDS */
        .stat-theme-purple .stat-icon { background: #f3effa; color: var(--primary-purple); }
        .stat-theme-blue .stat-icon { background: #e8f3fc; color: #1976d2; }
        .stat-theme-green .stat-icon { background: #eafaf1; color: #2e7d32; }
        .stat-theme-warning .stat-icon { background: #fef8e7; color: #d97706; }
        .stat-theme-info .stat-icon { background: #e0f2fe; color: #0284c7; }
        .stat-theme-secondary .stat-icon { background: #f1f5f9; color: #64748b; }
        .stat-theme-danger .stat-icon { background: #fee2e2; color: #dc2626; }
        .stat-theme-primary .stat-icon { background: #ede9fe; color: #6d28d9; }

        /* BUTTONS */
        .btn-creative-primary {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(121, 75, 196, 0.25);
        }
        .btn-creative-primary:hover {
            background: linear-gradient(135deg, #6a3bb5 0%, #4b2382 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(121, 75, 196, 0.35);
        }

        .btn-excel {
            background: #107c41;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 124, 65, 0.2);
            transition: all 0.25s;
        }
        .btn-excel:hover {
            background: #0b5c30;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* FORMS */
        .form-select, .form-control {
            border-radius: 12px;
            border: 1.5px solid #dcd5ec;
            padding: 9px 13px;
            font-size: 0.88rem;
            color: #333;
        }
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.15);
        }

        /* TABLES MODERN */
        .table-custom {
            border-radius: 14px;
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        .table-custom thead th {
            background-color: var(--dark-purple);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.83rem;
            letter-spacing: 0.4px;
            padding: 13px 14px;
            border: none;
            cursor: pointer;
            user-select: none;
        }
        .table-custom thead th:hover {
            background-color: #35215c;
        }
        .table-custom thead th i.sort-icon {
            font-size: 0.75rem;
            margin-left: 4px;
            opacity: 0.6;
        }
        .table-custom tbody td {
            padding: 13px 14px;
            font-size: 0.88rem;
            border-bottom: 1px solid #f0ecfa;
            vertical-align: middle;
        }
        .table-custom tbody tr:nth-of-type(even) {
            background-color: #faf8fd;
        }
        .table-custom tbody tr:hover {
            background-color: #f2edf9;
        }

        /* MONITORING MINI CARDS */
        .monitoring-box {
            border-radius: 14px;
            padding: 16px;
            background: #fff;
            border: 1.5px solid #ede8f5;
            transition: all 0.25s;
        }
        .monitoring-box:hover {
            box-shadow: 0 6px 18px rgba(121, 75, 196, 0.08);
        }

        /* CHART BOX */
        .chart-box {
            position: relative;
            height: 270px;
            width: 100%;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; padding: 16px; }
            .mobile-toggle-btn { display: inline-block !important; }
        }
        .mobile-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-purple);
            margin-right: 15px;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- === SIDEBAR UTAMA DENGAN SUBMENU LAPORAN === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
            <?php if (!empty($isMentor)): ?>
                <div class="mt-2 text-white-50 small fw-semibold">PANEL MENTOR</div>
            <?php else: ?>
                <div class="mt-2 text-white-50 small fw-semibold">PANEL ADMIN</div>
            <?php endif; ?>
        </div>

        <ul class="nav flex-column">
            <?php if (!empty($isMentor)): ?>
                <li class="nav-item"><a href="<?= base_url('mentor/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a></li>
                <li class="nav-item"><a href="<?= base_url('mentor/kelas'); ?>" class="nav-link"><i class="fas fa-book"></i> <span>Daftar Kelas</span></a></li>
                
                <!-- Submenu Laporan Mentor -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanMentor" role="button" aria-expanded="true">
                        <i class="fas fa-file-lines"></i> <span>Laporan</span>
                        <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                    <div class="collapse show" id="submenuLaporanMentor">
                        <ul class="nav flex-column ms-2">
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('mentor/laporan-peserta'); ?>" class="nav-link <?= (url_is('mentor/laporan-peserta*')) ? 'active' : ''; ?>"><i class="fas fa-chart-pie me-2"></i> <span>Laporan Peserta</span></a>
                            </li>
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('mentor/laporan-mentor'); ?>" class="nav-link <?= (url_is('mentor/laporan-mentor*')) ? 'active' : ''; ?>"><i class="fas fa-chalkboard-user me-2"></i> <span>Laporan Mentor</span></a>
                            </li>
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('mentor/laporan-angket'); ?>" class="nav-link <?= (url_is('mentor/laporan-angket*')) ? 'active' : ''; ?>"><i class="fas fa-star-half-stroke me-2"></i> <span>Laporan Angket Mentor</span></a>
                            </li>
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('mentor/laporan-kehadiran'); ?>" class="nav-link <?= (url_is('mentor/laporan-kehadiran*')) ? 'active' : ''; ?>"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item"><a href="<?= base_url('mentor/profil'); ?>" class="nav-link"><i class="fas fa-user"></i> <span>Profil Mentor</span></a></li>
            <?php else: ?>
                <!-- Navigasi Admin -->
                <li class="nav-item"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book"></i> <span>Master Kelas</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user"></i> <span>Mentor</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link"><i class="fas fa-users"></i> <span>Data Peserta</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/validasi'); ?>" class="nav-link"><i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/angket'); ?>" class="nav-link"><i class="fas fa-poll"></i> <span>Angket</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-award"></i> <span>Sertifikat</span></a></li>
                
                <!-- Submenu Laporan Admin -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanAdmin" role="button" aria-expanded="true">
                        <i class="fas fa-chart-simple"></i> <span>Laporan</span>
                        <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem;"></i>
                    </a>
                    <div class="collapse show" id="submenuLaporanAdmin">
                        <ul class="nav flex-column ms-2">
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('admin/laporan-peserta'); ?>" class="nav-link <?= (url_is('admin/laporan-peserta*')) ? 'active' : ''; ?>"><i class="fas fa-chart-pie me-2"></i> <span>Laporan Peserta</span></a>
                            </li>
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('admin/laporan-mentor'); ?>" class="nav-link <?= (url_is('admin/laporan-mentor*')) ? 'active' : ''; ?>"><i class="fas fa-chalkboard-user me-2"></i> <span>Laporan Mentor</span></a>
                            </li>
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('admin/laporan-angket'); ?>" class="nav-link <?= (url_is('admin/laporan-angket*')) ? 'active' : ''; ?>"><i class="fas fa-star-half-stroke me-2"></i> <span>Laporan Angket Mentor</span></a>
                            </li>
                            <li class="nav-item submenu-item">
                                <a href="<?= base_url('admin/laporan-kehadiran'); ?>" class="nav-link <?= (url_is('admin/laporan-kehadiran*')) ? 'active' : ''; ?>"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear"></i> <span>Pengaturan</span></a></li>
            <?php endif; ?>
            <li class="nav-item mt-4"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <!-- === KONTEN UTAMA === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: var(--dark-purple);">Laporan Kehadiran Peserta</h4>
                    <p class="text-muted small mb-0">Rekapitulasi presensi, tingkat keaktifan, monitoring kedisiplinan, dan riwayat KBM</p>
                </div>
            </div>

            <!-- ACTION BUTTONS: EXPORT & CETAK -->
            <?php 
                $baseUrlReport = $isMentor ? 'mentor/laporan-kehadiran' : 'admin/laporan-kehadiran';
                $queryString = http_build_query($filters);
            ?>
            <div class="d-flex gap-2">
                <a href="<?= base_url($baseUrlReport . '/export-excel?' . $queryString); ?>" class="btn btn-excel btn-sm d-flex align-items-center">
                    <i class="fas fa-file-excel me-2"></i> <span>Download Excel</span>
                </a>
                <a href="<?= base_url($baseUrlReport . '/cetak?' . $queryString); ?>" target="_blank" class="btn btn-creative-primary btn-sm d-flex align-items-center">
                    <i class="fas fa-print me-2"></i> <span>Download PDF</span>
                </a>
            </div>
        </div>

        <!-- 1. SECTION FILTER (LENGKAP & REAKTIF) -->
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                    <i class="fas fa-filter text-primary me-2"></i> Filter Kehadiran Peserta
                </h6>
                <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                    <i class="fas fa-calendar-day text-primary me-1"></i> Periode: <?= esc($periodeText); ?>
                </span>
            </div>

            <form method="GET" action="<?= base_url($baseUrlReport); ?>" id="formFilterKehadiran">
                <div class="row g-3">
                    <!-- Tipe Periode -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted">Periode</label>
                        <select name="periode" id="filterPeriode" class="form-select form-select-sm" onchange="toggleMonthFilter()">
                            <option value="tahunan" <?= ($filters['periode'] === 'tahunan') ? 'selected' : ''; ?>>Tahunan</option>
                            <option value="bulanan" <?= ($filters['periode'] === 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y; ?>" <?= ($filters['tahun'] == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Bulan -->
                    <div class="col-12 col-sm-6 col-md-2" id="boxFilterBulan" style="<?= ($filters['periode'] === 'bulanan') ? '' : 'display:none;'; ?>">
                        <label class="form-label small fw-semibold text-muted">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <?php foreach ($bulanNames as $num => $name): ?>
                                <option value="<?= $num; ?>" <?= ($filters['bulan'] == $num) ? 'selected' : ''; ?>><?= $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Pelatihan (Kategori) -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Pelatihan</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="all">-- Semua Pelatihan --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat); ?>" <?= ($filters['kategori'] === $cat) ? 'selected' : ''; ?>>
                                    <?= esc($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kelas -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Kelas</label>
                        <select name="id_kelas" class="form-select form-select-sm">
                            <option value="all">-- Semua Kelas --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id_kelas']; ?>" <?= ($filters['id_kelas'] == $c['id_kelas']) ? 'selected' : ''; ?>>
                                    <?= esc($c['nama_kelas']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Peserta -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Peserta</label>
                        <select name="id_peserta" class="form-select form-select-sm">
                            <option value="all">-- Semua Peserta --</option>
                            <?php foreach ($participants as $p): ?>
                                <option value="<?= $p['id_pendaftaran']; ?>" <?= ($filters['id_peserta'] == $p['id_pendaftaran']) ? 'selected' : ''; ?>>
                                    <?= esc($p['nama_peserta']); ?> (<?= esc($p['nis']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Status Kehadiran -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Status Kehadiran</label>
                        <select name="status_kehadiran" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['status_kehadiran'] === 'all') ? 'selected' : ''; ?>>-- Semua Status --</option>
                            <option value="hadir" <?= ($filters['status_kehadiran'] === 'hadir') ? 'selected' : ''; ?>>Hadir Tepat Waktu</option>
                            <option value="terlambat" <?= ($filters['status_kehadiran'] === 'terlambat') ? 'selected' : ''; ?>>Terlambat</option>
                            <option value="izin" <?= ($filters['status_kehadiran'] === 'izin') ? 'selected' : ''; ?>>Izin</option>
                            <option value="sakit" <?= ($filters['status_kehadiran'] === 'sakit') ? 'selected' : ''; ?>>Sakit</option>
                            <option value="alpa" <?= ($filters['status_kehadiran'] === 'alpa') ? 'selected' : ''; ?>>Alpa (Tanpa Keterangan)</option>
                        </select>
                    </div>

                    <!-- Tombol Terapkan & Reset -->
                    <div class="col-12 col-md-6 d-flex align-items-end justify-content-md-end gap-2">
                        <a href="<?= base_url($baseUrlReport); ?>" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:10px;">
                            <i class="fas fa-rotate-left me-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-sm btn-creative-primary px-4">
                            <i class="fas fa-magnifying-glass me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. CARD RINGKASAN STATISTIK (SEMUA METRIK MENGIKUTI FILTER) -->
        <div class="row g-3 mb-4">
            <!-- 1. Total Peserta -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-purple">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="stat-label">Total Peserta</div>
                        <div class="stat-value"><?= number_format($stats['total_peserta']); ?></div>
                        <small class="text-muted">Terdaftar</small>
                    </div>
                </div>
            </div>

            <!-- 2. Total Pertemuan -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-blue">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div>
                        <div class="stat-label">Total Pertemuan</div>
                        <div class="stat-value"><?= number_format($stats['total_pertemuan']); ?></div>
                        <small class="text-muted">Sesi KBM</small>
                    </div>
                </div>
            </div>

            <!-- 3. Total Kehadiran -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-primary">
                    <div class="stat-icon"><i class="fas fa-clipboard-user"></i></div>
                    <div>
                        <div class="stat-label">Total Kehadiran</div>
                        <div class="stat-value"><?= number_format($stats['total_kehadiran']); ?></div>
                        <small class="text-muted">Siswa Masuk</small>
                    </div>
                </div>
            </div>

            <!-- 4. Hadir Tepat Waktu -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-green">
                    <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                    <div>
                        <div class="stat-label">Hadir</div>
                        <div class="stat-value text-success"><?= number_format($stats['total_hadir']); ?></div>
                        <small class="text-muted">Tepat Waktu</small>
                    </div>
                </div>
            </div>

            <!-- 5. Terlambat -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-warning">
                    <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
                    <div>
                        <div class="stat-label">Terlambat</div>
                        <div class="stat-value text-warning"><?= number_format($stats['total_terlambat']); ?></div>
                        <small class="text-muted">&gt; 15 Menit</small>
                    </div>
                </div>
            </div>

            <!-- 6. Izin -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-info">
                    <div class="stat-icon"><i class="fas fa-envelope-open-text"></i></div>
                    <div>
                        <div class="stat-label">Izin</div>
                        <div class="stat-value text-info"><?= number_format($stats['total_izin']); ?></div>
                        <small class="text-muted">Surat Izin</small>
                    </div>
                </div>
            </div>

            <!-- 7. Sakit -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-secondary">
                    <div class="stat-icon"><i class="fas fa-notes-medical"></i></div>
                    <div>
                        <div class="stat-label">Sakit</div>
                        <div class="stat-value text-secondary"><?= number_format($stats['total_sakit']); ?></div>
                        <small class="text-muted">Surat Dokter</small>
                    </div>
                </div>
            </div>

            <!-- 8. Alpa -->
            <div class="col-6 col-md-4 col-xl-3">
                <div class="stat-card stat-theme-danger">
                    <div class="stat-icon"><i class="fas fa-user-xmark"></i></div>
                    <div>
                        <div class="stat-label">Alpa</div>
                        <div class="stat-value text-danger"><?= number_format($stats['total_alpa']); ?></div>
                        <small class="text-muted">Tanpa Keterangan</small>
                    </div>
                </div>
            </div>

            <!-- 9. Persentase Kehadiran Rata-rata -->
            <div class="col-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #ffffff 0%, #f4edff 100%); border-left: 6px solid var(--primary-purple);">
                    <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon" style="background: var(--primary-purple); color: #fff; width: 54px; height: 54px; font-size: 1.6rem;">
                                <i class="fas fa-percent"></i>
                            </div>
                            <div>
                                <div class="stat-label">Tingkat Kehadiran Rata-rata Peserta</div>
                                <div class="fs-4 fw-bold text-dark">
                                    <span class="text-primary"><?= number_format($stats['persentase_kehadiran'], 1); ?>%</span> 
                                    <span class="fs-6 text-muted fw-normal ms-2">Akumulasi seluruh kelas yang dipilih</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <?php if ($stats['persentase_kehadiran'] >= 85): ?>
                                <span class="badge bg-success px-3 py-2 fs-6 rounded-pill"><i class="fas fa-circle-check me-1"></i> Status: Sangat Baik</span>
                            <?php elseif ($stats['persentase_kehadiran'] >= 75): ?>
                                <span class="badge bg-primary px-3 py-2 fs-6 rounded-pill"><i class="fas fa-circle-check me-1"></i> Status: Baik</span>
                            <?php else: ?>
                                <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill"><i class="fas fa-triangle-exclamation me-1"></i> Status: Perlu Perhatian</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. GRAFIK INFORMATIF & MONITORING TERTINGGI/TERENDAH -->
        <div class="row g-4 mb-4">
            <!-- Grafik 1: Distribusi Status Kehadiran (Donut) -->
            <div class="col-12 col-lg-4">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-pie text-primary me-2"></i> Status Kehadiran
                        </h6>
                        <span class="badge bg-light text-muted border">Distribusi</span>
                    </div>
                    <div class="chart-box d-flex justify-content-center align-items-center">
                        <canvas id="chartStatusDonut"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 2: Rata-rata Kehadiran per Kelas (Bar) -->
            <div class="col-12 col-lg-8">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-column text-success me-2"></i> Kehadiran per Kelas Pelatihan
                        </h6>
                        <span class="badge bg-light text-muted border">Perbandingan (%)</span>
                    </div>
                    <div class="chart-box">
                        <canvas id="chartKelasBar"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 3: Tren Kehadiran per Bulan (Line) -->
            <div class="col-12">
                <div class="custom-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-line text-info me-2"></i> Tren Kehadiran per Bulan (Tahun <?= esc($filters['tahun']); ?>)
                        </h6>
                        <span class="badge bg-light text-muted border">12 Bulan</span>
                    </div>
                    <div class="chart-box" style="height: 230px;">
                        <canvas id="chartTrenLine"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. MONITORING: KEHADIRAN TERTINGGI & TERENDAH -->
        <div class="row g-3 mb-4">
            <!-- Kehadiran Tertinggi -->
            <div class="col-12 col-md-6">
                <div class="monitoring-box border-success border-opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-success mb-0"><i class="fas fa-star text-warning me-2"></i> Kehadiran Tertinggi (Disiplin)</h6>
                        <span class="badge bg-success bg-opacity-10 text-success">Disiplin Tinggi</span>
                    </div>
                    <?php if (!empty($topLowest['highest'])): ?>
                        <div class="d-flex flex-column gap-2 mt-2">
                            <?php foreach ($topLowest['highest'] as $hi): ?>
                            <div class="p-2 rounded bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-dark small"><?= esc($hi['nama_peserta']); ?></strong>
                                    <div class="text-muted" style="font-size: 0.75rem;"><?= esc($hi['kelas']); ?> • NIS: <?= esc($hi['nis']); ?></div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success"><?= $hi['persentase_kehadiran']; ?>%</span>
                                    <div class="text-muted" style="font-size: 0.7rem;"><?= $hi['hadir']; ?>/<?= $hi['total_pertemuan']; ?> Sesi</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="small text-muted py-2">Belum ada data.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kehadiran Terendah (Perlu Perhatian) -->
            <div class="col-12 col-md-6">
                <div class="monitoring-box border-danger border-opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-danger mb-0"><i class="fas fa-triangle-exclamation text-danger me-2"></i> Perlu Perhatian (Kehadiran Rendah)</h6>
                        <span class="badge bg-danger bg-opacity-10 text-danger">Monitoring Khusus</span>
                    </div>
                    <?php if (!empty($topLowest['lowest'])): ?>
                        <div class="d-flex flex-column gap-2 mt-2">
                            <?php foreach ($topLowest['lowest'] as $lo): ?>
                            <div class="p-2 rounded bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-dark small"><?= esc($lo['nama_peserta']); ?></strong>
                                    <div class="text-muted" style="font-size: 0.75rem;"><?= esc($lo['kelas']); ?> • Alpa: <?= $lo['alpa']; ?> Sesi</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger"><?= $lo['persentase_kehadiran']; ?>%</span>
                                    <div class="text-muted" style="font-size: 0.7rem;"><?= $lo['hadir']; ?>/<?= $lo['total_pertemuan']; ?> Sesi</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="small text-muted py-2">Seluruh peserta memiliki presensi baik.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 5. TABEL REKAP KEHADIRAN PESERTA (SEARCH, SORTING, PAGINATION) -->
        <div class="custom-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                        <i class="fas fa-table-list text-primary me-2"></i> Rekapitulasi Kehadiran Peserta
                    </h6>
                    <small class="text-muted">Klik pada nama kolom untuk mengurutkan (sort) data, atau gunakan kotak pencarian cepat</small>
                </div>
                
                <!-- Quick Search Input -->
                <div class="d-flex gap-2 align-items-center">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="tableSearchInput" class="form-control border-start-0" placeholder="Cari nama, NIS, kelas..." onkeyup="filterTableLive()">
                    </div>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0" id="mainRekapTable">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center" onclick="sortTable(0)">No <i class="fas fa-sort sort-icon"></i></th>
                            <th width="11%" class="text-center" onclick="sortTable(1)">NIS <i class="fas fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(2)">Nama Peserta <i class="fas fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(3)">Kelas <i class="fas fa-sort sort-icon"></i></th>
                            <th onclick="sortTable(4)">Pelatihan <i class="fas fa-sort sort-icon"></i></th>
                            <th width="7%" class="text-center" onclick="sortTable(5, true)">Pertemuan <i class="fas fa-sort sort-icon"></i></th>
                            <th width="6%" class="text-center text-success" onclick="sortTable(6, true)">Hadir <i class="fas fa-sort sort-icon"></i></th>
                            <th width="6%" class="text-center" onclick="sortTable(7, true)">Izin <i class="fas fa-sort sort-icon"></i></th>
                            <th width="6%" class="text-center" onclick="sortTable(8, true)">Sakit <i class="fas fa-sort sort-icon"></i></th>
                            <th width="6%" class="text-center text-danger" onclick="sortTable(9, true)">Alpa <i class="fas fa-sort sort-icon"></i></th>
                            <th width="7%" class="text-center text-warning" onclick="sortTable(10, true)">Terlambat <i class="fas fa-sort sort-icon"></i></th>
                            <th width="9%" class="text-center" onclick="sortTable(11, true)">Persentase <i class="fas fa-sort sort-icon"></i></th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="8%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rekapTableBody">
                        <?php if (!empty($rekapList)): ?>
                            <?php $no = 1; foreach ($rekapList as $row): ?>
                            <tr class="table-row-item">
                                <td class="text-center text-muted fw-bold"><?= $no++; ?></td>
                                <td class="text-center"><code><?= esc($row['nis']); ?></code></td>
                                <td>
                                    <strong><?= esc($row['nama_peserta']); ?></strong>
                                    <div class="text-muted small" style="font-size: 0.75rem;"><?= esc($row['email']); ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= esc($row['kelas']); ?></span></td>
                                <td><span class="text-secondary small fw-semibold"><?= esc($row['pelatihan']); ?></span></td>
                                <td class="text-center fw-bold"><?= $row['total_pertemuan']; ?></td>
                                <td class="text-center fw-bold text-success"><?= $row['hadir']; ?></td>
                                <td class="text-center"><?= $row['izin']; ?></td>
                                <td class="text-center"><?= $row['sakit']; ?></td>
                                <td class="text-center fw-bold text-danger"><?= $row['alpa']; ?></td>
                                <td class="text-center fw-bold text-warning"><?= $row['terlambat']; ?></td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary fs-6"><?= number_format($row['persentase_kehadiran'], 1); ?>%</span>
                                    <div class="progress" style="height: 4px;">
                                        <div class="progress-bar <?= $row['badge_class']; ?>" style="width: <?= $row['persentase_kehadiran']; ?>%;"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $row['badge_class']; ?> px-2 py-1 rounded-pill">
                                        <?= esc($row['predikat']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1" style="border-radius:8px;" onclick="openModalDetail(<?= $row['id_pendaftaran']; ?>, '<?= esc($row['nama_peserta']); ?>')">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="emptyRow">
                                <td colspan="14" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-user fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6>Belum ada data kehadiran pada periode yang dipilih.</h6>
                                    <p class="small">Silakan sesuaikan filter tahun, bulan, atau kelas di atas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top" id="paginationControls">
                <div class="small text-muted" id="pageInfo">
                    Menampilkan <strong><span id="showingStart">1</span> - <span id="showingEnd"><?= count($rekapList); ?></span></strong> dari <strong><?= count($rekapList); ?></strong> data peserta
                </div>
                <div class="d-flex gap-1" id="pageButtons">
                    <!-- Dynamic Page Buttons -->
                </div>
            </div>
        </div>

    </div>

    <!-- === MODAL INTERAKTIF: RIWAYAT DETAIL KEHADIRAN PESERTA === -->
    <div class="modal fade" id="modalDetailKehadiran" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none;">
                <div class="modal-header text-white" style="background: var(--dark-purple);">
                    <div>
                        <h5 class="modal-title fw-bold" id="modalDetailLabel">Riwayat Detail Kehadiran Peserta</h5>
                        <p class="mb-0 small text-white-50" id="mPesertaSubtitle">Peserta: -</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background: #fbfafd; max-height: 75vh; overflow-y: auto;">
                    <div id="modalLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Memuat riwayat...</span>
                        </div>
                        <p class="text-muted small mt-2">Mengambil riwayat presensi KBM...</p>
                    </div>

                    <div id="modalContentContainer" style="display: none;">
                        <!-- Profil Singkat Peserta -->
                        <div class="p-3 bg-white border rounded-3 mb-3 shadow-sm">
                            <div class="row g-2 align-items-center">
                                <div class="col-12 col-md-8">
                                    <h6 class="fw-bold mb-1 text-dark" id="detNama">-</h6>
                                    <div class="small text-muted">
                                        NIS: <strong id="detNis">-</strong> | Kelas: <strong id="detKelas">-</strong>
                                    </div>
                                    <div class="small text-muted">
                                        Pelatihan: <span id="detPelatihan">-</span> | Mentor: <span id="detMentor">-</span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 text-md-end">
                                    <div class="fs-4 fw-bold text-primary" id="detPersentase">0%</div>
                                    <span class="badge bg-success" id="detBadge">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Riwayat Pertemuan -->
                        <h6 class="fw-bold mb-2 text-dark"><i class="fas fa-list-check text-primary me-2"></i> Log Pertemuan & Presensi KBM</h6>
                        <div class="table-responsive bg-white rounded-3 border">
                            <table class="table table-sm align-middle mb-0" style="font-size: 0.85rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="8%">Pert.</th>
                                        <th width="16%">Tanggal</th>
                                        <th>Materi Pembelajaran</th>
                                        <th width="14%">Jam Masuk</th>
                                        <th width="12%" class="text-center">Status</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody id="detTbodyRiwayat">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT GRAFIK, SEARCH, SORTING & PAGINATION -->
    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Toggle Month Filter based on Periode
        function toggleMonthFilter() {
            const periode = document.getElementById('filterPeriode').value;
            const boxBulan = document.getElementById('boxFilterBulan');
            boxBulan.style.display = (periode === 'bulanan') ? 'block' : 'none';
        }

        // Setup Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            const chartDataServer = <?= json_encode($chartData); ?>;

            // 1. Donut: Status Kehadiran
            const ctxDonut = document.getElementById('chartStatusDonut').getContext('2d');
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: chartDataServer.chart_status_donut.labels,
                    datasets: [{
                        data: chartDataServer.chart_status_donut.data,
                        backgroundColor: ['#2e7d32', '#f59e0b', '#0284c7', '#64748b', '#dc2626'],
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10.5 } } }
                    },
                    cutout: '65%'
                }
            });

            // 2. Bar: Kehadiran per Kelas
            const ctxBar = document.getElementById('chartKelasBar').getContext('2d');
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: chartDataServer.chart_kelas_bar.labels,
                    datasets: [{
                        label: 'Persentase Kehadiran (%)',
                        data: chartDataServer.chart_kelas_bar.data,
                        backgroundColor: 'rgba(121, 75, 196, 0.85)',
                        borderColor: '#794bc4',
                        borderWidth: 1.5,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                        x: { ticks: { font: { size: 11 } } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // 3. Line: Tren Kehadiran per Bulan (12 Bulan)
            const ctxLine = document.getElementById('chartTrenLine').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: chartDataServer.chart_tren_line.labels,
                    datasets: [{
                        label: 'Tren Kehadiran Bulanan (%)',
                        data: chartDataServer.chart_tren_line.data,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.1)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#0284c7',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { min: 80, max: 100, ticks: { callback: v => v + '%' } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Setup Client-side Pagination
            initPagination();
        });

        // LIVE SEARCH FILTER TABLE
        function filterTableLive() {
            const query = document.getElementById('tableSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#rekapTableBody tr.table-row-item');
            let matchCount = 0;

            rows.forEach(r => {
                const text = r.textContent.toLowerCase();
                if (text.includes(query)) {
                    r.style.display = '';
                    matchCount++;
                } else {
                    r.style.display = 'none';
                }
            });

            const emptyRow = document.getElementById('emptyRow');
            if (emptyRow) {
                emptyRow.style.display = (matchCount === 0 && rows.length > 0) ? '' : 'none';
            }
        }

        // SORTING TABLE BY COLUMN
        let sortDirections = {};
        function sortTable(colIndex, isNumeric = false) {
            const table = document.getElementById('mainRekapTable');
            const tbody = document.getElementById('rekapTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr.table-row-item'));

            const isAsc = !sortDirections[colIndex];
            sortDirections[colIndex] = isAsc;

            rows.sort((rowA, rowB) => {
                let cellA = rowA.children[colIndex].textContent.trim();
                let cellB = rowB.children[colIndex].textContent.trim();

                if (isNumeric) {
                    let numA = parseFloat(cellA.replace(/[^0-9.-]/g, '')) || 0;
                    let numB = parseFloat(cellB.replace(/[^0-9.-]/g, '')) || 0;
                    return isAsc ? (numA - numB) : (numB - numA);
                }

                return isAsc ? cellA.localeCompare(cellB) : cellB.localeCompare(cellA);
            });

            rows.forEach(r => tbody.appendChild(r));
        }

        // PAGINATION
        const rowsPerPage = 10;
        let currentPage = 1;

        function initPagination() {
            const rows = document.querySelectorAll('#rekapTableBody tr.table-row-item');
            const totalRows = rows.length;
            if (totalRows <= rowsPerPage) {
                document.getElementById('paginationControls').style.display = (totalRows > 0) ? 'flex' : 'none';
                return;
            }

            const totalPages = Math.ceil(totalRows / rowsPerPage);
            showPage(1, totalPages, rows);
        }

        function showPage(page, totalPages, rows) {
            currentPage = page;
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((r, idx) => {
                r.style.display = (idx >= start && idx < end) ? '' : 'none';
            });

            document.getElementById('showingStart').textContent = start + 1;
            document.getElementById('showingEnd').textContent = Math.min(end, rows.length);

            // Render buttons
            const container = document.getElementById('pageButtons');
            container.innerHTML = '';

            const prevBtn = document.createElement('button');
            prevBtn.className = 'btn btn-sm btn-outline-secondary px-2';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = (page === 1);
            prevBtn.onclick = () => showPage(page - 1, totalPages, rows);
            container.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const pBtn = document.createElement('button');
                pBtn.className = (i === page) ? 'btn btn-sm btn-creative-primary px-3' : 'btn btn-sm btn-outline-secondary px-3';
                pBtn.textContent = i;
                pBtn.onclick = () => showPage(i, totalPages, rows);
                container.appendChild(pBtn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = 'btn btn-sm btn-outline-secondary px-2';
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = (page === totalPages);
            nextBtn.onclick = () => showPage(page + 1, totalPages, rows);
            container.appendChild(nextBtn);
        }

        // AJAX MODAL DETAIL RIWAYAT KEHADIRAN
        function openModalDetail(idPendaftaran, namaPeserta) {
            const modalEl = document.getElementById('modalDetailKehadiran');
            const modal = new bootstrap.Modal(modalEl);

            document.getElementById('mPesertaSubtitle').textContent = 'Peserta: ' + namaPeserta;
            document.getElementById('modalLoadingSpinner').style.display = 'block';
            document.getElementById('modalContentContainer').style.display = 'none';

            modal.show();

            const url = '<?= base_url($baseUrlReport . "/detail"); ?>/' + idPendaftaran;

            fetch(url)
                .then(res => res.json())
                .then(resp => {
                    document.getElementById('modalLoadingSpinner').style.display = 'none';
                    document.getElementById('modalContentContainer').style.display = 'block';

                    if (resp.status === 'success' && resp.data) {
                        const p = resp.data.peserta;
                        document.getElementById('detNama').textContent = p.nama;
                        document.getElementById('detNis').textContent = p.nis;
                        document.getElementById('detKelas').textContent = p.kelas;
                        document.getElementById('detPelatihan').textContent = p.pelatihan;
                        document.getElementById('detMentor').textContent = p.mentor;
                        document.getElementById('detPersentase').textContent = p.persentase_kehadiran + '%';
                        
                        const badge = document.getElementById('detBadge');
                        badge.textContent = p.hadir + '/' + p.total_pertemuan + ' Sesi Hadir';

                        const tbody = document.getElementById('detTbodyRiwayat');
                        tbody.innerHTML = '';

                        if (resp.data.riwayat && resp.data.riwayat.length > 0) {
                            resp.data.riwayat.forEach(r => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td class="text-center fw-bold text-muted">${r.pertemuan_ke}</td>
                                    <td><strong>${escapeHtml(r.tanggal)}</strong><div class="text-muted small">${escapeHtml(r.jam_sesi)}</div></td>
                                    <td>${escapeHtml(r.materi)}</td>
                                    <td><strong class="text-dark">${escapeHtml(r.jam_masuk)}</strong></td>
                                    <td class="text-center"><span class="badge ${r.badge_class} px-2 py-1">${escapeHtml(r.status)}</span></td>
                                    <td class="small text-muted">${escapeHtml(r.keterangan)}</td>
                                `;
                                tbody.appendChild(tr);
                            });
                        } else {
                            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3 text-muted">Belum ada log presensi pada kelas ini.</td></tr>';
                        }
                    }
                })
                .catch(err => {
                    document.getElementById('modalLoadingSpinner').style.display = 'none';
                    document.getElementById('modalContentContainer').style.display = 'block';
                    document.getElementById('modalContentContainer').innerHTML = '<div class="alert alert-danger">Gagal memuat detail kehadiran. Silakan coba lagi.</div>';
                });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }
    </script>
</body>
</html>
