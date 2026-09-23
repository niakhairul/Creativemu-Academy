<?php
$filters = $filters ?? ['keyword' => '', 'status' => '', 'id_kelas' => '', 'bulan' => ''];
$summary = $summary ?? ['total' => count($pendaftaran ?? []), 'menunggu' => 0, 'disetujui' => 0, 'ditolak' => 0];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Validasi Pendaftaran'); ?> - Creativemu Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--sidebar-bg:#22133c;--sidebar-text:#c8bfe7;--primary:#794bc4;--primary-dark:#5931a0;--dark:#1e0f33;--soft:#f4f0fc;--border:#eadffb;--muted:#817796}*{box-sizing:border-box}body{margin:0;font-family:'Poppins',sans-serif;background:#f7f5fd;color:#2f2442;overflow-x:hidden}.sidebar{width:275px;background:var(--sidebar-bg);position:fixed;inset:0 auto 0 0;z-index:1040;overflow-y:auto;box-shadow:8px 0 28px rgba(34,19,60,.12)}.sidebar-header{padding:22px 18px;background:rgba(0,0,0,.22);text-align:center}.sidebar-header img{width:230px;max-width:100%;height:92px;object-fit:cover;border-radius:10px}.sidebar .nav{padding:18px 12px 28px}.sidebar .nav-link{display:flex;align-items:center;gap:12px;color:var(--sidebar-text);border-radius:12px;padding:12px 16px;margin-bottom:6px;font-weight:500;font-size:.9rem}.sidebar .nav-link i{width:21px;text-align:center}.sidebar .nav-link:hover,.sidebar .nav-link.active{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff}.main{margin-left:275px;width:calc(100% - 275px);padding:30px}.topbar,.panel,.metric,.validation-card{background:#fff;border:1px solid rgba(121,75,196,.08);box-shadow:0 14px 34px rgba(64,36,105,.06)}.topbar{border-radius:18px;padding:22px 26px;display:flex;justify-content:space-between;gap:18px;align-items:center;margin-bottom:20px}.mobile-menu{display:none}.page-title{margin:0;color:var(--dark);font-weight:800;font-size:clamp(1.25rem,2vw,1.75rem)}.page-subtitle{color:var(--muted);font-size:.9rem;margin:6px 0 0}.admin-profile{display:flex;gap:12px;align-items:center;min-width:max-content}.admin-profile img{width:46px;height:46px;border-radius:50%;object-fit:cover;border:2px solid var(--primary)}.admin-profile h6{margin:0;color:var(--dark);font-weight:700;font-size:.9rem}.admin-profile small{color:var(--muted)}.metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:18px}.metric{border-radius:16px;padding:17px}.metric-icon{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;color:#fff;background:linear-gradient(135deg,var(--primary),var(--primary-dark));margin-bottom:12px}.metric-label{color:var(--muted);font-size:.78rem;font-weight:700}.metric-value{font-size:1.45rem;font-weight:800;color:var(--dark)}.panel{border-radius:18px;padding:20px;margin-bottom:18px}
        
        /* Disesuaikan grid-nya agar memuat kolom tambahan (Search, Kelas, Status, Bulan, Tombol) */
        .filter-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr 1fr auto;gap:12px;align-items:end}
        
        .form-label{font-size:.78rem;font-weight:800;color:var(--dark)}.form-control,.form-select{border-radius:12px;border-color:var(--border);min-height:44px}.btn-purple{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:0;border-radius:12px;min-height:42px;padding:10px 16px;font-weight:700}.btn-purple:hover{color:#fff}.btn-soft{background:var(--soft);color:var(--primary);border:1px solid var(--border);border-radius:12px;min-height:42px;padding:10px 15px;font-weight:700}.table-wrap{overflow-x:auto}.table{margin:0;vertical-align:middle}.table thead th{background:#faf8ff;color:var(--primary-dark);border-bottom:1px solid var(--border);padding:14px;font-size:.78rem;text-transform:uppercase;white-space:nowrap}.table tbody td{padding:15px;border-bottom:1px solid #f0eafb;color:#443652}.nis-badge{display:inline-flex;border-radius:999px;background:#f4f0fc;color:#5931a0;border:1px solid #ded0f7;padding:7px 11px;font-weight:800;letter-spacing:.04em;font-family:Consolas,monospace}.badge-status{border-radius:999px;padding:7px 11px;font-weight:800}.actions{display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap}.mobile-list{display:none}.validation-card{border-radius:16px;padding:16px;margin-bottom:13px}.card-row{display:flex;justify-content:space-between;gap:12px;border-top:1px solid #f0eafb;margin-top:10px;padding-top:10px}.card-row span:first-child{color:var(--muted);font-size:.78rem;font-weight:800}.card-row span:last-child{text-align:right;font-weight:700;color:var(--dark)}.detail-section{margin-bottom:18px}.detail-section h6{font-weight:800;color:var(--dark);margin-bottom:12px}.detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.detail-item{background:#fcfbff;border:1px solid #f0eafb;border-radius:12px;padding:12px}.detail-label{font-size:.73rem;text-transform:uppercase;letter-spacing:.03em;color:var(--muted);font-weight:800}.detail-value{color:var(--dark);font-weight:700;overflow-wrap:anywhere}.empty-state{text-align:center;color:var(--muted);padding:38px 10px}.modal-content{border:0;border-radius:18px}.offcanvas{background:var(--sidebar-bg);color:var(--sidebar-text)}
        
        @media(max-width:1200px){.filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.filter-actions{grid-column:1/-1;display:flex;gap:10px}}
        @media(max-width:1100px){.metrics{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:768px){.sidebar{display:none}.sidebar.show{display:block}.mobile-menu{display:inline-flex}.main{margin-left:0;width:100%;padding:16px}.topbar{align-items:flex-start;flex-direction:column;border-radius:14px;padding:16px;padding-top:58px;position:relative}.admin-profile{width:100%}.metrics,.filter-grid{grid-template-columns:1fr}.filter-actions{display:grid;grid-template-columns:1fr 1fr}.desktop-table{display:none}.mobile-list{display:block}.panel{padding:15px;border-radius:14px}.detail-grid{grid-template-columns:1fr}.actions{justify-content:stretch}.actions .btn{width:100%}.offcanvas .nav-link{color:var(--sidebar-text)}}
        @media(max-width:430px){.filter-actions{grid-template-columns:1fr}.metric-value{font-size:1.25rem}}
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>
<aside class="sidebar"><div class="sidebar-header"><img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy"></div><nav class="nav flex-column"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie"></i>Dashboard</a><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book"></i>Master Kelas</a><a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user"></i>Instruktur</a><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link"><i class="fas fa-users"></i>Data Peserta</a><a href="<?= base_url('admin/validasi'); ?>" class="nav-link active"><i class="fas fa-clipboard-check"></i>Validasi Pendaftaran</a><a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link"><i class="fas fa-book-open"></i>Buku Induk</a><a href="<?= base_url('admin/angket'); ?>" class="nav-link"><i class="fas fa-poll"></i>Angket</a><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-certificate"></i>Sertifikat</a><a href="<?= base_url('admin/laporan'); ?>" class="nav-link"><i class="fas fa-file-lines"></i>Laporan</a><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear"></i>Pengaturan</a><a href="<?= base_url('logout'); ?>" class="nav-link text-danger mt-2"><i class="fas fa-right-from-bracket"></i>Logout</a></nav></aside>

<main class="main">
    <section class="topbar"><div class="d-flex align-items-start gap-3"><div><h1 class="page-title">Validasi Pendaftaran</h1><p class="page-subtitle">Setujui pembayaran dan pendaftaran peserta. NIS dibuat otomatis hanya saat disetujui.</p></div></div><div class="admin-profile"><img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil"><div><h6><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6><small>Administrator</small></div></div></section>

    <?php foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $flash => $type): ?><?php if (session()->getFlashdata($flash)): ?><div class="alert alert-<?= $type; ?> border-0 rounded-4"><?= session()->getFlashdata($flash); ?></div><?php endif; ?><?php endforeach; ?>

    <section class="metrics"><div class="metric"><div class="metric-icon"><i class="fas fa-file-signature"></i></div><div class="metric-label">Total Data</div><div class="metric-value"><?= number_format((int) $summary['total']); ?></div></div><div class="metric"><div class="metric-icon"><i class="fas fa-clock"></i></div><div class="metric-label">Menunggu</div><div class="metric-value"><?= number_format((int) $summary['menunggu']); ?></div></div><div class="metric"><div class="metric-icon"><i class="fas fa-check"></i></div><div class="metric-label">Disetujui</div><div class="metric-value"><?= number_format((int) $summary['disetujui']); ?></div></div><div class="metric"><div class="metric-icon"><i class="fas fa-xmark"></i></div><div class="metric-label">Ditolak</div><div class="metric-value"><?= number_format((int) $summary['ditolak']); ?></div></div></section>

    <!-- FORM FILTER DENGAN DROPDOWN BULAN SAJA (JANUARI - DESEMBER) -->
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
                <button class="btn btn-purple" type="submit"><i class="fas fa-magnifying-glass me-2"></i>Filter</button>
                <a href="<?= base_url('admin/validasi'); ?>" class="btn btn-soft"><i class="fas fa-rotate-left me-2"></i>Reset</a>
            </div>
        </form>
    </section>

    <section class="panel"><div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3"><div><h2 class="h5 fw-bold mb-1" style="color:var(--dark)">Daftar Validasi</h2><div class="text-muted small">Approve membuat NIS format YYMMNNNN. Reject tidak membuat NIS.</div></div></div><div class="desktop-table table-wrap"><table class="table table-hover"><thead><tr><th>No</th><th>Peserta</th><th>Kelas</th><th>Tanggal Daftar</th><th>Bukti</th><th>Status</th><th class="text-end">Aksi</th></tr></thead><tbody><?php if (!empty($pendaftaran)): ?><?php $no=1; foreach ($pendaftaran as $row): ?><?php 
        [$statusLabel,$statusClass]=validasi_status_badge($row); 
        $modalId='validasiDetail'.(int)$row['id_pendaftaran']; 
        $isApproved=$statusLabel==='Disetujui'; 
        $isRejected=$statusLabel==='Ditolak'; 
        
        $metodeBayar = strtolower($row['metode_pembayaran'] ?? $row['metode_pembelajaran'] ?? '');
        $isCod = str_contains($metodeBayar, 'cod') || str_contains($metodeBayar, 'tempat') || str_contains($metodeBayar, 'tunai');
    ?><tr><td><?= $no++; ?></td><td><strong><?= esc($row['nama'] ?? '-'); ?></strong><div class="text-muted small"><?= esc($row['email'] ?? '-'); ?></div><?= !empty($row['nis']) ? '<span class="nis-badge mt-1">'.esc($row['nis']).'</span>' : '<span class="badge bg-warning-subtle text-warning-emphasis rounded-pill mt-1">NIS belum dibuat</span>'; ?></td><td><?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></td><td><?= esc(validasi_tanggal($row['created_at'] ?? null)); ?></td><td>
        <?php if (!empty($row['bukti_pembayaran'])): ?>
            <a href="<?= base_url('uploads/bukti/' . $row['bukti_pembayaran']); ?>" target="_blank" class="btn btn-soft btn-sm"><i class="fas fa-receipt me-1"></i>Lihat</a>
        <?php elseif ($isCod): ?>
            <span class="badge bg-info-subtle text-info fw-bold px-2 py-1"><i class="fas fa-handshake me-1"></i>Bayar di Tempat (COD)</span>
        <?php else: ?>
            <span class="text-muted small">Belum ada bukti</span>
        <?php endif; ?>
    </td><td><span class="badge bg-<?= $statusClass; ?> badge-status"><?= esc($statusLabel); ?></span></td><td><div class="actions"><button class="btn btn-soft btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>"><i class="fas fa-eye me-1"></i>Detail</button><?php if (!$isApproved): ?><a class="btn btn-success btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/setuju'); ?>" onclick="return confirm('Setujui pendaftaran ini dan buat NIS otomatis?')"><i class="fas fa-check me-1"></i>Setujui</a><?php endif; ?><?php if (!$isRejected): ?><a class="btn btn-danger btn-sm" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/tolak'); ?>" onclick="return confirm('Tolak pendaftaran ini? NIS tidak akan dibuat.')"><i class="fas fa-xmark me-1"></i>Tolak</a><?php endif; ?></div></td></tr><?php endforeach; ?><?php else: ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-inbox fa-2x mb-3"></i><div>Belum ada data validasi.</div></div></td></tr><?php endif; ?></tbody></table></div>

    <div class="mobile-list"><?php if (!empty($pendaftaran)): ?><?php foreach ($pendaftaran as $row): ?><?php 
        [$statusLabel,$statusClass]=validasi_status_badge($row); 
        $modalId='validasiMobile'.(int)$row['id_pendaftaran']; 
        $isApproved=$statusLabel==='Disetujui'; 
        $isRejected=$statusLabel==='Ditolak'; 
        $metodeBayar = strtolower($row['metode_pembayaran'] ?? $row['metode_pembelajaran'] ?? '');
        $isCod = str_contains($metodeBayar, 'cod') || str_contains($metodeBayar, 'tempat') || str_contains($metodeBayar, 'tunai');
    ?><article class="validation-card"><div class="d-flex justify-content-between gap-2"><div><strong><?= esc($row['nama'] ?? '-'); ?></strong><div class="text-muted small"><?= esc($row['email'] ?? '-'); ?></div></div><span class="badge bg-<?= $statusClass; ?> badge-status align-self-start"><?= esc($statusLabel); ?></span></div><div class="card-row"><span>NIS</span><span><?= esc($row['nis'] ?: 'Belum dibuat'); ?></span></div><div class="card-row"><span>Kelas</span><span><?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></span></div><div class="card-row"><span>Metode / Bukti</span><span><?= $isCod ? 'Bayar di Tempat (COD)' : (!empty($row['bukti_pembayaran']) ? 'Ada Bukti Transfer' : 'Tidak ada'); ?></span></div><div class="card-row"><span>Tanggal Daftar</span><span><?= esc(validasi_tanggal($row['created_at'] ?? null)); ?></span></div><div class="actions mt-3"><button class="btn btn-soft" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>">Detail</button><?php if (!$isApproved): ?><a class="btn btn-success" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/setuju'); ?>" onclick="return confirm('Setujui pendaftaran ini dan buat NIS otomatis?')">Setujui</a><?php endif; ?><?php if (!$isRejected): ?><a class="btn btn-danger" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/tolak'); ?>" onclick="return confirm('Tolak pendaftaran ini?')">Tolak</a><?php endif; ?></div></article><?php endforeach; ?><?php else: ?><div class="empty-state"><i class="fas fa-inbox fa-2x mb-3"></i><div>Belum ada data validasi.</div></div><?php endif; ?></div></section>
</main>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar"><div class="offcanvas-header"><img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" style="width:180px;height:70px;object-fit:cover;border-radius:10px"><button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button></div><div class="offcanvas-body"><nav class="nav flex-column gap-1"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">Dashboard</a><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">Data Peserta</a><a href="<?= base_url('admin/validasi'); ?>" class="nav-link active">Validasi</a><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">Master Kelas</a><a href="<?= base_url('admin/mentor'); ?>" class="nav-link">Instruktur</a><a href="<?= base_url('admin/laporan'); ?>" class="nav-link">Laporan</a><a href="<?= base_url('logout'); ?>" class="nav-link text-danger">Logout</a></nav></div></div>

<?php foreach (($pendaftaran ?? []) as $row): ?><?php foreach (['validasiDetail','validasiMobile'] as $prefix): ?><?php 
    $modalId=$prefix.(int)$row['id_pendaftaran']; 
    [$statusLabel,$statusClass]=validasi_status_badge($row); 
    $metodeBayar = strtolower($row['metode_pembayaran'] ?? $row['metode_pembelajaran'] ?? '');
    $isCod = str_contains($metodeBayar, 'cod') || str_contains($metodeBayar, 'tempat') || str_contains($metodeBayar, 'tunai');
?><div class="modal fade" id="<?= $modalId; ?>" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><div><h5 class="modal-title fw-bold">Detail Validasi Pendaftaran</h5><div class="text-muted small"><?= esc($row['nama'] ?? '-'); ?> | <?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="detail-section"><h6>Data Peserta</h6><div class="detail-grid"><div class="detail-item"><div class="detail-label">Nama</div><div class="detail-value"><?= esc($row['nama'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">NIS</div><div class="detail-value"><?= esc($row['nis'] ?: 'Belum dibuat'); ?></div></div><div class="detail-item"><div class="detail-label">Email</div><div class="detail-value"><?= esc($row['email'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">No. WhatsApp</div><div class="detail-value"><?= esc($row['no_hp'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">Jenis Kelamin</div><div class="detail-value"><?= esc($row['jenis_kelamin'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">Pendidikan</div><div class="detail-value"><?= esc($row['pendidikan_terakhir'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">Alamat</div><div class="detail-value"><?= esc($row['alamat'] ?? '-'); ?></div></div></div></div><div class="detail-section"><h6>Data Pendaftaran</h6><div class="detail-grid"><div class="detail-item"><div class="detail-label">Kelas</div><div class="detail-value"><?= esc($row['nama_kelas'] ?? $row['pilihan_kelas'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">Jenis Kelas</div><div class="detail-value"><?= esc($row['jenis_kelas'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">Metode Pembelajaran</div><div class="detail-value"><?= esc($row['metode_pembelajaran'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">Lokasi</div><div class="detail-value"><?= esc($row['lokasi_pelatihan'] ?? $row['lokasi_media'] ?? '-'); ?></div></div><div class="detail-item"><div class="detail-label">Tanggal Mulai</div><div class="detail-value"><?= esc(validasi_tanggal($row['tanggal_mulai_kelas'] ?? $row['tanggal_mulai_master'] ?? null)); ?></div></div><div class="detail-item"><div class="detail-label">Tanggal Daftar</div><div class="detail-value"><?= esc(validasi_tanggal($row['created_at'] ?? null)); ?></div></div><div class="detail-item"><div class="detail-label">Bukti Pembayaran / Metode</div><div class="detail-value"><?php if (!empty($row['bukti_pembayaran'])): ?><a href="<?= base_url('uploads/bukti/' . $row['bukti_pembayaran']); ?>" target="_blank">Lihat bukti transfer</a><?php elseif ($isCod): ?><span class="text-primary fw-bold"><i class="fas fa-handshake me-1"></i> Tunai / Bayar di Tempat (COD) - Kwitansi fisik</span><?php else: ?>Tidak ada bukti pembayaran<?php endif; ?></div></div><div class="detail-item"><div class="detail-label">Status Validasi</div><div class="detail-value"><span class="badge bg-<?= $statusClass; ?>"><?= esc($statusLabel); ?></span></div></div></div></div></div><div class="modal-footer"><button class="btn btn-soft" data-bs-dismiss="modal">Tutup</button><?php if ($statusLabel !== 'Disetujui'): ?><a class="btn btn-success" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/setuju'); ?>" onclick="return confirm('Setujui pendaftaran ini dan buat NIS otomatis?')">Setujui</a><?php endif; ?><?php if ($statusLabel !== 'Ditolak'): ?><a class="btn btn-danger" href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/tolak'); ?>" onclick="return confirm('Tolak pendaftaran ini?')">Tolak</a><?php endif; ?></div></div></div></div><?php endforeach; ?><?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>