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
                <?php
                    $kodeStatus  = $detail['kode_status'] ?? 'menunggu';
                    $labelStatus = $detail['label_status'] ?? ($detail['status_pendaftaran'] ?? 'Menunggu Persetujuan');
                    $bisaDiakses = !empty($detail['bisa_diakses']);
                    $sudahDisetujui = in_array($kodeStatus, ['disetujui', 'siap_diakses'], true);

                    $bannerStyle = 'background: #fffbeb; border: 1.5px solid #fde68a; color: #92400e;';
                    if ($kodeStatus === 'siap_diakses') {
                        $bannerStyle = 'background: #ecfdf5; border: 1.5px solid #a7f3d0; color: #065f46;';
                    } elseif ($kodeStatus === 'sedang_disiapkan') {
                        $bannerStyle = 'background: #f5f3ff; border: 1.5px solid #ddd6fe; color: #5b21b6;';
                    } elseif ($kodeStatus === 'disetujui') {
                        $bannerStyle = 'background: #eff6ff; border: 1.5px solid #bfdbfe; color: #1e40af;';
                    } elseif ($kodeStatus === 'ditolak') {
                        $bannerStyle = 'background: #fef2f2; border: 1.5px solid #fecaca; color: #991b1b;';
                    }
                ?>
                <!-- Status Banner -->
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 rounded-4 p-3 mb-4" style="<?= $bannerStyle ?>">
                    <div>
                        <small class="d-block opacity-75 fw-semibold">Status Persetujuan Kelas:</small>
                        <strong class="fs-5 text-uppercase"><?= esc($labelStatus) ?></strong>
                        <?php if (!empty($detail['keterangan_status'])): ?>
                            <div class="small mt-1"><?= esc($detail['keterangan_status']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="text-md-end">
                        <small class="d-block opacity-75 fw-semibold mb-1">Status Pembayaran:</small>
                        <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill"><?= esc(strtoupper($detail['status_pembayaran'] ?? 'PENDING')) ?></span>
                    </div>
                </div>

                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-purple-custom me-2"></i> Informasi Data Diri</h5>
                <table class="table table-striped table-borderless align-middle mb-4">
                    <tr>
                        <td width="35%" class="fw-semibold text-muted">Nomor Induk Siswa (NIS)</td>
                        <td class="fw-bold">: <?= ($sudahDisetujui && !empty($detail['nis'])) ? esc($detail['nis']) : 'Diterbitkan setelah disetujui' ?></td>
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
                        <td class="fw-semibold text-muted">Paket Kelas</td>
                        <td>: <span class="badge" style="background: #f4eeff; color: #7a3ff2; font-weight: 600;"><?= esc($detail['jenis_kelas'] ?? 'Reguler') ?></span></td>
                    </tr>
                    <?php if (!empty($detail['materi_privat'])): ?>
                    <tr>
                        <td class="fw-semibold text-muted">Materi Privat Dipilih</td>
                        <td class="fw-bold" style="color: #682bd8;">: <?= esc($detail['materi_privat']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($detail['custom_materi'])): ?>
                    <tr>
                        <td class="fw-semibold text-muted">Permintaan Materi Khusus</td>
                        <td class="fst-italic text-muted">: <?= esc($detail['custom_materi']) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="fw-semibold text-muted">Mentor Pengampu</td>
                        <td>: <?= $sudahDisetujui ? esc($detail['nama_mentor'] ?? 'Mentor Creativemu') : '<span class="text-muted fst-italic">Ditampilkan setelah kelas disetujui</span>' ?></td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-muted">Jadwal Pelatihan</td>
                        <td>: 
                            <?php if ($bisaDiakses && !empty($detail['tanggal_mulai_kelas'])): ?>
                                <strong class="text-success"><?= esc(date('d M Y', strtotime($detail['tanggal_mulai_kelas']))) ?></strong>
                            <?php elseif ($kodeStatus === 'sedang_disiapkan'): ?>
                                <span class="fw-semibold" style="color: #6d28d9;"><i class="bi bi-gear-wide-connected me-1"></i>Kelas sedang disiapkan oleh tim instruktur</span>
                            <?php elseif ($kodeStatus === 'disetujui'): ?>
                                <span class="text-primary fw-semibold">Menunggu penetapan jadwal kelas</span>
                            <?php elseif ($kodeStatus === 'ditolak'): ?>
                                <span class="text-danger">Pendaftaran ditolak</span>
                            <?php else: ?>
                                <span class="text-warning-emphasis fw-semibold"><i class="bi bi-hourglass-split me-1"></i>Menunggu Persetujuan Admin</span>
                            <?php endif; ?>
                        </td>
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

                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-wallet2 text-purple-custom me-2"></i> Informasi Pembayaran</h5>
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
                                <span class="text-muted"><?= strtoupper($detail['metode_pembayaran'] ?? '') === 'COD' ? 'Pembayaran COD (Bayar di Tempat)' : 'Belum diunggah' ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                    <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>" class="btn px-4 py-2 fw-bold rounded-pill" style="background: #efeaff; color: #5b3fd6;">
                        <i class="bi bi-collection-play me-1"></i> Daftar Kelas Saya
                    </a>
                    <?php if ($bisaDiakses): ?>
                        <a href="<?= base_url('pelatihan/kelas?id_kelas=' . $detail['id_kelas']) ?>" class="btn px-4 py-2 text-white fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #7c5cfa, #5b3fd6);">
                            <i class="bi bi-door-open-fill me-1"></i> Masuk Ruang KBM
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>