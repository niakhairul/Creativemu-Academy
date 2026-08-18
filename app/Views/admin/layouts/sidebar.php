<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - Creativemu Academy'; ?></title>
    
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-pastel: #f4f5fa;
            --purple-main: #7c5dfa;
            --purple-dark: #5e35b1;
            --purple-light: #f0ecff;
            --text-dark: #1e1f24;
            --text-muted: #7e859e;
            --sidebar-width: 270px;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-pastel);
            color: var(--text-dark);
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #ffffff;
            border-right: 1px solid #eef0f6;
            padding: 28px 20px;
            box-shadow: 6px 0 24px rgba(124, 93, 250, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            z-index: 100;
        }

        .brand-logo {
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid #f0ecff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo img {
            max-height: 42px;
            width: auto;
            object-fit: contain;
        }

        .brand-logo span {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--purple-dark);
            letter-spacing: -0.3px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 18px;
            color: var(--text-muted);
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 8px;
            transition: all 0.25s ease-in-out;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            background-color: var(--purple-light);
            color: var(--purple-main);
            transform: translateX(4px);
        }

        .nav-link-custom.logout-btn {
            color: #e74c3c;
            margin-top: 10px;
            background-color: #fff5f5;
        }

        .nav-link-custom.logout-btn:hover {
            background-color: #ffe0e0;
            color: #c0392b;
            transform: none;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 36px 44px;
        }

        /* Stat Cards */
        .card-stat {
            border: none;
            border-radius: 20px;
            padding: 24px;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(124, 93, 250, 0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 36px rgba(124, 93, 250, 0.22);
        }

        .bg-purple-1 { background: linear-gradient(135deg, #7c5dfa, #9575cd); }
        .bg-purple-2 { background: linear-gradient(135deg, #7e57c2, #b39ddb); }
        .bg-purple-3 { background: linear-gradient(135deg, #5e35b1, #7c5dfa); }
        .bg-purple-4 { background: linear-gradient(135deg, #ff708d, #ff5252); }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        /* Chart & Flow Cards */
        .chart-card, .flow-card {
            background: #ffffff;
            border: 1px solid #eef0f6;
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            height: 100%;
        }

        .flow-header {
            font-weight: 700;
            color: var(--purple-dark);
            font-size: 1.1rem;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .flow-step-item {
            position: relative;
            padding-left: 24px;
            margin-bottom: 14px;
            font-size: 0.93rem;
        }

        .flow-step-item::before {
            content: "";
            position: absolute;
            left: 6px;
            top: 8px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--purple-main);
        }

        .badge-pastel {
            background-color: var(--purple-light);
            color: var(--purple-main);
            font-weight: 700;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <div class="sidebar">
        <div>
            <div class="brand-logo">
                <img src="<?= base_url('assets/img/logo.png'); ?>" alt="Creativemu Logo">
                <span>Creativemu</span>
            </div>
            
            <nav>
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link-custom <?= (url_is('admin/dashboard*')) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link-custom <?= (url_is('admin/master-kelas*')) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-book-bookmark"></i> Master Kelas
                </a>
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link-custom <?= (url_is('admin/data-peserta*')) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users"></i> Data Peserta
                </a>
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link-custom <?= (url_is('admin/validasi*')) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-check"></i> Validasi Pendaftaran
                </a>
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link-custom <?= (url_is('admin/sertifikat*')) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-award"></i> Sertifikat
                </a>
                <a href="<?= base_url('admin/laporan'); ?>" class="nav-link-custom <?= (url_is('admin/laporan*')) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-lines"></i> Laporan
                </a>
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link-custom <?= (url_is('admin/pengaturan*')) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-gear"></i> Pengaturan
                </a>
            </nav>
        </div>

        <!-- Tombol Keluar (Selalu Ada di Semua Halaman) -->
        <div class="pt-3">
            <a href="<?= base_url('login'); ?>" class="nav-link-custom logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <?= $this->renderSection('content'); ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>
    
    <!-- Render Script Khusus Halaman -->
    <?= $this->renderSection('script'); ?>
</body>
</html>