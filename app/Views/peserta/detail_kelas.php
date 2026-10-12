<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kelas - <?= esc($kelas['nama_kelas']) ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --text-main: #0f172a;
        }

        body { 
            /* Efek latar belakang gelap dengan blur lembut */
            background-color: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(10px);
            font-family: 'Inter', sans-serif; 
            color: var(--text-main);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Membatasi ukuran kotak agar pas di tengah dan tidak terlalu besar */
        .modal-box-container {
            width: 100%;
            max-width: 750px; 
        }

        .detail-card { 
            border: none; 
            border-radius: 1.25rem; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); 
            background: #ffffff; 
            overflow: hidden;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
        }

        .modal-header-custom {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }

        .modal-body-scroll {
            overflow-y: auto;
            padding: 1.5rem;
        }

        .img-detail { 
            width: 100%; 
            height: 220px; 
            object-fit: cover; 
            border-radius: 0.75rem;
        }

        .badge-kategori { background-color: #ede9fe; color: #6d28d9; font-size: 0.75rem; font-weight: 600; padding: 0.35rem 0.8rem; border-radius: 50rem; }
        .badge-tipe { background-color: #e0f2fe; color: #0284c7; font-size: 0.75rem; font-weight: 600; padding: 0.35rem 0.8rem; border-radius: 50rem; }
        
        .section-title { 
            font-weight: 700; 
            color: var(--text-main); 
            font-size: 0.95rem; 
            border-left: 3px solid var(--primary-color); 
            padding-left: 8px; 
            margin-bottom: 0.75rem; 
            margin-top: 1.25rem;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
        }
    </style>
</head>
<body>

<div class="modal-box-container">
    <div class="card detail-card">
        
        <!-- Header Modal -->
        <div class="modal-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold fs-6 text-dark">
                <i class="bi bi-info-circle-fill me-2 text-primary"></i> Detail Informasi Kelas
            </h5>
            <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn-close shadow-none" aria-label="Close"></a>
        </div>

        <!-- Body dengan Scroll Halus jika kontennya panjang -->
        <div class="modal-body-scroll">
            
            <!-- Thumbnail Kelas -->
            <?php 
                $gambarFile = !empty($kelas['thumbnail']) ? $kelas['thumbnail'] : (!empty($kelas['foto']) ? $kelas['foto'] : (!empty($kelas['image']) ? $kelas['image'] : null)); 
            ?>
            <?php if (!empty($gambarFile)): ?>
                <?php if (filter_var($gambarFile, FILTER_VALIDATE_URL)): ?>
                    <img src="<?= esc($gambarFile) ?>" class="img-detail mb-3" alt="<?= esc($kelas['nama_kelas'] ?? 'Kelas') ?>">
                <?php elseif (file_exists(FCPATH . 'uploads/kelas/' . $gambarFile)): ?>
                    <img src="<?= base_url('uploads/kelas/' . $gambarFile) ?>" class="img-detail mb-3" alt="<?= esc($kelas['nama_kelas'] ?? 'Kelas') ?>">
                <?php endif; ?>
            <?php endif; ?>

            <!-- Kategori, Tipe & Status -->
            <div class="d-flex flex-wrap gap-2 mb-2">
                <?php if (!empty($kelas['kategori'])): ?>
                    <span class="badge-kategori"><?= esc($kelas['kategori']) ?></span>
                <?php endif; ?>

                <?php if (!empty($kelas['tipe_kelas']) || !empty($kelas['metode_pembelajaran'])): ?>
                    <?php $tipeTampil = $kelas['tipe_kelas'] ?? $kelas['metode_pembelajaran']; ?>
                    <span class="badge-tipe">
                        <i class="bi <?= strtolower($tipeTampil) == 'online' ? 'bi-camera-video-fill' : 'bi-building-fill' ?> me-1"></i>
                        <?= esc(ucfirst($tipeTampil)) ?>
                    </span>
                <?php endif; ?>

                <?php if (!empty($kelas['status'])): ?>
                    <?php $status = strtolower($kelas['status']); ?>
                    <span class="badge <?= in_array($status, ['aktif', '1', 'active', 'approved']) ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-warning-subtle text-warning border border-warning-subtle' ?> px-2 py-1 rounded-pill" style="font-size: 0.7rem;">
                        <?= ucfirst(esc($kelas['status'])) ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Judul Kelas -->
            <?php if (!empty($kelas['nama_kelas'])): ?>
                <h3 class="fw-bold text-dark fs-5 mb-2"><?= esc($kelas['nama_kelas']) ?></h3>
            <?php endif; ?>

            <!-- Ringkasan Singkat -->
            <?php if (!empty($kelas['ringkasan']) && $kelas['ringkasan'] !== '-'): ?>
                <p class="text-muted mb-3" style="font-size: 0.85rem;"><?= esc($kelas['ringkasan']) ?></p>
            <?php endif; ?>

            <hr class="my-3 text-muted opacity-25">

            <!-- Informasi Detail (Grid Ringkas) -->
            <div class="row g-2 mb-2">
                <?php if (!empty($kelas['nama_mentor'])): ?>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-person-workspace text-primary me-1"></i> Instruktur Pengampu</span>
                            <strong class="text-dark" style="font-size: 0.8rem;"><?= esc($kelas['nama_mentor']) ?></strong>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($kelas['jumlah_pertemuan'])): ?>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-clock-fill text-primary me-1"></i> Jumlah Pertemuan</span>
                            <strong class="text-dark" style="font-size: 0.8rem;"><?= esc($kelas['jumlah_pertemuan']) ?> Pertemuan</strong>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($kelas['kapasitas']) && $kelas['kapasitas'] !== null && $kelas['kapasitas'] !== ''): ?>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-people-fill text-primary me-1"></i> Kapasitas Peserta</span>
                            <strong class="text-dark" style="font-size: 0.8rem;"><?= esc($kelas['kapasitas_tersedia'] ?? $kelas['kapasitas']) ?> Orang</strong>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($kelas['lokasi_media'])): ?>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="text-muted d-block" style="font-size: 0.7rem;"><i class="bi bi-geo-alt-fill text-primary me-1"></i> Lokasi Media</span>
                            <strong class="text-dark" style="font-size: 0.8rem;"><?= esc($kelas['lokasi_media']) ?></strong>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Deskripsi Lengkap -->
            <?php if (!empty($kelas['deskripsi']) && $kelas['deskripsi'] !== '-'): ?>
                <div class="section-title">Deskripsi Lengkap</div>
                <div class="text-secondary mb-3 p-3 bg-light rounded-3" style="font-size: 0.82rem; line-height: 1.6;">
                    <?= nl2br(esc($kelas['deskripsi'])) ?>
                </div>
            <?php endif; ?>

            <!-- Informasi Harga -->
            <?php 
                $hasReguler = isset($kelas['harga_reguler']) && $kelas['harga_reguler'] !== null && $kelas['harga_reguler'] !== '';
                $hasPrivat = isset($kelas['harga_privat']) && $kelas['harga_privat'] !== null && $kelas['harga_privat'] !== '';
            ?>
            <?php if ($hasReguler || $hasPrivat): ?>
                <div class="section-title">Rincian Biaya Pendaftaran</div>
                <div class="row g-2 mb-3">
                    <?php if ($hasReguler): ?>
                        <div class="<?= $hasPrivat ? 'col-6' : 'col-12' ?>">
                            <div class="p-2 border rounded-3 bg-white text-center">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Reguler</span>
                                <span class="fw-bold text-dark" style="font-size: 0.85rem;">Rp <?= number_format((float)$kelas['harga_reguler'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($hasPrivat): ?>
                        <div class="<?= $hasReguler ? 'col-6' : 'col-12' ?>">
                            <div class="p-2 border rounded-3 bg-light text-center border-primary">
                                <span class="text-primary d-block fw-semibold" style="font-size: 0.7rem;">Privat</span>
                                <span class="fw-bold text-primary" style="font-size: 0.85rem;">Rp <?= number_format((float)$kelas['harga_privat'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- Footer / Tombol Aksi -->
        <!-- Footer / Tombol Aksi -->
<div class="p-3 bg-light border-top d-flex gap-2">
    <a href="<?= base_url('pelatihan/daftar-kelas') ?>"
       class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold"
       style="font-size: 0.85rem;">
        Tutup
    </a>

    <?php if (($kelas['kapasitas_tersedia'] ?? 0) > 0): ?>

        <a href="<?= base_url('pelatihan/pendaftaran/' . $kelas['id_kelas']) ?>"
           class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm flex-grow-1 text-center"
           style="font-size: 0.85rem;">
            Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i>
        </a>

    <?php else: ?>

        <button type="button"
                class="btn btn-secondary rounded-pill px-4 py-2 fw-bold shadow-sm flex-grow-1"
                style="font-size: 0.85rem;"
                disabled>
            <i class="bi bi-lock-fill me-1"></i>
            Kelas Penuh
        </button>

    <?php endif; ?>
</div>

    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
