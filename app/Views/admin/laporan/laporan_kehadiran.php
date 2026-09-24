<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
            --card-shadow: 0 4px 16px rgba(121, 75, 196, 0.06);
            --card-hover-shadow: 0 8px 24px rgba(121, 75, 196, 0.12);
            --sidebar-bg: #22133c;
            --sidebar-text: #d8cde9;
            --border-soft: #ede8f5;
        }

        /* Mencegah pergeseran tata letak saat zoom dengan basis rem & skala stabil */
        html {
            font-size: 14px; /* Base diperkecil agar UI lebih compact */
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background-color: #f7f6fc;
            color: #2b2b2b;
            min-height: 100vh;
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            width: 100vw;
        }

        /* SIDEBAR STYLING (Fixed Width & Non-shift) */
        #sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            overflow-y: auto;
        }

        #sidebar .sidebar-header {
            padding: 18px 16px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        #sidebar .sidebar-header img {
            width: 180px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            filter: drop-shadow(0 2px 6px rgba(121, 75, 196, 0.4));
        }

        #sidebar .nav {
            padding: 15px 10px;
        }

        #sidebar .nav-item {
            margin-bottom: 4px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 9px 14px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.88rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        #sidebar .nav-link i {
            width: 24px;
            font-size: 1rem;
        }

        #sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
        }

        #sidebar .nav-link.active {
            color: #ffffff;
            background: var(--sidebar-active-gradient);
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
            font-weight: 600;
        }

        /* SUBMENU STYLING */
        .submenu-item .nav-link {
            padding: 7px 12px 7px 32px !important;
            font-size: 0.82rem !important;
            border-radius: 8px !important;
        }
        .submenu-item .nav-link.active {
            background: rgba(121, 75, 196, 0.4) !important;
            color: #ffffff !important;
        }

        /* MAIN CONTENT AREA */
        #main-content {
            margin-left: 250px;
            padding: 20px 24px;
            min-height: 100vh;
            width: calc(100vw - 250px);
            box-sizing: border-box;
        }

        /* TOP NAVBAR */
        .top-navbar {
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid var(--border-soft);
        }

        /* CARDS MODERN (Compact) */
        .custom-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--card-shadow);
            padding: 18px;
            margin-bottom: 20px;
        }

        /* STAT METRIC CARDS (Compact) */
        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border-soft);
            box-shadow: var(--card-shadow);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .stat-label {
            font-size: 0.72rem;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 1px;
        }
        .stat-value {
            font-size: 1.35rem;
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

        /* BUTTONS (Compact) */
        .btn-creative-primary {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            padding: 7px 16px;
            border-radius: 9px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 3px 10px rgba(121, 75, 196, 0.2);
        }
        .btn-creative-primary:hover {
            color: #ffffff;
            background: linear-gradient(135deg, #6a3bb5 0%, #4b2382 100%);
        }

        .btn-excel {
            background: #107c41;
            color: #ffffff;
            border: none;
            padding: 7px 16px;
            border-radius: 9px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 3px 10px rgba(16, 124, 65, 0.2);
        }
        .btn-excel:hover {
            background: #0b5c30;
            color: #ffffff;
        }

        /* FORMS (Compact) */
        .form-select, .form-control {
            border-radius: 9px;
            border: 1.5px solid #dcd5ec;
            padding: 7px 10px;
            font-size: 0.82rem;
            color: #333;
        }
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.15);
        }

        /* TABLES MODERN (Compact) */
        .table-custom {
            border-radius: 10px;
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        .table-custom thead th {
            background-color: var(--dark-purple);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.78rem;
            letter-spacing: 0.3px;
            padding: 10px 10px;
            border: none;
            cursor: pointer;
            user-select: none;
        }
        .table-custom tbody td {
            padding: 9px 10px;
            font-size: 0.82rem;
            border-bottom: 1px solid #f0ecfa;
            vertical-align: middle;
        }
        .table-custom tbody tr:nth-of-type(even) {
            background-color: #faf8fd;
        }

        /* MONITORING MINI CARDS */
        .monitoring-box {
            border-radius: 12px;
            padding: 12px;
            background: #fff;
            border: 1.5px solid #ede8f5;
        }

        /* CHART BOX */
        .chart-box {
            position: relative;
            height: 220px;
            width: 100%;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; width: 100vw; padding: 12px; }
            .mobile-toggle-btn { display: inline-block !important; }
        }
        .mobile-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--dark-purple);
            margin-right: 12px;
        }
    </style>
