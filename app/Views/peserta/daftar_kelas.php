<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<style>
    /* Styling Halaman Umum - Latar Belakang Premium */
    body {
        background-color: #f8fafc;
        background-image: 
            radial-gradient(at 10% 10%, rgba(237, 233, 254, 0.8) 0px, transparent 50%),
            radial-gradient(at 90% 90%, rgba(224, 242, 254, 0.6) 0px, transparent 50%);
        background-attachment: fixed;
    }

    .page-title {
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    /* Kartu Kelas Modern & Premium */
    .kelas-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .kelas-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(111, 66, 193, 0.12);
    }
    
    /* Gambar & Wrapper dengan Efek Zoom */
    .img-wrapper {
        position: relative;
        height: 210px;
        width: 100%;
        background-color: #f8fafc;
        overflow: hidden;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .kelas-card:hover .img-wrapper img {
        transform: scale(1.08);
    }

    /* Gradient Overlay agar Teks Badge Mudah Dibaca */
    .img-wrapper::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0.2) 100%);
        pointer-events: none;
    }

    /* Badge & Label Modern */
    .badge-kategori {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        color: #4f46e5;
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.72rem;
        letter-spacing: 0.3px;
    }
    .badge-tipe {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.72rem;
    }
    .badge-aktif {
        background: rgba(16, 185, 129, 0.9);
        backdrop-filter: blur(8px);
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.72rem;
    }

    /* Tipografi Konten Lebih Estetik */
    .judul-kelas {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
        transition: color 0.2s;
        margin-bottom: 0.5rem;
    }
    .kelas-card:hover .judul-kelas {
        color: #6f42c1;
    }
    
    .ringkasan-kelas {
        font-size: 0.875rem;
        color: #64748b;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 2.8em;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    /* Kotak Informasi Detail */
    .info-box {
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 12px;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 1rem;
    }
    
    .harga-text {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0d9488;
    }

    /* TOMBOL AKSI: UNGU PASTEL YANG ELEGAN */
    .btn-custom-detail {
        background-color: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 10px 12px;
        transition: all 0.2s ease;
    }
    .btn-custom-detail:hover {
        background-color: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    /* Tombol Daftar Ungu Pastel */
    .btn-custom-daftar {
        background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 10px 12px;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.25);
        transition: all 0.2s ease;
    }
    .btn-custom-daftar:hover {
        background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
        color: #ffffff;
        box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
        transform: translateY(-1px);
    }
    
    .btn-custom-full {
        background-color: #f1f5f9;
        color: #94a3b8;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 10px 12px;
        cursor: not-allowed;
    }

    /* Header Badge Total Kelas */
    .total-kelas-badge {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        color: #0f172a;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: bold;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.03);
    }
</style>

<!-- Ditambahkan margin-top agar tidak tertutup navbar -->
<div class="container-fluid py-4 px-lg-4" style="margin-top: 40px;">

    <!-- Header Halaman -->
    <div class="mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="page-title mb-2">Daftar Kelas Pelatihan</h2>
            <p class="text-muted mb-0" style="max-width: 600px;">Eksplorasi pilihan kelas berkualitas tinggi kami, dirancang untuk mengakselerasi pertumbuhan karier dan keahlian profesional Anda.</p>
        </div>
        <div class="total-kelas-badge">
            <i class="bi bi-grid-3x3-gap text-purple me-2"></i> Total: <?= count($kelas ?? []) ?> Kelas Aktif
        </div>
    </div>

    <!-- Area Card Kelas -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        <?php if (empty($kelas)) : ?>
            <div class="col-12 text-center py-5">
                <div class="card border-0 shadow-sm p-5 rounded-4 bg-white mx-auto" style="max-width: 500px;">
                    <i class="bi bi-inbox display-3 text-muted mb-4"></i>
                    <h4 class="fw-bold text-dark">Belum Ada Kelas Tersedia</h4>
                    <p class="text-muted mb-0">Saat ini belum ada kelas pelatihan yang dibuka. Silakan kembali lagi nanti untuk pembaruan jadwal.</p>
                </div>
            </div>
        <?php else : ?>
            
            <?php foreach ($kelas as $k) : ?>
                <!-- Ditambahkan kelas d-flex dan w-100 agar tinggi card rata dan rapi -->
                <div class="col d-flex">
                    <div class="card kelas-card w-100">
                        
                        <!-- BAGIAN GAMBAR & BADGE (TATA LETAK AMAN & TIDAK TABRAKAN) -->
                        <div class="img-wrapper">
                            <?php $gambarFile = $k['thumbnail'] ?? ''; ?>
                            
                            <?php if (!empty($gambarFile) && file_exists(FCPATH . 'uploads/kelas/' . $gambarFile)) : ?>
                                <img src="<?= base_url('uploads/kelas/' . $gambarFile) ?>" alt="<?= esc($k['nama_kelas']) ?>">
                            <?php else : ?>
                                <img src="https://via.placeholder.com/600x400?text=Kelas+Unggulan" alt="Default Thumbnail">
                            <?php endif; ?>

                            <!-- Baris Badge Atas (Kategori di Kiri, Status Aktif di Kanan) -->
                            <div class="position-absolute top-0 start-0 end-0 p-3 d-flex justify-content-between align-items-center" style="z-index: 5;">
                                <span class="badge-kategori shadow-sm"><?= esc($k['kategori']) ?></span>
                                <span class="badge-aktif shadow-sm"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                            </div>

                            <!-- Badge Tipe Kelas (Online/Offline) di Kiri Bawah Gambar -->
                            <div class="position-absolute bottom-0 start-0 p-3" style="z-index: 5;">
                                <span class="badge-tipe shadow-sm"><i class="bi bi-display me-1"></i><?= esc($k['tipe_kelas']) ?></span>
                            </div>
                        </div>

                        <!-- BAGIAN ISI KONTEN -->
                        <div class="card-body p-4 d-flex flex-column">
                            
                            <!-- Judul & Ringkasan -->
                            <h3 class="judul-kelas"><?= esc($k['nama_kelas']) ?></h3>
                            <p class="ringkasan-kelas">
                                <?= esc($k['ringkasan'] ?? $k['deskripsi']) ?>
                            </p>
                            
                            <!-- Kotak Informasi Detail -->
                            <div class="info-box mt-auto">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-purple bg-opacity-10 text-purple rounded-circle p-1.5 me-2 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                        <i class="bi bi-person-fill fs-7" style="color: #7c3aed;"></i>
                                    </div>
                                    <span class="text-truncate fw-semibold text-dark" title="<?= esc($k['nama_mentor'] ?? 'Belum ditentukan') ?>">
                                        <?= esc($k['nama_mentor'] ?? 'Belum ditentukan') ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-muted border-top pt-2 mt-1 small">
                                    <span><i class="bi bi-calendar-event text-primary me-1"></i><?= esc($k['tanggal_mulai_kelas']) ?></span>
                                    <span class="fw-bold text-dark"><i class="bi bi-layers text-warning me-1"></i><?= esc($k['jumlah_pertemuan']) ?> Pertemuan</span>
                                </div>
                            </div>

                            <!-- Harga & Sisa Kuota -->
                            <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                                <div class="harga-text">
    <span class="d-block">Reg: Rp <?= number_format($k['harga_reguler'] ?? 0, 0, ',', '.') ?></span>
    <span class="d-block text-muted" style="font-size: 0.85rem;">Privat: Rp <?= number_format($k['harga_privat'] ?? 0, 0, ',', '.') ?></span>
</div>
                                <div class="text-end">
                                    <?php 
                                    $sisa = $k['sisa'] ?? 0;
                                    $kapasitas = $k['kapasitas'] ?? 0;
                                    ?>
                                    <?php if ($sisa > 0) : ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                            Sisa: <?= $sisa ?>/<?= $kapasitas ?> kursi
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 0.72rem;">
                                            Kelas Penuh
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- TOMBOL AKSI SEJAJAR (DETAIL & DAFTAR SEKARANG - UNGU PASTEL) -->
                            <div class="row g-2">
                                <div class="col-5">
                                    <!-- Tombol menuju halaman rincian detail kelas -->
                                    <a href="<?= base_url('pelatihan/detail-kelas?id=' . $k['id_kelas']) ?>" class="btn btn-custom-detail w-100 text-center">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </a>
                                </div>
                                <div class="col-7">
                                    <?php if ($sisa > 0) : ?>
                                        <!-- Tombol menuju form pendaftaran kelas -->
                                        <a href="<?= base_url('pelatihan/pendaftaran?id=' . $k['id_kelas']) ?>" class="btn btn-custom-daftar w-100 text-center">
                                            Daftar <i class="bi bi-arrow-right-short ms-1"></i>
                                        </a>
                                    <?php else : ?>
                                        <button class="btn btn-custom-full w-100 text-center" disabled>Penuh</button>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
            
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>