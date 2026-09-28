<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Detail Pendaftaran') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8f6ff;
            color: #1e293b;
        }
        .card {
            border: none;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(124, 92, 250, 0.08);
        }
        .text-purple-custom { color: #7c5cfa; }
        .bg-purple-soft { background: #efeaff; color: #5b3fd6; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <a href="<?= base_url('peserta/dashboard') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
                <h4 class="fw-bold mb-0" style="color: #5b3fd6;">Detail Status & Form Pendaftaran</h4>
            </div>

            <div class="card p-4 p-md-5">
                <!-- Status Banner -->
                <div class="alert alert-info d-flex align-items-center justify-content-between rounded-4 mb-4">
                    <div>
                        <small class="text-muted d-block">Status Pendaftaran:</small>
                        <strong class="fs-5 text-uppercase"><?= esc($detail['status_pendaftaran'] ?? 'Menunggu') ?></strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Status Pembayaran:</small>
                        <span class="badge bg-secondary"><?= esc($detail['status_pembayaran'] ?? 'Pending') ?></span>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-purple-custom me-2"></i> Informasi Data Diri</h5>
                <table class="table table-striped table-borderless align-middle mb-4">
                    <tr>
                        <td width="35%" class="fw-semibold text-muted">Nomor Induk Siswa (NIS)</td>
                        <td class="fw-bold">: <?= esc($detail['nis'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Nama Lengkap</td>
                        <td class="fw-bold">: <?= esc($detail['nama'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Email</td>
                        <td>: <?= esc($detail['email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">No. WhatsApp / HP</td>
                        <td>: <?= esc($detail['no_hp'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Tempat, Tanggal Lahir (TTL)</td>
                        <td>: <?= esc($detail['ttl'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Jenis Kelamin</td>
                        <td>: <?= esc($detail['jenis_kelamin'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Alamat Lengkap</td>
                        <td>: <?= esc($detail['alamat'] ?? '-') ?></td>
                    </tr>
                </table>

                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-mortarboard text-purple-custom me-2"></i> Informasi Kelas & Pembelajaran</h5>
                <table class="table table-striped table-borderless align-middle mb-4">
                    <tr>
                        <td width="35%" class="fw-semibold text-muted">Pilihan Kelas</td>
                        <td class="fw-bold text-purple-custom">: <?= esc($detail['nama_kelas'] ?? $detail['pilihan_kelas'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Kategori Kelas</td>
                        <td>: <?= esc($detail['kategori_kelas'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Mentor Pengampu</td>
                        <td>: <?= esc($detail['nama_mentor'] ?? 'Mentor Creativemu') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Metode Pembelajaran</td>
                        <td class="text-uppercase fw-bold">: <?= esc($detail['metode_pembelajaran'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Lokasi Pelatihan</td>
                        <td>: <?= esc($detail['lokasi_pelatihan'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Status / Profesi Peserta</td>
                        <td>: <?= esc($detail['status'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Asal Instansi / Sekolah</td>
                        <td>: <?= esc($detail['asal_instansi'] ?? '-') ?></td>
                    </tr>
                </table>

                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-wallet2 text-purple-custom me-2"></i> Pembayaran & Berkas</h5>
                <table class="table table-striped table-borderless align-middle mb-4">
                    <tr>
                        <td width="35%" class="fw-semibold text-muted">Metode Pembayaran</td>
                        <td class="fw-bold">: <?= esc($detail['metode_pembayaran'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Bukti Pembayaran</td>
                        <td>: 
                            <?php if (!empty($detail['bukti_pembayaran'])): ?>
                                <a href="<?= base_url('uploads/bukti/' . $detail['bukti_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-image me-1"></i> Lihat Bukti Transfer
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Belum diunggah</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Pas Foto</td>
                        <td>: 
                            <?php if (!empty($detail['pas_foto'])): ?>
                                <a href="<?= base_url('uploads/foto/' . $detail['pas_foto']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-person-square me-1"></i> Lihat Pas Foto
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <div class="text-center mt-3">
                    <a href="<?= base_url('peserta/dashboard') ?>" class="btn px-5 py-2 text-white fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #7c5cfa, #5b3fd6);">
                        Kembali ke Dashboard Utama
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>