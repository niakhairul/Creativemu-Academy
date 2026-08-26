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
                <a href="<?= base_url('admin/mentor'); ?>" class="nav-link active">
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

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div class="fw-bold" style="font-size: 1.05rem; color: #1e1e2d;">
                    <i class="fas fa-list-check me-2"></i> Daftar Mentor Terdaftar
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge px-3 py-2 rounded-pill fw-semibold" style="background-color: var(--light-purple); color: var(--primary-purple) !important;">
                        Total: <?= isset($total_aktif) ? $total_aktif : 0; ?> Mentor Aktif
                    </span>
                    <button type="button" class="btn btn-purple rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalTambahMentor">
                        <i class="fas fa-plus me-2"></i> Tambah Mentor
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama & Kontak</th>
                            <th>Keahlian</th>
                            <th>Pengalaman</th>
                            <th>Status</th>
                            <th>CV</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mentor) && is_array($mentor)): ?>
                            <?php $no = 1; foreach ($mentor as $m): ?>
                                <tr>
                                    <td class="fw-semibold"><?= $no++; ?></td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--dark-purple);"><?= esc($m['nama_mentor']); ?></div>
                                        <small class="text-muted"><i class="fas fa-envelope me-1"></i> <?= esc($m['email']); ?></small><br>
                                        <small class="text-muted"><i class="fas fa-phone me-1"></i> <?= esc($m['telepon']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge px-2 py-1" style="background: var(--light-purple); color: var(--primary-purple);">
                                            <?= esc($m['keahlian']); ?>
                                        </span>
                                    </td>
                                    <td><?= esc($m['pengalaman']); ?> Tahun</td>
                                    <td>
                                        <?php if($m['status'] == 'Aktif'): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($m['cv'])): ?>
                                            <a href="<?= base_url('uploads/cv/' . $m['cv']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                <i class="fas fa-file-pdf text-danger me-1"></i> Lihat CV
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Tidak ada CV</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/mentor/edit/' . ($m['id_mentor'] ?? $m['id'])); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1" title="Edit">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>
                                        <a href="<?= base_url('admin/mentor/delete/' . ($m['id_mentor'] ?? $m['id'])); ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Hapus" onclick="return confirm('Yakin ingin menghapus mentor ini?')">
                                            <i class="fas fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data mentor yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- === MODAL TAMBAH MENTOR === -->
    <div class="modal fade" id="modalTambahMentor" tabindex="-1" aria-labelledby="modalTambahMentorLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="modalTambahMentorLabel" style="color: var(--dark-purple);">
                        <i class="fas fa-circle-plus me-2"></i> Form Tambah Mentor Baru
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="<?= base_url('admin/mentor/simpan'); ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap & Gelar</label>
                                <input type="text" name="nama_mentor" class="form-control" placeholder="Contoh: Dr. Budi Santoso, M.Kom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Aktif</label>
                                <input type="email" name="email" class="form-control" placeholder="budi@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. Telepon / WhatsApp</label>
                                <input type="text" name="telepon" class="form-control" placeholder="081234567890" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Keahlian Utama</label>
                                <input type="text" name="keahlian" class="form-control" placeholder="Contoh: Fullstack / Cyber Security" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Pengalaman Kerja (Tahun)</label>
                                <input type="number" name="pengalaman" class="form-control" placeholder="Contoh: 5" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status Keaktifan</label>
                                <select name="status" class="form-select" required>
                                    <option value="Aktif">Aktif Mengajar</option>
                                    <option value="Non-Aktif">Cuti / Non-Aktif</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio / Biografi Singkat</label>
                                <textarea name="bio" class="form-control" rows="3" placeholder="Masukkan bio singkat mentor..."><?= isset($mentor['bio']) ? esc($mentor['bio']) : ''; ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Unggah Dokumen CV (Format PDF / DOCX)</label>
                                <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Maksimal ukuran file menyesuaikan konfigurasi server.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light px-4 rounded-pill text-muted fw-semibold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-purple px-4 rounded-pill">
                            <i class="fas fa-save me-2"></i> Simpan Mentor
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script Tanggal Dinamis Bahasa Indonesia
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        document.getElementById('current-date').innerText = today.toLocaleDateString('id-ID', options);
    </script>
</body>
</html>