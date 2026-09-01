<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome untuk ikon pelengkap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf5ff; 
            background-image: radial-gradient(#d8b4fe 1px, transparent 1px);
            background-size: 24px 24px;
            margin: 0;
            padding: 0;
        }

        /* Layout Utama dengan Sidebar */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar dengan Gradasi 2 Warna Ungu */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #581c87 0%, #7c3aed 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px;
            box-shadow: 4px 0 20px rgba(124, 58, 237, 0.1);
            overflow-y: auto;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu a i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        /* Konten Utama yang menyesuaikan lebar sidebar */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
        }
    </style>
</head>
<body>

<div class="app-wrapper">
    <!-- Sidebar Kustom dengan Menu Terstruktur Ringkas & Konsisten -->
    <nav class="sidebar">
        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4"></i> Creativemu
        </a>
        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('peserta/dashboard') ?>"><i class="bi bi-grid-fill"></i> Dashboard</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>"><i class="bi bi-journals"></i> Daftar Kelas Saya</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/kbm') ?>"><i class="bi bi-mortarboard-fill"></i> KBM</a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>"><i class="bi bi-journals"></i> Pengaturan</a>
            </li>
            <li class="mt-5">
                <a href="<?= base_url('auth/logout') ?>" class="text-danger-subtle bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
            </li>
        </ul>
    </nav>

    <!-- PAGE CONTENT (Dibungkus rapi di dalam main-content agar tidak tumpang tindih dengan sidebar) -->
    <div class="main-content">
        <!-- Navbar Atas Sederhana -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4 px-4 py-3">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h5 fw-bold">Detail Kelas Peserta</span>
                <span class="text-muted"><i class="fa-solid fa-user-circle me-1"></i> Peserta</span>
            </div>
        </nav>

        <!-- Konten Utama Kelas -->
        <div class="container-fluid px-0">
            
            <!-- Card Informasi Kelas -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <?php if ($kelas): ?>
                        <h3 class="text-primary fw-bold"><?= esc($kelas['nama_kelas'] ?? 'Kelas Pelatihan') ?></h3>
                        <p class="text-muted mb-2"><?= esc($kelas['deskripsi'] ?? 'Pelatihan dirancang untuk membekali peserta dengan pemahaman komprehensif.') ?></p>
                        <span class="badge bg-success">Mentor: <?= esc($kelas['nama_mentor'] ?? '-') ?></span>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0">Anda belum terdaftar di kelas manapun.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Nav Tabs Navigasi -->
            <ul class="nav nav-tabs mb-4" id="kelasTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi" type="button" role="tab"><i class="fa-solid fa-book me-1"></i> Materi Pembelajaran</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="absensi-tab" data-bs-toggle="tab" data-bs-target="#absensi" type="button" role="tab"><i class="fa-solid fa-calendar-check me-1"></i> Absensi & Riwayat</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="ujian-tab" data-bs-toggle="tab" data-bs-target="#ujian" type="button" role="tab"><i class="fa-solid fa-pen-to-square me-1"></i> Ujian & Tugas</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="sertifikat-tab" data-bs-toggle="tab" data-bs-target="#sertifikat" type="button" role="tab"><i class="fa-solid fa-award me-1"></i> Sertifikat & Angket</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="kelasTabContent">
                <!-- Tab 1: Materi -->
                <div class="tab-pane fade show active" id="materi" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Daftar Modul & Materi Sesi</h5>
                            <p class="text-muted">Unduh atau pelajari modul materi yang telah diunggah oleh mentor.</p>
                            <div class="alert alert-info">Silakan cek modul berkala sesuai jadwal pertemuan kelas.</div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Absensi -->
                <div class="tab-pane fade" id="absensi" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Rekap Absensi Kehadiran</h5>
                            <p>Total Pertemuan: <strong><?= $totalPertemuan ?></strong> | Hadir: <strong><?= $jumlahHadir ?></strong> (<?= $persentaseKehadiran ?>%)</p>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Ujian -->
                <div class="tab-pane fade" id="ujian" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Ujian & Tugas Akhir</h5>
                            <p class="text-muted">Kerjakan ujian dan tugas yang tersedia untuk menyelesaikan pelatihan.</p>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Sertifikat -->
                <div class="tab-pane fade" id="sertifikat" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">2. Sertifikat Pelatihan</h5>
                            <?php if ($sertifikatAcademy): ?>
                                <div class="alert alert-success">Selamat! Anda lulus dan dapat mengunduh sertifikat Anda.</div>
                                <a href="<?= base_url('pelatihan/sertifikat') ?>" class="btn btn-success">Unduh Sertifikat</a>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">Sertifikat belum dapat diunduh. Pastikan Anda lulus ujian (minimal nilai 70) dan sudah mengisi angket evaluasi.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Load Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>