<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
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

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f7f5fd;
        }

        ::-webkit-scrollbar-thumb {
            background: #b293f0;
            border-radius: 10px;
        }

        /* SIDEBAR */
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.3s ease;
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

        /* MAIN */
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
            border: 1px solid rgba(121, 75, 196, 0.04);
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
            gap: 15px;
        }

        .admin-profile img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--primary-purple);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-purple);
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.78rem;
        }

        /* CONTENT */
        .page-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.06);
        }

        .filter-card {
            background: #fff;
            border-radius: 18px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(121, 75, 196, 0.05);
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--dark-purple);
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #e4dff0;
            padding: 10px 12px;
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
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            color: #fff;
            opacity: 0.92;
        }

        .section-title {
            color: var(--dark-purple);
            font-weight: 700;
            margin-bottom: 0;
        }

        .badge-lulus {
            background: #dff7e8;
            color: #198754;
        }

        .badge-belum {
            background: #fff0d9;
            color: #b76e00;
        }

        .badge-sertifikat {
            background: #e9ddff;
            color: #6f42c1;
        }

        .badge-belum-sertifikat {
            background: #f1f1f1;
            color: #666;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.84rem;
        }

        .table thead th {
            background: var(--dark-purple);
            color: #fff;
            border: none;
            white-space: nowrap;
            padding: 14px 12px;
        }

        .table tbody td {
            padding: 13px 12px;
            vertical-align: middle;
        }

        .certificate-card {
            display: none;
        }

        .empty-state {
            text-align: center;
            padding: 45px 20px;
            color: #8c83a5;
        }

        .empty-state i {
            font-size: 42px;
            margin-bottom: 12px;
            color: #b293f0;
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
            #sidebar {
                width: 275px;
            }

            #main-content {
                margin-left: 0;
                padding: 20px;
            }

            .top-navbar {
                padding: 18px;
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

            .filter-card {
                padding: 15px;
            }

            .table-responsive {
                display: none;
            }

            .certificate-card {
                display: block;
                background: #fff;
                border-radius: 16px;
                padding: 16px;
                margin-bottom: 12px;
                box-shadow: 0 6px 20px rgba(121, 75, 196, 0.06);
            }

            .certificate-card .name {
                font-weight: 700;
                color: var(--dark-purple);
                font-size: 0.95rem;
            }

            .certificate-card .detail {
                color: #777;
                font-size: 0.78rem;
                margin-top: 5px;
            }

            .certificate-card .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 7px;
                margin-top: 12px;
            }

            .certificate-card .actions .btn {
                font-size: 0.75rem;
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
            <h3>Manajemen Sertifikat</h3>
            <p>Kelola sertifikat kelulusan peserta.</p>
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

                <div class="admin-info">
                    <?= esc(session()->get('nama')); ?>
                    <small>Administrator</small>
                </div>
            </div>

        </div>
    </div>

    <!-- ALERT -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-circle-check me-2"></i>
            <?= esc(session()->getFlashdata('success')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-circle-exclamation me-2"></i>
            <?= esc(session()->getFlashdata('error')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- FILTER -->
    <div class="filter-card">

        <div class="row g-3 align-items-end">

            <div class="col-md-5">
                <label class="form-label">Cari Peserta</label>
                <input type="text"
                       id="searchPeserta"
                       class="form-control"
                       placeholder="Nama atau email peserta...">
            </div>

            <div class="col-md-3">
                <label class="form-label">Status Sertifikat</label>
                <select id="filterSertifikat" class="form-select">
                    <option value="">Semua</option>
                    <option value="sudah">Sudah Terbit</option>
                    <option value="belum">Belum Terbit</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Status Kelulusan</label>
                <select id="filterLulus" class="form-select">
                    <option value="">Semua</option>
                    <option value="lulus">Lulus</option>
                    <option value="belum">Belum Lulus</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="button"
                        class="btn btn-primary-custom w-100"
                        onclick="resetFilter()">
                    <i class="fas fa-rotate-left me-1"></i>
                    Reset
                </button>
            </div>

        </div>
    </div>

    <!-- PESERTA LULUS -->
    <div class="page-card mb-4">

        <div class="p-4 pb-3">
            <h5 class="section-title">
                <i class="fas fa-graduation-cap me-2"></i>
                Peserta Lulus
            </h5>

            <small class="text-muted">
                Peserta yang dinyatakan lulus dan dapat diterbitkan sertifikatnya.
            </small>
        </div>

        <div class="table-responsive px-4 pb-4">
            <table class="table table-hover align-middle" id="tablePesertaLulus">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peserta</th>
                        <th>Email</th>
                        <th>Kelas</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Sertifikat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php if (!empty($pesertaLulus)) : ?>

                    <?php $no = 1; ?>

                    <?php foreach ($pesertaLulus as $p) : ?>

                        <?php
                            $sudahTerbit = false;
                            $sertifikatId = null;
                            $fileSertifikat = null;

                            foreach ($sertifikat as $s) {
                                if (
    isset($s['id_user'], $s['id_kelas']) &&
    $s['id_user'] == $p['id_user'] &&
    $s['id_kelas'] == $p['id_kelas']
) {
    $sudahTerbit = true;
    $sertifikatId = $s['id_sertifikat'];
    $fileSertifikat = $s['file_sertifikat'];
    break;
}
                            }
                        ?>

                        <tr class="certificate-row"
                            data-nama="<?= esc(strtolower($p['nama_peserta'] ?? '')); ?>"
                            data-email="<?= esc(strtolower($p['email'] ?? '')); ?>"
                            data-lulus="lulus"
                            data-sertifikat="<?= $sudahTerbit ? 'sudah' : 'belum'; ?>">

                            <td><?= $no++; ?></td>

                            <td>
                                <strong><?= esc($p['nama_peserta'] ?? '-'); ?></strong>
                            </td>

                            <td><?= esc($p['email'] ?? '-'); ?></td>

                            <td><?= esc($p['nama_kelas'] ?? '-'); ?></td>

                            <td>
                                <strong><?= esc($p['nilai'] ?? '-'); ?></strong>
                            </td>

                            <td>
                                <span class="badge badge-lulus rounded-pill px-3 py-2">
                                    LULUS
                                </span>
                            </td>

                            <td>
                                <?php if ($sudahTerbit) : ?>

                                    <span class="badge badge-sertifikat rounded-pill px-3 py-2">
                                        <i class="fas fa-check me-1"></i>
                                        Sudah Terbit
                                    </span>

                                <?php else : ?>

                                    <span class="badge badge-belum-sertifikat rounded-pill px-3 py-2">
                                        Belum Terbit
                                    </span>

                                <?php endif; ?>
                            </td>

                            <td class="text-center">

                                <?php if ($sudahTerbit) : ?>

                                    <a href="<?= base_url('admin/sertifikat/edit/' . $sertifikatId); ?>"
   class="btn btn-warning btn-sm"
   title="Edit Sertifikat">
    <i class="fas fa-edit"></i>
</a>

                                    <a href="<?= base_url('admin/sertifikat/download-file/' . $sertifikatId); ?>"
                                        class="btn btn-success btn-sm"
                                        title="Download Sertifikat">
                                        <i class="fas fa-download"></i>
                                    </a>

                                    <?php if (!empty($fileSertifikat)) : ?>

                                        <a href="<?= base_url('admin/sertifikat/download/' . $sertifikatId); ?>"
                                        target="_blank"
                                        class="btn btn-outline-primary btn-sm"
                                         title="Lihat Sertifikat">
                                        <i class="fas fa-eye"></i>
                                        </a>

                                    <?php endif; ?>

                                <?php else : ?>

                                    <a href="<?= base_url('admin/sertifikat/upload?id_user=' . $p['id_user'] . '&id_kelas=' . $p['id_kelas']); ?>"
                                       class="btn btn-primary-custom btn-sm">
                                        <i class="fas fa-certificate me-1"></i>
                                        Terbitkan
                                    </a>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else : ?>

                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-user-graduate d-block"></i>
                                <div>Belum ada peserta yang dinyatakan lulus.</div>
                            </div>
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>
        </div>

        <!-- MOBILE LIST -->
        <div class="px-3 pb-3">

            <?php if (!empty($pesertaLulus)) : ?>

                <?php foreach ($pesertaLulus as $p) : ?>

                    <?php
                        $sudahTerbit = false;
                        $sertifikatId = null;

                        foreach ($sertifikat as $s) {
                            if (
    isset($s['id_user'], $s['id_kelas']) &&
    $s['id_user'] == $p['id_user'] &&
    $s['id_kelas'] == $p['id_kelas']
) {
    $sudahTerbit = true;
    $sertifikatId = $s['id_sertifikat'];
    break;
}
                        }
                    ?>

                    <div class="certificate-card certificate-mobile"
                         data-nama="<?= esc(strtolower($p['nama_peserta'] ?? '')); ?>"
                         data-email="<?= esc(strtolower($p['email'] ?? '')); ?>"
                         data-lulus="lulus"
                         data-sertifikat="<?= $sudahTerbit ? 'sudah' : 'belum'; ?>">

                        <div class="name">
                            <?= esc($p['nama_peserta'] ?? '-'); ?>
                        </div>

                        <div class="detail">
                            <i class="fas fa-envelope me-1"></i>
                            <?= esc($p['email'] ?? '-'); ?>
                        </div>

                        <div class="detail">
                            <i class="fas fa-book me-1"></i>
                            <?= esc($p['nama_kelas'] ?? '-'); ?>
                        </div>

                        <div class="detail">
                            <i class="fas fa-star me-1"></i>
                            Nilai: <strong><?= esc($p['nilai'] ?? '-'); ?></strong>
                        </div>

                        <div class="mt-2">

                            <span class="badge badge-lulus rounded-pill px-3 py-2">
                                LULUS
                            </span>

                            <?php if ($sudahTerbit) : ?>

                                <span class="badge badge-sertifikat rounded-pill px-3 py-2">
                                    Sertifikat Terbit
                                </span>

                            <?php else : ?>

                                <span class="badge badge-belum-sertifikat rounded-pill px-3 py-2">
                                    Belum Terbit
                                </span>

                            <?php endif; ?>

                        </div>

                                <div class="actions">

    <?php if ($sudahTerbit) : ?>

        <!-- Edit -->
        <a href="<?= base_url('admin/sertifikat/edit/' . $sertifikatId); ?>"
           class="btn btn-warning btn-sm"
           title="Edit Sertifikat">
            <i class="fas fa-edit"></i>
            Edit
        </a>

        <!-- Lihat -->
        <a href="<?= base_url('admin/sertifikat/download/' . $sertifikatId); ?>"
           target="_blank"
           class="btn btn-outline-primary btn-sm"
           title="Lihat Sertifikat">
            <i class="fas fa-eye"></i>
            Lihat
        </a>

        <!-- Download -->
        <a href="<?= base_url('admin/sertifikat/download-file/' . $sertifikatId); ?>"
           class="btn btn-success btn-sm"
           title="Download Sertifikat">
            <i class="fas fa-download"></i>
            Download
        </a>

    <?php else : ?>

                                <a href="<?= base_url('admin/sertifikat/upload?id_user=' . $p['id_user'] . '&id_kelas=' . $p['id_kelas']); ?>"
   class="btn btn-primary-custom btn-sm">
    <i class="fas fa-certificate me-1"></i>
    Terbitkan
</a>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

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

    // Filter
    const searchInput = document.getElementById('searchPeserta');
    const filterSertifikat = document.getElementById('filterSertifikat');
    const filterLulus = document.getElementById('filterLulus');

    function applyFilter() {

        const search = (searchInput.value || '').toLowerCase().trim();
        const sertifikat = filterSertifikat.value;
        const lulus = filterLulus.value;

        document.querySelectorAll('.certificate-row, .certificate-mobile')
            .forEach(function (item) {

                const nama = item.dataset.nama || '';
                const email = item.dataset.email || '';
                const statusSertifikat = item.dataset.sertifikat || '';
                const statusLulus = item.dataset.lulus || '';

                const cocokSearch =
                    nama.includes(search) ||
                    email.includes(search);

                const cocokSertifikat =
                    !sertifikat ||
                    statusSertifikat === sertifikat;

                const cocokLulus =
                    !lulus ||
                    statusLulus === lulus;

                item.style.display =
                    cocokSearch &&
                    cocokSertifikat &&
                    cocokLulus
                        ? ''
                        : 'none';
            });
    }

    function resetFilter() {
        searchInput.value = '';
        filterSertifikat.value = '';
        filterLulus.value = '';
        applyFilter();
    }

    searchInput.addEventListener('input', applyFilter);
    filterSertifikat.addEventListener('change', applyFilter);
    filterLulus.addEventListener('change', applyFilter);
</script>

</body>
</html>