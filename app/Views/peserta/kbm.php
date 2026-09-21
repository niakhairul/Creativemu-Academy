<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>KBM Peserta - Creativemu Academy</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #fdf4ff 100%);
            min-height: 100vh;
        }

        /* =========================
           LAYOUT
        ========================== */

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR TERBARU PESERTA
        ========================== */

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
    0% {
        background-position: 0% 0%, 0% 0%;
    }

    50% {
        background-position: 100% 100%, 100% 100%;
    }

    100% {
        background-position: 0% 0%, 0% 0%;
    }
}

.sidebar::before {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;
    width: 180px;
    height: 180px;
    background: radial-gradient(
        circle,
        rgba(255,255,255,0.18) 0%,
        rgba(255,255,255,0) 70%
    );
    border-radius: 50%;
    pointer-events: none;
    animation: floatBlob 8s ease-in-out infinite;
}

@keyframes floatBlob {
    0%, 100% {
        transform: translateY(0) scale(1);
    }

    50% {
        transform: translateY(20px) scale(1.08);
    }
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
    0%, 100% {
        transform: scale(1) rotate(0deg);
    }

    50% {
        transform: scale(1.12) rotate(-4deg);
    }
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

.sidebar-menu li:nth-child(1) {
    animation-delay: 0.05s;
}

.sidebar-menu li:nth-child(2) {
    animation-delay: 0.12s;
}

.sidebar-menu li:nth-child(3) {
    animation-delay: 0.19s;
}

.sidebar-menu li:nth-child(4) {
    animation-delay: 0.26s;
}

.sidebar-menu li:nth-child(5) {
    animation-delay: 0.33s;
}

.sidebar-menu li:nth-child(6) {
    animation-delay: 0.40s;
}

.sidebar-menu li:nth-child(7) {
    animation-delay: 0.47s;
}

@keyframes menuSlideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
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

.sidebar-menu a::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.15),
        transparent
    );
    transition: left 0.6s ease;
}

.sidebar-menu a:hover::before {
    left: 100%;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: rgba(255, 255, 255, 0.18);
    color: white;
    transform: translateX(6px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
}

.sidebar-menu a.active {
    background: linear-gradient(
        90deg,
        rgba(255, 255, 255, 0.28),
        rgba(255, 255, 255, 0.12)
    );
    box-shadow:
        0 6px 18px rgba(20, 5, 60, 0.28),
        inset 3px 0 0 #ffd166;
}

.sidebar-menu a i {
    font-size: 1.2rem;
    margin-right: 12px;
    transition: transform 0.3s ease;
}

.sidebar-menu a:hover i {
    transform: scale(1.15) rotate(-6deg);
}

.sidebar-menu .logout {
    margin-top: 45px;
    color: #ef4444;
    background: rgba(220,38,38,0.08);
}

.sidebar-menu .logout:hover {
    background: rgba(220,38,38,0.15);
    color: #ef4444;
}

        /* =========================
           CONTENT
        ========================== */

        .main-content {
            width: calc(100% - 240px);
            margin-left: 240px;
            padding: 30px;
        }

        .kbm-page {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* =========================
           HERO
        ========================== */

        .kbm-hero {
            position: relative;
            overflow: hidden;

            background: linear-gradient(125deg, #24123f, #6135a5);

            border-radius: 22px;

            color: #fff;

            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.10);
        }

        .kbm-hero::after {
            content: '';

            position: absolute;

            width: 250px;
            height: 250px;

            border: 38px solid rgba(255,255,255,0.08);
            border-radius: 50%;

            right: -65px;
            top: -125px;
        }

        .kbm-hero-content {
            position: relative;
            z-index: 2;
        }

        .kbm-hero h2 {
            font-size: 2rem;
        }

        .kbm-hero-description {
            color: rgba(255,255,255,0.75);
            line-height: 1.7;
        }

        /* =========================
           TABS
        ========================== */

        .kbm-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;

            border: 0;
        }

        .kbm-tabs .nav-link {
            border: 1px solid #e6dcef;
            border-radius: 11px;

            color: #665a73;

            background: rgba(255,255,255,0.75);

            font-size: 0.9rem;
            font-weight: 600;

            padding: 0.75rem 1rem;

            transition: all 0.25s ease;
        }

        .kbm-tabs .nav-link:hover {
            background: #f3eaff;
            color: #7042b4;
        }

        .kbm-tabs .nav-link.active {
            background: #7042b4;
            border-color: #7042b4;
            color: #fff;

            box-shadow: 0 6px 15px rgba(112,66,180,0.20);
        }

        /* =========================
           CONTENT CARD
        ========================== */

        .kbm-shell {
            background: rgba(255,255,255,0.95);

            border: 1px solid #ece6f4;
            border-radius: 18px;

            box-shadow: 0 9px 25px rgba(37,19,64,0.05);
        }

        .kbm-shell h4 {
            color: #292233;
            font-weight: 700;
        }

        /* =========================
           MATERI
        ========================== */

        .kbm-item {
            display: flex;
            gap: 1rem;

            padding: 1rem;

            border: 1px solid #eee8f4;
            border-radius: 14px;

            background: #fff;

            transition: all 0.25s ease;
        }

        .kbm-item:hover {
            transform: translateY(-2px);

            box-shadow: 0 8px 20px rgba(112,66,180,0.08);
        }

        .kbm-icon {
            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            width: 46px;
            height: 46px;

            border-radius: 12px;

            background: #f0e8fb;
            color: #7042b4;
        }

        /* =========================
           TABLE
        ========================== */

        .kbm-table {
            border: 1px solid #eee8f4;
            border-radius: 12px;
            overflow: hidden;
        }

        .kbm-table thead th {
            background: #faf8fd;
            color: #675a73;

            font-size: 0.78rem;
            text-transform: uppercase;

            border-bottom: 1px solid #eee8f4;
        }

        .kbm-table td {
            vertical-align: middle;
        }

        /* =========================
           BUTTON
        ========================== */

        .btn-primary {
            background: #7042b4;
            border-color: #7042b4;
        }

        .btn-primary:hover {
            background: #5f349f;
            border-color: #5f349f;
        }

        /* =========================
           RESPONSIVE
        ========================== */

        /* =========================
           RESPONSIVE & MOBILE DRAWER
        ========================= */
        .mobile-topbar {
            display: none;
            width: 100%;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 7, 35, 0.55);
            backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        .sidebar-close-btn {
            display: none;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.2s ease;
        }
        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        @media (max-width: 991.98px) {
            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #4a2fc9 0%, #7440e6 100%);
                color: white;
                padding: 14px 18px;
                position: sticky;
                top: 0;
                z-index: 990;
                box-shadow: 0 4px 18px rgba(116, 64, 230, 0.25);
            }

            .app-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 280px;
                max-width: 82vw;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
                box-shadow: 10px 0 40px rgba(0, 0, 0, 0.35);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-close-btn {
                display: flex;
            }

            .main-content {
                width: 100% !important;
                margin-left: 0 !important;
                padding: 20px 14px !important;
            }

            .kbm-tabs {
                display: flex;
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                gap: 8px;
                padding-bottom: 6px;
            }

            .kbm-tabs .nav-link {
                white-space: nowrap;
                font-size: 0.85rem;
                padding: 0.65rem 0.9rem;
                flex-shrink: 0;
            }

            .kbm-hero {
                border-radius: 16px;
            }

            .kbm-hero h2 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 14px 10px !important;
            }
            .kbm-hero h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>

