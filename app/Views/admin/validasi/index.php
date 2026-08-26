<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Pendaftaran - Panel Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-card-checklist text-primary me-2"></i>Validasi Pendaftaran</h3>
            <p class="text-muted mb-0">Kelola dan setujui peserta yang sedang menunggu konfirmasi admin.</p>
        </div>
        <div>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Notifikasi Sukses -->
    <?php if(session()->getFlashdata('success') || session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-5 border-success mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= session()->getFlashdata('success') ?? session()->getFlashdata('pesan') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Card Tabel -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="py-3 px-4 text-center" width="5%">No</th>
                        <th class="py-3">Data Peserta</th>
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Pembayaran</th>
                        <th class="py-3 text-center">Bukti</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php if(empty($pendaftaran)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data pendaftaran yang masuk.
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach(($pendaftaran ?? []) as $i => $item): ?>
                        <tr>
                            <td class="text-center px-4 fw-semibold text-muted"><?= $i + 1 ?></td>
                            <td>
                                <div class="fw-bold text-dark mb-1"><?= esc($item['nama'] ?? '-') ?></div>
                                <div class="text-muted small">
                                    <i class="bi bi-envelope me-1"></i><?= esc($item['email'] ?? '-') ?> <br>
                                    <i class="bi bi-telephone me-1"></i><?= esc($item['no_hp'] ?? '-') ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                    <?= esc($item['nama_kelas'] ?? '-') ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark"><?= esc($item['metode_pembayaran'] ?? '-') ?></div>
                                <small class="text-muted text-capitalize"><?= esc($item['status_pembayaran'] ?? 'pending') ?></small>
                            </td>
                            <td class="text-center">
                                <?php if(!empty($item['bukti_pembayaran'])): ?>
                                    <a href="<?= base_url('uploads/bukti_pembayaran/' . $item['bukti_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-image me-1"></i> Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-3">Belum Upload</span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- LOGIKA STATUS DINAMIS -->
                            <td class="text-center">
                                <?php 
                                    $status = strtolower($item['status_pembayaran'] ?? '');
                                    if ($status == 'terkonfirmasi' || $status == 'disetujui'): 
                                ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                <?php elseif ($status == 'batal' || $status == 'ditolak'): ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- TOMBOL AKSI -->
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="<?= base_url('admin/validasi/update/' . $item['id_pendaftaran'] . '/setuju') ?>" 
                                       class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" title="Setujui">
                                       <i class="bi bi-check-lg"></i>
                                    </a>
                                    <a href="<?= base_url('admin/validasi/update/' . $item['id_pendaftaran'] . '/tolak') ?>" 
                                       class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" title="Tolak">
                                       <i class="bi bi-x-lg"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>