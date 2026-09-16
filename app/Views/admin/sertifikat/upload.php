<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Terbitkan Sertifikat'); ?> - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --dark-purple: #1e0f33;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            overflow-x: hidden;
            margin: 0;
        }

        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(121, 75, 196, 0.08);
            overflow-y: auto;
        }

        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        #sidebar .sidebar-header img {
            width: 240px;
            height: 95px;
            object-fit: cover;
            border-radius: 10px;
            filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
        }

        #sidebar .nav {
            padding: 20px 14px;
        }

        #sidebar .nav-item {
            margin-bottom: 6px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 18px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        #sidebar .nav-link i {
            margin-right: 14px;
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #fff;
            transform: translateX(6px);
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #fff;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        #main-content {
            margin-left: 275px;
            padding: 35px;
            animation: mainFadeIn 0.7s ease;
        }

        .top-navbar {
            background: #fff;
            padding: 22px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dash-header h3 {
            font-weight: 800;
            color: var(--dark-purple);
            font-size: 1.6rem;
            margin-bottom: 4px;
        }

        .dash-header p {
            color: #8c83a5;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-profile img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-purple);
        }

        .form-card {
            background: #fff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.06);
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark-purple);
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #e1dced;
            padding: 11px 13px;
            font-size: 0.85rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.2rem rgba(121, 75, 196, 0.12);
        }

        .btn-primary-custom {
            background: var(--sidebar-active-gradient);
            border: none;
            color: #fff;
            border-radius: 11px;
            padding: 11px 22px;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            color: #fff;
            opacity: 0.92;
        }

        .info-box {
            background: #f6f1ff;
            border: 1px solid #e7dcfb;
            border-radius: 14px;
            padding: 15px;
            color: #66558a;
            font-size: 0.82rem;
            margin-bottom: 22px;
        }

        .empty-state {
            text-align: center;
            padding: 35px 20px;
            color: #8c83a5;
        }

        .empty-state i {
            font-size: 42px;
            color: #b293f0;
            margin-bottom: 12px;
        }

        @keyframes mainFadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 992px) {
            #main-content {
                margin-left: 0;
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            #main-content {
                padding: 12px;
            }

            .top-navbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 18px;
                padding-top: 58px;
                position: relative;
            }

            .admin-profile {
                width: 100%;
            }

            .form-card .card-body {
                padding: 18px !important;
            }

            .btn-primary-custom {
                width: 100%;
            }
        }
    </style>

    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>

<body>

<!-- SIDEBAR -->
<nav id="sidebar">

    <div class="sidebar-header">
        <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>"
             alt="Creativemu Academy"
             class="img-fluid">
    </div>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">
                <i class="fas fa-book"></i>
                <span>Master Kelas</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/mentor'); ?>" class="nav-link">
                <i class="fas fa-chalkboard-user"></i>
                <span>Instruktur</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Data Peserta</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                <i class="fas fa-clipboard-check"></i>
                <span>Validasi Pendaftaran</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link">
                <i class="fas fa-book-open"></i>
                <span>Buku Induk</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/angket'); ?>" class="nav-link">
                <i class="fas fa-award"></i>
                <span>Angket</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link active">
                <i class="fas fa-certificate"></i>
                <span>Sertifikat</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
                <i class="fas fa-file-lines"></i>
                <span>Laporan</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link">
                <i class="fas fa-gear"></i>
                <span>Pengaturan</span>
            </a>
        </li>

        <li class="nav-item mt-4">
            <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                <i class="fas fa-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>
</nav>

