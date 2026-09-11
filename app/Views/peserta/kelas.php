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
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #fdf4ff 100%);
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

/* Sidebar dengan Gradasi Ungu Deep Modern */
/* Sidebar dengan Gradasi Ungu Modern */
.sidebar {
    width: 260px;
    background:
        radial-gradient(circle at 15% 12%, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0) 45%),
        linear-gradient(165deg, #4a2fc9 0%, #7440e6 32%, #9257f2 60%, #b678f5 100%);
    background-size: 200% 200%, 220% 220%;
    animation: sidebarGlow 14s ease infinite;
    color: white;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 24px 20px;
    box-shadow: 6px 0 34px rgba(116, 64, 230, 0.35);
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar::after {
    content: "";
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.035) 0px,
        rgba(255, 255, 255, 0.035) 2px,
        transparent 2px,
        transparent 14px
    );
    pointer-events: none;
}

@keyframes sidebarGlow {
    0% {
        background-position: 0% 0%, 0% 0%;
    }

    50% {
        background-position: 100% 100%, 100% 100%;
    }

    100% {
        background-position: 0% 0%, 0% 0%;
    }
}

.sidebar::before {
    content: "";
    position: absolute;
    top: -60px;
    right: -60px;
    width: 180px;
    height: 180px;
    background: radial-gradient(
        circle,
        rgba(255,255,255,0.18) 0%,
        rgba(255,255,255,0) 70%
    );
    border-radius: 50%;
    pointer-events: none;
    animation: floatBlob 8s ease-in-out infinite;
}

@keyframes floatBlob {
    0%, 100% {
        transform: translateY(0) scale(1);
    }

    50% {
        transform: translateY(20px) scale(1.08);
    }
}

.sidebar-brand {
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}

.sidebar-brand i {
    animation: brandPulse 3s ease-in-out infinite;
}

@keyframes brandPulse {
    0%, 100% {
        transform: scale(1) rotate(0deg);
    }

    50% {
        transform: scale(1.12) rotate(-4deg);
    }
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
    z-index: 1;
}

.sidebar-menu li {
    margin-bottom: 8px;
    opacity: 0;
    transform: translateX(-12px);
    animation: menuSlideIn 0.5s ease forwards;
}

.sidebar-menu li:nth-child(1) {
    animation-delay: 0.05s;
}

.sidebar-menu li:nth-child(2) {
    animation-delay: 0.12s;
}

.sidebar-menu li:nth-child(3) {
    animation-delay: 0.19s;
}

.sidebar-menu li:nth-child(4) {
    animation-delay: 0.26s;
}

.sidebar-menu li:nth-child(5) {
    animation-delay: 0.33s;
}

@keyframes menuSlideIn {
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.sidebar-menu a {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.75);
    text-decoration: none;
    padding: 12px 16px;
    border-radius: 12px;
    font-weight: 500;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.sidebar-menu a::before {
    content: "";
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.15),
        transparent
    );
    transition: left 0.6s ease;
}

.sidebar-menu a:hover::before {
    left: 100%;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: rgba(255, 255, 255, 0.18);
    color: white;
    transform: translateX(6px);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
}

.sidebar-menu a.active {
    background: linear-gradient(
        90deg,
        rgba(255, 255, 255, 0.28),
        rgba(255, 255, 255, 0.12)
    );
    box-shadow:
        0 6px 18px rgba(20, 5, 60, 0.28),
        inset 3px 0 0 #ffd166;
}

.sidebar-menu a i {
    font-size: 1.2rem;
    margin-right: 12px;
    transition: transform 0.3s ease;
}