<!-- TOPBAR KHUSUS MOBILE -->
<div class="mobile-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-link text-white p-0 fs-3 text-decoration-none lh-1 shadow-none" id="sidebarToggle" aria-label="Buka Menu">
            <i class="bi bi-list"></i>
        </button>
        <span class="fw-bold fs-6 tracking-wide">
            <i class="bi bi-mortarboard-fill text-warning me-1"></i> Creativemu
        </span>
    </div>
    <a href="<?= base_url('pelatihan/pengaturan') ?>" class="text-white text-decoration-none small d-flex align-items-center gap-1 bg-white bg-opacity-20 px-2 py-1 rounded-pill">
        <i class="bi bi-person-circle"></i> Peserta
    </a>
</div>

<!-- BACKDROP UNTUK MENUTUP SIDEBAR DI MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- =========================
     SIDEBAR
========================== -->
<nav class="sidebar" id="sidebarMenu">

    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-15">
        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand p-0 m-0 border-0">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i> Creativemu
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarClose" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="<?= base_url('peserta/dashboard') ?>">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>">
                <i class="bi bi-journals"></i>
                Daftar Kelas Saya
            </a>
        </li>

        <li>
            <a href="<?= base_url('pelatihan/kbm') ?>" class="active">
                <i class="bi bi-mortarboard-fill"></i>
                KBM
            </a>
        </li>

        <li>
            <a href="<?= base_url('pelatihan/pengaturan') ?>">
                <i class="bi bi-gear-fill"></i>
                Pengaturan
            </a>
        </li>

        <li>
            <a href="<?= base_url('auth/logout') ?>" class="logout">
                <i class="bi bi-box-arrow-left"></i>
                Keluar
            </a>
        </li>

    </ul>

</nav>

