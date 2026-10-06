<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title); ?> - Creativemu Academy</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #794bc4;
            --primary-dark: #5931a0;
            --primary-light: #f4f0fc;

            --dark: #24143b;
            --text: #4d4659;
            --muted: #8d849d;

            --border: #eee9f5;
            --background: #f7f5fc;

            --success: #22a06b;
            --warning: #e99b18;
            --danger: #e05252;
            --remidi: #8b5cf6;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--text);
            font-size: 14px;
        }

        body {
            overflow-x: hidden;
        }

        /* =========================
           SCROLLBAR
        ========================= */

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1edf8;
        }

        ::-webkit-scrollbar-thumb {
            background: #b79be8;
            border-radius: 20px;
        }

        /* =========================
           MAIN CONTENT
        ========================= */

        #main-content {
            margin-left: 240px;
            padding: 24px;
            min-height: 100vh;
        }

        /* =========================
           TOP NAVBAR
        ========================= */

        .top-navbar {
            background: #ffffff;
            border: 1px solid rgba(121, 75, 196, 0.06);
            border-radius: 18px;
            padding: 18px 22px;
            margin-bottom: 22px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 8px 30px rgba(56, 32, 91, 0.06);
        }

        .dash-header h3 {
            margin: 0 0 5px 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
        }

        .dash-header p {
            margin: 0;
            color: var(--muted);
            font-size: 0.78rem;
        }

        /* =========================
           ADMIN PROFILE
        ========================= */

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-profile img {
            width: 42px;
            height: 42px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e5d9f7;
            padding: 2px;
            background: #fff;
        }

        .admin-info h6 {
            margin: 0;
            color: var(--dark);
            font-size: 0.84rem;
            font-weight: 700;
        }

        .admin-info small {
            color: var(--muted);
            font-size: 0.7rem;
        }

        .date-badge {
            background: var(--primary-light);
            color: var(--primary) !important;
            border: 1px solid #e8dcfa;
            padding: 7px 13px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        /* =========================
           MAIN CARD
        ========================= */

        .content-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(121, 75, 196, 0.06);
            padding: 24px;
            box-shadow: 0 8px 30px rgba(56, 32, 91, 0.05);
        }

        /* =========================
           CARD HEADER
        ========================= */

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .title-wrapper {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .title-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, #f0e7fc, #e6d8fa);
            color: var(--primary);
            font-size: 17px;
        }

        .card-title-custom {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
        }

        .card-description {
            margin: 3px 0 0 0;
            color: var(--muted);
            font-size: 0.73rem;
        }

        /* =========================
           ALERT
        ========================= */

        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.8rem;
            padding: 12px 15px;
        }

        /* =========================
           TABLE WRAPPER
        ========================= */

        .table-container {
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .table-responsive {
            margin: 0;
        }

        .table-custom {
            margin: 0;
            vertical-align: middle;
            font-size: 0.78rem;
        }

        /* =========================
           TABLE HEADER
        ========================= */

        .table-custom thead th {
            background: #faf8fd;
            color: #6c6080;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 15px 12px;
            border-bottom: 1px solid var(--border);
            border-top: none;
            white-space: nowrap;
        }

        /* =========================
           TABLE BODY
        ========================= */

        .table-custom tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #f2eef7;
            color: var(--text);
        }

        .table-custom tbody tr {
            transition: 0.2s ease;
        }

        .table-custom tbody tr:hover {
            background: #fcfaff;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        /* =========================
           PARTICIPANT
        ========================= */

        .participant-name {
            font-weight: 700;
            color: var(--dark);
            font-size: 0.78rem;
        }

        .participant-email {
            margin-top: 3px;
            font-size: 0.68rem;
            color: var(--muted);
        }

        .nis-text {
            font-weight: 600;
            color: #716781;
        }

        /* =========================
           CLASS BADGE
        ========================= */

        .class-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f6f3fb;
            color: #67557e;
            border: 1px solid #e9e1f4;
            padding: 5px 9px;
            border-radius: 7px;
            font-size: 0.68rem;
            font-weight: 600;
        }

        /* =========================
           NILAI
        ========================= */

        .score-input {
            width: 68px !important;
            margin: auto;
            border: 1px solid #e2d8f1;
            border-radius: 8px;
            font-weight: 700;
            color: var(--dark);
            padding: 7px 5px;
            font-size: 0.78rem;
        }

        .score-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
        }

        /* =========================
           ANGKET BADGE
        ========================= */

        .status-angket {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 0.67rem;
            font-weight: 600;
        }

        .status-angket.sudah {
            background: #e9f8f0;
            color: var(--success);
        }

        .status-angket.belum {
            background: #fff0f0;
            color: var(--danger);
        }

        /* =========================
           STATUS KELULUSAN
        ========================= */

        .status-select {
            min-width: 135px;
            border-radius: 9px;
            padding: 7px 10px;
            font-size: 0.72rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid #e5dced;
            background-color: #ffffff;
        }

        .status-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
        }

        .status-select.status-menunggu {
            color: #b87900;
            background-color: #fff8e8;
            border-color: #f3d28a;
        }

        .status-select.status-lulus {
            color: #168154;
            background-color: #ecfaf3;
            border-color: #b8e7cf;
        }

        .status-select.status-tidak-lulus {
            color: #c43e3e;
            background-color: #fff0f0;
            border-color: #efbcbc;
        }

        .status-select.status-remidi {
            color: #7040c0;
            background-color: #f4edff;
            border-color: #d7c2fa;
        }

        /* =========================
           KIRIM BUTTON
        ========================= */

        .btn-purple {
            border: none;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #ffffff;
            border-radius: 9px;
            padding: 7px 13px;
            font-size: 0.7rem;
            font-weight: 600;
            box-shadow: 0 5px 12px rgba(121, 75, 196, 0.2);
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 7px 16px rgba(121, 75, 196, 0.28);
        }

        .btn-purple:active {
            transform: translateY(0);
        }

        /* =========================
           EMPTY DATA
        ========================= */

        .empty-data {
            padding: 50px 20px !important;
            text-align: center;
        }

        .empty-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            background: #f4f0fb;
            color: #9a7ccc;
            border-radius: 50%;
            font-size: 20px;
        }

        .empty-data strong {
            display: block;
            color: var(--dark);
            font-size: 0.85rem;
            margin-bottom: 4px;
        }

        .empty-data span {
            color: var(--muted);
            font-size: 0.72rem;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1200px) {
            /* Let admin-responsive.css handle main-content margins */
            .table-custom {
                min-width: 1050px;
            }
        }

        @media (max-width: 768px) {
            /* Let admin-responsive.css handle main-content margins & padding */
            .top-navbar {
                padding: 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }
            .content-card {
                padding: 15px;
                border-radius: 14px;
            }
            .card-header-custom {
                align-items: flex-start;
            }
            .date-badge {
                display: none;
            }
        }
    </style>
    <!-- Admin Responsive Styles & Scripts -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>

