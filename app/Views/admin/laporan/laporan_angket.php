<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Laporan Angket Mentor - CreativeMU Academy'); ?></title>

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
            border-radius: 18px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--card-shadow);
            padding: 22px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-hover-shadow);
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: var(--primary-purple);
        }
        .stat-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-right: 18px;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: #22133c;
            line-height: 1.1;
        }

        /* THEME GRADIENTS FOR STATS */
        .stat-theme-purple .stat-icon { background: #f3effa; color: var(--primary-purple); }
        .stat-theme-purple::after { background: var(--primary-purple); }

        .stat-theme-blue .stat-icon { background: #e8f3fc; color: #1976d2; }
        .stat-theme-blue::after { background: #1976d2; }

        .stat-theme-gold .stat-icon { background: #fef8e7; color: #f59e0b; }
        .stat-theme-gold::after { background: #f59e0b; }

        .stat-theme-green .stat-icon { background: #eafaf1; color: #2e7d32; }
        .stat-theme-green::after { background: #2e7d32; }

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

        .btn-creative-outline {
            border: 1.5px solid var(--primary-purple);
            color: var(--primary-purple);
            background: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .btn-creative-outline:hover {
            background: var(--primary-purple);
            color: #ffffff;
            transform: translateY(-2px);
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
            padding: 10px 14px;
            font-size: 0.9rem;
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
            font-size: 0.85rem;
            letter-spacing: 0.4px;
            padding: 14px 16px;
            border: none;
        }
        .table-custom tbody td {
            padding: 14px 16px;
            font-size: 0.9rem;
            border-bottom: 1px solid #f0ecfa;
            vertical-align: middle;
        }
        .table-custom tbody tr:nth-of-type(even) {
            background-color: #faf8fd;
        }
        .table-custom tbody tr:hover {
            background-color: #f2edf9;
        }

        /* PODIUM / LEADERBOARD CARD */
        .podium-card {
            border-radius: 16px;
            border: 1.5px solid #ede8f5;
            padding: 20px;
            text-align: center;
            background: #ffffff;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
        }
        .podium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(121, 75, 196, 0.12);
        }
        .podium-badge {
            font-size: 2.2rem;
            margin-bottom: 6px;
        }
        .podium-score {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary-purple);
        }

        /* CHART BOX */
        .chart-box {
            position: relative;
            height: 290px;
            width: 100%;
        }

        /* PROGRESS BARS */
        .progress-custom {
            height: 10px;
            border-radius: 10px;
            background-color: #ebe5f8;
            overflow: hidden;
        }
        .progress-bar-custom {
            background: var(--sidebar-active-gradient);
            border-radius: 10px;
        }

        /* REVIEW CARDS */
        .review-card-item {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #ebe5f8;
            padding: 18px;
            transition: all 0.25s ease;
            position: relative;
            height: 100%;
        }
        .review-card-item:hover {
            box-shadow: 0 8px 20px rgba(121, 75, 196, 0.08);
            border-color: #cfc1ee;
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
                    <h4 class="mb-0 fw-bold" style="color: var(--dark-purple);">Laporan Angket Mentor</h4>
                    <p class="text-muted small mb-0">Hasil evaluasi, kepuasan peserta, dan analisis indikator penilaian pengajar</p>
                </div>
            </div>

            <!-- ACTION BUTTONS: EXPORT & CETAK -->
            <?php 
                $baseUrlReport = $isMentor ? 'mentor/laporan-angket' : 'admin/laporan-angket';
                $queryString = http_build_query($filters);
            ?>
            <div class="d-flex gap-2">
                <a href="<?= base_url($baseUrlReport . '/export-excel?' . $queryString); ?>" class="btn btn-excel btn-sm d-flex align-items-center">
                    <i class="fas fa-file-excel me-2"></i> <span>Download Excel</span>
                </a>
                <a href="<?= base_url($baseUrlReport . '/cetak?' . $queryString); ?>" target="_blank" class="btn btn-creative-primary btn-sm d-flex align-items-center">
                    <i class="fas fa-print me-2"></i> <span>Cetak / PDF</span>
                </a>
            </div>
        </div>

        <!-- 1. FILTER SECTION -->
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0" style="color: var(--dark-purple);"><i class="fas fa-filter text-primary me-2"></i> Filter Data Evaluasi Angket</h6>
                <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                    <i class="fas fa-calendar-day text-primary me-1"></i> Periode: <?= esc($periodeText); ?>
                </span>
            </div>

            <form method="GET" action="<?= base_url($baseUrlReport); ?>" id="formFilterAngket">
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

                    <!-- Nama Mentor -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Nama Mentor</label>
                        <select name="id_mentor" class="form-select form-select-sm" <?= (!empty($isMentor)) ? 'disabled' : ''; ?>>
                            <?php if (empty($isMentor)): ?>
                                <option value="all">-- Semua Mentor --</option>
                            <?php endif; ?>
                            <?php foreach ($mentors as $m): ?>
                                <option value="<?= $m['id_mentor']; ?>" <?= ($filters['id_mentor'] == $m['id_mentor']) ? 'selected' : ''; ?>>
                                    <?= esc($m['nama_mentor']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Pelatihan / Kelas -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label small fw-semibold text-muted">Pelatihan / Kelas</label>
                        <select name="id_kelas" class="form-select form-select-sm">
                            <option value="all">-- Semua Kelas Pelatihan --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id_kelas']; ?>" <?= ($filters['id_kelas'] == $c['id_kelas']) ? 'selected' : ''; ?>>
                                    <?= esc($c['nama_kelas']); ?> (<?= esc($c['kategori'] ?? 'Umum'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tombol Filter -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-3">
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

        <!-- 2. DASHBOARD RINGKASAN METRIK (4 KARTU STATISTIK) -->
        <div class="row g-4 mb-4">
            <!-- Total Mentor Dinilai -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card stat-theme-purple">
                    <div class="stat-icon">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Mentor Dinilai</div>
                        <div class="stat-value"><?= number_format($stats['total_mentor_dinilai']); ?></div>
                        <span class="badge bg-light text-muted small mt-1">Instruktur Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Total Responden -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card stat-theme-blue">
                    <div class="stat-icon">
                        <i class="fas fa-users-viewfinder"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Responden</div>
                        <div class="stat-value"><?= number_format($stats['total_responden']); ?></div>
                        <span class="badge bg-light text-primary small mt-1">Peserta Mengisi</span>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Nilai Angket -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card stat-theme-gold">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <div class="stat-label">Rata-rata Nilai Angket</div>
                        <div class="stat-value"><?= number_format($stats['avg_nilai_angket'], 2); ?> <span class="fs-6 text-muted">/ 5.0</span></div>
                        <span class="badge bg-warning bg-opacity-10 text-dark small mt-1">Skala 1.0 - 5.0</span>
                    </div>
                </div>
            </div>

            <!-- Persentase Kepuasan -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card stat-theme-green">
                    <div class="stat-icon">
                        <i class="fas fa-smile-beam"></i>
                    </div>
                    <div>
                        <div class="stat-label">Persentase Kepuasan</div>
                        <div class="stat-value text-success"><?= number_format($stats['persen_kepuasan'], 1); ?>%</div>
                        <span class="badge bg-success bg-opacity-10 text-success small mt-1">Indeks Sangat Baik</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. SECTION LEADERBOARD / PODIUM PERFORMA MENTOR (TOP 3) -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="custom-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                            <i class="fas fa-trophy text-warning me-2"></i> Leaderboard Performa & Kepuasan Mentor
                        </h6>
                        <span class="badge bg-purple-subtle text-primary fw-normal">Diurutkan Berdasarkan Nilai Angket & Jumlah Responden</span>
                    </div>

                    <div class="row g-3">
                        <?php 
                            $medals = ['🥇 Juara 1', '🥈 Juara 2', '🥉 Juara 3'];
                            $podiumBorder = ['#f59e0b', '#94a3b8', '#d97706'];
                            $top3 = array_slice($rankingList, 0, 3);
                            $podiumIdx = 0;
                        ?>
                        <?php if (!empty($top3)): ?>
                            <?php foreach ($top3 as $top): ?>
                            <div class="col-12 col-md-4">
                                <div class="podium-card" style="border-top: 4px solid <?= $podiumBorder[$podiumIdx]; ?>;">
                                    <div class="podium-badge"><?= explode(' ', $medals[$podiumIdx])[0]; ?></div>
                                    <span class="badge bg-light text-dark border px-2 py-1 mb-2 fw-semibold"><?= $medals[$podiumIdx]; ?></span>
                                    <h5 class="fw-bold mb-1 text-truncate" title="<?= esc($top['nama_mentor']); ?>">
                                        <?= esc($top['nama_mentor']); ?>
                                    </h5>
                                    <p class="text-muted small mb-2 text-truncate"><?= esc($top['pelatihan']); ?> • <?= esc($top['kelas']); ?></p>
                                    
                                    <div class="podium-score">
                                        <?= number_format($top['nilai_rata'], 2); ?> <span class="fs-6 text-muted">/ 5.00</span>
                                    </div>
                                    <div class="small fw-semibold text-success mb-2"><?= $top['persen_kepuasan']; ?>% Kepuasan</div>
                                    
                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <span class="badge bg-light text-dark border"><i class="fas fa-users me-1"></i> <?= $top['jumlah_responden']; ?> Responden</span>
                                        <span class="badge <?= $top['badge_class']; ?>"><?= $top['predikat']; ?></span>
                                    </div>

                                    <button onclick="openModalKomentar(<?= $top['id_mentor']; ?>, '<?= esc($top['nama_mentor']); ?>')" class="btn btn-sm btn-outline-primary mt-3 w-100" style="border-radius:10px;">
                                        <i class="fas fa-comment-dots me-1"></i> Lihat Ulasan Siswa
                                    </button>
                                </div>
                            </div>
                            <?php $podiumIdx++; endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center text-muted py-4">Belum ada data evaluasi mentor pada filter ini.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. SECTION GRAFIK ANALISIS ANGKET (CHART.JS) -->
        <div class="row g-4 mb-4">
            <!-- Grafik 1: Nilai per Indikator Angket (Bar Chart) -->
            <div class="col-12 col-lg-7">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-column text-primary me-2"></i> Skor per Indikator Pertanyaan Angket
                        </h6>
                        <span class="badge bg-light text-muted border">Skala 1.0 - 5.0</span>
                    </div>
                    <div class="chart-box">
                        <canvas id="chartIndikator"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 2: Distribusi Kepuasan Peserta (Donut Chart) -->
            <div class="col-12 col-lg-5">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-pie text-success me-2"></i> Distribusi Tingkat Kepuasan
                        </h6>
                        <span class="badge bg-light text-muted border">Kategori Predikat</span>
                    </div>
                    <div class="chart-box d-flex justify-content-center align-items-center">
                        <canvas id="chartKepuasanDonut"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 3: Tren Perkembangan Nilai Angket Bulanan (Line Chart) -->
            <div class="col-12">
                <div class="custom-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-line text-info me-2"></i> Perkembangan Tren Nilai Angket Per Bulan (Tahun <?= esc($filters['tahun']); ?>)
                        </h6>
                        <span class="badge bg-light text-muted border">Tren 12 Bulan</span>
                    </div>
                    <div class="chart-box" style="height: 250px;">
                        <canvas id="chartTrenBulanan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. SECTION PENILAIAN BERDASARKAN INDIKATOR ANGKET DATABASE -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="custom-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                                <i class="fas fa-list-check text-success me-2"></i> Rincian Skor per Aspek / Indikator Angket
                            </h6>
                            <small class="text-muted">Data dinamis diambil langsung dari struktur master pertanyaan angket di database</small>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <?= count($indikatorList); ?> Indikator Aktif
                        </span>
                    </div>

                    <div class="row g-3">
                        <?php foreach ($indikatorList as $ind): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50 h-100">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-purple-subtle text-primary fw-semibold px-2 py-1">
                                        <?= esc($ind['kategori']); ?>
                                    </span>
                                    <div class="fw-bold text-dark fs-6">
                                        <i class="fas fa-star text-warning me-1"></i><?= number_format($ind['nilai'], 2); ?> <small class="text-muted">/ 5.0</small>
                                    </div>
                                </div>
                                <div class="fw-semibold text-dark small mb-2" style="min-height: 40px;">
                                    <?= esc($ind['judul']); ?>
                                </div>
                                <div class="d-flex justify-content-between text-muted small mb-1">
                                    <span>Tingkat Kepuasan</span>
                                    <span class="fw-bold text-success"><?= $ind['persentase']; ?>%</span>
                                </div>
                                <div class="progress progress-custom">
                                    <div class="progress-bar progress-bar-custom" role="progressbar" style="width: <?= $ind['persentase']; ?>%;" aria-valuenow="<?= $ind['persentase']; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. TABEL UTAMA: REKAPITULASI PENILAIAN MENTOR -->
        <div class="custom-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                        <i class="fas fa-table-list text-primary me-2"></i> Tabel Rekapitulasi Penilaian Mentor
                    </h6>
                    <small class="text-muted">Hasil nilai rata-rata, jumlah responden, predikat, dan detail umpan balik peserta</small>
                </div>
                <div class="badge bg-light text-dark border px-3 py-2">
                    Menampilkan <strong><?= count($angketList); ?></strong> Mentor
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Mentor</th>
                            <th>Pelatihan / Kategori</th>
                            <th>Kelas Diampu</th>
                            <th class="text-center" width="12%">Responden</th>
                            <th class="text-center" width="14%">Nilai Rata-rata</th>
                            <th class="text-center" width="13%">Kepuasan (%)</th>
                            <th class="text-center" width="12%">Predikat</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($angketList)): ?>
                            <?php $no = 1; foreach ($angketList as $m): ?>
                            <tr>
                                <td class="text-center fw-bold text-muted"><?= $no++; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon me-2" style="width: 38px; height: 38px; border-radius: 10px; font-size: 1rem; background: #efe9f8; color: var(--primary-purple);">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= esc($m['nama_mentor']); ?></div>
                                            <div class="small text-muted">NIP: <?= esc($m['nip']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= esc($m['pelatihan']); ?></span></td>
                                <td><span class="fw-semibold text-secondary small"><?= esc($m['kelas']); ?></span></td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark"><?= number_format($m['jumlah_responden']); ?></span>
                                    <span class="text-muted small"> Siswa</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <i class="fas fa-star text-warning"></i>
                                        <span class="fw-bold fs-6 text-primary"><?= number_format($m['nilai_rata'], 2); ?></span>
                                        <small class="text-muted">/5.0</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-success"><?= number_format($m['persen_kepuasan'], 1); ?>%</span>
                                    <div class="progress progress-custom mt-1" style="height: 5px;">
                                        <div class="progress-bar progress-bar-custom" style="width: <?= $m['persen_kepuasan']; ?>%;"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $m['badge_class']; ?> px-3 py-2 rounded-pill">
                                        <?= $m['predikat']; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius:8px;" onclick="openModalKomentar(<?= $m['id_mentor']; ?>, '<?= esc($m['nama_mentor']); ?>')">
                                        <i class="fas fa-comments me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-question fa-3x text-muted mb-3 opacity-50"></i>
                                    <h6>Tidak ada data penilaian angket ditemukan</h6>
                                    <p class="small">Silakan sesuaikan filter tahun, bulan, atau nama mentor.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 7. SECTION KOMENTAR, KRITIK & SARAN PESERTA PELATIHAN -->
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">
                        <i class="fas fa-comments text-primary me-2"></i> Ulasan Kualitatif, Kritik & Saran Peserta Pelatihan
                    </h6>
                    <small class="text-muted">Feedback autentik dari responden angket untuk peningkatan mutu pembelajaran</small>
                </div>
                <span class="badge bg-light text-dark border px-3 py-2">
                    <?= count($komentarList); ?> Ulasan Terakhir
                </span>
            </div>

            <div class="row g-3">
                <?php if (!empty($komentarList)): ?>
                    <?php foreach (array_slice($komentarList, 0, 6) as $rev): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="review-card-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; color: var(--primary-purple); font-weight: bold;">
                                        <?= strtoupper(substr($rev['nama_peserta'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small"><?= esc($rev['nama_peserta']); ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;"><?= esc($rev['tanggal']); ?></div>
                                    </div>
                                </div>
                                <div class="badge bg-warning bg-opacity-10 text-dark fw-bold">
                                    <i class="fas fa-star text-warning me-1"></i><?= number_format($rev['rating'], 1); ?>
                                </div>
                            </div>
                            <div class="small text-muted mb-2">
                                Kelas: <strong><?= esc($rev['nama_kelas']); ?></strong> • Mentor: <strong><?= esc($rev['nama_mentor']); ?></strong>
                            </div>
                            <div class="text-dark small fst-italic" style="background: #faf8fd; padding: 10px; border-radius: 8px; border-left: 3px solid var(--primary-purple);">
                                "<?= esc($rev['ulasan']); ?>"
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted py-4">Belum ada saran atau ulasan tertulis dari peserta.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- === MODAL INTERAKTIF DETAIL ULASAN PESERTA === -->
    <div class="modal fade" id="modalKomentarPeserta" tabindex="-1" aria-labelledby="modalKomentarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 18px; overflow: hidden; border: none;">
                <div class="modal-header text-white" style="background: var(--dark-purple);">
                    <div>
                        <h5 class="modal-title fw-bold" id="modalKomentarLabel">Detail Ulasan & Komentar Peserta</h5>
                        <p class="mb-0 small text-white-50" id="modalMentorSubtitle">Mentor: -</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="background: #fbfafd; max-height: 70vh; overflow-y: auto;">
                    <div id="modalLoadingSpinner" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Memuat data...</span>
                        </div>
                        <p class="text-muted small mt-2">Mengambil riwayat ulasan peserta...</p>
                    </div>

                    <div id="modalContentContainer" style="display: none;">
                        <!-- Mentor Quick Header -->
                        <div class="p-3 bg-white border rounded-3 mb-3 d-flex justify-content-between align-items-center shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon me-3" style="width: 45px; height: 45px; border-radius: 12px; background: #f3effa; color: var(--primary-purple);">
                                    <i class="fas fa-chalkboard-user"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" id="mInfoNama">-</h6>
                                    <small class="text-muted" id="mInfoKeahlian">-</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary px-3 py-2 rounded-pill" id="mInfoTotalUlasan">0 Ulasan</span>
                            </div>
                        </div>

                        <!-- Daftar Ulasan Dinamis -->
                        <div id="mListReviews" class="d-flex flex-column gap-3"></div>
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

    <!-- SCRIPT DATA GRAFIK DAN INTERAKSI -->
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
            if (periode === 'bulanan') {
                boxBulan.style.display = 'block';
            } else {
                boxBulan.style.display = 'none';
            }
        }

        // Chart.js Setup
        document.addEventListener('DOMContentLoaded', function() {
            const chartDataServer = <?= json_encode($chartData); ?>;

            // 1. Bar Chart: Skor per Indikator
            const ctxIndikator = document.getElementById('chartIndikator').getContext('2d');
            new Chart(ctxIndikator, {
                type: 'bar',
                data: {
                    labels: chartDataServer.chart_indikator.labels,
                    datasets: [{
                        label: 'Nilai Rata-rata (1 - 5)',
                        data: chartDataServer.chart_indikator.data,
                        backgroundColor: 'rgba(121, 75, 196, 0.85)',
                        borderColor: '#794bc4',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        hoverBackgroundColor: '#5931a0'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 5.0,
                            ticks: { stepSize: 1.0 }
                        },
                        x: {
                            ticks: {
                                maxRotation: 25,
                                minRotation: 0,
                                font: { size: 11 }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Skor: ' + context.parsed.y + ' / 5.00';
                                }
                            }
                        }
                    }
                }
            });

            // 2. Donut Chart: Distribusi Kepuasan
            const ctxKepuasan = document.getElementById('chartKepuasanDonut').getContext('2d');
            new Chart(ctxKepuasan, {
                type: 'doughnut',
                data: {
                    labels: chartDataServer.chart_kepuasan_donut.labels,
                    datasets: [{
                        data: chartDataServer.chart_kepuasan_donut.data,
                        backgroundColor: ['#2e7d32', '#1976d2', '#f59e0b', '#d32f2f'],
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 14, font: { size: 11 } }
                        }
                    },
                    cutout: '68%'
                }
            });

            // 3. Line Chart: Tren Bulanan 12 Bulan
            const ctxTren = document.getElementById('chartTrenBulanan').getContext('2d');
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: chartDataServer.chart_tren_bulanan.labels,
                    datasets: [{
                        label: 'Nilai Kepuasan Rata-rata',
                        data: chartDataServer.chart_tren_bulanan.data,
                        borderColor: '#794bc4',
                        backgroundColor: 'rgba(121, 75, 196, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#794bc4',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 4.0,
                            max: 5.0,
                            ticks: { stepSize: 0.2 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });

        // AJAX Modal Detail Ulasan Peserta
        function openModalKomentar(idMentor, namaMentor) {
            const modalEl = document.getElementById('modalKomentarPeserta');
            const modal = new bootstrap.Modal(modalEl);
            
            document.getElementById('modalMentorSubtitle').textContent = 'Mentor: ' + namaMentor;
            document.getElementById('modalLoadingSpinner').style.display = 'block';
            document.getElementById('modalContentContainer').style.display = 'none';
            document.getElementById('mListReviews').innerHTML = '';

            modal.show();

            const baseUrl = '<?= base_url($baseUrlReport . "/detail-komentar"); ?>/' + idMentor;

            fetch(baseUrl)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modalLoadingSpinner').style.display = 'none';
                    document.getElementById('modalContentContainer').style.display = 'block';

                    document.getElementById('mInfoNama').textContent = data.mentor ? data.mentor.nama : namaMentor;
                    document.getElementById('mInfoKeahlian').textContent = data.mentor ? (data.mentor.keahlian + ' • NIP: ' + data.mentor.nip) : 'Instruktur CreativeMU';
                    document.getElementById('mInfoTotalUlasan').textContent = data.total + ' Ulasan Responden';

                    const listContainer = document.getElementById('mListReviews');
                    if (data.comments && data.comments.length > 0) {
                        data.comments.forEach(c => {
                            const card = document.createElement('div');
                            card.className = 'p-3 bg-white border rounded-3 shadow-sm';
                            card.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong class="text-dark">${escapeHtml(c.nama_peserta)}</strong>
                                        <small class="text-muted d-block">${escapeHtml(c.nama_kelas)} • ${escapeHtml(c.tanggal)}</small>
                                    </div>
                                    <div class="badge bg-warning bg-opacity-10 text-dark fw-bold">
                                        <i class="fas fa-star text-warning me-1"></i>${parseFloat(c.rating).toFixed(1)} / 5.0
                                    </div>
                                </div>
                                <p class="mb-0 text-dark small fst-italic" style="background:#faf8fd; padding:10px; border-radius:8px; border-left:3px solid var(--primary-purple);">
                                    "${escapeHtml(c.ulasan)}"
                                </p>
                            `;
                            listContainer.appendChild(card);
                        });
                    } else {
                        listContainer.innerHTML = '<div class="text-center py-4 text-muted small">Belum ada ulasan tertulis dari peserta untuk mentor ini.</div>';
                    }
                })
                .catch(err => {
                    document.getElementById('modalLoadingSpinner').style.display = 'none';
                    document.getElementById('modalContentContainer').style.display = 'block';
                    document.getElementById('mListReviews').innerHTML = '<div class="alert alert-danger">Gagal memuat ulasan. Silakan coba kembali.</div>';
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
