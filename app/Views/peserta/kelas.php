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
            /* Gradasi latar belakang cerah bernuansa ungu lembut */
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #fdf4ff 100%);
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        /* Layout Utama dengan Sidebar */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar dengan Gradasi Ungu Deep Modern */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #4c1d95 0%, #6d28d9 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 24px 20px;
            box-shadow: 6px 0 25px rgba(109, 40, 217, 0.15);
            overflow-y: auto;
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
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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
            width: calc(100% - 260px);
        }

        /* Navbar Atas dengan Efek Kaca Tipis */
        .top-navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.04);
        }

        /* Card Umum dengan Nuansa Berwarna Lembut */
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

        /* Nav Tabs Modern Style */
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
                            <?php if (isset($isSertifikasi) && $isSertifikasi): ?>
                                <span class="badge text-white px-3 py-2 rounded-pill fw-semibold shadow-sm" style="background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                                    <i class="bi bi-award me-1"></i> Tipe: Sertifikasi
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-semibold">
                                    Tipe: Basic
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0 rounded-3">
                            Anda belum terdaftar di kelas manapun.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notifikasi / Pengumuman Otomatis -->
            <?php if (isset($isSertifikasi) && $isSertifikasi): ?>
                <?php if (isset($tampilkan_notif_sertifikat) && $tampilkan_notif_sertifikat): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center rounded-4 p-3 bg-white" role="alert">
                        <i class="bi bi-award-fill fs-3 me-3 text-success"></i>
                        <div>
                            <strong>Pengumuman Penting!</strong> Sertifikat kelas sertifikasi Anda telah diterbitkan. Silakan cek menu Sertifikat untuk mengunduh.
                        </div>
                    </div>
                <?php elseif (isset($tampilkan_notif_angket) && $tampilkan_notif_angket): ?>
                    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-center rounded-4 p-3 bg-white" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-warning"></i>
                        <div>
                            <strong>Pengumuman Penting!</strong> Anda telah menyelesaikan ujian. Silakan isi Angket Evaluasi terlebih dahulu agar sertifikat pelatihan Anda dapat diproses.
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>


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
                        <i class="fa-solid fa-pen-to-square me-1"></i> Ujian & Tugas
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
                                        <?php
                                        $jadwalMateri = null;
                                        if (!empty($jadwal)) {
                                            foreach ($jadwal as $jadwalItem) {
                                                if (isset($item['id_jadwal_kelas'], $jadwalItem['id_jadwal_kelas']) && $item['id_jadwal_kelas'] == $jadwalItem['id_jadwal_kelas']) {
                                                    $jadwalMateri = $jadwalItem;
                                                    break;
                                                }
                                            }
                                        }
                                        $materiTerbuka = $jadwalMateri ? (($jadwalMateri['absensi']['status'] ?? null) === 'hadir') : false;
                                        ?>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="materi-card">
                                                <div class="materi-icon"><i class="bi bi-journal-richtext"></i></div>
                                                <span class="materi-badge">
                                                    <?= $jadwalMateri ? 'Pertemuan ' . esc($jadwalMateri['pertemuan_ke'] ?? '-') : 'Materi Pembelajaran' ?>
                                                </span>
                                                <h5 class="fw-bold mt-3 text-dark"><?= esc($item['judul_materi'] ?? '-') ?></h5>
                                                <p class="text-muted mb-3"><?= esc($item['deskripsi'] ?? 'Tidak ada deskripsi materi.') ?></p>
                                                <div class="materi-footer">
                                                    <?php if ($materiTerbuka): ?>
                                                        <span class="status-materi"><i class="bi bi-check-circle me-1"></i> Tersedia</span>
                                                        <?php if (!empty($item['file_materi'])): ?>
                                                            <a href="<?= base_url('pelatihan/materi/' . $item['id_materi_kelas']) ?>" class="btn btn-sm btn-primary px-3 rounded-pill shadow-sm">
                                                                Pelajari <i class="bi bi-arrow-right ms-1"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted small">File belum ada</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="status-materi-terkunci"><i class="bi bi-lock me-1"></i> Terkunci</span>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" disabled>Terkunci</button>
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
                            <p class="text-muted mb-4">
                                Total Pertemuan: <strong class="text-dark"><?= $totalPertemuan ?></strong> | 
                                Hadir: <strong class="text-success"><?= $jumlahHadir ?></strong> 
                                (<strong class="text-primary"><?= $persentaseKehadiran ?>%</strong>)
                            </p>

                            <?php if (!empty($jadwal)): ?>
                                <div class="row g-4">
                                    <?php foreach ($jadwal as $item): ?>
                                        <?php
                                        $absensi = $item['absensi'] ?? null;
                                        $statusAbsensi = $absensi['status'] ?? null;
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
                                                    <div class="materi-footer mt-auto">
                                                        <form action="<?= base_url('pelatihan/absensi/simpan') ?>" method="post" class="w-100 d-flex flex-column gap-2">
                                                            <input type="hidden" name="id_jadwal_kelas" value="<?= esc($item['id_jadwal_kelas']) ?>">
                                                            <div class="input-group input-group-sm">
                                                                <label class="input-group-text bg-light fw-semibold text-muted" for="status_<?= $item['id_jadwal_kelas'] ?>">Status</label>
                                                                <select name="status" id="status_<?= $item['id_jadwal_kelas'] ?>" class="form-select form-select-sm" required>
                                                                    <option value="hadir">Hadir</option>
                                                                    <option value="izin">Izin</option>
                                                                    <option value="sakit">Sakit</option>
                                                                    <option value="alpa">Tidak Masuk (Alpa)</option>
                                                                </select>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 shadow-sm">
                                                                <i class="bi bi-check-circle me-1"></i> Kirim Absensi
                                                            </button>
                                                        </form>
                                                    </div>
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
                            <h5 class="fw-bold mb-2 text-dark">Ujian & Tugas Akhir</h5>
                            <p class="text-muted mb-4">Silakan download soal ujian dan kumpulkan jawaban Anda dalam bentuk file PDF.</p>

                            <!-- Flashdata Notifikasi -->
                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <?= session()->getFlashdata('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($ujian)): ?>
                                <?php foreach ($ujian as $item): ?>
                                    <?php
                                    $deadlineLewat = false;
                                    if (!empty($item['deadline'])) {
                                        $deadlineLewat = strtotime($item['deadline']) < time();
                                    }
                                    ?>
                                    <div class="materi-card mb-4">
                                        <span class="materi-badge">Ujian / Evaluasi</span>
                                        <h5 class="fw-bold mt-3 mb-2 text-dark"><?= esc($item['judul_ujian'] ?? 'Ujian') ?></h5>
                                        <p class="text-muted"><?= esc($item['keterangan'] ?? 'Tidak ada keterangan ujian.') ?></p>

                                        <!-- DEADLINE -->
                                        <?php if (!empty($item['deadline'])): ?>
                                            <?php if ($deadlineLewat): ?>
                                                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-3 rounded-3 small">
                                                    <i class="bi bi-clock-history me-2"></i> <strong>Deadline pengumpulan sudah berakhir.</strong> 
                                                    (<?= date('d F Y, H:i', strtotime($item['deadline'])) ?>)
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-dark mb-3 rounded-3 small">
                                                    <i class="bi bi-clock me-2"></i> <strong>Deadline Pengumpulan:</strong> 
                                                    <?= date('d F Y, H:i', strtotime($item['deadline'])) ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="alert alert-secondary border-0 mb-3 rounded-3 small">
                                                <i class="bi bi-exclamation-circle me-2"></i> Deadline pengumpulan belum ditentukan.
                                            </div>
                                        <?php endif; ?>

                                        <!-- DOWNLOAD SOAL -->
                                        <?php if (!empty($item['file_soal'])): ?>
                                            <div class="d-flex align-items-center justify-content-between bg-white p-3 rounded-3 mb-3 border border-light shadow-sm">
                                                <span class="small fw-semibold text-success"><i class="bi bi-file-earmark-pdf-fill me-1"></i> Soal Tersedia</span>
                                                <a href="<?= base_url('uploads/ujian/' . $item['file_soal']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="bi bi-download me-1"></i> Download Soal
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-secondary border-0 mb-3 rounded-3 small">
                                                <i class="bi bi-exclamation-circle me-2"></i> Soal ujian belum tersedia.
                                            </div>
                                        <?php endif; ?>

                                        <!-- UPLOAD JAWABAN -->
                                        <?php if (!empty($item['jawaban'])): ?>
                                            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-0 rounded-3">
                                                <i class="bi bi-check-circle-fill me-2"></i> <strong>Jawaban sudah dikumpulkan.</strong><br>
                                                <small class="text-muted">
                                                    Waktu kumpul: <?= !empty($item['jawaban']['waktu_kumpul']) ? date('d F Y H:i', strtotime($item['jawaban']['waktu_kumpul'])) : '-' ?>
                                                </small>
                                            </div>
                                        <?php elseif ($deadlineLewat): ?>
                                            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-0 rounded-3 small">
                                                <i class="bi bi-lock-fill me-2"></i> Pengumpulan jawaban ditutup (deadline berakhir).
                                            </div>
                                        <?php else: ?>
                                            <form action="<?= base_url('pelatihan/ujian/simpan-jawaban') ?>" method="post" enctype="multipart/form-data" class="mt-2">
                                                <input type="hidden" name="id_ujian" value="<?= esc($item['id_ujian']) ?>">
                                                <label class="form-label fw-semibold small text-muted">Upload File Jawaban (Format PDF):</label>
                                                <input type="file" name="file_jawaban" class="form-control form-control-sm mb-3 rounded-3" accept=".pdf,application/pdf" required>
                                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 shadow-sm">
                                                    <i class="bi bi-upload me-1"></i> Kumpulkan Jawaban
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-info border-0 shadow-sm rounded-4 mb-0">
                                    <i class="bi bi-info-circle me-2"></i> Belum ada ujian yang tersedia.
                                </div>
                            <?php endif; ?>
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