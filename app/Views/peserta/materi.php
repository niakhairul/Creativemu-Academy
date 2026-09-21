<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($materi['judul_materi'] ?? 'Detail Materi') ?> - Creativemu</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf5ff;
            background-image: radial-gradient(#d8b4fe 1px, transparent 1px);
            background-size: 24px 24px;
            margin: 0;
            padding: 0;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #581c87 0%, #7c3aed 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px;
            box-shadow: 4px 0 20px rgba(124, 58, 237, 0.1);
            overflow-y: auto;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu a i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        /* =========================
           KONTEN UTAMA
        ========================= */

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
        }

        /* =========================
           KONTEN MATERI
        ========================= */

        .materi-card {
            background: #ffffff;
            border: 1px solid #eeeeee;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.25s ease;
            margin-bottom: 18px;
        }

        .materi-card .card-body {
            padding: 5px;
        }

        .materi-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0e7ff;
            border-radius: 16px;
            font-size: 28px;
            flex-shrink: 0;
        }

        .materi-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .materi-description {
            color: #6c757d;
            line-height: 1.7;
            margin-bottom: 0;
        }

        .file-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
            border: 1px solid #eeeeee;
            border-radius: 14px;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .file-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #f1eaff;
            color: #6f32c9;
            font-size: 22px;
        }

        .btn-primary {
            border-radius: 8px;
        }

        .btn-light {
            border-radius: 8px;
        }

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
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 20px 14px !important;
            }

            .file-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 14px 10px !important;
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
    <span class="text-white small bg-white bg-opacity-20 px-2 py-1 rounded-pill">
        <i class="bi bi-file-earmark-text"></i> Materi
    </span>
</div>

<!-- BACKDROP UNTUK MENUTUP SIDEBAR DI MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- =========================
     SIDEBAR PESERTA
========================= -->
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
                <i class="bi bi-journals"></i>
                Pengaturan
            </a>
        </li>

        <li class="mt-5">
            <a href="<?= base_url('auth/logout') ?>"
               class="text-danger-subtle bg-danger bg-opacity-10">

                <i class="bi bi-box-arrow-left"></i>
                Keluar

            </a>
        </li>

    </ul>

</nav>

<div class="app-wrapper">


    <!-- =========================
         KONTEN UTAMA
    ========================= -->

    <div class="main-content">

        <!-- Navbar Atas -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4 px-4 py-3">

            <div class="container-fluid">

                <span class="navbar-brand mb-0 h5 fw-bold">
                    Detail Materi
                </span>

                <span class="text-muted">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    Peserta
                </span>

            </div>

        </nav>


        <div class="container-fluid px-0">

            <?php if (empty($materi)): ?>

                <!-- =========================
                     MATERI TIDAK DITEMUKAN
                ========================= -->

                <div class="materi-card">

                    <div class="card-body">

                        <div class="alert alert-warning mb-0">

                            <i class="bi bi-exclamation-circle me-2"></i>

                            Materi tidak ditemukan.

                        </div>

                    </div>

                </div>


            <?php else: ?>


                <!-- =========================
                     INFORMASI MATERI
                ========================= -->

                <div class="materi-card">

                    <div class="card-body">

                        <div class="d-flex align-items-center gap-3">

                            <div class="materi-icon">
                                📚
                            </div>

                            <div>

                                <span class="badge bg-primary mb-2">
                                    Materi Pembelajaran
                                </span>

                                <div class="materi-title">

                                    <?= esc($materi['judul_materi']) ?>

                                </div>

                                <p class="materi-description">

                                    <?= esc($materi['deskripsi'] ?? 'Tidak ada deskripsi materi.') ?>

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- =========================
                     MODUL PEMBELAJARAN
                ========================= -->

                <div class="materi-card">

                    <div class="card-body">

                        <h4 class="fw-bold mb-3">
                            📄 Modul Pembelajaran
                        </h4>

                        <p class="text-muted mb-4">

                            Unduh modul pembelajaran yang telah disediakan oleh mentor.

                        </p>


                        <?php if (!empty($materi['file_materi'])): ?>

                            <div class="file-card">

                                <div class="file-info">

                                    <div class="file-icon">

                                        <i class="bi bi-file-earmark-pdf-fill"></i>

                                    </div>

                                    <div>

                                        <strong>
                                            <?= esc($materi['file_materi']) ?>
                                        </strong>

                                        <div class="text-muted small">
                                            File materi pembelajaran
                                        </div>

                                    </div>

                                </div>


                                <a href="<?= base_url('uploads/materi/' . $materi['file_materi']) ?>"
                                   class="btn btn-primary"
                                   download>

                                    <i class="bi bi-download me-1"></i>

                                    Download Modul

                                </a>

                            </div>

                        <?php else: ?>

                            <div class="alert alert-info mb-0">

                                <i class="bi bi-info-circle me-2"></i>

                                File materi belum tersedia.

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- =========================
                     DESKRIPSI MATERI
                ========================= -->

                <div class="materi-card">

                    <div class="card-body">

                        <h4 class="fw-bold mb-3">
                            📝 Deskripsi Materi
                        </h4>

                        <p class="materi-description">

                            <?= esc($materi['deskripsi'] ?? 'Tidak ada deskripsi materi.') ?>

                        </p>

                    </div>

                </div>


                <!-- =========================
                     NAVIGASI
                ========================= -->

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <a href="<?= base_url('pelatihan/kelas') ?>"
                       class="btn btn-light border">

                        <i class="bi bi-arrow-left me-1"></i>

                        Kembali ke KBM

                    </a>

                    <a href="<?= base_url('pelatihan/tugas') ?>"
                       class="btn btn-success">

                        Lanjut ke Tugas

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>


            <?php endif; ?>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->
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