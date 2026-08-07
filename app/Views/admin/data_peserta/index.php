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

        .badge-verified {
            background-color: #e3f9e5;
            color: #1f9254;
        }

        .badge-pending {
            background-color: #fff4e5;
            color: #b76e00;
        }

        .btn-pastel-purple {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            border-radius: 8px;
            font-weight: 600;
            border: none;
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-pastel-purple:hover {
            background-color: var(--purple-main);
            color: #ffffff;
        }

        .form-control, .form-select {
            border: 1px solid #e9e5f5;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--purple-main);
            box-shadow: 0 0 0 0.25rem rgba(140, 122, 230, 0.2);
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
                <h3 class="fw-bold mb-1" style="color: var(--purple-dark);">Data Peserta</h3>
                <p class="text-muted mb-0">Kelola daftar seluruh siswa/peserta yang terdaftar pada platform.</p>
            </div>
        </div>

        <!-- Tabel Data Peserta -->
        <div class="content-card">
            <div class="card-header-custom">
                <span><i class="fa-solid fa-users me-2"></i>Daftar Peserta Terdaftar</span>
                <!-- Search & Filter -->
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Cari nama / email..." style="width: 220px;">
                    <select class="form-select" style="width: 160px;">
                        <option value="">Semua Status</option>
                        <option value="Validasi">Sudah Validasi</option>
                        <option value="Pending">Menunggu</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Email</th>
                            <th>Kelas Diikuti</th>
                            <th>Status Akun / Akses</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>Anisa Rahmawati</strong><br>
                                <small class="text-muted">ID: PST-001</small>
                            </td>
                            <td>anisa@gmail.com</td>
                            <td>UI/UX Design Masterclass</td>
                            <td><span class="badge-status badge-verified"><i class="fa-solid fa-check-circle me-1"></i> Ter-Validasi</span></td>
                            <td class="text-center">
                                <button class="btn btn-pastel-purple btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalDetailPeserta">
                                    <i class="fa-solid fa-eye me-1"></i> Detail
                                </button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <strong>Rizky Pratama</strong><br>
                                <small class="text-muted">ID: PST-002</small>
                            </td>
                            <td>rizky@gmail.com</td>
                            <td>Web Dev CodeIgniter 4</td>
                            <td><span class="badge-status badge-pending"><i class="fa-solid fa-clock me-1"></i> Menunggu Validasi</span></td>
                            <td class="text-center">
                                <button class="btn btn-pastel-purple btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalDetailPeserta">
                                    <i class="fa-solid fa-eye me-1"></i> Detail
                                </button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Detail Peserta -->
    <div class="modal fade" id="modalDetailPeserta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background-color: var(--purple-light); border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold" style="color: var(--purple-dark);">
                        <i class="fa-solid fa-id-card me-2"></i>Detail Informasi Peserta
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 80px; height: 80px; background-color: var(--purple-light) !important;">
                            <i class="fa-solid fa-user-graduate fs-1" style="color: var(--purple-dark);"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Anisa Rahmawati</h5>
                        <small class="text-muted">anisa@gmail.com</small>
                    </div>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">ID Peserta</span>
                            <span class="fw-semibold">PST-001</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Kelas yang Diikuti</span>
                            <span class="fw-semibold">UI/UX Design Masterclass</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Status Validasi</span>
                            <span class="badge-status badge-verified">Sudah Divalidasi</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tanggal Bergabung</span>
                            <span class="fw-semibold">12 Januari 2026</span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9e5f5;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

   
</body>
</html>