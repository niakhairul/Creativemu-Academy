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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Data Peserta'); ?> - Creativemu Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --sidebar-bg:#22133c; --sidebar-text:#c8bfe7; --primary:#794bc4; --primary-dark:#5931a0; --dark:#1e0f33; --soft:#f4f0fc; --border:#eadffb; --muted:#817796; }
        *{box-sizing:border-box} body{margin:0;font-family:'Poppins',sans-serif;background:#f7f5fd;color:#2f2442;overflow-x:hidden}
        .admin-shell{display:flex;min-height:100vh}
        .sidebar{width:275px;background:var(--sidebar-bg);color:var(--sidebar-text);position:fixed;inset:0 auto 0 0;z-index:1040;overflow-y:auto;box-shadow:8px 0 28px rgba(34,19,60,.12)}
        .sidebar-header{padding:22px 18px;background:rgba(0,0,0,.22);text-align:center}
        .sidebar-header img{width:230px;max-width:100%;height:92px;object-fit:cover;border-radius:12px}
        .sidebar .nav{padding:18px 12px 28px}
        .sidebar .nav-link{display:flex;align-items:center;gap:12px;color:var(--sidebar-text);border-radius:12px;padding:12px 16px;margin-bottom:6px;font-weight:500;font-size:.9rem;transition:all .2s ease}
        .sidebar .nav-link i{width:21px;text-align:center}
        .sidebar .nav-link:hover,.sidebar .nav-link.active{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;box-shadow:0 4px 15px rgba(121,75,196,.3)}
        .main{margin-left:275px;width:calc(100% - 275px);padding:30px}
        .topbar,.panel,.participant-card{background:#fff;border:1px solid rgba(121,75,196,.08);box-shadow:0 10px 30px rgba(64,36,105,.04);transition:all .3s ease}
        .topbar{border-radius:20px;padding:22px 28px;display:flex;justify-content:space-between;gap:18px;align-items:center;margin-bottom:24px}
        .dash-header h3{color:var(--dark);font-weight:800;margin-bottom:4px}
        .dash-header p{color:var(--muted);font-size:.9rem;margin:0}
        .admin-profile{display:flex;gap:12px;align-items:center;min-width:max-content}
        .admin-profile img{width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--primary);padding:2px;background:#fff}
        .admin-profile h6{margin:0;color:var(--dark);font-weight:700;font-size:.92rem}
        .admin-profile small{color:var(--muted)}
        .panel{border-radius:20px;padding:24px;margin-bottom:20px}
        .filter-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr auto;gap:16px;align-items:end}
        .form-label{font-size:.8rem;font-weight:700;color:var(--dark);margin-bottom:8px}
        .form-control,.form-select{border-radius:14px;border-color:var(--border);min-height:46px;padding:10px 16px;font-size:.9rem}
        .form-control:focus,.form-select:focus{border-color:var(--primary);box-shadow:0 0 0 .25rem rgba(121,75,196,.12)}
        .btn-purple{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:0;border-radius:14px;min-height:46px;padding:10px 20px;font-weight:700;box-shadow:0 4px 12px rgba(121,75,196,.25);transition:all .2s ease}
        .btn-purple:hover{color:#fff;transform:translateY(-1px);box-shadow:0 6px 16px rgba(121,75,196,.35)}
        .btn-soft{background:var(--soft);color:var(--primary);border:1px solid var(--border);border-radius:14px;min-height:46px;padding:10px 20px;font-weight:700;transition:all .2s ease}
        .btn-soft:hover{background:#ede6fc;color:var(--primary-dark)}
        .table-wrap{overflow-x:auto}
        .table{margin:0;vertical-align:middle}
        .table thead th{background:#faf8ff;color:var(--primary-dark);border-bottom:2px solid var(--border);padding:16px;font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap}
        .table tbody td{padding:16px;border-bottom:1px solid #f2ecfb;color:#443652;font-size:.9rem}
        .table tbody tr{transition:background-color .2s ease}
        .table tbody tr:hover{background-color:#fcfbff}
        .nis-badge{display:inline-flex;align-items:center;gap:7px;border-radius:999px;background:#f4f0fc;color:#5931a0;border:1px solid #ded0f7;padding:6px 12px;font-weight:700;letter-spacing:.04em;font-family:Consolas,monospace;font-size:.85rem}
        .badge-status{border-radius:999px;padding:6px 14px;font-weight:700;font-size:.8rem}
        .mobile-list{display:none}
        .participant-card{border-radius:16px;padding:18px;margin-bottom:14px}
        .card-row{display:flex;justify-content:space-between;gap:12px;border-top:1px solid #f2ecfb;margin-top:12px;padding-top:12px}
        .card-row span:first-child{color:var(--muted);font-size:.78rem;font-weight:700}
        .card-row span:last-child{text-align:right;font-weight:600;color:var(--dark)}
        .pagination .page-link{border-color:var(--border);color:var(--primary);border-radius:12px;margin:0 4px;padding:8px 14px;font-weight:600}
        .pagination .active .page-link{background:var(--primary);border-color:var(--primary);box-shadow:0 4px 10px rgba(121,75,196,.25)}
        .empty-state{text-align:center;color:var(--muted);padding:45px 15px}
        .modal-content{border:0;border-radius:20px;box-shadow:0 20px 40px rgba(30,15,51,.15)}
        .modal-header{border-bottom:1px solid var(--border);padding:20px 24px}
        .modal-body{padding:24px}
        .modal-footer{border-top:1px solid var(--border);padding:16px 24px}
        .offcanvas{background:var(--sidebar-bg);color:var(--sidebar-text)}
        
        @media(max-width:1100px){.filter-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.filter-actions{grid-column:1/-1;display:flex;gap:10px}}
        @media(max-width:768px){.sidebar{display:none}.sidebar.show{display:block}.mobile-menu{display:inline-flex}.main{margin-left:0;width:100%;padding:16px}.topbar{align-items:flex-start;flex-direction:column;border-radius:16px;padding:18px;padding-top:64px;position:relative}.admin-profile{width:100%}.filter-grid{grid-template-columns:1fr}.filter-actions{display:grid;grid-template-columns:1fr 1fr}.desktop-table{display:none}.mobile-list{display:block}.panel{padding:16px;border-radius:16px}.offcanvas .nav-link{color:var(--sidebar-text)}}
        @media(max-width:430px){.filter-actions{grid-template-columns:1fr}}
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>
<div class="admin-shell">
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header"><img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy"></div>
        <nav class="nav flex-column">
            <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie"></i>Dashboard</a>
            <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book"></i>Master Kelas</a>
            <a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user"></i>Instruktur</a>
            <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link active"><i class="fas fa-users"></i>Data Peserta</a>
            <a href="<?= base_url('admin/validasi'); ?>" class="nav-link"><i class="fas fa-clipboard-check"></i>Validasi</a>
            <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link"><i class="fas fa-book-open"></i>Buku Induk</a>
            <a href="<?= base_url('admin/angket'); ?>" class="nav-link"><i class="fas fa-poll"></i>Angket</a>
            <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-certificate"></i>Sertifikat</a>
            <a href="<?= base_url('admin/laporan'); ?>" class="nav-link"><i class="fas fa-file-lines"></i>Laporan</a>
            <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear"></i>Pengaturan</a>
            <a href="<?= base_url('logout'); ?>" class="nav-link text-danger mt-2"><i class="fas fa-right-from-bracket"></i>Logout</a>
        </nav>
    </nav>

    <!-- === MAIN CONTENT === -->
   <main class="main">
        
        <!-- === TOP NAVBAR === -->
        <div class="topbar">
            <div class="dash-header">
                <h3>Daftar Peserta</h3>
                <p>Data peserta pelatihan terintegrasi langsung dengan pendaftaran kelas Creativemu Academy.</p>
            </div>
            <div class="admin-profile"><img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil"><div><h6><?= esc(session()->get('nama') ?: 'Administrator'); ?></h6><small>Administrator</small></div></div>
        </div>

        <?php foreach (['success' => 'success', 'error' => 'danger', 'warning' => 'warning'] as $flash => $type): ?>
            <?php if (session()->getFlashdata($flash)): ?><div class="alert alert-<?= $type; ?> border-0 rounded-4 shadow-sm mb-4"><?= session()->getFlashdata($flash); ?></div><?php endif; ?>
        <?php endforeach; ?>

        <section class="panel">
            <form action="<?= base_url('admin/data-peserta'); ?>" method="get" class="filter-grid">
                <div><label class="form-label">Search</label><input type="search" name="keyword" class="form-control" placeholder="Cari NIS, nama, WhatsApp, email..." value="<?= esc($keyword ?? ''); ?>"></div>
                <div><label class="form-label">Kelas</label><select name="id_kelas" class="form-select"><option value="">Semua kelas</option><?php foreach (($kelasList ?? []) as $kelas): ?><option value="<?= esc($kelas['id_kelas']); ?>" <?= ((string) ($selectedKelas ?? '') === (string) $kelas['id_kelas']) ? 'selected' : ''; ?>><?= esc($kelas['nama_kelas']); ?></option><?php endforeach; ?></select></div>
                <div><label class="form-label">Status</label><select name="status" class="form-select"><option value="">Semua status</option><?php foreach (['disetujui'=>'Disetujui','menunggu'=>'Menunggu','ditolak'=>'Ditolak'] as $value=>$label): ?><option value="<?= $value; ?>" <?= (($selectedStatus ?? '') === $value) ? 'selected' : ''; ?>><?= $label; ?></option><?php endforeach; ?></select></div>
                <div class="filter-actions"><button class="btn btn-purple w-100" type="submit"><i class="fas fa-magnifying-glass me-2"></i>Filter</button><a href="<?= base_url('admin/data-peserta'); ?>" class="btn btn-soft w-100 text-center"><i class="fas fa-rotate-left me-2"></i>Reset</a></div>
            </form>
        </section>

        <section class="panel">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1" style="color:var(--dark)">Tabel Data Peserta</h2>
                    <div class="text-muted small">Menampilkan <?= number_format(count($peserta ?? [])); ?> dari <?= number_format((int) $pagination['totalRows']); ?> data</div>
                </div>
            </div>
            <div class="desktop-table table-wrap">
                <table class="table table-hover align-middle">
                    <thead><tr><th>No</th><th>NIS</th><th>Nama Peserta</th><th>Kelas</th><th>No. WhatsApp</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    <?php if (!empty($peserta)): ?>
                        <?php foreach ($peserta as $index => $item): ?>
                            <?php [$statusLabel, $statusClass] = admin_status_badge($item); $modalId = 'detailPeserta' . (int) $item['id_pendaftaran']; ?>
                            <tr>
                                <td><?= (int) $pagination['offset'] + $index + 1; ?></td>
                                <td><?= !empty($item['resolved_nis']) ? '<span class="nis-badge"><i class="fas fa-id-card"></i>' . esc($item['resolved_nis']) . '</span>' : '<span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2">Belum ada NIS</span>'; ?></td>
                                <td><strong><?= esc($item['nama_lengkap'] ?? '-'); ?></strong><div class="text-muted small"><?= esc($item['email_terbaru'] ?? '-'); ?></div></td>
                                <td><?= esc($item['nama_kelas'] ?? $item['pilihan_kelas'] ?? '-'); ?></td>
                                <td><?= esc($item['no_hp_terbaru'] ?? '-'); ?></td>
                                <td><span class="badge bg-<?= $statusClass; ?> badge-status"><?= esc($statusLabel); ?></span></td>
                                <td class="text-end"><button class="btn btn-soft btn-sm px-3" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>"><i class="fas fa-eye me-1"></i>Detail</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-inbox fa-3x mb-3 text-muted opacity-50"></i><div>Belum ada data peserta yang ditemukan.</div></div></td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mobile-list">
                <?php if (!empty($peserta)): ?>
                    <?php foreach ($peserta as $index => $item): ?>
                        <?php [$statusLabel, $statusClass] = admin_status_badge($item); $modalId = 'detailPesertaMobile' . (int) $item['id_pendaftaran']; ?>
                        <article class="participant-card shadow-sm"><div class="d-flex justify-content-between gap-2"><div><strong><?= esc($item['nama_lengkap'] ?? '-'); ?></strong><div class="text-muted small"><?= esc($item['email_terbaru'] ?? '-'); ?></div></div><span class="badge bg-<?= $statusClass; ?> badge-status align-self-start"><?= esc($statusLabel); ?></span></div><div class="card-row"><span>NIS</span><span><?= esc($item['resolved_nis'] ?: 'Belum ada NIS'); ?></span></div><div class="card-row"><span>Kelas</span><span><?= esc($item['nama_kelas'] ?? $item['pilihan_kelas'] ?? '-'); ?></span></div><div class="card-row"><span>WhatsApp</span><span><?= esc($item['no_hp_terbaru'] ?? '-'); ?></span></div><button class="btn btn-purple w-100 mt-3" data-bs-toggle="modal" data-bs-target="#<?= $modalId; ?>"><i class="fas fa-eye me-2"></i>Lihat Detail</button></article>
                    <?php endforeach; ?>
                <?php else: ?><div class="empty-state"><i class="fas fa-inbox fa-3x mb-3 text-muted opacity-50"></i><div>Belum ada data peserta yang ditemukan.</div></div><?php endif; ?>
            </div>

            <?php if (($pagination['totalPages'] ?? 1) > 1): ?>
                <nav class="mt-4"><ul class="pagination justify-content-end flex-wrap">
                    <?php for ($p = 1; $p <= $pagination['totalPages']; $p++): $query = array_merge($queryBase, ['page' => $p]); ?>
                        <li class="page-item <?= $p === (int) $pagination['page'] ? 'active' : ''; ?>"><a class="page-link" href="<?= base_url('admin/data-peserta') . '?' . http_build_query($query); ?>"><?= $p; ?></a></li>
                    <?php endfor; ?>
                </ul></nav>
            <?php endif; ?>
        </section>
    </main>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar"><div class="offcanvas-header"><img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" style="width:180px;height:70px;object-fit:cover;border-radius:10px"><button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button></div><div class="offcanvas-body"><nav class="nav flex-column gap-1"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">Dashboard</a><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">Master Kelas</a><a href="<?= base_url('admin/mentor'); ?>" class="nav-link">Instruktur</a><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link active">Data Peserta</a><a href="<?= base_url('admin/validasi'); ?>" class="nav-link">Validasi</a><a href="<?= base_url('admin/laporan'); ?>" class="nav-link">Laporan</a><a href="<?= base_url('logout'); ?>" class="nav-link text-danger">Logout</a></nav></div></div>

<?php foreach (($peserta ?? []) as $item): ?>
    <?php foreach (['detailPeserta', 'detailPesertaMobile'] as $prefix): $modalId = $prefix . (int) $item['id_pendaftaran']; [$statusLabel, $statusClass] = admin_status_badge($item); ?>
        <div class="modal fade" id="<?= $modalId; ?>" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><div><h5 class="modal-title fw-bold">Detail Peserta</h5><div class="text-muted small"><?= esc($item['nama_lengkap'] ?? '-'); ?></div></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p class="mb-2"><strong>NIS:</strong> <?= esc($item['resolved_nis'] ?: 'Belum ada NIS'); ?></p><p class="mb-2"><strong>Status:</strong> <span class="badge bg-<?= $statusClass; ?>"><?= esc($statusLabel); ?></span></p><p class="mb-2"><strong>Nama:</strong> <?= esc($item['nama_lengkap'] ?? '-'); ?></p><p class="mb-2"><strong>Email:</strong> <?= esc($item['email_terbaru'] ?? '-'); ?></p><p class="mb-2"><strong>WhatsApp:</strong> <?= esc($item['no_hp_terbaru'] ?? '-'); ?></p><p class="mb-2"><strong>Kelas:</strong> <?= esc($item['nama_kelas'] ?? $item['pilihan_kelas'] ?? '-'); ?></p><p class="mb-2"><strong>Jenis Kelamin:</strong> <?= esc($item['gender_terbaru'] ?? '-'); ?></p><p class="mb-0"><strong>Alamat:</strong> <?= esc($item['alamat'] ?? '-'); ?></p></div><div class="modal-footer"><button class="btn btn-soft" data-bs-dismiss="modal">Tutup</button><a href="<?= base_url('admin/validasi'); ?>?keyword=<?= urlencode($item['resolved_nis'] ?: ($item['nama_lengkap'] ?? '')); ?>" class="btn btn-purple">Buka Validasi</a></div></div></div></div>
    <?php endforeach; ?>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
