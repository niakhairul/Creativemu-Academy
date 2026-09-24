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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            font-size: 14px; /* Ukuran standar agar UI lebih rapi & compact */
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f7f5fd;
        }
        ::-webkit-scrollbar-thumb {
            background: #b293f0;
            border-radius: 10px;
        }

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

        /* Sidebar Navigation */
        #sidebar .nav {
            padding: 12px 10px;
        }

        #sidebar .nav-item {
            margin-bottom: 4px;
        }

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
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
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

        /* --- Modern Stat Cards --- */
        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: var(--primary-purple);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: var(--light-purple);
            color: var(--primary-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .stat-card small {
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #8c83a5;
            font-size: 0.7rem;
        }

        .stat-card h3 {
            font-weight: 700;
            color: var(--dark-purple);
            margin-top: 2px;
            margin-bottom: 0;
            font-size: 1.45rem;
        }

        /* --- Chart Boxes & Content Cards --- */
        .chart-box, .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }
        
        .chart-title {
            font-weight: 700;
            color: var(--dark-purple);
            margin-bottom: 15px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-purple);
        }

        /* --- Responsif Layout --- */
        @media (max-width: 992px) {
            #sidebar {
                width: 70px;
            }
            #sidebar .sidebar-header .logo-card {
                padding: 6px;
                width: 100%;
            }
            #sidebar .sidebar-header img {
                height: 30px;
            }
            #sidebar .panel-title, #sidebar span {
                display: none;
            }
            #sidebar .nav-link {
                justify-content: center;
                padding: 10px;
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
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link active">
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
                <h3>Dashboard Overview</h3>
                <p>Selamat datang kembali, kelola pelatihan Creativemu Academy dengan mudah.</p>
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

        <!-- === STAT CARDS === -->
        <div class="row g-3 mb-3">
            <div class="col-xl-4 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <small>TOTAL SISWA</small>
                        <h3><?= $total_peserta ?? 0; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <small>TOTAL INSTRUKTUR</small>
                        <h3><?= $total_mentor ?? 0; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <small>PENDING VALIDASI</small>
                        <h3 class="text-warning"><?= $pending_validasi ?? 0; ?></h3>
                    </div>
                    <div class="stat-icon" style="background: #fff8e1; color: #fbc02d;"><i class="fas fa-clock-rotate-left"></i></div>
                </div>
            </div>
        </div>

        <!-- === DIAGRAM SECTION === -->
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <div class="chart-box">
                    <div class="chart-title">
                        <i class="fas fa-chart-column text-primary"></i> Monitoring Angket Kepuasan
                    </div>
                    <canvas id="angketChart" height="110"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-box">
                    <div class="chart-title">
                        <i class="fas fa-chart-line text-primary"></i> Absensi Kehadiran Instruktur
                    </div>
                    <canvas id="absensiChart" height="110"></canvas>
                </div>
            </div>
        </div>

        <!-- === PINTASAN ADMIN === -->
        <div class="content-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-bolt text-primary me-2"></i> Pintasan Admin
                </h6>
            </div>
            <p class="text-muted small mb-3" style="font-size: 0.78rem;">Akses cepat menu pengelolaan Creativemu Academy.</p>

            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <a href="<?= base_url('admin/validasi'); ?>" class="p-2 bg-light rounded-3 text-decoration-none d-block text-center border border-light">
                        <i class="fas fa-clipboard-check text-warning fs-4 mb-1"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Validasi</h6>
                        <small class="text-muted" style="font-size: 0.68rem;">Konfirmasi Peserta</small>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="<?= base_url('admin/master-kelas'); ?>" class="p-2 bg-light rounded-3 text-decoration-none d-block text-center border border-light">
                        <i class="fas fa-book text-primary fs-4 mb-1"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Master Kelas</h6>
                        <small class="text-muted" style="font-size: 0.68rem;">Kelola Pelatihan</small>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="<?= base_url('admin/mentor'); ?>" class="p-2 bg-light rounded-3 text-decoration-none d-block text-center border border-light">
                        <i class="fas fa-chalkboard-user text-success fs-4 mb-1"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Instruktur</h6>
                        <small class="text-muted" style="font-size: 0.68rem;">Daftar Instruktur</small>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="<?= base_url('admin/laporan'); ?>" class="p-2 bg-light rounded-3 text-decoration-none d-block text-center border border-light">
                        <i class="fas fa-file-lines text-danger fs-4 mb-1"></i>
                        <h6 class="fw-bold text-dark mb-0 small">Laporan</h6>
                        <small class="text-muted" style="font-size: 0.68rem;">Unduh Rekap</small>
                    </a>
                </div>
            </div>
        </div>

        <!-- === ANTREAN VALIDASI PENDAFTARAN === -->
        <div class="content-card mb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark">Antrean Validasi Pendaftaran</h6>
                <a href="<?= base_url('admin/validasi'); ?>" class="text-decoration-none small fw-semibold text-primary" style="font-size: 0.78rem;">Kelola Semua →</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0 align-middle">
                    <thead>
                        <tr class="text-secondary small">
                            <th>NAMA PESERTA</th>
                            <th>PILIHAN KELAS</th>
                            <th>TANGGAL</th>
                            <th class="text-center">AKSI CEPAT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($pendaftaran_pending)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">
                                    <i class="fas fa-check-circle fs-4 text-success mb-1 d-block"></i>
                                    <p class="mb-0 small">Tidak ada antrean pendaftaran baru yang tertunda.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach(($pendaftaran_pending ?? []) as $row): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark small"><?= esc($row['nama']); ?></div>
                                        <small class="text-muted" style="font-size: 0.72rem;"><?= esc($row['email']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.7rem;">
                                            <?= esc($row['nama_kelas']); ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted" style="font-size: 0.75rem;"><?= date('d M Y', strtotime($row['created_at'] ?? 'now')); ?></small></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/setuju'); ?>" class="btn btn-sm btn-success rounded-pill px-2 py-0 shadow-sm me-1" title="Setujui">
                                            <i class="fas fa-check" style="font-size: 0.7rem;"></i>
                                        </a>
                                        <a href="<?= base_url('admin/validasi/update/' . $row['id_pendaftaran'] . '/tolak'); ?>" class="btn btn-sm btn-danger rounded-pill px-2 py-0 shadow-sm" title="Tolak">
                                            <i class="fas fa-times" style="font-size: 0.7rem;"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Format Tanggal Bahasa Indonesia
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        document.getElementById('current-date').innerText = today.toLocaleDateString('id-ID', options);

        // Chart Angket
        const ctxAngket = document.getElementById('angketChart').getContext('2d');
        new Chart(ctxAngket, {
            type: 'bar',
            data: {
                labels: ['Sangat Puas', 'Puas', 'Cukup', 'Kurang'],
                datasets: [{
                    label: 'Jumlah Responden',
                    data: [0, 0, 0, 0],
                    backgroundColor: '#e9ecef',
                    borderRadius: 6,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: {
                    y: { beginAtZero: true, max: 10, grid: { color: '#f0edf6' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Chart Absensi
        const ctxAbsensi = document.getElementById('absensiChart').getContext('2d');
        new Chart(ctxAbsensi, {
            type: 'line',
            data: {
                labels: <?= json_encode($absensi_labels ?? []) ?>,
                datasets: [{
                    label: 'Absensi Instruktur',
                    data: <?= json_encode($absensi_data ?? []) ?>,
                    borderColor: '#794bc4',
                    backgroundColor: 'rgba(121, 75, 196, 0.08)',
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom', labels: { font: { family: 'Poppins', size: 11 } } }, tooltip: { enabled: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f0edf6' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>