<!-- MAIN -->
<div id="main-content">

    <!-- TOP NAVBAR -->
    <div class="top-navbar">

        <div class="dash-header">
            <h3>Terbitkan Sertifikat</h3>
            <p>Terbitkan sertifikat untuk peserta yang telah dinyatakan lulus.</p>
        </div>

        <div class="d-flex align-items-center gap-4">

            <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light"
                 id="current-date"
                 style="font-size: 0.82rem; font-weight: 600; color: #794bc4 !important;">
                Memuat tanggal...
            </div>

            <div class="admin-profile">

                <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ?: 'admin-profile.jpg')); ?>"
                     alt="Foto Profil">

                <div>
                    <div class="fw-bold" style="color: var(--dark-purple);">
                        <?= esc(session()->get('nama') ?? 'Super Admin'); ?>
                    </div>

                    <small class="text-muted">
                        Administrator
                    </small>
                </div>

            </div>

        </div>

    </div>

    <!-- KEMBALI -->
    <div class="mb-3">

        <a href="<?= base_url('admin/sertifikat'); ?>"
           class="btn btn-secondary"
           style="border-radius: 10px;">

            <i class="fa-solid fa-arrow-left me-1"></i>
            Kembali

        </a>

    </div>

    <!-- FORM -->
    <div class="form-card">

        <div class="card-body p-4">

            <div class="info-box">
                <i class="fas fa-circle-info me-2"></i>
                Hanya peserta yang sudah dinyatakan <strong>LULUS</strong>
                yang dapat diterbitkan sertifikatnya.
            </div>

            <?php if (empty($pesertaLulus)) : ?>

                <div class="empty-state">

                    <i class="fas fa-user-graduate d-block"></i>

                    <h6 class="fw-bold" style="color: var(--dark-purple);">
                        Belum Ada Peserta Lulus
                    </h6>

                    <p class="mb-3">
                        Belum terdapat data peserta yang dinyatakan lulus pada hasil ujian.
                    </p>

                    <a href="<?= base_url('admin/sertifikat'); ?>"
                       class="btn btn-primary-custom">

                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali ke Sertifikat

                    </a>

                </div>

            <?php else : ?>

                <form action="<?= base_url('admin/sertifikat/store'); ?>"
                      method="post"
                      enctype="multipart/form-data">

                    <?= csrf_field(); ?>

                    <!-- PESERTA -->
                    <div class="mb-4">

                        <label for="id_users" class="form-label">
                            Pilih Peserta Lulus
                        </label>

                        <select name="id_users"
                                id="id_users"
                                class="form-select"
                                required>

                            <option value="">
                                -- Pilih Peserta --
                            </option>

                            <?php foreach ($pesertaLulus as $p) : ?>

                               <option value="<?= esc($p['id_user']); ?>"
        data-id-kelas="<?= esc($p['id_kelas']); ?>"
        data-nama-kelas="<?= esc($p['nama_kelas'] ?? ''); ?>"
        <?= (isset($selectedUser) && $selectedUser == $p['id_user']
            && isset($selectedKelas) && $selectedKelas == $p['id_kelas'])
            ? 'selected'
            : ''; ?>>

    <?= esc($p['nama_peserta'] ?? '-'); ?>
    —
    <?= esc($p['nama_kelas'] ?? '-'); ?>
    (Nilai: <?= esc($p['nilai'] ?? '-'); ?>)

</option>

                            <?php endforeach; ?>

                        </select>

                        <small class="text-muted">
                            Daftar diambil otomatis dari peserta yang sudah dinyatakan LULUS.
                        </small>

                    </div>

                    <!-- KELAS -->
                    <div class="mb-4">

                        <label for="nama_kelas" class="form-label">
                            Kelas
                        </label>

                        <input type="text"
                               id="nama_kelas"
                               class="form-control"
                               placeholder="Kelas akan terisi otomatis"
                               readonly>

                        <input type="hidden"
                               name="id_kelas"
                               id="id_kelas">

                    </div>

                    <!-- NOMOR SERTIFIKAT -->
                    <div class="mb-4">

                        <label class="form-label">
                            Nomor Sertifikat
                        </label>

                        <input type="text"
                               class="form-control"
                               value="Nomor akan dibuat otomatis saat diterbitkan"
                               readonly>

                        <small class="text-muted">
                            Nomor sertifikat dibuat otomatis oleh sistem.
                        </small>

                    </div>

                    <!-- FILE -->
                    <div class="mb-4">

                        <label for="file_sertifikat" class="form-label">
                            File Sertifikat
                        </label>

                        <input type="file"
                               class="form-control"
                               id="file_sertifikat"
                               name="file_sertifikat"
                               accept=".pdf,.jpg,.jpeg,.png"
                               required>

                        <small class="text-muted">
                            Format yang diperbolehkan: PDF, JPG, JPEG, PNG.
                        </small>

                    </div>

                    <!-- TOMBOL -->
                    <div class="d-flex gap-2 flex-wrap">

                        <a href="<?= base_url('admin/sertifikat'); ?>"
                           class="btn btn-secondary"
                           style="border-radius: 11px; padding: 11px 22px;">

                            Batal

                        </a>

                        <button type="submit"
                                class="btn btn-primary-custom">

                            <i class="fas fa-certificate me-1"></i>
                            Terbitkan Sertifikat

                        </button>

                    </div>

                </form>

            <?php endif; ?>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Tanggal
    const currentDate = document.getElementById('current-date');

    if (currentDate) {
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        currentDate.innerText =
            new Date().toLocaleDateString('id-ID', options);
    }

    // Otomatis mengisi kelas berdasarkan peserta yang dipilih
    const pesertaSelect = document.getElementById('id_users');
const idKelasInput = document.getElementById('id_kelas');
const namaKelasInput = document.getElementById('nama_kelas');

function isiDataPeserta() {
    if (!pesertaSelect) return;

    const selectedOption =
        pesertaSelect.options[pesertaSelect.selectedIndex];

    if (!selectedOption || !pesertaSelect.value) {
        idKelasInput.value = '';
        namaKelasInput.value = '';
        return;
    }

    const idKelas =
        selectedOption.getAttribute('data-id-kelas') || '';

    const namaKelas =
        selectedOption.getAttribute('data-nama-kelas') || '';

    idKelasInput.value = idKelas;
    namaKelasInput.value = namaKelas;
}

if (pesertaSelect) {
    pesertaSelect.addEventListener('change', isiDataPeserta);

    // Isi otomatis saat halaman dibuka
    isiDataPeserta();
}
</script>

</body>
</html>