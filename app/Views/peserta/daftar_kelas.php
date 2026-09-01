<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas Pelatihan - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --purple-primary: #7c3aed;    
            --purple-hover: #6d28d9;
            --purple-light: #f5f3ff;      
            --purple-gradient-start: #8b5cf6;
            --purple-gradient-end: #4c1d95;  
            --bg-light: #f8fafc;         
            --text-dark: #1e1b4b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(139, 92, 246, 0.1) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 4px 25px rgba(124, 58, 237, 0.1);
            padding: 14px 0;
            border-bottom: 1px solid rgba(221, 214, 254, 0.8);
        }

        .navbar-logo {
            width: 46px;
            height: 46px;
            object-fit: contain;
            border-radius: 10px;
        }

        .navbar-brand span {
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-muted) !important;
            transition: color 0.2s ease;
            cursor: pointer;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--purple-primary) !important;
        }

        .btn-outline-custom-auth {
            border: 1.5px solid #7c3aed;
            color: #7c3aed;
            background: transparent;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 8px 16px;
            transition: all 0.25s ease;
        }
        .btn-outline-custom-auth:hover {
            background: rgba(124, 58, 237, 0.05);
            color: #6d28d9;
            border-color: #6d28d9;
        }

        .btn-custom-auth {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 8px 18px;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.35);
            transition: all 0.25s ease;
        }
        .btn-custom-auth:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            color: #ffffff;
            box-shadow: 0 6px 16px rgba(124, 58, 237, 0.45);
            transform: translateY(-1px);
        }

        /* HERO SECTION DIPERBAIKI AGAR TIDAK TUMPANG TINDIH */
        .hero-section {
            background: linear-gradient(135deg, rgba(245, 243, 255, 0.95) 0%, rgba(237, 233, 254, 0.9) 100%);
            padding: 40px 0 60px 0;
            border-bottom: 1px solid rgba(221, 214, 254, 0.8);
            position: relative;
            overflow: hidden;
            margin-bottom: -30px; /* Memberikan ruang transisi yang pas untuk filter box */
        }

        .page-title {
            font-weight: 800;
            color: #1e1b4b;
            letter-spacing: -0.8px;
            font-size: 2.2rem;
        }

        .filter-container {
            background: #ffffff;
            border-radius: 24px;
            padding: 20px;
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.08);
            border: 1px solid rgba(221, 214, 254, 0.9);
            position: relative;
            z-index: 10;
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .search-input, .filter-select {
            border: 1.5px solid #ede9fe;
            border-radius: 14px;
            padding: 12px 16px;
            font-size: 0.92rem;
            color: var(--text-dark);
            background-color: #faf5ff;
            width: 100%;
            transition: all 0.25s ease;
        }

        .search-input:focus, .filter-select:focus {
            border-color: var(--purple-primary);
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.15);
            background-color: #ffffff;
            outline: none;
        }

        .kelas-card {
            border: 1px solid rgba(221, 214, 254, 0.8);
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.06);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .kelas-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(124, 58, 237, 0.2);
            border-color: rgba(124, 58, 237, 0.6);
        }
        
        .img-wrapper {
            position: relative;
            height: 200px;
            width: 100%;
            background-color: #f5f3ff;
            overflow: hidden;
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .kelas-card:hover .img-wrapper img {
            transform: scale(1.1);
        }

        .badge-kategori {
            background: rgba(124, 58, 237, 0.95);
            backdrop-filter: blur(6px);
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.4px;
        }

        .badge-metode {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            color: #1e1b4b;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.7rem;
        }

        .badge-status-aktif {
            background-color: #d1fae5;
            color: #065f46;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .badge-status-nonaktif {
            background-color: #fee2e2;
            color: #991b1b;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .judul-kelas {
            font-size: 1.12rem;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em;
            margin-bottom: 0.5rem;
            transition: color 0.2s ease;
        }

        .kelas-card:hover .judul-kelas {
            color: var(--purple-primary);
        }
        
        .deskripsi-kelas {
            font-size: 0.85rem;
            color: var(--text-muted);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .box-harga {
            background: #faf5ff;
            border: 1px dashed #c4b5fd;
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 1rem;
        }

        .btn-outline-detail {
            border: 1.5px solid #7c3aed;
            color: #7c3aed;
            background: #ffffff;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 9px 10px;
            transition: all 0.25s ease;
        }
        .btn-outline-detail:hover {
            background: rgba(124, 58, 237, 0.05);
            color: #6d28d9;
            border-color: #6d28d9;
        }

        .btn-custom-daftar {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 9px 10px;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.35);
            transition: all 0.3s ease;
        }
        .btn-custom-daftar:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.45);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<!-- Navbar Atas -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container px-lg-4">
        <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>" alt="Logo Creativemu" class="navbar-logo shadow-sm" onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/3413/3413535.png';">
            <span class="fs-4">Creativemu Academy</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav gap-4">
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('pelatihan/daftar-kelas') ?>">Program</a></li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCekStatus" style="cursor: pointer;">
                        <i class="bi bi-search me-1"></i> Cek Status Pendaftaran
                    </a>
                </li>
            </ul>
        </div>

        <div class="d-none d-lg-flex align-items-center gap-2">
            <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-custom-auth px-3">Login</a>
            <a href="<?= base_url('auth/register') ?>" class="btn btn-custom-auth px-3">Sign Up</a>
        </div>
    </div>
