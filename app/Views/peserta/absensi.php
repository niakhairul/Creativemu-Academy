<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Absensi Peserta - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
          rel="stylesheet">

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

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
        }

        .absensi-card {
            background: #ffffff;
            border: 1px solid #eeeeee;
            border-radius: 16px;
            padding: 20px;
            transition: all 0.25s ease;
        }

        .absensi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(108, 63, 200, 0.10);
        }

        .pertemuan-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            background: #eee5ff;
            color: #6f32c9;
            font-size: 12px;
            font-weight: 600;
        }

        .absensi-card .btn {
            border-radius: 8px;
            padding: 8px 15px;
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="app-wrapper">

    <!-- Sidebar -->
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
                <a href="<?= base_url('pelatihan/kbm') ?>">
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


    <!-- Konten -->
    <div class="main-content">

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4 px-4 py-3">

            <div class="container-fluid">

                <span class="navbar-brand mb-0 h5 fw-bold">
                    Absensi Peserta
                </span>

                <span class="text-muted">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    Peserta
                </span>

            </div>

        </nav>


        <div class="container-fluid px-0">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-2">
                        Absensi Kehadiran
                    </h5>

                    <p class="text-muted mb-4">
                        Silakan lakukan absensi sesuai jadwal pertemuan.
                    </p>


                    <?php if (session()->getFlashdata('success')): ?>

                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>

                    <?php endif; ?>


                    <?php if (session()->getFlashdata('error')): ?>

                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>

                    <?php endif; ?>


                    <?php if (!empty($jadwal)): ?>

                        <div class="row g-4">

                            <?php foreach ($jadwal as $item): ?>

                                <?php
                                $absensi = $item['absensi'] ?? null;
                                ?>

                                <div class="col-lg-6">

                                    <div class="absensi-card h-100">

                                        <span class="pertemuan-badge">
                                            Pertemuan
                                            <?= esc($item['pertemuan_ke'] ?? '-') ?>
                                        </span>


                                        <h5 class="fw-bold mt-3 mb-2">
                                            Pertemuan
                                            <?= esc($item['pertemuan_ke'] ?? '-') ?>
                                        </h5>


                                        <p class="text-muted mb-2">

                                            <i class="bi bi-calendar-event me-1"></i>

                                            <?= !empty($item['tanggal_kbm'])
                                                ? date('d F Y H:i', strtotime($item['tanggal_kbm']))
                                                : '-'
                                            ?>

                                        </p>


                                        <?php if (!empty($absensi)): ?>

                                            <?php if (($absensi['status'] ?? '') === 'hadir'): ?>

                                                <div class="alert alert-success mb-0">

                                                    <i class="bi bi-check-circle-fill me-2"></i>

                                                    Anda sudah hadir pada pertemuan ini.

                                                </div>

                                            <?php else: ?>

                                                <div class="alert alert-warning mb-0">

                                                    Status:
                                                    <strong>
                                                        <?= esc(ucfirst($absensi['status'] ?? '-')) ?>
                                                    </strong>

                                                </div>

                                            <?php endif; ?>


                                        <?php else: ?>

                                            <form action="<?= base_url('pelatihan/absensi/simpan') ?>"
                                                  method="post">

                                                <input type="hidden"
                                                       name="id_jadwal_kelas"
                                                       value="<?= esc($item['id_jadwal_kelas']) ?>">


                                                <button type="submit"
                                                        class="btn btn-primary">

                                                    <i class="bi bi-check-circle me-1"></i>

                                                    Absen Sekarang

                                                </button>

                                            </form>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>


                    <?php else: ?>

                        <div class="alert alert-info mb-0">

                            <i class="bi bi-info-circle me-2"></i>

                            Belum ada jadwal pertemuan.

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>