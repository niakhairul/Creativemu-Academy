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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf5ff; /* Ungu pastel sangat lembut sebagai latar belakang utama */
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

        /* Konten Utama */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.1) !important;
        }
        .stat-icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
        }
        .bg-purple-soft {
            background-color: #f3e8ff;
        }
        .text-purple-custom {
            color: #7c3aed;
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
                <a href="<?= base_url('peserta/dashboard') ?>" class="active"><i class="bi bi-grid-fill"></i> Dashboard</a>
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

    <!-- Bagian Konten Utama -->
    <div class="main-content">
        <div class="container-fluid py-2">

            <!-- Header Sambutan Sesuai Referensi Gambar -->
            <div class="row mb-4 align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h2 class="fw-bold mb-1" style="color: #2e1065;">
                        Halo, <?= esc($user['nama']) ?> 👋
                    </h2>
                    <p class="text-muted mb-0">
                        Selamat datang di dashboard peserta
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-inline-flex align-items-center bg-white p-2 px-3 rounded-pill shadow-sm border border-purple border-opacity-10">
                        <div class="bg-purple-soft text-purple-custom p-2 rounded-circle me-2 position-relative">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>
                        </div>
                        <div class="text-start me-2">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?= esc($user['nama']) ?></h6>
                            <span class="text-muted" style="font-size: 0.75rem;">Peserta</span>
                        </div>
                    </div>
                </div>
            </div>
                
            <!-- Kotak Statistik Ringkas 4 Kolom Sesuai Gambar Referensi -->
            <div class="row mb-4">
                <!-- 1. Kelas Aktif -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-mortarboard-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #2e1065;"><?= $pendaftaran ? 1 : 0 ?></h3>
                                <p class="text-dark mb-0 fw-bold small">Kelas Aktif</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Sedang berlangsung</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 2. Kehadiran -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-calendar-check-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #2e1065;"><?= esc($total_kehadiran ?? '0') ?>%</h3>
                                <p class="text-dark mb-0 fw-bold small">Kehadiran</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Total Kehadiran</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Tugas -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-clipboard-check-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #2e1065;"><?= esc($total_tugas ?? '0') ?></h3>
                                <p class="text-dark mb-0 fw-bold small">Tugas</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Belum Dikumpulkan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Sertifikat -->
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4 d-flex align-items-center">
                            <div class="stat-icon bg-purple-soft text-purple-custom me-3 flex-shrink-0">
                                <i class="bi bi-award-fill fs-4"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0" style="color: #2e1065;"><?= esc($total_sertifikat ?? '0') ?></h3>
                                <p class="text-dark mb-0 fw-bold small">Sertifikat</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Telah Diperoleh</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Utama: Profil & Status Pendaftaran -->
            <div class="row mb-4">

                <!-- Profil Saya -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                                    <i class="bi bi-person-circle fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #2e1065;">Profil Saya</h4>
                            </div>

                            <table class="table table-borderless align-middle mb-0">
                                <tr>
                                    <td width="130" class="text-muted fw-semibold">NIS</td>
                                    <td class="fw-bold text-primary">: <span class="badge bg-purple-soft text-purple-custom px-2 py-1"><?= esc($user['nis'] ?? $pendaftaran['nis'] ?? '-') ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Nama</td>
                                    <td class="fw-bold text-dark">: <?= esc($user['nama']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Email</td>
                                    <td class="fw-bold text-dark">: <?= esc($user['email']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">No HP</td>
                                    <td class="fw-bold text-dark">: <?= esc($user['no_hp'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Pendaftaran -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                                    <i class="bi bi-clipboard-check fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #2e1065;">Status Pendaftaran</h4>
                            </div>

                            <?php if ($pendaftaran == null): ?>
                                <div class="text-center py-3">
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill mb-3 shadow-sm">
                                        Belum Mendaftar
                                    </span>
                                    <p class="text-muted small mb-3">
                                        Anda belum terdaftar di kelas pelatihan apapun. Mari mulai langkah belajarmu sekarang.
                                    </p>
                                    <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-custom-primary px-4 rounded-pill shadow-sm text-white" style="background-color: #7c3aed;">
                                        Pilih Kelas Sekarang
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="mb-3">
                                    <?php 
                                        $statusDaftar = $pendaftaran['status_pendaftaran'] ?? '';
                                        $statusBayar  = $pendaftaran['status_pembayaran'] ?? '';
                                    ?>

                                    <?php if ($statusBayar == 'terkonfirmasi' || strtolower($statusDaftar) == 'disetujui' || $statusBayar == 'valid'): ?>
                                        <span class="badge bg-success fs-6 px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-check-circle me-1"></i> Sudah Divalidasi / Disetujui
                                        </span>
                                    <?php elseif ($statusBayar == 'batal' || strtolower($statusDaftar) == 'ditolak' || $statusBayar == 'rejected'): ?>
                                        <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-x-circle me-1"></i> Ditolak
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                                            <i class="bi bi-clock-history me-1"></i> Menunggu Validasi Admin
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Kotak Informasi Kelas yang Dipilih -->
                                <!-- Kotak Informasi Kelas yang Dipilih -->
<div class="p-3 border border-purple border-opacity-25 rounded-4 bg-light shadow-sm">
    <h6 class="fw-bold mb-2" style="color: #2e1065;"><?= esc($pendaftaran['nama_kelas'] ?? 'Kelas Pelatihan') ?></h6>
    <p class="text-muted small mb-1">
        <i class="bi bi-person-badge me-1"></i> Mentor: <strong class="text-dark"><?= esc($pendaftaran['nama_mentor'] ?? '-') ?></strong>
    </p>
    <p class="text-muted small mb-0">
        <i class="bi bi-calendar-event me-1"></i> Jadwal: <strong class="text-dark"><?= esc($pendaftaran['jadwal'] ?? '-') ?></strong>
    </p>
</div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Bagian Tambahan: Jadwal Pelatihan & Pengumuman -->
            <div class="row">
                
                <!-- Jadwal Pelatihan & Materi (Dinamis dari Admin) -->
                <div class="col-lg-7 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                                    <i class="bi bi-calendar-range fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #2e1065;">Jadwal Pelatihan & Materi</h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-uppercase" style="font-size: 0.75rem;">
                                        <tr>
                                            <th>Pertemuan</th>
                                            <th>Tanggal</th>
                                            <th>Materi Pembelajaran</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($jadwal_pelatihan) && is_array($jadwal_pelatihan)): ?>
                                            <?php foreach ($jadwal_pelatihan as $jp): ?>
                                                <tr>
                                                    <td class="fw-bold text-purple-custom">Pertemuan <?= esc($jp['pertemuan_ke'] ?? $jp['pertemuan'] ?? '-') ?></td>
                                                    <td class="small text-muted"><?= esc($jp['tanggal'] ?? '-') ?></td>
                                                    <td class="fw-semibold text-dark"><?= esc($jp['materi'] ?? '-') ?></td>
                                                    <td>
                                                        <?php 
                                                            $statusJadwal = strtolower($jp['status'] ?? 'selesai');
                                                            if ($statusJadwal == 'selesai') {
                                                                echo '<span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">Selesai</span>';
                                                            } elseif ($statusJadwal == 'berjalan' || $statusJadwal == 'aktif') {
                                                                echo '<span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill">Berjalan</span>';
                                                            } else {
                                                                echo '<span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">Akan Datang</span>';
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <i class="bi bi-calendar-x fs-3 d-block mb-2"></i><span>Belum ada jadwal pelatihan yang diatur oleh admin.</span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengumuman (Dinamis dari Admin) -->
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100 hover-card bg-white">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-purple-soft text-purple-custom p-2 rounded-3 me-3">
                                    <i class="bi bi-megaphone-fill fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #2e1065;">Pengumuman Penting</h4>
                            </div>

                            <?php if (!empty($pengumuman_list) && is_array($pengumuman_list)): ?>
                                <?php foreach ($pengumuman_list as $pengumuman): ?>
                                    <div class="p-3 mb-3 border border-purple border-opacity-25 rounded-4 bg-purple-soft bg-opacity-25">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-info-circle fs-3 text-purple-custom me-3 mt-1"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1" style="color: #2e1065;"><?= esc($pengumuman['judul'] ?? 'Pengumuman') ?></h6>
                                                <p class="text-muted small mb-2">
                                                    <?= esc($pengumuman['isi'] ?? $pengumuman['konten'] ?? '') ?>
                                                </p>
                                                <?php if (!empty($pengumuman['link_url'])): ?>
                                                    <a href="<?= esc($pengumuman['link_url']) ?>" class="btn btn-sm btn-custom-primary rounded-pill px-3 fw-semibold text-white" style="background-color: #7c3aed;">
                                                        <?= esc($pengumuman['link_text'] ?? 'Aksi Selengkapnya') ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Kotak Pengumuman Sertifikat & Angket (Default Fallback) -->
                                <div class="p-3 mb-3 border border-purple border-opacity-25 rounded-4 bg-purple-soft bg-opacity-25">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-award fs-3 text-purple-custom me-3 mt-1"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1" style="color: #2e1065;">Unduh Sertifikat Pelatihan</h6>
                                            <p class="text-muted small mb-3">
                                                Bagi peserta yang mengambil pilihan kelas dengan sertifikat, silakan lengkapi angket evaluasi terlebih dahulu melalui menu KBM sebelum mengunduh sertifikat Anda.
                                            </p>
                                            
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php if (!empty($pendaftaran['ambil_sertifikat']) && $pendaftaran['ambil_sertifikat'] == 'Ya'): ?>
                                                    <a href="<?= base_url('pelatihan/kbm') ?>" class="btn btn-sm btn-custom-primary rounded-pill px-3 fw-semibold text-white" style="background-color: #7c3aed;">
                                                        <i class="bi bi-mortarboard me-1"></i> Buka Menu KBM
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                                                        <i class="bi bi-info-circle me-1"></i> Kelas Anda tidak mengambil opsi sertifikat.
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Ketentuan Kehadiran -->
                            <div class="p-3 border rounded-4 bg-light">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle fs-4 text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Ketentuan Kehadiran</h6>
                                        <p class="text-muted small mb-0">
                                            Pastikan selalu melakukan absensi pada setiap sesi pertemuan agar persentase kehadiran Anda tetap memenuhi syarat kelulusan minimal 80%.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>