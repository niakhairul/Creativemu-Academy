<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Laporan Mentor'); ?> - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --secondary-purple: #5931a0;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
            --card-shadow: 0 10px 25px rgba(121, 75, 196, 0.06);
            --border-soft: rgba(121, 75, 196, 0.08);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            overflow-x: hidden;
            margin: 0;
            color: #495057;
        }

        /* SIDEBAR STYLING */
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(121, 75, 196, 0.08);
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
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
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-soft);
            padding: 24px;
            margin-bottom: 25px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        /* STATISTIC METRIC CARDS */
        .stat-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-soft);
            padding: 22px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(121, 75, 196, 0.12);
        }

        .stat-card .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #fff;
            margin-bottom: 15px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--dark-purple);
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* GRADIENT ICON BACKGROUNDS */
        .bg-grad-purple { background: linear-gradient(135deg, #794bc4 0%, #5931a0 100%); }
        .bg-grad-blue   { background: linear-gradient(135deg, #3a86ff 0%, #0056b3 100%); }
        .bg-grad-cyan   { background: linear-gradient(135deg, #06d6a0 0%, #009688 100%); }
        .bg-grad-pink   { background: linear-gradient(135deg, #ff006e 0%, #b5179e 100%); }
        .bg-grad-amber  { background: linear-gradient(135deg, #ffb703 0%, #fb8500 100%); }
        .bg-grad-red    { background: linear-gradient(135deg, #e63946 0%, #d62828 100%); }

        /* BUTTONS */
        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.25);
            transition: all 0.25s ease;
        }
        .btn-purple:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.35);
        }

        .btn-outline-purple {
            border: 1.5px solid var(--primary-purple);
            color: var(--primary-purple);
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            background: transparent;
            transition: all 0.25s ease;
        }
        .btn-outline-purple:hover {
            background: var(--light-purple);
            color: var(--primary-purple);
            transform: translateY(-2px);
        }

        .btn-excel {
            background: #107c41;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 124, 65, 0.2);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-excel:hover {
            background: #0b582e;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-print {
            background: #5931a0;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(89, 49, 160, 0.2);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-print:hover {
            background: #42207a;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* FORM CONTROLS */
        .form-select, .form-control {
            border-radius: 12px;
            padding: 10px 14px;
            border: 1.5px solid #e0dbf0;
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

        /* RANKING PODIUM CARD */
        .rank-card {
            border-radius: 16px;
            border: 1.5px solid #eef0f6;
            padding: 20px;
            text-align: center;
            background: #fff;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease;
        }
        .rank-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(121, 75, 196, 0.1);
        }
        .rank-badge {
            font-size: 2.2rem;
            margin-bottom: 6px;
        }
        .rank-score {
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

        /* PROGRESS BAR */
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
                                <a href="<?= base_url('mentor/laporan-kehadiran'); ?>" class="nav-link <?= (url_is('mentor/laporan-kehadiran*')) ? 'active' : ''; ?>"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran Peserta</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item"><a href="<?= base_url('mentor/profil'); ?>" class="nav-link"><i class="fas fa-user"></i> <span>Profil Mentor</span></a></li>
            <?php else: ?>
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
                                <a href="<?= base_url('admin/laporan-kehadiran'); ?>" class="nav-link <?= (url_is('admin/laporan-kehadiran*')) ? 'active' : ''; ?>"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran Peserta</span></a>
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
                    <h4 class="fw-bold m-0" style="color: var(--dark-purple);">Laporan Kinerja & Keaktifan Mentor</h4>
                    <p class="text-muted m-0 small">Analisis komparatif kehadiran, keterlambatan, dan evaluasi kepuasan peserta.</p>
                </div>
            </div>

            <?php 
                $baseUrlReport = $isMentor ? 'mentor/laporan-mentor' : 'admin/laporan-mentor';
                $exportQuery = http_build_query($filters);
            ?>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url($baseUrlReport . '/export-excel?' . $exportQuery); ?>" class="btn-excel">
                    <i class="fas fa-file-excel me-2"></i> Download Excel (.xlsx)
                </a>
                <a href="<?= base_url($baseUrlReport . '/cetak?' . $exportQuery); ?>" target="_blank" class="btn-print">
                    <i class="fas fa-print me-2"></i> Cetak / PDF
                </a>
            </div>
        </div>

        <!-- FILTER PERIODE FORM CARD -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                    <i class="fas fa-filter text-purple me-2" style="color: var(--primary-purple);"></i> Filter Laporan Mentor
                </h6>
                <span class="badge bg-light text-muted border px-2 py-1">Periode: <?= esc($periodeText); ?></span>
            </div>

            <form action="<?= base_url($baseUrlReport); ?>" method="GET" id="filterMentorForm">
                <div class="row g-3">
                    
                    <!-- Periode -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label small fw-semibold text-muted">Periode</label>
                        <select name="periode" id="periodeSelect" class="form-select" onchange="toggleBulanField()">
                            <option value="tahunan" <?= ($filters['periode'] === 'tahunan') ? 'selected' : ''; ?>>Tahunan</option>
                            <option value="bulanan" <?= ($filters['periode'] === 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                        </select>
                    </div>

                    <!-- Tahun -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label small fw-semibold text-muted">Tahun</label>
                        <select name="tahun" class="form-select">
                            <?php foreach ($years as $y): ?>
                                <option value="<?= $y; ?>" <?= ((int)$filters['tahun'] === (int)$y) ? 'selected' : ''; ?>>
                                    <?= $y; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Bulan (Muncul jika Bulanan) -->
                    <div class="col-12 col-md-6 col-lg-2" id="bulanWrapper" style="<?= ($filters['periode'] === 'bulanan') ? '' : 'display: none;'; ?>">
                        <label class="form-label small fw-semibold text-muted">Bulan</label>
                        <select name="bulan" class="form-select">
                            <?php foreach ($bulanNames as $num => $namaBulan): ?>
                                <option value="<?= $num; ?>" <?= ((int)$filters['bulan'] === $num) ? 'selected' : ''; ?>>
                                    <?= $namaBulan; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Mentor -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Mentor</label>
                        <select name="id_mentor" class="form-select" <?= (!empty($isMentor) ? 'disabled' : ''); ?>>
                            <option value="all">-- Semua Mentor --</option>
                            <?php foreach ($mentors as $men): ?>
                                <option value="<?= $men['id_mentor']; ?>" <?= ((string)$filters['id_mentor'] === (string)$men['id_mentor']) ? 'selected' : ''; ?>>
                                    <?= esc($men['nama_mentor']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Pelatihan -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Pelatihan</label>
                        <select name="kategori" class="form-select">
                            <option value="all">-- Semua Pelatihan --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat); ?>" <?= ($filters['kategori'] === $cat) ? 'selected' : ''; ?>>
                                    <?= esc($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="col-12 d-flex gap-2 justify-content-end align-items-center mt-3 pt-2 border-top">
                        <a href="<?= base_url($baseUrlReport); ?>" class="btn btn-outline-purple">
                            <i class="fas fa-rotate-left me-1"></i> Reset Filter
                        </a>
                        <button type="submit" class="btn btn-purple">
                            <i class="fas fa-magnifying-glass me-1"></i> Terapkan Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- DASHBOARD STATISTIK (5 CARDS) -->
        <div class="row g-3 mb-4">
            
            <!-- Card 1: Total Mentor -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-purple"><i class="fas fa-chalkboard-user"></i></div>
                    <div class="stat-value"><?= number_format($stats['total_mentor']); ?></div>
                    <div class="stat-label">Total Mentor</div>
                </div>
            </div>

            <!-- Card 2: Rata-rata Keaktifan -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-blue"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-value"><?= number_format($stats['avg_keaktifan'], 1); ?>%</div>
                    <div class="stat-label">Rata-rata Keaktifan</div>
                </div>
            </div>

            <!-- Card 3: Rata-rata Kehadiran -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-cyan"><i class="fas fa-user-check"></i></div>
                    <div class="stat-value"><?= number_format($stats['avg_kehadiran'], 1); ?>%</div>
                    <div class="stat-label">Rata-rata Kehadiran</div>
                </div>
            </div>

            <!-- Card 4: Rata-rata Keterlambatan -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-red"><i class="fas fa-stopwatch"></i></div>
                    <div class="stat-value"><?= number_format($stats['avg_keterlambatan'], 1); ?>%</div>
                    <div class="stat-label">Rata-rata Keterlambatan</div>
                </div>
            </div>

            <!-- Card 5: Rata-rata Angket -->
            <div class="col-12 col-sm-6 col-xl">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-amber"><i class="fas fa-star"></i></div>
                    <div class="stat-value"><?= number_format($stats['avg_angket'], 2); ?></div>
                    <div class="stat-label">Rata-rata Angket</div>
                </div>
            </div>

        </div>

        <!-- SECTION ⭐ RANKING & PERFORMA MENTOR (LEADERBOARD) -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <div>
                    <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                        <i class="fas fa-award text-warning me-2"></i> ⭐ Leaderboard & Ranking Performa Mentor
                    </h6>
                    <small class="text-muted">Kalkulasi bobot transparan: Kehadiran 35% | Keaktifan 30% | Ketepatan 15% | Angket 20%</small>
                </div>
                <span class="badge bg-light text-primary border px-2 py-1">Top Performers</span>
            </div>

            <div class="row g-3">
                <?php 
                    $medals = ['🥇 Juara 1', '🥈 Juara 2', '🥉 Juara 3'];
                    $topThree = array_slice($rankingList, 0, 3);
                    $idx = 0;
                    foreach ($topThree as $rm):
                ?>
                <div class="col-12 col-md-4">
                    <div class="rank-card">
                        <div class="rank-badge"><?= explode(' ', $medals[$idx])[0]; ?></div>
                        <h6 class="fw-bold text-dark m-0"><?= esc($rm['nama_mentor']); ?></h6>
                        <small class="text-muted d-block mb-2"><?= esc($rm['keahlian']); ?></small>
                        <div class="rank-score"><?= number_format($rm['skor_performa'], 1); ?>%</div>
                        <span class="badge <?= $rm['badge_class']; ?> mt-2 px-3 py-1"><?= $rm['predikat']; ?></span>
                        
                        <div class="mt-3 pt-2 border-top text-start small">
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Kehadiran:</span>
                                <span class="fw-semibold"><?= $rm['persen_kehadiran']; ?>%</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Keaktifan Sesi:</span>
                                <span class="fw-semibold"><?= $rm['persen_keaktifan']; ?>%</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted">Nilai Angket:</span>
                                <span class="fw-semibold text-warning"><i class="fas fa-star"></i> <?= number_format($rm['nilai_angket'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $idx++; endforeach; ?>
            </div>
        </div>

        <!-- SECTION GRAFIK ANALITIK (DONUT, BAR, RADAR, LINE) -->
        <div class="row g-3 mb-4">
            
            <!-- Grafik 1: Donut Kehadiran Mentor -->
            <div class="col-12 col-xl-4">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.95rem;">
                            <i class="fas fa-chart-pie text-success me-2"></i> Komposisi Kehadiran
                        </h6>
                        <small class="text-muted">Status Sesi</small>
                    </div>
                    <div class="chart-box">
                        <canvas id="chartKehadiranDonut"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 2: Bar Keterlambatan per Mentor -->
            <div class="col-12 col-xl-4">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.95rem;">
                            <i class="fas fa-stopwatch text-danger me-2"></i> Persentase Keterlambatan
                        </h6>
                        <small class="text-muted">Makin rendah makin baik</small>
                    </div>
                    <div class="chart-box">
                        <canvas id="chartKeterlambatanBar"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 3: Radar Chart Penilaian Angket Siswa -->
            <div class="col-12 col-xl-4">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.95rem;">
                            <i class="fas fa-bullseye text-primary me-2"></i> Evaluasi Angket per Indikator
                        </h6>
                        <small class="text-muted">Skala 1.0 - 5.0</small>
                    </div>
                    <div class="chart-box">
                        <canvas id="chartRadarAngket"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 4: Line Chart Tren Tahunan 12 Bulan -->
            <div class="col-12">
                <div class="custom-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                                <i class="fas fa-chart-line text-purple me-2" style="color: var(--primary-purple);"></i> Perkembangan Performa Mentor Sepanjang Tahun (12 Bulan)
                            </h6>
                            <small class="text-muted">Tren skor performa, kehadiran, dan evaluasi angket Januari s/d Desember</small>
                        </div>
                    </div>
                    <div class="chart-box" style="height: 260px;">
                        <canvas id="chartTrenTahunan"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- SECTION KEAKTIFAN MENTOR (PROGRESS BARS) -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                    <i class="fas fa-fire text-danger me-2"></i> Analisis Keaktifan Mengajar & Distribusi Materi
                </h6>
                <small class="text-muted">Sesi terlaksana dan materi modul yang diberikan</small>
            </div>

            <div class="row g-3">
                <?php foreach ($mentorList as $m): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="p-3 border rounded-4 bg-light h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-bold text-dark"><?= esc($m['nama_mentor']); ?></div>
                            <span class="badge bg-purple-subtle text-primary border" style="background-color: var(--light-purple); color: var(--primary-purple) !important;">
                                <?= $m['persen_keaktifan']; ?>% Keaktifan
                            </span>
                        </div>

                        <!-- Progress Bar Keaktifan -->
                        <div class="progress-custom mb-3">
                            <div class="progress-bar-custom" style="width: <?= $m['persen_keaktifan']; ?>%; height: 100%;"></div>
                        </div>

                        <div class="small text-muted">
                            <div class="d-flex justify-content-between py-1">
                                <span><i class="fas fa-calendar-check me-1 text-primary"></i> Sesi Mengajar Terlaksana:</span>
                                <span class="fw-bold text-dark"><?= $m['sesi_terlaksana']; ?> / <?= $m['total_sesi']; ?> Sesi</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span><i class="fas fa-file-pdf me-1 text-danger"></i> Berkas/Modul Materi:</span>
                                <span class="fw-bold text-dark"><?= $m['total_materi']; ?> Modul</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span><i class="fas fa-graduation-cap me-1 text-info"></i> Kelas Diampu:</span>
                                <span class="fw-semibold text-dark text-truncate" style="max-width: 170px;"><?= esc($m['kelas']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- TABEL UTAMA LAPORAN MENTOR -->
        <div class="custom-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 pb-3 border-bottom">
                <div>
                    <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                        <i class="fas fa-table-list me-2" style="color: var(--primary-purple);"></i> Tabel Data Laporan Mentor
                    </h6>
                    <small class="text-muted">Total <?= count($mentorList); ?> mentor dievaluasi pada periode <?= esc($periodeText); ?></small>
                </div>

                <!-- Live Search -->
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="searchMentorInput" class="form-control" placeholder="Cari nama mentor, kelas..." onkeyup="filterMentorTable()">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom" id="tableLaporanMentor">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">No</th>
                            <th>Nama Mentor</th>
                            <th>Pelatihan</th>
                            <th>Kelas Diampu</th>
                            <th class="text-center">Keaktifan</th>
                            <th class="text-center">Kehadiran</th>
                            <th class="text-center">Keterlambatan</th>
                            <th class="text-center">Nilai Angket</th>
                            <th class="text-center">Predikat</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mentorList)): ?>
                            <?php $no = 1; foreach ($mentorList as $m): ?>
                            <tr class="mentor-row" data-search="<?= strtolower(esc($m['nama_mentor'] . ' ' . $m['pelatihan'] . ' ' . $m['kelas'])); ?>">
                                <td class="text-center fw-bold row-no"><?= $no++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($m['nama_mentor']); ?></div>
                                    <small class="text-muted">NIP: <?= esc($m['nip']); ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= esc($m['pelatihan']); ?></span></td>
                                <td class="small fw-semibold"><?= esc($m['kelas']); ?></td>
                                <td class="text-center fw-bold text-primary"><?= $m['persen_keaktifan']; ?>%</td>
                                <td class="text-center fw-bold text-success"><?= $m['persen_kehadiran']; ?>%</td>
                                <td class="text-center">
                                    <span class="badge <?= ($m['persen_keterlambatan'] > 10) ? 'bg-danger-subtle text-danger' : 'bg-light text-dark border'; ?>">
                                        <?= $m['persen_keterlambatan']; ?>%
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-warning">
                                    <i class="fas fa-star me-1"></i><?= number_format($m['nilai_angket'], 2); ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $m['badge_class']; ?> px-2 py-1"><?= $m['predikat']; ?></span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-purple" onclick="openDetailModal(<?= $m['id_mentor']; ?>)">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3 opacity-50 d-block"></i>
                                    <h6 class="fw-semibold">Belum ada data laporan mentor pada periode yang dipilih.</h6>
                                    <p class="small text-muted m-0">Silakan pilih periode atau pelatihan lainnya di bagian filter atas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="noMentorMatch" class="text-center py-4 text-muted d-none">
                <i class="fas fa-filter-circle-xmark fa-2x mb-2 opacity-50"></i>
                <p class="m-0 fw-semibold">Tidak ada mentor yang cocok dengan kata kunci pencarian.</p>
            </div>
        </div>

    </div>

    <!-- === MODAL DETAIL MENTOR === -->
    <div class="modal fade" id="detailMentorModal" tabindex="-1" aria-labelledby="detailMentorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0" style="background: var(--light-purple);">
                    <div>
                        <h5 class="modal-title fw-bold text-dark" id="modalNamaMentor">Detail Kinerja Mentor</h5>
                        <p class="small text-muted m-0" id="modalKeahlianMentor">Informasi profil & riwayat sesi mengajar</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalDetailBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Memuat...</span>
                        </div>
                        <p class="small text-muted mt-2">Mengambil data mentor...</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- INLINE JAVASCRIPT & CHART.JS INITIALIZATION -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function toggleBulanField() {
            const periode = document.getElementById('periodeSelect').value;
            const bulanWrapper = document.getElementById('bulanWrapper');
            bulanWrapper.style.display = (periode === 'bulanan') ? 'block' : 'none';
        }

        function filterMentorTable() {
            const q = document.getElementById('searchMentorInput').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.mentor-row');
            let visible = 0;

            rows.forEach(r => {
                const search = r.getAttribute('data-search') || '';
                if (q === '' || search.includes(q)) {
                    r.style.display = '';
                    visible++;
                    const noCell = r.querySelector('.row-no');
                    if (noCell) noCell.textContent = visible;
                } else {
                    r.style.display = 'none';
                }
            });

            const noMatch = document.getElementById('noMentorMatch');
            if (noMatch) {
                if (visible === 0 && rows.length > 0) {
                    noMatch.classList.remove('d-none');
                } else {
                    noMatch.classList.add('d-none');
                }
            }
        }

        // AJAX Modal Detail Mentor
        function openDetailModal(idMentor) {
            const modal = new bootstrap.Modal(document.getElementById('detailMentorModal'));
            const modalBody = document.getElementById('modalDetailBody');
            modalBody.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="small text-muted mt-2">Mengambil data mentor...</p>
                </div>
            `;
            modal.show();

            const baseUrl = "<?= base_url($baseUrlReport); ?>";
            fetch(`${baseUrl}/detail/${idMentor}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        modalBody.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
                        return;
                    }
                    document.getElementById('modalNamaMentor').textContent = data.nama_mentor;
                    document.getElementById('modalKeahlianMentor').textContent = `Bidang: ${data.keahlian} | NIP: ${data.nip}`;

                    let sessionsHtml = '';
                    if (data.riwayat_sesi && data.riwayat_sesi.length > 0) {
                        data.riwayat_sesi.forEach(s => {
                            sessionsHtml += `
                                <tr>
                                    <td>Pertemuan ke-${s.pertemuan_ke}</td>
                                    <td>${s.nama_kelas}</td>
                                    <td>${s.tanggal_kbm || '-'} (${s.waktu_mulai || ''})</td>
                                    <td><span class="badge ${s.status_kehadiran === 'hadir' || s.status_kehadiran === 'Hadir' ? 'bg-success' : 'bg-warning'}">${s.status_kehadiran}</span></td>
                                    <td class="small text-muted">${s.waktu_absen_mentor || '-'}</td>
                                </tr>
                            `;
                        });
                    } else {
                        sessionsHtml = `<tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat sesi mengajar.</td></tr>`;
                    }

                    modalBody.innerHTML = `
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <small class="text-muted d-block">Total Sesi</small>
                                    <h5 class="fw-bold m-0 text-dark">${data.total_sesi}</h5>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <small class="text-muted d-block">Kehadiran</small>
                                    <h5 class="fw-bold m-0 text-success">${data.persen_kehadiran}%</h5>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <small class="text-muted d-block">Keterlambatan</small>
                                    <h5 class="fw-bold m-0 text-danger">${data.persen_keterlambatan}%</h5>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <small class="text-muted d-block">Skor Angket</small>
                                    <h5 class="fw-bold m-0 text-warning"><i class="fas fa-star"></i> ${data.nilai_angket}</h5>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-2 text-dark"><i class="fas fa-list-check me-2 text-primary"></i>Riwayat Sesi Mengajar Terakhir</h6>
                        <div class="table-responsive border rounded-3">
                            <table class="table table-sm table-striped m-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sesi</th>
                                        <th>Kelas</th>
                                        <th>Jadwal KBM</th>
                                        <th>Status</th>
                                        <th>Waktu Absen</th>
                                    </tr>
                                </thead>
                                <tbody>${sessionsHtml}</tbody>
                            </table>
                        </div>
                    `;
                })
                .catch(() => {
                    modalBody.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan saat memuat data mentor.</div>`;
                });
        }

        // Inisialisasi Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = <?= json_encode($chartData); ?>;

            // 1. Donut Kehadiran
            const ctxDonut = document.getElementById('chartKehadiranDonut');
            if (ctxDonut) {
                new Chart(ctxDonut.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: chartData.kehadiran_donut.labels,
                        datasets: [{
                            data: chartData.kehadiran_donut.data,
                            backgroundColor: ['#06d6a0', '#3a86ff', '#ffb703', '#e63946'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } },
                        cutout: '70%'
                    }
                });
            }

            // 2. Bar Keterlambatan
            const ctxBar = document.getElementById('chartKeterlambatanBar');
            if (ctxBar) {
                new Chart(ctxBar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartData.keterlambatan_bar.labels,
                        datasets: [{
                            label: 'Keterlambatan (%)',
                            data: chartData.keterlambatan_bar.data,
                            backgroundColor: '#e63946',
                            borderRadius: 8,
                            barThickness: 24,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // 3. Radar Angket
            const ctxRadar = document.getElementById('chartRadarAngket');
            if (ctxRadar) {
                new Chart(ctxRadar.getContext('2d'), {
                    type: 'radar',
                    data: {
                        labels: chartData.radar_angket.labels,
                        datasets: chartData.radar_angket.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                min: 0,
                                max: 5.0,
                                ticks: { stepSize: 1.0 },
                                pointLabels: { font: { size: 11 } }
                            }
                        },
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }

            // 4. Line Chart Tren Tahunan
            const ctxLine = document.getElementById('chartTrenTahunan');
            if (ctxLine) {
                new Chart(ctxLine.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartData.tren_tahunan.labels,
                        datasets: [
                            {
                                label: 'Performa (%)',
                                data: chartData.tren_tahunan.performa,
                                borderColor: '#794bc4',
                                backgroundColor: '#794bc415',
                                tension: 0.35,
                                fill: true,
                                borderWidth: 2.5
                            },
                            {
                                label: 'Kehadiran (%)',
                                data: chartData.tren_tahunan.kehadiran,
                                borderColor: '#06d6a0',
                                tension: 0.35,
                                borderWidth: 2,
                                borderDash: [4, 4]
                            },
                            {
                                label: 'Keterlambatan (%)',
                                data: chartData.tren_tahunan.keterlambatan,
                                borderColor: '#e63946',
                                tension: 0.35,
                                borderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: {
                            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
