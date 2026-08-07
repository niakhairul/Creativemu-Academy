<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <!-- Google Fonts & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-pastel: #f8f6fc;
            --purple-main: #8c7ae6;
            --purple-dark: #6c5ce7;
            --purple-light: #e0dcf8;
            --text-dark: #2d3436;
            --text-muted: #636e72;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-pastel);
            color: var(--text-dark);
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #ffffff;
            border-right: 1px solid #e9e5f5;
            padding: 24px 16px;
            box-shadow: 4px 0 20px rgba(140, 122, 230, 0.05);
        }

        .brand-logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--purple-dark);
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 32px 40px;
        }

        .content-card {
            background: #ffffff;
            border: 1px solid #e9e5f5;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        }

        .card-header-custom {
            font-weight: 700;
            color: var(--purple-dark);
            font-size: 1.15rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Table Styling */
        .table-custom {
            vertical-align: middle;
        }

        .table-custom thead th {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            font-weight: 600;
            border: none;
            padding: 12px 16px;
        }

        .table-custom tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #e9e5f5;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-pending {
            background-color: #fff4e5;
            color: #b76e00;
        }

        .btn-approve {
            background-color: #2ed573;
            color: #fff;
            border-radius: 8px;
            border: none;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-approve:hover {
            background-color: #26af5f;
            color: #fff;
        }

        .btn-reject {
            background-color: #ff4757;
            color: #fff;
            border-radius: 8px;
            border: none;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-reject:hover {
            background-color: #d63031;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <div class="sidebar">
        <div class="brand-logo">
            <i class="fa-solid fa-graduation-cap fs-4"></i>
            <span>Creativemu</span>
        </div>
        <nav>
            <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link-custom <?= (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link-custom <?= (uri_string() == 'admin/master-kelas') ? 'active' : ''; ?>">
                <i class="fa-solid fa-book-bookmark"></i> Master Kelas
            </a>
            <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link-custom <?= (uri_string() == 'admin/data-peserta') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Data Peserta
            </a>
            <a href="<?= base_url('admin/validasi'); ?>" class="nav-link-custom <?= (uri_string() == 'admin/validasi') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-check"></i> Validasi Pendaftaran
            </a>
            <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link-custom <?= (uri_string() == 'admin/sertifikat') ? 'active' : ''; ?>">
                <i class="fa-solid fa-award"></i> Sertifikat
            </a>
            <a href="<?= base_url('admin/laporan'); ?>" class="nav-link-custom <?= (uri_string() == 'admin/laporan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-lines"></i> Laporan
            </a>
            <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link-custom <?= (uri_string() == 'admin/pengaturan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear"></i> Pengaturan
            </a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: var(--purple-dark);">Validasi Pendaftaran</h3>
                <p class="text-muted mb-0">Verifikasi berkas dan persetujuan pendaftaran peserta pelatihan baru.</p>
            </div>
        </div>

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
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>Rizky Pratama</strong><br>
                                <small class="text-muted">rizky@gmail.com</small>
                            </td>
                            <td>Web Dev CodeIgniter 4</td>
                            <td>07 Agt 2026</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalBukti">
                                    <i class="fa-solid fa-file-image me-1"></i> Lihat Berkas
                                </button>
                            </td>
                            <td><span class="badge-status badge-pending"><i class="fa-solid fa-hourglass-half me-1"></i> Menunggu</span></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/validasi/update/1/Diterima'); ?>" class="btn btn-approve btn-sm me-1" onclick="return confirm('Setujui pendaftaran ini?')">
                                    <i class="fa-solid fa-check me-1"></i> Setujui
                                </a>
                                <a href="<?= base_url('admin/validasi/update/1/Ditolak'); ?>" class="btn btn-reject btn-sm" onclick="return confirm('Tolak pendaftaran ini?')">
                                    <i class="fa-solid fa-xmark me-1"></i> Tolak
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Bukti Berkas -->
    <div class="modal fade" id="modalBukti" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background-color: var(--purple-light); border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold" style="color: var(--purple-dark);">
                        <i class="fa-solid fa-image me-2"></i>Bukti Pembayaran / Berkas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img src="https://via.placeholder.com/400x300?text=Bukti+Transfer+|+Berkas" class="img-fluid rounded border shadow-sm" alt="Bukti Berkas">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>