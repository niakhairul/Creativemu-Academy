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
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        /* Main Content Styling */
        .main-content {
            margin-left: 260px;
            padding: 32px 40px;
        }

        /* Stat Cards Pastel */
        .card-stat {
            border: none;
            border-radius: 16px;
            padding: 20px;
            color: #ffffff;
            box-shadow: 0 8px 24px rgba(140, 122, 230, 0.12);
            transition: transform 0.3s ease;
        }

        .card-stat:hover {
            transform: translateY(-4px);
        }

        .bg-purple-1 { background: linear-gradient(135deg, #8c7ae6, #9b87f5); }
        .bg-purple-2 { background: linear-gradient(135deg, #a29bfe, #b39ddb); }
        .bg-purple-3 { background: linear-gradient(135deg, #6c5ce7, #8c7ae6); }
        .bg-purple-4 { background: linear-gradient(135deg, #fd79a8, #e84393); }

        .stat-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Chart Card */
        .chart-card {
            background: #ffffff;
            border: 1px solid #e9e5f5;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        /* Flow Cards Section */
        .flow-card {
            background: #ffffff;
            border: 1px solid #e9e5f5;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        }

        .flow-header {
            font-weight: 700;
            color: var(--purple-dark);
            font-size: 1.1rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flow-step-item {
            position: relative;
            padding-left: 28px;
            margin-bottom: 12px;
        }

        .flow-step-item::before {
            content: "•";
            position: absolute;
            left: 10px;
            color: var(--purple-main);
            font-size: 1.5rem;
            line-height: 1;
        }

        .badge-pastel {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            font-weight: 600;
            border-radius: 8px;
            padding: 6px 12px;
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
            <a href="/admin/dashboard" class="nav-link-custom <?= (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="/admin/master-kelas" class="nav-link-custom <?= (uri_string() == 'admin/master-kelas') ? 'active' : ''; ?>">
                <i class="fa-solid fa-book-bookmark"></i> Master Kelas
            </a>
            <a href="/admin/data-peserta" class="nav-link-custom <?= (uri_string() == 'admin/data-peserta') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Data Peserta
            </a>
            <a href="/admin/validasi" class="nav-link-custom <?= (uri_string() == 'admin/validasi') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-check"></i> Validasi Pendaftaran
            </a>
            <a href="/admin/sertifikat" class="nav-link-custom <?= (uri_string() == 'admin/sertifikat') ? 'active' : ''; ?>">
                <i class="fa-solid fa-award"></i> Sertifikat
            </a>
            <a href="/admin/laporan" class="nav-link-custom <?= (uri_string() == 'admin/laporan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-lines"></i> Laporan
            </a>
            <a href="/admin/pengaturan" class="nav-link-custom <?= (uri_string() == 'admin/pengaturan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear"></i> Pengaturan
            </a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: var(--purple-dark);">Dashboard Panel Admin</h3>
                <p class="text-muted mb-0">Selamat datang kembali! Ringkasan statistik dan monitoring KBM.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge badge-pastel"><i class="fa-solid fa-calendar me-1"></i> <?= date('d M Y'); ?></span>
            </div>
        </div>

        <!-- 4 Card Statistik (3 Grid Bootstrap / col-md-3) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card-stat bg-purple-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-medium">Total Kelas</span>
                        <div class="stat-icon"><i class="fa-solid fa-layer-group"></i></div>
                    </div>
                    <h2 class="fw-bold mb-0"><?= $total_kelas; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat bg-purple-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-medium">Total Mentor</span>
                        <div class="stat-icon"><i class="fa-solid fa-user-tie"></i></div>
                    </div>
                    <h2 class="fw-bold mb-0"><?= $total_mentor; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat bg-purple-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-medium">Total Peserta</span>
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                    </div>
                    <h2 class="fw-bold mb-0"><?= $total_peserta; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-stat bg-purple-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-medium">Menunggu Validasi</span>
                        <div class="stat-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    </div>
                    <h2 class="fw-bold mb-0"><?= $pending_validasi; ?></h2>
                </div>
            </div>
        </div>

        <!-- Diagram Grafik Absen Mentor & Angket -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3" style="color: var(--purple-dark);">
                        <i class="fa-solid fa-clipboard-user me-2"></i>Monitoring Absen Mentor
                    </h5>
                    <div style="height: 240px;">
                        <canvas id="chartAbsenMentor"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <h5 class="fw-bold mb-3" style="color: var(--purple-dark);">
                        <i class="fa-solid fa-square-poll-vertical me-2"></i>Hasil Angket Kepuasan Siswa
                    </h5>
                    <div style="height: 240px;">
                        <canvas id="chartAngket"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Alur & Hak Akses Siswa -->
        <h4 class="fw-bold mb-3" style="color: var(--purple-dark);">Ringkasan Alur & Hak Akses Siswa</h4>

        <div class="row">
            <!-- A. Registrasi & Login -->
            <div class="col-md-6">
                <div class="flow-card">
                    <div class="flow-header">
                        <i class="fa-solid fa-user-plus"></i> A. Registrasi & Login
                    </div>
                    <div class="flow-step-item">
                        <strong>Registrasi Akun Baru:</strong> Siswa mengisikan data akun dasar (Nama, Email, dan Password).
                    </div>
                    <div class="flow-step-item">
                        <strong>Login:</strong> Siswa masuk ke dalam sistem menggunakan kredensial yang telah didaftarkan.
                    </div>
                </div>
            </div>

            <!-- B. Dashboard Siswa -->
            <div class="col-md-6">
                <div class="flow-card">
                    <div class="flow-header">
                        <i class="fa-solid fa-gauge-high"></i> B. Dashboard Siswa
                    </div>
                    <p class="text-muted small mb-2">Setelah berhasil login, siswa diarahkan ke Dashboard Utama yang berisi:</p>
                    <div class="flow-step-item">
                        <strong>Profil Siswa:</strong> Menampilkan informasi biodata serta indikator status validasi pendaftaran kelas.
                    </div>
                    <div class="flow-step-item">
                        <strong>Pilihan / Katalog Kelas:</strong> Menampilkan daftar kelas yang tersedia beserta informasi detail kelas.
                    </div>
                </div>
            </div>

            <!-- C. Pendaftaran Kelas & Verifikasi Admin -->
            <div class="col-md-6">
                <div class="flow-card">
                    <div class="flow-header">
                        <i class="fa-solid fa-file-signature"></i> C. Pendaftaran Kelas & Verifikasi Admin
                    </div>
                    <div class="flow-step-item">
                        <strong>Eksplorasi Kelas:</strong> Siswa memilih kelas dan melihat Detail Kelas.
                    </div>
                    <div class="flow-step-item">
                        <strong>Pengisian Form Pendaftaran:</strong> Siswa menekan tombol "Daftar Kelas" dan mengisi formulir pendaftaran yang diperlukan.
                    </div>
                    <div class="flow-step-item">
                        <strong>Status Menunggu Verifikasi (Pending):</strong>
                        <ul class="ps-3 mt-1 mb-0 text-muted small">
                            <li>Setelah formulir dikirim, status pendaftaran berubah menjadi "Menunggu Verifikasi".</li>
                            <li>Tombol "Daftar Kelas" pada kelas tersebut otomatis berubah menjadi status nonaktif (disabled) untuk mencegah pendaftaran ganda.</li>
                        </ul>
                    </div>
                    <div class="flow-step-item mt-2">
                        <strong>Verifikasi Admin:</strong> Admin melakukan peninjauan (review) data pendaftaran dari panel Admin.
                    </div>
                </div>
            </div>

            <!-- D. Pasca-Validasi -->
            <div class="col-md-6">
                <div class="flow-card">
                    <div class="flow-header">
                        <i class="fa-solid fa-circle-check"></i> D. Pasca-Validasi
                    </div>
                    <p class="text-muted small mb-2">Setelah Admin menyetujui pendaftaran:</p>
                    <div class="flow-step-item">
                        <strong>Perubahan Status Profil:</strong> Di bawah nama profil siswa, indikator status berubah menjadi "Sudah Divalidasi".
                    </div>
                    <div class="flow-step-item">
                        <strong>Aktivasi Kelas:</strong> Kelas yang didaftarkan otomatis muncul di daftar kelas aktif siswa.
                    </div>
                    <div class="flow-step-item">
                        <strong>Pembukaan Akses KBM:</strong> Seluruh fitur dalam menu Kegiatan Belajar Mengajar (KBM) resmi terbuka (unlocked).
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Inisialisasi Chart.js -->
    <script>
        // 1. Chart Absensi Mentor
        const ctxAbsen = document.getElementById('chartAbsenMentor').getContext('2d');
        new Chart(ctxAbsen, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [{
                    label: 'Kehadiran Mengajar (%)',
                    data: [95, 100, 90, 98],
                    backgroundColor: '#8c7ae6',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });

        // 2. Chart Angket Evaluasi Siswa
        const ctxAngket = document.getElementById('chartAngket').getContext('2d');
        new Chart(ctxAngket, {
            type: 'doughnut',
            data: {
                labels: ['Sangat Puas', 'Puas', 'Cukup', 'Kurang'],
                datasets: [{
                    data: [65, 25, 8, 2],
                    backgroundColor: ['#6c5ce7', '#a29bfe', '#ffeaa7', '#ff7675']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</body>
</html>