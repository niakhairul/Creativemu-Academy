<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Formulir Pendaftaran Pelatihan - Creativemu Academy</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --purple-primary: #7a3ff2;
            --purple-hover: #682bd8;
            --purple-dark: #241147;
            --purple-title: #341a66;
            --purple-label: #3a1e70;
            --purple-subtle: #f4eeff;
            --purple-card-bg: #faf8ff;
            --purple-border: #dfd2f7;
            --purple-border-focus: #8b53ff;
            --purple-glow: rgba(122, 63, 242, 0.14);
            --bg-page: #f8f6fc;
            --text-main: #1e1338;
            --text-muted: #64567d;
            --white: #ffffff;
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 24px;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-page);
            background-image: 
                radial-gradient(circle at 5% 5%, rgba(138, 83, 255, 0.08) 0%, transparent 42%),
                radial-gradient(circle at 95% 8%, rgba(192, 132, 252, 0.07) 0%, transparent 45%),
                radial-gradient(circle at 50% 95%, rgba(122, 63, 242, 0.05) 0%, transparent 55%);
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text-main);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Container Optimal 90% - 94% (Max 1460px) */
        .page-wrapper {
            width: 93%;
            max-width: 1460px;
            margin: 0 auto;
            padding: 24px 0 60px 0;
        }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #7a3ff2 0%, #6324d4 50%, #4a13a8 100%);
            color: #ffffff;
            border-radius: var(--radius-xl);
            position: relative;
            overflow: hidden;
            box-shadow: 0 16px 36px -8px rgba(109, 40, 217, 0.26);
            padding: 38px 32px;
            margin-bottom: 24px;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.09);
            filter: blur(25px);
            pointer-events: none;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(237, 222, 255, 0.14);
            filter: blur(25px);
            pointer-events: none;
        }

        .logo-wrap {
            width: 74px;
            height: 74px;
            padding: 4px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Ringkasan Kelas Terpilih (Wide Horizontal Card) */
        .class-summary-card {
            background: var(--white);
            border: 1.5px solid var(--purple-border);
            border-radius: var(--radius-lg);
            padding: 22px 28px;
            margin-bottom: 24px;
            box-shadow: 0 10px 28px rgba(109, 40, 217, 0.05);
            width: 100%;
            box-sizing: border-box;
        }

        .class-thumb-sm {
            width: 110px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Section Cards */
        .form-section-card {
            background: var(--white);
            border: 1.5px solid var(--purple-border);
            border-radius: var(--radius-lg);
            padding: 32px 34px;
            margin-bottom: 24px;
            box-shadow: 0 8px 25px rgba(99, 42, 196, 0.04);
            transition: box-shadow 0.25s ease, border-color 0.25s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .form-section-card:hover {
            box-shadow: 0 12px 32px rgba(99, 42, 196, 0.07);
            border-color: #cfbcf5;
        }

        /* Card Persetujuan Paling Atas */
        .persetujuan-card-top {
            border: 2px solid #cfbcf5;
            background: #ffffff;
            box-shadow: 0 12px 32px rgba(122, 63, 242, 0.08);
            position: relative;
        }

        .persetujuan-badge-flag {
            position: absolute;
            top: -12px;
            left: 28px;
            background: linear-gradient(135deg, #7a3ff2 0%, #6324d4 100%);
            color: #ffffff;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 50rem;
            box-shadow: 0 4px 12px rgba(122, 63, 242, 0.28);
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 22px;
            padding-bottom: 15px;
            border-bottom: 1.5px solid rgba(122, 63, 242, 0.1);
        }

        .section-icon-badge {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--purple-subtle) 0%, #e8dbff 100%);
            color: var(--purple-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.28rem;
            margin-right: 16px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(122, 63, 242, 0.08);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--purple-title);
            margin: 0;
            line-height: 1.28;
        }

        .section-subtitle {
            font-size: 0.86rem;
            color: var(--text-muted);
            margin: 3px 0 0 0;
        }

        /* Controls & Labels */
        .form-label-custom {
            font-size: 0.94rem;
            font-weight: 600;
            color: var(--purple-label);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .required-star {
            color: #e11d48;
            margin-left: 3px;
            font-weight: 700;
        }

        .form-control, .form-select {
            min-height: 50px;
            border: 1.5px solid var(--purple-border);
            background-color: var(--purple-card-bg);
            color: var(--text-main);
            border-radius: var(--radius-md);
            padding: 0.72rem 1.1rem;
            font-size: 0.98rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control::placeholder {
            color: #9f94b8;
            font-size: 0.92rem;
            font-weight: 400;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--purple-border-focus);
            background-color: #ffffff;
            color: var(--text-main);
            box-shadow: 0 0 0 4px var(--purple-glow);
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 105px;
        }

        /* Radio Selection Cards (Jenis Kelas & Metode Pembayaran) */
        .selection-card {
            border: 2px solid var(--purple-border);
            background-color: var(--purple-card-bg);
            border-radius: var(--radius-md);
            padding: 18px 20px;
            cursor: pointer;
            transition: all 0.22s ease-in-out;
            display: flex;
            align-items: center;
            gap: 16px;
            height: 100%;
            user-select: none;
            width: 100%;
            box-sizing: border-box;
        }

        .selection-card:hover {
            border-color: #bfa5f2;
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(122, 63, 242, 0.08);
        }

        .btn-check:checked + .selection-card {
            border-color: var(--purple-primary) !important;
            background: linear-gradient(135deg, rgba(122, 63, 242, 0.05) 0%, rgba(122, 63, 242, 0.12) 100%) !important;
            box-shadow: 0 0 0 3px rgba(122, 63, 242, 0.18) !important;
        }

        .selection-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .btn-check:checked + .selection-card .selection-icon {
            transform: scale(1.08);
        }

        /* Upload Container & Dropzone */
        .upload-dropzone {
            border: 2px dashed var(--purple-border);
            background-color: var(--purple-card-bg);
            border-radius: var(--radius-md);
            padding: 24px 20px;
            text-align: center;
            transition: all 0.22s ease-in-out;
            cursor: pointer;
            position: relative;
            width: 100%;
            box-sizing: border-box;
        }

        .upload-dropzone:hover {
            border-color: var(--purple-primary);
            background-color: #f7f2ff;
        }

        .upload-dropzone.disabled-state {
            background-color: #f3f3f6 !important;
            border-color: #dcdde2 !important;
            opacity: 0.65;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .upload-icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--purple-subtle);
            color: var(--purple-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 12px;
            transition: transform 0.2s ease;
        }

        .upload-dropzone:hover .upload-icon-circle {
            transform: translateY(-2px);
        }

        /* Info Boxes */
        .alert-info-purple {
            background: linear-gradient(135deg, #f7f2ff 0%, #ede3ff 100%);
            border: 1.5px solid #dccefa;
            color: #381577;
            border-radius: var(--radius-md);
            padding: 20px 24px;
        }

        .alert-info-cod {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1.5px solid #fde68a;
            color: #78350f;
            border-radius: var(--radius-md);
            padding: 20px 24px;
        }

        /* Ketentuan Box */
        .ketentuan-card {
            background: #ffffff;
            border: 1.5px solid var(--purple-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            width: 100%;
        }

        .ketentuan-card img {
            display: block;
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: contain;
            background: #fbfaff;
        }

        /* Submit Button */
        .btn-submit-main {
            background: linear-gradient(135deg, #7a3ff2 0%, #682bd8 100%);
            color: #ffffff;
            border: none;
            padding: 1.15rem 2rem;
            font-size: 1.12rem;
            font-weight: 700;
            border-radius: var(--radius-md);
            box-shadow: 0 12px 28px -4px rgba(122, 63, 242, 0.4);
            transition: all 0.22s ease-in-out;
            letter-spacing: 0.3px;
        }

        .btn-submit-main:hover {
            background: linear-gradient(135deg, #6d2fd9 0%, #581ebf 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 16px 32px -4px rgba(122, 63, 242, 0.48);
        }

        .btn-submit-main:active {
            transform: translateY(0);
        }

        .btn-copy {
            background: #ffffff;
            color: var(--purple-primary);
            border: 1.5px solid var(--purple-border);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-copy:hover {
            background: var(--purple-primary);
            color: #ffffff;
            border-color: var(--purple-primary);
        }

        .badge-optional {
            background: #f1ecfa;
            color: #694b9e;
            font-size: 0.74rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 6px;
        }

        /* ================================================= */
        /* RESPONSIVE MEDIA QUERIES (Mobile & Tablet)        */
        /* ================================================= */
        @media (max-width: 991.98px) {
            .page-wrapper {
                width: 96%;
                padding: 16px 0 40px 0;
            }
            .form-section-card {
                padding: 24px 20px;
                border-radius: 16px;
            }
            .hero-banner {
                padding: 28px 20px;
                border-radius: 18px;
            }
            .class-summary-card {
                padding: 18px 20px;
            }
        }

        @media (max-width: 767.98px) {
            .page-wrapper {
                width: 100%;
                padding: 10px 12px 36px 12px;
            }
            .form-section-card {
                padding: 20px 16px;
                border-radius: 14px;
                margin-bottom: 18px;
            }
            .hero-banner {
                padding: 24px 16px;
                border-radius: 16px;
                margin-bottom: 18px;
            }
            .class-summary-card {
                padding: 16px 14px;
                border-radius: 14px;
                margin-bottom: 18px;
            }
            .class-thumb-sm {
                width: 100%;
                height: 140px;
            }
            .section-header {
                margin-bottom: 18px;
                padding-bottom: 12px;
            }
            .section-icon-badge {
                width: 40px;
                height: 40px;
                font-size: 1.15rem;
                margin-right: 12px;
            }
            .section-title {
                font-size: 1.12rem;
            }
            .section-subtitle {
                font-size: 0.8rem;
            }
            .form-control, .form-select {
                font-size: 16px; /* Cegah auto-zoom iOS Safari */
                padding: 0.68rem 0.95rem;
                min-height: 48px;
            }
            .form-label-custom {
                font-size: 0.9rem;
            }
            .btn-submit-main {
                padding: 1rem 1.2rem;
                font-size: 1.02rem;
            }
            .persetujuan-badge-flag {
                left: 16px;
                font-size: 0.68rem;
                padding: 3px 10px;
            }
        }

        @media (max-width: 480px) {
            .page-wrapper {
                padding: 8px 8px 30px 8px;
            }
            .form-section-card {
                padding: 18px 12px;
            }
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    
    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4 d-flex align-items-center" role="alert">
        <i class="fas fa-exclamation-circle fs-4 me-3 text-danger flex-shrink-0"></i>
        <div>
            <strong>Gagal Memproses Pendaftaran:</strong>
            <div class="small mt-1"><?= esc(session()->getFlashdata('error')) ?></div>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($user['is_logged_in'])): ?>
    <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center shadow-sm" style="background: #ffffff; border: 1.5px solid var(--purple-border); color: var(--purple-primary); text-decoration: none;">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Kelas Saya
        </a>
        <span class="badge px-3 py-2 rounded-pill fw-semibold" style="background: var(--purple-subtle); color: var(--purple-primary); font-size: 0.82rem;">
            <i class="bi bi-person-check-fill me-1"></i> Akun Peserta: <?= esc($user['nama'] ?? 'Peserta') ?>
        </span>
    </div>
    <?php endif; ?>

    <!-- HERO BANNER (Full Width, Profesional) -->
    <div class="hero-banner text-center">
        <div class="position-relative" style="z-index: 2;">
            <div class="logo-wrap mb-3">
    <img src="<?= base_url('assets/img/logo_creativemu.jpg') ?>"
         alt="Creativemu Academy Logo"
         style="width: 100%; height: 100%; object-fit: contain; border-radius: 14px; padding: 0; transform: scale(1.35);">
</div>
            <h1 class="fw-bold mb-2 fs-2">Formulir Pendaftaran Pelatihan</h1>
            <p class="text-white-50 mb-0 mx-auto" style="max-width: 680px; font-size: 0.95rem; line-height: 1.6;">
                Tingkatkan kompetensi profesional Anda bersama instruktur ahli Creativemu Academy. Lengkapi data di bawah ini untuk mengamankan slot pelatihan eksklusif Anda.
            </p>
        </div>
    </div>

    <?php 
        $kelas = isset($kelas) ? $kelas : [];
        $user  = isset($user) ? $user : [];
        $fotoKelas = !empty($kelas['thumbnail']) ? $kelas['thumbnail'] : (!empty($kelas['foto']) ? $kelas['foto'] : 'default.jpg');
        $tipeKelas = !empty($kelas['tipe_kelas']) ? $kelas['tipe_kelas'] : 'Online'; 
        $mentorName = !empty($kelas['nama_mentor']) ? $kelas['nama_mentor'] : (!empty($kelas['mentor']) ? $kelas['mentor'] : (!empty($kelas['pengajar']) ? $kelas['pengajar'] : 'Mentor'));
    ?>

    <!-- RINGKASAN KELAS TERPILIH (Wide Banner Horizontal) -->
    <div class="class-summary-card">
        <div class="row g-3 align-items-center">
            <div class="col-md-auto text-center text-md-start">
                <img id="imgSummaryFoto" src="<?= base_url('uploads/kelas/' . $fotoKelas); ?>" alt="Banner <?= esc($kelas['nama_kelas'] ?? 'Kelas') ?>" class="class-thumb-sm" onerror="this.onerror=null; this.src='<?= base_url('assets/img/default-class.jpg') ?>';">
            </div>
            <div class="col-md">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <span class="badge" id="badgeSummaryKategori" style="background: var(--purple-subtle); color: var(--purple-primary); font-weight: 700; font-size: 0.76rem;">
                        <i class="bi bi-mortarboard-fill me-1"></i> <?= esc($kelas['kategori'] ?? 'Pelatihan'); ?>
                    </span>
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold" id="badgeSummaryTipe" style="font-size: 0.76rem;">
                        <i class="bi bi-broadcast me-1"></i> <?= esc(ucfirst($tipeKelas)) ?>
                    </span>
                </div>
                <h4 class="fw-bold mb-1 fs-5" id="titleSummaryNamaKelas" style="color: var(--purple-dark);">
                    <?= esc($kelas['nama_kelas'] ?? $kelas['nama'] ?? 'Pelatihan CreativeMU') ?>
                </h4>
                <div class="d-flex flex-wrap align-items-center gap-3 text-muted small">
                    <span><i class="bi bi-person-badge text-primary me-1"></i> Mentor: <strong id="textSummaryMentor"><?= esc($mentorName) ?></strong></span>
                    <span><i class="bi bi-calendar3 text-primary me-1"></i> Mulai: <strong id="textSummaryTanggal"><?= esc($kelas['tanggal_mulai_kelas'] ?? $kelas['awal_pelatihan'] ?? 'Sesuai Jadwal') ?></strong></span>
                    <span><i class="bi bi-layers text-success me-1"></i> Durasi: <strong id="textSummaryDurasi"><?= esc($kelas['jumlah_pertemuan'] ?? '6') ?> Sesi</strong></span>
                </div>
            </div>
            <div class="col-md-auto border-start-md ps-md-4 text-center text-md-end">
                <div class="small text-muted mb-1" style="font-size: 0.76rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Biaya Investasi</div>
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                    <span class="badge px-3 py-2 bg-success bg-opacity-10 text-success fw-bold" id="textSummaryHargaReguler" style="font-size: 0.85rem;">
                        Reguler: Rp <?= number_format($kelas['harga_reguler'] ?? 0, 0, ',', '.') ?>
                    </span>
                    <span class="badge px-3 py-2" id="textSummaryHargaPrivat" style="background: var(--purple-subtle); color: var(--purple-primary); font-weight: 700; font-size: 0.85rem;">
                        Privat: Rp <?= number_format($kelas['harga_privat'] ?? 0, 0, ',', '.') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM UTAMA -->
    <form action="<?= base_url('pelatihan/simpan-pendaftaran') ?>" method="post" enctype="multipart/form-data" id="formPendaftaran">
        <?= csrf_field() ?>
        
        <!-- Hidden Fields Sistem -->
        <input type="hidden" name="id_kelas" id="hidden_id_kelas" value="<?= $kelas['id_kelas'] ?? $kelas['id'] ?? ''; ?>">
        <input type="hidden" name="pilihan_pelatihan" id="hidden_pilihan_pelatihan" value="<?= esc($kelas['nama_kelas'] ?? $kelas['nama'] ?? 'Pelatihan Umum') ?>">
        <input type="hidden" name="pilihan_kelas" id="hidden_pilihan_kelas" value="<?= esc($kelas['nama_kelas'] ?? $kelas['nama'] ?? 'Kelas Umum') ?>">
        <input type="hidden" name="tanggal_mulai_kelas" id="hidden_tanggal_mulai_kelas" value="<?= esc($kelas['tanggal_mulai_kelas'] ?? $kelas['awal_pelatihan'] ?? date('Y-m-d')) ?>">

        <!-- ============================================================== -->
        <!-- 1. BAGIAN PERSETUJUAN (PALING ATAS, LEBAR PENUH CARD KHUSUS)  -->
        <!-- ============================================================== -->
        <div class="form-section-card persetujuan-card-top" id="sectionPersetujuan">
            <span class="persetujuan-badge-flag">
                <i class="bi bi-check-circle-fill me-1"></i> Langkah Wajib Pertama
            </span>
            
            <div class="section-header mt-2">
                <div class="section-icon-badge">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h5 class="section-title">Persetujuan Pendaftaran</h5>
                    <p class="section-subtitle">Silakan membaca dan menyetujui komitmen & ketentuan resmi sebelum melengkapi formulir.</p>
                </div>
            </div>

            <!-- Box Pratinjau Dokumen Syarat & Ketentuan -->
            <div class="ketentuan-card shadow-sm mb-3">
                <div class="p-2 text-center bg-light border-bottom d-flex justify-content-between align-items-center px-3">
                    <span class="small fw-semibold text-muted" style="font-size: 0.78rem;">
                        <i class="bi bi-file-earmark-text me-1 text-primary"></i> Dokumen Syarat & Ketentuan Resmi CreativeMU Academy
                    </span>
                    <a href="<?= base_url('uploads/syarat_dan_persetujuan/syarat_dan_persetujuan.jpeg') ?>" target="_blank" class="small fw-bold text-decoration-none" style="color: var(--purple-primary); font-size: 0.78rem;">
                        <i class="bi bi-arrows-fullscreen me-1"></i> Perbesar Dokumen
                    </a>
                </div>
                <img src="<?= base_url('uploads/syarat_dan_persetujuan/syarat_dan_persetujuan.jpeg') ?>" alt="Syarat dan Ketentuan Pendaftaran CreativeMU Academy" class="img-fluid" onerror="this.style.display='none';">
            </div>

            <!-- Checkbox Persetujuan Wajib (*) -->
            <div class="form-check p-3 rounded-3" style="background: #faf8fe; border: 1.5px solid var(--purple-border);">
                <input class="form-check-input mt-1 ms-1 me-2" type="checkbox" value="1" id="persetujuan_syarat" name="persetujuan_syarat" checked required style="cursor: pointer; transform: scale(1.18);">
                <label class="form-check-label small" for="persetujuan_syarat" style="color: var(--purple-dark); cursor: pointer; line-height: 1.55;">
                    Saya telah membaca, memahami, dan menyetujui seluruh <a href="<?= base_url('uploads/syarat_dan_persetujuan/syarat_dan_persetujuan.jpeg') ?>" target="_blank" class="fw-bold text-decoration-underline" style="color: var(--purple-primary);">Syarat dan Ketentuan</a> serta <a href="<?= base_url('kebijakan-privasi') ?>" target="_blank" class="fw-bold text-decoration-underline" style="color: var(--purple-primary);">Kebijakan Privasi</a> yang berlaku di Creativemu Academy. <span class="required-star">*</span>
                </label>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 2 KOLOM OPTIMAL DESKTOP (DATA PRIBADI vs PILIHAN PELATIHAN)    -->
        <!-- ============================================================== -->
        <div class="row g-4">
            
            <!-- KOLOM KIRI: DATA PRIBADI PESERTA -->
            <div class="col-lg-6">
                <div class="form-section-card h-100 mb-0">
                    <div class="section-header">
                        <div class="section-icon-badge">
                            <i class="bi bi-person-vcard-fill"></i>
                        </div>
                        <div>
                            <h5 class="section-title">Data Pribadi Calon Peserta</h5>
                            <p class="section-subtitle">
                                <?php if (!empty($user['is_logged_in'])): ?>
                                    <span class="badge" style="background: var(--purple-subtle); color: var(--purple-primary); font-size: 0.76rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i> Data Terisi Otomatis dari Profil Akun Anda
                                    </span>
                                <?php else: ?>
                                    Lengkapi identitas diri Anda secara valid untuk keperluan registrasi dan sertifikat.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <?php 
                        $isLoggedIn = !empty($user['is_logged_in']);
                        $statusAktif = strtolower($user['pilihan_status'] ?? $user['status'] ?? '');
                        $pendidikanAktif = strtolower($user['pendidikan_terakhir'] ?? '');
                        $genderAktif = strtolower($user['jenis_kelamin'] ?? '');
                    ?>

                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="nama" class="form-label-custom">
                            <span>Nama Lengkap <span class="required-star">*</span></span>
                            <small class="text-muted fw-normal" style="font-size: 0.75rem;">Sesuai identitas</small>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: var(--purple-border); border-radius: var(--radius-md) 0 0 var(--radius-md);">
                                <i class="bi bi-person-fill text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" id="nama" name="nama" value="<?= esc($user['nama'] ?? '') ?>" required placeholder="Masukkan nama lengkap Anda" <?= $isLoggedIn ? 'readonly' : '' ?> style="border-radius: 0 var(--radius-md) var(--radius-md) 0;">
                        </div>
                    </div>

                    <!-- Email & No HP -->
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="email" class="form-label-custom">
                                <span>Alamat Email <span class="required-star">*</span></span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: var(--purple-border); border-radius: var(--radius-md) 0 0 var(--radius-md);">
                                    <i class="bi bi-envelope-fill text-primary"></i>
                                </span>
                                <input type="email" class="form-control border-start-0" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>" required placeholder="nama@email.com" <?= $isLoggedIn ? 'readonly' : '' ?> style="border-radius: 0 var(--radius-md) var(--radius-md) 0;">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="no_hp" class="form-label-custom">
                                <span>Nomor HP / WhatsApp <span class="required-star">*</span></span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: var(--purple-border); border-radius: var(--radius-md) 0 0 var(--radius-md);">
                                    <i class="bi bi-whatsapp text-success"></i>
                                </span>
                                <input type="tel" class="form-control border-start-0" id="no_hp" name="no_hp" value="<?= esc($user['no_hp'] ?? '') ?>" required placeholder="08xxxxxxxxxx" <?= $isLoggedIn ? 'readonly' : '' ?> style="border-radius: 0 var(--radius-md) var(--radius-md) 0;">
                            </div>
                        </div>
                    </div>

                    <!-- TTL & Jenis Kelamin -->
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="ttl" class="form-label-custom">
                                <span>Tempat, Tanggal Lahir <span class="required-star">*</span></span>
                            </label>
                            <input type="text" class="form-control" id="ttl" name="ttl" value="<?= esc($user['ttl'] ?? '') ?>" required placeholder="Contoh: Yogyakarta, 12 Mei 2002" <?= $isLoggedIn ? 'readonly' : '' ?>>
                        </div>
                        <div class="col-sm-6">
                            <label for="jenis_kelamin" class="form-label-custom">
                                <span>Jenis Kelamin <span class="required-star">*</span></span>
                            </label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required <?= $isLoggedIn ? 'disabled' : '' ?>>
                                <option value="" disabled <?= empty($genderAktif) ? 'selected' : '' ?>>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?= ($genderAktif === 'laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="Perempuan" <?= ($genderAktif === 'perempuan') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <?php if ($isLoggedIn): ?>
                                <input type="hidden" name="jenis_kelamin" value="<?= esc($user['jenis_kelamin'] ?? '') ?>">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Pendidikan Terakhir & Status -->
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="pendidikan_terakhir" class="form-label-custom">
                                <span>Pendidikan Terakhir <span class="required-star">*</span></span>
                            </label>
                            <select class="form-select" id="pendidikan_terakhir" name="pendidikan_terakhir" required <?= $isLoggedIn ? 'disabled' : '' ?>>
                                <option value="" disabled <?= empty($pendidikanAktif) ? 'selected' : '' ?>>Pilih Pendidikan Terakhir</option>
                                <option value="Smp/Sederajat" <?= (strpos($pendidikanAktif, 'smp') !== false) ? 'selected' : '' ?>>SMP / Sederajat</option>
                                <option value="Sma/Smk/Sederajat" <?= (strpos($pendidikanAktif, 'sma') !== false || strpos($pendidikanAktif, 'smk') !== false) ? 'selected' : '' ?>>SMA / SMK / Sederajat</option>
                                <option value="D3/D4" <?= (strpos($pendidikanAktif, 'd3') !== false || strpos($pendidikanAktif, 'd4') !== false) ? 'selected' : '' ?>>D3 / D4</option>
                                <option value="S1" <?= ($pendidikanAktif === 's1') ? 'selected' : '' ?>>S1 (Sarjana)</option>
                                <option value="S2" <?= ($pendidikanAktif === 's2') ? 'selected' : '' ?>>S2 (Magister)</option>
                            </select>
                            <?php if ($isLoggedIn): ?>
                                <input type="hidden" name="pendidikan_terakhir" value="<?= esc($user['pendidikan_terakhir'] ?? '') ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6">
                            <?php if (!empty($isStatusLocked) && !empty($user['status'])): ?>
                                <label class="form-label-custom">
                                    <span>Status Peserta <span class="required-star">*</span></span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle ms-1" style="font-size: 0.72rem;"><i class="bi bi-lock-fill me-1"></i> Terkunci Otomatis</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: var(--purple-border); border-radius: var(--radius-md) 0 0 var(--radius-md);">
                                        <i class="bi bi-person-badge-fill text-success"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" value="<?= esc($user['status']) ?>" readonly style="background-color: #f8f6fc; cursor: not-allowed; font-weight: 700; color: #4d1d95; border-radius: 0 var(--radius-md) var(--radius-md) 0;">
                                </div>
                                <input type="hidden" name="pilihan_status" value="<?= esc($user['status']) ?>">
                                <input type="hidden" name="status" value="<?= esc($user['status']) ?>">
                                <small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-shield-check text-success me-1"></i>Status otomatis diambil dari akun peserta Anda.</small>
                            <?php else: ?>
                                <label for="pilihan_status" class="form-label-custom">
                                    <span>Status / Profesi <span class="required-star">*</span></span>
                                </label>
                                <select class="form-select" id="pilihan_status" name="pilihan_status" required <?= $isLoggedIn ? 'disabled' : '' ?>>
                                    <option value="" disabled <?= empty($statusAktif) ? 'selected' : '' ?>>Pilih Status Saat Ini</option>
                                    <option value="Pelajar SMP" <?= ($statusAktif === 'pelajar smp') ? 'selected' : '' ?>>Pelajar SMP</option>
                                    <option value="Pelajar SMA/SMK" <?= ($statusAktif === 'pelajar sma/smk' || $statusAktif === 'pelajar') ? 'selected' : '' ?>>Pelajar SMA / SMK</option>
                                    <option value="Mahasiswa" <?= ($statusAktif === 'mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                                    <option value="Guru/Dosen" <?= (strpos($statusAktif, 'guru') !== false || strpos($statusAktif, 'dosen') !== false) ? 'selected' : '' ?>>Guru / Dosen</option>
                                    <option value="Karyawan" <?= (strpos($statusAktif, 'karyawan') !== false) ? 'selected' : '' ?>>Karyawan Swasta</option>
                                    <option value="Pegawainegri" <?= (strpos($statusAktif, 'pegawai') !== false || strpos($statusAktif, 'asn') !== false) ? 'selected' : '' ?>>ASN / TNI / POLRI</option>
                                    <option value="Lainnya" <?= ($statusAktif === 'lainnya') ? 'selected' : '' ?>>Lainnya / Umum</option>
                                </select>
                                <?php if ($isLoggedIn): ?>
                                    <input type="hidden" name="pilihan_status" value="<?= esc($user['status'] ?? '') ?>">
                                    <input type="hidden" name="status" value="<?= esc($user['status'] ?? '') ?>">
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="mb-0">
                        <label for="alamat" class="form-label-custom">
                            <span>Alamat Lengkap Domisili <span class="required-star">*</span></span>
                        </label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required placeholder="Tuliskan nama jalan, RT/RW, kelurahan, kecamatan, kota/kabupaten tempat tinggal Anda..." <?= $isLoggedIn ? 'readonly' : '' ?>><?= esc($user['alamat'] ?? '') ?></textarea>
                    </div>

                </div>
            </div>

            <!-- KOLOM KANAN: PILIHAN PELATIHAN & DOKUMEN -->
            <div class="col-lg-6">
                <div class="d-flex flex-column gap-4 h-100">
                    
                    <!-- KARTU PILIHAN PELATIHAN & SUMBER INFORMASI -->
                    <div class="form-section-card mb-0">
                        <div class="section-header">
                            <div class="section-icon-badge">
                                <i class="bi bi-mortarboard"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Pilihan Pelatihan & Informasi</h5>
                                <p class="section-subtitle">Pilih kelas yang ingin diambil, metode belajar, dan kategori pelatihan.</p>
                            </div>
                        </div>

                        <!-- 8. PILIH KELAS PELATIHAN DARI DATABASE -->
                        <div class="mb-3">
                            <label for="select_kelas" class="form-label-custom mb-2">
                                <span>Pilih Kelas Pelatihan <span class="required-star">*</span></span>
                                <span class="badge bg-purple-subtle text-purple ms-2" style="font-size: 0.72rem;">Daftar Kelas Dibuka</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: var(--purple-border); border-radius: var(--radius-md) 0 0 var(--radius-md);">
                                    <i class="bi bi-journal-bookmark-fill text-primary"></i>
                                </span>
                                <select class="form-select border-start-0" id="select_kelas" name="id_kelas" required onchange="handleSelectKelas(this)" style="border-radius: 0 var(--radius-md) var(--radius-md) 0; font-weight: 600;">
                                    <?php if (!empty($kelasList)): ?>
                                        <?php foreach ($kelasList as $kOpt): ?>
                                            <option value="<?= esc($kOpt['id_kelas']) ?>"
                                                <?= ($kOpt['id_kelas'] == $kelas['id_kelas']) ? 'selected' : '' ?>
                                                data-nama="<?= esc($kOpt['nama_kelas']) ?>"
                                                data-kategori="<?= esc($kOpt['kategori'] ?? 'Pelatihan') ?>"
                                                data-tipe="<?= esc(strtolower($kOpt['tipe_kelas'] ?? 'offline')) ?>"
                                                data-reguler="<?= (float)($kOpt['harga_reguler'] ?? 0) ?>"
                                                data-privat="<?= (float)($kOpt['harga_privat'] ?? 0) ?>"
                                                data-mentor="<?= esc($kOpt['nama_mentor'] ?? 'Mentor Creativemu') ?>"
                                                data-tanggal="<?= esc($kOpt['tanggal_mulai_kelas'] ?? '-') ?>"
                                                data-pertemuan="<?= esc($kOpt['jumlah_pertemuan'] ?? '6') ?>"
                                                data-kuota="<?= esc($kOpt['kapasitas_tersedia'] ?? $kOpt['kapasitas'] ?? '0') ?>"
                                                data-thumbnail="<?= esc($kOpt['thumbnail'] ?? '') ?>">
                                                <?= esc($kOpt['nama_kelas']) ?> | Sisa kuota: <?= esc($kOpt['kapasitas_tersedia'] ?? $kOpt['kapasitas'] ?? '0') ?> kursi
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="<?= esc($kelas['id_kelas']) ?>" selected><?= esc($kelas['nama_kelas']) ?> | Sisa kuota: <?= esc($kelas['kapasitas_tersedia'] ?? $kelas['kapasitas'] ?? '0') ?> kursi</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;"><i class="bi bi-info-circle me-1"></i>Hanya menampilkan kelas aktif yang belum pernah Anda daftarkan.</small>
                        </div>

                        <!-- 9. Kategori Kelas (Paket Reguler vs Privat) -->
                        <div class="mb-3">
                            <label class="form-label-custom mb-2">
                                <span>Pilih Kategori Kelas (Paket) <span class="required-star">*</span></span>
                            </label>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <input type="radio" class="btn-check" name="jenis_kelas" id="kelas_reguler" value="Reguler" checked required onchange="updateBiayaTampil()">
                                    <label class="selection-card" for="kelas_reguler">
                                        <div class="selection-icon bg-success bg-opacity-10 text-success">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold" style="color: var(--text-main); font-size: 0.95rem;">Kelas Reguler</span>
                                            <small class="text-success fw-bold" id="labelHargaReguler">Rp <?= number_format($kelas['harga_reguler'] ?? 0, 0, ',', '.') ?></small>
                                            <span class="d-block text-muted" style="font-size: 0.72rem;">Belajar kelompok interaktif</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <input type="radio" class="btn-check" name="jenis_kelas" id="kelas_privat" value="Privat" onchange="updateBiayaTampil()">
                                    <label class="selection-card" for="kelas_privat">
                                        <div class="selection-icon" style="background: var(--purple-subtle); color: var(--purple-primary);">
                                            <i class="bi bi-person-fill-lock"></i>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold" style="color: var(--text-main); font-size: 0.95rem;">Kelas Privat</span>
                                            <small class="fw-bold" style="color: var(--purple-primary);" id="labelHargaPrivat">Rp <?= number_format($kelas['harga_privat'] ?? 0, 0, ',', '.') ?></small>
                                            <span class="d-block text-muted" style="font-size: 0.72rem;">1-on-1 Intensif</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Kategori Kelas & Metode Pembelajaran -->
                        <?php 
                            $tipeKelasKelas = strtolower($kelas['tipe_kelas'] ?? 'offline');
                            $isOnlineKelas = ($tipeKelasKelas === 'online');
                        ?>
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="kategori_kelas" class="form-label-custom">
                                    <span>Kategori Kelas <span class="required-star">*</span></span>
                                </label>
                                <select class="form-select" id="kategori_kelas" name="kategori_kelas" required>
                                    <option value="Basic Pelatihan" <?= (isset($kelas['kategori_kelas']) && $kelas['kategori_kelas'] == 'Basic Pelatihan') ? 'selected' : '' ?>>Basic Pelatihan (Fundamental)</option>
                                    <option value="Pelatihan Sertifikasi" <?= (isset($kelas['kategori_kelas']) && $kelas['kategori_kelas'] == 'Pelatihan Sertifikasi') ? 'selected' : '' ?>>Pelatihan Sertifikasi</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="metode_pembelajaran" class="form-label-custom">
                                    <span>Metode Pembelajaran <span class="required-star">*</span></span>
                                </label>
                                <select class="form-select" id="metode_pembelajaran" name="metode_pembelajaran" required onchange="toggleLokasiPelatihan()">
                                    <option value="online" <?= $isOnlineKelas ? 'selected' : '' ?>>Online (Virtual Class)</option>
                                    <option value="offline" <?= (!$isOnlineKelas) ? 'selected' : '' ?>>Offline (Tatap Muka)</option>
                                </select>
                            </div>
                        </div>

                        <!-- 11. Tempat Pelatihan Offline (Kondisional) -->
                        <div class="mb-3" id="lokasiPelatihanContainer" style="display: none;">
                            <div class="p-3 rounded-3" style="background: #faf8fe; border: 1.5px dashed var(--purple-border);">
                                <label for="pilihan_lokasi" class="form-label-custom">
                                    <span>Pilih Tempat Pelatihan Offline <span class="required-star">*</span></span>
                                    <span class="badge bg-warning bg-opacity-25 text-dark" style="font-size: 0.72rem;">Wajib untuk Offline</span>
                                </label>
                                <select class="form-select" id="pilihan_lokasi" name="pilihan_lokasi">
                                    <option value="" disabled selected>Pilih Lokasi Kantor / Ruang Kelas</option>
                                    <?php foreach (($lokasiPelatihan ?? []) as $lokasi): ?>
                                        <?php $labelLokasi = trim(($lokasi['nama_lokasi'] ?? '') . (!empty($lokasi['alamat']) ? ' - ' . $lokasi['alamat'] : '')); ?>
                                        <option value="<?= esc($labelLokasi) ?>"><?= esc($lokasi['nama_lokasi'] ?? '-') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Sumber Informasi (12 Pilihan Resmi) -->
                        <div class="mb-0">
                            <label for="sumber_informasi" class="form-label-custom">
                                <span>Anda mengetahui CreativeMU dari mana? <span class="required-star">*</span></span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted" style="border-color: var(--purple-border); border-radius: var(--radius-md) 0 0 var(--radius-md);">
                                    <i class="bi bi-compass-fill text-primary"></i>
                                </span>
                                <select class="form-select border-start-0" id="sumber_informasi" name="sumber_informasi" required style="border-radius: 0 var(--radius-md) var(--radius-md) 0;">
                                    <option value="" disabled selected>-- Pilih sumber informasi --</option>
                                    <option value="TikTok">TikTok</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="WhatsApp">WhatsApp</option>
                                    <option value="Brosur">Brosur</option>
                                    <option value="YouTube">YouTube</option>
                                    <option value="Twitter/X">Twitter/X</option>
                                    <option value="Teman">Teman</option>
                                    <option value="Alumni CreativeMU">Alumni CreativeMU</option>
                                    <option value="Website CreativeMU">Website CreativeMU</option>
                                    <option value="Google">Google</option>
                                    <option value="Keluarga">Keluarga</option>
                                    <option value="Media Elektronik">Media Elektronik</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- KARTU DOKUMEN PENDUKUNG (PAS FOTO) -->
                    <div class="form-section-card mb-0 flex-grow-1">
                        <div class="section-header">
                            <div class="section-icon-badge">
                                <i class="bi bi-file-earmark-person-fill"></i>
                            </div>
                            <div>
                                <h5 class="section-title">Dokumen Pendukung</h5>
                                <p class="section-subtitle">Foto profil untuk database peserta & sertifikat resmi.</p>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label for="pas_foto" class="form-label-custom mb-0">
                                    <span>Pas Foto Peserta (Ukuran 3x4)</span>
                                </label>
                                <span class="badge-optional">
                                    <i class="bi bi-info-circle me-1"></i> Opsional
                                </span>
                            </div>

                            <div class="upload-dropzone" id="dropzoneFoto" onclick="triggerFileInput('pas_foto')">
                                <div class="upload-icon-circle">
                                    <i class="bi bi-camera-fill"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: var(--purple-title); font-size: 0.96rem;">
                                    Pilih Berkas Pas Foto
                                </h6>
                                <p class="text-muted small mb-2" style="font-size: 0.8rem;">
                                    Format formal/semi-formal (JPG/PNG, Maks. 2 MB). Dapat disusulkan nanti.
                                </p>
                                <div id="previewFotoWrap" class="mt-2 text-success fw-bold small" style="display: none;">
                                    <i class="bi bi-check-circle-fill me-1"></i> File foto: <span id="namaFileFoto">-</span>
                                </div>
                                <input type="file" id="pas_foto" name="pas_foto" class="d-none" accept="image/png, image/jpeg, image/jpg" onchange="handleFileChange(this, 'namaFileFoto', 'previewFotoWrap')">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- ============================================================== -->
        <!-- 3. BAGIAN PEMBAYARAN (LEBAR PENUH OPTIMAL)                     -->
        <!-- ============================================================== -->
        <div class="form-section-card mt-4">
            <div class="section-header">
                <div class="section-icon-badge">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div>
                    <h5 class="section-title">Metode Pembayaran</h5>
                    <p class="section-subtitle">Pilih opsi pembayaran melalui COD (Bayar di Tempat) atau Transfer Bank langsung.</p>
                </div>
            </div>

            <!-- Radio Pilihan Pembayaran -->
            <div class="mb-4">
                <label class="form-label-custom mb-2">
                    <span>Pilih Metode Pembayaran <span class="required-star">*</span></span>
                </label>
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="radio" class="btn-check" name="metode_pembayaran" id="bayar_cod" value="COD" required>
                        <label class="selection-card" for="bayar_cod">
                            <div class="selection-icon bg-warning bg-opacity-15 text-warning">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div>
                                <span class="d-block fw-bold fs-6" style="color: var(--text-main);">COD (Bayar di Tempat)</span>
                                <span class="d-block text-muted" style="font-size: 0.78rem;">Pelunasan saat registrasi ulang sesi pertama di lokasi</span>
                            </div>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <input type="radio" class="btn-check" name="metode_pembayaran" id="bayar_transfer" value="Transfer">
                        <label class="selection-card" for="bayar_transfer">
                            <div class="selection-icon" style="background: var(--purple-subtle); color: var(--purple-primary);">
                                <i class="bi bi-bank2"></i>
                            </div>
                            <div>
                                <span class="d-block fw-bold fs-6" style="color: var(--text-main);">Transfer Bank</span>
                                <span class="d-block text-muted" style="font-size: 0.78rem;">Transfer via Bank BCA / M-Banking / ATM</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Instruksi Transfer Bank (Aktif jika Transfer) -->
            <div id="rekening" class="alert-info-purple mb-4 shadow-sm" style="display: none;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle-fill fs-5 me-2" style="color: var(--purple-primary);"></i>
                        <h6 class="fw-bold mb-0" style="color: var(--purple-dark);">Instruksi Pembayaran Transfer Bank:</h6>
                    </div>
                    <span class="badge" style="background: var(--purple-primary); color: #ffffff; padding: 6px 12px;">Bank BCA</span>
                </div>

                <div class="bg-white p-3 rounded-3 border mb-2 d-flex flex-wrap align-items-center justify-content-between gap-3" style="border-color: var(--purple-border) !important;">
                    <div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Nomor Rekening BCA Resmi:</small>
                        <span class="fs-4 fw-bold font-monospace" id="nomorRekeningText" style="color: var(--purple-title); letter-spacing: 1px;">1234567890</span>
                        <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">Atas Nama: <strong>Creativemu Academy</strong></small>
                    </div>
                    <button type="button" class="btn btn-copy d-inline-flex align-items-center" id="btnSalinRekening" onclick="salinRekening()">
                        <i class="bi bi-clipboard me-1" id="iconSalin"></i> <span id="labelSalin">Salin No. Rekening</span>
                    </button>
                </div>
                <small class="text-muted d-block mt-2" style="font-size: 0.78rem;">
                    <i class="bi bi-check-circle-fill text-success me-1"></i> Setelah transfer selesai, silakan unggah foto/tangkapan layar struk bukti transfer pada kotak di bawah ini.
                </small>
            </div>

            <!-- Informasi Ketentuan COD (Aktif jika COD) -->
            <div id="infoCod" class="alert-info-cod mb-4 shadow-sm" style="display: none;">
                <div class="d-flex align-items-start">
                    <i class="bi bi-clock-history fs-4 me-3 text-warning flex-shrink-0"></i>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #92400e;">Ketentuan Pembayaran COD (Bayar di Tempat):</h6>
                        <p class="small mb-1" style="color: #78350f; line-height: 1.55;">
                            Anda memilih metode COD. Pembayaran tunai/non-tunai dilakukan secara langsung di kantor atau lokasi pelatihan saat registrasi ulang sesi pertama pelatihan.
                        </p>
                        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 0.76rem;">
                            <i class="bi bi-shield-check me-1"></i> Bukti transfer tidak perlu diunggah untuk metode COD ini.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Area Upload Bukti Pembayaran -->
            <div id="uploadBuktiContainer" class="mb-0">
                <label for="bukti_pembayaran" class="form-label-custom mb-2" id="labelUploadBukti">
                    <span>Upload Bukti Transaksi <span class="required-star" id="bintangBukti">*</span></span>
                    <span class="badge" id="badgeStatusBukti" style="background: var(--purple-subtle); color: var(--purple-primary);">Wajib untuk Transfer</span>
                </label>

                <div class="upload-dropzone" id="dropzoneBukti" onclick="triggerFileInput('bukti_pembayaran')">
                    <div class="upload-icon-circle" id="iconWrapBukti">
                        <i class="bi bi-cloud-arrow-up-fill" id="iconFileBukti"></i>
                    </div>
                    <h6 class="fw-bold mb-1" id="textTitleBukti" style="color: var(--purple-title); font-size: 1rem;">
                        Pilih Berkas Bukti Pembayaran
                    </h6>
                    <p class="text-muted small mb-2" id="textDescBukti" style="font-size: 0.82rem;">
                        Klik area ini untuk memilih foto/struk transfer dari perangkat Anda (Maksimal 2 MB).
                    </p>
                    <div class="d-inline-flex align-items-center gap-1">
                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.74rem;">JPG</span>
                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.74rem;">PNG</span>
                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.74rem;">JPEG</span>
                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.74rem;">Maks. 2 MB</span>
                    </div>
                    <div id="previewBuktiWrap" class="mt-2 text-success fw-bold small" style="display: none;">
                        <i class="bi bi-check-circle-fill me-1"></i> File dipilih: <span id="namaFileBukti">-</span>
                    </div>
                    <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="d-none" accept="image/png, image/jpeg, image/jpg" onchange="handleFileChange(this, 'namaFileBukti', 'previewBuktiWrap')">
                </div>
            </div>

        </div>

        <!-- ============================================================== -->
        <!-- 4. TOMBOL SUBMIT PENDAFTARAN                                   -->
        <!-- ============================================================== -->
        <div class="form-section-card text-center p-4 mt-4">
            <button type="submit" class="btn btn-submit-main w-100 d-flex align-items-center justify-content-center" id="btnSubmitPendaftaran">
                <i class="fas fa-paper-plane me-2 fs-5"></i>
                <span>Kirim Formulir Pendaftaran Sekarang</span>
            </button>
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-3 mt-3 text-muted small" style="font-size: 0.8rem;">
                <span><i class="bi bi-shield-lock-fill text-success me-1"></i> Data Anda tersimpan aman dan terenkripsi</span>
                <span>•</span>
                <span><i class="bi bi-patch-check-fill text-primary me-1"></i> Pendaftaran Resmi Creativemu Academy</span>
            </div>
        </div>

    </form>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Logika Interaktif Form, COD/Transfer, dan Validasi -->
<script>
/**
 * Logika pemilihan kelas secara dinamis dari database
 */
function handleSelectKelas(selectEl) {
    if (!selectEl) return;
    const opt = selectEl.options[selectEl.selectedIndex];
    if (!opt) return;

    const idKelas = opt.value;
    const namaKelas = opt.dataset.nama || '';
    const kategori = opt.dataset.kategori || 'Pelatihan';
    const tipe = (opt.dataset.tipe || 'offline').toLowerCase();
    const reguler = parseFloat(opt.dataset.reguler || 0);
    const privat = parseFloat(opt.dataset.privat || 0);
    const mentor = opt.dataset.mentor || 'Mentor Creativemu';
    const tanggal = opt.dataset.tanggal || '-';
    const pertemuan = opt.dataset.pertemuan || '6';
    const thumbnail = opt.dataset.thumbnail || '';

    // 1. Update hidden inputs
    const hiddenId = document.getElementById('hidden_id_kelas');
    if (hiddenId) hiddenId.value = idKelas;
    const hiddenPelatihan = document.getElementById('hidden_pilihan_pelatihan');
    if (hiddenPelatihan) hiddenPelatihan.value = namaKelas;
    const hiddenPilihanKelas = document.getElementById('hidden_pilihan_kelas');
    if (hiddenPilihanKelas) hiddenPilihanKelas.value = namaKelas;
    const hiddenTanggal = document.getElementById('hidden_tanggal_mulai_kelas');
    if (hiddenTanggal) hiddenTanggal.value = tanggal;

    // 2. Update Class Summary Card
    const titleEl = document.getElementById('titleSummaryNamaKelas');
    if (titleEl) titleEl.textContent = namaKelas;
    const mentorEl = document.getElementById('textSummaryMentor');
    if (mentorEl) mentorEl.textContent = mentor;
    const tanggalEl = document.getElementById('textSummaryTanggal');
    if (tanggalEl) tanggalEl.textContent = tanggal;
    const durasiEl = document.getElementById('textSummaryDurasi');
    if (durasiEl) durasiEl.textContent = pertemuan + ' Sesi';
    const badgeKategori = document.getElementById('badgeSummaryKategori');
    if (badgeKategori) badgeKategori.innerHTML = '<i class="bi bi-mortarboard-fill me-1"></i> ' + kategori;
    const badgeTipe = document.getElementById('badgeSummaryTipe');
    if (badgeTipe) {
        badgeTipe.innerHTML = '<i class="bi bi-broadcast me-1"></i> ' + (tipe.charAt(0).toUpperCase() + tipe.slice(1));
    }
    const hargaRegulerEl = document.getElementById('textSummaryHargaReguler');
    if (hargaRegulerEl) hargaRegulerEl.textContent = 'Reguler: Rp ' + reguler.toLocaleString('id-ID');
    const hargaPrivatEl = document.getElementById('textSummaryHargaPrivat');
    if (hargaPrivatEl) hargaPrivatEl.textContent = 'Privat: Rp ' + privat.toLocaleString('id-ID');

    // Thumbnail img
    const thumbImg = document.getElementById('imgSummaryFoto');
    if (thumbImg) {
        if (thumbnail) {
            thumbImg.src = '<?= base_url("uploads/kelas/") ?>/' + thumbnail;
        } else {
            thumbImg.src = '<?= base_url("assets/img/default-class.jpg") ?>';
        }
    }

    // 3. Update Radio Harga
    const labelReg = document.getElementById('labelHargaReguler');
    if (labelReg) labelReg.textContent = 'Rp ' + reguler.toLocaleString('id-ID');
    const labelPriv = document.getElementById('labelHargaPrivat');
    if (labelPriv) labelPriv.textContent = 'Rp ' + privat.toLocaleString('id-ID');

    // 4. Update Metode Pembelajaran
    const metodeSelect = document.getElementById('metode_pembelajaran');
    if (metodeSelect) {
        metodeSelect.value = tipe;
        toggleLokasiPelatihan();
    }
}

function updateBiayaTampil() {
    // Reaktif saat radio reguler vs privat berganti
}

/**
 * Logika Pilihan Metode Pembelajaran (Online vs Offline)
 */
function toggleLokasiPelatihan() {
    const metodeSelect = document.getElementById('metode_pembelajaran');
    const container = document.getElementById('lokasiPelatihanContainer');
    const selectLokasi = document.getElementById('pilihan_lokasi');

    if (!metodeSelect || !container || !selectLokasi) return;

    if (metodeSelect.value === 'offline') {
        container.style.display = 'block';
        selectLokasi.setAttribute('required', 'required');
    } else {
        container.style.display = 'none';
        selectLokasi.removeAttribute('required');
        selectLokasi.value = ''; 
    }
}

/**
 * Logika Metode Pembayaran (COD vs Transfer)
 */
function togglePembayaran() {
    const selected = document.querySelector('input[name="metode_pembayaran"]:checked');
    const rekeningBox = document.getElementById('rekening');
    const infoCodBox = document.getElementById('infoCod');
    
    // Elemen Upload Bukti
    const inputBukti = document.getElementById('bukti_pembayaran');
    const dropzoneBukti = document.getElementById('dropzoneBukti');
    const bintangBukti = document.getElementById('bintangBukti');
    const badgeStatusBukti = document.getElementById('badgeStatusBukti');
    const textTitleBukti = document.getElementById('textTitleBukti');
    const textDescBukti = document.getElementById('textDescBukti');
    const previewBuktiWrap = document.getElementById('previewBuktiWrap');
    const namaFileBukti = document.getElementById('namaFileBukti');

    if (!selected) {
        if (rekeningBox) rekeningBox.style.display = 'none';
        if (infoCodBox) infoCodBox.style.display = 'none';
        return;
    }

    if (selected.value === "COD") {
        // JIKA COD:
        // 1. Tampilkan info COD & sembunyikan rekening transfer
        if (rekeningBox) rekeningBox.style.display = 'none';
        if (infoCodBox) infoCodBox.style.display = 'block';

        // 2. Nonaktifkan input file bukti pembayaran & reset valuenya
        if (inputBukti) {
            inputBukti.disabled = true;
            inputBukti.removeAttribute('required');
            inputBukti.value = ''; // Reset berkas jika sebelumnya dipilih
        }

        // 3. Reset teks preview
        if (previewBuktiWrap) previewBuktiWrap.style.display = 'none';
        if (namaFileBukti) namaFileBukti.textContent = '-';

        // 4. Ubah styling dropzone menjadi terlihat nonaktif (disabled state)
        if (dropzoneBukti) {
            dropzoneBukti.classList.add('disabled-state');
        }
        if (bintangBukti) {
            bintangBukti.style.display = 'none';
        }
        if (badgeStatusBukti) {
            badgeStatusBukti.className = 'badge bg-secondary bg-opacity-25 text-secondary';
            badgeStatusBukti.textContent = 'Tidak Diperlukan untuk COD';
        }
        if (textTitleBukti) {
            textTitleBukti.textContent = 'Upload Bukti Pembayaran Dinonaktifkan';
        }
        if (textDescBukti) {
            textDescBukti.textContent = 'Metode COD tidak memerlukan bukti transfer. Pembayaran dilakukan langsung di tempat saat sesi pertama.';
        }

    } else if (selected.value === "Transfer") {
        // JIKA TRANSFER:
        // 1. Tampilkan rekening & sembunyikan info COD
        if (rekeningBox) rekeningBox.style.display = 'block';
        if (infoCodBox) infoCodBox.style.display = 'none';

        // 2. Aktifkan input file bukti pembayaran & jadikan wajib (required)
        if (inputBukti) {
            inputBukti.disabled = false;
            inputBukti.setAttribute('required', 'required');
        }

        // 3. Kembalikan styling dropzone menjadi aktif
        if (dropzoneBukti) {
            dropzoneBukti.classList.remove('disabled-state');
        }
        if (bintangBukti) {
            bintangBukti.style.display = 'inline';
        }
        if (badgeStatusBukti) {
            badgeStatusBukti.className = 'badge';
            badgeStatusBukti.style.background = 'var(--purple-subtle)';
            badgeStatusBukti.style.color = 'var(--purple-primary)';
            badgeStatusBukti.textContent = 'Wajib Diunggah (Transfer)';
        }
        if (textTitleBukti) {
            textTitleBukti.textContent = 'Pilih Berkas Bukti Transfer Pembayaran';
        }
        if (textDescBukti) {
            textDescBukti.textContent = 'Klik area ini untuk mencari foto/struk transfer dari perangkat Anda.';
        }
    }
}

/**
 * Trigger klik pada input file tersembunyi
 */
function triggerFileInput(inputId) {
    const input = document.getElementById(inputId);
    if (input && !input.disabled) {
        input.click();
    }
}

/**
 * Update nama file yang dipilih pada preview container
 */
function handleFileChange(input, targetNameId, targetWrapId) {
    const nameSpan = document.getElementById(targetNameId);
    const wrap = document.getElementById(targetWrapId);
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        // Cek ukuran file (maks 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert("Peringatan: Ukuran file melebihi batas maksimal 2 MB (" + (file.size / (1024*1024)).toFixed(2) + " MB). Silakan pilih file yang lebih kecil.");
            input.value = '';
            if (nameSpan) nameSpan.textContent = '-';
            if (wrap) wrap.style.display = 'none';
            return;
        }

        if (nameSpan) nameSpan.textContent = file.name + " (" + (file.size / 1024).toFixed(1) + " KB)";
        if (wrap) wrap.style.display = 'block';
    } else {
        if (nameSpan) nameSpan.textContent = '-';
        if (wrap) wrap.style.display = 'none';
    }
}

/**
 * Salin nomor rekening ke clipboard
 */
function salinRekening() {
    const rekeningText = document.getElementById('nomorRekeningText');
    const labelSalin = document.getElementById('labelSalin');
    const iconSalin = document.getElementById('iconSalin');
    
    if (!rekeningText) return;

    const noRek = rekeningText.innerText.trim();
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(noRek).then(function() {
            tampilkanFeedbackSalin();
        }).catch(function() {
            fallbackCopy(noRek);
        });
    } else {
        fallbackCopy(noRek);
    }

    function fallbackCopy(text) {
        const tempInput = document.createElement('input');
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        tampilkanFeedbackSalin();
    }

    function tampilkanFeedbackSalin() {
        if (labelSalin) labelSalin.textContent = 'Tersalin!';
        if (iconSalin) iconSalin.className = 'bi bi-check2-all text-success me-1';
        setTimeout(function() {
            if (labelSalin) labelSalin.textContent = 'Salin No. Rekening';
            if (iconSalin) iconSalin.className = 'bi bi-clipboard me-1';
        }, 2500);
    }
}

// Inisialisasi event listeners saat DOM selesai dimuat
document.addEventListener("DOMContentLoaded", function() {
    // Validasi submit: persetujuan wajib dicentang
    const form = document.getElementById('formPendaftaran');
    if (form) {
        form.addEventListener('submit', function(e) {
            const persetujuan = document.getElementById('persetujuan_syarat');
            if (persetujuan && !persetujuan.checked) {
                e.preventDefault();
                alert("Peringatan: Anda wajib membaca dan menyetujui Syarat & Ketentuan serta Kebijakan Privasi terlebih dahulu untuk melanjutkan pendaftaran.");
                persetujuan.scrollIntoView({ behavior: 'smooth', block: 'center' });
                persetujuan.focus();
                return false;
            }
        });
    }

    const metodePembelajaranEl = document.getElementById('metode_pembelajaran');
    if (metodePembelajaranEl) {
        metodePembelajaranEl.addEventListener('change', toggleLokasiPelatihan);
    }

    const radioPembayaran = document.querySelectorAll('input[name="metode_pembayaran"]');
    radioPembayaran.forEach(function(radio) {
        radio.addEventListener('change', togglePembayaran);
    });

    // Jalankan inisialisasi awal saat load
    togglePembayaran();
    toggleLokasiPelatihan();
});
</script>

</body>
</html>