<body>

    <!-- SIDEBAR -->
    <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

    <!-- MAIN CONTENT -->
    <div id="main-content">

        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="dash-header">
                <h3>Manajemen Kegiatan Belajar Mengajar</h3>
                <p>Kelola nilai ujian dan status kelulusan peserta.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div id="current-date" class="date-badge d-none d-md-block">Memuat tanggal...</div>
                <div class="admin-profile">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                    <div class="admin-info">
                        <h6><?= esc(session()->get('nama') ?? 'Administrator'); ?></h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT CARD -->
        <div class="content-card">

            <!-- FLASH MESSAGE -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-circle-check me-2"></i>
                    <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-circle-exclamation me-2"></i>
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- CARD TITLE -->
            <div class="card-header-custom">
                <div class="title-wrapper">
                    <div class="title-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h5 class="card-title-custom">Daftar Penilaian & Kelulusan Peserta</h5>
                        <p class="card-description">Tinjau nilai ujian dan tentukan status akhir peserta.</p>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-custom align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="10%">NIS</th>
                                <th width="18%">Peserta</th>
                                <th width="18%">Email</th>
                                <th width="14%">Kelas</th>
                                <th class="text-center" width="10%">Nilai</th>
                                <th class="text-center" width="10%">Angket</th>
                                <th width="14%">Status</th>
                                <th class="text-center" width="9%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($list_kbm)): ?>
                                <?php foreach ($list_kbm as $index => $row): ?>

                                    <form
                                        id="form-update-<?= $row['id_nilai_ujian']; ?>"
                                        action="<?= base_url('admin/manajemen-kbm/update-status/' . $row['id_nilai_ujian']); ?>"
                                        method="POST">
                                        <?= csrf_field(); ?>
                                    </form>

                                    <tr>
                                        <!-- NO -->
                                        <td class="text-center">
                                            <span class="fw-semibold"><?= $index + 1; ?></span>
                                        </td>

                                        <!-- NIS -->
                                        <td>
                                            <span class="nis-text"><?= esc($row['nis'] ?? '-'); ?></span>
                                        </td>

                                        <!-- PESERTA -->
                                        <td>
                                            <div class="participant-name"><?= esc($row['nama_peserta'] ?? '-'); ?></div>
                                        </td>

                                        <!-- EMAIL -->
                                        <td>
                                            <div class="participant-email">
                                                <i class="fas fa-envelope me-1"></i>
                                                <?= esc($row['email_peserta'] ?? '-'); ?>
                                            </div>
                                        </td>

                                        <!-- KELAS -->
                                        <td>
                                            <span class="class-badge">
                                                <i class="fas fa-users"></i>
                                                <?= esc($row['kelas'] ?? '-'); ?>
                                            </span>
                                        </td>

                                        <!-- NILAI -->
                                        <td class="text-center">
                                            <input
                                                type="number"
                                                form="form-update-<?= $row['id_nilai_ujian']; ?>"
                                                name="nilai"
                                                value="<?= esc($row['nilai'] ?? 0); ?>"
                                                class="form-control score-input text-center"
                                                min="0"
                                                max="100">
                                        </td>

                                        <td class="text-center">
                                            <?php if (isset($row['jumlah_angket']) && $row['jumlah_angket'] > 0): ?>
                                                <span class="status-angket sudah">
                                                    <i class="fas fa-check"></i> Sudah Mengisi
                                                </span>
                                            <?php else: ?>
                                                <span class="status-angket belum">
                                                    <i class="fas fa-xmark"></i> Belum Mengisi
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- STATUS KELULUSAN -->
                                        <td>
                                            <?php
                                            $status = strtolower($row['status_kelulusan'] ?? 'menunggu');
                                            $statusClass = 'status-menunggu';

                                            if ($status === 'lulus') {
                                                $statusClass = 'status-lulus';
                                            } elseif ($status === 'tidak lulus') {
                                                $statusClass = 'status-tidak-lulus';
                                            } elseif ($status === 'remidi') {
                                                $statusClass = 'status-remidi';
                                            }
                                            ?>

                                            <select
                                                form="form-update-<?= $row['id_nilai_ujian']; ?>"
                                                name="status_kelulusan"
                                                class="form-select status-select <?= $statusClass; ?>"
                                                onchange="changeStatusColor(this)">

                                                <option value="menunggu" <?= ($status === 'menunggu') ? 'selected' : ''; ?>>
                                                    Menunggu
                                                </option>
                                                <option value="lulus" <?= ($status === 'lulus') ? 'selected' : ''; ?>>
                                                    Lulus
                                                </option>
                                                <option value="tidak lulus" <?= ($status === 'tidak lulus') ? 'selected' : ''; ?>>
                                                    Tidak Lulus
                                                </option>
                                                <option value="remidi" <?= ($status === 'remidi') ? 'selected' : ''; ?>>
                                                    Remidi
                                                </option>
                                            </select>
                                        </td>

                                        <!-- AKSI -->
                                        <td class="text-center">
                                            <button
                                                type="submit"
                                                form="form-update-<?= $row['id_nilai_ujian']; ?>"
                                                class="btn btn-purple">
                                                <i class="fas fa-paper-plane me-1"></i> Kirim
                                            </button>
                                        </td>
                                    </tr>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="empty-data">
                                        <div class="empty-icon">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <strong>Belum Ada Data Peserta</strong>
                                        <span>Data peserta dengan nilai ujian akan muncul di sini.</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* TANGGAL */
        document.addEventListener("DOMContentLoaded", function () {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date();
            const dateString = today.toLocaleDateString('id-ID', options);
            const dateElement = document.getElementById('current-date');
            if (dateElement) {
                dateElement.textContent = dateString;
            }
        });

        /* WARNA STATUS KELULUSAN */
        function changeStatusColor(select) {
            // Hapus semua class warna
            select.classList.remove(
                'status-menunggu',
                'status-lulus',
                'status-tidak-lulus',
                'status-remidi'
            );

            // Tambahkan class sesuai status (menggunakan toLowerCase agar aman)
            switch (select.value.toLowerCase()) {
                case 'lulus':
                    select.classList.add('status-lulus');
                    break;
                case 'tidak lulus':
                    select.classList.add('status-tidak-lulus');
                    break;
                case 'remidi':
                    select.classList.add('status-remidi');
                    break;
                default:
                    select.classList.add('status-menunggu');
                    break;
            }
        }

        /* SET WARNA SAAT HALAMAN DIBUKA */
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.status-select').forEach(function (select) {
                changeStatusColor(select);
            });
        });
    </script>
</body>
</html>