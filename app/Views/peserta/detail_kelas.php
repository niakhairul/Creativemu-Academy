<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kelas - <?= esc($kelas['nama_kelas'] ?? 'Creativemu Academy') ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --purple-primary: #7b5af6;
            --purple-deep: #5b3fd6;
            --purple-gradient: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            --purple-soft: #f5f3ff;
            --purple-border: rgba(123, 90, 246, 0.16);
            --ink: #1e1b4b;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body { 
            background: 
                radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.35) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(6, 182, 212, 0.25) 0%, transparent 40%),
                linear-gradient(135deg, rgba(15, 23, 42, 0.75) 0%, rgba(30, 27, 75, 0.82) 100%);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--ink);
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
        }

        .modal-box-container {
            width: 100%;
            max-width: 780px;
            animation: modalPopIn 0.38s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes modalPopIn {
            0% { opacity: 0; transform: scale(0.94) translateY(18px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        .detail-card { 
            border: 1px solid rgba(255, 255, 255, 0.7); 
            border-radius: 26px; 
            box-shadow: 0 30px 70px -15px rgba(24, 12, 54, 0.38), 0 0 0 1px rgba(123, 90, 246, 0.12); 
            background: #ffffff; 
            overflow: hidden;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }

        .modal-header-custom {
            background: linear-gradient(135deg, #faf8ff 0%, #ffffff 100%);
            border-bottom: 1px solid var(--purple-border);
            padding: 1.15rem 1.6rem;
        }

        .modal-body-scroll {
            overflow-y: auto;
            padding: 1.6rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(123, 90, 246, 0.3) transparent;
        }

        .modal-body-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .modal-body-scroll::-webkit-scrollbar-thumb {
            background: #d8b4fe;
            border-radius: 10px;
        }

        /* Banner Thumbnail */
        .img-banner-wrapper {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 14px 34px -10px rgba(123, 90, 246, 0.22);
            margin-bottom: 1.4rem;
        }

        .img-detail { 
            width: 100%; 
            height: 230px; 
            object-fit: cover; 
            display: block;
            transition: transform 0.4s ease;
        }

        .img-banner-wrapper:hover .img-detail {
            transform: scale(1.02);
        }

        .banner-placeholder {
            width: 100%;
            height: 190px;
            background: linear-gradient(135deg, #7b5af6 0%, #a855f7 50%, #ec4899 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            position: relative;
        }

        .banner-placeholder::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(255,255,255,0.18) 0%, transparent 70%);
        }

        /* Badges */
        .badge-kategori-custom {
            background: linear-gradient(135deg, #ede9fe 0%, #fae8ff 100%);
            color: #6d28d9;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.42rem 0.95rem;
            border-radius: 50rem;
            border: 1px solid rgba(124, 58, 237, 0.18);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-metode-custom {
            background: linear-gradient(135deg, #e0f2fe 0%, #eff6ff 100%);
            color: #0284c7;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.42rem 0.95rem;
            border-radius: 50rem;
            border: 1px solid rgba(2, 132, 199, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-status-custom {
            background: #ecfdf5;
            color: #047857;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 0.42rem 0.95rem;
            border-radius: 50rem;
            border: 1px solid #a7f3d0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .dot-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.28);
        }

        /* Title & Ringkasan */
        .judul-detail {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1e1b4b;
            line-height: 1.35;
            margin-bottom: 0.6rem;
        }

        .ringkasan-box {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-left: 4px solid var(--purple-primary);
            padding: 0.85rem 1.15rem;
            border-radius: 0 14px 14px 0;
            font-size: 0.88rem;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 1.25rem;
        }

        /* Section Titles */
        .section-heading {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1e1b4b;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 1.35rem;
            margin-bottom: 0.85rem;
        }

        .section-heading i {
            color: var(--purple-primary);
        }

        /* Info Grid Cards */
        .info-card-item {
            background: #ffffff;
            border: 1px solid #e9e8f6;
            border-radius: 16px;
            padding: 0.95rem 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(123, 90, 246, 0.04);
        }

        .info-card-item:hover {
            transform: translateY(-2px);
            border-color: rgba(123, 90, 246, 0.35);
            box-shadow: 0 8px 20px rgba(123, 90, 246, 0.1);
        }

        .info-icon-wrapper {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .icon-purple  { background: #ede9fe; color: #7c3aed; }
        .icon-amber   { background: #fef3c7; color: #d97706; }
        .icon-cyan    { background: #cffafe; color: #0891b2; }
        .icon-rose    { background: #ffe4e6; color: #e11d48; }
        .icon-emerald { background: #d1fae5; color: #059669; }

        .info-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: block;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
            display: block;
            line-height: 1.25;
        }

        /* Deskripsi Box */
        .deskripsi-wrapper {
            background: #fbfbfe;
            border: 1px solid #eef0f8;
            border-radius: 16px;
            padding: 1.15rem 1.35rem;
            font-size: 0.86rem;
            line-height: 1.7;
            color: #475569;
        }

        /* Price Highlight Card */
        .price-card-glow {
            background: linear-gradient(135deg, #f5f3ff 0%, #faf5ff 50%, #eff6ff 100%);
            border: 1.5px solid rgba(123, 90, 246, 0.28);
            border-radius: 20px;
            padding: 1.15rem 1.4rem;
            box-shadow: 0 12px 28px -6px rgba(123, 90, 246, 0.16);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .price-label-badge {
            font-size: 0.72rem;
            font-weight: 800;
            color: #6d28d9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 3px;
        }

        .price-amount {
            font-size: 1.45rem;
            font-weight: 800;
            color: #5b21b6;
            letter-spacing: -0.5px;
        }

        /* Footer */
        .modal-footer-custom {
            background: #faf8ff;
            border-top: 1px solid var(--purple-border);
            padding: 1.15rem 1.6rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-tutup {
            background: #ffffff;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 10px 22px;
            border-radius: 50rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-tutup:hover {
            background: #f1f5f9;
            color: #334155;
            border-color: #cbd5e1;
        }

        .btn-daftar-glow {
            background: var(--purple-gradient);
            color: #ffffff !important;
            border: none;
            font-weight: 800;
            font-size: 0.92rem;
            padding: 11px 26px;
            border-radius: 50rem;
            box-shadow: 0 10px 24px rgba(109, 40, 217, 0.32);
            transition: all 0.25s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }

        .btn-daftar-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(109, 40, 217, 0.44);
            color: #ffffff !important;
        }

        .btn-close-stylish {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #64748b;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 1rem;
        }

        .btn-close-stylish:hover {
            background: #fee2e2;
            color: #ef4444;
            transform: rotate(90deg);
        }
    </style>
</head>
<body>

<div class="modal-box-container">
    <div class="card detail-card">
        
        <!-- Header Modal -->
        <div class="modal-header-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background: #ede9fe; color: #7c3aed;">
                    <i class="bi bi-mortarboard-fill fs-6"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold fs-6 text-dark">Informasi Detail Pelatihan</h5>
                    <small class="text-muted" style="font-size: 0.74rem;">Creativemu Academy Portal</small>
                </div>
            </div>
            <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn-close-stylish shadow-none" title="Tutup">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>

        <!-- Body dengan Scroll Halus -->
        <div class="modal-body-scroll">
            
            <!-- Thumbnail / Header Banner -->
            <?php 
                $gambarFile = !empty($kelas['thumbnail']) ? $kelas['thumbnail'] : (!empty($kelas['foto']) ? $kelas['foto'] : (!empty($kelas['image']) ? $kelas['image'] : null)); 
            ?>
            <div class="img-banner-wrapper">
                <?php if (!empty($gambarFile)): ?>
                    <?php if (filter_var($gambarFile, FILTER_VALIDATE_URL)): ?>
                        <img src="<?= esc($gambarFile) ?>" class="img-detail" alt="<?= esc($kelas['nama_kelas'] ?? 'Kelas Pelatihan') ?>">
                    <?php elseif (file_exists(FCPATH . 'uploads/kelas/' . $gambarFile)): ?>
                        <img src="<?= base_url('uploads/kelas/' . $gambarFile) ?>" class="img-detail" alt="<?= esc($kelas['nama_kelas'] ?? 'Kelas Pelatihan') ?>">
                    <?php else: ?>
                        <div class="banner-placeholder">
                            <i class="bi bi-stars fs-1 mb-2"></i>
                            <h4 class="fw-bold mb-0 text-white"><?= esc($kelas['kategori'] ?? 'Program Pelatihan') ?></h4>
                            <span class="small opacity-75">Creativemu Academy</span>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="banner-placeholder">
                        <i class="bi bi-laptop fs-1 mb-2"></i>
                        <h4 class="fw-bold mb-0 text-white"><?= esc($kelas['kategori'] ?? 'Program Unggulan') ?></h4>
                        <span class="small opacity-75">Creativemu Academy</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kategori, Metode & Status Badges -->
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <?php if (!empty($kelas['kategori'])): ?>
                    <span class="badge-kategori-custom">
                        <i class="bi bi-tag-fill"></i>
                        <?= esc($kelas['kategori']) ?>
                    </span>
                <?php endif; ?>

                <?php 
                    $metodeTampil = !empty($kelas['metode_pembelajaran']) ? $kelas['metode_pembelajaran'] : ($kelas['tipe_kelas'] ?? '');
                    // Saring penyebutan metode belajar yang informatif
                    if (stripos((string)$metodeTampil, 'privat') !== false || empty($metodeTampil)) {
                        $metodeTampil = 'Tatap Muka & Online';
                    }
                ?>
                <span class="badge-metode-custom">
                    <i class="bi <?= (strtolower($metodeTampil) === 'online') ? 'bi-camera-video-fill' : 'bi-building-fill' ?>"></i>
                    <?= esc(ucwords($metodeTampil)) ?>
                </span>

                <?php 
                    $status = strtolower($kelas['status'] ?? 'aktif');
                    $isAktif = in_array($status, ['aktif', '1', 'active', 'approved']);
                ?>
                <span class="badge-status-custom">
                    <span class="dot-pulse"></span>
                    <?= $isAktif ? 'Pendaftaran Dibuka' : esc(ucwords($status)) ?>
                </span>
            </div>

            <!-- Judul Kelas -->
            <?php if (!empty($kelas['nama_kelas'])): ?>
                <h2 class="judul-detail"><?= esc($kelas['nama_kelas']) ?></h2>
            <?php endif; ?>

            <!-- Ringkasan Singkat (Jika ada) -->
            <?php if (!empty($kelas['ringkasan']) && $kelas['ringkasan'] !== '-'): ?>
                <div class="ringkasan-box">
                    <i class="bi bi-quote fs-5 me-1 text-primary opacity-50"></i>
                    <?= esc($kelas['ringkasan']) ?>
                </div>
            <?php endif; ?>

            <!-- Grid Highlight Informasi Kelas -->
            <div class="section-heading">
                <i class="bi bi-grid-fill"></i> Ringkasan Fasilitas & Jadwal
            </div>

            <div class="row g-2 mb-3">
                <!-- Instruktur Pengampu -->
                <?php if (!empty($kelas['nama_mentor'])): ?>
                    <div class="col-sm-6">
                        <div class="info-card-item">
                            <div class="info-icon-wrapper icon-purple">
                                <i class="bi bi-person-video3"></i>
                            </div>
                            <div class="overflow-hidden">
                                <span class="info-label">Instruktur</span>
                                <span class="info-value text-truncate" title="<?= esc($kelas['nama_mentor']) ?>">
                                    <?= esc($kelas['nama_mentor']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Jumlah Pertemuan -->
                <?php if (!empty($kelas['jumlah_pertemuan'])): ?>
                    <div class="col-sm-6">
                        <div class="info-card-item">
                            <div class="info-icon-wrapper icon-amber">
                                <i class="bi bi-calendar2-check-fill"></i>
                            </div>
                            <div>
                                <span class="info-label">Durasi Pertemuan</span>
                                <span class="info-value"><?= esc($kelas['jumlah_pertemuan']) ?> Pertemuan Intensif</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Kapasitas / Kuota -->
                <?php if (isset($kelas['kapasitas']) && $kelas['kapasitas'] !== null && $kelas['kapasitas'] !== ''): ?>
                    <div class="col-sm-6">
                        <div class="info-card-item">
                            <div class="info-icon-wrapper icon-cyan">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <span class="info-label">Kuota Tersedia</span>
                                <span class="info-value"><?= esc($kelas['kapasitas_tersedia'] ?? $kelas['kapasitas']) ?> Peserta</span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Lokasi / Media Belajar -->
                <?php if (!empty($kelas['lokasi_media'])): ?>
                    <div class="col-sm-6">
                        <div class="info-card-item">
                            <div class="info-icon-wrapper icon-rose">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="overflow-hidden">
                                <span class="info-label">Lokasi / Media</span>
                                <span class="info-value text-truncate" title="<?= esc($kelas['lokasi_media']) ?>">
                                    <?= esc($kelas['lokasi_media']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tanggal / Waktu Mulai jika tersedia -->
                <?php if (!empty($kelas['tanggal_mulai_kelas'])): ?>
                    <div class="col-sm-6">
                        <div class="info-card-item">
                            <div class="info-icon-wrapper icon-emerald">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <span class="info-label">Mulai Pelatihan</span>
                                <span class="info-value"><?= date('d F Y', strtotime($kelas['tanggal_mulai_kelas'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Informasi Biaya Investasi -->
            <?php 
                $hargaTampil = $kelas['harga_reguler'] ?? $kelas['harga'] ?? $kelas['harga_privat'] ?? null;
            ?>
            <?php if ($hargaTampil !== null && $hargaTampil !== ''): ?>
                <div class="price-card-glow mt-3 mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="info-icon-wrapper icon-purple" style="width: 46px; height: 46px; border-radius: 14px;">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                        <div>
                            <span class="price-label-badge">Biaya Investasi Pelatihan</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1" style="font-size: 0.7rem; font-weight: 700;">
                                <i class="bi bi-patch-check-fill me-1"></i> Sertifikat & Modul Lengkap
                            </span>
                        </div>
                    </div>
                    <div>
                        <?php if ((float)$hargaTampil > 0): ?>
                            <span class="price-amount">Rp <?= number_format((float)$hargaTampil, 0, ',', '.') ?></span>
                        <?php else: ?>
                            <span class="price-amount text-success">Gratis / Free</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Deskripsi Lengkap Materi -->
            <?php if (!empty($kelas['deskripsi']) && $kelas['deskripsi'] !== '-'): ?>
                <div class="section-heading">
                    <i class="bi bi-card-text"></i> Deskripsi & Silabus Pelatihan
                </div>
                <div class="deskripsi-wrapper mb-2">
                    <?= nl2br(esc($kelas['deskripsi'])) ?>
                </div>
            <?php endif; ?>

        </div>

        <!-- Footer / Tombol Aksi -->
        <div class="modal-footer-custom">
            <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn-tutup">
                <i class="bi bi-arrow-left me-1.5"></i> Tutup
            </a>

            <?php if (($kelas['kapasitas_tersedia'] ?? 1) > 0): ?>
                <a href="<?= base_url('pelatihan/pendaftaran/' . $kelas['id_kelas']) ?>" class="btn-daftar-glow">
                    <span>Daftar Sekarang</span>
                    <i class="bi bi-arrow-right-circle-fill ms-2 fs-5"></i>
                </a>
            <?php else: ?>
                <button type="button" class="btn btn-secondary rounded-pill px-4 py-2.5 fw-bold shadow-sm flex-grow-1" style="font-size: 0.88rem;" disabled>
                    <i class="bi bi-lock-fill me-1"></i> Kuota Kelas Penuh
                </button>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
