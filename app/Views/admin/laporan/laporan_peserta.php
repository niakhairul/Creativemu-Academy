<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Laporan Peserta'); ?> - Creativemu Academy</title>
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
        .bg-grad-green  { background: linear-gradient(135deg, #2ec4b6 0%, #157347 100%); }
        .bg-grad-orange { background: linear-gradient(135deg, #ff9f1c 0%, #e63946 100%); }

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

        /* BADGES */
        .badge-lulus {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-tidak-lulus {
            background-color: #f8d7da;
            color: #842029;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-proses {
            background-color: #fff3cd;
            color: #664d03;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* CHART CONTAINERS */
        .chart-container-box {
            position: relative;
            height: 280px;
            width: 100%;
        }

        /* MOBILE RESPONSIVENESS */
        @media (max-width: 991px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0;
                padding: 16px;
            }
            .mobile-toggle-btn {
                display: inline-block !important;
            }
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

    <!-- === SIDEBAR === -->
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
                <!-- Navigasi Mentor -->
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
                                <a href="<?= base_url('mentor/laporan-kehadiran'); ?>" class="nav-link <?= (url_is('mentor/laporan-kehadiran*')) ? 'active' : ''; ?>"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran Peserta</span></a>
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

    <!-- === MAIN CONTENT === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h4 class="fw-bold m-0" style="color: var(--dark-purple);">Laporan Data Peserta Pelatihan</h4>
                    <p class="text-muted m-0 small">Analisis komprehensif data pendaftaran, kelulusan, dan statistik peserta.</p>
                </div>
            </div>

            <!-- Quick Export Buttons -->
            <?php 
                $baseUrlReport = $isMentor ? 'mentor/laporan' : 'admin/laporan';
                $exportQuery = http_build_query($filters);
            ?>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url($baseUrlReport . '/export-excel?' . $exportQuery); ?>" class="btn-excel">
                    <i class="fas fa-file-excel me-2"></i> Export Excel
                </a>
                <a href="<?= base_url($baseUrlReport . '/cetak?' . $exportQuery); ?>" target="_blank" class="btn-print">
                    <i class="fas fa-print me-2"></i> Cetak / PDF
                </a>
            </div>
        </div>

        <!-- FILTER FORM CARD -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                    <i class="fas fa-filter text-purple me-2" style="color: var(--primary-purple);"></i> Filter & Periode Laporan
                </h6>
                <span class="badge bg-light text-muted border px-2 py-1">Filter Dinamis Database</span>
            </div>

            <form action="<?= base_url($baseUrlReport); ?>" method="GET" id="filterForm">
                <div class="row g-3">
                    
                    <!-- Pilihan Periode (Tahunan / Bulanan) -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label small fw-semibold text-muted">Periode</label>
                        <select name="periode" id="periodeSelect" class="form-select" onchange="toggleBulanField()">
                            <option value="tahunan" <?= ($filters['periode'] === 'tahunan') ? 'selected' : ''; ?>>Tahunan</option>
                            <option value="bulanan" <?= ($filters['periode'] === 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                        </select>
                    </div>

                    <!-- Pilihan Tahun -->
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

                    <!-- Pilihan Bulan (Muncul jika bulanan dipilih) -->
                    <div class="col-12 col-md-6 col-lg-2" id="bulanWrapper" style="<?= ($filters['periode'] === 'bulanan') ? '' : 'display: none;'; ?>">
                        <label class="form-label small fw-semibold text-muted">Bulan</label>
                        <select name="bulan" class="form-select">
                            <?php 
                            $monthNames = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            foreach ($monthNames as $num => $namaBulan): ?>
                                <option value="<?= $num; ?>" <?= ((int)$filters['bulan'] === $num) ? 'selected' : ''; ?>>
                                    <?= $namaBulan; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kelas -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Kelas</label>
                        <select name="id_kelas" class="form-select">
                            <option value="all">-- Semua Kelas --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id_kelas']; ?>" <?= ((string)$filters['id_kelas'] === (string)$c['id_kelas']) ? 'selected' : ''; ?>>
                                    <?= esc($c['nama_kelas']); ?> (<?= esc($c['nama_mentor'] ?? 'Mentor -'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Pelatihan / Kategori -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label small fw-semibold text-muted">Pelatihan / Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="all">-- Semua Pelatihan --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat); ?>" <?= ($filters['kategori'] === $cat) ? 'selected' : ''; ?>>
                                    <?= esc($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tombol Aksi Filter -->
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

        <!-- 2. DASHBOARD RINGKASAN (6 CARDS STATISTIK) -->
        <div class="row g-3 mb-4">
            
            <!-- Card 1: Total Peserta -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-purple">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value"><?= number_format($stats['total_peserta']); ?></div>
                    <div class="stat-label">Total Peserta</div>
                </div>
            </div>

            <!-- Card 2: Total Kelas -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-blue">
                        <i class="fas fa-chalkboard-user"></i>
                    </div>
                    <div class="stat-value"><?= number_format($stats['total_kelas']); ?></div>
                    <div class="stat-label">Total Kelas</div>
                </div>
            </div>

            <!-- Card 3: Peserta Laki-laki -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-cyan">
                        <i class="fas fa-mars"></i>
                    </div>
                    <div class="stat-value"><?= number_format($stats['peserta_laki']); ?></div>
                    <div class="stat-label">Laki-laki</div>
                </div>
            </div>

            <!-- Card 4: Peserta Perempuan -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-pink">
                        <i class="fas fa-venus"></i>
                    </div>
                    <div class="stat-value"><?= number_format($stats['peserta_perempuan']); ?></div>
                    <div class="stat-label">Perempuan</div>
                </div>
            </div>

            <!-- Card 5: Peserta Lulus -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-green">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-value"><?= number_format($stats['peserta_lulus']); ?></div>
                    <div class="stat-label">Peserta Lulus</div>
                </div>
            </div>

            <!-- Card 6: Peserta Tidak Lulus -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-orange">
                        <i class="fas fa-circle-xmark"></i>
                    </div>
                    <div class="stat-value"><?= number_format($stats['peserta_tidak_lulus']); ?></div>
                    <div class="stat-label">Tidak Lulus</div>
                </div>
            </div>

        </div>

        <!-- 3. INFORMASI PELATIHAN / KELAS -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                    <i class="fas fa-circle-info text-purple me-2" style="color: var(--primary-purple);"></i> Informasi Pelatihan
                </h6>
                <small class="text-muted">Status & kapasitas kelas periode terpilih</small>
            </div>

            <div class="row g-3">
                <?php if (!empty($infoKelas)): ?>
                    <?php foreach ($infoKelas as $ik): ?>
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="p-3 rounded-4 border bg-light h-100 position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-purple-subtle text-primary border px-2 py-1" style="background-color: var(--light-purple); color: var(--primary-purple) !important;">
                                    <?= esc($ik['kategori'] ?? 'Pelatihan'); ?>
                                </span>
                                <span class="badge <?= ($ik['status'] === 'aktif') ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?= ucfirst(esc($ik['status'] ?? 'aktif')); ?>
                                </span>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark"><?= esc($ik['nama_kelas']); ?></h6>
                            <div class="small text-muted mb-2">
                                <i class="fas fa-tag me-1"></i> ID Kelas: #<?= esc($ik['id_kelas']); ?>
                            </div>

                            <div class="pt-2 border-top small">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted"><i class="fas fa-user-tie me-1"></i> Mentor:</span>
                                    <span class="fw-semibold text-dark"><?= esc($ik['nama_mentor'] ?? 'Belum Ditentukan'); ?></span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted"><i class="fas fa-calendar-day me-1"></i> Periode Mulai:</span>
                                    <span class="fw-semibold text-dark"><?= !empty($ik['tanggal_mulai_kelas']) ? date('d M Y', strtotime($ik['tanggal_mulai_kelas'])) : '-'; ?></span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted"><i class="fas fa-users me-1"></i> Peserta Periode Ini:</span>
                                    <span class="fw-bold text-primary"><?= (int)$ik['jumlah_peserta_filter']; ?> Peserta</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="fas fa-box-open fa-2x mb-2 opacity-50"></i>
                        <p class="m-0">Tidak ada informasi kelas yang sesuai dengan kriteria filter.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. GRAFIK LAPORAN INTERAKTIF (CHART.JS) -->
        <div class="row g-3 mb-4">
            
            <!-- Grafik 1: Peserta Per Kelas (Bar Chart) -->
            <div class="col-12 col-xl-6">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-column me-2" style="color: var(--primary-purple);"></i> Jumlah Peserta Per Kelas
                        </h6>
                        <small class="text-muted">Distribusi peserta</small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartPesertaPerKelas"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 2: Perkembangan Pendaftaran (Line Chart) -->
            <div class="col-12 col-xl-6">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-line me-2" style="color: #3a86ff;"></i> Perkembangan Pendaftaran Peserta
                        </h6>
                        <small class="text-muted"><?= ($chartData['line_tren']['mode'] === 'bulanan') ? 'Tren Mingguan' : 'Tren Bulanan 12 Bulan'; ?></small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartPerkembangan"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 3: Gender Peserta (Donut Chart) -->
            <div class="col-12 col-md-6 col-xl-6">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                            <i class="fas fa-chart-pie me-2" style="color: #06d6a0;"></i> Komposisi Gender Peserta
                        </h6>
                        <small class="text-muted">Rasio Pria & Wanita</small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik 4: Status Kelulusan (Donut Chart) -->
            <div class="col-12 col-md-6 col-xl-6">
                <div class="custom-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                            <i class="fas fa-circle-check me-2" style="color: #2ec4b6;"></i> Persentase Kelulusan
                        </h6>
                        <small class="text-muted">Hasil Ujian & Evaluasi</small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartKelulusan"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <!-- 5. TABEL REKAPITULASI PESERTA PER KELAS -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                    <i class="fas fa-table-list me-2" style="color: var(--primary-purple);"></i> Rekapitulasi Jumlah Peserta Per Kelas
                </h6>
                <span class="badge bg-light text-muted border px-2 py-1">Tabel Rekap</span>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Kelas</th>
                            <th>Nama Pelatihan</th>
                            <th class="text-center">Jumlah Peserta</th>
                            <th class="text-center">Laki-laki</th>
                            <th class="text-center">Perempuan</th>
                            <th class="text-center">Lulus</th>
                            <th class="text-center">Tidak Lulus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rekapKelas)): ?>
                            <?php $no = 1; foreach ($rekapKelas as $rk): ?>
                            <tr>
                                <td class="text-center fw-bold"><?= $no++; ?></td>
                                <td class="fw-semibold text-dark"><?= esc($rk['nama_kelas']); ?></td>
                                <td><span class="badge bg-light text-dark border"><?= esc($rk['kategori']); ?></span></td>
                                <td class="text-center fw-bold text-primary"><?= number_format($rk['jumlah_peserta']); ?></td>
                                <td class="text-center text-info-emphasis"><?= number_format($rk['laki_laki']); ?></td>
                                <td class="text-center text-danger-emphasis"><?= number_format($rk['perempuan']); ?></td>
                                <td class="text-center"><span class="badge bg-success-subtle text-success px-2 py-1 fw-bold"><?= number_format($rk['lulus']); ?></span></td>
                                <td class="text-center"><span class="badge bg-danger-subtle text-danger px-2 py-1 fw-bold"><?= number_format($rk['tidak_lulus']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 opacity-50 d-block"></i>
                                    Tidak ada data kelas pada periode yang dipilih.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. TABEL DETAIL DATA PESERTA -->
        <div class="custom-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 pb-3 border-bottom">
                <div>
                    <h6 class="fw-bold m-0" style="color: var(--dark-purple);">
                        <i class="fas fa-address-book me-2" style="color: var(--primary-purple);"></i> Detail Data Peserta
                    </h6>
                    <small class="text-muted">Total: <?= count($detailList); ?> peserta terdaftar pada filter aktif</small>
                </div>

                <!-- Client-Side Realtime Search & Filter Status -->
                <div class="d-flex gap-2 flex-wrap">
                    <select id="filterStatusClient" class="form-select form-select-sm" style="width: auto;" onchange="filterDetailTable()">
                        <option value="all">Semua Status</option>
                        <option value="lulus">Lulus</option>
                        <option value="tidak lulus">Tidak Lulus</option>
                        <option value="dalam proses">Dalam Proses</option>
                    </select>
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <input type="text" id="searchDetailInput" class="form-control" placeholder="Cari nama, NIS, kelas..." onkeyup="filterDetailTable()">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom" id="detailPesertaTable">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Peserta</th>
                            <th>ID / NIS</th>
                            <th>Gender</th>
                            <th>Kelas</th>
                            <th>Pelatihan</th>
                            <th>Tanggal Daftar</th>
                            <th class="text-center">Status Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody id="detailPesertaTbody">
                        <?php if (!empty($detailList)): ?>
                            <?php $no = 1; foreach ($detailList as $p): ?>
                            <tr class="detail-row" 
                                data-status="<?= strtolower($p['status_kelulusan']); ?>"
                                data-search="<?= strtolower(esc($p['nama_peserta'] . ' ' . $p['resolved_nis'] . ' ' . $p['nama_kelas'] . ' ' . $p['kategori'])); ?>">
                                <td class="text-center fw-bold row-no"><?= $no++; ?></td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($p['nama_peserta']); ?></div>
                                    <small class="text-muted"><?= esc($p['resolved_email'] ?? '-'); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-mono"><?= esc($p['resolved_nis']); ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $isMale = !str_contains(strtolower($p['resolved_gender']), 'perempuan');
                                    ?>
                                    <span class="badge <?= $isMale ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger'; ?>">
                                        <i class="fas <?= $isMale ? 'fa-mars' : 'fa-venus'; ?> me-1"></i>
                                        <?= esc($p['resolved_gender']); ?>
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark"><?= esc($p['nama_kelas'] ?? '-'); ?></td>
                                <td><span class="badge bg-light text-secondary border"><?= esc($p['kategori'] ?? '-'); ?></span></td>
                                <td class="small text-muted">
                                    <?= !empty($p['tanggal_daftar']) ? date('d M Y, H:i', strtotime($p['tanggal_daftar'])) : '-'; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($p['status_kelulusan'] === 'Lulus'): ?>
                                        <span class="badge-lulus"><i class="fas fa-check-circle"></i> Lulus</span>
                                    <?php elseif ($p['status_kelulusan'] === 'Tidak Lulus'): ?>
                                        <span class="badge-tidak-lulus"><i class="fas fa-times-circle"></i> Tidak Lulus</span>
                                    <?php else: ?>
                                        <span class="badge-proses"><i class="fas fa-clock"></i> Dalam Proses</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="emptyRow">
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3 opacity-50 d-block"></i>
                                    <h6 class="fw-semibold">Tidak ada data peserta pada periode yang dipilih.</h6>
                                    <p class="small text-muted m-0">Silakan ubah filter periode atau filter kelas di atas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Client Empty Result Row (Hidden by default) -->
            <div id="noMatchMessage" class="text-center py-4 text-muted d-none">
                <i class="fas fa-filter-circle-xmark fa-2x mb-2 opacity-50"></i>
                <p class="m-0 fw-semibold">Tidak ada data peserta yang cocok dengan pencarian / status.</p>
            </div>

        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- INLINE JAVASCRIPT UNTUK INTERAKTIVITAS & GRAFIK -->
    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Toggle Field Bulan sesuai Periode
        function toggleBulanField() {
            const periode = document.getElementById('periodeSelect').value;
            const bulanWrapper = document.getElementById('bulanWrapper');
            if (periode === 'bulanan') {
                bulanWrapper.style.display = 'block';
            } else {
                bulanWrapper.style.display = 'none';
            }
        }

        // Realtime Client-side Search & Status Filter pada Tabel Detail Peserta
        function filterDetailTable() {
            const searchKeyword = document.getElementById('searchDetailInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('filterStatusClient').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.detail-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const searchData = row.getAttribute('data-search') || '';
                const rowStatus  = row.getAttribute('data-status') || '';

                const matchesSearch = (searchKeyword === '' || searchData.includes(searchKeyword));
                const matchesStatus = (statusFilter === 'all' || rowStatus === statusFilter);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                    const noCell = row.querySelector('.row-no');
                    if (noCell) noCell.textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            const noMatchMsg = document.getElementById('noMatchMessage');
            if (noMatchMsg) {
                if (visibleCount === 0 && rows.length > 0) {
                    noMatchMsg.classList.remove('d-none');
                } else {
                    noMatchMsg.classList.add('d-none');
                }
            }
        }

        // Inisialisasi 4 Grafik Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = <?= json_encode($chartData); ?>;

            // Palette Warna Modern CreativeMU
            const purpleMain = '#794bc4';
            const purpleDark = '#5931a0';
            const purpleLight = '#c8bfe7';
            const tealColor  = '#06d6a0';
            const blueColor  = '#3a86ff';
            const pinkColor  = '#ff006e';
            const redColor   = '#e63946';
            const amberColor = '#ffb703';

            // 1. Bar Chart: Peserta Per Kelas
            const ctxBar = document.getElementById('chartPesertaPerKelas');
            if (ctxBar) {
                new Chart(ctxBar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartData.bar_kelas.labels,
                        datasets: [{
                            label: 'Jumlah Peserta',
                            data: chartData.bar_kelas.data,
                            backgroundColor: purpleMain,
                            hoverBackgroundColor: purpleDark,
                            borderRadius: 8,
                            barThickness: 28,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) { return ' ' + ctx.parsed.y + ' Peserta'; }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });
            }

            // 2. Line Chart: Perkembangan Pendaftaran Peserta
            const ctxLine = document.getElementById('chartPerkembangan');
            if (ctxLine) {
                new Chart(ctxLine.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: chartData.line_tren.labels,
                        datasets: [{
                            label: 'Pendaftaran Peserta',
                            data: chartData.line_tren.data,
                            borderColor: blueColor,
                            backgroundColor: 'rgba(58, 134, 255, 0.12)',
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: blueColor,
                            pointBorderColor: '#ffffff',
                            pointHoverRadius: 6,
                            pointRadius: 4,
                            borderWidth: 2.5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) { return ' ' + ctx.parsed.y + ' Pendaftaran'; }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 },
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });
            }

            // 3. Donut Chart: Komposisi Gender
            const ctxGender = document.getElementById('chartGender');
            if (ctxGender) {
                new Chart(ctxGender.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: chartData.donut_gender.labels,
                        datasets: [{
                            data: chartData.donut_gender.data,
                            backgroundColor: [tealColor, pinkColor],
                            hoverOffset: 6,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 12 }, padding: 14 }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }

            // 4. Donut Chart: Status Kelulusan
            const ctxKelulusan = document.getElementById('chartKelulusan');
            if (ctxKelulusan) {
                new Chart(ctxKelulusan.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: chartData.donut_kelulusan.labels,
                        datasets: [{
                            data: chartData.donut_kelulusan.data,
                            backgroundColor: [tealColor, redColor, amberColor],
                            hoverOffset: 6,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { font: { size: 12 }, padding: 14 }
                            }
                        },
                        cutout: '70%'
                    }
                });
            }

        });
    </script>
</body>
</html>
