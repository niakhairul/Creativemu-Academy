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
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f7f5fd;
        }
        ::-webkit-scrollbar-thumb {
            background: #b293f0;
            border-radius: 10px;
        }

        /* --- Sidebar Styling with Glass/Glow Touch --- */
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
            transition: transform 0.3s ease;
        }

        #sidebar .sidebar-header img:hover {
            transform: scale(1.05);
        }

        #sidebar .nav {
            padding: 20px 14px;
        }

        #sidebar .nav-item {
            margin-bottom: 6px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 18px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
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

        #sidebar .nav-link:hover i {
            transform: scale(1.2);
            color: #b293f0;
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
            transition: transform 0.3s ease;
        }

        .top-navbar:hover {
            box-shadow: 0 15px 35px rgba(121, 75, 196, 0.08);
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
            transition: transform 0.3s ease;
        }

        .admin-profile img:hover {
            transform: rotate(10deg) scale(1.05);
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

        /* --- Modern Stat Cards with Floating & Glow Animation --- */
        .stat-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            border: 1px solid rgba(121, 75, 196, 0.05);
        }
        
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-purple);
            transition: width 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(121, 75, 196, 0.12);
        }

        .stat-card:hover::after {
            width: 8px;
        }

        .stat-icon {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: var(--light-purple);
            color: var(--primary-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.4s ease;
        }

        .stat-card:hover .stat-icon {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            transform: scale(1.1) rotate(6deg);
        }

        .stat-card small {
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #8c83a5;
            font-size: 0.75rem;
        }

        .stat-card h3 {
            font-weight: 800;
            color: var(--dark-purple);
            margin-top: 6px;
            margin-bottom: 0;
            font-size: 1.9rem;
        }

        /* --- Chart Boxes with Glass/Card Styling --- */
        .chart-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            margin-bottom: 30px;
            border: 1px solid rgba(121, 75, 196, 0.05);
            transition: transform 0.3s ease;
        }

        .chart-box:hover {
            box-shadow: 0 15px 40px rgba(121, 75, 196, 0.08);
        }
        
        .chart-title {
            font-weight: 800;
            color: var(--dark-purple);
            margin-bottom: 25px;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-title i {
            color: var(--primary-purple);
        }

        /* --- Extra Section (Table & Security Status) --- */
        .extra-section {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-purple);
            transform: scale(1.01);
        }

        /* --- Animations --- */
        @keyframes mainFadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 992px) {
            #sidebar { width: 80px; }
            #sidebar .sidebar-header img, #sidebar span { display: none; }
            #main-content { margin-left: 80px; padding: 20px; }
        }
    </style>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <!-- Logo Creativemu Academy -->
            <img src="<?= base_url('assets/img/logo-creativemu.png'); ?>" alt="Creativemu Academy" class="img-fluid">
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
                <h3>Dashboard Overview</h3>
                <p>Selamat datang kembali, kelola pelatihan Creativemu Academy dengan mudah.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" id="current-date" style="font-size: 0.82rem; font-weight: 600; color: #794bc4 !important;">
                    Memuat tanggal...
                </div>
                <div class="admin-profile">
                    <img src="https://ui-avatars.com/api/?name=Super+Admin&background=794bc4&color=fff&size=128" alt="Admin Photo">
                    <div class="admin-info">
                        <h6>Super Admin</h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- === STAT CARDS (KOTAKAN ATAS) === -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <small>TOTAL KELAS</small>
                        <h3><?= $total_kelas; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <small>TOTAL MENTOR</small>
                        <h3><?= $total_mentor; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <small>TOTAL SISWA</small>
                        <h3><?= $total_peserta; ?></h3>
                    </div>
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <small>VALIDASI</small>
                        <h3 class="text-warning"><?= $pending_validasi; ?></h3>
                    </div>
                    <div class="stat-icon" style="background: #fff8e1; color: #fbc02d;"><i class="fas fa-clock-rotate-left"></i></div>
                </div>
            </div>
        </div>

        <!-- === DIAGRAM / CHART SECTION === -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="chart-box">
                    <div class="chart-title">
                        <i class="fas fa-chart-column"></i> Monitoring Angket Kepuasan
                    </div>
                    <canvas id="angketChart" height="140"></canvas>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="chart-box">
                    <div class="chart-title">
                        <i class="fas fa-chart-line"></i> Absensi Kehadiran Mentor (Per Bulan)
                    </div>
                    <canvas id="absensiChart" height="140"></canvas>
                </div>
            </div>
        </div>

        <!-- === TAMBAHAN DI BAWAH DIAGRAM (AKTIVITAS & STATISTIK CEPAT) === -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="extra-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="color: var(--dark-purple);">Pendaftaran Siswa Terbaru</h6>
                        <a href="<?= base_url('admin/validasi'); ?>" class="text-decoration-none fw-bold" style="font-size: 0.85rem; color: var(--primary-purple);">Kelola Semua &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="table-light text-uppercase fs-7 text-muted">
                                <tr>
                                    <th>Nama Peserta</th>
                                    <th>Pilihan Kelas</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">Ahmad Fauzi</td>
                                    <td>Fullstack Web Development</td>
                                    <td>12 Agu 2026</td>
                                    <td><span class="badge bg-warning text-dark px-2 py-1 rounded-pill">Pending</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Siti Aminah</td>
                                    <td>UI/UX Design Masterclass</td>
                                    <td>11 Agu 2026</td>
                                    <td><span class="badge bg-success px-2 py-1 rounded-pill">Diterima</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Budi Santoso</td>
                                    <td>Flutter Mobile App</td>
                                    <td>10 Agu 2026</td>
                                    <td><span class="badge bg-success px-2 py-1 rounded-pill">Diterima</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="extra-section text-center py-4 d-flex flex-column justify-content-center align-items-center h-100">
                    <div class="mb-3 p-3 rounded-circle" style="background: var(--light-purple); color: var(--primary-purple); width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-shield-halved fa-2x"></i>
                    </div>
                    <h6 class="fw-bold" style="color: var(--dark-purple);">Sistem Status & Keamanan</h6>
                    <p class="text-muted small px-3 mb-3">Database MySQL terhubung dengan aman menggunakan CodeIgniter 4 ORM/Query Builder.</p>
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold" style="font-size: 0.8rem;">
                        <i class="fas fa-circle fa-2xs me-1 text-success animate-pulse"></i> Server Normal & Stabil
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Tanggal Dinamis Bahasa Indonesia
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        document.getElementById('current-date').innerText = today.toLocaleDateString('id-ID', options);

        // Chart Angket (Bar Chart dengan Animasi Elegan & Gradient)
        const ctxAngket = document.getElementById('angketChart').getContext('2d');
        
        // Gradient untuk Bar Chart
        let gradientBar = ctxAngket.createLinearGradient(0, 0, 0, 300);
        gradientBar.addColorStop(0, '#794bc4');
        gradientBar.addColorStop(1, '#b293f0');

        new Chart(ctxAngket, {
            type: 'bar',
            data: {
                labels: ['Sangat Puas', 'Puas', 'Cukup', 'Kurang'],
                datasets: [{
                    label: 'Jumlah Responden',
                    data: [120, 85, 30, 10],
                    backgroundColor: gradientBar,
                    borderRadius: 10,
                    barThickness: 45,
                    hoverBackgroundColor: '#5931a0'
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                },
                plugins: { 
                    legend: { display: false } 
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f0edf6' } 
                    },
                    x: { 
                        grid: { display: false } 
                    }
                }
            }
        });

        // Chart Absensi Mentor (Line Chart Per Bulan dengan Smooth Gradient Fill)
        const ctxAbsensi = document.getElementById('absensiChart').getContext('2d');
        
        let gradientLine = ctxAbsensi.createLinearGradient(0, 0, 0, 250);
        gradientLine.addColorStop(0, 'rgba(121, 75, 196, 0.35)');
        gradientLine.addColorStop(1, 'rgba(121, 75, 196, 0.0)');

        new Chart(ctxAbsensi, {
            type: 'line',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
                datasets: [{
                    label: 'Rata-rata Kehadiran (%)',
                    data: [92, 94, 91, 96, 95, 98],
                    borderColor: '#794bc4',
                    backgroundColor: gradientLine,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3.5,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#794bc4',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1800,
                    easing: 'easeOutQuart'
                },
                plugins: { 
                    legend: { position: 'bottom', labels: { font: { family: 'Poppins', size: 12, weight: 500 }, padding: 15 } } 
                },
                scales: {
                    y: { 
                        beginAtZero: false, 
                        min: 80, 
                        max: 100, 
                        grid: { color: '#f0edf6' } 
                    },
                    x: { 
                        grid: { display: false } 
                    }
                }
            }
        });
    </script>
</body>
</html>