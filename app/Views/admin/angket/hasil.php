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
        }
        body { font-family: 'Poppins', sans-serif; background-color: #f7f5fd; }
        #sidebar { width: 275px; height: 100vh; position: fixed; top: 0; left: 0; background-color: var(--sidebar-bg); color: var(--sidebar-text); z-index: 1000; overflow-y: auto; }
        .sidebar-header { padding: 25px 20px; background: rgba(0, 0, 0, 0.25); text-align: center; }
        .nav-link { color: var(--sidebar-text); padding: 12px 18px; display: flex; align-items: center; border-radius: 12px; margin: 0 14px 6px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: var(--sidebar-active-gradient); color: #ffffff; }
        
        #main-content { margin-left: 275px; padding: 35px; }
        .content-card { background: #ffffff; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .text-purple { color: var(--primary-purple); }
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
            
            <!-- Menu Angket dengan Sub-menu -->
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link"><i class="fas fa-award me-3"></i> Angket</a>
                <ul class="nav flex-column ms-4">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/hasil_angket'); ?>" class="nav-link active bg-dark bg-opacity-25">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Hasil Angket Siswa</h3>
                <p class="text-muted mb-0">Daftar rekapitulasi penilaian peserta.</p>
            </div>
            <a href="<?= base_url('admin/angket'); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark m-0"><i class="fas fa-poll-h me-2 text-purple"></i> Data Jawaban</h5>
                <span class="badge bg-purple text-white px-3 py-2">Total Respon: <?= !empty($hasil) ? count($hasil) : 0; ?></span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Judul Angket</th>
                            <th>Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($hasil) && is_array($hasil)) : ?>
                            <?php $no = 1; foreach ($hasil as $h): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><div class="fw-bold"><?= esc($h['nama_siswa'] ?? 'Tanpa Nama'); ?></div></td>
                                <td><span class="badge bg-light text-purple border"><?= esc($h['judul_angket'] ?? '-'); ?></span></td>
                                <td><div class="bg-light p-2 rounded small"><?= esc($h['jawaban'] ?? '-'); ?></div></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">Belum ada data tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>