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

        @keyframes floatPanel {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
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

        .navbar-logo {
            width: 46px;
            height: 46px;
            object-fit: contain;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(122, 63, 242, 0.18);
        }

        .navbar-brand span {
            max-width: 260px;
            line-height: 1.15;
            font-weight: 800;
            color: var(--purple-strong);
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
        .btn-outline-detail,
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

        .hero-content,
        .hero-highlight { position: relative; z-index: 1; }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
            color: rgba(255, 255, 255, 0.92);
            font-size: 0.82rem;
            font-weight: 800;
            backdrop-filter: blur(10px);
        }

        .page-title {
            max-width: 780px;
            margin: 0;
            color: #ffffff;
            font-size: clamp(2.05rem, 5vw, 4.25rem);
            line-height: 1.02;
            font-weight: 800;
        }

        .hero-section .lead {
            max-width: 650px;
            color: rgba(255, 255, 255, 0.82);
            font-weight: 500;
        }

        .hero-highlight {
            width: min(100%, 350px);
            margin-left: auto;
            padding: 22px;
            border: 1px solid rgba(255, 255, 255, 0.26);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.14);
            box-shadow: 0 24px 60px rgba(32, 20, 63, 0.22);
            backdrop-filter: blur(16px);
            animation: floatPanel 5.5s ease-in-out infinite;
        }

        .hero-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .hero-stat {
            min-height: 92px;
            padding: 16px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .hero-stat strong {
            display: block;
            font-size: 1.45rem;
            line-height: 1;
        }

        .hero-stat span {
            display: block;
            margin-top: 8px;
            color: rgba(255, 255, 255, 0.78);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .main-content { position: relative; z-index: 3; }

        .filter-container {
            position: relative;
            z-index: 4;
            padding: 18px;
            margin-bottom: 28px;
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 24px;
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(18px);
            animation: fadeInUp 0.8s var(--ease) forwards;
        }

        .filter-form {
            display: grid;
            grid-template-columns: minmax(220px, 1fr) minmax(180px, 0.45fr) minmax(130px, 0.24fr);
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

        .btn-custom-daftar {
            font-size: 0.86rem;
            padding: 10px 14px;
        }

        .alert-modern {
            border-radius: 18px;
            padding: 1rem 1.15rem;
            margin-bottom: 1.4rem;
            font-weight: 700;
            border: 1px solid transparent;
            box-shadow: 0 14px 34px rgba(32, 20, 63, 0.08);
        }

        .alert-modern.success { color: #166534; background: #f0fdf4; border-color: #bbf7d0; }
        .alert-modern.error { color: #991b1b; background: #fef2f2; border-color: #fecaca; }

        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

        .kelas-card::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(145deg, rgba(149, 101, 255, 0.16), transparent 48%);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .kelas-card::after {
            content: '';
            position: absolute;
            top: -45%;
            left: -70%;
            z-index: 3;
            width: 48%;
            height: 190%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.46), transparent);
            transform: rotate(22deg) translateX(0);
            transition: transform 0.82s ease;
            pointer-events: none;
        }

        .kelas-card:hover {
            transform: translateY(-12px);
            border-color: rgba(122, 63, 242, 0.42);
            box-shadow: 0 30px 70px rgba(54, 24, 108, 0.2);
        }

        .kelas-card:hover::before { opacity: 1; }
        .kelas-card:hover::after { transform: rotate(22deg) translateX(390%); }

        .img-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            min-height: 190px;
            overflow: hidden;
            background: linear-gradient(135deg, #f1eaff, #ffffff);
        }

        .img-wrapper::after {
            content: '';
            position: absolute;
            inset: auto 0 0;
            height: 54%;
            background: linear-gradient(180deg, transparent, rgba(32, 20, 63, 0.54));
            transition: opacity 0.3s ease;
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.01);
            transition: transform 0.72s var(--ease), filter 0.35s ease;
        }

        .kelas-card:hover .img-wrapper img {
            transform: scale(1.1);
            filter: saturate(1.08);
        }

        .badge-kategori,
        .badge-metode,
        .badge-status-aktif,
        .badge-status-nonaktif {
            display: inline-flex;
            align-items: center;
            width: max-content;
            max-width: 100%;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
        }

        .badge-kategori {
            max-width: min(210px, 58vw);
            padding: 8px 12px;
            color: #ffffff;
            background: rgba(77, 29, 149, 0.9);
            box-shadow: 0 12px 28px rgba(32, 20, 63, 0.22);
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .badge-metode {
            padding: 8px 11px;
            color: var(--ink);
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 12px 28px rgba(32, 20, 63, 0.16);
        }

        .badge-status-aktif { padding: 7px 10px; color: #047857; background: #d9fbe8; }
        .badge-status-nonaktif { padding: 7px 10px; color: #a21caf; background: #fae8ff; }

        .card-body { position: relative; z-index: 2; }

        .judul-kelas {
            min-width: 0;
            margin-bottom: 0;
            color: var(--ink);
            font-size: 1.12rem;
            font-weight: 800;
            line-height: 1.36;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.25s ease;
        }

        .kelas-card:hover .judul-kelas { color: var(--purple-primary); }

        .deskripsi-kelas {
            min-height: 3.1em;
            margin: 12px 0 16px;
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.58;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .info-list {
            display: grid;
            gap: 10px;
            margin-bottom: 16px;
            color: var(--muted);
            font-size: 0.84rem;
            font-weight: 650;
        }

        .info-row,
        .info-split {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-row strong {
            min-width: 0;
            color: var(--ink);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .info-icon {
            flex: 0 0 auto;
            display: inline-grid;
            place-items: center;
            width: 28px;
            height: 28px;
            border-radius: 10px;
            color: var(--purple-primary);
            background: var(--purple-soft);
        }

        .box-harga {
            margin-top: auto;
            margin-bottom: 16px;
            padding: 14px;
            border: 1px solid rgba(122, 63, 242, 0.14);
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(250, 247, 255, 0.96), rgba(255, 255, 255, 0.94));
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            font-size: 0.82rem;
        }

        .price-row + .price-row {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid rgba(122, 63, 242, 0.1);
        }

        .price-row span { color: var(--muted); font-weight: 700; }
        .price-row strong { color: var(--purple-strong); font-size: 0.9rem; white-space: nowrap; }

        .btn-outline-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1.5px solid rgba(122, 63, 242, 0.28);
            color: var(--purple-primary);
            background: #ffffff;
            font-size: 0.86rem;
            padding: 10px 12px;
        }

        .btn-outline-detail:hover {
            color: #ffffff;
            border-color: var(--purple-primary);
            background: var(--purple-primary);
            box-shadow: 0 16px 30px rgba(122, 63, 242, 0.24);
            transform: translateY(-2px);
        }

        .empty-state {
            max-width: 560px;
            margin-inline: auto;
            padding: 44px 28px;
            border: 1px solid rgba(122, 63, 242, 0.14);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
        }

        .empty-icon {
            display: inline-grid;
            place-items: center;
            width: 76px;
            height: 76px;
            margin-bottom: 18px;
            border-radius: 24px;
            color: var(--purple-primary);
            background: linear-gradient(135deg, var(--purple-soft), #ffffff);
            box-shadow: 0 16px 34px rgba(122, 63, 242, 0.16);
        }

        @media (min-width: 1400px) {
            .kelas-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }

        @media (max-width: 991.98px) {
            .navbar-custom { padding: 10px 0; }
            .navbar-collapse { padding-top: 14px; }
            .navbar-nav { gap: 10px !important; }
            .hero-section { margin-bottom: -34px; padding: 42px 0 76px; }
            .hero-highlight { margin: 24px 0 0; }
            .filter-form { grid-template-columns: 1fr 210px; }
            .filter-action { grid-column: 1 / -1; }
        }

        @media (max-width: 575.98px) {
            .container { padding-left: 18px; padding-right: 18px; }
            .navbar-logo { width: 40px; height: 40px; border-radius: 12px; }
            .navbar-brand span { max-width: 185px; font-size: 1rem !important; }
            .hero-section { padding: 34px 0 68px; }
            .hero-eyebrow { font-size: 0.76rem; }
            .hero-highlight { width: 100%; padding: 16px; border-radius: 20px; }
            .hero-stat { min-height: 82px; padding: 13px; }
            .hero-stat strong { font-size: 1.18rem; }
            .filter-container { padding: 14px; border-radius: 20px; }
            .filter-form { grid-template-columns: 1fr; gap: 12px; }
            .kelas-grid { grid-template-columns: minmax(0, 1fr); gap: 22px; }
            .kelas-card { border-radius: 22px; }
            .kelas-card:hover { transform: translateY(-6px); }
            .img-wrapper { min-height: 184px; }
            .card-body { padding: 18px !important; }
            .judul-kelas { font-size: 1rem; }
            .info-split { flex-wrap: wrap; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }

            .kelas-card:hover,
            .kelas-card:hover .img-wrapper img,
            .btn-custom-daftar:hover,
            .btn-outline-detail:hover,
            .btn-custom-auth:hover,
            .btn-outline-custom-auth:hover { transform: none; }
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

<!-- Hero Section -->
<div class="hero-section">
    <div class="container px-lg-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 hero-content animate-fade-in">
                <div class="hero-eyebrow"><i class="bi bi-stars"></i> Kelas kreatif pilihan untuk naik level</div>
                <h1 class="page-title mb-3">Daftar Program Pelatihan</h1>
                <p class="lead mb-0">Temukan kelas pengembangan skill profesional dengan mentor berpengalaman, jadwal fleksibel, dan materi yang siap dipakai untuk karier maupun bisnis.</p>
            </div>
            <div class="col-lg-4">
                <div class="hero-highlight">
                    <div class="hero-stat-grid">
                        <div class="hero-stat">
                            <strong><?= count($kelas ?? []) ?></strong>
                            <span>Program tersedia</span>
                        </div>
                        <div class="hero-stat">
                            <strong>Online</strong>
                            <span>Belajar fleksibel</span>
                        </div>
                        <div class="hero-stat">
                            <strong>Privat</strong>
                            <span>Opsi intensif</span>
                        </div>
                        <div class="hero-stat">
                            <strong>Mentor</strong>
                            <span>Pendamping ahli</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content / Daftar Kelas -->
<div class="container px-lg-4 py-3 main-content">

    <!-- NOTIFIKASI FLASH MESSAGE (SUKSES / ERROR) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-modern success animate-fade-in">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-modern error animate-fade-in">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Kotak Filter Pencarian -->
    <div class="filter-container">
        <form action="" method="get" class="filter-form">
            <div>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3" style="color: #5b21b6;"></i>
                    <input type="text" name="keyword" class="search-input ps-5" placeholder="Cari nama kelas atau materi..." value="<?= esc($_GET['keyword'] ?? '') ?>">
                </div>
            </div>
            <div>
                <select name="kategori" class="filter-select">
                    <option value="">Semua Kategori</option>
                </select>
            </div>
            <div class="filter-action">
                <button type="submit" class="btn btn-custom-daftar w-100 py-2.5">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Daftar Kelas Grid -->
    <div class="kelas-grid mt-2">
        <?php if (empty($kelas)) : ?>
            <div class="kelas-grid-item text-center py-5" style="grid-column: 1 / -1;">
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
                <div class="kelas-grid-item d-flex animate-fade-in" style="animation-delay: <?= $delay ?>s;">
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

                        <div class="card-body p-4 d-flex flex-column">
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

                            <div class="info-list">
                                <div class="info-row">
                                    <span class="info-icon"><i class="bi bi-person-workspace"></i></span>
                                    <span>Mentor: <strong><?= esc($k['nama_mentor'] ?? '-') ?></strong></span>
                                </div>
                                <div class="info-split justify-content-between">
                                    <span class="info-row"><span class="info-icon"><i class="bi bi-clock-fill"></i></span><?= esc($k['jumlah_pertemuan']) ?> Pertemuan</span>
                                    <span class="info-row"><span class="info-icon"><i class="bi bi-people-fill"></i></span><?= esc($k['kapasitas_tersedia'] ?? $k['kapasitas'] ?? '-') ?> org</span>
                                </div>
                            </div>

                            <div class="box-harga mt-auto">
                                <div class="price-row">
                                    <span>Reguler</span>
                                    <strong>Rp <?= number_format($k['harga_reguler'] ?? 0, 0, ',', '.') ?></strong>
                                </div>
                                <div class="price-row">
                                    <span>Privat</span>
                                    <strong>Rp <?= number_format($k['harga_privat'] ?? 0, 0, ',', '.') ?></strong>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-1">
                                <a href="<?= base_url('pelatihan/detail/' . $k['id_kelas']) ?>" class="btn btn-outline-detail flex-fill text-center">
                                    <i class="bi bi-arrow-right-circle"></i> Lihat Detail
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