</nav>

<!-- HERO SECTION (DITAMBAHKAN KEMBALI AGAR JUDUL & SEARCH TIDAK TUMPANG TINDIH) -->
<div class="hero-section">
    <div class="container px-lg-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title mb-2">Daftar Program Pelatihan</h1>
                <p class="text-muted mb-0">Temukan kelas pengembangan skill profesional terbaik yang sesuai dengan kebutuhan kariermu.</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content / Daftar Kelas -->
<div class="container px-lg-4 py-3">

    <!-- NOTIFIKASI FLASH MESSAGE (SUKSES / ERROR) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 500;">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 500;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Kotak Filter Pencarian -->
    <div class="filter-container">
        <form action="" method="get" class="row g-3 align-items-center">
            <div class="col-lg-7">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #5b21b6;"></i>
                    <input type="text" name="keyword" class="search-input ps-5" placeholder="Cari nama kelas atau materi..." value="<?= esc($_GET['keyword'] ?? '') ?>">
                </div>
            </div>
            <div class="col-lg-3">
                <select name="kategori" class="filter-select">
                    <option value="">Semua Kategori</option>
                </select>
            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-custom-daftar w-100 py-2.5">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Kelas Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mt-2">
        <?php if (empty($kelas)) : ?>
            <div class="col-12 text-center py-5">
                <div class="card border-0 shadow-sm p-5 rounded-4 bg-white mx-auto animate-fade-in" style="max-width: 500px;">
                    <div class="p-4 rounded-circle d-inline-flex mx-auto mb-3" style="background-color: rgba(91,33,182,0.1); color: #5b21b6;">
                        <i class="bi bi-inbox fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Belum Ada Kelas Tersedia</h4>
                    <p class="text-muted mb-0 small">Saat ini belum ada data kelas pelatihan aktif yang dapat ditampilkan.</p>
                </div>
            </div>
        <?php else : ?>
            <?php $delay = 0.1; foreach ($kelas as $k) : ?>
                <div class="col d-flex animate-fade-in" style="animation-delay: <?= $delay ?>s;">
                    <div class="card kelas-card w-100">
                        <div class="img-wrapper">
                            <?php $gambarFile = $k['thumbnail'] ?? ''; ?>
                            <?php if (!empty($gambarFile) && file_exists(FCPATH . 'uploads/kelas/' . $gambarFile)) : ?>
                                <img src="<?= base_url('uploads/kelas/' . $gambarFile) ?>" alt="<?= esc($k['nama_kelas']) ?>">
                            <?php else : ?>
                                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=600&q=80" alt="Default Thumbnail">
                            <?php endif; ?>

                            <div class="position-absolute top-0 start-0 p-3" style="z-index: 5;">
                                <span class="badge-kategori shadow-sm"><?= esc($k['kategori']) ?></span>
                            </div>

                            <?php if (!empty($k['metode'])) : ?>
                                <div class="position-absolute top-0 end-0 p-3" style="z-index: 5;">
                                    <span class="badge-metode shadow-sm">
                                        <i class="bi <?= strtolower($k['metode']) == 'online' ? 'bi-camera-video-fill text-primary' : 'bi-building-fill text-success' ?> me-1"></i>
                                        <?= esc($k['metode']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-body p-3.5 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                <h3 class="judul-kelas flex-grow-1 mb-0"><?= esc($k['nama_kelas']) ?></h3>
                                <div>
                                    <?php 
                                        $status = strtolower($k['status'] ?? 'aktif');
                                        if ($status == 'aktif' || $status == '1' || $status == 'active') : 
                                    ?>
                                        <span class="badge-status-aktif">Aktif</span>
                                    <?php else : ?>
                                        <span class="badge-status-nonaktif">Non-Aktif</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <p class="deskripsi-kelas">
                                <?= esc($k['deskripsi']) ?>
                            </p>

                            <div class="small mb-2 d-flex flex-column gap-1 text-muted">
                                <div><i class="bi bi-person-workspace me-1" style="color: #5b21b6;"></i> Mentor: <strong class="text-dark"><?= esc($k['nama_mentor'] ?? '-') ?></strong></div>
                                <div class="d-flex justify-content-between">
                                    <span><i class="bi bi-clock-fill me-1" style="color: #5b21b6;"></i><?= esc($k['jumlah_pertemuan']) ?> Pertemuan</span>
                                    <span><i class="bi bi-people-fill me-1" style="color: #5b21b6;"></i>Kapasitas: <?= esc($k['kapasitas'] ?? '-') ?> org</span>
                                </div>
                            </div>

                            <div class="box-harga mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-1 small">
                                    <span class="text-muted" style="font-size: 0.78rem;">Reguler:</span>
                                    <strong class="text-dark" style="font-size: 0.85rem;">Rp <?= number_format($k['harga_reguler'] ?? 0, 0, ',', '.') ?></strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="text-muted" style="font-size: 0.78rem;">Privat:</span>
                                    <strong style="color: #5b21b6; font-size: 0.85rem;">Rp <?= number_format($k['harga_privat'] ?? 0, 0, ',', '.') ?></strong>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-1">
                                <a href="<?= base_url('pelatihan/detail/' . $k['id_kelas']) ?>" class="btn btn-outline-detail flex-fill text-center">
                                    <i class="bi bi-info-circle me-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $delay += 0.1; endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL POPUP CEK STATUS -->
<div class="modal fade" id="modalCekStatus" tabindex="-1" aria-labelledby="modalCekStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg p-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalCekStatusLabel">
                    <i class="bi bi-search text-primary me-2" style="color: #5b21b6 !important;"></i>Cek Status Pendaftaran Pelatihan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted small mb-4">Masukkan Email atau Nomor HP yang Anda gunakan saat mendaftar untuk melacak status validasi pendaftaran Anda.</p>

                <form id="formCekStatus">
                    <div class="input-group input-group-lg mb-3">
                        <input type="text" id="keywordStatus" name="keyword" class="form-control rounded-start-pill border py-3 px-4" placeholder="Contoh: email@anda.com atau 0812345..." required>
                        <button class="btn btn-custom-daftar rounded-end-pill px-4 px-md-5" type="submit" id="btnCek">
                            Cek Sekarang
                        </button>
                    </div>
                </form>

                <div id="hasilPencarianModal" class="mt-4"></div>

                <div class="alert alert-light border text-muted small rounded-3 mb-0 mt-3">
                    <i class="bi bi-info-circle me-1" style="color: #5b21b6;"></i> <strong>Tips:</strong> Pastikan data email atau nomor handphone yang diinput sama persis.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script AJAX untuk Cek Status -->
<script>
document.getElementById('formCekStatus').addEventListener('submit', function(e) {
    e.preventDefault();
    lakukanCekStatus();
});

function lakukanCekStatus() {
    const keyword = document.getElementById('keywordStatus').value;
    if (!keyword) return;

    const hasilDiv = document.getElementById('hasilPencarianModal');
    const btnCek = document.getElementById('btnCek');

    btnCek.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mencari...';
    btnCek.disabled = true;
    hasilDiv.innerHTML = '';

    fetch('<?= base_url("pelatihan/ajax_cek_status") ?>?keyword=' + encodeURIComponent(keyword))
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                return response.text().then(text => { throw new Error(text); });
            }
        })
        .then(data => {
            btnCek.innerHTML = 'Cek Sekarang';
            btnCek.disabled = false;

            if (data.status === 'success') {
                let statusVal = data.data.status_pembayaran ? data.data.status_pembayaran.toLowerCase() : 'pending';

                let badgeClass = 'bg-warning text-dark';
                let statusTeks = 'Pending (Menunggu Validasi)';
                
                if (statusVal === 'valid' || statusVal === 'diterima') {
                    badgeClass = 'bg-success';
                    statusTeks = 'Diterima / Valid';
                } else if (statusVal === 'rejected' || statusVal === 'ditolak') {
                    badgeClass = 'bg-danger';
                    statusTeks = 'Ditolak';
                }

                let htmlInfoTambahan = '';
                if (statusVal === 'valid' || statusVal === 'diterima') {
                    htmlInfoTambahan = `
                        <div class="alert alert-success mt-3 mb-2 small">
                            <strong>Pendaftaran Anda telah disetujui!</strong> Silakan membuat akun terlebih dahulu untuk mengakses dashboard peserta.
                        </div>
                        <a href="<?= base_url('auth/register') ?>" class="btn btn-custom-daftar w-100 mt-2 btn-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> Buat Akun Sekarang
                        </a>
                    `;
                } else if (statusVal === 'rejected' || statusVal === 'ditolak') {
                    htmlInfoTambahan = `
                        <div class="alert alert-danger mt-3 mb-2 small">
                            <strong>Pendaftaran Ditolak!</strong> Alasan: ${data.data.alasan_penolakan || 'Tidak valid'}. Silakan perbarui data diri dan unggah ulang bukti pembayaran Anda.
                        </div>
                        <a href="<?= base_url('pelatihan/upload_ulang/') ?>${data.data.id_pendaftaran}" class="btn btn-warning fw-bold text-dark w-100 mt-2 btn-sm">
                            <i class="bi bi-pencil-square me-1"></i> Buka Menu Edit & Upload Ulang Data
                        </a>
                    `;
                } else {
                    htmlInfoTambahan = `
                        <div class="alert alert-info mt-3 mb-2 small">
                            Bukti pembayaran Anda sedang dalam antrean pengecekan oleh Admin. Mohon menunggu.
                        </div>
                    `;
                }

                hasilDiv.innerHTML = `
                    <div class="card border shadow-sm p-3 rounded-3 bg-light">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-check-circle-fill text-success me-1"></i> Data Ditemukan</h6>
                        <div class="row small mb-2">
                            <div class="col-6"><strong>Nama:</strong> ${data.data.nama}</div>
                            <div class="col-6"><strong>No HP:</strong> ${data.data.no_hp}</div>
                        </div>
                        <div class="small mb-2"><strong>Status:</strong> <span class="badge ${badgeClass}">${statusTeks}</span></div>
                        ${htmlInfoTambahan}
                    </div>
                `;
            } else {
                hasilDiv.innerHTML = `
                    <div class="alert alert-warning small mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            btnCek.innerHTML = 'Cek Sekarang';
            btnCek.disabled = false;
            hasilDiv.innerHTML = `
                <div class="alert alert-danger small mb-0">
                    <strong>Terjadi Kesalahan Sistem:</strong><br>${error.message}
                </div>
            `;
        });
}
</script>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>