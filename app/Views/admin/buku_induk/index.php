<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
            --card-hover-shadow: 0 14px 28px rgba(121, 75, 196, 0.12);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            color: #2b263b;
            overflow-x: hidden;
            margin: 0;
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
            transition: all 0.3s ease;
            z-index: 1050;
            overflow-y: auto;
            box-shadow: 8px 0 30px rgba(121, 75, 196, 0.08);
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        #sidebar .sidebar-header img {
            width: 240px;
            height: 95px;
            object-fit: cover;
            border-radius: 10px;
            filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
        }

        #sidebar .nav {
            padding: 18px 14px;
        }

        #sidebar .nav-item {
            margin-bottom: 5px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 11px 16px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.25s ease;
            font-size: 0.88rem;
            text-decoration: none;
        }

        #sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.05rem;
            width: 22px;
            text-align: center;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
            transform: translateX(4px);
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        .submenu-item .nav-link {
            padding-left: 28px !important;
            font-size: 0.84rem;
        }

        /* MAIN CONTENT AREA */
        #main-content {
            margin-left: 275px;
            padding: 30px 35px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* TOP NAVBAR */
        .top-navbar {
            background: #ffffff;
            padding: 20px 28px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        /* STAT CARDS */
        .stat-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 20px 24px;
            box-shadow: 0 8px 24px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.06);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-hover-shadow);
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-purple { background: #f2ebfc; color: var(--primary-purple); }
        .stat-green { background: #eafaf1; color: #2e7d32; }
        .stat-amber { background: #fef8e7; color: #d97706; }
        .stat-blue { background: #e8f3fc; color: #1976d2; }

        /* FILTER & CONTENT CARDS */
        .creative-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.05);
            margin-bottom: 25px;
        }

        /* BUTTONS */
        .btn-creative-primary {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            padding: 9px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(121, 75, 196, 0.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-creative-primary:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(121, 75, 196, 0.35);
        }

        .btn-excel {
            background: #107c41;
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(16, 124, 65, 0.2);
        }

        .btn-excel:hover {
            background: #0b5c30;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-print {
            background: #475569;
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.25s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print:hover {
            background: #334155;
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* TABLE STYLING */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background-color: #22133c;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.83rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 13px 14px;
            border: none;
            white-space: nowrap;
        }

        .table-custom thead th:first-child { border-top-left-radius: 14px; }
        .table-custom thead th:last-child { border-top-right-radius: 14px; }

        .table-custom tbody td {
            padding: 12px 14px;
            font-size: 0.86rem;
            border-bottom: 1px solid #f0ecfa;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-custom tbody tr:nth-of-type(even) {
            background-color: #fbf9fe;
        }

        .table-custom tbody tr:hover {
            background-color: #f3ecfb;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.78rem;
            display: inline-block;
        }

        .badge-selesai { background: #eafaf1; color: #2e7d32; }
        .badge-menunggu { background: #fef8e7; color: #d97706; }
        .badge-ditolak { background: #fee2e2; color: #dc2626; }
        .badge-aktif { background: #ede9fe; color: #6d28d9; }

        .nis-tag {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            background: #f1ecfa;
            color: #5931a0;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.88rem;
            letter-spacing: 0.5px;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; padding: 18px; }
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

    <!-- === SIDEBAR ADMIN (DENGAN BUKU INDUK DI ATAS ANGKET) === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
            <div class="mt-2 text-white-50 small fw-semibold">PANEL ADMINISTRATOR</div>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">
                    <i class="fas fa-book"></i> <span>Master Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/mentor'); ?>" class="nav-link">
                    <i class="fas fa-chalkboard-user"></i> <span>Instruktur</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">
                    <i class="fas fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>

            <!-- MENU BUKU INDUK (TEPAT DI ATAS ANGKET) -->
            <li class="nav-item">
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link active">
                    <i class="fas fa-book-open"></i> <span>Buku Induk</span>
                </a>
            </li>

            <!-- MENU ANGKET -->
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link">
                    <i class="fas fa-poll"></i> <span>Angket</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link">
                    <i class="fas fa-award"></i> <span>Sertifikat</span>
                </a>
            </li>

            <!-- SUBMENU LAPORAN -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanAdmin" role="button" aria-expanded="false">
                    <i class="fas fa-chart-simple"></i> <span>Laporan</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem;"></i>
                </a>
                <div class="collapse" id="submenuLaporanAdmin">
                    <ul class="nav flex-column ms-2">
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-peserta'); ?>" class="nav-link"><i class="fas fa-chart-pie me-2"></i> <span>Laporan Peserta</span></a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user me-2"></i> <span>Laporan Instruktur</span></a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-angket'); ?>" class="nav-link"><i class="fas fa-star-half-stroke me-2"></i> <span>Laporan Angket Instruktur</span></a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-kehadiran'); ?>" class="nav-link"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/hak-akses'); ?>" class="nav-link">
                    <i class="fas fa-user-shield"></i> <span>Hak Akses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link">
                    <i class="fas fa-gear"></i> <span>Pengaturan</span>
                </a>
            </li>

            <li class="nav-item mt-4">
                <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                    <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- === KONTEN UTAMA === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: var(--dark-purple);">Buku Induk Peserta</h4>
                    <p class="text-muted small mb-0">Dokumentasi data induk, status sertifikasi, dan riwayat pendaftaran peserta Creativemu Academy</p>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-creative-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPeserta">
                    <i class="fas fa-plus"></i> Tambah Peserta
                </button>
                <?php 
                    $exportQuery = http_build_query($filters);
                ?>
                <a href="<?= base_url('admin/buku-induk/export-excel?' . $exportQuery); ?>" class="btn btn-excel" target="_blank">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="<?= base_url('admin/buku-induk/cetak?' . $exportQuery); ?>" class="btn btn-print" target="_blank">
                    <i class="fas fa-print"></i> Cetak / PDF
                </a>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon stat-purple">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Total Peserta</div>
                        <h3 class="fw-bold mb-0" style="color: var(--dark-purple);"><?= number_format($statistics['total_peserta'] ?? 0); ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Terdata di Buku Induk</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon stat-green">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Sertifikat Selesai</div>
                        <h3 class="fw-bold mb-0 text-success"><?= number_format($statistics['sertifikat_selesai'] ?? 0); ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Lulus / Diterbitkan</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon stat-amber">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Sertifikat Menunggu</div>
                        <h3 class="fw-bold mb-0 text-warning"><?= number_format($statistics['sertifikat_menunggu'] ?? 0); ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">KBM Berjalan / Pending</small>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon stat-blue">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold text-uppercase">Kelas Terdaftar</div>
                        <h3 class="fw-bold mb-0 text-primary"><?= number_format($statistics['total_kelas'] ?? 0); ?></h3>
                        <small class="text-muted" style="font-size: 0.75rem;">Variasi kelas pelatihan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER CARD -->
        <div class="creative-card mb-4">
            <form action="<?= base_url('admin/buku-induk'); ?>" method="get" id="filterForm">
                <div class="row g-3 align-items-end">
                    <!-- Filter Tahun -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small fw-bold text-muted">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['tahun'] === 'all') ? 'selected' : ''; ?>>Semua Tahun</option>
                            <?php foreach ($filterYears as $yr): ?>
                                <option value="<?= $yr; ?>" <?= ((string)$filters['tahun'] === (string)$yr) ? 'selected' : ''; ?>><?= $yr; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small fw-bold text-muted">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['bulan'] === 'all') ? 'selected' : ''; ?>>Semua Bulan</option>
                            <?php 
                                $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                                foreach ($namaBulan as $num => $nm): 
                            ?>
                                <option value="<?= $num; ?>" <?= ((string)$filters['bulan'] === (string)$num) ? 'selected' : ''; ?>><?= $nm; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kelas -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small fw-bold text-muted">Kelas</label>
                        <select name="id_kelas" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['id_kelas'] === 'all') ? 'selected' : ''; ?>>Semua Kelas</option>
                            <?php foreach ($filterClasses as $k): ?>
                                <option value="<?= $k['id_kelas']; ?>" <?= ((string)$filters['id_kelas'] === (string)$k['id_kelas']) ? 'selected' : ''; ?>><?= esc($k['nama_kelas']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Kategori Kelas -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small fw-bold text-muted">Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['kategori'] === 'all') ? 'selected' : ''; ?>>Semua Kategori</option>
                            <?php foreach ($filterCategories as $kat): ?>
                                <option value="<?= esc($kat); ?>" <?= ($filters['kategori'] === $kat) ? 'selected' : ''; ?>><?= esc($kat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Status Sertifikat -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small fw-bold text-muted">Status Sertifikat</label>
                        <select name="status_sertifikat" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['status_sertifikat'] === 'all') ? 'selected' : ''; ?>>Semua Status</option>
                            <option value="Selesai" <?= ($filters['status_sertifikat'] === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Menunggu" <?= ($filters['status_sertifikat'] === 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                        </select>
                    </div>

                    <!-- Filter Status Diterima -->
                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label small fw-bold text-muted">Status Validasi</label>
                        <select name="status_diterima" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['status_diterima'] === 'all') ? 'selected' : ''; ?>>Semua Validasi</option>
                            <option value="Disetujui" <?= (strtolower($filters['status_diterima']) === 'disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                            <option value="Menunggu" <?= (strtolower($filters['status_diterima']) === 'menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="Ditolak" <?= (strtolower($filters['status_diterima']) === 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>
                </div>

                <!-- Search Input & Buttons -->
                <div class="row g-3 mt-1 align-items-center">
                    <div class="col-lg-8 col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="keyword" class="form-control form-control-sm border-start-0" placeholder="Cari berdasarkan NIS, Nama Peserta, WhatsApp, Alamat, atau Kelas..." value="<?= esc($filters['keyword']); ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5 d-flex gap-2 justify-content-md-end">
                        <button type="submit" class="btn btn-creative-primary btn-sm px-3">
                            <i class="fas fa-filter"></i> Terapkan
                        </button>
                        <a href="<?= base_url('admin/buku-induk'); ?>" class="btn btn-light btn-sm border px-3">
                            <i class="fas fa-rotate-left"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- TABEL BUKU INDUK -->
        <div class="creative-card p-0 overflow-hidden">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom bg-light">
                <div class="fw-bold" style="color: var(--dark-purple);">
                    <i class="fas fa-table me-2 text-primary"></i> Rekap Data Buku Induk (Total: <?= number_format($totalData); ?> Peserta)
                </div>
                <div class="text-muted small">
                    Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 45px;">No</th>
                            <th>NIS</th>
                            <th>Nama Peserta</th>
                            <th>Tanggal Masuk</th>
                            <th>Kelas</th>
                            <th>Tanggal Selesai</th>
                            <th>Status Sertifikat</th>
                            <th>Diterima</th>
                            <th>Kategori Kelas</th>
                            <th>Pilihan Kelas</th>
                            <th>Metode</th>
                            <th>Jenis Kelas</th>
                            <th>Lokasi Pelatihan</th>
                            <th>Pendidikan</th>
                            <th>Status</th>
                            <th>No. WhatsApp</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat, Tgl Lahir</th>
                            <th>Alamat</th>
                            <th class="text-center" style="min-width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pesertaList)): ?>
                            <tr>
                                <td colspan="20" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 text-secondary d-block"></i>
                                    <strong>Tidak ada data peserta yang sesuai dengan filter pencarian.</strong>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                                $nomor = ($currentPage - 1) * $perPage + 1;
                                foreach ($pesertaList as $item): 
                            ?>
                                <tr>
                                    <td class="text-center fw-semibold text-muted"><?= $nomor++; ?></td>
                                    <td>
                                        <span class="nis-tag"><?= esc($item['nis']); ?></span>
                                    </td>
                                    <td class="fw-bold" style="color: var(--dark-purple);"><?= esc($item['nama_peserta']); ?></td>
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-1 small"></i>
                                        <?= !empty($item['tanggal_masuk']) && $item['tanggal_masuk'] !== '-' ? date('d/m/Y', strtotime($item['tanggal_masuk'])) : '-'; ?>
                                    </td>
                                    <td class="fw-semibold text-primary"><?= esc($item['nama_kelas'] ?: '-'); ?></td>
                                    <td>
                                        <?= !empty($item['tanggal_selesai_kelas']) && $item['tanggal_selesai_kelas'] !== '-' ? date('d/m/Y', strtotime($item['tanggal_selesai_kelas'])) : '<span class="text-muted">-</span>'; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['status_sertifikat'] === 'Selesai'): ?>
                                            <span class="badge-status badge-selesai"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                                        <?php else: ?>
                                            <span class="badge-status badge-menunggu"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $diterima = strtolower($item['diterima']);
                                            if (in_array($diterima, ['disetujui', 'valid', 'approved'])):
                                        ?>
                                            <span class="badge-status badge-selesai">Disetujui</span>
                                        <?php elseif (in_array($diterima, ['ditolak', 'rejected'])): ?>
                                            <span class="badge-status badge-ditolak">Ditolak</span>
                                        <?php else: ?>
                                            <span class="badge-status badge-menunggu">Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= esc($item['kategori_kelas']); ?></span></td>
                                    <td><?= esc($item['pilihan_kelas']); ?></td>
                                    <td>
                                        <span class="badge <?= (strtolower($item['metode']) === 'online') ? 'bg-info text-dark' : 'bg-primary'; ?> bg-opacity-10 text-primary fw-bold">
                                            <?= ucfirst(esc($item['metode'])); ?>
                                        </span>
                                    </td>
                                    <td><?= ucfirst(esc($item['jenis_kelas'])); ?></td>
                                    <td><?= esc($item['lokasi_pelatihan']); ?></td>
                                    <td><?= esc($item['pendidikan_terakhir']); ?></td>
                                    <td>
                                        <span class="badge-status badge-aktif"><?= ucfirst(esc($item['status_peserta'])); ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['no_whatsapp']) && $item['no_whatsapp'] !== '-'): ?>
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $item['no_whatsapp']); ?>" target="_blank" class="text-success text-decoration-none fw-semibold">
                                                <i class="fab fa-whatsapp me-1"></i> <?= esc($item['no_whatsapp']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($item['jenis_kelamin']); ?></td>
                                    <td><?= esc($item['tempat_tanggal_lahir']); ?></td>
                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;"><?= esc($item['alamat']); ?></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-light border text-primary" title="Lihat Detail" onclick="bukaDetail(<?= $item['id_pendaftaran']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border text-warning" title="Edit Peserta" onclick="bukaEdit(<?= $item['id_pendaftaran']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>
                <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top bg-light">
                    <div class="small text-muted">
                        Menampilkan <?= ($currentPage - 1) * $perPage + 1; ?> - <?= min($currentPage * $perPage, $totalData); ?> dari <?= $totalData; ?> data
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <?php 
                                $queryParams = $filters;
                                $buildPageUrl = function($p) use ($queryParams) {
                                    $queryParams['page'] = $p;
                                    return base_url('admin/buku-induk?' . http_build_query($queryParams));
                                };
                            ?>
                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $buildPageUrl($currentPage - 1); ?>">&laquo;</a>
                            </li>
                            <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): ?>
                                <li class="page-item <?= ($p === $currentPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?= $buildPageUrl($p); ?>"><?= $p; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $buildPageUrl($currentPage + 1); ?>">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- === MODAL DETAIL PESERTA BUKU INDUK === -->
    <div class="modal fade" id="modalDetailPeserta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header py-3 px-4 text-white" style="background: var(--sidebar-active-gradient);">
                    <h5 class="modal-title fw-bold"><i class="fas fa-id-card me-2"></i> Detail Data Buku Induk Peserta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-purple" role="status"></div>
                        <p class="text-muted mt-2">Memuat rincian data peserta...</p>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- === MODAL TAMBAH PESERTA BUKU INDUK === -->
    <div class="modal fade" id="modalTambahPeserta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header py-3 px-4 text-white" style="background: var(--sidebar-active-gradient);">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Tambah Peserta ke Buku Induk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambahPeserta" onsubmit="simpanPesertaBaru(event)">
                    <?= csrf_field(); ?>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">NIS (Nomor Induk Siswa)</label>
                                <input type="text" name="nis" class="form-control form-control-sm" placeholder="Otomatis (YYYYMMXXX) jika dikosongkan">
                                <small class="text-muted" style="font-size: 0.72rem;">Contoh format: 202609013</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Pilih Kelas Pelatihan <span class="text-danger">*</span></label>
                                <select name="id_kelas" class="form-select form-select-sm" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php foreach ($filterClasses as $k): ?>
                                        <option value="<?= $k['id_kelas']; ?>"><?= esc($k['nama_kelas']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Lengkap Peserta <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control form-control-sm" required placeholder="Masukkan nama lengkap">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">No. WhatsApp / HP</label>
                                <input type="text" name="no_hp" class="form-control form-control-sm" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Alamat Email</label>
                                <input type="email" name="email" class="form-control form-control-sm" placeholder="email@contoh.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select form-select-sm">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tempat, Tanggal Lahir</label>
                                <input type="text" name="ttl" class="form-control form-control-sm" placeholder="Kota, DD-MM-YYYY">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Pendidikan Terakhir</label>
                                <select name="pendidikan_terakhir" class="form-select form-select-sm">
                                    <option value="SMA/SMK">SMA/SMK</option>
                                    <option value="D3">D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="SMP">SMP</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Metode Pembelajaran</label>
                                <select name="metode_pembelajaran" class="form-select form-select-sm">
                                    <option value="Offline">Offline</option>
                                    <option value="Online">Online</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Jenis Kelas</label>
                                <select name="jenis_kelas" class="form-select form-select-sm">
                                    <option value="Reguler">Reguler</option>
                                    <option value="Privat">Privat</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tanggal Mulai Kelas</label>
                                <input type="date" name="tanggal_mulai_kelas" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Lokasi Pelatihan</label>
                                <input type="text" name="lokasi_pelatihan" class="form-control form-control-sm" value="CreativeMU Training Center">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" rows="2" class="form-control form-control-sm" placeholder="Alamat domisili peserta..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-creative-primary btn-sm px-4" id="btnSimpanPeserta">
                            <i class="fas fa-save me-1"></i> Simpan ke Buku Induk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- === MODAL EDIT PESERTA BUKU INDUK === -->
    <div class="modal fade" id="modalEditPeserta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="modal-header py-3 px-4 text-white" style="background: var(--sidebar-active-gradient);">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i> Edit Data Buku Induk Peserta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditPeserta" onsubmit="simpanPerubahanPeserta(event)">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id_pendaftaran" id="edit_id_pendaftaran">
                    <div class="modal-body p-4" id="modalEditBody">
                        <div class="text-center py-4">
                            <div class="spinner-border text-purple" role="status"></div>
                            <p class="text-muted mt-2">Memuat data edit...</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-creative-primary btn-sm px-4" id="btnUpdatePeserta">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // BUKA MODAL DETAIL PESERTA
        function bukaDetail(id) {
            const modalEl = document.getElementById('modalDetailPeserta');
            const modal = new bootstrap.Modal(modalEl);
            const content = document.getElementById('modalDetailContent');
            content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2">Memuat rincian...</p></div>';
            modal.show();

            fetch('<?= base_url('admin/buku-induk/detail'); ?>/' + id)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        content.innerHTML = `
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Nomor Induk Siswa (NIS)</small>
                                        <span class="nis-tag fs-6 mt-1 d-inline-block">${d.nis || '-'}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Status Sertifikat</small>
                                        <span class="badge ${d.status_sertifikat === 'Selesai' ? 'bg-success' : 'bg-warning'} px-3 py-2 mt-1">${d.status_sertifikat}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><th class="text-muted ps-0" style="width: 140px;">Nama Peserta</th><td class="fw-bold">${d.nama_peserta || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Jenis Kelamin</th><td>${d.jenis_kelamin || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">No. WhatsApp</th><td>${d.no_whatsapp || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Tempat, Tgl Lahir</th><td>${d.tempat_tanggal_lahir || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Pendidikan</th><td>${d.pendidikan_terakhir || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Alamat</th><td>${d.alamat || '-'}</td></tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><th class="text-muted ps-0" style="width: 140px;">Kelas</th><td class="fw-bold text-primary">${d.nama_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Kategori Kelas</th><td>${d.kategori_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Pilihan Kelas</th><td>${d.pilihan_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Metode</th><td>${d.metode || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Jenis Kelas</th><td>${d.jenis_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Lokasi Pelatihan</th><td>${d.lokasi_pelatihan || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Tanggal Masuk</th><td>${d.tanggal_masuk || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Tanggal Selesai</th><td>${d.tanggal_selesai_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Status Pendaftaran</th><td>${d.diterima || '-'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = '<div class="alert alert-danger mb-0">' + (res.message || 'Gagal memuat data.') + '</div>';
                    }
                })
                .catch(err => {
                    content.innerHTML = '<div class="alert alert-danger mb-0">Terjadi kesalahan koneksi server.</div>';
                });
        }

        // BUKA MODAL EDIT PESERTA
        function bukaEdit(id) {
            const modalEl = document.getElementById('modalEditPeserta');
            const modal = new bootstrap.Modal(modalEl);
            const body = document.getElementById('modalEditBody');
            document.getElementById('edit_id_pendaftaran').value = id;
            body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2">Memuat data edit...</p></div>';
            modal.show();

            fetch('<?= base_url('admin/buku-induk/detail'); ?>/' + id)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        body.innerHTML = `
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">NIS (Nomor Induk Siswa)</label>
                                    <input type="text" name="nis" class="form-control form-control-sm" value="${d.nis || ''}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nama Lengkap Peserta</label>
                                    <input type="text" name="nama" class="form-control form-control-sm" value="${d.nama_peserta || ''}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">No. WhatsApp / HP</label>
                                    <input type="text" name="no_hp" class="form-control form-control-sm" value="${d.no_whatsapp !== '-' ? d.no_whatsapp : ''}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select form-select-sm">
                                        <option value="Laki-laki" ${d.jenis_kelamin === 'Laki-laki' ? 'selected' : ''}>Laki-laki</option>
                                        <option value="Perempuan" ${d.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Tempat, Tanggal Lahir</label>
                                    <input type="text" name="ttl" class="form-control form-control-sm" value="${d.tempat_tanggal_lahir !== '-' ? d.tempat_tanggal_lahir : ''}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan_terakhir" class="form-control form-control-sm" value="${d.pendidikan_terakhir !== '-' ? d.pendidikan_terakhir : ''}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Metode Pembelajaran</label>
                                    <select name="metode_pembelajaran" class="form-select form-select-sm">
                                        <option value="Offline" ${d.metode === 'Offline' ? 'selected' : ''}>Offline</option>
                                        <option value="Online" ${d.metode === 'Online' ? 'selected' : ''}>Online</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Jenis Kelas</label>
                                    <select name="jenis_kelas" class="form-select form-select-sm">
                                        <option value="Reguler" ${d.jenis_kelas === 'Reguler' ? 'selected' : ''}>Reguler</option>
                                        <option value="Privat" ${d.jenis_kelas === 'Privat' ? 'selected' : ''}>Privat</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Status Pendaftaran (Diterima)</label>
                                    <select name="status_pendaftaran" class="form-select form-select-sm">
                                        <option value="Disetujui" ${d.diterima === 'Disetujui' ? 'selected' : ''}>Disetujui</option>
                                        <option value="Menunggu" ${d.diterima === 'Menunggu' ? 'selected' : ''}>Menunggu</option>
                                        <option value="Ditolak" ${d.diterima === 'Ditolak' ? 'selected' : ''}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Status Keaktifan</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="Aktif" ${d.status_peserta === 'Aktif' ? 'selected' : ''}>Aktif</option>
                                        <option value="Selesai" ${d.status_peserta === 'Selesai' ? 'selected' : ''}>Selesai</option>
                                        <option value="Non-aktif" ${d.status_peserta === 'Non-aktif' ? 'selected' : ''}>Non-aktif</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Lokasi Pelatihan</label>
                                    <input type="text" name="lokasi_pelatihan" class="form-control form-control-sm" value="${d.lokasi_pelatihan !== '-' ? d.lokasi_pelatihan : ''}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">Alamat</label>
                                    <textarea name="alamat" rows="2" class="form-control form-control-sm">${d.alamat !== '-' ? d.alamat : ''}</textarea>
                                </div>
                            </div>
                        `;
                    } else {
                        body.innerHTML = '<div class="alert alert-danger mb-0">' + (res.message || 'Gagal memuat data.') + '</div>';
                    }
                });
        }

        // SIMPAN PERUBAHAN PESERTA
        function simpanPerubahanPeserta(e) {
            e.preventDefault();
            const form = document.getElementById('formEditPeserta');
            const id = document.getElementById('edit_id_pendaftaran').value;
            const btn = document.getElementById('btnUpdatePeserta');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

            const formData = new FormData(form);

            fetch('<?= base_url('admin/buku-induk/update'); ?>/' + id, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Perubahan';
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message || 'Gagal menyimpan perubahan.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan Perubahan';
                alert('Terjadi kesalahan jaringan.');
            });
        }

        // SIMPAN PESERTA BARU
        function simpanPesertaBaru(e) {
            e.preventDefault();
            const form = document.getElementById('formTambahPeserta');
            const btn = document.getElementById('btnSimpanPeserta');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

            const formData = new FormData(form);

            fetch('<?= base_url('admin/buku-induk/store'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan ke Buku Induk';
                if (res.status === 'success') {
                    alert(res.message);
                    location.reload();
                } else {
                    alert(res.message || 'Gagal menambahkan peserta.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i> Simpan ke Buku Induk';
                alert('Terjadi kesalahan jaringan.');
            });
        }
    </script>
</body>
</html>
