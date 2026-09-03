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
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .sidebar {
                width: 170px;
            }

            .main-content {
                margin-left: 170px;
                width: calc(100% - 170px);
                padding: 15px;
            }

            .file-card {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>
</head>

<body>

<div class="app-wrapper">

    <!-- =========================
         SIDEBAR PESERTA
    ========================= -->

    <nav class="sidebar">

        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4"></i>
            Creativemu
        </a>

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

</body>
</html>