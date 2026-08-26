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
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link active">
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
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
             <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link">
                    <i class="fas fa-award"></i> <span>Angket</span>
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
                <h3>Master Kelas</h3>
                <p>Kelola data pelatihan, tambah kelas baru, dan atur jadwal dengan mudah.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" id="current-date" style="font-size: 0.82rem; font-weight: 600; color: #794bc4 !important;">
                    Memuat tanggal...
                </div>
                <div class="admin-profile">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                    <div class="admin-info">
                        <h6><?= esc(session()->get('nama')); ?></h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

   <!-- Notifikasi Flashdata (Jika ada) -->
<?php if (session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- === SECTION: DAFTAR KELAS (BENTUK GRID/KOTAK) === -->
<div class="content-card">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="card-title-custom">
            <i class="fas fa-th-large me-2"></i> Katalog Kelas Akademi
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge px-3 py-2 rounded-pill fw-semibold" style="background-color: var(--light-purple); color: var(--primary-purple) !important;">
                Total: <?= isset($kelas) ? count($kelas) : 0; ?> Kelas
            </span>
            <!-- Tombol Trigger Modal Tambah Kelas -->
            <button type="button" class="btn btn-purple rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                <i class="fas fa-plus me-2"></i> Tambah Kelas
            </button>
        </div>
    </div>

    <!-- === MODAL TAMBAH KELAS === -->
    <div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-plus-circle me-2 text-purple"></i> Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="<?= base_url('admin/master-kelas/tambah'); ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Kelas</label>
                                <input type="text" name="nama_kelas" class="form-control" required>
                            </div>
                            
                            <!-- Pilih Mentor -->
                            <div class="col-md-6">
                                <label for="id_mentor" class="form-label">Pilih Mentor</label>
                                <select name="id_mentor" id="id_mentor" class="form-control" required>
                                    <option value="">-- Pilih Mentor --</option>
                                    <?php if (!empty($mentor)) : ?>
                                        <?php foreach ($mentor as $m) : ?>
                                            <option value="<?= $m['id_mentor']; ?>"><?= $m['nama_mentor']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Kategori & Jumlah Pertemuan -->
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="kategori" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Jml Pertemuan</label>
                                <input type="number" name="jumlah_pertemuan" class="form-control" min="1" required>
                            </div>

                            <!-- Harga Reguler & Privat -->
                            <div class="col-md-6">
                                <label class="form-label">Harga Reguler (Rp)</label>
                                <input type="number" name="harga_reguler" class="form-control" min="0" placeholder="Contoh: 150000" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga Privat (Rp)</label>
                                <input type="number" name="harga_privat" class="form-control" min="0" placeholder="Contoh: 500000" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Kapasitas Peserta</label>
                                <input type="number" name="kapasitas" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai_kelas" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status Kelas</label>
                                <select name="status" class="form-control" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipe Kelas</label>
                                <select name="tipe_kelas" class="form-control" required>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                    <option value="Hybrid">Hybrid</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ringkasan</label>
                                <textarea name="ringkasan" class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Deskripsi Lengkap</label>
                                <textarea name="deskripsi" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Thumbnail / Foto</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-purple rounded-pill px-4">Simpan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Grid Container -->
    <div class="row g-4">
        <?php if (!empty($kelas) && is_array($kelas)): ?>
            <?php foreach ($kelas as $row): ?>
                <div class="col-md-6 col-xl-4">
                    <div class="class-card shadow-sm border-0 rounded-4 overflow-hidden bg-white">
                        <div class="class-card-img-wrapper position-relative" style="height: 180px; overflow: hidden;">
                            <?php 
                                $fotoKelas = !empty($row['thumbnail']) ? $row['thumbnail'] : (!empty($row['foto']) ? $row['foto'] : 'default.jpg');
                            ?>
                            <img src="<?= base_url('uploads/kelas/' . $fotoKelas); ?>" alt="Foto Kelas" class="w-100 h-100 object-fit-cover">
                            
                            <!-- Badge Kategori & Tipe -->
                            <div class="class-badge-overlay position-absolute top-0 start-0 p-2 d-flex gap-1">
                                <span class="badge px-2 py-1 shadow-sm" style="background: rgba(121, 75, 196, 0.85); backdrop-filter: blur(4px); font-size: 0.72rem;"><?= esc($row['kategori'] ?? 'Umum'); ?></span>
                                <span class="badge bg-dark bg-opacity-75 px-2 py-1 shadow-sm" style="backdrop-filter: blur(4px); font-size: 0.72rem;"><?= esc($row['tipe_kelas'] ?? $row['jenis_kelas'] ?? 'Online'); ?></span>
                            </div>

                            <!-- Badge Status -->
                            <div class="class-status-overlay position-absolute top-0 end-0 p-2">
                                <?php 
                                    $statusKelas = strtolower($row['status'] ?? 'draft');
                                    if ($statusKelas == 'aktif'): 
                                ?>
                                    <span class="badge bg-success shadow-sm" style="font-size: 0.72rem;">Aktif</span>
                                <?php elseif ($statusKelas == 'nonaktif'): ?>
                                    <span class="badge bg-secondary shadow-sm" style="font-size: 0.72rem;">Nonaktif</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark shadow-sm" style="font-size: 0.72rem;">Draft</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="class-card-body p-3">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0 text-truncate" style="color: var(--dark-purple); font-size: 1.05rem;" title="<?= esc($row['nama_kelas']); ?>"><?= esc($row['nama_kelas']); ?></h5>
                                </div>
                                <p class="text-muted small mb-3 text-truncate"><?= esc($row['ringkasan'] ?? 'Tidak ada ringkasan.'); ?></p>
                            </div>

                            <div class="border-top pt-3 mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.82rem;">
                                    <span class="text-muted text-truncate" style="max-width: 55%;" title="<?= esc($row['nama_mentor'] ?? 'Belum ada mentor'); ?>">
                                        <i class="fas fa-chalkboard-user me-1 text-primary"></i> <?= esc($row['nama_mentor'] ?? 'Belum ditentukan'); ?>
                                    </span>
                                    <span class="fw-bold text-dark"><i class="fas fa-rotate text-purple me-1"></i> <?= esc($row['jumlah_pertemuan']); ?>x Pertemuan</span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 0.82rem;">
                                    <span class="text-muted"><i class="fas fa-calendar-days me-1 text-muted"></i> <?= esc($row['tanggal_mulai_kelas'] ?? '-'); ?></span>
                                    
                                    <!-- Harga Format Rupiah Lengkap Menggunakan $row -->
                                    <span class="fw-bold text-success fs-6">
                                        <div class="pricing-info">
                                            <p class="mb-1"><strong>Reg:</strong> Rp <?= number_format($row['harga_reguler'] ?? 0, 0, ',', '.'); ?></p>
                                            <p class="mb-0"><strong>Priv:</strong> Rp <?= number_format($row['harga_privat'] ?? 0, 0, ',', '.'); ?></p>
                                        </div>
                                    </span>
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="d-flex flex-column gap-2">
                                    <button type="button" class="btn btn-sm btn-info text-white rounded-pill py-1 w-100" style="font-size: 0.82rem;" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDetailKelas<?= $row['id_kelas']; ?>">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail Kelas
                                    </button>
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('admin/master-kelas/edit/' . $row['id_kelas']); ?>" class="btn btn-sm btn-outline-primary rounded-pill w-50 py-1" style="font-size: 0.82rem;">
                                            <i class="fas fa-pen-to-square me-1"></i> Edit
                                        </a>
                                        <a href="<?= base_url('admin/master-kelas/delete/' . $row['id_kelas']); ?>" class="btn btn-sm btn-outline-danger rounded-pill w-50 py-1" style="font-size: 0.82rem;" onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                            <i class="fas fa-trash-can me-1"></i> Hapus
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- === MODAL DETAIL KELAS PER ITEM === -->
                <div class="modal fade" id="modalDetailKelas<?= $row['id_kelas']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header bg-light">
                                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-info-circle me-2 text-purple"></i> Detail Kelas: <?= esc($row['nama_kelas']); ?></h5>
                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-5 text-center">
                                        <img src="<?= base_url('uploads/kelas/' . $fotoKelas); ?>" alt="Banner" class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover" style="max-height: 220px;">
                                        <div class="mt-3">
                                            <span class="badge px-3 py-2 
                                                <?
                                                    if ($statusKelas == 'aktif') echo 'bg-success';
                                                    elseif ($statusKelas == 'nonaktif') echo 'bg-secondary';
                                                    else echo 'bg-warning text-dark';
                                                ?>">
                                                <?= ucfirst(esc($row['status'] ?? 'Draft')); ?>
                                            </span>
                                            <span class="badge px-3 py-2" style="background-color: #794bc4; color: #fff;"><?= esc($row['tipe_kelas'] ?? $row['jenis_kelas'] ?? 'Online'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <table class="table table-borderless table-sm mb-0">
                                            <tr>
                                                <td class="fw-semibold text-muted" width="35%">Nama Kelas</td>
                                                <td>: <?= esc($row['nama_kelas']); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Mentor Pengampu</td>
                                                <td>: <?= esc($row['nama_mentor'] ?? '-'); ?> <?= !empty($row['keahlian']) ? '(' . esc($row['keahlian']) . ')' : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Kategori</td>
                                                <td>: <?= esc($row['kategori']); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Harga Reguler</td>
                                                <td>: Rp <?= number_format($row['harga_reguler'] ?? 0, 0, ',', '.'); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Harga Privat</td>
                                                <td>: Rp <?= number_format($row['harga_privat'] ?? 0, 0, ',', '.'); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Jumlah Pertemuan</td>
                                                <td>: <?= esc($row['jumlah_pertemuan']); ?>x Pertemuan</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Kapasitas Peserta</td>
                                                <td>: <?= esc($row['kapasitas']); ?> Peserta</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Tanggal Mulai</td>
                                                <td>: <?= esc($row['tanggal_mulai_kelas'] ?? '-'); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-12 border-top pt-3">
                                        <h6 class="fw-bold text-dark">Ringkasan:</h6>
                                        <p class="text-muted small"><?= esc($row['ringkasan'] ?? '-'); ?></p>
                                        
                                        <h6 class="fw-bold text-dark mt-3">Deskripsi Lengkap:</h6>
                                        <p class="text-muted small" style="white-space: pre-line;"><?= esc($row['deskripsi'] ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- === AKHIR MODAL DETAIL === -->

            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="text-muted py-4">Belum ada data kelas yang tersedia. Silakan tambahkan kelas baru.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Script Tanggal Dinamis Bahasa Indonesia
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date();
    const dateEl = document.getElementById('current-date');
    if (dateEl) {
        dateEl.innerText = today.toLocaleDateString('id-ID', options);
    }
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>