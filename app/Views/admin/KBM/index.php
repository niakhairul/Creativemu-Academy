<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title); ?> - Creativemu Academy</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #794bc4;
            --primary-dark: #5931a0;
            --primary-light: #f4f0fc;

            --dark: #24143b;
            --text: #4d4659;
            --muted: #8d849d;

            --border: #eee9f5;
            --background: #f7f5fc;

            --success: #22a06b;
            --warning: #e99b18;
            --danger: #e05252;
            --remidi: #8b5cf6;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
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

        /* =========================
           SCROLLBAR
        ========================= */

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1edf8;
        }

        ::-webkit-scrollbar-thumb {
            background: #b79be8;
            border-radius: 20px;
        }

        /* =========================
           MAIN CONTENT
        ========================= */

        #main-content {
            margin-left: 240px;
            padding: 24px;
            min-height: 100vh;
        }

        /* =========================
           TOP NAVBAR
        ========================= */

        .top-navbar {
            background: #ffffff;
            border: 1px solid rgba(121, 75, 196, 0.06);
            border-radius: 18px;
            padding: 18px 22px;
            margin-bottom: 22px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 8px 30px rgba(56, 32, 91, 0.06);
        }

        .dash-header h3 {
            margin: 0 0 5px 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
        }

        .dash-header p {
            margin: 0;
            color: var(--muted);
            font-size: 0.78rem;
        }

        /* =========================
           ADMIN PROFILE
        ========================= */

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-profile img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e5d9f7;
            padding: 2px;
            background: #fff;
        }

        .admin-info h6 {
            margin: 0;
            color: var(--dark);
            font-size: 0.84rem;
            font-weight: 700;
        }

        .admin-info small {
            color: var(--muted);
            font-size: 0.7rem;
        }

        .date-badge {
            background: var(--primary-light);
            color: var(--primary) !important;
            border: 1px solid #e8dcfa;
            padding: 7px 13px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        /* =========================
           MAIN CARD
        ========================= */

        .content-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(121, 75, 196, 0.06);
            padding: 24px;
            box-shadow: 0 8px 30px rgba(56, 32, 91, 0.05);
        }

        /* =========================
           CARD HEADER
        ========================= */

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .title-wrapper {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .title-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, #f0e7fc, #e6d8fa);
            color: var(--primary);
            font-size: 17px;
        }

        .card-title-custom {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
        }

        .card-description {
            margin: 3px 0 0 0;
            color: var(--muted);
            font-size: 0.73rem;
        }

        /* =========================
           ALERT
        ========================= */

        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.8rem;
            padding: 12px 15px;
        }

        /* =========================
           TABLE WRAPPER
        ========================= */

        .table-container {
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .table-responsive {
            margin: 0;
        }

        .table-custom {
            margin: 0;
            vertical-align: middle;
            font-size: 0.78rem;
        }

        /* =========================
           TABLE HEADER
        ========================= */

        .table-custom thead th {
            background: #faf8fd;
            color: #6c6080;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 15px 12px;
            border-bottom: 1px solid var(--border);
            border-top: none;
            white-space: nowrap;
        }

        /* =========================
           TABLE BODY
        ========================= */

        .table-custom tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #f2eef7;
            color: var(--text);
        }

        .table-custom tbody tr {
            transition: 0.2s ease;
        }

        .table-custom tbody tr:hover {
            background: #fcfaff;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        /* =========================
           PARTICIPANT
        ========================= */

        .participant-name {
            font-weight: 700;
            color: var(--dark);
            font-size: 0.78rem;
        }

        .participant-email {
            margin-top: 3px;
            font-size: 0.68rem;
            color: var(--muted);
        }

        .nis-text {
            font-weight: 600;
            color: #716781;
        }

        /* =========================
           CLASS BADGE
        ========================= */

        .class-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f6f3fb;
            color: #67557e;
            border: 1px solid #e9e1f4;
            padding: 5px 9px;
            border-radius: 7px;
            font-size: 0.68rem;
            font-weight: 600;
        }

        /* =========================
           NILAI
        ========================= */

        .score-input {
            width: 68px !important;
            margin: auto;
            border: 1px solid #e2d8f1;
            border-radius: 8px;
            font-weight: 700;
            color: var(--dark);
            padding: 7px 5px;
            font-size: 0.78rem;
        }

        .score-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
        }

        /* =========================
           CARD KELAS
        ========================= */

        .class-card {
            background: #ffffff;
            border: 1.5px solid #eae5f5;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .class-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: 0 8px 22px rgba(121, 75, 196, 0.12);
        }

        .class-card.active {
            border: 2px solid var(--primary);
            background: linear-gradient(135deg, #ffffff 0%, #f7f2fe 100%);
            box-shadow: 0 8px 24px rgba(121, 75, 196, 0.18);
        }

        .class-card.active .class-card-icon {
            background: var(--primary);
            color: #ffffff;
        }

        .class-card.active .card-action-icon {
            transform: translateX(3px);
            color: var(--primary-dark) !important;
            font-weight: 700;
        }

        .class-card.active .card-status-label {
            color: var(--primary) !important;
            font-weight: 700;
        }

        .class-card-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: all 0.25s ease;
        }

        .class-card-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--dark);
        }

        .bg-purple-soft {
            background: rgba(121, 75, 196, 0.1);
        }

        .text-purple {
            color: var(--primary) !important;
        }

        /* =========================
           ANGKET BADGE
        ========================= */

        .status-angket {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 0.67rem;
            font-weight: 600;
        }

        .status-angket.sudah {
            background: #e9f8f0;
            color: var(--success);
        }

        .status-angket.belum {
            background: #fff0f0;
            color: var(--danger);
        }

        /* =========================
           STATUS KELULUSAN
        ========================= */

        .status-select {
            min-width: 135px;
            border-radius: 9px;
            padding: 7px 10px;
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

        /* =========================
           KIRIM BUTTON
        ========================= */

        .btn-purple {
            border: none;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            border-radius: 9px;
            padding: 7px 13px;
            font-size: 0.7rem;
            font-weight: 600;
            box-shadow: 0 5px 12px rgba(121, 75, 196, 0.2);
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 7px 16px rgba(121, 75, 196, 0.28);
        }

        .btn-purple:active {
            transform: translateY(0);
        }

        /* =========================
           EMPTY DATA
        ========================= */

        .empty-data {
            padding: 50px 20px !important;
            text-align: center;
        }

        .empty-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            background: #f4f0fb;
            color: #9a7ccc;
            border-radius: 50%;
            font-size: 20px;
        }

        .empty-data strong {
            display: block;
            color: var(--dark);
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        .empty-data span {
            color: var(--muted);
            font-size: 0.72rem;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1200px) {
            /* Let admin-responsive.css handle main-content margins */
            .table-custom {
                min-width: 1050px;
            }
        }

        @media (max-width: 768px) {
            /* Let admin-responsive.css handle main-content margins & padding */
            .top-navbar {
                padding: 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }
            .content-card {
                padding: 15px;
                border-radius: 14px;
            }
            .card-header-custom {
                align-items: flex-start;
            }
            .date-badge {
                display: none;
            }
        }
    </style>
    <!-- Admin Responsive Styles & Scripts -->
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
                <p>Kelola nilai ujian dan status kelulusan peserta.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
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

        <!-- CONTENT CARD -->
        <div class="content-card">

            <!-- FLASH MESSAGE -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-circle-check me-2"></i>
                    <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-circle-exclamation me-2"></i>
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

<?php
// Grouping data kelas dari $list_kbm
$daftarKelas = [];
foreach ($list_kbm as $kbm) {
    $cId = (int)($kbm['id_kelas'] ?? 0);
    $cName = trim((string)($kbm['kelas'] ?? 'Kelas Pelatihan'));
    if (!isset($daftarKelas[$cId])) {
        $daftarKelas[$cId] = [
            'id_kelas'      => $cId,
            'nama_kelas'    => $cName,
            'total_peserta' => 0,
            'lulus'         => 0,
            'menunggu'      => 0,
            'remidi'        => 0,
            'tidak_lulus'   => 0,
        ];
    }
    $daftarKelas[$cId]['total_peserta']++;
    $st = strtolower(trim((string)($kbm['status_kelulusan'] ?? 'menunggu')));
    if ($st === 'lulus') $daftarKelas[$cId]['lulus']++;
    elseif ($st === 'remidi') $daftarKelas[$cId]['remidi']++;
    elseif ($st === 'tidak lulus') $daftarKelas[$cId]['tidak_lulus']++;
    else $daftarKelas[$cId]['menunggu']++;
}
?>

            <!-- SECTION CARD KELAS -->
            <div class="class-cards-section mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h6 class="fw-bold mb-1" style="color: var(--dark); font-size: 0.95rem;">
                            <i class="fas fa-layer-group me-1.5" style="color: var(--primary);"></i> Pilih Kelas Pelatihan
                        </h6>
                        <p class="text-muted small mb-0" style="font-size: 0.76rem;">Klik salah satu card kelas di bawah untuk melihat daftar nama peserta yang mengikuti kelas tersebut.</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 shadow-none active" id="btn-show-all-class" onclick="resetClassFilter()">
                            <i class="fas fa-list me-1"></i> Tampilkan Semua Kelas (<?= count($list_kbm); ?> Peserta)
                        </button>
                    </div>
                </div>

                <div class="row g-3">
                    <?php if (!empty($daftarKelas)): ?>
                        <?php foreach ($daftarKelas as $cId => $kInfo): ?>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="class-card h-100 p-3" data-class-id="<?= $cId; ?>" onclick="selectClassCard(<?= $cId; ?>, '<?= esc(addslashes($kInfo['nama_kelas'])); ?>')">
                                    <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                        <div class="class-card-icon">
                                            <i class="fas fa-chalkboard-user"></i>
                                        </div>
                                        <span class="badge rounded-pill bg-purple-soft text-purple fw-bold px-2.5 py-1" style="font-size: 0.7rem;">
                                            <i class="fas fa-users me-1"></i> <?= $kInfo['total_peserta']; ?> Peserta
                                        </span>
                                    </div>

                                    <h6 class="class-card-title mb-2 text-truncate" title="<?= esc($kInfo['nama_kelas']); ?>">
                                        <?= esc($kInfo['nama_kelas']); ?>
                                    </h6>

                                    <div class="d-flex align-items-center gap-1.5 flex-wrap mb-3" style="font-size: 0.68rem;">
                                        <?php if ($kInfo['menunggu'] > 0): ?>
                                            <span class="badge bg-warning bg-opacity-15 text-warning px-2 py-1 rounded-pill">
                                                <i class="fas fa-clock me-1"></i> <?= $kInfo['menunggu']; ?> Menunggu
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($kInfo['lulus'] > 0): ?>
                                            <span class="badge bg-success bg-opacity-15 text-success px-2 py-1 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i> <?= $kInfo['lulus']; ?> Lulus
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($kInfo['remidi'] > 0): ?>
                                            <span class="badge bg-purple bg-opacity-15 text-purple px-2 py-1 rounded-pill">
                                                <i class="fas fa-redo me-1"></i> <?= $kInfo['remidi']; ?> Remidi
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($kInfo['tidak_lulus'] > 0): ?>
                                            <span class="badge bg-danger bg-opacity-15 text-danger px-2 py-1 rounded-pill">
                                                <i class="fas fa-times-circle me-1"></i> <?= $kInfo['tidak_lulus']; ?> Tidak Lulus
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="class-card-footer mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                        <span class="card-status-label small text-muted" style="font-size: 0.72rem;">Klik untuk buka</span>
                                        <span class="card-action-icon text-primary"><i class="fas fa-arrow-right"></i></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info py-2 px-3 small rounded-3 mb-0">
                                <i class="fas fa-info-circle me-1"></i> Belum ada kelas yang memiliki peserta aktif.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CARD TITLE & TABLE HEADER -->
            <div class="card-header-custom" id="daftar-peserta-header">
                <div class="title-wrapper">
                    <div class="title-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h5 class="card-title-custom" id="selected-class-title">Daftar Penilaian &amp; Kelulusan Peserta</h5>
                        <p class="card-description" id="selected-class-desc">Menampilkan semua peserta dari seluruh kelas pelatihan.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="max-width: 220px;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="search-peserta-input" class="form-control border-start-0" placeholder="Cari nama / NIS..." oninput="filterPesertaByName(this.value)">
                    </div>
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" id="btn-reset-filter" onclick="resetClassFilter()" style="display: none;">
                        <i class="fas fa-rotate-left me-1"></i> Tampilkan Semua
                    </button>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-custom align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="11%">NIS</th>
                                <th width="18%">Peserta</th>
                                <th width="18%">Email</th>
                                <th width="16%">Kelas</th>
                                <th class="text-center" width="11%">Angket</th>
                                <th width="13%">Status</th>
                                <th class="text-center" width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($list_kbm)): ?>
                                <?php foreach ($list_kbm as $index => $row): ?>

                                    <form
                                        id="form-update-<?= $row['id_nilai_ujian']; ?>"
                                        action="<?= base_url('admin/manajemen-kbm/update-status/' . $row['id_nilai_ujian']); ?>"
                                        method="POST">
                                        <?= csrf_field(); ?>
                                    </form>

                                    <tr class="peserta-row" data-id-kelas="<?= (int)($row['id_kelas'] ?? 0); ?>" data-nama="<?= esc(strtolower($row['nama_peserta'] ?? '')); ?>" data-nis="<?= esc(strtolower($row['nis'] ?? '')); ?>">
                                        <!-- NO -->
                                        <td class="text-center">
                                            <span class="fw-semibold row-number"><?= $index + 1; ?></span>
                                        </td>

                                        <!-- NIS -->
                                        <td>
                                            <span class="nis-text"><?= esc($row['nis'] ?? '-'); ?></span>
                                        </td>

                                        <!-- PESERTA -->
                                        <td>
                                            <div class="participant-name"><?= esc($row['nama_peserta'] ?? '-'); ?></div>
                                        </td>

                                        <!-- EMAIL -->
                                        <td>
                                            <div class="participant-email">
                                                <i class="fas fa-envelope me-1"></i>
                                                <?= esc($row['email_peserta'] ?? '-'); ?>
                                            </div>
                                        </td>

                                        <!-- KELAS -->
                                        <td>
                                            <span class="class-badge">
                                                <i class="fas fa-users"></i>
                                                <?= esc($row['kelas'] ?? '-'); ?>
                                            </span>
                                            <div class="mt-1">
                                                <a href="<?= base_url('admin/manajemen-kbm/unduh-file-nilai/' . $row['id_nilai_ujian']); ?>"
                                                   class="badge bg-light text-primary border text-decoration-none"
                                                   title="Unduh berkas nilai dari instruktur (Laravel)">
                                                    <i class="fas fa-file-arrow-down me-1"></i> File Nilai
                                                </a>
                                            </div>
                                        </td>


                                        <td class="text-center">
                                            <?php if (isset($row['jumlah_angket']) && $row['jumlah_angket'] > 0): ?>
                                                <span class="status-angket sudah">
                                                    <i class="fas fa-check"></i> Sudah Mengisi
                                                </span>
                                            <?php else: ?>
                                                <span class="status-angket belum">
                                                    <i class="fas fa-xmark"></i> Belum Mengisi
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- STATUS KELULUSAN -->
                                        <td>
                                            <?php
                                            $status = strtolower($row['status_kelulusan'] ?? 'menunggu');
                                            $statusClass = 'status-menunggu';

                                            if ($status === 'lulus') {
                                                $statusClass = 'status-lulus';
                                            } elseif ($status === 'tidak lulus') {
                                                $statusClass = 'status-tidak-lulus';
                                            } elseif ($status === 'remidi') {
                                                $statusClass = 'status-remidi';
                                            }
                                            ?>

                                            <select
                                                form="form-update-<?= $row['id_nilai_ujian']; ?>"
                                                name="status_kelulusan"
                                                class="form-select status-select <?= $statusClass; ?>"
                                                onchange="changeStatusColor(this)">

                                                <option value="menunggu" <?= ($status === 'menunggu') ? 'selected' : ''; ?>>
                                                    Menunggu
                                                </option>
                                                <option value="lulus" <?= ($status === 'lulus') ? 'selected' : ''; ?>>
                                                    Lulus
                                                </option>
                                                <option value="tidak lulus" <?= ($status === 'tidak lulus') ? 'selected' : ''; ?>>
                                                    Tidak Lulus
                                                </option>
                                                <option value="remidi" <?= ($status === 'remidi') ? 'selected' : ''; ?>>
                                                    Remidi
                                                </option>
                                            </select>
                                        </td>

                                        <!-- AKSI -->
                                        <td class="text-center">
                                            <button
                                                type="submit"
                                                form="form-update-<?= $row['id_nilai_ujian']; ?>"
                                                class="btn btn-purple">
                                                <i class="fas fa-paper-plane me-1"></i> Kirim
                                            </button>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                                <tr id="no-matching-row" style="display: none;">
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <div class="py-3">
                                            <i class="fas fa-users-slash fs-2 d-block mb-2 opacity-50"></i>
                                            <strong class="d-block mb-1">Tidak ada peserta ditemukan</strong>
                                            <span class="small">Tidak ada data peserta yang cocok pada kelas ini atau pencarian Anda.</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="empty-data">
                                        <div class="empty-icon">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <strong>Belum Ada Data Peserta</strong>
                                        <span>Data peserta dengan nilai ujian akan muncul di sini.</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* TANGGAL */
        document.addEventListener("DOMContentLoaded", function () {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date();
            const dateString = today.toLocaleDateString('id-ID', options);
            const dateElement = document.getElementById('current-date');
            if (dateElement) {
                dateElement.textContent = dateString;
            }
        });

        /* WARNA STATUS KELULUSAN */
        function changeStatusColor(select) {
            // Hapus semua class warna
            select.classList.remove(
                'status-menunggu',
                'status-lulus',
                'status-tidak-lulus',
                'status-remidi'
            );

            // Tambahkan class sesuai status (menggunakan toLowerCase agar aman)
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

        /* SET WARNA SAAT HALAMAN DIBUKA */
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.status-select').forEach(function (select) {
                changeStatusColor(select);
            });
        });

        /* ======================================================
           FILTER KELAS BERDASARKAN CARD KELAS
        ====================================================== */
        let currentSelectedClassId = 'all';

        function selectClassCard(classId, className) {
            currentSelectedClassId = String(classId);
            
            // Simpan ke localStorage agar tidak hilang saat reload atau setelah submit form
            localStorage.setItem('active_kbm_kelas', currentSelectedClassId);
            localStorage.setItem('active_kbm_kelas_name', className);

            // Update styling card
            document.querySelectorAll('.class-card').forEach(card => {
                if (card.getAttribute('data-class-id') === String(classId)) {
                    card.classList.add('active');
                    const lbl = card.querySelector('.card-status-label');
                    if (lbl) lbl.textContent = 'Sedang Ditampilkan';
                } else {
                    card.classList.remove('active');
                    const lbl = card.querySelector('.card-status-label');
                    if (lbl) lbl.textContent = 'Klik untuk buka';
                }
            });

            const btnAll = document.getElementById('btn-show-all-class');
            if (btnAll) {
                btnAll.classList.remove('active');
            }

            const btnReset = document.getElementById('btn-reset-filter');
            if (btnReset) btnReset.style.display = 'inline-block';

            // Update judul section tabel
            const titleEl = document.getElementById('selected-class-title');
            if (titleEl) titleEl.textContent = 'Peserta Kelas: ' + className;
            
            applyFilters();

            // Smooth scroll ke tabel peserta
            const headerEl = document.getElementById('daftar-peserta-header');
            if (headerEl) {
                headerEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function resetClassFilter() {
            currentSelectedClassId = 'all';
            localStorage.removeItem('active_kbm_kelas');
            localStorage.removeItem('active_kbm_kelas_name');

            document.querySelectorAll('.class-card').forEach(card => {
                card.classList.remove('active');
                const lbl = card.querySelector('.card-status-label');
                if (lbl) lbl.textContent = 'Klik untuk buka';
            });

            const btnAll = document.getElementById('btn-show-all-class');
            if (btnAll) {
                btnAll.classList.add('active');
            }

            const btnReset = document.getElementById('btn-reset-filter');
            if (btnReset) btnReset.style.display = 'none';

            const searchInput = document.getElementById('search-peserta-input');
            if (searchInput) searchInput.value = '';

            const titleEl = document.getElementById('selected-class-title');
            const descEl = document.getElementById('selected-class-desc');
            if (titleEl) titleEl.textContent = 'Daftar Penilaian & Kelulusan Peserta';
            if (descEl) descEl.textContent = 'Menampilkan semua peserta dari seluruh kelas pelatihan.';

            applyFilters();
        }

        function filterPesertaByName(query) {
            applyFilters(query.trim().toLowerCase());
        }

        function applyFilters(searchQuery = null) {
            if (searchQuery === null) {
                const input = document.getElementById('search-peserta-input');
                searchQuery = input ? input.value.trim().toLowerCase() : '';
            }

            const rows = document.querySelectorAll('tbody tr.peserta-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const rowClassId = String(row.getAttribute('data-id-kelas') || '');
                const rowNama = row.getAttribute('data-nama') || '';
                const rowNis = row.getAttribute('data-nis') || '';

                const matchClass = (currentSelectedClassId === 'all' || rowClassId === currentSelectedClassId);
                const matchSearch = (!searchQuery || rowNama.includes(searchQuery) || rowNis.includes(searchQuery));

                if (matchClass && matchSearch) {
                    row.style.display = '';
                    visibleCount++;
                    const numEl = row.querySelector('.row-number');
                    if (numEl) numEl.textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            const noRow = document.getElementById('no-matching-row');
            if (noRow) {
                noRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }

            const descEl = document.getElementById('selected-class-desc');
            if (descEl) {
                if (currentSelectedClassId !== 'all') {
                    descEl.textContent = 'Menampilkan ' + visibleCount + ' peserta pada kelas ini.';
                } else {
                    descEl.textContent = 'Menampilkan ' + visibleCount + ' peserta dari seluruh kelas pelatihan.';
                }
            }
        }

        // Inisialisasi saat DOM siap: periksa URL param ?id_kelas=... atau localStorage
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            const paramClassId = urlParams.get('id_kelas');
            const savedClassId = localStorage.getItem('active_kbm_kelas');
            const savedClassName = localStorage.getItem('active_kbm_kelas_name');

            const targetClassId = paramClassId || savedClassId;

            if (targetClassId && targetClassId !== 'all') {
                const cardTarget = document.querySelector('.class-card[data-class-id="' + targetClassId + '"]');
                if (cardTarget) {
                    const cName = cardTarget.querySelector('.class-card-title')?.textContent?.trim() || savedClassName || 'Kelas';
                    selectClassCard(targetClassId, cName);
                }
            }
        });
    </script>
</body>
</html>