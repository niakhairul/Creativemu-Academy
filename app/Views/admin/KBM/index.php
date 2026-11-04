<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title); ?> - Creativemu Academy</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #794bc4;
            --primary-dark: #5931a0;
            --primary-light: #f4f0fc;

            --dark: #1e0f33;
            --text: #2f2442;
            --muted: #817796;

            --border: #ece7f5;
            --background: #f8f6fc;

            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
            --remidi: #7c3aed;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--text);
            font-size: 14px;
        }

        body {
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1edf8;
        }

        ::-webkit-scrollbar-thumb {
            background: #d5c8ed;
            border-radius: 20px;
        }

        #main-content {
            margin-left: 240px;
            padding: 24px;
            min-height: 100vh;
        }

        /* TOP NAVBAR */
        .top-navbar {
            background: #ffffff;
            border: 1px solid rgba(121, 75, 196, 0.08);
            border-radius: 18px;
            padding: 18px 24px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(64, 36, 105, 0.03);
        }

        .dash-header h3 {
            margin: 0 0 4px 0;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--dark);
        }

        .dash-header p {
            margin: 0;
            color: var(--muted);
            font-size: 0.78rem;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--primary-light);
            padding: 6px 14px;
            border-radius: 50px;
            border: 1px solid #eadffb;
        }

        .admin-profile img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--primary);
            padding: 2px;
            background: #fff;
        }

        .admin-info h6 {
            margin: 0;
            color: var(--dark);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .admin-info small {
            color: var(--muted);
            font-size: 0.7rem;
        }

        /* STATS CARDS - CLEAN WHITE */
        .kbm-stat-card {
            background: #ffffff;
            border: 1px solid rgba(121, 75, 196, 0.08);
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: 0 4px 20px rgba(64, 36, 105, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .kbm-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(64, 36, 105, 0.06);
        }

        .kbm-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .icon-purple-soft {
            background: #f4f0fc;
            color: var(--primary);
        }

        .icon-blue-soft {
            background: #eff6ff;
            color: #2563eb;
        }

        .icon-green-soft {
            background: #ecfdf5;
            color: #059669;
        }

        .kbm-stat-number {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.1;
        }

        .kbm-stat-label {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 600;
            display: block;
            margin-top: 3px;
        }

        /* MAIN CONTENT CARD - CLEAN WHITE */
        .kbm-main-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(121, 75, 196, 0.08);
            box-shadow: 0 4px 24px rgba(64, 36, 105, 0.03);
            overflow: hidden;
        }

        .kbm-card-header {
            padding: 20px 24px;
            background: #ffffff;
            border-bottom: 1px solid #f0edf7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }

        /* TABLE THEAD - CLEAN WHITE */
        .table-kbm-head tr th {
            background: #ffffff !important;
            color: var(--dark) !important;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px 22px;
            border-bottom: 2px solid #f0edf7;
            border-top: none;
        }

        .table-kbm-body tr td {
            padding: 16px 22px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f2fa;
            font-size: 0.82rem;
            background: #ffffff;
        }

        .table-kbm-body tr:hover td {
            background-color: #faf8fd;
        }

        .instructor-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f4f0fc;
            color: var(--primary);
            font-weight: 700;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #eadffb;
        }

        .badge-kbm-stat {
            background: #fbf9fe !important;
            color: #4d4659 !important;
            border: 1px solid #e7dfef;
            font-size: 0.74rem;
            font-weight: 600;
            padding: 5px 12px;
        }

        .btn-kbm-detail {
            background: #ffffff;
            color: var(--primary);
            border: 1.5px solid #d8c8f3;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-kbm-detail:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.25);
        }

        /* DETAIL MONITORING - CLEAN WHITE */
        .detail-monitoring-header {
            background: #ffffff;
            border: 1px solid rgba(121, 75, 196, 0.08);
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow: 0 4px 20px rgba(64, 36, 105, 0.03);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.82rem;
            margin-bottom: 12px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .detail-meta-text {
            color: var(--muted);
            font-size: 0.8rem;
            margin-top: 6px;
        }

        /* 5 DETAIL STAT CARDS - CLEAN WHITE */
        .metric-card-detail-white {
            background: #ffffff;
            border: 1px solid rgba(121, 75, 196, 0.09);
            border-radius: 14px;
            padding: 16px 18px;
            position: relative;
            height: 100%;
            box-shadow: 0 3px 14px rgba(64, 36, 105, 0.02);
            transition: transform 0.2s ease;
        }

        .metric-card-detail-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(64, 36, 105, 0.05);
        }

        .metric-card-detail-white .metric-title {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--muted);
        }

        .metric-card-detail-white .metric-num {
            font-size: 1.65rem;
            font-weight: 800;
            margin: 4px 0 2px 0;
            color: var(--dark);
            line-height: 1.1;
        }

        .metric-card-detail-white .metric-sub {
            font-size: 0.72rem;
            color: var(--muted);
        }

        .metric-icon-bubble {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
        }

        /* Subtle Accent Icon Bubbles */
        .bubble-blue { background: #eff6ff; color: #2563eb; }
        .bubble-purple { background: #f5f3ff; color: #7c3aed; }
        .bubble-green { background: #ecfdf5; color: #059669; }
        .bubble-red { background: #fef2f2; color: #dc2626; }
        .bubble-yellow { background: #fffbeb; color: #d97706; }

        /* STATUS KELULUSAN SELECT */
        .status-select {
            min-width: 130px;
            border-radius: 9px;
            padding: 6px 10px;
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #e5dced;
            background-color: #ffffff;
        }

        .status-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
        }

        .status-select.status-menunggu {
            color: #b87900;
            background-color: #fff8e8;
            border-color: #f3d28a;
        }

        .status-select.status-lulus {
            color: #168154;
            background-color: #ecfaf3;
            border-color: #b8e7cf;
        }

        .status-select.status-tidak-lulus {
            color: #c43e3e;
            background-color: #fff0f0;
            border-color: #efbcbc;
        }

        .status-select.status-remidi {
            color: #7040c0;
            background-color: #f4edff;
            border-color: #d7c2fa;
        }

        .btn-purple-save {
            border: none;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            border-radius: 8px;
            padding: 6px 13px;
            font-size: 0.72rem;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(121, 75, 196, 0.2);
            transition: all 0.2s ease;
        }

        .btn-purple-save:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(121, 75, 196, 0.26);
        }

        @media (max-width: 768px) {
            .top-navbar {
                padding: 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .kbm-stat-card {
                padding: 15px;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>

<body>

    <!-- SIDEBAR -->
    <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

    <!-- MAIN CONTENT -->
    <div id="main-content">

        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="dash-header">
                <h3>Manajemen Kegiatan Belajar Mengajar</h3>
                <p>Pantau kegiatan kelas aktif, rekap sesi KBM, nilai ujian, dan status kelulusan peserta.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <?php
                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
                ?>
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap;">
                    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
                </div>
                <div class="admin-profile">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                    <div class="admin-info">
                        <h6><?= esc(session()->get('nama') ?? 'Administrator'); ?></h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- FLASH MESSAGE -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-xs mb-3" role="alert">
                <i class="fas fa-circle-check me-2"></i>
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-xs mb-3" role="alert">
                <i class="fas fa-circle-exclamation me-2"></i>
                <?= session()->getFlashdata('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- ==========================================
             VIEW 1: MONITORING MASTER (LIST OF CLASSES)
             ========================================== -->
        <div id="view-master-monitoring">
            <!-- 3 TOP STATS CARDS - CLEAN WHITE -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Kelas Dipantau -->
                <div class="col-md-4">
                    <div class="kbm-stat-card d-flex align-items-center">
                        <div class="kbm-stat-icon icon-purple-soft me-3">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <div class="kbm-stat-number"><?= esc($total_kelas_dipantau ?? count($daftar_kelas ?? [])); ?></div>
                            <span class="kbm-stat-label">Kelas Dipantau</span>
                        </div>
                    </div>
                </div>
                <!-- Card 2: Total Peserta Aktif -->
                <div class="col-md-4">
                    <div class="kbm-stat-card d-flex align-items-center">
                        <div class="kbm-stat-icon icon-blue-soft me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="kbm-stat-number"><?= esc($total_peserta_aktif ?? count($list_kbm ?? [])); ?></div>
                            <span class="kbm-stat-label">Total Peserta Aktif</span>
                        </div>
                    </div>
                </div>
                <!-- Card 3: Total Sesi KBM -->
                <div class="col-md-4">
                    <div class="kbm-stat-card d-flex align-items-center">
                        <div class="kbm-stat-icon icon-green-soft me-3">
                            <i class="fas fa-chart-simple"></i>
                        </div>
                        <div>
                            <div class="kbm-stat-number"><?= esc($total_sesi_kbm ?? 0); ?></div>
                            <span class="kbm-stat-label">Total Sesi KBM</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD DAFTAR KELAS AKTIF - CLEAN WHITE -->
            <div class="kbm-main-card mb-4">
                <div class="kbm-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-chart-simple text-primary fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Daftar Kelas Aktif</h5>
                        <span class="badge rounded-pill bg-light text-primary border" style="font-weight: 700; font-size: 0.72rem; padding: 4px 10px;">
                            <?= count($daftar_kelas ?? []); ?> KELAS
                        </span>
                    </div>
                    <div style="min-width: 260px; max-width: 340px;" class="w-100 w-md-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control border-start-0" id="search-kelas-input" placeholder="Cari kelas, program, instruktur..." oninput="filterKelasTable(this.value)">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="table-kelas-aktif">
                        <thead class="table-kbm-head">
                            <tr>
                                <th width="38%">KELAS &amp; PROGRAM</th>
                                <th width="30%">INSTRUKTUR</th>
                                <th width="20%">STATISTIK</th>
                                <th width="12%" class="text-end pe-4">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="table-kbm-body">
                            <?php if (!empty($daftar_kelas)): ?>
                                <?php foreach ($daftar_kelas as $cId => $kInfo): ?>
                                    <tr class="kelas-item-row" data-search="<?= esc(strtolower($kInfo['nama_kelas'] . ' ' . ($kInfo['program'] ?? '') . ' ' . ($kInfo['instruktur'] ?? ''))); ?>">
                                        <!-- KELAS & PROGRAM -->
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.88rem;"><?= esc($kInfo['nama_kelas']); ?></div>
                                            <small class="text-muted" style="font-size: 0.76rem;"><?= esc($kInfo['program'] ?: 'Pelatihan'); ?></small>
                                        </td>
                                        <!-- INSTRUKTUR -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="instructor-avatar-circle">
                                                    <?= esc(strtoupper(substr(trim($kInfo['instruktur'] ?? 'I'), 0, 1) ?: 'I')); ?>
                                                </div>
                                                <span class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                                    <?= esc($kInfo['instruktur'] ?: 'Instruktur'); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <!-- STATISTIK -->
                                        <td>
                                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                                <span class="badge rounded-pill badge-kbm-stat">
                                                    <i class="fas fa-user me-1 text-primary"></i> <?= (int)$kInfo['total_siswa']; ?> Siswa
                                                </span>
                                                <span class="badge rounded-pill badge-kbm-stat">
                                                    <i class="far fa-calendar-alt me-1 text-primary"></i> <?= (int)$kInfo['jumlah_sesi']; ?> Sesi
                                                </span>
                                            </div>
                                        </td>
                                        <!-- AKSI -->
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-kbm-detail" onclick="openClassDetail(<?= $cId; ?>)">
                                                <i class="far fa-eye me-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr id="no-matching-class" style="display: none;">
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-search me-1"></i> Tidak ada kelas yang sesuai dengan kata kunci pencarian.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-chalkboard-user fs-2 d-block mb-2 opacity-50"></i>
                                        Belum ada kelas yang dipantau.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==========================================
             VIEW 2: DETAIL MONITORING (PER KELAS)
             ========================================== -->
        <div id="view-detail-monitoring" style="display: none;">
            <!-- BACK LINK & HEADER - CLEAN WHITE -->
            <div class="detail-monitoring-header">
                <a href="javascript:void(0)" onclick="closeClassDetail()" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Monitoring KBM
                </a>
                <div class="text-muted small fw-medium mb-1">
                    Detail Monitoring: <span class="fw-bold text-dark" id="breadcrumb-class-name">Nama Kelas</span>
                </div>
                <h2 class="fw-bold mb-1" style="color: var(--dark); font-size: 1.45rem;">
                    Detail <span style="color: var(--primary);">Monitoring</span>
                </h2>
                <h4 class="fw-bold text-dark mb-2" id="header-class-name" style="font-size: 1.15rem;">Nama Kelas</h4>
                <p class="detail-meta-text mb-0" id="header-class-meta">
                    Program: <span id="meta-program">-</span> &bull; 
                    Instruktur: <span id="meta-instruktur">-</span> &bull; 
                    Periode: <span id="meta-periode">- s/d -</span> &bull; 
                    Kuota: <span id="meta-kuota">20 Peserta</span>
                </p>
            </div>

            <!-- 5 METRIC STAT CARDS - CLEAN WHITE -->
            <div class="row g-2.5 g-md-3 mb-4">
                <!-- 1. TOTAL SESI -->
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card-detail-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="metric-title">TOTAL SESI</span>
                            <span class="metric-icon-bubble bubble-blue"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <div class="metric-num" id="metric-total-sesi">0</div>
                        <div class="metric-sub">Pertemuan kelas</div>
                    </div>
                </div>
                <!-- 2. TOTAL PESERTA -->
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card-detail-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="metric-title">TOTAL PESERTA</span>
                            <span class="metric-icon-bubble bubble-purple"><i class="fas fa-users"></i></span>
                        </div>
                        <div class="metric-num" id="metric-total-peserta">0</div>
                        <div class="metric-sub">Siswa terdaftar</div>
                    </div>
                </div>
                <!-- 3. LULUS UJIAN -->
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card-detail-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="metric-title">LULUS UJIAN</span>
                            <span class="metric-icon-bubble bubble-green"><i class="fas fa-circle"></i></span>
                        </div>
                        <div class="metric-num" id="metric-lulus">0</div>
                        <div class="metric-sub" id="metric-lulus-pct">0% dari total siswa</div>
                    </div>
                </div>
                <!-- 4. TIDAK LULUS -->
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card-detail-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="metric-title">TIDAK LULUS</span>
                            <span class="metric-icon-bubble bubble-red"><i class="fas fa-circle"></i></span>
                        </div>
                        <div class="metric-num" id="metric-tidak-lulus">0</div>
                        <div class="metric-sub" id="metric-tidak-lulus-note">Nihil ketidaklulusan</div>
                    </div>
                </div>
                <!-- 5. BELUM UJIAN -->
                <div class="col-6 col-md-4 col-lg">
                    <div class="metric-card-detail-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="metric-title">BELUM UJIAN</span>
                            <span class="metric-icon-bubble bubble-yellow"><i class="fas fa-circle"></i></span>
                        </div>
                        <div class="metric-num" id="metric-menunggu">0</div>
                        <div class="metric-sub">Menunggu pelaksanaan</div>
                    </div>
                </div>
            </div>

            <!-- TABLE DAFTAR PESERTA & NILAI UJIAN - CLEAN WHITE -->
            <div class="kbm-main-card mb-4">
                <div class="kbm-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-chart-simple text-primary fs-5"></i>
                        <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Daftar Peserta &amp; Nilai Ujian</h5>
                    </div>
                    <div style="min-width: 260px; max-width: 340px;" class="w-100 w-md-auto">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control border-start-0" id="search-peserta-input" placeholder="Cari nama atau email siswa." oninput="filterPesertaDetailTable(this.value)">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="table-detail-peserta">
                        <thead class="table-kbm-head">
                            <tr>
                                <th width="5%" class="text-center">NO</th>
                                <th width="28%">NAMA PESERTA</th>
                                <th width="14%" class="text-center">KEHADIRAN</th>
                                <th width="16%" class="text-center">NILAI UJIAN</th>
                                <th width="23%">STATUS KELULUSAN</th>
                                <th width="14%" class="text-center">STATUS ANGKET</th>
                            </tr>
                        </thead>
                        <tbody class="table-kbm-body" id="detail-peserta-tbody">
                            <?php if (!empty($list_kbm)): ?>
                                <?php foreach ($list_kbm as $pIndex => $row): ?>
                                    <form
                                        id="form-update-<?= $row['id_nilai_ujian']; ?>"
                                        action="<?= base_url('admin/manajemen-kbm/update-status/' . $row['id_nilai_ujian']); ?>"
                                        method="POST">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="id_kelas" value="<?= (int)($row['id_kelas'] ?? 0); ?>">
                                    </form>

                                    <tr class="peserta-detail-row" 
                                        data-class-id="<?= (int)($row['id_kelas'] ?? 0); ?>" 
                                        data-nama="<?= esc(strtolower($row['nama_peserta'] ?? '')); ?>" 
                                        data-email="<?= esc(strtolower($row['email_peserta'] ?? '')); ?>"
                                        data-nis="<?= esc(strtolower($row['nis'] ?? '')); ?>">
                                        <!-- NO -->
                                        <td class="text-center fw-semibold text-secondary row-no-cell">
                                            <?= $pIndex + 1; ?>
                                        </td>

                                        <!-- NAMA PESERTA -->
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.88rem;"><?= esc($row['nama_peserta'] ?? '-'); ?></div>
                                            <div class="text-muted" style="font-size: 0.74rem;">
                                                <i class="far fa-envelope me-1"></i><?= esc($row['email_peserta'] ?? '-'); ?>
                                            </div>
                                            <?php if (!empty($row['nis']) && $row['nis'] !== '-'): ?>
                                                <span class="badge bg-light text-secondary border mt-1 font-monospace" style="font-size: 0.68rem;">
                                                    NIS: <?= esc($row['nis']); ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- KEHADIRAN -->
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-light text-dark border px-2.5 py-1.5" style="font-size: 0.74rem;">
                                                <i class="fas fa-clipboard-user text-primary me-1"></i>
                                                <?= (int)($row['total_hadir'] ?? 0); ?> Hadir
                                            </span>
                                        </td>

                                        <!-- NILAI UJIAN & BERKAS NILAI -->
                                        <td class="text-center">
                                            <div class="fw-bold fs-6 text-dark mb-0.5">
                                                <?= number_format((float)($row['nilai'] ?? 0), 0); ?>
                                            </div>
                                            <?php if (!empty($row['has_file_nilai'])): ?>
                                                <a href="<?= base_url('admin/manajemen-kbm/unduh-file-nilai/' . $row['id_nilai_ujian']); ?>"
                                                   class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none py-1 px-2 rounded-pill shadow-xs mt-1 d-inline-block"
                                                   title="Unduh Berkas Nilai dari Instruktur">
                                                    <i class="fas fa-file-arrow-down me-1"></i> Berkas Nilai
                                                </a>
                                            <?php else: ?>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">
                                                    <i class="fas fa-clock opacity-50 me-1"></i> Belum ada file
                                                </small>
                                            <?php endif; ?>
                                        </td>

                                        <!-- STATUS KELULUSAN -->
                                        <td>
                                            <?php
                                            $status = strtolower(trim((string)($row['status_kelulusan'] ?? 'menunggu')));
                                            $statusClass = 'status-menunggu';
                                            if ($status === 'lulus') $statusClass = 'status-lulus';
                                            elseif ($status === 'tidak lulus') $statusClass = 'status-tidak-lulus';
                                            elseif ($status === 'remidi') $statusClass = 'status-remidi';
                                            ?>
                                            <div class="d-flex align-items-center gap-1.5 flex-nowrap">
                                                <select
                                                    form="form-update-<?= $row['id_nilai_ujian']; ?>"
                                                    name="status_kelulusan"
                                                    class="form-select status-select <?= $statusClass; ?>"
                                                    onchange="changeStatusColor(this)">
                                                    <option value="menunggu" <?= ($status === 'menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                                                    <option value="lulus" <?= ($status === 'lulus') ? 'selected' : ''; ?>>Lulus</option>
                                                    <option value="tidak lulus" <?= ($status === 'tidak lulus') ? 'selected' : ''; ?>>Tidak Lulus</option>
                                                    <option value="remidi" <?= ($status === 'remidi') ? 'selected' : ''; ?>>Remidi</option>
                                                </select>
                                                <button
                                                    type="submit"
                                                    form="form-update-<?= $row['id_nilai_ujian']; ?>"
                                                    class="btn btn-purple-save"
                                                    title="Simpan Perubahan Status">
                                                    <i class="fas fa-paper-plane me-1"></i> Kirim
                                                </button>
                                            </div>
                                        </td>

                                        <!-- STATUS ANGKET -->
                                        <td class="text-center">
                                            <?php if (isset($row['jumlah_angket']) && (int)$row['jumlah_angket'] > 0): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5" style="font-size: 0.72rem;">
                                                    <i class="fas fa-check me-1"></i> Sudah Mengisi
                                                </span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1.5" style="font-size: 0.72rem;">
                                                    <i class="fas fa-xmark me-1"></i> Belum Mengisi
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- EMPTY STATE -->
                <div id="detail-empty-state" class="text-center py-5 text-muted" style="display: none;">
                    <p class="mb-0" style="color: var(--muted); font-size: 0.88rem;">Belum ada peserta terdaftar di kelas ini.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- DATA KELAS JSON FOR CLIENT JAVASCRIPT -->
    <script>
        const DAFTAR_KELAS_DATA = <?= json_encode($daftar_kelas ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        const ACTIVE_CLASS_FLASH = <?= json_encode(session()->getFlashdata('active_class_id') ?? null); ?>;

        /* Filter tabel kelas aktif di View 1 */
        function filterKelasTable(keyword) {
            const term = (keyword || '').toLowerCase().trim();
            const rows = document.querySelectorAll('#table-kelas-aktif tbody tr.kelas-item-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const searchContent = row.getAttribute('data-search') || '';
                if (term === '' || searchContent.includes(term)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noRow = document.getElementById('no-matching-class');
            if (noRow) {
                noRow.style.display = (visibleCount === 0 && term !== '') ? '' : 'none';
            }
        }

        /* Buka Detail Monitoring Kelas (View 2) */
        function openClassDetail(classId) {
            const classInfo = DAFTAR_KELAS_DATA[classId];
            if (!classInfo) return;

            // 1. Update Header Info
            const className = classInfo.nama_kelas || ('Kelas #' + classId);
            document.getElementById('breadcrumb-class-name').textContent = className;
            document.getElementById('header-class-name').textContent = className;

            document.getElementById('meta-program').textContent = classInfo.program || 'Pelatihan';
            document.getElementById('meta-instruktur').textContent = classInfo.instruktur || 'Instruktur';
            document.getElementById('meta-periode').textContent = classInfo.periode || '- s/d -';
            document.getElementById('meta-kuota').textContent = (classInfo.kuota || 20) + ' Peserta';

            // 2. Update 5 Metric Cards
            const totalSiswa = parseInt(classInfo.total_siswa) || 0;
            const lulus = parseInt(classInfo.lulus) || 0;
            const tidakLulus = parseInt(classInfo.tidak_lulus) || 0;
            const menunggu = parseInt(classInfo.menunggu) || 0;
            const totalSesi = parseInt(classInfo.jumlah_sesi) || 6;

            document.getElementById('metric-total-sesi').textContent = totalSesi;
            document.getElementById('metric-total-peserta').textContent = totalSiswa;
            document.getElementById('metric-lulus').textContent = lulus;
            
            const pctLulus = totalSiswa > 0 ? Math.round((lulus / totalSiswa) * 100) : 0;
            document.getElementById('metric-lulus-pct').textContent = pctLulus + '% dari total siswa';

            document.getElementById('metric-tidak-lulus').textContent = tidakLulus;
            document.getElementById('metric-tidak-lulus-note').textContent = tidakLulus === 0 ? 'Nihil ketidaklulusan' : (tidakLulus + ' siswa tidak lulus');

            document.getElementById('metric-menunggu').textContent = menunggu;

            // 3. Filter Tabel Peserta
            const pesertaRows = document.querySelectorAll('#table-detail-peserta tbody tr.peserta-detail-row');
            let matchedPeserta = 0;

            pesertaRows.forEach(row => {
                const rowClassId = parseInt(row.getAttribute('data-class-id'));
                if (rowClassId === parseInt(classId)) {
                    row.style.display = '';
                    matchedPeserta++;
                    const rowNoCell = row.querySelector('.row-no-cell');
                    if (rowNoCell) rowNoCell.textContent = matchedPeserta;
                } else {
                    row.style.display = 'none';
                }
            });

            // 4. Handle Empty State
            const tableElement = document.querySelector('#table-detail-peserta');
            const emptyState = document.getElementById('detail-empty-state');
            if (matchedPeserta === 0) {
                if (tableElement) tableElement.style.display = 'none';
                if (emptyState) emptyState.style.display = 'block';
            } else {
                if (tableElement) tableElement.style.display = '';
                if (emptyState) emptyState.style.display = 'none';
            }

            // Reset search input
            const searchInput = document.getElementById('search-peserta-input');
            if (searchInput) searchInput.value = '';

            // Switch Views
            document.getElementById('view-master-monitoring').style.display = 'none';
            document.getElementById('view-detail-monitoring').style.display = 'block';

            // Update URL query tanpa reload halaman
            const url = new URL(window.location);
            url.searchParams.set('id_kelas', classId);
            window.history.pushState({ classId: classId }, '', url);

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        /* Kembali ke View 1 (Monitoring Master) */
        function closeClassDetail() {
            document.getElementById('view-detail-monitoring').style.display = 'none';
            document.getElementById('view-master-monitoring').style.display = 'block';

            const url = new URL(window.location);
            url.searchParams.delete('id_kelas');
            window.history.pushState({}, '', url.pathname);

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        /* Filter tabel peserta pada View 2 */
        function filterPesertaDetailTable(keyword) {
            const term = (keyword || '').toLowerCase().trim();
            const currentUrl = new URL(window.location);
            const activeClassId = parseInt(currentUrl.searchParams.get('id_kelas'));
            if (!activeClassId) return;

            const rows = document.querySelectorAll('#table-detail-peserta tbody tr.peserta-detail-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const rowClassId = parseInt(row.getAttribute('data-class-id'));
                if (rowClassId !== activeClassId) {
                    row.style.display = 'none';
                    return;
                }

                const nama = row.getAttribute('data-nama') || '';
                const email = row.getAttribute('data-email') || '';
                const nis = row.getAttribute('data-nis') || '';

                if (term === '' || nama.includes(term) || email.includes(term) || nis.includes(term)) {
                    row.style.display = '';
                    visibleCount++;
                    const rowNoCell = row.querySelector('.row-no-cell');
                    if (rowNoCell) rowNoCell.textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            const tableElement = document.querySelector('#table-detail-peserta');
            const emptyState = document.getElementById('detail-empty-state');
            if (visibleCount === 0) {
                if (emptyState) {
                    emptyState.style.display = 'block';
                    emptyState.innerHTML = '<p class="mb-0" style="color: var(--muted); font-size: 0.88rem;">Tidak ada peserta yang cocok dengan pencarian.</p>';
                }
            } else {
                if (tableElement) tableElement.style.display = '';
                if (emptyState) emptyState.style.display = 'none';
            }
        }

        /* Warna Status Kelulusan Select */
        function changeStatusColor(select) {
            select.classList.remove('status-menunggu', 'status-lulus', 'status-tidak-lulus', 'status-remidi');
            switch (select.value.toLowerCase()) {
                case 'lulus':
                    select.classList.add('status-lulus');
                    break;
                case 'tidak lulus':
                    select.classList.add('status-tidak-lulus');
                    break;
                case 'remidi':
                    select.classList.add('status-remidi');
                    break;
                default:
                    select.classList.add('status-menunggu');
                    break;
            }
        }

        /* Inisialisasi awal saat load halaman */
        document.addEventListener("DOMContentLoaded", function () {
            // Cek apakah ada parameter id_kelas di URL atau dari Flash session setelah update
            const urlParams = new URLSearchParams(window.location.search);
            const classParam = urlParams.get('id_kelas') || ACTIVE_CLASS_FLASH;

            if (classParam && DAFTAR_KELAS_DATA[classParam]) {
                openClassDetail(parseInt(classParam));
            }
        });

        // Tangani tombol browser Back / Forward
        window.addEventListener('popstate', function (event) {
            const urlParams = new URLSearchParams(window.location.search);
            const classParam = urlParams.get('id_kelas');
            if (classParam && DAFTAR_KELAS_DATA[classParam]) {
                openClassDetail(parseInt(classParam));
            } else {
                document.getElementById('view-detail-monitoring').style.display = 'none';
                document.getElementById('view-master-monitoring').style.display = 'block';
            }
        });
    </script>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
