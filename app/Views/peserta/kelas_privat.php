<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas Privat - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --purple-primary: #7a3ff2;
            --purple-strong: #4d1d95;
            --purple-mid: #9565ff;
            --purple-soft: #eee7ff;
            --purple-tint: #faf7ff;
            --ink: #20143f;
            --muted: #6f6581;
            --line: rgba(122, 63, 242, 0.16);
            --surface: rgba(255, 255, 255, 0.86);
            --shadow: 0 22px 60px rgba(54, 24, 108, 0.14);
            --ease: cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            letter-spacing: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            background:
                linear-gradient(135deg, rgba(248, 245, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 42%, rgba(235, 229, 255, 0.88) 100%),
                repeating-linear-gradient(115deg, rgba(122, 63, 242, 0.045) 0 1px, transparent 1px 28px);
            overflow-x: hidden;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes softSweep {
            from { transform: translateX(-34%) skewX(-10deg); }
            to { transform: translateX(34%) skewX(-10deg); }
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeInUp 0.72s var(--ease) forwards;
        }

        .navbar-custom {
            padding: 12px 0;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(122, 63, 242, 0.12);
            box-shadow: 0 12px 34px rgba(54, 24, 108, 0.08);
        }

        .navbar-logo-wrapper {
            background-color: #ffffff;
            border-radius: 14px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 24px rgba(122, 63, 242, 0.18);
        }

        .navbar-logo {
            width: auto;
            max-width: 140px;
            height: auto;
            max-height: 40px;
            object-fit: contain;
            display: block;
        }

        .brand-text {
            max-width: 260px;
            line-height: 1.15;
            font-weight: 800;
            color: var(--purple-strong);
            font-size: 1.5rem;
        }
        .btn-auth-mobile {
            padding: 8px 18px;
            font-size: 0.95rem;
        }

        .nav-link {
            position: relative;
            width: max-content;
            font-weight: 700;
            color: var(--muted) !important;
            transition: color 0.25s ease;
            cursor: pointer;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 2px;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--purple-primary), var(--purple-mid));
            border-radius: 99px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.28s var(--ease);
        }

        .nav-link:hover,
        .nav-link.active { color: var(--purple-primary) !important; }
        .nav-link:hover::after,
        .nav-link.active::after { transform: scaleX(1); }

        .btn-outline-custom-auth,
        .btn-custom-auth,
        .btn-custom-daftar {
            min-height: 42px;
            border-radius: 14px;
            font-weight: 800;
            transition: transform 0.28s var(--ease), box-shadow 0.28s var(--ease), background 0.28s ease, color 0.28s ease, border-color 0.28s ease;
        }

        .btn-outline-custom-auth {
            border: 1.5px solid rgba(122, 63, 242, 0.45);
            color: var(--purple-primary);
            background: rgba(255, 255, 255, 0.7);
            font-size: 0.86rem;
            padding: 8px 16px;
        }

        .btn-outline-custom-auth:hover {
            color: var(--purple-strong);
            border-color: var(--purple-primary);
            background: var(--purple-tint);
            transform: translateY(-2px);
        }

        .btn-custom-auth,
        .btn-custom-daftar {
            color: #ffffff;
            border: none;
            background: linear-gradient(135deg, var(--purple-mid) 0%, var(--purple-primary) 48%, var(--purple-strong) 100%);
            box-shadow: 0 14px 28px rgba(122, 63, 242, 0.28);
        }

        .btn-custom-auth {
            font-size: 0.86rem;
            padding: 8px 18px;
        }

        .btn-custom-auth:hover,
        .btn-custom-daftar:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 18px 38px rgba(77, 29, 149, 0.32);
        }

        .hero-section {
            position: relative;
            overflow: hidden;
            margin-bottom: -42px;
            padding: 58px 0 92px;
            color: #ffffff;
            background:
                linear-gradient(135deg, rgba(77, 29, 149, 0.98) 0%, rgba(122, 63, 242, 0.94) 54%, rgba(149, 101, 255, 0.9) 100%),
                repeating-linear-gradient(125deg, rgba(255, 255, 255, 0.08) 0 1px, transparent 1px 34px);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(100deg, transparent 0%, rgba(255, 255, 255, 0.18) 46%, transparent 74%);
            opacity: 0.65;
            animation: softSweep 7s ease-in-out infinite alternate;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            inset: auto 0 0;
            height: 80px;
            background: linear-gradient(180deg, transparent, rgba(250, 247, 255, 0.9));
        }

        .hero-content { position: relative; z-index: 1; }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            margin-bottom: 12px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            color: rgba(255, 255, 255, 0.92);
            font-size: 0.75rem;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .page-title {
            max-width: 780px;
            margin: 0;
            color: #ffffff;
            font-size: clamp(1.8rem, 4vw, 3.2rem);
            line-height: 1.15;
            font-weight: 800;
        }

        .hero-section .lead {
            max-width: 650px;
            color: rgba(255, 255, 255, 0.82);
            font-weight: 500;
            font-size: clamp(0.95rem, 1.5vw, 1.1rem);
            line-height: 1.6;
            margin-top: 10px;
        }

        .main-content {
            position: relative;
            z-index: 2;
        }

        .filter-container {
            margin-bottom: 34px;
            padding: 18px 22px;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
            animation: fadeInUp 0.8s var(--ease) forwards;
        }

        .filter-form {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) minmax(180px, 0.4fr) minmax(240px, auto);
            gap: 14px;
            align-items: center;
        }

        .search-input,
        .filter-select {
            width: 100%;
            min-height: 52px;
            border: 1.5px solid rgba(122, 63, 242, 0.14);
            border-radius: 16px;
            padding: 13px 16px;
            font-size: 0.93rem;
            font-weight: 600;
            color: var(--ink);
            background-color: rgba(255, 255, 255, 0.92);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);
            transition: border-color 0.24s ease, box-shadow 0.24s ease, background 0.24s ease;
        }

        .search-input:focus,
        .filter-select:focus {
            border-color: rgba(122, 63, 242, 0.78);
            box-shadow: 0 0 0 5px rgba(122, 63, 242, 0.12);
            background-color: #ffffff;
            outline: none;
        }

        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 28px;
            align-items: stretch;
            padding-bottom: 56px;
        }

        .kelas-grid-item { min-width: 0; }

        .kelas-card {
            position: relative;
            isolation: isolate;
            height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(122, 63, 242, 0.14);
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 18px 44px rgba(54, 24, 108, 0.11);
            transition: transform 0.42s var(--ease), box-shadow 0.42s var(--ease), border-color 0.3s ease;
            will-change: transform;
        }

        .kelas-card:hover {
            transform: translateY(-8px);
            border-color: rgba(122, 63, 242, 0.38);
            box-shadow: 0 26px 56px rgba(54, 24, 108, 0.18);
        }

        .btn-daftar-privat {
            background: linear-gradient(135deg, #7a3ff2 0%, #682bd8 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            padding: 10px 14px;
            box-shadow: 0 8px 20px -4px rgba(122, 63, 242, 0.35);
            transition: all 0.25s var(--ease);
        }

        .btn-daftar-privat:hover {
            background: linear-gradient(135deg, #6d2fd9 0%, #581ebf 100%);
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -4px rgba(122, 63, 242, 0.45);
        }

        @media (max-width: 991.98px) {
            .navbar-custom { padding: 10px 0; }
            .hero-section { margin-bottom: -34px; padding: 42px 0 76px; }
            .filter-form { grid-template-columns: 1fr; }
        }

        @media (max-width: 575.98px) {
            .container { padding-left: 10px; padding-right: 10px; }
            .filter-container { padding: 14px; border-radius: 20px; }
            .kelas-grid { grid-template-columns: minmax(0, 1fr); gap: 22px; }
            .card-body { padding: 18px !important; }
        }
    </style>
</head>
<body>

<!-- Navbar Atas -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container px-2 px-lg-4 flex-wrap flex-lg-nowrap">
        <a class="navbar-brand d-flex align-items-center gap-1 gap-lg-2 text-decoration-none me-0 me-lg-3" href="<?= base_url('/') ?>">
            <div class="navbar-logo-wrapper">
                <img src="<?= base_url('assets/img/logo_creativemu_admin.png') ?>" alt="Logo Creativemu" class="navbar-logo" onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/3413/3413535.png';">
            </div>
            <span class="brand-text">Creativemu Academy</span>
        </a>

        <div class="d-flex align-items-center ms-auto gap-1 gap-lg-2 order-lg-last">
            <a href="<?= base_url('auth/login') ?>" class="btn btn-outline-custom-auth btn-auth-mobile">Login</a>
            <a href="<?= base_url('auth/register') ?>" class="btn btn-custom-auth btn-auth-mobile">Sign Up</a>
            
            <button class="navbar-toggler border-0 shadow-none p-1 ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="width: 1.3em; height: 1.3em;"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse justify-content-center order-last order-lg-0" id="navbarNav">
            <ul class="navbar-nav gap-2 gap-lg-4 mt-3 mt-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('pelatihan/daftar-kelas') ?>">Program Reguler</a></li>
                <li class="nav-item"><a class="nav-link active" href="<?= base_url('pelatihan/kelas-privat') ?>">Kelas Privat</a></li>
                <li class="nav-item">
                    <a class="nav-link fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCekStatus" style="cursor: pointer;">
                        <i class="bi bi-search me-1"></i> Cek Status Pendaftaran
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section Kelas Privat -->
<div class="hero-section">
    <div class="container px-lg-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 hero-content animate-fade-in">
                <div class="hero-eyebrow"><i class="bi bi-award-fill"></i> Program Eksklusif 1-on-1 Intensif</div>
                <h1 class="page-title mb-3">Daftar Kelas Privat</h1>
                <p class="lead mb-0">Belajar intensif satu lawan satu dengan instruktur profesional. Materi dapat disesuaikan dengan kebutuhan Anda, jadwal fleksibel, dan konsultasi privat mendalam.</p>
            </div>
            <div class="col-lg-4">
                <div class="hero-highlight border-0 bg-transparent shadow-none p-0 text-center text-lg-end">
                    <img src="<?= base_url('assets/img/foto_utama.png') ?>" alt="Ilustrasi Kelas Privat" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content / Daftar Kelas Privat -->
<div class="container px-lg-4 py-3 main-content">

    <!-- Kotak Toolbar Pencarian & Filter -->
    <div class="filter-container">
        <form action="" method="get" class="filter-form">
            <div>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #5b21b6;"></i>
                    <input type="text" name="keyword" class="search-input ps-5" placeholder="Cari nama kelas privat..." value="<?= esc($_GET['keyword'] ?? '') ?>">
                </div>
            </div>
            <div>
                <select name="kategori" class="filter-select">
                    <option value="">Semua Kategori</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-custom-daftar flex-grow-1 py-2.5">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-outline-custom-auth py-2.5 d-inline-flex align-items-center justify-content-center text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Kelas Reguler
                </a>
            </div>
        </form>
    </div>

    <!-- Grid Kartu Kelas Privat -->
    <div class="kelas-grid mt-2">
        <?php if (empty($kelas)) : ?>
            <div class="kelas-grid-item text-center py-5" style="grid-column: 1 / -1;">
                <div class="card border-0 shadow-sm p-5 rounded-4 bg-white mx-auto animate-fade-in" style="max-width: 500px;">
                    <div class="p-4 rounded-circle d-inline-flex mx-auto mb-3" style="background-color: rgba(91,33,182,0.1); color: #5b21b6;">
                        <i class="bi bi-person-workspace fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Belum Ada Kelas Privat</h4>
                    <p class="text-muted mb-0 small">Saat ini belum ada data kelas privat aktif yang tersedia dari server pusat.</p>
                </div>
            </div>
        <?php else : ?>
            <?php $delay = 0.1; foreach ($kelas as $k) : ?>
                <div class="kelas-grid-item d-flex animate-fade-in" style="animation-delay: <?= $delay ?>s;">
                    <div class="card kelas-card w-100 h-100 border-0 shadow-sm" style="border-radius: 16px; transition: all 0.3s ease;">
                        <div class="card-body p-4 d-flex flex-column h-100">
                            
                            <!-- Top Badges -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="background-color: #f4ebff; color: #6b21a8; padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <?= esc($k['kategori'] ?? 'Privat') ?>
                                </span>
                                <div>
                                    <span style="border-radius: 50px; padding: 6px 14px; font-size: 0.75rem; background-color: #ede9fe; color: #6d28d9; font-weight: 700;">
                                        <i class="bi bi-person-fill-lock me-1"></i> Privat 1-on-1
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="judul-kelas mb-4" style="font-size: 1.25rem; font-weight: 700; color: #1e293b; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 3.5rem;">
                                <?= esc($k['nama_kelas']) ?>
                            </h3>
                            
                            <!-- Info Box -->
                            <div class="info-list mt-auto mb-4" style="background-color: #fafbfc; padding: 1.25rem; border-radius: 16px; border: 1px solid #e2e8f0;">
                                <?php if (!empty($k['nama_mentor'])): ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px; background-color: #e2e8f0; color: #475569; margin-right: 14px;">
                                            <i class="bi bi-person-video"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span style="font-size: 0.7rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; margin-bottom: 2px;">Instruktur</span>
                                            <strong style="font-size: 0.95rem; color: #334155; font-weight: 700;">
                                                <?= esc($k['nama_mentor']) ?>
                                            </strong>
                                        </div>
                                    </div>
                                    <div style="height: 1px; background-color: #e2e8f0; margin: 0 4px 1rem 4px;"></div>
                                <?php endif; ?>

                                <?php if (!empty($k['lokasi_media'])): ?>
                                    <div class="d-flex align-items-center mb-2 px-1">
                                        <i class="bi bi-geo-alt me-2" style="color: #64748b; font-size: 0.95rem;"></i>
                                        <span style="font-size: 0.85rem; color: #475569; font-weight: 600;"><?= esc($k['lokasi_media']) ?></span>
                                    </div>
                                    <div style="height: 1px; background-color: #e2e8f0; margin: 0 4px 0.75rem 4px;"></div>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-between align-items-center px-1">
                                    <?php if (!empty($k['jumlah_pertemuan'])): ?>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock me-2" style="color: #64748b; font-size: 0.95rem;"></i>
                                            <span style="font-size: 0.85rem; color: #475569; font-weight: 500;"><?= esc($k['jumlah_pertemuan']) ?> Pertemuan</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex align-items-center ms-auto">
                                        <i class="bi bi-calendar2-check me-2" style="color: #64748b; font-size: 0.95rem;"></i>
                                        <span style="font-size: 0.85rem; color: #475569; font-weight: 500;">Jadwal Fleksibel</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Price: Biaya Investasi Kelas Privat -->
                            <?php if (isset($k['harga_privat']) && $k['harga_privat'] !== null && $k['harga_privat'] !== ''): ?>
                                <div class="d-flex align-items-center mb-4 gap-2">
                                    <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Biaya Privat</span>
                                    <strong style="color: #5b21b6; font-size: 1.4rem; font-weight: 800; line-height: 1; margin-left: auto;">
                                        Rp <?= number_format((float)$k['harga_privat'], 0, ',', '.') ?>
                                    </strong>
                                </div>
                            <?php endif; ?>

                            <!-- Tombol Aksi: Langsung mengarah ke formulir pendaftaran kelas privat -->
                            <div class="mt-auto d-flex flex-column gap-2">
                                <a href="<?= base_url('pelatihan/pendaftaran/' . $k['id_kelas'] . '?jenis=Privat') ?>" class="btn btn-daftar-privat w-100 py-2.5 text-center d-flex align-items-center justify-content-center">
                                    <i class="bi bi-pencil-square me-2"></i> Daftar Kelas Privat
                                </a>
                                <a href="<?= base_url('pelatihan/detail-kelas/' . $k['id_kelas']) ?>" class="btn btn-link text-decoration-none text-muted small py-1 text-center" style="font-size: 0.82rem;">
                                    Lihat Detail Silabus
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

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

