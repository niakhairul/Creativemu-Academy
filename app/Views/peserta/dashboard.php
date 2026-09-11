<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome Icons (untuk ikon tambahan) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --purple-deep: #5b3fd6;
            --purple-mid: #7c5cfa;
            --purple-light: #8b6cf0;
            --purple-soft: #efeaff;
            --purple-soft2: #dcd0ff;
            --purple-glow: rgba(124, 92, 250, 0.35);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(124, 92, 250, 0.4) transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Gradasi latar belakang cerah bernuansa ungu lembut yang selaras, bergerak halus */
            background: linear-gradient(120deg, #f8f6ff, #f1ecff, #faf5ff, #eee8ff);
            background-size: 300% 300%;
            animation: bgFlow 22s ease infinite;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        @keyframes bgFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Layout Utama dengan Sidebar */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar dengan Gradasi Ungu yang Lebih Kaya & Hidup */
        .sidebar {
            width: 260px;
            background:
                radial-gradient(circle at 15% 12%, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0) 45%),
                linear-gradient(165deg, #4a2fc9 0%, #7440e6 32%, #9257f2 60%, #b678f5 100%);
            background-size: 200% 200%, 220% 220%;
            animation: sidebarGlow 14s ease infinite;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 24px 20px;
            box-shadow: 6px 0 34px rgba(116, 64, 230, 0.35);
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Tekstur garis tipis diagonal agar sidebar tidak terlihat polos/monoton */
        .sidebar::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.035) 0px,
                rgba(255, 255, 255, 0.035) 2px,
                transparent 2px,
                transparent 14px
            );
            pointer-events: none;
        }

        @keyframes sidebarGlow {
            0% { background-position: 0% 0%, 0% 0%; }
            50% { background-position: 100% 100%, 100% 100%; }
            100% { background-position: 0% 0%, 0% 0%; }
        }

        /* Aksen bintik cahaya lembut di sidebar agar terasa hidup, tanpa mengganggu isi */
        .sidebar::before {
            content: "";
            position: absolute;
            top: -60px;
            right: -60px;
            width: 180px;
            height: 180px;
            background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
            animation: floatBlob 8s ease-in-out infinite;
        }

        @keyframes floatBlob {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(20px) scale(1.08); }
        }

        .sidebar-brand {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .sidebar-brand i {
            animation: brandPulse 3s ease-in-out infinite;
        }

        @keyframes brandPulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.12) rotate(-4deg); }
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu li {
            margin-bottom: 8px;
            opacity: 0;
            transform: translateX(-12px);
            animation: menuSlideIn 0.5s ease forwards;
        }

        /* Menu muncul bertahap satu-persatu saat halaman dibuka */
        .sidebar-menu li:nth-child(1) { animation-delay: 0.05s; }
        .sidebar-menu li:nth-child(2) { animation-delay: 0.12s; }
        .sidebar-menu li:nth-child(3) { animation-delay: 0.19s; }
        .sidebar-menu li:nth-child(4) { animation-delay: 0.26s; }
        .sidebar-menu li:nth-child(5) { animation-delay: 0.33s; }
        .sidebar-menu li:nth-child(6) { animation-delay: 0.40s; }
        .sidebar-menu li:nth-child(7) { animation-delay: 0.47s; }

        @keyframes menuSlideIn {
            to { opacity: 1; transform: translateX(0); }
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* Efek kilau halus saat menu di-hover */
        .sidebar-menu a::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s ease;
        }

        .sidebar-menu a:hover::before {
            left: 100%;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            transform: translateX(6px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.28), rgba(255, 255, 255, 0.12));
            box-shadow: 0 6px 18px rgba(20, 5, 60, 0.28), inset 3px 0 0 #ffd166;
        }

        .sidebar-menu a i {
            font-size: 1.2rem;
            margin-right: 12px;
            transition: transform 0.3s ease;
        }

        .sidebar-menu a:hover i {
            transform: scale(1.15) rotate(-6deg);
        }

        /* Konten Utama */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
            animation: contentFadeIn 0.6s ease;
        }

        @keyframes contentFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Card Umum dengan Nuansa Semi-Transparan & Glassmorphism */
        .card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(124, 92, 250, 0.08);
            transition: all 0.35s ease;
            opacity: 0;
            animation: cardFadeUp 0.55s ease forwards;
        }

        @keyframes cardFadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Kartu muncul bertahap mengikuti urutan tampil di halaman */
        .row:nth-of-type(1) .card { animation-delay: 0.05s; }
        .row:nth-of-type(2) .card { animation-delay: 0.12s; }
        .row:nth-of-type(3) .card { animation-delay: 0.18s; }
        .row:nth-of-type(4) .card { animation-delay: 0.24s; }

        .hover-card {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }
        .hover-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 38px rgba(124, 92, 250, 0.18) !important;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(124, 92, 250, 0.15);
            transition: transform 0.3s ease;
        }

        .hover-card:hover .stat-icon {
            transform: scale(1.1) rotate(-4deg);
        }

        .bg-purple-soft {
            background: linear-gradient(135deg, var(--purple-soft) 0%, var(--purple-soft2) 100%);
        }
        
        .text-purple-custom {
            color: var(--purple-mid);
        }

        /* Lonceng notifikasi berdenyut lembut agar terasa hidup */
        .bi-bell-fill {
            display: inline-block;
            animation: bellRing 4s ease-in-out infinite;
            transform-origin: top center;
        }

        @keyframes bellRing {
            0%, 92%, 100% { transform: rotate(0deg); }
            94% { transform: rotate(12deg); }
            96% { transform: rotate(-10deg); }
            98% { transform: rotate(6deg); }
        }

        /* Tombol dengan sedikit gerak saat hover agar interaktif */
        .btn {
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }
        .btn:hover {
            transform: translateY(-2px) scale(1.02);
            filter: brightness(1.05);
        }

        /* Badge status dengan denyut lembut supaya menarik perhatian */
        .badge.bg-warning, .badge.bg-success, .badge.bg-danger {
            animation: badgePop 0.4s ease;
            transition: transform 0.25s ease;
        }
        .badge.bg-warning:hover, .badge.bg-success:hover, .badge.bg-danger:hover {
            transform: scale(1.06);
        }

        @keyframes badgePop {
            from { transform: scale(0.85); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* ===== Sentuhan Ekstra Agar Lebih Hidup & Menarik ===== */

        /* Bola cahaya dekoratif melayang di latar belakang untuk kedalaman visual */
        body::before, body::after {
            content: "";
            position: fixed;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(95px);
            z-index: 0;
            pointer-events: none;
            opacity: 0.32;
        }
        body::before {
            background: var(--purple-light);
            top: -120px;
            right: -100px;
            animation: blobMove1 15s ease-in-out infinite;
        }
        body::after {
            background: var(--purple-mid);
            bottom: -140px;
            left: -100px;
            animation: blobMove2 17s ease-in-out infinite;
        }
        @keyframes blobMove1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-50px, 40px) scale(1.12); }
        }
        @keyframes blobMove2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -30px) scale(1.08); }
        }
        .app-wrapper { position: relative; z-index: 1; }

        /* Scrollbar sidebar yang lebih halus (Webkit) */
        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.3); border-radius: 10px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }

        /* Sapaan "Halo, Nama" tampil dengan gradasi teks yang bergerak halus */
        .row:nth-of-type(1) h2 {
            background: linear-gradient(90deg, var(--purple-deep), var(--purple-mid), var(--purple-light), var(--purple-mid));
            background-size: 250% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent !important;
            animation: gradientTextMove 5s linear infinite;
            display: inline-block;
        }
        @keyframes gradientTextMove {
            0% { background-position: 0% center; }
            100% { background-position: 250% center; }
        }

        /* Ikon kecil judul kartu (mis. Profil Saya, Pengumuman) melayang pelan */
        .bg-purple-soft.rounded-3 {
            animation: iconFloat 3s ease-in-out infinite;
        }
        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        /* Efek kilau menyapu saat kartu di-hover, memberi kesan premium & hidup */
        .hover-card {
            position: relative;
            overflow: hidden;
        }
        .hover-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: -150%;
            width: 55%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: skewX(-20deg);
            transition: left 0.65s ease;
            pointer-events: none;
            z-index: 2;
        }
        .hover-card:hover::before {
            left: 150%;
        }

        /* Empat kartu statistik diberi aksen warna ungu yang bervariasi agar lebih hidup namun tetap satu keluarga warna */
        .row:nth-of-type(2) .col-md-3:nth-of-type(1) { --card-accent: #7c5cfa; }
        .row:nth-of-type(2) .col-md-3:nth-of-type(2) { --card-accent: #9b6bf5; }
        .row:nth-of-type(2) .col-md-3:nth-of-type(3) { --card-accent: #6a4ce0; }
        .row:nth-of-type(2) .col-md-3:nth-of-type(4) { --card-accent: #b18bf7; }

        .row:nth-of-type(2) .card {
            border-top: 4px solid var(--card-accent, var(--purple-mid));
        }

        .row:nth-of-type(2) .stat-icon {
            position: relative;
        }
        .row:nth-of-type(2) .stat-icon::after {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 18px;
            border: 2px solid var(--card-accent, var(--purple-mid));
            opacity: 0.7;
            animation: ringPulse 2.6s ease-out infinite;
        }
        @keyframes ringPulse {
            0% { transform: scale(0.9); opacity: 0.65; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        /* Tombol dengan efek kilau menyapu saat hover */
        .btn {
            position: relative;
            overflow: hidden;
        }
        .btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 45%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.55), transparent);
            transform: skewX(-20deg);
            transition: left 0.5s ease;
        }
        .btn:hover::after {
            left: 130%;
        }

        /* Baris tabel profil sedikit menyorot saat disentuh */
        .table-borderless tr {
            transition: background 0.25s ease;
        }
        .table-borderless tr:hover {
            background: var(--purple-soft);
        }
        .table-borderless td {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="app-wrapper">

    <!-- Sidebar Kustom dengan Menu Terstruktur Ringkas & Konsisten -->
    <nav class="sidebar">
        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i> Creativemu
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('peserta/dashboard') ?>" class="active"><i class="bi bi-grid-fill"></i> Dashboard</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>"><i class="bi bi-journals"></i> Daftar Kelas Saya</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/kelas') ?>"><i class="bi bi-mortarboard-fill"></i> KBM</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>"><i class="bi bi-gear-fill"></i> Pengaturan</a>
            </li>
            <li class="mt-5">
                <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </li>
        </ul>
    </nav>

    <!-- Bagian Konten Utama -->
    <div class="main-content">
        <div class="container-fluid py-2">

            <!-- Header Sambutan -->
            <div class="row mb-4 align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1" style="color: #5b3fd6;">
                        Halo, <?= esc($user['nama']) ?> 👋
                    </h2>
                    <p class="text-muted mb-0">
                        Selamat datang di dashboard peserta
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-flex align-items-center bg-white p-2 px-3 rounded-pill shadow-sm border border-purple border-opacity-10">
                        <div class="bg-purple-soft text-purple-custom p-2 rounded-circle me-2 position-relative">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"></span>
                        </div>
                        <div class="text-start me-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?= esc($user['nama']) ?></h6>
                            <span class="text-muted" style="font-size: 0.75rem;">Peserta</span>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Kotak Statistik Ringkas 4 Kolom -->
            <div class="row mb-4">
                <!-- 1. Kelas Aktif -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #5b3fd6;"><?= $pendaftaran ? 1 : 0 ?></h3>
                                <p class="text-dark mb-0 fw-bold small">Kelas Aktif</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Sedang berlangsung</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 2. Kehadiran -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-calendar-check-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #5b3fd6;"><?= esc($total_kehadiran ?? '0') ?>%</h3>
                                <p class="text-dark mb-0 fw-bold small">Kehadiran</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Total Kehadiran</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Tugas -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-clipboard-check-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #5b3fd6;"><?= esc($total_tugas ?? '0') ?></h3>
                                <p class="text-dark mb-0 fw-bold small">Tugas</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Belum Dikumpulkan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Sertifikat -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-award-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #5b3fd6;"><?= esc($total_sertifikat ?? '0') ?></h3>
                                <p class="text-dark mb-0 fw-bold small">Sertifikat</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Telah Diperoleh</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Utama: Profil & Status Pendaftaran -->
            <div class="row mb-4">

                <!-- Profil Saya -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                                    <i class="bi bi-person-circle fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #5b3fd6;">Profil Saya</h4>
                            </div>

                            <table class="table table-borderless align-middle mb-0 bg-transparent">
                                <tr>
                                    <td width="130" class="text-muted fw-semibold">NIS</td>
                                    <td class="fw-bold text-primary">: <span class="badge bg-purple-soft text-purple-custom px-2 py-1"><?= esc($user['nis'] ?? $pendaftaran['nis'] ?? '-') ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Nama</td>
                                    <td class="fw-bold text-dark">: <?= esc($user['nama']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Email</td>
                                    <td class="fw-bold text-dark">: <?= esc($user['email']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">No HP</td>
                                    <td class="fw-bold text-dark">: <?= esc($user['no_hp'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Pendaftaran -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                                    <i class="bi bi-clipboard-check fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #5b3fd6;">Status Pendaftaran</h4>
                            </div>

                            <?php if ($pendaftaran == null): ?>
                                <div class="text-center py-3">
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill mb-3 shadow-sm">
                                        Belum Mendaftar
                                    </span>
                                    <p class="text-muted small mb-3">
                                        Anda belum terdaftar di kelas pelatihan apapun. Mari mulai langkah belajarmu sekarang.
                                    </p>
                                    <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn px-4 rounded-pill shadow-sm text-white fw-bold" style="background: linear-gradient(135deg, #7c5cfa, #5b3fd6);">
                                        Pilih Kelas Sekarang
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <?php 
                                        $statusDaftar = $pendaftaran['status_pendaftaran'] ?? '';
                                        $statusBayar  = $pendaftaran['status_pembayaran'] ?? '';
                                    ?>

                                    <?php if ($statusBayar == 'terkonfirmasi' || strtolower($statusDaftar) == 'disetujui' || $statusBayar == 'valid'): ?>
                                        <span class="badge bg-success fs-6 px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-check-circle me-1"></i> Sudah Divalidasi / Disetujui
                                        </span>
                                    <?php elseif ($statusBayar == 'batal' || strtolower($statusDaftar) == 'ditolak' || $statusBayar == 'rejected'): ?>
                                        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-x-circle me-1"></i> Ditolak
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-clock-history me-1"></i> Menunggu Validasi Admin
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Kotak Informasi Kelas yang Dipilih -->
                                <div class="p-3 border border-purple border-opacity-25 rounded-4 bg-white shadow-sm">
                                    <h6 class="fw-bold mb-2" style="color: #5b3fd6;"><?= esc($pendaftaran['nama_kelas'] ?? 'Kelas Pelatihan') ?></h6>
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-person-badge me-1"></i> Mentor: <strong class="text-dark"><?= esc($pendaftaran['nama_mentor'] ?? '-') ?></strong>
                                    </p>
                                    <p class="text-muted small mb-1">
                                        <i class="bi bi-calendar-event me-1"></i> Jadwal: <strong class="text-dark"><?= esc($pendaftaran['jadwal'] ?? '-') ?></strong>
                                    </p>
                                    <hr class="my-2 border-purple border-opacity-10">
                                    <div class="row g-1 text-muted small">
                                        <div class="col-12">
                                            <span><i class="bi bi-laptop me-1"></i> Metode Pembelajaran:</span> 
                                            <strong class="text-dark text-capitalize"><?= esc($pendaftaran['metode_pembelajaran'] ?? '-') ?></strong>
                                        </div>
                                        <div class="col-12">
                                            <span><i class="bi bi-grid me-1"></i> Kategori Kelas:</span> 
                                            <strong class="text-dark"><?= esc($pendaftaran['kategori_kelas'] ?? '-') ?></strong>
                                        </div>
                                        <div class="col-12">
                                            <span><i class="bi bi-tag me-1"></i> Jenis Kelas:</span> 
                                            <strong class="text-dark text-capitalize"><?= esc($pendaftaran['jenis_kelas'] ?? '-') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Bagian Tambahan: Jadwal Pelatihan & Pengumuman -->
            <div class="row">
                
                <!-- Jadwal Pelatihan & Materi -->
                <!-- Jadwal Pelatihan & Materi -->
<div class="col-lg-7 mb-4">
    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                    <i class="bi bi-calendar-range fs-4"></i>
                </div>
                <h4 class="fw-bold mb-0" style="color: #5b3fd6;">Jadwal Pelatihan & Materi</h4>
            </div>

            <!-- Loop Data Jadwal dari Mentor -->
            <?php if (!empty($list_jadwal) && is_array($list_jadwal)): ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($list_jadwal as $jdl): ?>
                        <div class="p-3 mb-3 border border-purple border-opacity-25 rounded-4 bg-white shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0" style="color: #5b3fd6;"><?= esc($jdl['materi']); ?></h6>
                                <span class="badge bg-purple-soft text-purple-custom px-2 py-1"><?= esc($jdl['status'] ?? 'Terjadwal'); ?></span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-calendar-event text-purple-custom me-2"></i><strong>Tanggal:</strong> <?= esc($jdl['tanggal_kbm']); ?>
                            </p>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-clock text-purple-custom me-2"></i><strong>Jam mulai:</strong> <?= esc(date('H:i', strtotime($jdl['tanggal_kbm']))); ?>
                            </p>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-clock text-purple-custom me-2"></i><strong>Jam selesai:</strong> <?= esc($jdl['jam_selesai'] ?? '-'); ?>
                            </p>
                            <p class="text-muted small mb-2">
    <i class="bi bi-camera-video text-purple-custom me-2"></i>
    <strong>Tempat / Link Meet:</strong> -
</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <div class="text-muted mb-2"><i class="bi bi-calendar-x fs-1 opacity-50"></i></div>
                    <p class="text-muted small mb-0">Belum ada jadwal pelatihan atau materi yang dikirimkan oleh mentor.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

                <!-- Pengumuman -->
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                                    <i class="bi bi-megaphone-fill fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #5b3fd6;">Pengumuman Penting</h4>
                            </div>

                            <?php if (!empty($pengumuman_list) && is_array($pengumuman_list)): ?>
                                <?php foreach ($pengumuman_list as $pengumuman): ?>
                                    <div class="p-3 mb-3 border border-purple border-opacity-25 rounded-4 bg-white shadow-sm">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-info-circle fs-3 text-purple-custom me-3 mt-1"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1" style="color: #5b3fd6;"><?= esc($pengumuman['judul'] ?? 'Pengumuman') ?></h6>
                                                <p class="text-muted small mb-2">
                                                    <?= esc($pengumuman['isi'] ?? $pengumuman['konten'] ?? '') ?>
                                                </p>
                                                <?php if (!empty($pengumuman['link_url'])): ?>
                                                    <a href="<?= esc($pengumuman['link_url']) ?>" class="btn btn-sm rounded-pill px-3 fw-semibold text-white shadow-sm" style="background: linear-gradient(135deg, #7c5cfa, #5b3fd6);">
                                                        <?= esc($pengumuman['link_text'] ?? 'Aksi Selengkapnya') ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Kotak Pengumuman Sertifikat (Default Fallback) -->
                                <?php 
                                    $kategoriKelas = trim($pendaftaran['kategori_kelas'] ?? '');
                                    $isSertifikat = (strcasecmp($kategoriKelas, 'Pelatihan Sertifikasi') === 0 || stripos($kategoriKelas, 'sertifikasi') !== false);
                                ?>
                                <div class="p-3 mb-3 border border-purple border-opacity-25 rounded-4 bg-white shadow-sm">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-award fs-3 text-purple-custom me-3 mt-1"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1" style="color: #5b3fd6;">Informasi Sertifikat Pelatihan</h6>
                                            <p class="text-muted small mb-0">
                                                <?php if ($isSertifikat): ?>
                                                    Bagi peserta yang mengambil pilihan kelas dengan sertifikat, silakan lengkapi angket evaluasi terlebih dahulu melalui menu KBM sebelum mengunduh sertifikat Anda[cite: 9].
                                                <?php else: ?>
                                                    Kelas Anda terdaftar sebagai kelas basic/non-sertifikat, sehingga tidak mengambil opsi sertifikat[cite: 9].
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- KARTU PENGUMUMAN ANGKET -->
                            <?php if (isset($tampilkan_notif_angket) && $tampilkan_notif_angket): ?>
                                <div class="card shadow-sm border-0 rounded-4 p-4 mb-3 bg-white" style="border-left: 4px solid #7c5cfa !important;">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3 fs-3 text-purple-custom">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1" style="color: #5b3fd6;">Angket Evaluasi Pelatihan</h5>
                                            <p class="text-muted mb-3" style="font-size: 14px;">
                                                Anda telah menyelesaikan seluruh rangkaian ujian. Silakan isi angket evaluasi terlebih dahulu untuk membantu meningkatkan kualitas pelatihan kami.
                                            </p>
                                            <a href="<?= base_url('pelatihan/angket'); ?>" class="btn btn-sm text-white fw-bold px-3 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #7c5cfa, #5b3fd6);">
                                                Isi Angket Sekarang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Ketentuan Kehadiran -->
                            <div class="p-3 border border-light rounded-4 bg-white shadow-sm">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle fs-4 text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Ketentuan Kehadiran</h6>
                                        <p class="text-muted small mb-0">
                                            Pastikan selalu melakukan absensi pada setiap sesi pertemuan agar persentase kehadiran Anda tetap memenuhi syarat kelulusan minimal 80%.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Animasi angka statistik (count-up) - hanya efek tampilan, nilai akhir tetap sama dari server -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.stat-icon').forEach(function (icon) {
        var wrapper = icon.parentElement;
        if (!wrapper) return;
        var numEl = wrapper.querySelector('h3');
        if (!numEl) return;

        var raw = numEl.textContent.trim();
        var match = raw.match(/^(\d+)(.*)$/);
        if (!match) return;

        var target = parseInt(match[1], 10);
        var suffix = match[2] || '';
        if (isNaN(target) || target <= 0) return;

        var current = 0;
        var duration = 900;
        var stepTime = Math.max(Math.floor(duration / target), 25);

        numEl.textContent = '0' + suffix;

        var timer = setInterval(function () {
            current++;
            numEl.textContent = current + suffix;
            if (current >= target) {
                numEl.textContent = target + suffix;
                clearInterval(timer);
            }
        }, stepTime);
    });
});
</script>
</body>
</html>