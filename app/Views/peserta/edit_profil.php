<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Profil - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
          rel="stylesheet">

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

        <!-- HEADER -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-4 mb-4 px-4 py-3">

            <div class="container-fluid">

                <span class="navbar-brand mb-0 h5 fw-bold">
                    Edit Profil
                </span>

                <span class="text-muted">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    Peserta
                </span>

            </div>

        </nav>


        <div class="container-fluid px-0">

            <!-- CARD EDIT PROFIL -->
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-md-5">

                    <!-- JUDUL -->
                    <div class="mb-4">

                        <h5 class="fw-bold mb-1">
                            Informasi Profil
                        </h5>

                        <p class="text-muted mb-0">
                            Perbarui informasi pribadi dan foto profil Anda.
                        </p>

                    </div>


                    <form action="<?= base_url('pelatihan/update-profil') ?>"
                          method="post"
                          enctype="multipart/form-data">

                        <?= csrf_field() ?>


                        <!-- FOTO PROFIL -->
                        <div class="text-center mb-4">

                            <?php
                            if (!empty($pendaftaran['pas_foto'])) {

                                $fotoUrl = base_url(
                                    'uploads/foto/' . $pendaftaran['pas_foto']
                                );

                            } elseif (!empty($user['foto_profil'])) {

                                $fotoUrl = base_url(
                                    'uploads/profil/' . $user['foto_profil']
                                );

                            } else {

                                $fotoUrl = base_url(
                                    'assets/img/logo creativemu academy.jpg'
                                );

                            }
                            ?>

                            <img src="<?= $fotoUrl ?>"
                                 class="rounded-circle shadow-sm border"
                                 width="120"
                                 height="120"
                                 style="object-fit: cover;">

                            <div class="mt-3">

                                <label class="form-label fw-semibold d-block">
                                    Foto Profil
                                </label>

                                <input
                                    type="file"
                                    name="foto"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png">

                                <small class="text-muted">
                                    JPG, JPEG, atau PNG. Maksimal 2 MB.
                                </small>

                            </div>

                        </div>


                        <hr class="my-4">


                        <!-- NAMA -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control form-control-lg"
                                value="<?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '') ?>"
                                placeholder="Masukkan nama lengkap"
                                required>

                        </div>


                        <!-- EMAIL & NO HP -->
                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control form-control-lg"
                                    value="<?= esc($pendaftaran['email'] ?? $user['email'] ?? '') ?>"
                                    placeholder="Masukkan email"
                                    required>

                            </div>


                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Nomor HP / WhatsApp
                                </label>

                                <input
                                    type="text"
                                    name="no_hp"
                                    class="form-control form-control-lg"
                                    value="<?= esc($pendaftaran['no_hp'] ?? $user['no_hp'] ?? '') ?>"
                                    placeholder="08xxxxxxxxxx">

                            </div>

                        </div>


                        <!-- JENIS KELAMIN & TTL -->
                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Jenis Kelamin
                                </label>

                                <select
                                    name="jenis_kelamin"
                                    class="form-select form-select-lg">

                                    <option value="">
                                        Pilih Jenis Kelamin
                                    </option>

                                    <option value="Laki-laki"
                                        <?= ($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>
                                        Laki-laki
                                    </option>

                                    <option value="Perempuan"
                                        <?= ($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>
                                        Perempuan
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Tempat, Tanggal Lahir
                                </label>

                                <input
                                    type="text"
                                    name="ttl"
                                    class="form-control form-control-lg"
                                    value="<?= esc($pendaftaran['ttl'] ?? '') ?>"
                                    placeholder="Contoh: Bali, 12-10-2004">

                            </div>

                        </div>


                        <!-- PENDIDIKAN & LOKASI -->
                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    Pendidikan Terakhir
                                </label>

                                <select
                                    name="pendidikan_terakhir"
                                    class="form-select form-select-lg">

                                    <option value="">
                                        Pilih Pendidikan
                                    </option>

                                    <option value="Smp/Sederajat"
                                        <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'Smp/Sederajat' ? 'selected' : '' ?>>
                                        SMP/Sederajat
                                    </option>

                                    <option value="Sma/Smk/Sederajat"
                                        <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'Sma/Smk/Sederajat' ? 'selected' : '' ?>>
                                        SMA/SMK/Sederajat
                                    </option>

                                    <option value="D3/D4"
                                        <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'D3/D4' ? 'selected' : '' ?>>
                                        D3/D4
                                    </option>

                                    <option value="S1"
                                        <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'S1' ? 'selected' : '' ?>>
                                        S1
                                    </option>

                                    <option value="S2"
                                        <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'S2' ? 'selected' : '' ?>>
                                        S2
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-6 mb-4">

    <label class="form-label fw-semibold">
        Lokasi Pelatihan
    </label>

    <input
        type="text"
        class="form-control form-control-lg"
        value="<?= esc($pendaftaran['lokasi_pelatihan'] ?? '') ?>"
        readonly>

    <small class="text-muted">
        Lokasi pelatihan ditentukan oleh penyelenggara.
    </small>

</div>

                        </div>


                        <!-- ALAMAT -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Alamat Lengkap
                            </label>

                            <textarea
                                name="alamat"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan alamat lengkap"><?= esc($pendaftaran['alamat'] ?? '') ?></textarea>

                        </div>


                        <!-- TOMBOL -->
                        <div class="d-flex gap-2 mt-4">

                            <button
                                type="submit"
                                class="btn btn-primary px-4">

                                💾 Simpan Perubahan

                            </button>


                            <a
                                href="<?= base_url('pelatihan/pengaturan') ?>"
                                class="btn btn-light border px-4">

                                Batal

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>