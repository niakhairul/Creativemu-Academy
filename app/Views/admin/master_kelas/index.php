<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Mencegah zoom / layar bergerak di HP/Tablet -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #1c1032;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
        }

        html, body {
            touch-action: pan-x pan-y;
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            overflow-x: hidden;
            margin: 0;
            font-size: 14px; /* Ukuran font standar agar lebih compact */
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f7f5fd; }
        ::-webkit-scrollbar-thumb { background: #b293f0; border-radius: 10px; }

        /* --- Sidebar Styling --- */
        #sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(121, 75, 196, 0.08);
            overflow-y: auto;
        }

        /* Sidebar Header & Logo Card */
        #sidebar .sidebar-header {
            padding: 20px 15px 15px 15px;
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-align: center;
        }

        #sidebar .logo-card {
            background-color: #ffffff;
            border-radius: 14px;
            padding: 10px 14px;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 85%;
        }

        #sidebar .logo-card img {
            max-width: 100%;
            height: 45px;
            object-fit: contain;
        }

        #sidebar .panel-title {
            color: #a497c6;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-top: 12px;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        /* Navigation Links */
        #sidebar .nav { padding: 12px 10px; }
        #sidebar .nav-item { margin-bottom: 4px; }
        
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 9px 14px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.85rem;
        }

        #sidebar .nav-link i {
            margin-right: 10px;
            font-size: 0.95rem;
            width: 18px;
            text-align: center;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
            font-weight: 600;
        }

        #sidebar .nav-link.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #ff6b6b !important;
        }

        /* --- Main Content Area --- */
        #main-content {
            margin-left: 240px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        /* --- Top Navbar --- */
        .top-navbar {
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
        }

        .dash-header h3 {
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 1.25rem;
            margin: 0;
        }
        
        .dash-header p {
            color: #8c83a5;
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-purple);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.88rem;
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.72rem;
        }

        /* --- Content Cards --- */
        .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .card-title-custom {
            font-weight: 700;
            color: var(--dark-purple);
            margin-bottom: 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title-custom i {
            color: var(--primary-purple);
        }

        /* --- Modal Customization --- */
        .modal-content {
            border-radius: 14px;
            border: none;
            box-shadow: 0 10px 30px rgba(30, 15, 51, 0.15);
        }

        .modal-header {
            background-color: var(--light-purple);
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(121, 75, 196, 0.08);
        }

        .modal-body { padding: 20px; }

        .modal-footer {
            background-color: #fcfbfe;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
            padding: 12px 20px;
            border-top: 1px solid rgba(121, 75, 196, 0.08);
        }

        /* --- Form Styling --- */
        .form-label {
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.8rem;
            margin-bottom: 4px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 8px 12px;
            border: 1.5px solid #e2d9f3;
            font-size: 0.85rem;
            background-color: #fcfbfe;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
            background-color: #ffffff;
        }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.25);
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            opacity: 0.95;
            color: #ffffff;
        }

        /* --- Responsive Mobile --- */
        @media (max-width: 992px) {
            #sidebar {
                width: 70px;
            }

            #sidebar .logo-card {
                padding: 6px;
                width: 100%;
            }

            #sidebar .logo-card img {
                height: 30px;
            }

            #sidebar .panel-title,
            #sidebar span {
                display: none;
            }

            #sidebar .sidebar-header {
                padding: 15px 8px;
            }

            #sidebar .nav {
                padding: 10px 6px;
            }

            #sidebar .nav-link {
                justify-content: center;
                padding: 9px;
            }

            #sidebar .nav-link i {
                margin-right: 0;
            }

            #main-content {
                margin-left: 70px;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            #main-content {
                padding: 10px;
            }

            .top-navbar {
                padding: 12px 15px;
                margin-bottom: 15px;
                gap: 10px;
                flex-direction: column;
                align-items: flex-start;
            }

            .top-navbar > .d-flex {
                width: 100%;
                justify-content: flex-end;
            }

            .dash-header h3 {
                font-size: 1.1rem;
            }

            .dash-header p {
                font-size: 0.75rem;
            }

            .content-card {
                padding: 15px;
                border-radius: 12px;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="logo-card">
                <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy">
            </div>
            <div class="panel-title">PANEL ADMIN</div>
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
                    <i class="fas fa-chalkboard-user"></i> <span>Instruktur</span>
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
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link">
                    <i class="fas fa-book-open"></i> <span>Buku Induk</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link">
                    <i class="fas fa-poll"></i> <span>Angket</span>
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
            <li class="nav-item mt-3">
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
            <div class="d-flex align-items-center gap-3">
                <div class="text-muted d-none d-md-block px-3 py-1 rounded-pill bg-light" id="current-date" style="font-size: 0.78rem; font-weight: 600; color: #794bc4 !important;">
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
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-3 py-2 px-3 small" role="alert">
                <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('pesan'); ?>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- === SECTION: DAFTAR KELAS (BENTUK GRID/KOTAK) === -->
        <div class="content-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <div class="card-title-custom">
                    <i class="fas fa-th-large me-1"></i> Katalog Kelas Akademi
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge px-2 py-1 rounded-pill fw-semibold" style="background-color: var(--light-purple); color: var(--primary-purple) !important; font-size: 0.75rem;">
                        Total: <?= isset($kelas) ? count($kelas) : 0; ?> Kelas
                    </span>
                    <!-- Tombol Trigger Modal Tambah Kelas -->
                    <button type="button" class="btn btn-purple rounded-pill px-3 py-1" data-bs-toggle="modal" data-bs-target="#modalTambahKelas">
                        <i class="fas fa-plus me-1"></i> Tambah Kelas
                    </button>
                </div>
            </div>

            <!-- === MODAL TAMBAH KELAS === -->
            <div class="modal fade" id="modalTambahKelas" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-light">
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-plus-circle me-1 text-purple"></i> Tambah Kelas Baru</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <form action="<?= base_url('admin/master-kelas/tambah'); ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <div class="modal-body p-3">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Kelas</label>
                                        <input type="text" name="nama_kelas" class="form-control" required>
                                    </div>
                                    
                                    <!-- Pilih Instruktur -->
                                    <div class="col-md-6">
                                        <label for="id_mentor" class="form-label">Pilih Instruktur</label>
                                        <select name="id_mentor" id="id_mentor" class="form-select" required>
                                            <option value="">-- Pilih Instruktur --</option>
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
                                        <select name="status" class="form-select" required>
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Tipe Kelas</label>
                                        <select name="tipe_kelas" class="form-select" required>
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
                                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-sm btn-purple rounded-pill px-3">Simpan Kelas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Grid Container -->
            <div class="row g-3">
                <?php if (!empty($kelas) && is_array($kelas)): ?>
                    <?php foreach ($kelas as $row): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="class-card shadow-sm border rounded-3 overflow-hidden bg-white">
                                <div class="class-card-img-wrapper position-relative" style="height: 150px; overflow: hidden;">
                                    <?php 
                                        $fotoKelas = !empty($row['thumbnail']) ? $row['thumbnail'] : (!empty($row['foto']) ? $row['foto'] : 'default.jpg');
                                    ?>
                                    <img src="<?= base_url('uploads/kelas/' . $fotoKelas); ?>" alt="Foto Kelas" class="w-100 h-100 object-fit-cover">
                                    
                                    <!-- Badge Kategori & Tipe -->
                                    <div class="class-badge-overlay position-absolute top-0 start-0 p-2 d-flex gap-1">
                                        <span class="badge px-2 py-1 shadow-sm" style="background: rgba(121, 75, 196, 0.85); backdrop-filter: blur(4px); font-size: 0.68rem;"><?= esc($row['kategori'] ?? 'Umum'); ?></span>
                                        <span class="badge bg-dark bg-opacity-75 px-2 py-1 shadow-sm" style="backdrop-filter: blur(4px); font-size: 0.68rem;"><?= esc($row['tipe_kelas'] ?? $row['jenis_kelas'] ?? 'Online'); ?></span>
                                    </div>

                                    <!-- Badge Status -->
                                    <div class="class-status-overlay position-absolute top-0 end-0 p-2">
                                        <?php 
                                            $statusKelas = strtolower($row['status'] ?? 'draft');
                                            if ($statusKelas == 'aktif'): 
                                        ?>
                                            <span class="badge bg-success shadow-sm" style="font-size: 0.68rem;">Aktif</span>
                                        <?php elseif ($statusKelas == 'nonaktif'): ?>
                                            <span class="badge bg-secondary shadow-sm" style="font-size: 0.68rem;">Nonaktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark shadow-sm" style="font-size: 0.68rem;">Draft</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="class-card-body p-3">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold mb-0 text-truncate" style="color: var(--dark-purple); font-size: 0.95rem;" title="<?= esc($row['nama_kelas']); ?>"><?= esc($row['nama_kelas']); ?></h6>
                                        </div>
                                        <p class="text-muted small mb-2 text-truncate" style="font-size: 0.78rem;"><?= esc($row['ringkasan'] ?? 'Tidak ada ringkasan.'); ?></p>
                                    </div>

                                    <div class="border-top pt-2 mt-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.78rem;">
                                            <span class="text-muted text-truncate" style="max-width: 55%;" title="<?= esc($row['nama_mentor'] ?? 'Belum ada instruktur'); ?>">
                                                <i class="fas fa-chalkboard-user me-1 text-primary"></i> <?= esc($row['nama_mentor'] ?? 'Belum ditentukan'); ?>
                                            </span>
                                            <span class="fw-bold text-dark"><i class="fas fa-rotate text-purple me-1"></i> <?= esc($row['jumlah_pertemuan']); ?>x Pertemuan</span>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 0.78rem;">
                                            <span class="text-muted"><i class="fas fa-calendar-days me-1 text-muted"></i> <?= esc($row['tanggal_mulai_kelas'] ?? '-'); ?></span>
                                            
                                            <!-- Harga Format Rupiah -->
                                            <div class="pricing-info text-end">
                                                <small class="d-block text-muted" style="font-size: 0.7rem;">Reg: <strong>Rp <?= number_format($row['harga_reguler'] ?? 0, 0, ',', '.'); ?></strong></small>
                                                <small class="d-block text-success fw-bold" style="font-size: 0.72rem;">Priv: Rp <?= number_format($row['harga_privat'] ?? 0, 0, ',', '.'); ?></small>
                                            </div>
                                        </div>

                                        <!-- Tombol Aksi -->
                                        <div class="d-flex flex-column gap-1">
                                            <button type="button" class="btn btn-sm btn-info text-white rounded-pill py-1 w-100" style="font-size: 0.78rem;" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalDetailKelas<?= $row['id_kelas']; ?>">
                                                <i class="fas fa-eye me-1"></i> Lihat Detail Kelas
                                            </button>
                                            <a href="<?= base_url('admin/master-kelas/jadwal/' . $row['id_kelas']); ?>" class="btn btn-sm btn-warning text-dark rounded-pill py-1 w-100" style="font-size: 0.78rem;">
                                                <i class="fas fa-calendar-alt me-1"></i> Jadwal Kelas
                                            </a>
                                            <div class="d-flex gap-1 mt-1">
                                                <a href="<?= base_url('admin/master-kelas/edit/' . $row['id_kelas']); ?>" class="btn btn-sm btn-outline-primary rounded-pill w-50 py-1" style="font-size: 0.75rem;">
                                                    <i class="fas fa-pen-to-square me-1"></i> Edit
                                                </a>
                                                <a href="<?= base_url('admin/master-kelas/delete/' . $row['id_kelas']); ?>" class="btn btn-sm btn-outline-danger rounded-pill w-50 py-1" style="font-size: 0.75rem;" onclick="return confirm('Yakin ingin menghapus kelas ini?')">
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
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-light">
                                        <h6 class="fw-bold text-dark mb-0"><i class="fas fa-info-circle me-1 text-purple"></i> Detail Kelas: <?= esc($row['nama_kelas']); ?></h6>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-5 text-center">
                                                <img src="<?= base_url('uploads/kelas/' . $fotoKelas); ?>" alt="Banner" class="img-fluid rounded-3 shadow-sm w-100 object-fit-cover" style="max-height: 180px;">
                                                <div class="mt-2 d-flex justify-content-center gap-1">
                                                    <span class="badge px-2 py-1 
                                                        <?php
                                                            if ($statusKelas == 'aktif') echo 'bg-success';
                                                            elseif ($statusKelas == 'nonaktif') echo 'bg-secondary';
                                                            else echo 'bg-warning text-dark';
                                                        ?>">
                                                        <?= ucfirst(esc($row['status'] ?? 'Draft')); ?>
                                                    </span>
                                                    <span class="badge px-2 py-1" style="background-color: #794bc4; color: #fff;"><?= esc($row['tipe_kelas'] ?? $row['jenis_kelas'] ?? 'Online'); ?></span>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <table class="table table-borderless table-sm mb-0 small">
                                                    <tr>
                                                        <td class="fw-semibold text-muted" width="38%">Nama Kelas</td>
                                                        <td>: <?= esc($row['nama_kelas']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Instruktur Pengampu</td>
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
                                            <div class="col-12 border-top pt-2">
                                                <h6 class="fw-bold text-dark mb-1 small">Ringkasan:</h6>
                                                <p class="text-muted small mb-2"><?= esc($row['ringkasan'] ?? '-'); ?></p>
                                                
                                                <h6 class="fw-bold text-dark mb-1 small">Deskripsi Lengkap:</h6>
                                                <p class="text-muted small mb-0" style="white-space: pre-line;"><?= esc($row['deskripsi'] ?? '-'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- === AKHIR MODAL DETAIL === -->

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-4">
                        <div class="text-muted small">Belum ada data kelas yang tersedia. Silakan tambahkan kelas baru.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Script Tanggal Dinamis -->
    <script>
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