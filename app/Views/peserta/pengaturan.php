<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome untuk ikon pelengkap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #fdf4ff 100%);
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        /* Layout Utama dengan Sidebar */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar dengan Gradasi Ungu Deep Modern */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #4c1d95 0%, #6d28d9 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px 16px;
            box-shadow: 6px 0 25px rgba(109, 40, 217, 0.15);
            overflow-y: auto;
        }

        .sidebar-brand {
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 15px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu a i {
            font-size: 1.1rem;
            margin-right: 10px;
        }

        /* Konten Utama */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 24px;
            width: calc(100% - 250px);
        }

        /* Card yang Lebih Kompak dan Minimalis */
        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 6px 20px rgba(109, 40, 217, 0.04);
            transition: all 0.3s ease;
        }

        /* Navbar Atas Minimalis */
        .top-navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(109, 40, 217, 0.03);
        }
    </style>
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar Kustom -->
    <nav class="sidebar">
        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-5 text-warning"></i> Creativemu
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('peserta/dashboard') ?>"><i class="bi bi-grid-fill"></i> Dashboard</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>"><i class="bi bi-journals"></i> Daftar Kelas Saya</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/kbm') ?>"><i class="bi bi-mortarboard-fill"></i> KBM</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>" class="active"><i class="bi bi-gear-fill"></i> Pengaturan</a>
            </li>
            <li class="mt-4">
                <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </li>
        </ul>
    </nav>

    <!-- PAGE CONTENT -->
    <div class="main-content">
        <!-- Navbar Atas Kompak -->
        <nav class="navbar navbar-expand-lg top-navbar mb-3 px-3 py-2">
            <div class="container-fluid px-0">
                <span class="navbar-brand mb-0 h6 fw-bold text-dark">Pengaturan Akun</span>
                <span class="text-muted fw-semibold" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-user-circle me-1 text-primary"></i> Peserta
                </span>
            </div>
        </nav>

        <!-- Konten Pengaturan -->
        <div class="container-fluid px-0">

            <!-- NOTIFIKASI -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-3 py-2 small">
                    <strong>✅ Berhasil!</strong> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-3 py-2 small">
                    <strong>⚠️ Perhatian!</strong> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- KARTU PROFIL UTAMA -->
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body p-3 p-md-4">

                    <?php
                    if (!empty($user['foto_profil'])) {
                        $fotoUrl = base_url('uploads/profil/' . $user['foto_profil']);
                    } elseif (!empty($pendaftaran['pas_foto'])) {
                        $fotoUrl = base_url('uploads/foto/' . $pendaftaran['pas_foto']);
                    } else {
                        $fotoUrl = base_url('assets/img/logo creativemu academy.jpg');
                    }
                    ?>

                    <!-- HEADER PROFIL (Dibuat Lebih Rapat & Ringkas) -->
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-center gap-3 mb-3">
                        <!-- FOTO -->
                        <div class="flex-shrink-0">
                            <img src="<?= $fotoUrl ?>" class="rounded-circle shadow-sm border" width="85" height="85" style="object-fit: cover;">
                        </div>

                        <!-- NAMA DAN STATUS -->
                        <div class="text-center text-md-start flex-grow-1">
                            <h5 class="fw-bold mb-1" style="color: #4c1d95;"><?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?></h5>
                            <p class="text-muted small mb-2">Peserta CreativeMU Academy</p>
                            <?php if (!empty($pendaftaran['status'])): ?>
                                <span class="badge px-2.5 py-1 rounded-pill text-white" style="font-size: 0.75rem; background: linear-gradient(135deg, #7c3aed, #4c1d95);"><?= esc($pendaftaran['status']) ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- TOMBOL EDIT -->
                        <div>
                            <a href="<?= base_url('pelatihan/edit-profil') ?>" class="btn btn-sm text-white rounded-pill px-3 py-1.5 shadow-sm" style="font-size: 0.85rem; background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </a>
                        </div>
                    </div>

                    <hr class="my-3 border-purple border-opacity-10">

                    <!-- INFORMASI PRIBADI (Grid 3 Kolom atau 2 Kolom dengan Padding Lebih Kecil) -->
                    <h6 class="fw-bold mb-3" style="color: #4c1d95; font-size: 0.95rem;">Informasi Pribadi</h6>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Nama Lengkap</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Email</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['email'] ?? $user['email'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Nomor HP / WhatsApp</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['no_hp'] ?? $user['no_hp'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Jenis Kelamin</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Tempat, Tanggal Lahir</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['ttl'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Pendidikan Terakhir</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['pendidikan_terakhir'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Asal Sekolah / Kampus</small>
                                <span class="fw-semibold text-dark small"><?= esc($user['asal_sekolah'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Lokasi Pelatihan</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['lokasi_pelatihan'] ?? '-') ?></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="p-2.5 bg-white rounded-3 border border-light shadow-sm">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Alamat Lengkap</small>
                                <span class="fw-semibold text-dark small"><?= esc($pendaftaran['alamat'] ?? '-') ?></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- PENGATURAN KEAMANAN AKUN (Lebih Compact) -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3 p-md-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #4c1d95; font-size: 0.95rem;">Keamanan Akun</h6>
                        <p class="text-muted small mb-2 mb-md-0">Kelola kata sandi untuk menjaga keamanan akun Anda.</p>
                    </div>
                    <a href="<?= base_url('pelatihan/ubah-password') ?>" class="btn btn-sm btn-warning rounded-pill px-3 py-1.5 text-dark fw-semibold shadow-sm text-nowrap" style="font-size: 0.85rem;">
                        <i class="bi bi-shield-lock-fill me-1"></i> Ubah Password
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Load Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>