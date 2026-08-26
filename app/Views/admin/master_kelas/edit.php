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

        .admin-info {
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 0.98rem;
        }

        .admin-info small {
            display: block;
            color: #8c83a5;
            font-size: 0.78rem;
            font-weight: normal;
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

        /* --- Grid Class Card Styling --- */
        .class-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(121, 75, 196, 0.08);
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.04);
            transition: all 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(121, 75, 196, 0.12);
            border-color: rgba(121, 75, 196, 0.3);
        }

        .class-card-img-wrapper {
            position: relative;
            height: 160px;
            overflow: hidden;
        }

        .class-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .class-card:hover .class-card-img-wrapper img {
            transform: scale(1.05);
        }

        .class-badge-overlay {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            gap: 6px;
        }

        .class-status-overlay {
            position: absolute;
            top: 12px;
            right: 12px;
        }

        .class-card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
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
                        <?= esc(session()->get('nama')); ?>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

<body class="bg-light">

    <div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Edit Data Kelas</h4>
                    
                    <form action="<?= base_url('admin/master-kelas/update/' . $kelas['id_kelas']); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <!-- Nama Kelas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Kelas</label>
                            <input type="text" name="nama_kelas" class="form-control" value="<?= esc($kelas['nama_kelas'] ?? ''); ?>" required>
                        </div>

                        <!-- Mentor Pengampu -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mentor Pengampu</label>
                            <select name="id_mentor" class="form-select" required>
                                <option value="">-- Pilih Mentor Pengajar --</option>
                                <?php if (!empty($mentor)) : ?>
                                    <?php foreach ($mentor as $m) : ?>
                                        <option value="<?= $m['id_mentor']; ?>" <?= (isset($kelas['id_mentor']) && $kelas['id_mentor'] == $m['id_mentor']) ? 'selected' : ''; ?>>
                                            <?= esc($m['nama_mentor']); ?> <?= !empty($m['keahlian']) ? '- ' . esc($m['keahlian']) : ''; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Kategori Kelas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Kelas</label>
                            <input type="text" name="kategori" class="form-control" value="<?= esc($kelas['kategori'] ?? ''); ?>" required>
                        </div>

                        <!-- Tipe Kelas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipe Kelas</label>
                            <?php 
                                $tipeAktif = $kelas['jenis_kelas'] ?? ($kelas['tipe_kelas'] ?? ''); 
                            ?>
                            <select name="tipe_kelas" class="form-select" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Online" <?= ($tipeAktif == 'Online') ? 'selected' : ''; ?>>Online</option>
                                <option value="Offline" <?= ($tipeAktif == 'Offline') ? 'selected' : ''; ?>>Offline</option>
                                <option value="Hybrid" <?= ($tipeAktif == 'Hybrid') ? 'selected' : ''; ?>>Hybrid</option>
                            </select>
                        </div>

                        <!-- Input Jumlah Pertemuan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Pertemuan</label>
                            <input type="number" name="jumlah_pertemuan" class="form-control" min="1" value="<?= esc($kelas['jumlah_pertemuan'] ?? ''); ?>" required>
                        </div>

                        <!-- Input Harga Reguler & Privat -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Harga Kelas Reguler (Rp)</label>
                                <input type="number" name="harga_reguler" value="<?= $kelas['harga_reguler']; ?>" class="form-control" required>
                                <div class="form-text">Harga untuk kelas kelompok/reguler.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Harga Kelas Privat (Rp)</label>
                                <input type="number" name="harga_privat" value="<?= $kelas['harga_privat']; ?>" class="form-control" required>
                                <div class="form-text">Harga untuk kelas eksklusif 1-on-1.</div>
                            </div>
                        </div>

                        <!-- Kapasitas Peserta -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kapasitas Peserta</label>
                            <input type="number" name="kapasitas" class="form-control" value="<?= esc($kelas['kapasitas'] ?? ''); ?>" min="1" required>
                        </div>

                        <!-- Tanggal Pelaksanaan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal Pelaksanaan</label>
                            <input type="date" name="tanggal_mulai_kelas" class="form-control" value="<?= esc($kelas['tanggal_mulai_kelas'] ?? ''); ?>" required>
                        </div>

                        <!-- Ringkasan (Ditambahkan) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ringkasan Singkat</label>
                            <textarea name="ringkasan" class="form-control" rows="2" placeholder="Tuliskan ringkasan singkat tentang kelas..." required><?= esc($kelas['ringkasan'] ?? ''); ?></textarea>
                        </div>

                        <!-- Deskripsi Lengkap (Ditambahkan) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi Lengkap</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tuliskan deskripsi lengkap kelas..." required><?= esc($kelas['deskripsi'] ?? ''); ?></textarea>
                        </div>

                        <!-- Banner / Foto Kelas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Banner / Foto Kelas</label>
                            <?php if (!empty($kelas['thumbnail'])) : ?>
                                <div class="mb-2">
                                    <img src="<?= base_url('uploads/kelas/' . $kelas['thumbnail']); ?>" alt="Banner Kelas" class="img-thumbnail rounded-3" style="max-height: 120px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <div class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah banner.</div>
                        </div>

                        <!-- Status Kelas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status Kelas</label>
                            <select name="status" class="form-select" required>
                                <option value="aktif" <?= (isset($kelas['status']) && strtolower($kelas['status']) == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                                <option value="nonaktif" <?= (isset($kelas['status']) && strtolower($kelas['status']) == 'nonaktif') ? 'selected' : ''; ?>>Non Aktif</option>
                                <option value="draft" <?= (isset($kelas['status']) && strtolower($kelas['status']) == 'draft') ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-between pt-2">
                            <a href="<?= base_url('admin/master-kelas'); ?>" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        // Script Tanggal Dinamis Bahasa Indonesia
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        document.getElementById('current-date').innerText = today.toLocaleDateString('id-ID', options);
    </script>

</body>
</html>