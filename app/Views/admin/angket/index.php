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
            --light-purple: #f4f0fc;
        }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f7f5fd; 
            margin: 0; 
        }

        /* Sidebar Styling */
        #sidebar { 
            width: 275px; 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            background-color: var(--sidebar-bg); 
            color: var(--sidebar-text); 
            z-index: 1000; 
            overflow-y: auto; 
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.05);
        }
        .sidebar-header { 
            padding: 25px 20px; 
            background: rgba(0, 0, 0, 0.15); 
            text-align: center; 
        }
        .nav-link { 
            color: var(--sidebar-text); 
            padding: 12px 18px; 
            display: flex; 
            align-items: center; 
            border-radius: 12px; 
            margin: 0 14px 6px; 
            transition: all 0.3s ease; 
        }
        .nav-link:hover, .nav-link.active { 
            background: var(--sidebar-active-gradient); 
            color: #ffffff; 
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
        }

        /* Main Content Styling */
        #main-content { 
            margin-left: 275px; 
            padding: 35px; 
        }
        .top-navbar { 
            background: #ffffff; 
            padding: 22px 30px; 
            border-radius: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); 
        }
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .admin-profile img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-purple);
        }
        .admin-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: #22133c;
        }
        .admin-info small {
            color: #8c82a5;
            font-size: 0.75rem;
        }
        .btn-purple { 
            background: var(--sidebar-active-gradient); 
            color: #ffffff; 
            border: none; 
            border-radius: 12px; 
            padding: 10px 20px; 
            font-weight: 600; 
            transition: all 0.3s ease;
        }
        .btn-purple:hover { 
            color: #ffffff; 
            opacity: 0.9; 
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
        }

        /* Card & Table Modern Styling */
        .card-table {
            background: #ffffff;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: none;
        }
        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }
        .table>thead>tr>th {
            background-color: #faf8ff;
            color: #5931a0;
            font-weight: 600;
            border-bottom: 2px solid #f0ecfa;
            padding: 14px 16px;
        }
        .table tbody td {
            padding: 16px;
            color: #4a4a4a;
            border-bottom: 1px solid #f4f0fc;
        }
        .table-hover tbody tr:hover {
            background-color: #faf8ff;
        }
        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 8px;
        }
        .alert {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo-creativemu.png'); ?>" alt="Logo" class="img-fluid" style="max-width: 150px;">
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie me-3"></i> Dashboard</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book me-3"></i> Master Kelas</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user me-3"></i> Mentor</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link"><i class="fas fa-users me-3"></i> Data Peserta</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/validasi'); ?>" class="nav-link"><i class="fas fa-clipboard-check me-3"></i> Validasi</a></li>
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link active"><i class="fas fa-award me-3"></i> Angket</a>
                <ul class="nav flex-column ms-3 mt-1">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/hasil_angket'); ?>" class="nav-link py-2" style="font-size: 0.9rem;">
                            <i class="fas fa-poll-h me-2"></i> Hasil Angket
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item"><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-certificate me-3"></i> Sertifikat</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/laporan'); ?>" class="nav-link"><i class="fas fa-file-lines me-3"></i> Laporan</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear me-3"></i> Pengaturan</a></li>
            <li class="nav-item mt-4"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket me-3"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Top Navbar with Profile & Date -->
        <div class="top-navbar">
            <div>
                <h3 class="fw-bold text-dark mb-1">Daftar Konfigurasi Angket</h3>
                <p class="text-muted mb-0">Kelola formulir penilaian evaluasi mentor dan fasilitas pelatihan.</p>
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

        <!-- Notifikasi Pesan Sukses -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Action Bar with Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-muted small">Menampilkan data konfigurasi angket aktif</span>
            </div>
            <a href="<?= base_url('admin/angket/tambah_angket'); ?>" class="btn btn-purple">
                <i class="fas fa-plus me-2"></i> Buat Angket Baru
            </a>
        </div>

        <!-- Tabel Dibungkus Card Modern -->
        <div class="card-table">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Judul Angket</th>
                            <th width="20%">Kelas</th>
                            <th width="20%">Tanggal Dibuat</th>
                            <th width="10%">Status</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($angket as $item): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-semibold text-dark"><?= esc($item['judul_angket'] ?? 'Angket Evaluasi'); ?></td>
                            <td><?= esc($item['nama_kelas']); ?></td>
                            <td><span class="text-muted"><?= esc($item['created_at']); ?></span></td>
                            <td>
                                <span class="badge bg-<?= (($item['status'] ?? 'Aktif') == 'Aktif') ? 'success' : 'secondary'; ?> bg-opacity-10 text-<?= (($item['status'] ?? 'Aktif') == 'Aktif') ? 'success' : 'secondary'; ?>">
                                    <?= esc($item['status'] ?? 'Aktif'); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/angket/detail/' . ($item['id'] ?? $item['id_angket_pertanyaan'])); ?>" class="btn btn-info btn-sm text-white mb-1 px-2" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('admin/angket/edit/' . ($item['id'] ?? $item['id_angket_pertanyaan'])); ?>" class="btn btn-warning btn-sm text-white mb-1 px-2" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="<?= base_url('admin/angket/delete/' . ($item['id'] ?? $item['id_angket_pertanyaan'])); ?>" class="btn btn-danger btn-sm mb-1 px-2" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script to display current formatted date
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        const dateElement = document.getElementById('current-date');
        if(dateElement) {
            dateElement.innerText = today.toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>