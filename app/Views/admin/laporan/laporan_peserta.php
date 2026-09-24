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
            --card-shadow: 0 4px 15px rgba(121, 75, 196, 0.05);
            --border-soft: rgba(121, 75, 196, 0.08);
        }

        /* MENCEGAH LAYOUT PECAH SAAT ZOOM */
        html, body {
            width: 100%;
            min-width: 1200px;
            overflow-x: auto;
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            margin: 0;
            color: #495057;
            font-size: 0.8125rem;
        }

        /* SIDEBAR STYLING */
        #sidebar {
            width: 250px !important;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(121, 75, 196, 0.08);
            overflow-y: auto;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        #sidebar .sidebar-header {
            padding: 16px 12px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        /* LOGO PERSEGI PANJANG - LEBIH BESAR & PROPORSIONAL */
        #sidebar .sidebar-header img {
            width: 100%;
            max-width: 210px;
            height: auto;
            max-height: 80px;
            object-fit: contain;
            border-radius: 8px;
            padding: 6px 10px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
            display: block;
            margin: 0 auto;
        }

        #sidebar .nav {
            padding: 12px 10px;
        }

        #sidebar .nav-item {
            margin-bottom: 4px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 8px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.825rem;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        #sidebar .nav-link i {
            width: 24px;
            font-size: 0.95rem;
        }

        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }

        #sidebar .nav-link.active {
            color: #ffffff;
            background: var(--sidebar-active-gradient);
            box-shadow: 0 3px 10px rgba(121, 75, 196, 0.35);
            font-weight: 600;
        }

        /* MAIN CONTENT MENYESUAIKAN DENGAN PRESISI */
        #main-content {
            margin-left: 250px !important;
            width: calc(100% - 250px) !important;
            min-width: 950px;
            padding: 20px;
            min-height: 100vh;
            box-sizing: border-box;
            transition: margin-left 0.3s ease;
        }

        .top-navbar {
            background: #ffffff;
            padding: 12px 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-soft);
        }

        .custom-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-soft);
            padding: 16px;
            margin-bottom: 18px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-soft);
            padding: 12px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(121, 75, 196, 0.1);
        }

        .stat-card .stat-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: #fff;
            margin-bottom: 8px;
        }

        .stat-card .stat-value {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--dark-purple);
        }

        .stat-card .stat-label {
            font-size: 0.7rem;
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 2px;
        }

        .bg-grad-purple { background: linear-gradient(135deg, #794bc4 0%, #5931a0 100%); }
        .bg-grad-blue   { background: linear-gradient(135deg, #3a86ff 0%, #0056b3 100%); }
        .bg-grad-cyan   { background: linear-gradient(135deg, #06d6a0 0%, #009688 100%); }
        .bg-grad-pink   { background: linear-gradient(135deg, #ff006e 0%, #b5179e 100%); }
        .bg-grad-green  { background: linear-gradient(135deg, #2ec4b6 0%, #157347 100%); }
        .bg-grad-orange { background: linear-gradient(135deg, #ff9f1c 0%, #e63946 100%); }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(121, 75, 196, 0.2);
            transition: all 0.25s ease;
        }
        .btn-purple:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(121, 75, 196, 0.3);
        }

        .btn-outline-purple {
            border: 1.5px solid var(--primary-purple);
            color: var(--primary-purple);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            background: transparent;
            transition: all 0.25s ease;
        }
        .btn-outline-purple:hover {
            background: var(--light-purple);
            color: var(--primary-purple);
            transform: translateY(-1px);
        }

        .btn-excel {
            background: #107c41;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(16, 124, 65, 0.15);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-excel:hover {
            background: #0b582e;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-print {
            background: #5931a0;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 3px 8px rgba(89, 49, 160, 0.15);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-print:hover {
            background: #42207a;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .form-select, .form-control {
            border-radius: 8px;
            padding: 6px 10px;
            border: 1.5px solid #e0dbf0;
            font-size: 0.8rem;
            color: #333;
        }
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 2px rgba(121, 75, 196, 0.15);
        }

        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            min-width: 850px;
        }
        .table-custom thead th {
            background-color: var(--dark-purple);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            padding: 10px 12px;
            border: none;
        }
        .table-custom tbody td {
            padding: 8px 12px;
            font-size: 0.8rem;
            border-bottom: 1px solid #f0ecfa;
            vertical-align: middle;
        }

        .badge-lulus {
            background-color: #d1e7dd;
            color: #0f5132;
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .badge-tidak-lulus {
            background-color: #f8d7da;
            color: #842029;
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .badge-proses {
            background-color: #fff3cd;
            color: #664d03;
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .chart-container-box {
            position: relative;
            height: 220px;
            width: 100%;
        }

        /* MATIKAN MEDIA QUERY MOBILE AGAR TAMPILAN DESKTOP KONSISTEN SAAT ZOOM */
        @media (max-width: 991px) {
            #sidebar {
                transform: none !important;
            }
            #main-content {
                margin-left: 250px !important;
                width: calc(100% - 250px) !important;
            }
            .mobile-toggle-btn {
                display: none !important;
            }
        }

        .mobile-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--dark-purple);
            margin-right: 10px;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- === SIDEBAR === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy">
            <?php if (!empty($isMentor)): ?>
                <div class="mt-2 text-white-50 super-small fw-semibold" style="font-size: 0.7rem;">PANEL MENTOR</div>
            <?php else: ?>
                <div class="mt-2 text-white-50 super-small fw-semibold" style="font-size: 0.7rem;">PANEL ADMIN</div>
            <?php endif; ?>
        </div>

        <ul class="nav flex-column">
            <?php if (!empty($isMentor)): ?>
                <li class="nav-item"><a href="<?= base_url('mentor/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a></li>
                <li class="nav-item"><a href="<?= base_url('mentor/kelas'); ?>" class="nav-link"><i class="fas fa-book"></i> <span>Daftar Kelas</span></a></li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanMentor" role="button" aria-expanded="true">
                        <i class="fas fa-file-lines"></i> <span>Laporan</span>
                        <i class="fas fa-chevron-down ms-auto" style="font-size: 0.65rem;"></i>
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
                <li class="nav-item"><a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link"><i class="fas fa-book-open"></i> <span>Buku Induk</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/angket'); ?>" class="nav-link"><i class="fas fa-poll"></i> <span>Angket</span></a></li>
                <li class="nav-item"><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-award"></i> <span>Sertifikat</span></a></li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanAdmin" role="button" aria-expanded="true">
                        <i class="fas fa-chart-simple"></i> <span>Laporan</span>
                        <i class="fas fa-chevron-down ms-auto" style="font-size: 0.65rem;"></i>
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
            <li class="nav-item mt-3"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <!-- === MAIN CONTENT === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h5 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 1.05rem;">Laporan Data Peserta Pelatihan</h5>
                    <p class="text-muted m-0" style="font-size: 0.75rem;">Analisis komprehensif data pendaftaran, kelulusan, dan statistik peserta.</p>
                </div>
            </div>

            <?php 
                $baseUrlReport = $isMentor ? 'mentor/laporan' : 'admin/laporan';
                $exportQuery = http_build_query($filters ?? []);
            ?>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= base_url($baseUrlReport . '/export-excel?' . $exportQuery); ?>" class="btn-excel">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="<?= base_url($baseUrlReport . '/cetak?' . $exportQuery); ?>" target="_blank" class="btn-print">
                    <i class="fas fa-print me-1"></i> Cetak / PDF
                </a>
            </div>
        </div>

        <!-- FILTER FORM CARD -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.85rem;">
                    <i class="fas fa-filter me-1" style="color: var(--primary-purple);"></i> Filter Laporan
                </h6>
                <small class="text-muted" style="font-size: 0.7rem;">Pilih parameter untuk menyaring data laporan</small>
            </div>

            <form action="<?= base_url($baseUrlReport); ?>" method="GET" id="filterForm">
                <div class="row g-2">
                    
                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label mb-1 super-small fw-semibold text-muted" style="font-size: 0.725rem;">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="all">-- Semua Bulan --</option>
                            <?php 
                            $listBulan = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                            $currentBulan = $filters['bulan'] ?? date('m');
                            foreach ($listBulan as $bKey => $bVal): 
                            ?>
                                <option value="<?= $bKey; ?>" <?= ($currentBulan === $bKey) ? 'selected' : ''; ?>><?= $bVal; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label mb-1 super-small fw-semibold text-muted" style="font-size: 0.725rem;">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="all">-- Semua Tahun --</option>
                            <?php 
                            $currentTahun = $filters['tahun'] ?? date('Y');
                            $startYear = 2020;
                            $endYear = date('Y') + 1;
                            for ($y = $endYear; $y >= $startYear; $y--): 
                            ?>
                                <option value="<?= $y; ?>" <?= ((string)$currentTahun === (string)$y) ? 'selected' : ''; ?>><?= $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label mb-1 super-small fw-semibold text-muted" style="font-size: 0.725rem;">Kelas</label>
                        <select name="id_kelas" class="form-select form-select-sm">
                            <option value="all">-- Semua Kelas --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= $c['id_kelas']; ?>" <?= ((string)($filters['id_kelas'] ?? '') === (string)$c['id_kelas']) ? 'selected' : ''; ?>>
                                    <?= esc($c['nama_kelas']); ?> (<?= esc($c['nama_mentor'] ?? 'Mentor -'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-2">
                        <label class="form-label mb-1 super-small fw-semibold text-muted" style="font-size: 0.725rem;">Pelatihan / Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="all">-- Semua Pelatihan --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc($cat); ?>" <?= (($filters['kategori'] ?? '') === $cat) ? 'selected' : ''; ?>>
                                    <?= esc($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label mb-1 super-small fw-semibold text-muted" style="font-size: 0.725rem;">Tempat Pelatihan</label>
                        <select name="tempat_pelatihan" class="form-select form-select-sm">
                            <option value="all">-- Semua Tempat --</option>
                            <?php 
                            $selectedTempat = $filters['tempat_pelatihan'] ?? 'all';
                            $listTempatOpt = $tempatList ?? ['Kantor Pusat', 'Kantor Cabang', 'Kantor Perwakilan'];
                            ?>
                            <?php foreach ($listTempatOpt as $tempat): ?>
                                <option value="<?= esc($tempat); ?>" <?= ($selectedTempat === $tempat) ? 'selected' : ''; ?>><?= esc($tempat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 d-flex gap-2 justify-content-end align-items-center mt-2 pt-1">
                        <a href="<?= base_url($baseUrlReport); ?>" class="btn btn-outline-purple">
                            <i class="fas fa-rotate-left me-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-purple">
                            <i class="fas fa-magnifying-glass me-1"></i> Terapkan Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- DASHBOARD RINGKASAN (6 CARDS STATISTIK) -->
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-purple"><i class="fas fa-users"></i></div>
                    <div class="stat-value"><?= number_format($stats['total_peserta']); ?></div>
                    <div class="stat-label">Total Peserta</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-blue"><i class="fas fa-chalkboard-user"></i></div>
                    <div class="stat-value"><?= number_format($stats['total_kelas']); ?></div>
                    <div class="stat-label">Total Kelas</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-cyan"><i class="fas fa-mars"></i></div>
                    <div class="stat-value"><?= number_format($stats['peserta_laki']); ?></div>
                    <div class="stat-label">Laki-laki</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-pink"><i class="fas fa-venus"></i></div>
                    <div class="stat-value"><?= number_format($stats['peserta_perempuan']); ?></div>
                    <div class="stat-label">Perempuan</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-green"><i class="fas fa-award"></i></div>
                    <div class="stat-value"><?= number_format($stats['peserta_lulus']); ?></div>
                    <div class="stat-label">Peserta Lulus</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="stat-card">
                    <div class="stat-icon bg-grad-orange"><i class="fas fa-circle-xmark"></i></div>
                    <div class="stat-value"><?= number_format($stats['peserta_tidak_lulus']); ?></div>
                    <div class="stat-label">Tidak Lulus</div>
                </div>
            </div>
        </div>

        <!-- GRAFIK LAPORAN INTERAKTIF -->
        <div class="row g-2 mb-3">
            <div class="col-12 col-xl-6">
                <div class="custom-card h-100 mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.85rem;">
                            <i class="fas fa-chart-column me-1" style="color: var(--primary-purple);"></i> Jumlah Peserta Per Kelas
                        </h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Distribusi peserta</small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartPesertaPerKelas"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="custom-card h-100 mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.85rem;">
                            <i class="fas fa-chart-line me-1" style="color: #3a86ff;"></i> Perkembangan Pendaftaran
                        </h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Tren Periode Ini</small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartPerkembangan"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-6">
                <div class="custom-card h-100 mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.85rem;">
                            <i class="fas fa-chart-pie me-1" style="color: #06d6a0;"></i> Komposisi Gender Peserta
                        </h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Rasio Pria & Wanita</small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-6">
                <div class="custom-card h-100 mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.85rem;">
                            <i class="fas fa-circle-check me-1" style="color: #2ec4b6;"></i> Persentase Kelulusan
                        </h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Hasil Ujian & Evaluasi</small>
                    </div>
                    <div class="chart-container-box">
                        <canvas id="chartKelulusan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL REKAPITULASI -->
        <div class="custom-card">
            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.85rem;">
                    <i class="fas fa-list-check me-1" style="color: var(--primary-purple);"></i> Rekapitulasi Jumlah Peserta
                </h6>
            </div>

            <div class="table-responsive">
                <table class="table-custom border">
                    <thead>
                        <tr>
                            <th rowspan="2" width="4%" class="text-center align-middle">No</th>
                            <th rowspan="2" width="22%" class="align-middle">Nama Kelas</th>
                            <th rowspan="2" width="16%" class="align-middle">Kategori / Pelatihan</th>
                            <th colspan="3" class="text-center border-bottom" style="background-color: #311b58;">
                                <i class="fas fa-building me-1 text-warning"></i> Tempat Pelatihan
                            </th>
                            <th rowspan="2" class="text-center align-middle">Total Peserta</th>
                            <th rowspan="2" class="text-center align-middle">Laki-Laki</th>
                            <th rowspan="2" class="text-center align-middle">Perempuan</th>
                            <th rowspan="2" class="text-center align-middle">Lulus</th>
                            <th rowspan="2" class="text-center align-middle">Tidak Lulus</th>
                        </tr>
                        <tr>
                            <th class="text-center bg-dark-subtle text-dark" style="font-size: 0.725rem; min-width: 110px;">Kantor Pusat</th>
                            <th class="text-center bg-dark-subtle text-dark" style="font-size: 0.725rem; min-width: 110px;">Kantor Cabang</th>
                            <th class="text-center bg-dark-subtle text-dark" style="font-size: 0.725rem; min-width: 110px;">Kantor Perwakilan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rekapKelas)): ?>
                            <?php $no = 1; foreach ($rekapKelas as $k): ?>
                                <?php 
                                    $pusat = $k['jumlah_pusat'] ?? $k['kantor_pusat'] ?? $k['pusat'] ?? 0;
                                    $cabang = $k['jumlah_cabang'] ?? $k['kantor_cabang'] ?? $k['cabang'] ?? 0;
                                    $perwakilan = $k['jumlah_perwakilan'] ?? $k['kantor_perwakilan'] ?? $k['perwakilan'] ?? 0;

                                    if ($pusat == 0 && $cabang == 0 && $perwakilan == 0 && !empty($detailList)) {
                                        foreach ($detailList as $d) {
                                            if (($d['id_kelas'] ?? '') == ($k['id_kelas'] ?? '')) {
                                                $tp = strtolower($d['tempat_pelatihan'] ?? '');
                                                if (str_contains($tp, 'pusat')) $pusat++;
                                                elseif (str_contains($tp, 'cabang')) $cabang++;
                                                elseif (str_contains($tp, 'perwakilan')) $perwakilan++;
                                            }
                                        }
                                    }
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $no++; ?></td>
                                    <td class="fw-semibold text-dark"><?= esc($k['nama_kelas']); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= esc($k['kategori']); ?></span></td>
                                    <td class="text-center fw-semibold text-primary"><?= number_format($pusat); ?></td>
                                    <td class="text-center fw-semibold text-primary"><?= number_format($cabang); ?></td>
                                    <td class="text-center fw-semibold text-primary"><?= number_format($perwakilan); ?></td>
                                    <td class="text-center fw-bold text-purple"><?= number_format($k['jumlah_peserta']); ?></td>
                                    <td class="text-center text-muted"><?= number_format($k['laki_laki']); ?></td>
                                    <td class="text-center text-muted"><?= number_format($k['perempuan']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success"><?= number_format($k['lulus']); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-danger"><?= number_format($k['tidak_lulus']); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    Tidak ada data rekapitulasi.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TABEL DETAIL DATA PESERTA -->
        <div class="custom-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2 pb-2 border-bottom">
                <div>
                    <h6 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 0.85rem;">
                        <i class="fas fa-address-book me-1" style="color: var(--primary-purple);"></i> Detail Data Peserta
                    </h6>
                    <small class="text-muted" style="font-size: 0.7rem;">Total: <?= count($detailList ?? []); ?> peserta terdaftar</small>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <select id="filterStatusClient" class="form-select form-select-sm" style="width: auto;" onchange="filterDetailTable()">
                        <option value="all">Semua Status</option>
                        <option value="lulus">Lulus</option>
                        <option value="tidak lulus">Tidak Lulus</option>
                        <option value="dalam proses">Dalam Proses</option>
                    </select>
                    <div class="input-group input-group-sm" style="width: 220px;">
                        <input type="text" id="searchDetailInput" class="form-control" placeholder="Cari nama, NIS, kelas..." onkeyup="filterDetailTable()">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom" id="detailPesertaTable">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">No</th>
                            <th>Nama Peserta</th>
                            <th>ID / NIS</th>
                            <th>Gender</th>
                            <th>Kelas</th>
                            <th>Pelatihan</th>
                            <th>Tempat Pelatihan</th>
                            <th>Tanggal Daftar</th>
                            <th class="text-center">Status Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody id="detailPesertaTbody">
                        <?php if (!empty($detailList)): ?>
                            <?php $no = 1; foreach ($detailList as $p): ?>
                            <tr class="detail-row" 
                                data-status="<?= strtolower($p['status_kelulusan'] ?? 'dalam proses'); ?>"
                                data-search="<?= strtolower(esc(($p['nama_peserta'] ?? '') . ' ' . ($p['resolved_nis'] ?? '') . ' ' . ($p['nama_kelas'] ?? '') . ' ' . ($p['kategori'] ?? '') . ' ' . ($p['tempat_pelatihan'] ?? ''))); ?>">
                                <td class="text-center fw-bold row-no"><?= $no++; ?></td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($p['nama_peserta'] ?? '-'); ?></div>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?= esc($p['resolved_email'] ?? '-'); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-mono" style="font-size: 0.7rem;"><?= esc($p['resolved_nis'] ?? '-'); ?></span>
                                </td>
                                <td>
                                    <?php 
                                    $isMale = !str_contains(strtolower($p['resolved_gender'] ?? ''), 'perempuan');
                                    ?>
                                    <span class="badge <?= $isMale ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger'; ?>" style="font-size: 0.68rem;">
                                        <i class="fas <?= $isMale ? 'fa-mars' : 'fa-venus'; ?> me-1"></i>
                                        <?= esc($p['resolved_gender'] ?? '-'); ?>
                                    </span>
                                </td>
                                <td class="fw-semibold text-dark"><?= esc($p['nama_kelas'] ?? '-'); ?></td>
                                <td><span class="badge bg-light text-secondary border" style="font-size: 0.68rem;"><?= esc($p['kategori'] ?? '-'); ?></span></td>
                                
                                <td>
                                    <span class="text-dark" style="max-width: 200px; display: inline-block; white-space: normal; font-size: 0.75rem;">
                                        <?= esc($p['tempat_pelatihan'] ?? '-'); ?>
                                    </span>
                                </td>

                                <td class="text-muted" style="font-size: 0.725rem;">
                                    <?= !empty($p['tanggal_daftar']) ? date('d M Y, H:i', strtotime($p['tanggal_daftar'])) : '-'; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (($p['status_kelulusan'] ?? '') === 'Lulus'): ?>
                                        <span class="badge-lulus"><i class="fas fa-check-circle"></i> Lulus</span>
                                    <?php elseif (($p['status_kelulusan'] ?? '') === 'Tidak Lulus'): ?>
                                        <span class="badge-tidak-lulus"><i class="fas fa-times-circle"></i> Tidak Lulus</span>
                                    <?php else: ?>
                                        <span class="badge-proses"><i class="fas fa-clock"></i> Dalam Proses</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="emptyRow">
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-2 opacity-50 d-block"></i>
                                    <h6 class="fw-semibold" style="font-size: 0.85rem;">Tidak ada data peserta pada periode ini.</h6>
                                    <p class="text-muted m-0" style="font-size: 0.7rem;">Silakan ubah filter bulan, tahun, atau kelas di atas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="noMatchMessage" class="text-center py-3 text-muted d-none">
                <i class="fas fa-filter-circle-xmark fa-2x mb-1 opacity-50"></i>
                <p class="m-0 fw-semibold" style="font-size: 0.75rem;">Tidak ada data peserta yang cocok dengan pencarian / status.</p>
            </div>

        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- INLINE JAVASCRIPT -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            const chartData = <?= json_encode($chartData ?? []); ?>;

            const purpleMain = '#794bc4';
            const purpleDark = '#5931a0';
            const tealColor  = '#06d6a0';
            const blueColor  = '#3a86ff';
            const pinkColor  = '#ff006e';
            const redColor   = '#e63946';
            const amberColor = '#ffb703';

            const ctxBar = document.getElementById('chartPesertaPerKelas');
            if (ctxBar && chartData.bar_kelas) {
                new Chart(ctxBar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartData.bar_kelas.labels,
                        datasets: [{
                            label: 'Jumlah Peserta',
                            data: chartData.bar_kelas.data,
                            backgroundColor: purpleMain,
                            hoverBackgroundColor: purpleDark,
                            borderRadius: 6,
                            barThickness: 22,
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
                            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }

            const ctxLine = document.getElementById('chartPerkembangan');
            if (ctxLine && chartData.line_tren) {
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
                            pointHoverRadius: 5,
                            pointRadius: 3,
                            borderWidth: 2,
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
                            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 10 } }, grid: { color: 'rgba(0,0,0,0.05)' } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }

            const ctxGender = document.getElementById('chartGender');
            if (ctxGender && chartData.donut_gender) {
                new Chart(ctxGender.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: chartData.donut_gender.labels,
                        datasets: [{
                            data: chartData.donut_gender.data,
                            backgroundColor: [tealColor, pinkColor],
                            hoverOffset: 4,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 10 } } },
                        cutout: '70%'
                    }
                });
            }

            const ctxKelulusan = document.getElementById('chartKelulusan');
            if (ctxKelulusan && chartData.donut_kelulusan) {
                new Chart(ctxKelulusan.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: chartData.donut_kelulusan.labels,
                        datasets: [{
                            data: chartData.donut_kelulusan.data,
                            backgroundColor: [tealColor, redColor, amberColor],
                            hoverOffset: 4,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 10 } } },
                        cutout: '70%'
                    }
                });
            }
        });
    </script>
</body>
</html>