<div class="app-wrapper">


    <!-- =========================
         KONTEN UTAMA
    ========================== -->

    <main class="main-content">

        <div class="kbm-page">

            <!-- HEADER KELAS -->

            <section class="kbm-hero p-4 p-lg-5 mb-4">

                <div class="kbm-hero-content">

                    <span class="badge rounded-pill bg-light text-primary mb-3">
                        RUANG BELAJAR
                    </span>

                    <h2 class="fw-bold mb-2">
                        <?= esc($kelas['nama_kelas']) ?>
                    </h2>

                    <p class="kbm-hero-description mb-3">
                        <?= esc($kelas['ringkasan'] ?? $kelas['deskripsi'] ?? '-') ?>
                    </p>

                    <span class="badge bg-light text-primary">
                        Mentor:
                        <?= esc($kelas['nama_mentor'] ?? '-') ?>
                    </span>


                </div>

            </section>


            <!-- NAVIGASI TAB -->

            <ul class="nav kbm-tabs mb-3" id="kbmTab" role="tablist">

                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link active"
                        id="materi-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#materi"
                        type="button"
                        role="tab">

                        📚 Materi Pembelajaran

                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="absensi-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#absensi"
                        type="button"
                        role="tab">

                        📅 Absensi & Riwayat

                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="tugas-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#tugas"
                        type="button"
                        role="tab">

                        📝 Ujian & Tugas

                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button
                        class="nav-link"
                        id="sertifikat-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#sertifikat"
                        type="button"
                        role="tab">

                        🏆 Sertifikat & Angket

                    </button>

                </li>

            </ul>


            <!-- KONTEN TAB -->

            <div class="tab-content kbm-shell p-4" id="kbmTabContent">


                <!-- =========================
                     TAB 1 : MATERI
                ========================== -->

                <div
                    class="tab-pane fade show active"
                    id="materi"
                    role="tabpanel">

                    <h4 class="mb-2">
                        Daftar Modul & Materi Sesi
                    </h4>

                    <p class="text-muted mb-4">
                        Unduh atau pelajari modul materi yang telah diunggah oleh mentor.
                    </p>

                    <?php if (empty($jadwal)): ?>

                        <div class="alert alert-info">
                            Belum ada sesi materi.
                        </div>

                    <?php else: ?>

                        
<?php foreach ($jadwal as $j): ?>

    <div class="kbm-item mb-3">

        <div class="kbm-icon">
            <i class="fa-solid fa-book-open"></i>
        </div>

        <div class="flex-grow-1">

            <div class="fw-bold mb-1">
                Pertemuan
                <?= esc($j['pertemuan_ke']) ?>
            </div>

            <div class="text-muted">
                <?= esc($j['materi'] ?? $j['topik'] ?? 'Materi sesi') ?>
            </div>

            <div class="text-muted small mt-2">
                <i class="fa-regular fa-calendar me-1"></i>

                <?php if (!empty($j['tanggal_kbm'])): ?>

                    <?= esc(
                        date(
                            'd M Y, H:i',
                            strtotime($j['tanggal_kbm'])
                        )
                    ) ?>

                <?php else: ?>

                    Jadwal belum ditentukan

                <?php endif; ?>
            </div>

            <div class="mt-3">

                <?php if (isset($j['absensi']['status'])): ?>

                    <span class="badge bg-success text-capitalize">
                        <?= esc($j['absensi']['status']) ?>
                    </span>

                <?php else: ?>

                    <span class="badge bg-warning text-dark">
                        Belum Absen
                    </span>

                    <?php if (!isset($j['absensi'])): ?>
                        <div class="mt-2">
                            <a href="<?= base_url('pelatihan/absensi') ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-geo-alt-fill me-1"></i> Absen Sekarang (GPS)
                            </a>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

        </div>

    </div>

