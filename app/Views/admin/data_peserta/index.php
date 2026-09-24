<?php
$summary = $summary ?? ['total' => 0, 'disetujui' => 0, 'menunggu' => 0, 'ditolak' => 0];
$pagination = $pagination ?? ['page' => 1, 'perPage' => 10, 'totalRows' => count($peserta ?? []), 'totalPages' => 1, 'offset' => 0];
$queryBase = $_GET ?? [];
unset($queryBase['page']);

if (!function_exists('admin_status_badge')) {
    function admin_status_badge(array $row): array
    {
        $text = strtolower(($row['status_pembayaran'] ?? '') . ' ' . ($row['status_pendaftaran'] ?? '') . ' ' . ($row['status'] ?? ''));
        if (str_contains($text, 'valid') || str_contains($text, 'disetujui')) {
            return ['Disetujui', 'success'];
        }
        if (str_contains($text, 'rejected') || str_contains($text, 'ditolak')) {
            return ['Ditolak', 'danger'];
        }
        return ['Menunggu', 'warning'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Mencegah zoom / layar bergeser berlebihan -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title ?? 'Data Peserta'); ?> - Creativemu Academy</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #1c1032;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
            --border-color: #eadffb;
        }

        html, body {
            touch-action: pan-x pan-y;
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            color: #2f2442;
            overflow-x: hidden;
            margin: 0;
            font-size: 14px; /* Ukuran dasar compact */
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f7f5fd; }
        ::-webkit-scrollbar-thumb { background: #b293f0; border-radius: 10px; }

        /* --- Sidebar Styling --- */
        #sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(121, 75, 196, 0.08);
            overflow-y: auto;
        }

        #sidebar .sidebar-header {
            padding: 20px 15px 15px 15px;
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
        }

        #sidebar .logo-card {
            background-color: #ffffff;
            border-radius: 14px;
            padding: 10px 14px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 85%;
        }

        #sidebar .logo-card img {
            max-width: 100%;
            height: 45px;
            object-fit: contain;
        }

        #sidebar .panel-title {
            color: #a497c6;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-top: 12px;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        #sidebar .nav { padding: 12px 10px; }
        #sidebar .nav-item { margin-bottom: 4px; }
        
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 9px 14px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.85rem;
        }

        #sidebar .nav-link i {
            margin-right: 10px;
            font-size: 0.95rem;
            width: 18px;
            text-align: center;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
            font-weight: 600;
        }

        #sidebar .nav-link.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #ff6b6b !important;
        }

        /* --- Main Content Area --- */
        #main-content {
            margin-left: 240px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        /* Topbar */
        .top-navbar {
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
        }

        .dash-header h3 {
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 1.25rem;
            margin: 0;
        }
        
        .dash-header p {
            color: #8c83a5;
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-purple);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.88rem;
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.72rem;
        }

        /* Card / Panel Compact */
        .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr auto;
            gap: 12px;
            align-items: end;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.78rem;
            margin-bottom: 4px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 7px 12px;
            border: 1.5px solid #e2d9f3;
            font-size: 0.82rem;
            background-color: #fcfbfe;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
            background-color: #ffffff;
        }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.82rem;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.25);
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            opacity: 0.95;
            color: #ffffff;
        }

        .btn-soft {
            background: var(--light-purple);
            color: var(--primary-purple);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.82rem;
            transition: all 0.2s ease;
        }

        .btn-soft:hover {
            background: #ede6fc;
            color: var(--dark-purple);
        }

        /* --- Table Styling --- */
        .table-custom {
            vertical-align: middle;
            font-size: 0.82rem;
        }

        .table-custom th {
            background-color: var(--light-purple);
            color: var(--dark-purple);
            font-weight: 700;
            padding: 10px 12px;
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.03em;
        }

        .table-custom td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0edf6;
            color: #4a4259;
        }

        .nis-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 6px;
            background: var(--light-purple);
            color: #5931a0;
            border: 1px solid #ded0f7;
            padding: 3px 8px;
            font-weight: 700;
            font-family: Consolas, monospace;
            font-size: 0.78rem;
        }

        .badge-status {
            border-radius: 20px;
            padding: 4px 10px;
            font-weight: 600;
            font-size: 0.72rem;
        }

        .mobile-list { display: none; }

        .participant-card {
            background: #ffffff;
            border: 1px solid rgba(121,75,196,0.08);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .card-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            border-top: 1px solid #f2ecfb;
            margin-top: 8px;
            padding-top: 8px;
        }

        .card-row span:first-child { color: #8c83a5; font-size: 0.75rem; font-weight: 600; }
        .card-row span:last-child { font-weight: 600; color: var(--dark-purple); font-size: 0.8rem; }

        .pagination .page-link {
            border-color: var(--border-color);
            color: var(--primary-purple);
            border-radius: 6px;
            margin: 0 2px;
            padding: 5px 10px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .pagination .active .page-link {
            background: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        /* Modal Customization */
        .modal-content {
            border-radius: 14px;
            border: none;
            box-shadow: 0 10px 30px rgba(30, 15, 51, 0.15);
        }

        .modal-header {
            background-color: var(--light-purple);
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(121, 75, 196, 0.08);
        }

        .modal-body { padding: 18px 20px; font-size: 0.85rem; }

        .modal-footer {
            background-color: #fcfbfe;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
            padding: 10px 20px;
            border-top: 1px solid rgba(121, 75, 196, 0.08);
        }

        /* --- Responsive View --- */
        @media (max-width: 992px) {
            #sidebar { width: 70px; }
            #sidebar .logo-card { padding: 6px; width: 100%; }
            #sidebar .logo-card img { height: 30px; }
            #sidebar .panel-title, #sidebar span { display: none; }
            #sidebar .sidebar-header { padding: 15px 8px; }
            #sidebar .nav { padding: 10px 6px; }
            #sidebar .nav-link { justify-content: center; padding: 9px; }
            #sidebar .nav-link i { margin-right: 0; }
            #main-content { margin-left: 70px; padding: 15px; }
            .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .filter-actions { grid-column: 1/-1; display: flex; gap: 8px; }
        }

        @media (max-width: 576px) {
            #main-content { padding: 10px; }
            .top-navbar {
                padding: 12px 15px;
                margin-bottom: 15px;
                gap: 10px;
                flex-direction: column;
                align-items: flex-start;
            }
            .top-navbar > .d-flex { width: 100%; justify-content: flex-end; }
            .dash-header h3 { font-size: 1.1rem; }
            .dash-header p { font-size: 0.75rem; }
            .filter-grid { grid-template-columns: 1fr; }
            .desktop-table { display: none; }
            .mobile-list { display: block; }
            .content-card { padding: 14px; border-radius: 12px; }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="logo-card">
                <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy">
            </div>
            <div class="panel-title">PANEL ADMIN</div>
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
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link active">
                    <i class="fas fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link">
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
                    <i class="fas fa-certificate"></i> <span>Sertifikat</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
                    <i class="fas fa-file-lines"></i> <span>Laporan</span>
                </a>
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

    <!-- === MAIN CONTENT === -->
    <div id="main-content">
        
        <!-- === TOP NAVBAR === -->
        <div class="top-navbar">
            <div class="dash-header">
                <h3>Daftar Peserta</h3>
                <p>Data peserta pelatihan terintegrasi langsung dengan pendaftaran kelas Creativemu Academy.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="admin-profile">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                    <div class="admin-info">
                        <h6><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <?php foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $flash => $type): ?>
            <?php if (session()->getFlashdata($flash)): ?>
                <div class="alert alert-<?= $type; ?> alert-dismissible fade show rounded-3 shadow-sm mb-3 py-2 px-3 small" role="alert">
                    <?= session()->getFlashdata($flash); ?>
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Panel Filter -->
        <div class="content-card">
            <form action="<?= base_url('admin/data-peserta'); ?>" method="get" class="filter-grid">
                <div>
                    <label class="form-label">Search</label>
                    <input type="search" name="keyword" class="form-control" placeholder="Cari NIS, nama, WhatsApp, email..." value="<?= esc($keyword ?? ''); ?>">
                </div>
                <div>
                    <label class="form-label">Kelas</label>
                    <select name="id_kelas" class="form-select">
                        <option value="">Semua kelas</option>
                        <?php foreach (($kelasList ?? []) as $kelas): ?>
                            <option value="<?= esc($kelas['id_kelas']); ?>" <?= ((string) ($selectedKelas ?? '') === (string) $kelas['id_kelas']) ? 'selected' : ''; ?>>
                                <?= esc($kelas['nama_kelas']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua status</option>
                        <?php foreach (['disetujui'=>'Disetujui','menunggu'=>'Menunggu','ditolak'=>'Ditolak'] as $value=>$label): ?>
                            <option value="<?= $value; ?>" <?= (($selectedStatus ?? '') === $value) ? 'selected' : ''; ?>><?= $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-actions d-flex gap-2">
                    <button class="btn btn-purple" type="submit"><i class="fas fa-magnifying-glass me-1"></i> Filter</button>
                    <a href="<?= base_url('admin/data-peserta'); ?>" class="btn btn-soft text-center"><i class="fas fa-rotate-left me-1"></i> Reset</a>
                </div>
            </form>
        </div>

        <!-- Tabel Peserta -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
                <div>
                    <h6 class="fw-bold mb-0" style="color:var(--dark-purple);"><i class="fas fa-table me-1 text-purple"></i> Tabel Data Peserta</h6>
                    <small class="text-muted">Menampilkan <?= number_format(count($peserta ?? [])); ?> dari <?= number_format((int) $pagination['totalRows']); ?> data</small>
                </div>
            </div>

            <!-- Display Desktop Table -->
            <div class="desktop-table table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>NIS</th>
                            <th>Nama Peserta</th>
                            <th>Kelas</th>
                            <th>No. WhatsApp</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($peserta)): ?>
                        <?php foreach ($peserta as $index => $item): ?>
                            <?php [$statusLabel, $statusClass] = admin_status_badge($item); $modalId = 'detailPeserta' . (int) $item['id_pendaftaran']; ?>
                            <tr>
                                <td class="fw-semibold text-muted"><?= (int) $pagination['offset'] + $index + 1; ?></td>
                                <td><?= !empty($item['resolved_nis']) ? '<span class="nis-badge"><i class="fas fa-id-card"></i>' . esc($item['resolved_nis']) . '</span>' : '<span class="badge bg-warning-subtle text-warning border px-2 py-1" style="font-size: 0.7rem;">Belum ada NIS</span>'; ?></td>
                                <td>
                                    <strong style="color: var(--dark-purple);"><?= esc($item['nama_lengkap'] ?? '-'); ?></strong>
                                    <div class="text-muted small" style="font-size: 0.72rem;"><?= esc($item['email_terbaru'] ?? '-'); ?></div>
                                </td>
                                <td><?= esc($item['nama_kelas'] ?? $item['pilihan_kelas'] ?? '-'); ?></td>
                                <td><i class="fab fa-whatsapp text-success me-1"></i><?= esc($item['no_hp_terbaru'] ?? '-'); ?></td>
                                <td><span class="badge bg-<?= $statusClass; ?> badge-status"><?= esc($statusLabel); ?></span></td>
                                <td class="text-end">
                                    <button class="btn btn-soft btn-sm px-2 py-1" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>" style="font-size: 0.75rem;">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted small">Belum ada data peserta yang ditemukan.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Display Mobile List -->
            <div class="mobile-list">
                <?php if (!empty($peserta)): ?>
                    <?php foreach ($peserta as $index => $item): ?>
                        <?php [$statusLabel, $statusClass] = admin_status_badge($item); $modalId = 'detailPesertaMobile' . (int) $item['id_pendaftaran']; ?>
                        <article class="participant-card">
                            <div class="d-flex justify-content-between gap-2">
                                <div>
                                    <strong><?= esc($item['nama_lengkap'] ?? '-'); ?></strong>
                                    <div class="text-muted small" style="font-size: 0.72rem;"><?= esc($item['email_terbaru'] ?? '-'); ?></div>
                                </div>
                                <span class="badge bg-<?= $statusClass; ?> badge-status align-self-start"><?= esc($statusLabel); ?></span>
                            </div>
                            <div class="card-row"><span>NIS</span><span><?= esc($item['resolved_nis'] ?: 'Belum ada NIS'); ?></span></div>
                            <div class="card-row"><span>Kelas</span><span><?= esc($item['nama_kelas'] ?? $item['pilihan_kelas'] ?? '-'); ?></span></div>
                            <div class="card-row"><span>WhatsApp</span><span><?= esc($item['no_hp_terbaru'] ?? '-'); ?></span></div>
                            <button class="btn btn-purple w-100 mt-2 py-1" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </button>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted small">Belum ada data peserta yang ditemukan.</div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if (($pagination['totalPages'] ?? 1) > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination justify-content-end mb-0 flex-wrap">
                        <?php for ($p = 1; $p <= $pagination['totalPages']; $p++): $query = array_merge($queryBase, ['page' => $p]); ?>
                            <li class="page-item <?= $p === (int) $pagination['page'] ? 'active' : ''; ?>">
                                <a class="page-link" href="<?= base_url('admin/data-peserta') . '?' . http_build_query($query); ?>"><?= $p; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>

    <!-- === MODALS DETAIL PESERTA === -->
    <?php foreach (($peserta ?? []) as $item): ?>
        <?php foreach (['detailPeserta', 'detailPesertaMobile'] as $prefix): 
            $modalId = $prefix . (int) $item['id_pendaftaran']; 
            [$statusLabel, $statusClass] = admin_status_badge($item); 
        ?>
            <div class="modal fade" id="<?= $modalId; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h6 class="modal-title fw-bold mb-0">Detail Data Peserta</h6>
                                <small class="text-muted"><?= esc($item['nama_lengkap'] ?? '-'); ?></small>
                            </div>
                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-md-6"><p class="mb-1"><strong>NIS:</strong> <?= esc($item['resolved_nis'] ?: 'Belum ada NIS'); ?></p></div>
                                <div class="col-md-6"><p class="mb-1"><strong>Status:</strong> <span class="badge bg-<?= $statusClass; ?>"><?= esc($statusLabel); ?></span></p></div>
                                <div class="col-md-6"><p class="mb-1"><strong>Nama Lengkap:</strong> <?= esc($item['nama_lengkap'] ?? '-'); ?></p></div>
                                <div class="col-md-6"><p class="mb-1"><strong>Email:</strong> <?= esc($item['email_terbaru'] ?? '-'); ?></p></div>
                                <div class="col-md-6"><p class="mb-1"><strong>WhatsApp:</strong> <?= esc($item['no_hp_terbaru'] ?? '-'); ?></p></div>
                                <div class="col-md-6"><p class="mb-1"><strong>Kelas:</strong> <?= esc($item['nama_kelas'] ?? $item['pilihan_kelas'] ?? '-'); ?></p></div>
                                <div class="col-md-6"><p class="mb-1"><strong>Jenis Kelamin:</strong> <?= esc($item['gender_terbaru'] ?? '-'); ?></p></div>
                                <div class="col-12"><p class="mb-0"><strong>Alamat:</strong> <?= esc($item['alamat'] ?? '-'); ?></p></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                            <a href="<?= base_url('admin/validasi'); ?>?keyword=<?= urlencode($item['resolved_nis'] ?: ($item['nama_lengkap'] ?? '')); ?>" class="btn btn-sm btn-purple rounded-pill px-3">
                                <i class="fas fa-clipboard-check me-1"></i> Buka Validasi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>