.sidebar-menu a:hover i {
    transform: scale(1.15) rotate(-6deg);
}

        .main-content {
            flex: 0 0 calc(100% - 260px);
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
            max-width: calc(100% - 260px);
            min-width: 0;
            box-sizing: border-box;
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.04);
        }

        .card {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.05);
            transition: all 0.3s ease;
        }

        .materi-card {
            background: linear-gradient(145deg, #ffffff 0%, #faf8ff 100%);
            border: 1px solid #ede9fe;
            border-radius: 18px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .materi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(109, 40, 217, 0.12);
            border-color: #c4b5fd;
        }

        .materi-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            color: #7c3aed;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
        }

        .materi-badge {
            display: inline-block;
            margin-top: 14px;
            width: fit-content;
            padding: 6px 14px;
            border-radius: 20px;
            background: #f3e8ff;
            color: #7c3aed;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .materi-card p {
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
        }

        .materi-footer {
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .status-materi {
            font-size: 12px;
            font-weight: 700;
            color: #059669;
        }

        .status-materi-terkunci {
            font-size: 12px;
            font-weight: 600;
            color: #94a3b8;
        }

        .nav-tabs {
            border-bottom: none;
            gap: 10px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #64748b;
            font-weight: 600;
            padding: 12px 22px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(5px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: all 0.25s ease;
        }

        .nav-tabs .nav-link:hover {
            color: #7c3aed;
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link.active {
            color: white;
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.25);
            border: none;
        }
    </style>
</head>

<body>

<div class="app-wrapper">

    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i> Creativemu
        </a>

        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('peserta/dashboard') ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>">
                    <i class="bi bi-journals"></i> Daftar Kelas Saya
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/kbm') ?>" class="active">
                    <i class="bi bi-mortarboard-fill"></i> KBM
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>">
                    <i class="bi bi-gear-fill"></i> Pengaturan
                </a>
            </li>
            <li class="mt-5">
                <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </a>
            </li>
        </ul>
    </nav>


    <!-- PAGE CONTENT -->
    <div class="main-content">

        <!-- Navbar Atas -->
        <nav class="navbar navbar-expand-lg top-navbar mb-4 px-4 py-3">
            <div class="container-fluid px-0">
                <span class="navbar-brand mb-0 h5 fw-bold text-dark">
                    Detail Kelas Peserta
                </span>
                <span class="text-muted fw-semibold small px-3 py-1 bg-white rounded-pill shadow-sm">
                    <i class="fa-solid fa-user-circle me-1 text-primary"></i> Peserta
                </span>
            </div>
        </nav>


        <!-- Konten Utama Kelas -->
        <div class="container-fluid px-0">

            <!-- Flashdata Alert -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Card Informasi Kelas -->
            <div class="card mb-4 border-start border-4 border-primary">
                <div class="card-body p-4">
                    <?php if ($kelas): ?>
                        <h3 class="fw-bold mb-2" style="color: #4c1d95;">
                            <?= esc($kelas['nama_kelas'] ?? 'Kelas Pelatihan') ?>
                        </h3>
                        <p class="text-muted mb-3">
                            <?= esc($kelas['deskripsi'] ?? 'Pelatihan dirancang untuk membekali peserta dengan pemahaman komprehensif.') ?>
                        </p>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold">
                                <i class="bi bi-person-badge me-1"></i> Mentor: <?= esc($kelas['nama_mentor'] ?? '-') ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0 rounded-3">
                            Anda belum terdaftar di kelas manapun.
                        </div>
                    <?php endif; ?>
                </div>
            </div>


            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" id="kelasTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi" type="button" role="tab">
                        <i class="fa-solid fa-book me-1"></i> Materi Pembelajaran
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="absensi-tab" data-bs-toggle="tab" data-bs-target="#absensi" type="button" role="tab">
                        <i class="fa-solid fa-calendar-check me-1"></i> Absensi & Riwayat
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ujian-tab" data-bs-toggle="tab" data-bs-target="#ujian" type="button" role="tab">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Ujian
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="angket-tab" data-bs-toggle="tab" data-bs-target="#angket" type="button" role="tab">
                        <i class="fa-solid fa-clipboard-list me-1"></i> Angket Evaluasi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sertifikat-tab" data-bs-toggle="tab" data-bs-target="#sertifikat" type="button" role="tab">
                        <i class="fa-solid fa-award me-1"></i> Sertifikat
                    </button>
                </li>
            </ul>


            <!-- Tab Content -->
            <div class="tab-content" id="kelasTabContent">

                <!-- ================= TAB 1 : MATERI ================= -->
                <div class="tab-pane fade show active" id="materi" role="tabpanel">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2 text-dark">Daftar Modul & Materi Sesi</h5>
                            <p class="text-muted mb-4">Unduh atau pelajari modul materi yang telah diunggah oleh mentor.</p>

                            <?php if (!empty($materi)): ?>
                                <div class="row g-4">
                                    <?php foreach ($materi as $item): ?>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="materi-card">
                                                <div class="materi-icon"><i class="bi bi-journal-richtext"></i></div>
                                                <h5 class="fw-bold mt-3 text-dark"><?= esc($item['judul_materi'] ?? '-') ?></h5>
                                                <p class="text-muted mb-3"><?= esc($item['deskripsi'] ?? 'Tidak ada deskripsi materi.') ?></p>
                                                <div class="materi-footer">
                                                    <?php if (!empty($item['file_materi'])): ?>
                                                        <a href="<?= base_url('uploads/materi/' . $item['file_materi']) ?>" target="_blank" class="btn btn-sm btn-primary px-3 rounded-pill shadow-sm">
                                                            Download <i class="bi bi-download ms-1"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted small">File belum ada</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-0">
                                    <i class="bi bi-info-circle me-2"></i> Belum ada materi yang diunggah oleh mentor.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- ================= TAB 2 : ABSENSI ================= -->
                <div class="tab-pane fade" id="absensi" role="tabpanel">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2 text-dark">Rekap Absensi Kehadiran</h5>
                            <p class="text-muted mb-4">Masukkan 4 digit token yang diberikan oleh mentor saat sesi kelas berlangsung.</p>

                            <?php if (!empty($jadwal)): ?>
                                <div class="row g-4">
                                    <?php foreach ($jadwal as $item): ?>
                                        <?php
                                        $absensi = $item['absensi'] ?? null;
                                        $statusAbsensi = $absensi['status'] ?? null;
                                        $idJadwalItem = $item['id_jadwal'] ?? ($item['id_jadwal'] ?? '');
                                        
                                        // Cek status buka absensi dengan fallback agar aman jika key tidak terbawa
                                        $absensiDibuka = $item['absensi_dibuka'] ?? 1;
                                        ?>
                                        <div class="col-lg-6">
                                            <div class="materi-card">
                                                <span class="materi-badge">Pertemuan <?= esc($item['pertemuan_ke'] ?? '-') ?></span>
                                                <h5 class="fw-bold mt-3 mb-2 text-dark">Pertemuan <?= esc($item['pertemuan_ke'] ?? '-') ?></h5>
                                                <p class="text-muted mb-3">
                                                    <i class="bi bi-calendar-event me-1 text-primary"></i>
                                                    <?= !empty($item['tanggal_kbm']) ? date('d F Y, H:i', strtotime($item['tanggal_kbm'])) : 'Jadwal belum ditentukan' ?>
                                                </p>

                                                <?php if ($statusAbsensi === 'hadir'): ?>
                                                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-0 rounded-3 py-2 small">
                                                        <i class="bi bi-check-circle-fill me-2"></i> Anda sudah hadir pada pertemuan ini.
                                                    </div>
                                                <?php else: ?>
                                                    <!-- Form Input Token Absensi -->
                                                    <?php if ($absensiDibuka == 1) : ?>
                                                        <form action="<?= base_url('peserta/proses-absen'); ?>" method="POST" class="mt-2">
                                                            <?= csrf_field(); ?>
                                                            <input type="hidden" name="id_jadwal" value="<?= esc($idJadwalItem); ?>">
                                                            
                                                            <div class="input-group mb-2">
                                                                <input type="text" class="form-control" name="token_absen" maxlength="4" placeholder="Masukkan 4 digit token" required>
                                                                <button class="btn btn-primary" type="submit">Kirim Absen</button>
                                                            </div>
                                                        </form>
                                                    <?php else : ?>
                                                        <span class="badge bg-secondary">Absensi Belum Dibuka</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-0">
                                    <i class="bi bi-info-circle me-2"></i> Belum ada jadwal pertemuan.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- ================= TAB 3 : UJIAN ================= -->
                <div class="tab-pane fade" id="ujian" role="tabpanel">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2 text-dark">Ujian Akhir</h5>
                            <p class="text-muted mb-4">Silakan download soal ujian dan kumpulkan jawaban Anda.</p>
                        </div>
                    </div>
                </div>


                <!-- ================= TAB 4 : ANGKET EVALUASI ================= -->
                <div class="tab-pane fade" id="angket" role="tabpanel">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-2 text-dark">Angket Evaluasi Pelatihan</h5>
                            <?php if (isset($sudah_ujian) && $sudah_ujian): ?>
                                <?php if (isset($sudah_isi_angket) && $sudah_isi_angket): ?>
                                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-0 rounded-4 p-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> Terima kasih, Anda sudah mengisi angket evaluasi pelatihan ini.
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-4">Silakan isi angket evaluasi untuk membantu meningkatkan kualitas pelatihan kami ke depannya.</p>
                                    <a href="<?= base_url('pelatihan/angket'); ?>" class="btn text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                                        <i class="fas fa-clipboard-list me-2"></i> Isi Angket Sekarang
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark mb-0 rounded-4 p-3">
                                    <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i> <strong>Belum tersedia.</strong> Angket evaluasi akan terbuka setelah Anda menyelesaikan seluruh ujian.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>


                <!-- ================= TAB 5 : SERTIFIKAT ================= -->
                <div class="tab-pane fade" id="sertifikat" role="tabpanel">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3 text-dark">Sertifikat Pelatihan</h5>
                            <?php 
                                $kategoriKelas = '';
                                if (!empty($pendaftaran) && is_array($pendaftaran)) {
                                    $kategoriKelas = trim($pendaftaran['kategori_kelas'] ?? '');
                                }
                                $isSertifikasi = (strcasecmp($kategoriKelas, 'Pelatihan Sertifikasi') === 0 || stripos($kategoriKelas, 'sertifikasi') !== false);
                            ?>

                            <?php if ($isSertifikasi): ?>
                                <?php if (isset($sertifikatTerbit) && $sertifikatTerbit): ?>
                                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-3 rounded-4 p-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> Selamat! Sertifikat kelas sertifikasi Anda sudah terbit dan siap diunduh.
                                    </div>
                                    <a href="<?= base_url('pelatihan/sertifikat/' . ($pendaftaran['id_kelas'] ?? '')) ?>" class="btn btn-success rounded-pill px-4 py-2 shadow-sm">
                                        <i class="bi bi-download me-1"></i> Unduh Sertifikat PDF
                                    </a>
                                <?php elseif (isset($sudah_isi_angket) && $sudah_isi_angket): ?>
                                    <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info mb-0 rounded-4 p-3">
                                        <i class="bi bi-info-circle me-2"></i> Terima kasih telah mengisi angket evaluasi. Sertifikat Anda sedang dalam proses verifikasi oleh admin.
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark mb-0 rounded-4 p-3">
                                        <i class="bi bi-exclamation-circle me-2 text-warning"></i> Sertifikat belum dapat diunduh. Pastikan Anda sudah menyelesaikan ujian dan mengisi angket evaluasi terlebih dahulu.
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-secondary border-0 bg-secondary bg-opacity-10 text-secondary mb-0 rounded-4 p-3">
                                    <i class="bi bi-info-circle me-2"></i> Anda terdaftar pada kelas tipe <strong>Basic</strong>. Kelas tipe Basic tidak menerbitkan sertifikat kelulusan.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>