</head>
<body>

    <!-- === SIDEBAR UTAMA === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
            <?php if (!empty($isMentor)): ?>
                <div class="mt-1 text-white-50" style="font-size: 0.7rem; font-weight: 600;">PANEL MENTOR</div>
            <?php else: ?>
                <div class="mt-1 text-white-50" style="font-size: 0.7rem; font-weight: 600;">PANEL ADMIN</div>
            <?php endif; ?>
        </div>

        <ul class="nav flex-column">
            <?php if (!empty($isMentor)): ?>
                <li class="nav-item"><a href="<?= base_url('mentor/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a></li>
                <li class="nav-item"><a href="<?= base_url('mentor/kelas'); ?>" class="nav-link"><i class="fas fa-book"></i> <span>Daftar Kelas</span></a></li>
                
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanMentor" role="button" aria-expanded="true">
                        <i class="fas fa-file-lines"></i> <span>Laporan</span>
                        <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7rem;"></i>
                    </a>
                    <div class="collapse show" id="submenuLaporanMentor">
                        <ul class="nav flex-column ms-2">
                            <li class="nav-item submenu-item"><a href="<?= base_url('mentor/laporan-peserta'); ?>" class="nav-link"><i class="fas fa-chart-pie me-2"></i> <span>Laporan Peserta</span></a></li>
                            <li class="nav-item submenu-item"><a href="<?= base_url('mentor/laporan-mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user me-2"></i> <span>Laporan Mentor</span></a></li>
                            <li class="nav-item submenu-item"><a href="<?= base_url('mentor/laporan-angket'); ?>" class="nav-link"><i class="fas fa-star-half-stroke me-2"></i> <span>Laporan Angket</span></a></li>
                            <li class="nav-item submenu-item"><a href="<?= base_url('mentor/laporan-kehadiran'); ?>" class="nav-link active"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran</span></a></li>
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
                        <i class="fas fa-chevron-down ms-auto" style="font-size: 0.7rem;"></i>
                    </a>
                    <div class="collapse show" id="submenuLaporanAdmin">
                        <ul class="nav flex-column ms-2">
                            <li class="nav-item submenu-item"><a href="<?= base_url('admin/laporan-peserta'); ?>" class="nav-link"><i class="fas fa-chart-pie me-2"></i> <span>Laporan Peserta</span></a></li>
                            <li class="nav-item submenu-item"><a href="<?= base_url('admin/laporan-mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user me-2"></i> <span>Laporan Mentor</span></a></li>
                            <li class="nav-item submenu-item"><a href="<?= base_url('admin/laporan-angket'); ?>" class="nav-link"><i class="fas fa-star-half-stroke me-2"></i> <span>Laporan Angket</span></a></li>
                            <li class="nav-item submenu-item"><a href="<?= base_url('admin/laporan-kehadiran'); ?>" class="nav-link active"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran</span></a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear"></i> <span>Pengaturan</span></a></li>
            <?php endif; ?>
            <li class="nav-item mt-3"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <!-- === KONTEN UTAMA === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h5 class="mb-0 fw-bold" style="color: var(--dark-purple); font-size: 1.15rem;">Laporan Kehadiran Peserta</h5>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Rekapitulasi presensi, tingkat keaktifan, dan monitoring kedisiplinan KBM</p>
                </div>
            </div>

            <?php 
                $baseUrlReport = $isMentor ? 'mentor/laporan-kehadiran' : 'admin/laporan-kehadiran';
                $queryString = http_build_query($filters ?? []);
            ?>
            <div class="d-flex gap-2">
                <a href="<?= base_url($baseUrlReport . '/export-excel?' . $queryString); ?>" class="btn btn-excel btn-sm d-flex align-items-center">
                    <i class="fas fa-file-excel me-1"></i> <span>Excel</span>
                </a>
                <a href="<?= base_url($baseUrlReport . '/cetak?' . $queryString); ?>" target="_blank" class="btn btn-creative-primary btn-sm d-flex align-items-center">
                    <i class="fas fa-print me-1"></i> <span>PDF</span>
                </a>
            </div>
        </div>

        <!-- 1. SECTION FILTER (DENGAN FILTER TAHUN) -->
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0" style="color: var(--dark-purple); font-size: 0.9rem;">
                    <i class="fas fa-filter text-primary me-1"></i> Filter Data Kehadiran
                </h6>
                <span class="badge bg-light text-dark border px-2 py-1 fw-semibold" style="font-size: 0.75rem;">
                    <i class="fas fa-calendar-day text-primary me-1"></i> Periode: <?= esc($periodeText ?? '-'); ?>
                </span>
            </div>

            <form method="GET" action="<?= base_url($baseUrlReport); ?>" id="formFilterKehadiran">
                <div class="row g-2">
                    <!-- Filter Tahun -->
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.72rem;">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <?php 
                                $currentYear = date('Y');
                                $selectedYear = $filters['tahun'] ?? $currentYear;
                                for ($y = $currentYear; $y >= $currentYear - 3; $y--): 
                            ?>
                                <option value="<?= $y; ?>" <?= ($selectedYear == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.72rem;">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <?php foreach (($bulanNames ?? []) as $num => $name): ?>
                                <option value="<?= $num; ?>" <?= (($filters['bulan'] ?? '') == $num) ? 'selected' : ''; ?>><?= $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Pelatihan -->
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.72rem;">Pelatihan</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="all">-- Semua Pelatihan --</option>
                            <?php foreach (($categories ?? []) as $cat): ?>
                                <option value="<?= esc($cat); ?>" <?= (($filters['kategori'] ?? '') === $cat) ? 'selected' : ''; ?>>
                                    <?= esc($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kelas -->
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.72rem;">Kelas</label>
                        <select name="id_kelas" class="form-select form-select-sm">
                            <option value="all">-- Semua Kelas --</option>
                            <?php foreach (($classes ?? []) as $c): ?>
                                <option value="<?= $c['id_kelas']; ?>" <?= (($filters['id_kelas'] ?? '') == $c['id_kelas']) ? 'selected' : ''; ?>>
                                    <?= esc($c['nama_kelas']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Tempat Pelatihan -->
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.72rem;">Tempat</label>
                        <select name="tempat_pelatihan" class="form-select form-select-sm">
                            <option value="all">-- Semua Tempat --</option>
                            <?php foreach (($tempatList ?? []) as $tempat): ?>
                                <option value="<?= esc($tempat); ?>" <?= (($filters['tempat_pelatihan'] ?? 'all') === $tempat) ? 'selected' : ''; ?>><?= esc($tempat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Status Kehadiran -->
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1" style="font-size: 0.72rem;">Status</label>
                        <select name="status_kehadiran" class="form-select form-select-sm">
                            <option value="all">-- Semua Status --</option>
                            <option value="hadir" <?= (($filters['status_kehadiran'] ?? '') === 'hadir') ? 'selected' : ''; ?>>Hadir</option>
                            <option value="terlambat" <?= (($filters['status_kehadiran'] ?? '') === 'terlambat') ? 'selected' : ''; ?>>Terlambat</option>
                            <option value="izin" <?= (($filters['status_kehadiran'] ?? '') === 'izin') ? 'selected' : ''; ?>>Izin</option>
                            <option value="sakit" <?= (($filters['status_kehadiran'] ?? '') === 'sakit') ? 'selected' : ''; ?>>Sakit</option>
                            <option value="alpa" <?= (($filters['status_kehadiran'] ?? '') === 'alpa') ? 'selected' : ''; ?>>Alpa</option>
                        </select>
                    </div>

                    <!-- Tombol Action -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <a href="<?= base_url($baseUrlReport); ?>" class="btn btn-sm btn-outline-secondary px-3" style="border-radius:8px; font-size: 0.8rem;">
                            <i class="fas fa-rotate-left me-1"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-sm btn-creative-primary px-3" style="font-size: 0.8rem;">
                            <i class="fas fa-magnifying-glass me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. CARD RINGKASAN STATISTIK (COMPACT GRID) -->
        <div class="row g-2 mb-3">
            <?php 
                $statsData = [
                    ['label' => 'Total Peserta', 'val' => $stats['total_peserta'] ?? 0, 'sub' => 'Terdaftar', 'theme' => 'stat-theme-purple', 'icon' => 'fa-users'],
                    ['label' => 'Total Pertemuan', 'val' => $stats['total_pertemuan'] ?? 0, 'sub' => 'Sesi KBM', 'theme' => 'stat-theme-blue', 'icon' => 'fa-calendar-check'],
                    ['label' => 'Kehadiran', 'val' => $stats['total_kehadiran'] ?? 0, 'sub' => 'Siswa Masuk', 'theme' => 'stat-theme-primary', 'icon' => 'fa-clipboard-user'],
                    ['label' => 'Hadir Tepat', 'val' => $stats['total_hadir'] ?? 0, 'sub' => 'Tepat Waktu', 'theme' => 'stat-theme-green', 'icon' => 'fa-user-check', 'color' => 'text-success'],
                    ['label' => 'Terlambat', 'val' => $stats['total_terlambat'] ?? 0, 'sub' => '> 15 Mnt', 'theme' => 'stat-theme-warning', 'icon' => 'fa-user-clock', 'color' => 'text-warning'],
                    ['label' => 'Izin', 'val' => $stats['total_izin'] ?? 0, 'sub' => 'Surat Izin', 'theme' => 'stat-theme-info', 'icon' => 'fa-envelope-open-text', 'color' => 'text-info'],
                    ['label' => 'Sakit', 'val' => $stats['total_sakit'] ?? 0, 'sub' => 'Surat Dokter', 'theme' => 'stat-theme-secondary', 'icon' => 'fa-notes-medical', 'color' => 'text-secondary'],
                    ['label' => 'Alpa', 'val' => $stats['total_alpa'] ?? 0, 'sub' => 'Tanpa Ket.', 'theme' => 'stat-theme-danger', 'icon' => 'fa-user-xmark', 'color' => 'text-danger'],
                ];
                foreach($statsData as $st):
            ?>
            <div class="col-6 col-md-3 col-xl-3">
                <div class="stat-card <?= $st['theme']; ?>">
                    <div class="stat-icon"><i class="fas <?= $st['icon']; ?>"></i></div>
                    <div>
                        <div class="stat-label"><?= $st['label']; ?></div>
                        <div class="stat-value <?= $st['color'] ?? ''; ?>"><?= number_format($st['val']); ?></div>
                        <small class="text-muted" style="font-size: 0.65rem;"><?= $st['sub']; ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Rata-rata Kehadiran -->
            <div class="col-12">
                <div class="stat-card" style="background: linear-gradient(135deg, #ffffff 0%, #f4edff 100%); border-left: 4px solid var(--primary-purple);">
                    <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon" style="background: var(--primary-purple); color: #fff; width: 42px; height: 42px; font-size: 1.2rem;">
                                <i class="fas fa-percent"></i>
                            </div>
                            <div>
                                <div class="stat-label">Tingkat Kehadiran Rata-rata Peserta</div>
                                <div class="fs-5 fw-bold text-dark">
                                    <span class="text-primary"><?= number_format($stats['persentase_kehadiran'] ?? 0, 1); ?>%</span> 
                                    <span class="small text-muted fw-normal ms-2" style="font-size: 0.75rem;">Akumulasi seluruh kelas</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <?php if (($stats['persentase_kehadiran'] ?? 0) >= 85): ?>
                                <span class="badge bg-success px-2 py-1" style="font-size: 0.75rem;"><i class="fas fa-circle-check me-1"></i> Sangat Baik</span>
                            <?php elseif (($stats['persentase_kehadiran'] ?? 0) >= 75): ?>
                                <span class="badge bg-primary px-2 py-1" style="font-size: 0.75rem;"><i class="fas fa-circle-check me-1"></i> Baik</span>
                            <?php else: ?>
                                <span class="badge bg-danger px-2 py-1" style="font-size: 0.75rem;"><i class="fas fa-triangle-exclamation me-1"></i> Perlu Perhatian</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. GRAFIK INFORMATIF (COMPACT) -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-4">
                <div class="custom-card h-100 mb-0">
                    <h6 class="fw-bold mb-2" style="color: var(--dark-purple); font-size: 0.88rem;"><i class="fas fa-chart-pie text-primary me-1"></i> Status Kehadiran</h6>
                    <div class="chart-box">
                        <canvas id="chartStatusDonut"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="custom-card h-100 mb-0">
                    <h6 class="fw-bold mb-2" style="color: var(--dark-purple); font-size: 0.88rem;"><i class="fas fa-chart-column text-success me-1"></i> Kehadiran per Kelas Pelatihan</h6>
                    <div class="chart-box">
                        <canvas id="chartKelasBar"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. TABEL REKAP KEHADIRAN PESERTA -->
        <div class="custom-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h6 class="fw-bold mb-0" style="color: var(--dark-purple); font-size: 0.9rem;">
                        <i class="fas fa-table-list text-primary me-1"></i> Rekapitulasi Kehadiran Peserta
                    </h6>
                    <small class="text-muted" style="font-size: 0.72rem;">Gunakan kolom pencarian cepat atau klik header tabel untuk sorting</small>
                </div>
                <div class="input-group input-group-sm" style="width: 220px;">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted" style="font-size: 0.75rem;"></i></span>
                    <input type="text" id="tableSearchInput" class="form-control border-start-0" placeholder="Cari nama, NIS..." onkeyup="filterTableLive()">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0" id="mainRekapTable">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center" onclick="sortTable(0)">No</th>
                            <th width="10%" class="text-center" onclick="sortTable(1)">NIS</th>
                            <th onclick="sortTable(2)">Nama Peserta</th>
                            <th onclick="sortTable(3)">Kelas</th>
                            <th onclick="sortTable(4)">Pelatihan</th>
                            <th>Tempat</th>
                            <th width="6%" class="text-center" onclick="sortTable(5, true)">Sesi</th>
                            <th width="6%" class="text-center text-success" onclick="sortTable(6, true)">Hadir</th>
                            <th width="5%" class="text-center" onclick="sortTable(7, true)">Izin</th>
                            <th width="5%" class="text-center" onclick="sortTable(8, true)">Sakit</th>
                            <th width="5%" class="text-center text-danger" onclick="sortTable(9, true)">Alpa</th>
                            <th width="6%" class="text-center text-warning" onclick="sortTable(10, true)">Telat</th>
                            <th width="8%" class="text-center" onclick="sortTable(11, true)">%</th>
                            <th width="9%" class="text-center">Status</th>
                            <th width="7%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rekapTableBody">
                        <?php if (!empty($rekapList)): ?>
                            <?php $no = 1; foreach ($rekapList as $row): ?>
                            <tr class="table-row-item">
                                <td class="text-center text-muted fw-bold"><?= $no++; ?></td>
                                <td class="text-center"><code><?= esc($row['nis']); ?></code></td>
                                <td>
                                    <strong class="text-dark"><?= esc($row['nama_peserta']); ?></strong>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= esc($row['kelas']); ?></span></td>
                                <td><span class="text-secondary small fw-semibold"><?= esc($row['pelatihan']); ?></span></td>
                                <td class="small"><?= esc($row['tempat_pelatihan'] ?? '-'); ?></td>
                                <td class="text-center fw-bold"><?= $row['total_pertemuan']; ?></td>
                                <td class="text-center fw-bold text-success"><?= $row['hadir']; ?></td>
                                <td class="text-center"><?= $row['izin']; ?></td>
                                <td class="text-center"><?= $row['sakit']; ?></td>
                                <td class="text-center fw-bold text-danger"><?= $row['alpa']; ?></td>
                                <td class="text-center fw-bold text-warning"><?= $row['terlambat']; ?></td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary"><?= number_format($row['persentase_kehadiran'], 1); ?>%</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $row['badge_class']; ?> px-2 py-0.5" style="font-size: 0.7rem;">
                                        <?= esc($row['predikat']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary px-2 py-0.5" style="font-size: 0.75rem; border-radius:6px;" onclick="openModalDetail(<?= $row['id_pendaftaran']; ?>, '<?= esc($row['nama_peserta']); ?>')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="emptyRow">
                                <td colspan="15" class="text-center py-4 text-muted">
                                    <i class="fas fa-clipboard-user fa-2x text-muted mb-2 opacity-50"></i>
                                    <p class="small mb-0">Belum ada data kehadiran pada periode yang dipilih.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SCRIPT CHART & INTERAKSI -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const chartDataServer = <?= json_encode($chartData ?? []); ?>;

            // Donut Chart
            if (document.getElementById('chartStatusDonut') && chartDataServer.chart_status_donut) {
                new Chart(document.getElementById('chartStatusDonut').getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: chartDataServer.chart_status_donut.labels,
                        datasets: [{
                            data: chartDataServer.chart_status_donut.data,
                            backgroundColor: ['#2e7d32', '#f59e0b', '#0284c7', '#64748b', '#dc2626'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } },
                        cutout: '60%'
                    }
                });
            }

            // Bar Chart
            if (document.getElementById('chartKelasBar') && chartDataServer.chart_kelas_bar) {
                new Chart(document.getElementById('chartKelasBar').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartDataServer.chart_kelas_bar.labels,
                        datasets: [{
                            data: chartDataServer.chart_kelas_bar.data,
                            backgroundColor: 'rgba(121, 75, 196, 0.8)',
                            borderColor: '#794bc4',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%', font: { size: 10 } } },
                            x: { ticks: { font: { size: 10 } } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        });

        function filterTableLive() {
            const query = document.getElementById('tableSearchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#rekapTableBody tr.table-row-item');
            rows.forEach(r => {
                r.style.display = r.textContent.toLowerCase().includes(query) ? '' : 'none';
            });
        }

        let sortDirections = {};
        function sortTable(colIndex, isNumeric = false) {
            const tbody = document.getElementById('rekapTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr.table-row-item'));
            const isAsc = !sortDirections[colIndex];
            sortDirections[colIndex] = isAsc;

            rows.sort((a, b) => {
                let valA = a.children[colIndex].textContent.trim();
                let valB = b.children[colIndex].textContent.trim();
                if (isNumeric) {
                    valA = parseFloat(valA.replace(/[^0-9.-]/g, '')) || 0;
                    valB = parseFloat(valB.replace(/[^0-9.-]/g, '')) || 0;
                    return isAsc ? valA - valB : valB - valA;
                }
                return isAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
            });
            rows.forEach(r => tbody.appendChild(r));
        }
    </script>
</body>
</html>