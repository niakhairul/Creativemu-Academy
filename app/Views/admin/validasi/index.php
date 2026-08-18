<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
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

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            overflow-x: hidden;
            margin: 0;
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f7f5fd; }
        ::-webkit-scrollbar-thumb { background: #b293f0; border-radius: 10px; }

        /* --- Sidebar Styling --- */
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
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
            max-width: 170px;
            height: auto;
            filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
        }

        #sidebar .nav { padding: 20px 14px; }
        #sidebar .nav-item { margin-bottom: 6px; }
        
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
            transition: transform 0.3s ease;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
            transform: translateX(6px);
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        #sidebar .nav-link.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #ff6b6b !important;
        }

        /* --- Main Content Area --- */
        #main-content {
            margin-left: 275px;
            padding: 35px;
            transition: all 0.4s ease;
            animation: mainFadeIn 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* --- Top Navbar --- */
        .top-navbar {
            background: #ffffff;
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
            letter-spacing: -0.5px;
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
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.2);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 0.98rem;
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.78rem;
        }

        /* --- Content Cards --- */
        .content-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            margin-bottom: 30px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .card-title-custom {
            font-weight: 800;
            color: var(--dark-purple);
            margin-bottom: 0;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title-custom i {
            color: var(--primary-purple);
        }

        /* --- Modal Customization --- */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 50px rgba(30, 15, 51, 0.2);
        }

        .modal-header {
            background-color: var(--light-purple);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            padding: 20px 25px;
            border-bottom: 1px solid rgba(121, 75, 196, 0.08);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            background-color: #fcfbfe;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            padding: 15px 25px;
            border-top: 1px solid rgba(121, 75, 196, 0.08);
        }

        /* --- Form Styling --- */
        .form-label {
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.88rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1.5px solid #e2d9f3;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: #fcfbfe;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 4px rgba(121, 75, 196, 0.1);
            background-color: #ffffff;
        }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.3);
            transition: all 0.3s ease;
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(121, 75, 196, 0.4);
            color: #ffffff;
        }

        /* --- Validation Status & Action Styling --- */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 12px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-pending {
            background: #fff6df;
            color: #b77900;
        }

        .badge-validated {
            background: #e9f8ef;
            color: #16803c;
        }

        .badge-rejected {
            background: #fdebec;
            color: #c62828;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            flex-wrap: wrap;
        }

        .btn-action {
            border: 0;
            border-radius: 10px;
            padding: 8px 13px;
            font-size: 0.78rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.25s ease;
        }

        .btn-approve {
            background: #e9f8ef;
            color: #16803c;
        }

        .btn-approve:hover {
            background: #16a34a;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(22,163,74,.22);
        }

        .btn-reject {
            background: #fdebec;
            color: #c62828;
        }

        .btn-reject:hover {
            background: #dc3545;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(220,53,69,.22);
        }

        .btn-view-reason {
            background: #f3effb;
            color: #7044b7;
            border: 0;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .btn-view-reason:hover {
            background: #794bc4;
            color: #fff;
        }

        .reject-info {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 15px;
            background: #fff6f6;
            border: 1px solid #f5d7d7;
            border-radius: 14px;
        }

        .reject-icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            border-radius: 12px;
            background: #fdebec;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .reject-info small {
            display: block;
            color: #958ca4;
            font-size: .73rem;
        }

        .reject-info strong {
            display: block;
            color: var(--dark-purple);
            font-size: .9rem;
            margin-top: 2px;
        }

        .validation-empty {
            text-align: center;
            padding: 45px 20px !important;
            color: #8c83a5;
        }

        .validation-empty i {
            font-size: 2.5rem;
            color: #b293f0;
            margin-bottom: 12px;
        }

        /* --- Table Styling --- */
        .table-custom {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table-custom th {
            background-color: var(--light-purple);
            color: var(--dark-purple);
            font-weight: 700;
            padding: 15px;
            border: none;
        }

        .table-custom td {
            padding: 15px;
            border-bottom: 1px solid #f0edf6;
            color: #4a4259;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-purple);
            transform: scale(1.005);
        }

        @keyframes mainFadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo-creativemu.png'); ?>" alt="Creativemu Academy" class="img-fluid">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">
                    <i class="fas fa-book"></i> <span>Master Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/mentor'); ?>" class="nav-link">
                    <i class="fas fa-chalkboard-user"></i> <span>Mentor</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">
                    <i class="fas fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link active">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link">
                    <i class="fas fa-award"></i> <span>Sertifikat</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
                    <i class="fas fa-file-lines"></i> <span>Laporan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link">
                    <i class="fas fa-gear"></i> <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                    <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- === MAIN CONTENT === -->
    <div id="main-content">
        
        <!-- === TOP NAVBAR === -->
        <div class="top-navbar">
            <div class="dash-header">
                <h3>Validasi Pendaftaran</h3>
                <p>Verifikasi berkas dan persetujuan pendaftaran peserta pelatihan baru</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" id="current-date" style="font-size: 0.82rem; font-weight: 600; color: #794bc4 !important;">
                    Memuat tanggal...
                </div>
                <div class="admin-profile">
                    <img src="https://ui-avatars.com/api/?name=Super+Admin&background=794bc4&color=fff&size=128" alt="Admin Photo">
                    <div class="admin-info">
                        <h6>Super Admin</h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

    <!-- Main Content Area -->
    <div class="main-content">

        <!-- Flash Notifikasi Flashdata -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('warning')) : ?>
            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= session()->getFlashdata('warning'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tabel Antrean Validasi -->
        <div class="content-card">
            <div class="card-header-custom">
                <span><i class="fa-solid fa-clock-rotate-left me-2"></i>Antrean Persetujuan Pendaftaran</span>
            </div>

            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peserta</th>
                            <th>Pilihan Kelas</th>
                            <th>Tanggal Daftar</th>
                            <th>Bukti / Berkas</th>
                            <th>Status</th>
                            <th class="text-center">Aksi Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        /*
                         * View ini mendukung data dari controller melalui:
                         * $pendaftaran / $dataPendaftaran / $pendaftaranList.
                         * Jika belum ada data dari controller, halaman tidak akan
                         * menampilkan data palsu/hard-code.
                         */
                        $rows = $pendaftaran ?? ($dataPendaftaran ?? ($pendaftaranList ?? []));
                        ?>

                        <?php if (!empty($rows)) : ?>

                            <?php foreach ($rows as $index => $row) : ?>

                                <?php
                                $id = $row['id_pendaftaran']
                                    ?? $row['id']
                                    ?? $row['id_daftar']
                                    ?? '';

                                $nama = $row['nama']
                                    ?? $row['nama_peserta']
                                    ?? $row['nama_siswa']
                                    ?? 'Peserta';

                                $email = $row['email']
                                    ?? $row['email_peserta']
                                    ?? '-';

                                $kelas = $row['nama_kelas']
                                    ?? $row['kelas']
                                    ?? $row['pilihan_kelas']
                                    ?? '-';

                                $tanggal = $row['tanggal_daftar']
                                    ?? $row['created_at']
                                    ?? '-';

                                $bukti = $row['bukti_pembayaran']
                                    ?? $row['bukti_berkas']
                                    ?? $row['bukti']
                                    ?? '';

                                $status = strtolower(trim($row['status'] ?? 'Menunggu'));

                                $alasan = $row['alasan_penolakan']
                                    ?? $row['alasan']
                                    ?? '';

                                $initials = strtoupper(
                                    substr($nama, 0, 1) .
                                    (strpos($nama, ' ') !== false
                                        ? substr($nama, strpos($nama, ' ') + 1, 1)
                                        : '')
                                );
                                ?>

                                <tr>
                                    <td><?= $index + 1; ?></td>

                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="participant-avatar"
                                                 style="width:40px;height:40px;border-radius:12px;background:#f4f0fc;color:#794bc4;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                                <?= esc($initials); ?>
                                            </div>

                                            <div>
                                                <strong class="d-block">
                                                    <?= esc($nama); ?>
                                                </strong>
                                                <small class="text-muted">
                                                    <?= esc($email); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <strong><?= esc($kelas); ?></strong>
                                    </td>

                                    <td><?= esc($tanggal); ?></td>

                                    <td>
                                        <?php if ($bukti) : ?>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalBukti<?= esc($id); ?>">
                                                <i class="fa-solid fa-file-image me-1"></i>
                                                Lihat Berkas
                                            </button>
                                        <?php else : ?>
                                            <span class="text-muted small">
                                                Tidak ada berkas
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if (in_array($status, ['menunggu', 'pending', 'belum divalidasi'])) : ?>

                                            <span class="badge-status badge-pending">
                                                <i class="fa-solid fa-hourglass-half"></i>
                                                Menunggu
                                            </span>

                                        <?php elseif (in_array($status, ['diterima', 'sudah divalidasi', 'sudah divalidasi'])) : ?>

                                            <span class="badge-status badge-validated">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Sudah Divalidasi
                                            </span>

                                        <?php elseif ($status === 'ditolak') : ?>

                                            <span class="badge-status badge-rejected">
                                                <i class="fa-solid fa-circle-xmark"></i>
                                                Ditolak
                                            </span>

                                        <?php else : ?>

                                            <span class="badge-status badge-pending">
                                                <?= esc($row['status'] ?? 'Menunggu'); ?>
                                            </span>

                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">

                                        <?php if (in_array($status, ['menunggu', 'pending', 'belum divalidasi'])) : ?>

                                            <div class="action-buttons">

                                                <button
                                                    type="button"
                                                    class="btn-action btn-approve"
                                                    onclick="confirmApprove(
                                                        <?= (int) $id; ?>,
                                                        <?= htmlspecialchars(json_encode($nama), ENT_QUOTES, 'UTF-8'); ?>
                                                    )">
                                                    <i class="fa-solid fa-check"></i>
                                                    Setujui
                                                </button>

                                                <button
                                                    type="button"
                                                    class="btn-action btn-reject"
                                                    data-id="<?= (int) $id; ?>"
                                                    data-nama="<?= esc($nama); ?>"
                                                    onclick="openRejectModal(this)">
                                                    <i class="fa-solid fa-xmark"></i>
                                                    Tolak
                                                </button>

                                            </div>

                                        <?php elseif ($status === 'ditolak') : ?>

                                            <?php if ($alasan) : ?>
                                                <button
                                                    type="button"
                                                    class="btn-view-reason"
                                                    onclick="showRejectReason(
                                                        <?= htmlspecialchars(json_encode($alasan), ENT_QUOTES, 'UTF-8'); ?>
                                                    )">
                                                    <i class="fa-solid fa-eye me-1"></i>
                                                    Lihat Alasan
                                                </button>
                                            <?php else : ?>
                                                <span class="text-muted small">
                                                    Tidak ada alasan
                                                </span>
                                            <?php endif; ?>

                                        <?php elseif (in_array($status, ['diterima', 'sudah divalidasi', 'sudah divalidasi'])) : ?>

                                            <span class="text-success fw-semibold small">
                                                <i class="fa-solid fa-circle-check me-1"></i>
                                                Selesai
                                            </span>

                                        <?php endif; ?>

                                    </td>
                                </tr>

                                <?php if ($bukti) : ?>
                                    <div class="modal fade"
                                         id="modalBukti<?= esc($id); ?>"
                                         tabindex="-1"
                                         aria-hidden="true">

                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">
                                                        <i class="fa-solid fa-image me-2"></i>
                                                        Bukti Pembayaran / Berkas
                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                    </button>
                                                </div>

                                                <div class="modal-body text-center p-4">
                                                    <img
                                                        src="<?= base_url('uploads/' . $bukti); ?>"
                                                        class="img-fluid rounded-3 border shadow-sm"
                                                        alt="Bukti Berkas">
                                                </div>

                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Tutup
                                                    </button>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                <?php endif; ?>

                            <?php endforeach; ?>

                        <?php else : ?>

                            <tr>
                                <td colspan="7" class="validation-empty">
                                    <i class="fa-solid fa-circle-check d-block"></i>
                                    <strong class="d-block mb-1">
                                        Tidak ada pendaftaran yang menunggu validasi
                                    </strong>
                                    <small>
                                        Semua pendaftaran yang masuk sudah diproses.
                                    </small>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- =====================================================
         MODAL ALASAN PENOLAKAN
    ====================================================== -->
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form id="formTolak" method="POST">

                    <?= csrf_field(); ?>

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-circle-xmark text-danger me-2"></i>
                            Tolak Pendaftaran
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="reject-info">
                            <div class="reject-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>

                            <div>
                                <small>Peserta yang akan ditolak</small>
                                <strong id="namaPesertaTolak">-</strong>
                            </div>
                        </div>

                        <div class="mt-4">

                            <label for="alasan_penolakan" class="form-label">
                                Alasan Penolakan
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                name="alasan_penolakan"
                                id="alasan_penolakan"
                                class="form-control"
                                rows="5"
                                minlength="10"
                                required
                                placeholder="Tuliskan alasan penolakan pendaftaran..."></textarea>

                            <small class="text-muted d-block mt-2">
                                Minimal 10 karakter. Alasan akan disimpan sebagai catatan validasi.
                            </small>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light px-4"
                            data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger px-4">
                            <i class="fa-solid fa-xmark me-1"></i>
                            Tolak Pendaftaran
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        /* =========================
           TANGGAL
        ========================= */
        document.addEventListener('DOMContentLoaded', function () {

            const dateElement = document.getElementById('current-date');

            if (dateElement) {
                const today = new Date();

                dateElement.innerHTML =
                    '<i class="fa-regular fa-calendar me-1"></i> ' +
                    today.toLocaleDateString('id-ID', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
            }

        });


        /* =========================
           SETUJUI PENDAFTARAN
        ========================= */
        function confirmApprove(id, nama) {

            Swal.fire({
                title: 'Setujui Pendaftaran?',
                html:
                    'Pendaftaran <strong>' +
                    nama +
                    '</strong> akan divalidasi dan dimasukkan ke Data Peserta.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText:
                    '<i class="fa-solid fa-check me-1"></i> Ya, Setujui',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {

                    window.location.href =
                        "<?= base_url('admin/validasi/setujui'); ?>/" + id;

                }

            });

        }


        /* =========================
           MODAL TOLAK
        ========================= */
        function openRejectModal(button) {

            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');

            document.getElementById('namaPesertaTolak').textContent = nama;
            document.getElementById('alasan_penolakan').value = '';

            document.getElementById('formTolak').action =
                "<?= base_url('admin/validasi/tolak'); ?>/" + id;

            const modalElement =
                document.getElementById('modalTolak');

            const modal =
                bootstrap.Modal.getOrCreateInstance(modalElement);

            modal.show();

        }


        /* =========================
           VALIDASI FORM TOLAK
        ========================= */
        document.getElementById('formTolak').addEventListener('submit', function (event) {

            const alasan =
                document.getElementById('alasan_penolakan').value.trim();

            if (alasan.length < 10) {

                event.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Alasan Belum Memadai',
                    text: 'Alasan penolakan minimal 10 karakter.',
                    confirmButtonColor: '#794bc4'
                });

                return;
            }

            event.preventDefault();

            Swal.fire({
                title: 'Tolak Pendaftaran?',
                text: 'Pastikan alasan penolakan sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText:
                    '<i class="fa-solid fa-xmark me-1"></i> Ya, Tolak',
                cancelButtonText: 'Kembali',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {
                    this.submit();
                }

            });

        });


        /* =========================
           LIHAT ALASAN PENOLAKAN
        ========================= */
        function showRejectReason(alasan) {

            Swal.fire({
                icon: 'error',
                title: 'Alasan Penolakan',
                html:
                    '<div style="text-align:left;background:#fff6f6;border:1px solid #f5d7d7;padding:15px;border-radius:12px;">' +
                    $('<div>').text(alasan).html() +
                    '</div>',
                confirmButtonColor: '#794bc4',
                confirmButtonText: 'Tutup'
            });

        }


        /* =========================
           FLASHDATA SUCCESS
        ========================= */
        <?php if (session()->getFlashdata('success')) : ?>

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: <?= json_encode(session()->getFlashdata('success')); ?>,
                confirmButtonColor: '#794bc4',
                timer: 2800,
                timerProgressBar: true,
                showConfirmButton: false
            });

        <?php endif; ?>


        /* =========================
           FLASHDATA WARNING
        ========================= */
        <?php if (session()->getFlashdata('warning')) : ?>

            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: <?= json_encode(session()->getFlashdata('warning')); ?>,
                confirmButtonColor: '#794bc4'
            });

        <?php endif; ?>

    </script>

</body>
</html>