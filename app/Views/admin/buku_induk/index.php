<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Diubah: Ditambahkan user-scalable=no agar tampilan tidak berubah drastis saat zoom -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            color: #2b263b;
            overflow-x: hidden;
            margin: 0;
            font-size: 0.82rem; /* Diperkecil dari default */
        }

        /* SIDEBAR STYLING - Ukuran Diperkecil */
        #sidebar {
            width: 230px; /* Dikecilkan dari 275px */
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
            padding: 15px 10px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        #sidebar .sidebar-header img {
            width: 180px; /* Dikecilkan */
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
        }

        #sidebar .nav {
            padding: 12px 10px;
        }

        #sidebar .nav-item {
            margin-bottom: 3px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 8px 12px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.25s ease;
            font-size: 0.8rem; /* Font diperkecil */
            text-decoration: none;
        }

        #sidebar .nav-link i {
            margin-right: 10px;
            font-size: 0.95rem;
            width: 20px;
            text-align: center;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
            transform: translateX(3px);
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        .submenu-item .nav-link {
            padding-left: 24px !important;
            font-size: 0.78rem;
        }

        /* MAIN CONTENT AREA - Margin disesuaikan */
        #main-content {
            margin-left: 230px; /* Sesuaikan dengan sidebar baru */
            padding: 20px 25px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* TOP NAVBAR */
        .top-navbar {
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .top-navbar h4 {
            font-size: 1.1rem;
        }

        /* FILTER & CONTENT CARDS */
        .creative-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.05);
            margin-bottom: 20px;
        }

        /* BUTTONS - Ukuran Kompak */
        .btn-creative-primary, .btn-excel, .btn-print {
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-creative-primary {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            box-shadow: 0 3px 10px rgba(121, 75, 196, 0.25);
        }

        .btn-excel {
            background: #107c41;
            color: #ffffff;
            border: none;
        }

        .btn-print {
            background: #475569;
            color: #ffffff;
            border: none;
        }

        /* TABLE STYLING - Lebih Ringkas */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background-color: #22133c;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 8px 10px;
            border: none;
            white-space: nowrap;
        }

        .table-custom thead th:first-child { border-top-left-radius: 10px; }
        .table-custom thead th:last-child { border-top-right-radius: 10px; }

        .table-custom tbody td {
            padding: 8px 10px;
            font-size: 0.78rem;
            border-bottom: 1px solid #f0ecfa;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-custom tbody td.kolom-alamat,
        .table-custom tbody td.kolom-lokasi {
            white-space: normal !important;
            max-width: 180px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .table-custom tbody tr:nth-of-type(even) { background-color: #fbf9fe; }
        .table-custom tbody tr:hover { background-color: #f3ecfb; }

        .badge-status {
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.72rem;
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
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.78rem;
        }

        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; padding: 15px; }
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

    <!-- SIDEBAR -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
            <div class="mt-2 text-white-50 small fw-semibold" style="font-size: 0.7rem;">PANEL ADMINISTRATOR</div>
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
            <li class="nav-item">
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link active">
                    <i class="fas fa-book-open"></i> <span>Buku Induk</span>
                </a>
            </li>
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
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanAdmin" role="button" aria-expanded="false">
                    <i class="fas fa-chart-simple"></i> <span>Laporan</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.65rem;"></i>
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
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link">
                    <i class="fas fa-gear"></i> <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item mt-3">
                <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                    <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- KONTEN UTAMA -->
    <div id="main-content">
        
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: var(--dark-purple);">Buku Induk Peserta</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Dokumentasi data induk, status sertifikasi, dan riwayat pendaftaran peserta Creativemu Academy</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <?php $exportQuery = http_build_query($filters); ?>
                <a href="<?= base_url('admin/buku-induk/export-excel?' . $exportQuery); ?>" class="btn btn-excel" target="_blank">
                    <i class="fas fa-file-excel"></i> Download Excel
                </a>
                <a href="<?= base_url('admin/buku-induk/cetak?' . $exportQuery); ?>" class="btn btn-print" target="_blank">
                    <i class="fas fa-print"></i> Cetak / PDF
                </a>
            </div>
        </div>

        <!-- FILTER CARD -->
        <div class="creative-card mb-3">
            <form action="<?= base_url('admin/buku-induk'); ?>" method="get" id="filterForm">
                <div class="row g-2 align-items-end">
                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['tahun'] === 'all') ? 'selected' : ''; ?>>Semua Tahun</option>
                            <?php foreach ($filterYears as $yr): ?>
                                <option value="<?= $yr; ?>" <?= ((string)$filters['tahun'] === (string)$yr) ? 'selected' : ''; ?>><?= $yr; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Bulan</label>
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

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Kelas</label>
                        <select name="id_kelas" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['id_kelas'] === 'all') ? 'selected' : ''; ?>>Semua Kelas</option>
                            <?php foreach ($filterClasses as $k): ?>
                                <option value="<?= $k['id_kelas']; ?>" <?= ((string)$filters['id_kelas'] === (string)$k['id_kelas']) ? 'selected' : ''; ?>><?= esc($k['nama_kelas']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['kategori'] === 'all') ? 'selected' : ''; ?>>Semua Kategori</option>
                            <?php foreach ($filterCategories as $kat): ?>
                                <option value="<?= esc($kat); ?>" <?= ($filters['kategori'] === $kat) ? 'selected' : ''; ?>><?= esc($kat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Status Sertifikat</label>
                        <select name="status_sertifikat" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['status_sertifikat'] === 'all') ? 'selected' : ''; ?>>Semua Status</option>
                            <option value="Selesai" <?= ($filters['status_sertifikat'] === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Menunggu" <?= ($filters['status_sertifikat'] === 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Status Validasi</label>
                        <select name="status_diterima" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['status_diterima'] === 'all') ? 'selected' : ''; ?>>Semua Validasi</option>
                            <option value="Disetujui" <?= (strtolower($filters['status_diterima']) === 'disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                            <option value="Menunggu" <?= (strtolower($filters['status_diterima']) === 'menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="Ditolak" <?= (strtolower($filters['status_diterima']) === 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 mt-1 align-items-center">
                    <div class="col-lg-8 col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 py-1"><i class="fas fa-search text-muted small"></i></span>
                            <input type="text" name="keyword" class="form-control form-control-sm border-start-0" placeholder="Cari berdasarkan NIS, Nama Peserta, WhatsApp, Alamat..." value="<?= esc($filters['keyword']); ?>">
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
            <div class="p-2 px-3 d-flex justify-content-between align-items-center border-bottom bg-light">
                <div class="fw-bold small" style="color: var(--dark-purple);">
                    <i class="fas fa-table me-2 text-primary"></i> Rekap Data Buku Induk (Total: <?= number_format($totalData); ?> Peserta)
                </div>
                <div class="text-muted small" style="font-size: 0.75rem;">
                    Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 35px;">No</th>
                            <th>NIS</th>
                            <th>Nama Peserta</th>
                            <th>Tanggal Masuk</th>
                            <th>Kelas</th>
                            <th>Lokasi Pelatihan</th>
                            <th>Tanggal Selesai</th>
                            <th>Status Sertifikat</th>
                            <th>Diterima</th>
                            <th>Kategori Kelas</th>
                            <th>Pilihan Kelas</th>
                            <th>Metode</th>
                            <th>Jenis Kelas</th>
                            <th>Pendidikan</th>
                            <th>Status</th>
                            <th>No. WhatsApp</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat, Tgl Lahir</th>
                            <th>Alamat</th>
                            <th class="text-center" style="min-width: 90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pesertaList)): ?>
                            <tr>
                                <td colspan="20" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 text-secondary d-block"></i>
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
                                    
                                    <!-- LOGIKA TANGGAL MASUK FIX -->
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-1 small"></i>
                                        <?php 
                                            // Ambil tanggal masuk dinamis (gunakan created_at jika tanggal_masuk tidak ada)
                                            $tglMasuk = !empty($item['tanggal_masuk']) && $item['tanggal_masuk'] !== '-' ? $item['tanggal_masuk'] : ($item['created_at'] ?? '');
                                            if (!empty($tglMasuk) && $tglMasuk !== '0000-00-00' && $tglMasuk !== '0000-00-00 00:00:00') {
                                                echo date('d/m/Y', strtotime($tglMasuk));
                                            } else {
                                                echo '-';
                                            }
                                        ?>
                                    </td>

                                    <td class="fw-semibold text-primary"><?= esc($item['nama_kelas'] ?: '-'); ?></td>
                                    <td class="kolom-lokasi"><?= esc($item['lokasi_pelatihan']); ?></td>
                                    
                                    <!-- LOGIKA TANGGAL SELESAI FIX (Kosong Jika Belum Selesai) -->
                                    <td>
                                        <?php 
                                            $isSelesai = (strtolower($item['status_peserta'] ?? '') === 'selesai' || strtolower($item['status_sertifikat'] ?? '') === 'selesai');
                                            $tglSelesai = $item['tanggal_selesai_kelas'] ?? '';

                                            if ($isSelesai && !empty($tglSelesai) && $tglSelesai !== '-' && $tglSelesai !== '0000-00-00') {
                                                echo date('d/m/Y', strtotime($tglSelesai));
                                            } else {
                                                echo '<span class="text-muted fw-bold">-</span>';
                                            }
                                        ?>
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
                                    <td><span class="badge bg-light text-dark border" style="font-size: 0.7rem;"><?= esc($item['kategori_kelas']); ?></span></td>
                                    <td><?= esc($item['pilihan_kelas']); ?></td>
                                    <td>
                                        <span class="badge <?= (strtolower($item['metode']) === 'online') ? 'bg-info text-dark' : 'bg-primary'; ?> bg-opacity-10 text-primary fw-bold" style="font-size: 0.7rem;">
                                            <?= ucfirst(esc($item['metode'])); ?>
                                        </span>
                                    </td>
                                    <td><?= ucfirst(esc($item['jenis_kelas'])); ?></td>
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
                                    <td class="kolom-alamat"><?= esc($item['alamat']); ?></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-light border text-primary py-0 px-2" title="Lihat Detail" onclick="bukaDetail(<?= $item['id_pendaftaran']; ?>)">
                                                <i class="fas fa-eye small"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border text-warning py-0 px-2" title="Edit Peserta" onclick="bukaEdit(<?= $item['id_pendaftaran']; ?>)">
                                                <i class="fas fa-edit small"></i>
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
                <div class="p-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top bg-light">
                    <div class="small text-muted" style="font-size: 0.75rem;">
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

    <!-- MODAL DETAIL PESERTA -->
    <div class="modal fade" id="modalDetailPeserta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header py-2 px-3 text-white" style="background: var(--sidebar-active-gradient);">
                    <h6 class="modal-title fw-bold mb-0"><i class="fas fa-id-card me-2"></i> Detail Data Buku Induk Peserta</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3" id="modalDetailContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-purple" role="status"></div>
                        <p class="text-muted mt-2 small">Memuat rincian data peserta...</p>
                    </div>
                </div>
                <div class="modal-footer bg-light px-3 py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT PESERTA -->
    <div class="modal fade" id="modalEditPeserta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header py-2 px-3 text-white" style="background: var(--sidebar-active-gradient);">
                    <h6 class="modal-title fw-bold mb-0"><i class="fas fa-user-edit me-2"></i> Edit Data Buku Induk Peserta</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditPeserta" onsubmit="simpanPerubahanPeserta(event)">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id_pendaftaran" id="edit_id_pendaftaran">
                    <div class="modal-body p-3" id="modalEditBody">
                        <div class="text-center py-4">
                            <div class="spinner-border text-purple" role="status"></div>
                            <p class="text-muted mt-2 small">Memuat data edit...</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-3 py-2">
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-creative-primary btn-sm px-3" id="btnUpdatePeserta">
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

        function bukaDetail(id) {
            const modalEl = document.getElementById('modalDetailPeserta');
            const modal = new bootstrap.Modal(modalEl);
            const content = document.getElementById('modalDetailContent');
            content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2 small">Memuat rincian...</p></div>';
            modal.show();

            fetch('<?= base_url('admin/buku-induk/detail'); ?>/' + id)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        const tglSelesaiVal = (d.status_peserta === 'Selesai' || d.status_sertifikat === 'Selesai') ? (d.tanggal_selesai_kelas || '-') : '-';
                        content.innerHTML = `
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded-3">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.68rem;">Nomor Induk Siswa (NIS)</small>
                                        <span class="nis-tag mt-1 d-inline-block">${d.nis || '-'}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 bg-light rounded-3">
                                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.68rem;">Status Sertifikat</small>
                                        <span class="badge ${d.status_sertifikat === 'Selesai' ? 'bg-success' : 'bg-warning'} px-2 py-1 mt-1" style="font-size: 0.72rem;">${d.status_sertifikat}</span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0 small">
                                        <tr><th class="text-muted ps-0" style="width: 120px;">Nama Peserta</th><td class="fw-bold">${d.nama_peserta || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Jenis Kelamin</th><td>${d.jenis_kelamin || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">No. WhatsApp</th><td>${d.no_whatsapp || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Tempat, Tgl Lahir</th><td>${d.tempat_tanggal_lahir || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Pendidikan</th><td>${d.pendidikan_terakhir || '-'}</td></tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless mb-0 small">
                                        <tr><th class="text-muted ps-0" style="width: 120px;">Kelas</th><td class="fw-bold text-primary">${d.nama_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Lokasi Pelatihan</th><td>${d.lokasi_pelatihan || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Kategori Kelas</th><td>${d.kategori_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Pilihan Kelas</th><td>${d.pilihan_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Metode</th><td>${d.metode || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Jenis Kelas</th><td>${d.jenis_kelas || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Tanggal Masuk</th><td>${d.tanggal_masuk || d.created_at || '-'}</td></tr>
                                        <tr><th class="text-muted ps-0">Tanggal Selesai</th><td>${tglSelesaiVal}</td></tr>
                                        <tr><th class="text-muted ps-0">Status Pendaftaran</th><td>${d.diterima || '-'}</td></tr>
                                    </table>
                                </div>

                                <div class="col-12 mt-1">
                                    <div class="p-2 bg-light rounded-3">
                                        <small class="text-muted d-block text-uppercase fw-semibold mb-1" style="font-size: 0.68rem;">Alamat Lengkap</small>
                                        <div class="fw-normal text-dark small" style="white-space: pre-line; word-break: break-word;">${d.alamat || '-'}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = '<div class="alert alert-danger mb-0 small">' + (res.message || 'Gagal memuat data.') + '</div>';
                    }
                })
                .catch(err => {
                    content.innerHTML = '<div class="alert alert-danger mb-0 small">Terjadi kesalahan koneksi server.</div>';
                });
        }

        function bukaEdit(id) {
            const modalEl = document.getElementById('modalEditPeserta');
            const modal = new bootstrap.Modal(modalEl);
            const body = document.getElementById('modalEditBody');
            document.getElementById('edit_id_pendaftaran').value = id;
            body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="text-muted mt-2 small">Memuat data edit...</p></div>';
            modal.show();

            fetch('<?= base_url('admin/buku-induk/detail'); ?>/' + id)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const d = res.data;
                        body.innerHTML = `
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">NIS</label>
                                    <input type="text" name="nis" class="form-control form-control-sm" value="${d.nis || ''}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Nama Lengkap Peserta</label>
                                    <input type="text" name="nama" class="form-control form-control-sm" value="${d.nama_peserta || ''}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">No. WhatsApp / HP</label>
                                    <input type="text" name="no_hp" class="form-control form-control-sm" value="${d.no_whatsapp !== '-' ? d.no_whatsapp : ''}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select form-select-sm">
                                        <option value="Laki-laki" ${d.jenis_kelamin === 'Laki-laki' ? 'selected' : ''}>Laki-laki</option>
                                        <option value="Perempuan" ${d.jenis_kelamin === 'Perempuan' ? 'selected' : ''}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Tempat, Tanggal Lahir</label>
                                    <input type="text" name="ttl" class="form-control form-control-sm" value="${d.tempat_tanggal_lahir !== '-' ? d.tempat_tanggal_lahir : ''}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan_terakhir" class="form-control form-control-sm" value="${d.pendidikan_terakhir !== '-' ? d.pendidikan_terakhir : ''}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Metode Pembelajaran</label>
                                    <select name="metode_pembelajaran" class="form-select form-select-sm">
                                        <option value="Offline" ${d.metode === 'Offline' ? 'selected' : ''}>Offline</option>
                                        <option value="Online" ${d.metode === 'Online' ? 'selected' : ''}>Online</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Jenis Kelas</label>
                                    <select name="jenis_kelas" class="form-select form-select-sm">
                                        <option value="Reguler" ${d.jenis_kelas === 'Reguler' ? 'selected' : ''}>Reguler</option>
                                        <option value="Privat" ${d.jenis_kelas === 'Privat' ? 'selected' : ''}>Privat</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Status Pendaftaran (Diterima)</label>
                                    <select name="status_pendaftaran" class="form-select form-select-sm">
                                        <option value="Disetujui" ${d.diterima === 'Disetujui' ? 'selected' : ''}>Disetujui</option>
                                        <option value="Menunggu" ${d.diterima === 'Menunggu' ? 'selected' : ''}>Menunggu</option>
                                        <option value="Ditolak" ${d.diterima === 'Ditolak' ? 'selected' : ''}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold mb-1">Status Keaktifan</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="Aktif" ${d.status_peserta === 'Aktif' ? 'selected' : ''}>Aktif</option>
                                        <option value="Selesai" ${d.status_peserta === 'Selesai' ? 'selected' : ''}>Selesai</option>
                                        <option value="Non-aktif" ${d.status_peserta === 'Non-aktif' ? 'selected' : ''}>Non-aktif</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold mb-1">Lokasi Pelatihan</label>
                                    <input type="text" name="lokasi_pelatihan" class="form-control form-control-sm" value="${d.lokasi_pelatihan !== '-' ? d.lokasi_pelatihan : ''}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold mb-1">Alamat</label>
                                    <textarea name="alamat" rows="2" class="form-control form-control-sm">${d.alamat !== '-' ? d.alamat : ''}</textarea>
                                </div>
                            </div>
                        `;
                    } else {
                        body.innerHTML = '<div class="alert alert-danger mb-0 small">' + (res.message || 'Gagal memuat data.') + '</div>';
                    }
                });
        }

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
    </script>
</body>
</html>