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
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link active">
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
                <h3>Edit Angket</h3>
                <p>Ubah dan perbarui data pertanyaan angket yang tersedia.</p>
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

        <!-- === FORM EDIT CONTENT === -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="content-card">
                        <h4 class="card-title-custom mb-4">
                            <i class="fas fa-pen-to-square"></i> Form Edit Angket
                        </h4>
                        
                        <form action="<?= base_url('admin/angket/update/' . $id); ?>" method="post">
                            <!-- Judul Angket -->
                            <div class="mb-3">
                                <label class="form-label">Judul Angket</label>
                                <input type="text" name="judul_angket" class="form-control" value="<?= esc($angket['judul_angket'] ?? ''); ?>" required>
                            </div>

                            <!-- Pilihan Kelas -->
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <select name="id_kelas" class="form-select">
                                    <?php foreach ($kelas as $k): ?>
                                        <option value="<?= $k['id_kelas']; ?>" <?= ($angket['id_kelas'] == $k['id_kelas']) ? 'selected' : ''; ?>>
                                            <?= $k['nama_kelas']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
    <label class="form-label">Kelas & Mentor</label>
    <select name="id_kelas" class="form-select" required>
        <?php foreach ($kelas as $k) : ?>
            <option value="<?= $k['id_kelas']; ?>" <?= ($k['id_kelas'] == $angket['id_kelas']) ? 'selected' : ''; ?>>
                <?= $k['nama_kelas']; ?> 
                <?php 
                    // Mencari nama mentor berdasarkan id_mentor dari kelas tersebut
                    $namaMentor = '-';
                    foreach ($mentor as $m) {
                        if ($m['id_mentor'] == $k['id_mentor']) {
                            $namaMentor = $m['nama_mentor'];
                            break;
                        }
                    }
                ?>
                (Mentor: <?= $namaMentor; ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>

                            <!-- Pertanyaan (Textarea) -->
                            <!-- Ganti bagian input pertanyaan lama Anda dengan perulangan ini -->
<div id="container-pertanyaan">
    <?php if (!empty($semua_pertanyaan)) : ?>
        <?php foreach ($semua_pertanyaan as $row) : ?>
            <div class="card p-3 mb-3 border-light shadow-sm">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori[]" class="form-control" value="<?= esc($row['kategori']); ?>">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Pertanyaan</label>
                        <input type="text" name="pertanyaan[]" class="form-control" value="<?= esc($row['pertanyaan']); ?>" required>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

                            <!-- Kotak Saran (Opsional) -->
                            <div class="mb-3">
                                <label class="form-label">Kotak Saran</label>
                                <textarea name="saran" class="form-control" rows="2"><?= esc($angket['saran'] ?? ''); ?></textarea>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Aktif" <?= ($angket['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="Nonaktif" <?= ($angket['status'] == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="<?= base_url('admin/angket'); ?>" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                                <button type="submit" class="btn btn-purple px-4">Simpan Perubahan</button>
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