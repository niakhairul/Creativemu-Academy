<?php
$filters = $filters ?? ['keyword' => '', 'status' => '', 'id_kelas' => '', 'bulan' => ''];
$summary = $summary ?? ['total' => count($pendaftaran ?? []), 'menunggu' => 0, 'disetujui' => 0, 'ditolak' => 0];
$pagination = $pagination ?? ['offset' => 0, 'totalPages' => 1, 'page' => 1, 'perPage' => 10, 'totalRows' => count($pendaftaran ?? [])];

if (!function_exists('validasi_status_badge')) {
    function validasi_status_badge(array $row): array
    {
        $text = strtolower(($row['status_pembayaran'] ?? '') . ' ' . ($row['status_pendaftaran'] ?? '') . ' ' . ($row['status'] ?? ''));
        if (str_contains($text, 'valid') || str_contains($text, 'disetujui')) return ['Disetujui', 'success'];
        if (str_contains($text, 'rejected') || str_contains($text, 'ditolak')) return ['Ditolak', 'danger'];
        return ['Menunggu', 'warning'];
    }
    function validasi_tanggal($value): string
    {
        return !empty($value) ? date('d M Y', strtotime($value)) : '-';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Mengunci skala zoom agar tidak ikut membesar secara berlebihan -->
    <meta name="viewport" content="width=device-width, initial-scale=0.85, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title ?? 'Validasi Pendaftaran'); ?> - Creativemu Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-bg: #1c1032;
            --sidebar-text: #c8bfe7;
            --primary: #794bc4;
            --primary-dark: #5931a0;
            --dark: #1e0f33;
            --soft: #f4f0fc;
            --border: #eadffb;
            --muted: #817796;
            --success-bg: #e1fcef;
            --success-text: #0d6832;
            --warning-bg: #fff5db;
            --warning-text: #8c6100;
            --danger-bg: #fde8e8;
            --danger-text: #9b1c1c;
        }
        
        * { box-sizing: border-box; }

        /* Mengatur skala dasar UI menjadi lebih ringkas & kompak (88%) */
        html {
            font-size: 88%;
            -webkit-text-size-adjust: 100%;
            touch-action: manipulation;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f8f6fc;
            color: #2f2442;
            overflow-x: hidden;
        }

        /* Layout Utama Desktop */
        @media (min-width: 992px) {
            #main-content {
                margin-left: 240px !important;
                width: calc(100% - 240px) !important;
                box-sizing: border-box;
                padding: 20px;
            }
        }

        /* Sidebar Ringkas */
        .zoom-wrapper { zoom: 0.9; }

        /* Main Section & Cards */
        .topbar, .panel, .metric, .validation-card {
            background: #fff;
            border: 1px solid rgba(121, 75, 196, 0.08);
            box-shadow: 0 4px 20px rgba(64, 36, 105, 0.03);
            transition: all 0.2s ease;
        }

        .topbar {
            border-radius: 16px;
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 16px;
        }
        .mobile-menu { display: none; }
        .page-title { margin: 0; color: var(--dark); font-weight: 800; font-size: 1.3rem; letter-spacing: -0.3px; }
        .page-subtitle { color: var(--muted); font-size: 0.8rem; margin: 3px 0 0; }
        
        /* Admin Profile */
        .admin-profile { display: flex; gap: 10px; align-items: center; min-width: max-content; background: var(--soft); padding: 6px 12px; border-radius: 50px; border: 1px solid var(--border); }
        .admin-profile img { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary); }
        .admin-profile h6 { margin: 0; color: var(--dark); font-weight: 700; font-size: 0.82rem; }
        .admin-profile small { color: var(--muted); font-size: 0.72rem; }

        /* Metrics */
        .metrics { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 16px; }
        .metric { border-radius: 16px; padding: 16px; position: relative; overflow: hidden; }
        .metric::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0;
            width: 4px;
            background: var(--primary);
            opacity: 0.5;
        }
        .metric-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            margin-bottom: 10px;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(121, 75, 196, 0.2);
        }
        .metric-label { color: var(--muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .metric-value { font-size: 1.35rem; font-weight: 800; color: var(--dark); margin-top: 2px; }

        /* Panel & Filter */
        .panel { border-radius: 16px; padding: 20px; margin-bottom: 16px; }
        .filter-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr 1fr auto; gap: 10px; align-items: end; }
        .form-label { font-size: 0.75rem; font-weight: 800; color: var(--dark); margin-bottom: 6px; letter-spacing: .3px; }
        .form-control, .form-select { border-radius: 10px; border-color: var(--border); min-height: 38px; font-size: 0.82rem; padding: 6px 12px; background-color: #fcfbfe; transition: all 0.2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.12); background-color: #fff; }
        
        /* Buttons */
        .btn-purple {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: 0;
            border-radius: 10px;
            min-height: 38px;
            padding: 6px 16px;
            font-weight: 700;
            font-size: 0.8rem;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.25);
            transition: all 0.2s;
        }
        .btn-purple:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 15px rgba(121, 75, 196, 0.35); }
        .btn-soft {
            background: var(--soft);
            color: var(--primary);
            border: 1px solid var(--border);
            border-radius: 10px;
            min-height: 38px;
            padding: 6px 14px;
            font-weight: 700;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        .btn-soft:hover { background: #ebdffe; color: var(--primary-dark); }

        /* Table Styling */
        .table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid var(--border); }
        .table { margin: 0; vertical-align: middle; font-size: 0.82rem; }
        .table thead th {
            background: #f5f0fd;
            color: var(--primary-dark);
            border-bottom: 2px solid var(--border);
            padding: 12px 14px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
            font-weight: 800;
        }
        .table tbody td { padding: 12px 14px; border-bottom: 1px solid #f2ecfb; color: #3b2d4c; background: #fff; }
        .table tbody tr:hover td { background-color: #fbf9ff; }
        
        /* Badges */
        .nis-badge {
            display: inline-flex;
            border-radius: 6px;
            background: #f4f0fc;
            color: #5931a0;
            border: 1px solid #ded0f7;
            padding: 3px 8px;
            font-weight: 800;
            font-size: 0.72rem;
            letter-spacing: .04em;
            font-family: Consolas, monospace;
        }
        .badge-status { border-radius: 30px; padding: 6px 12px; font-weight: 800; font-size: 0.72rem; letter-spacing: .3px; text-transform: uppercase; }
        .badge.bg-success { background-color: var(--success-bg) !important; color: var(--success-text) !important; border: 1px solid #a3e8c0; }
        .badge.bg-warning { background-color: var(--warning-bg) !important; color: var(--warning-text) !important; border: 1px solid #fce2a3; }
        .badge.bg-danger { background-color: var(--danger-bg) !important; color: var(--danger-text) !important; border: 1px solid #f5b7b7; }

        .actions { display: flex; gap: 6px; justify-content: flex-end; flex-wrap: wrap; }
        .actions .btn-sm { font-size: 0.75rem; padding: 5px 10px; border-radius: 8px; font-weight: 600; }

        /* Cards Mobile & Detail */
        .mobile-list { display: none; }
        .validation-card { border-radius: 14px; padding: 16px; margin-bottom: 12px; border: 1px solid var(--border); }
        .card-row { display: flex; justify-content: space-between; gap: 8px; border-top: 1px solid #f4effb; margin-top: 8px; padding-top: 8px; font-size: 0.8rem; }
        .card-row span:first-child { color: var(--muted); font-size: 0.75rem; font-weight: 800; }
        .card-row span:last-child { text-align: right; font-weight: 700; color: var(--dark); }
        
        .detail-section { margin-bottom: 16px; }
        .detail-section h6 { font-weight: 800; color: var(--dark); margin-bottom: 10px; font-size: 0.9rem; border-left: 3px solid var(--primary); padding-left: 8px; }
        .detail-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .detail-item { background: #fbf9ff; border: 1px solid #f2ecfb; border-radius: 10px; padding: 10px 12px; }
        .detail-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: .04em; color: var(--muted); font-weight: 800; margin-bottom: 2px; }
        .detail-value { color: var(--dark); font-weight: 700; font-size: 0.82rem; overflow-wrap: anywhere; }
        
        .empty-state { text-align: center; color: var(--muted); padding: 40px 20px; font-size: 0.85rem; }
        .modal-content { border: 0; border-radius: 18px; box-shadow: 0 15px 35px rgba(30, 15, 51, 0.15); overflow: hidden; }
        .offcanvas { background: var(--sidebar-bg); color: var(--sidebar-text); }

        /* Media Queries */
        @media(max-width: 1200px) {
            .filter-grid { grid-template-columns: repeat(2, minmax(0,1fr)); }
            .filter-actions { grid-column: 1/-1; display: flex; gap: 8px; }
        }
        @media(max-width: 1100px) {
            .metrics { grid-template-columns: repeat(2, minmax(0,1fr)); }
        }
        @media(max-width: 768px) {
            .zoom-wrapper { zoom: 1; } /* Kembali normal di mobile agar pembacaan nyaman */
            .mobile-menu { display: inline-flex; }
            .topbar { align-items: flex-start; flex-direction: column; border-radius: 14px; padding: 14px; position: relative; }
            .admin-profile { width: 100%; justify-content: flex-start; }
            .metrics, .filter-grid { grid-template-columns: 1fr; }
            .filter-actions { display: grid; grid-template-columns: 1fr 1fr; }
            .desktop-table { display: none; }
            .mobile-list { display: block; }
            .panel { padding: 14px; border-radius: 14px; }
            .detail-grid { grid-template-columns: 1fr; }
            .actions { justify-content: stretch; }
            .actions .btn { width: 100%; }
            .offcanvas .nav-link { color: var(--sidebar-text); }
        }
        @media(max-width: 430px) {
            .filter-actions { grid-template-columns: 1fr; }
            .metric-value { font-size: 1.2rem; }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>
    <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

<div id="main-content">
    <div class="zoom-wrapper">
    <section class="topbar">
        <div class="d-flex align-items-start gap-3">
            <div>
                <h1 class="page-title">Validasi Pendaftaran</h1>
                <p class="page-subtitle">Setujui pembayaran dan pendaftaran peserta. NIS dibuat otomatis hanya saat disetujui.</p>
            </div>
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
            <div>
                <h6><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6>
                <small>Administrator</small>
            </div>
        </div>
    </div>
    </section>

    <?php foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $flash => $type): ?>
        <?php if (session()->getFlashdata($flash)): ?>
            <div class="alert alert-<?= $type; ?> border-0 rounded-3 py-2 px-3 mb-3 small shadow-sm"><?= session()->getFlashdata($flash); ?></div>
        <?php endif; ?>
    <?php endforeach; ?>

    <section class="metrics">
        <div class="metric"><div class="metric-icon"><i class="fas fa-file-signature"></i></div><div class="metric-label">Total Data</div><div class="metric-value"><?= number_format((int) $summary['total']); ?></div></div>
        <div class="metric"><div class="metric-icon"><i class="fas fa-clock"></i></div><div class="metric-label">Menunggu</div><div class="metric-value"><?= number_format((int) $summary['menunggu']); ?></div></div>
        <div class="metric"><div class="metric-icon"><i class="fas fa-check"></i></div><div class="metric-label">Disetujui</div><div class="metric-value"><?= number_format((int) $summary['disetujui']); ?></div></div>
        <div class="metric"><div class="metric-icon"><i class="fas fa-xmark"></i></div><div class="metric-label">Ditolak</div><div class="metric-value"><?= number_format((int) $summary['ditolak']); ?></div></div>
    </section>

    <!-- FORM FILTER BULAN -->
    <section class="panel">
        <form action="<?= base_url('admin/validasi'); ?>" method="get" class="filter-grid">
            <div>
                <label class="form-label">Search</label>
                <input type="search" name="keyword" class="form-control" placeholder="Cari nama, NIS, WhatsApp, email, kelas..." value="<?= esc($filters['keyword'] ?? ''); ?>">
            </div>
            <div>
                <label class="form-label">Kelas</label>
                <select name="id_kelas" class="form-select">
                    <option value="">Semua kelas</option>
                    <?php foreach (($kelasList ?? []) as $kelas): ?>
                        <option value="<?= esc($kelas['id_kelas']); ?>" <?= ((string) ($filters['id_kelas'] ?? '') === (string) $kelas['id_kelas']) ? 'selected' : ''; ?>><?= esc($kelas['nama_kelas']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    <?php foreach (['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'] as $value=>$label): ?>
                        <option value="<?= $value; ?>" <?= (($filters['status'] ?? '') === $value) ? 'selected' : ''; ?>><?= $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="">Semua Bulan</option>
                    <?php 
                    $listBulan = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni', 
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ];
                    
                    foreach ($listBulan as $num => $namaBulan):
                        $selected = (($filters['bulan'] ?? '') === $num) ? 'selected' : '';
                    ?>
                        <option value="<?= $num; ?>" <?= $selected; ?>><?= $namaBulan; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-actions">
                <button class="btn btn-purple" type="submit"><i class="fas fa-magnifying-glass me-1"></i>Filter</button>
                <a href="<?= base_url('admin/validasi'); ?>" class="btn btn-soft"><i class="fas fa-rotate-left me-1"></i>Reset</a>
            </div>
        </form>
    </section>

    <section class="panel">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h2 class="h6 fw-bold mb-0" style="color:var(--dark)">Daftar Validasi</h2>
                <div class="text-muted" style="font-size:0.75rem">Approve membuat NIS format YYMMNNNN. Reject tidak membuat NIS.</div>
            </div>
        </div>
        <div class="desktop-table table-wrap">
            <table class="table table-hover">
                <thead>
                    <tr><th>No</th><th>Peserta</th><th>Kelas</th><th>Tanggal Daftar</th><th>Bukti</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php if (!empty($pendaftaran)): ?>
                        <?php $no = $pagination['offset'] + 1; foreach ($pendaftaran as $row): ?>
                            <?php 
                                [$statusLabel,$statusClass]=validasi_status_badge($row); 
                                $modalId='validasiDetail'.(int)$row['id_pendaftaran']; 
                                $isApproved=$statusLabel==='Disetujui'; 
                                $isRejected=$statusLabel==='Ditolak'; 
                                
                                $metodeBayar = strtolower($row['metode_pembayaran'] ?? $row['metode_pembelajaran'] ?? '');
                                $isCod = str_contains($metodeBayar, 'cod') || str_contains($metodeBayar, 'tempat') || str_contains($metodeBayar, 'tunai');
                            ?>
                            <tr>
                                <td class="fw-bold text-muted"><?= $no++; ?></td>
                                <td>
                                    <strong><?= esc($row['nama'] ?? '-'); ?></strong>
                                    <div class="text-muted" style="font-size:0.72rem"><?= esc($row['email'] ?? '-'); ?></div>
                                    <?= !empty($row['nis']) ? '<span class="nis-badge mt-1">'.esc($row['nis']).'</span>' : '<span class="badge bg-warning-subtle text-warning-emphasis rounded-pill mt-1" style="font-size:0.68rem">NIS belum dibuat</span>'; ?>
                                </td>
                                <td><?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></td>
                                <td><?= esc(validasi_tanggal($row['created_at'] ?? null)); ?></td>
                                <td>
                                    <?php if (!empty($row['bukti_pembayaran'])): ?>
                                        <a href="<?= base_url('uploads/bukti/' . $row['bukti_pembayaran']); ?>" target="_blank" class="btn btn-soft btn-sm"><i class="fas fa-receipt me-1"></i>Lihat</a>
                                    <?php elseif ($isCod): ?>
                                        <span class="badge bg-info-subtle text-info fw-bold px-2 py-1" style="font-size:0.7rem"><i class="fas fa-handshake me-1"></i>Bayar di Tempat (COD)</span>
                                    <?php else: ?>
                                        <span class="text-muted small">Belum ada bukti</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-<?= $statusClass; ?> badge-status"><?= esc($statusLabel); ?></span></td>
                                <td>
                                    <div class="actions">
                                        <button class="btn btn-soft btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>"><i class="fas fa-eye me-1"></i>Detail</button>
                                        <?php if (!$isApproved): ?>
                                            <a class="btn btn-success btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/setuju'); ?>" onclick="return confirm('Setujui pendaftaran ini dan buat NIS otomatis?')"><i class="fas fa-check me-1"></i>Setujui</a>
                                        <?php endif; ?>
                                        <?php if (!$isRejected): ?>
                                            <a class="btn btn-danger btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/tolak'); ?>" onclick="return confirm('Tolak pendaftaran ini? NIS tidak akan dibuat.')"><i class="fas fa-xmark me-1"></i>Tolak</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7"><div class="empty-state"><i class="fas fa-inbox fa-3x mb-3 text-purple opacity-50"></i><div>Belum ada data validasi.</div></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mobile-list">
            <?php if (!empty($pendaftaran)): ?>
                <?php foreach ($pendaftaran as $row): ?>
                    <?php 
                        [$statusLabel,$statusClass]=validasi_status_badge($row); 
                        $modalId='validasiMobile'.(int)$row['id_pendaftaran']; 
                        $isApproved=$statusLabel==='Disetujui'; 
                        $isRejected=$statusLabel==='Ditolak'; 
                        $metodeBayar = strtolower($row['metode_pembayaran'] ?? $row['metode_pembelajaran'] ?? '');
                        $isCod = str_contains($metodeBayar, 'cod') || str_contains($metodeBayar, 'tempat') || str_contains($metodeBayar, 'tunai');
                    ?>
                    <article class="validation-card">
                        <div class="d-flex justify-content-between gap-2">
                            <div>
                                <strong><?= esc($row['nama'] ?? '-'); ?></strong>
                                <div class="text-muted" style="font-size:0.72rem"><?= esc($row['email'] ?? '-'); ?></div>
                            </div>
                            <span class="badge bg-<?= $statusClass; ?> badge-status align-self-start"><?= esc($statusLabel); ?></span>
                        </div>
                        <div class="card-row"><span>NIS</span><span><?= esc($row['nis'] ?: 'Belum dibuat'); ?></span></div>
                        <div class="card-row"><span>Kelas</span><span><?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></span></div>
                        <div class="card-row"><span>Metode / Bukti</span><span><?= $isCod ? 'Bayar di Tempat (COD)' : (!empty($row['bukti_pembayaran']) ? 'Ada Bukti Transfer' : 'Tidak ada'); ?></span></div>
                        <div class="card-row"><span>Tanggal Daftar</span><span><?= esc(validasi_tanggal($row['created_at'] ?? null)); ?></span></div>
                        <div class="actions mt-3">
                            <button class="btn btn-soft btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>">Detail</button>
                            <?php if (!$isApproved): ?><a class="btn btn-success btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/setuju'); ?>" onclick="return confirm('Setujui pendaftaran ini dan buat NIS otomatis?')">Setujui</a><?php endif; ?>
                            <?php if (!$isRejected): ?><a class="btn btn-danger btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/tolak'); ?>" onclick="return confirm('Tolak pendaftaran ini?')">Tolak</a><?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state"><i class="fas fa-inbox fa-3x mb-3 text-purple opacity-50"></i><div>Belum ada data validasi.</div></div>
            <?php endif; ?>
        </div>

        <!-- NAVIGASI PAGINASI (1 2 dst) -->
        <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-3 px-2 flex-wrap gap-2">
                <div class="text-muted" style="font-size: 0.78rem;">
                    Menampilkan data ke-<?= $pagination['offset'] + 1; ?> sampai <?= min($pagination['offset'] + $pagination['perPage'], $pagination['totalRows']); ?> dari total <?= $pagination['totalRows']; ?> data
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Tombol Previous -->
                        <li class="page-item <?= ($pagination['page'] <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?= base_url('admin/validasi?' . http_build_query(array_merge($filters, ['page' => $pagination['page'] - 1]))); ?>">Sebelumnya</a>
                        </li>

                        <!-- Nomor Halaman -->
                        <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                            <li class="page-item <?= ($pagination['page'] == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?= base_url('admin/validasi?' . http_build_query(array_merge($filters, ['page' => $i]))); ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Tombol Next -->
                        <li class="page-item <?= ($pagination['page'] >= $pagination['totalPages']) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?= base_url('admin/validasi?' . http_build_query(array_merge($filters, ['page' => $pagination['page'] + 1]))); ?>">Berikutnya</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </section>
    </div>
</div>
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <img src="<?= base_url('assets/img/logo_creativemu_admin.png'); ?>" alt="Creativemu Academy" style="width:auto;max-width:180px;height:auto;max-height:50px;object-fit:contain;background:#fff;padding:6px 12px;border-radius:10px">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="nav flex-column gap-1">
            <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">Dashboard</a>
            <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">Data Peserta</a>
            <a href="<?= base_url('admin/validasi'); ?>" class="nav-link active">Validasi</a>
            <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">Master Kelas</a>
            <a href="<?= base_url('admin/mentor'); ?>" class="nav-link">Instruktur</a>
            <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">Laporan</a>
            <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">Logout</a>
        </nav>
    </div>
</div>

<?php foreach (($pendaftaran ?? []) as $row): ?>
    <?php foreach (['validasiDetail','validasiMobile'] as $prefix): ?>
        <?php 
            $modalId=$prefix.(int)$row['id_pendaftaran']; 
            [$statusLabel,$statusClass]=validasi_status_badge($row); 
            $metodeBayar = strtolower($row['metode_pembayaran'] ?? $row['metode_pembelajaran'] ?? '');
            $isCod = str_contains($metodeBayar, 'cod') || str_contains($metodeBayar, 'tempat') || str_contains($metodeBayar, 'tunai');
        ?>
        <div class="modal fade" id="<?= $modalId; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header py-3 px-4 bg-light">
                        <div>
                            <h5 class="modal-title fw-bold fs-6">Detail Validasi Pendaftaran</h5>
                            <div class="text-muted" style="font-size:0.75rem"><?= esc($row['nama'] ?? '-'); ?> | <?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="detail-section">
                            <h6>Data Peserta</h6>
                            <div class="detail-grid">
                                <div class="detail-item"><div class="detail-label">Nama</div><div class="detail-value"><?= esc($row['nama'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">NIS</div><div class="detail-value"><?= esc($row['nis'] ?: 'Belum dibuat'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Email</div><div class="detail-value"><?= esc($row['email'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">No. WhatsApp</div><div class="detail-value"><?= esc($row['no_hp'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Jenis Kelamin</div><div class="detail-value"><?= esc($row['jenis_kelamin'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Pendidikan</div><div class="detail-value"><?= esc($row['pendidikan_terakhir'] ?? '-'); ?></div></div>
                                <div class="detail-item" style="grid-column: span 2;"><div class="detail-label">Alamat</div><div class="detail-value"><?= esc($row['alamat'] ?? '-'); ?></div></div>
                            </div>
                        </div>
                        <div class="detail-section">
                            <h6>Data Pendaftaran</h6>
                            <div class="detail-grid">
                                <div class="detail-item"><div class="detail-label">Kelas</div><div class="detail-value"><?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Jenis Kelas</div><div class="detail-value"><?= esc($row['jenis_kelas'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Metode Pembelajaran</div><div class="detail-value"><?= esc($row['metode_pembelajaran'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Lokasi</div><div class="detail-value"><?= esc($row['lokasi_pelatihan'] ?? $row['lokasi_media'] ?? '-'); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Tanggal Mulai</div><div class="detail-value"><?= esc(validasi_tanggal($row['tanggal_mulai_kelas'] ?? $row['tanggal_mulai_master'] ?? null)); ?></div></div>
                                <div class="detail-item"><div class="detail-label">Tanggal Daftar</div><div class="detail-value"><?= esc(validasi_tanggal($row['created_at'] ?? null)); ?></div></div>
                                <div class="detail-item" style="grid-column: span 2;">
                                    <div class="detail-label">Bukti Pembayaran / Metode</div>
                                    <div class="detail-value">
                                        <?php if (!empty($row['bukti_pembayaran'])): ?>
                                            <a href="<?= base_url('uploads/bukti/' . $row['bukti_pembayaran']); ?>" target="_blank" class="text-decoration-none fw-bold text-primary"><i class="fas fa-external-link-alt me-1"></i> Lihat bukti transfer</a>
                                        <?php elseif ($isCod): ?>
                                            <span class="text-primary fw-bold"><i class="fas fa-handshake me-1"></i> Tunai / Bayar di Tempat (COD) - Kwitansi fisik</span>
                                        <?php else: ?>
                                            Tidak ada bukti pembayaran
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="detail-item" style="grid-column: span 2;"><div class="detail-label">Status Validasi</div><div class="detail-value"><span class="badge bg-<?= $statusClass; ?> badge-status"><?= esc($statusLabel); ?></span></div></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-3 px-4 bg-light">
                        <button class="btn btn-soft btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <?php if ($statusLabel !== 'Disetujui'): ?>
                            <a class="btn btn-success btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/setuju'); ?>" onclick="return confirm('Setujui pendaftaran ini dan buat NIS otomatis?')">Setujui</a>
                        <?php endif; ?>
                        <?php if ($statusLabel !== 'Ditolak'): ?>
                            <a class="btn btn-danger btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/tolak'); ?>" onclick="return confirm('Tolak pendaftaran ini?')">Tolak</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>