<?php endforeach; ?>
                      

                    <?php endif; ?>

                </div>


                <!-- =========================
                     TAB 2 : ABSENSI
                ========================== -->

                <div
                    class="tab-pane fade"
                    id="absensi"
                    role="tabpanel">

                    <h4 class="mb-3">
                        Absensi Kehadiran & Riwayat
                    </h4>

                    <div class="mb-3">

                        <span class="badge bg-secondary me-1">
                            Total Hadir:
                            <?= $jumlahHadir ?>
                            dari
                            <?= $totalPertemuan ?>
                            Pertemuan
                        </span>

                        <span class="badge bg-info">
                            Persentase:
                            <?= $persentaseKehadiran ?>%
                        </span>

                    </div>


                    <?php if (empty($jadwal)): ?>

                        <div class="alert alert-info">
                            Belum ada jadwal pertemuan.
                        </div>

                    <?php else: ?>

                        <div class="table-responsive">

                            <table class="table kbm-table table-hover align-middle mb-0">

                                <thead>

                                    <tr>
                                        <th>Pertemuan</th>
                                        <th>Topik / Tanggal</th>
                                        <th>Status Kehadiran</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach ($jadwal as $j): ?>

                                        <tr>

                                            <td>
                                                <?= esc($j['pertemuan_ke']) ?>
                                            </td>

                                            <td>

                                                <?= esc(
                                                    $j['materi']
                                                    ?? 'Sesi ' . $j['pertemuan_ke']
                                                ) ?>

                                                <?php if (!empty($j['tanggal_kbm'])): ?>

                                                    <div class="text-muted small mt-1">

                                                        <?= esc(
                                                            date(
                                                                'd M Y, H:i',
                                                                strtotime($j['tanggal_kbm'])
                                                            )
                                                        ) ?>

                                                    </div>

                                                <?php endif; ?>

                                            </td>

                                            <td>

                                                <?php if (!empty($j['absensi']['status'])): ?>

                                                    <span class="badge bg-success text-capitalize">
                                                        <?= esc($j['absensi']['status']) ?>
                                                    </span>

                                                <?php else: ?>

                                                    <span class="badge bg-warning text-dark">
                                                        Belum Absen
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <td>

                                                <?php if (!isset($j['absensi'])): ?>
                                                    <a href="<?= base_url('pelatihan/absensi') ?>" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-geo-alt-fill me-1"></i> Absen (GPS)
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">
                                                        <i class="bi bi-check-circle-fill text-success"></i> Selesai
                                                    </span>
                                                <?php endif; ?>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- =========================
                     TAB 3 : UJIAN & TUGAS
                ========================== -->

                <div
                    class="tab-pane fade"
                    id="tugas"
                    role="tabpanel">

                    <h4 class="mb-3">
                        Daftar Tugas & Ujian
                    </h4>

                    <div class="row">


                        <!-- TUGAS -->

                        <div class="col-md-6 mb-3">

                            <div class="card border h-100">

                                <div class="card-body">

                                    <h5 class="fw-bold">
                                        Tugas Praktikum
                                    </h5>

                                    <p class="text-muted">
                                        Status:
                                        <?= !empty($pengumpulan)
                                            ? 'Sudah Diunggah'
                                            : 'Belum Dikerjakan' ?>
                                    </p>

                                    <a
                                        href="<?= base_url('pelatihan/tugas') ?>"
                                        class="btn btn-sm btn-outline-primary">

                                        Kelola Tugas

                                    </a>

                                </div>

                            </div>

                        </div>


                        <!-- UJIAN -->

                        <div class="col-md-6 mb-3">

                            <div class="card border h-100">

                                <div class="card-body">

                                    <h5 class="fw-bold">
                                        Ujian Akhir Kelas
                                    </h5>

                                    <p class="text-muted">
                                        Nilai:
                                        <?= $hasilUjian['nilai'] ?? 'Belum Ujian' ?>
                                    </p>

                                    <a
                                        href="<?= base_url('pelatihan/ujian') ?>"
                                        class="btn btn-sm btn-outline-primary">

                                        Mulai / Cek Ujian

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =========================
                     TAB 4 : ANGKET & SERTIFIKAT
                ========================== -->

                <div
                    class="tab-pane fade"
                    id="sertifikat"
                    role="tabpanel">

                    <h4 class="mb-3">
                        Evaluasi & Sertifikat Kelulusan
                    </h4>


                    <!-- ANGKET -->

                    <div class="mb-4">

                        <h5>
                            1. Angket Evaluasi Kepuasan
                        </h5>

                        <?php if ($sudahIsiAngket): ?>

                            <div class="alert alert-success">

                                Terima kasih, Anda telah mengisi
                                angket evaluasi pelatihan ini.

                            </div>

                        <?php else: ?>

                            <p class="text-muted">

                                Silakan isi angket kepuasan terlebih dahulu
                                untuk membuka akses unduh sertifikat.

                            </p>

                            <a
                                href="<?= base_url('pelatihan/angket') ?>"
                                class="btn btn-warning">

                                Isi Angket Evaluasi

                            </a>

                        <?php endif; ?>

                    </div>


                    <hr>


                    <!-- SERTIFIKAT -->

                    <div>

                        <h5>
                            2. Sertifikat Pelatihan
                        </h5>

                        <?php if ($sertifikatAcademy): ?>

                            <div class="alert alert-success">

                                Selamat! Anda lulus dan dapat
                                mengunduh sertifikat Anda.

                            </div>

                            <a
                                href="<?= base_url('pelatihan/sertifikat') ?>"
                                class="btn btn-success">

                                Unduh Sertifikat

                            </a>

                        <?php else: ?>

                            <div class="alert alert-warning">

                                Sertifikat belum dapat diunduh.
                                Pastikan Anda lulus ujian
                                (minimal nilai 70) dan sudah
                                mengisi angket evaluasi.

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebarMenu');
    const backdrop = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('show');
        if (backdrop) backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);

    const navLinks = sidebar ? sidebar.querySelectorAll('li a') : [];
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });
});
</script>

</body>
</html>