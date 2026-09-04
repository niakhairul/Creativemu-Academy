<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </style>
</head>

<body>

<div class="app-wrapper">

    <!-- SIDEBAR -->
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
                <a href="<?= base_url('pelatihan/kelas') ?>">
                    <i class="bi bi-mortarboard-fill"></i>
                    KBM
                </a>
            </li>

            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>" class="active">
                    <i class="bi bi-gear-fill"></i>
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


    <!-- CONTENT -->
    <div class="main-content">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-4 mb-4 px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h5 fw-bold">
                    Pengaturan Akun
                </span>

                <span class="text-muted">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    Peserta
                </span>
            </div>
        </nav>


        <div class="container-fluid px-0">

            <!-- NOTIFIKASI -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4">
                    <strong>✅ Berhasil!</strong>
                    <?= session()->getFlashdata('success') ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>


            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4">
                    <strong>⚠️ Perhatian!</strong>
                    <?= session()->getFlashdata('error') ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>


            <!-- KARTU PROFIL -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">

                <div class="card-body p-4 p-md-5">

                    <?php
if (!empty($user['foto_profil'])) {
    $fotoUrl = base_url(
        'uploads/profil/' . $user['foto_profil']
    );
} elseif (!empty($pendaftaran['pas_foto'])) {
    $fotoUrl = base_url(
        'uploads/foto/' . $pendaftaran['pas_foto']
    );
} else {
    $fotoUrl = base_url(
        'assets/img/logo creativemu academy.jpg'
    );
}

?>


                    <!-- HEADER PROFIL -->
                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 mb-4">

                        <!-- FOTO -->
                        <div class="text-center">

                            <img src="<?= $fotoUrl ?>"
                                 class="rounded-circle shadow-sm border"
                                 width="130"
                                 height="130"
                                 style="object-fit: cover;">

                        </div>


                        <!-- NAMA -->
                        <div class="text-center text-md-start flex-grow-1">

                            <h3 class="fw-bold mb-1">
                                <?= esc($pendaftaran['nama'] ?? '-') ?>
                            </h3>

                            <p class="text-muted mb-2">
                                Peserta CreativeMU Academy
                            </p>

                           <?php
$statusPembayaran = strtolower($pendaftaran['status_pembayaran'] ?? '');

if ($statusPembayaran === 'valid') {
    $statusTampil = 'Aktif';
} elseif ($statusPembayaran === 'rejected' || $statusPembayaran === 'ditolak') {
    $statusTampil = 'Ditolak';
} else {
    $statusTampil = 'Pending';
}
?>
<span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
    <?= esc($statusTampil) ?>
</span>

                        </div>


                        <!-- EDIT -->
                        <div class="d-flex gap-2">

                            <a href="<?= base_url('pelatihan/edit-profil') ?>"
                               class="btn btn-primary rounded-pill px-4">

                                <i class="bi bi-pencil-square me-1"></i>
                                Edit Profil

                            </a>

                        </div>

                    </div>


                    <hr class="my-4">


                    <!-- INFORMASI PRIBADI -->
                    <h5 class="fw-bold mb-4">
                        Informasi Pribadi
                    </h5>


                    <div class="row g-4">

                        <!-- NAMA -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Nama Lengkap
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['nama'] ?? '-') ?>
                                </span>

                            </div>
                        </div>


                        <!-- EMAIL -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Email
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['email'] ?? '-') ?>
                                </span>

                            </div>
                        </div>


                        <!-- NO HP -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Nomor HP / WhatsApp
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['no_hp'] ?? '-') ?>
                                </span>

                            </div>
                        </div>


                        <!-- JENIS KELAMIN -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Jenis Kelamin
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['jenis_kelamin'] ?? '-') ?>
                                </span>

                            </div>
                        </div>


                        <!-- TTL -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Tempat, Tanggal Lahir
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['ttl'] ?? '-') ?>
                                </span>

                            </div>
                        </div>


                        <!-- PENDIDIKAN -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Pendidikan Terakhir
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['pendidikan_terakhir'] ?? '-') ?>
                                </span>

                            </div>
                        </div>



                        <!-- LOKASI -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Lokasi Pelatihan
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['lokasi_pelatihan'] ?? '-') ?>
                                </span>

                            </div>
                        </div>


                        <!-- ALAMAT -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">

                                <small class="text-muted d-block mb-1">
                                    Alamat Lengkap
                                </small>

                                <span class="fw-semibold">
                                    <?= esc($pendaftaran['alamat'] ?? '-') ?>
                                </span>

                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <!-- KEAMANAN AKUN -->
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-1">
                        Keamanan Akun
                    </h5>

                    <p class="text-muted mb-4">
                        Kelola kata sandi untuk menjaga keamanan akun Anda.
                    </p>

                    <a href="<?= base_url('pelatihan/ubah-password') ?>"
                       class="btn btn-warning rounded-pill px-4 text-dark fw-semibold">

                        <i class="bi bi-shield-lock-fill me-1"></i>
                        Ubah Password

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>