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

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
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
            width: calc(100% - 260px);
        }

        .materi-card {
            background: #ffffff;
            border: 1px solid #eeeeee;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.25s ease;
        }

        .materi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(108, 63, 200, 0.12);
            border-color: #8b5cf6;
        }

        .materi-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #f1eaff;
            font-size: 25px;
        }

        .materi-badge {
            display: inline-block;
            margin-top: 15px;
            width: fit-content;
            padding: 5px 12px;
            border-radius: 20px;
            background: #eee5ff;
            color: #6f32c9;
            font-size: 12px;
            font-weight: 600;
        }

        .materi-card p {
            font-size: 14px;
            line-height: 1.6;
        }

        .materi-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #eeeeee;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .status-materi {
            font-size: 12px;
            font-weight: 600;
            color: #198754;
        }

        .status-materi-terkunci {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
        }

        .materi-footer .btn {
            border-radius: 8px;
            padding: 7px 13px;
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="app-wrapper">

    <!-- Sidebar -->
    <nav class="sidebar">

        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4"></i> Creativemu
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
                <a href="<?= base_url('pelatihan/kbm') ?>">
                    <i class="bi bi-mortarboard-fill"></i> KBM
                </a>
            </li>

            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>">
                    <i class="bi bi-journals"></i> Pengaturan
                </a>
            </li>

            <li class="mt-5">
                <a href="<?= base_url('auth/logout') ?>"
                   class="text-danger-subtle bg-danger bg-opacity-10">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </a>
            </li>

        </ul>
    </nav>


    <!-- PAGE CONTENT -->
    <div class="main-content">

        <!-- Navbar Atas -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded mb-4 px-4 py-3">

            <div class="container-fluid">

                <span class="navbar-brand mb-0 h5 fw-bold">
                    Detail Kelas Peserta
                </span>

                <span class="text-muted">
                    <i class="fa-solid fa-user-circle me-1"></i> Peserta
                </span>

            </div>

        </nav>


        <!-- Konten Utama Kelas -->
        <div class="container-fluid px-0">

            <!-- Card Informasi Kelas -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <?php if ($kelas): ?>

                        <h3 class="text-primary fw-bold">
                            <?= esc($kelas['nama_kelas'] ?? 'Kelas Pelatihan') ?>
                        </h3>

                        <p class="text-muted mb-2">
                            <?= esc($kelas['deskripsi'] ?? 'Pelatihan dirancang untuk membekali peserta dengan pemahaman komprehensif.') ?>
                        </p>

                        <span class="badge bg-success">
                            Mentor: <?= esc($kelas['nama_mentor'] ?? '-') ?>
                        </span>

                    <?php else: ?>

                        <div class="alert alert-warning mb-0">
                            Anda belum terdaftar di kelas manapun.
                        </div>

                    <?php endif; ?>

                </div>

            </div>


            <!-- Nav Tabs -->
            <ul class="nav nav-tabs mb-4" id="kelasTab" role="tablist">

                <li class="nav-item" role="presentation">

                    <button class="nav-link active fw-semibold"
                            id="materi-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#materi"
                            type="button"
                            role="tab">

                        <i class="fa-solid fa-book me-1"></i>
                        Materi Pembelajaran

                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button class="nav-link fw-semibold"
                            id="absensi-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#absensi"
                            type="button"
                            role="tab">

                        <i class="fa-solid fa-calendar-check me-1"></i>
                        Absensi & Riwayat

                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button class="nav-link fw-semibold"
                            id="ujian-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#ujian"
                            type="button"
                            role="tab">

                        <i class="fa-solid fa-pen-to-square me-1"></i>
                        Ujian & Tugas

                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button class="nav-link fw-semibold"
                            id="sertifikat-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#sertifikat"
                            type="button"
                            role="tab">

                        <i class="fa-solid fa-award me-1"></i>
                        Sertifikat & Angket

                    </button>

                </li>

            </ul>


            <!-- Tab Content -->
            <div class="tab-content" id="kelasTabContent">


                <!-- ========================================= -->
                <!-- TAB 1 : MATERI -->
                <!-- ========================================= -->

                <div class="tab-pane fade show active"
                     id="materi"
                     role="tabpanel">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h5 class="fw-bold mb-3">
                                Daftar Modul & Materi Sesi
                            </h5>

                            <p class="text-muted mb-4">
                                Unduh atau pelajari modul materi yang telah diunggah oleh mentor.
                            </p>


                            <?php if (!empty($materi)): ?>

                                <div class="row g-4">

                                    <?php foreach ($materi as $item): ?>

                                        <?php
                                        /*
                                         * Cari jadwal yang menjadi pasangan materi ini.
                                         */
                                        $jadwalMateri = null;

                                        if (!empty($jadwal)) {
                                            foreach ($jadwal as $jadwalItem) {

                                                if (
                                                    isset($item['id_jadwal_kelas']) &&
                                                    isset($jadwalItem['id_jadwal_kelas']) &&
                                                    $item['id_jadwal_kelas'] == $jadwalItem['id_jadwal_kelas']
                                                ) {
                                                    $jadwalMateri = $jadwalItem;
                                                    break;
                                                }

                                            }
                                        }

                                        /*
                                         * Materi terbuka hanya jika peserta
                                         * sudah hadir pada pertemuan tersebut.
                                         */
                                        $materiTerbuka = false;

                                        if ($jadwalMateri) {
                                            $materiTerbuka = (
                                                ($jadwalMateri['absensi']['status'] ?? null) === 'hadir'
                                            );
                                        }
                                        ?>


                                        <div class="col-lg-4 col-md-6">

                                            <div class="materi-card h-100">

                                                <div class="materi-icon">
                                                    📚
                                                </div>


                                                <span class="materi-badge">

                                                    <?php if ($jadwalMateri): ?>

                                                        Pertemuan
                                                        <?= esc($jadwalMateri['pertemuan_ke'] ?? '-') ?>

                                                    <?php else: ?>

                                                        Materi Pembelajaran

                                                    <?php endif; ?>

                                                </span>


                                                <h5 class="fw-bold mt-3">

                                                    <?= esc($item['judul_materi'] ?? '-') ?>

                                                </h5>


                                                <p class="text-muted">

                                                    <?= esc(
                                                        $item['deskripsi']
                                                        ?? 'Tidak ada deskripsi materi.'
                                                    ) ?>

                                                </p>


                                                <div class="materi-footer">


                                                    <?php if ($materiTerbuka): ?>

                                                        <!-- Materi sudah terbuka -->

                                                        <span class="status-materi">

                                                            ✓ Materi tersedia

                                                        </span>


                                                        <?php if (!empty($item['file_materi'])): ?>

                                                            <a href="<?= base_url('pelatihan/materi/' . $item['id_materi_kelas']) ?>"
                                                               class="btn btn-primary">

                                                                Pelajari
                                                                <i class="bi bi-arrow-right"></i>

                                                            </a>

                                                        <?php else: ?>

                                                            <span class="text-muted small">

                                                                File belum tersedia

                                                            </span>

                                                        <?php endif; ?>


                                                    <?php else: ?>

                                                        <!-- Materi masih terkunci -->

                                                        <span class="status-materi-terkunci">

                                                            🔒 Absen terlebih dahulu

                                                        </span>


                                                        <button type="button"
                                                                class="btn btn-secondary"
                                                                disabled>

                                                            Terkunci
                                                            <i class="bi bi-lock-fill"></i>

                                                        </button>

                                                    <?php endif; ?>


                                                </div>

                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                </div>


                            <?php else: ?>

                                <div class="alert alert-info mb-0">

                                    <i class="bi bi-info-circle me-2"></i>

                                    Belum ada materi yang diunggah oleh mentor.

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>


              <!-- ========================================= -->
<!-- TAB 2 : ABSENSI -->
<!-- ========================================= -->

<div class="tab-pane fade"
     id="absensi"
     role="tabpanel">

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">
                Rekap Absensi Kehadiran
            </h5>

            <p class="mb-4">
                Total Pertemuan:
                <strong><?= $totalPertemuan ?></strong>
                |
                Hadir:
                <strong><?= $jumlahHadir ?></strong>
                (<?= $persentaseKehadiran ?>%)
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

                                <span class="materi-badge">
                                    Pertemuan
                                    <?= esc($item['pertemuan_ke'] ?? '-') ?>
                                </span>

                                <h5 class="fw-bold mt-3 mb-2">
                                    Pertemuan
                                    <?= esc($item['pertemuan_ke'] ?? '-') ?>
                                </h5>

                                <p class="text-muted mb-3">

                                    <i class="bi bi-calendar-event me-1"></i>

                                    <?= !empty($item['tanggal_kbm'])
                                        ? date('d F Y H:i', strtotime($item['tanggal_kbm']))
                                        : 'Jadwal belum ditentukan'
                                    ?>

                                </p>


                                <?php if ($statusAbsensi === 'hadir'): ?>

                                    <div class="alert alert-success mb-0">

                                        <i class="bi bi-check-circle-fill me-2"></i>

                                        Anda sudah hadir pada pertemuan ini.

                                    </div>


                                <?php else: ?>

                                    <div class="materi-footer">

                                        <span class="status-materi-terkunci">

                                            Belum melakukan absensi

                                        </span>


                                        <form action="<?= base_url('pelatihan/absensi/simpan') ?>"
                                              method="post">

                                            <input type="hidden"
                                                   name="id_jadwal_kelas"
                                                   value="<?= esc($item['id_jadwal_kelas']) ?>">

                                            <button type="submit"
                                                    class="btn btn-primary">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Absen Sekarang

                                            </button>

                                        </form>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>


            <?php else: ?>

                <div class="alert alert-info mb-0">

                    <i class="bi bi-info-circle me-2"></i>

                    Belum ada jadwal pertemuan.

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


             <!-- ========================================= -->
<!-- TAB 3 : UJIAN -->
<!-- ========================================= -->

<div class="tab-pane fade"
     id="ujian"
     role="tabpanel">

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">
                Ujian
            </h5>

            <p class="text-muted mb-4">
                Silakan download soal ujian dan kumpulkan jawaban dalam bentuk PDF.
            </p>


            <?php if (!empty($ujian)): ?>

                <?php foreach ($ujian as $item): ?>

                    <?php
                    // Cek apakah deadline sudah lewat
                    $deadlineLewat = false;

                    if (!empty($item['deadline'])) {
                        $deadlineLewat = strtotime($item['deadline']) < time();
                    }
                    ?>


                    <div class="materi-card mb-4">

                        <span class="materi-badge">
                            Ujian
                        </span>


                        <h5 class="fw-bold mt-3 mb-2">
                            <?= esc($item['judul_ujian'] ?? 'Ujian') ?>
                        </h5>


                        <p class="text-muted">
                            <?= esc(
                                $item['keterangan']
                                ?? 'Tidak ada keterangan ujian.'
                            ) ?>
                        </p>


                        <!-- DEADLINE -->

                        <?php if (!empty($item['deadline'])): ?>

                            <?php if ($deadlineLewat): ?>

                                <div class="alert alert-danger mb-3">

                                    <i class="bi bi-clock-history me-2"></i>

                                    <strong>Deadline pengumpulan sudah berakhir.</strong>

                                    <br>

                                    <small>
                                        Deadline:
                                        <?= date(
                                            'd F Y, H:i',
                                            strtotime($item['deadline'])
                                        ) ?>
                                    </small>

                                </div>

                            <?php else: ?>

                                <div class="alert alert-warning mb-3">

                                    <i class="bi bi-clock me-2"></i>

                                    <strong>Deadline Pengumpulan:</strong>

                                    <?= date(
                                        'd F Y, H:i',
                                        strtotime($item['deadline'])
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        <?php else: ?>

                            <div class="alert alert-warning mb-3">

                                <i class="bi bi-exclamation-circle me-2"></i>

                                Deadline pengumpulan belum ditentukan.

                            </div>

                        <?php endif; ?>


                        <!-- DOWNLOAD SOAL -->

                        <?php if (!empty($item['file_soal'])): ?>

                            <div class="materi-footer mb-3">

                                <span class="status-materi">

                                    ✓ Soal tersedia

                                </span>


                                <a href="<?= base_url('uploads/ujian/' . $item['file_soal']) ?>"
                                   target="_blank"
                                   class="btn btn-primary">

                                    <i class="bi bi-file-earmark-pdf me-1"></i>

                                    Download Soal

                                </a>

                            </div>

                        <?php else: ?>

                            <div class="alert alert-warning mb-3">

                                <i class="bi bi-exclamation-circle me-2"></i>

                                Soal ujian belum tersedia.

                            </div>

                        <?php endif; ?>


                        <!-- UPLOAD JAWABAN -->

                        <?php if (!empty($item['jawaban'])): ?>

                            <div class="alert alert-success mb-0">

                                <i class="bi bi-check-circle-fill me-2"></i>

                                <strong>Jawaban sudah dikumpulkan.</strong>

                                <br>

                                <small>
                                    Dikumpulkan pada:

                                    <?= !empty($item['jawaban']['waktu_kumpul'])
                                        ? date(
                                            'd F Y H:i',
                                            strtotime($item['jawaban']['waktu_kumpul'])
                                        )
                                        : '-'
                                    ?>
                                </small>

                            </div>


                        <?php elseif ($deadlineLewat): ?>

                            <div class="alert alert-danger mb-0">

                                <i class="bi bi-lock-fill me-2"></i>

                                Pengumpulan jawaban sudah ditutup karena
                                deadline telah berakhir.

                            </div>


                        <?php else: ?>

                            <form action="<?= base_url('pelatihan/ujian/simpan-jawaban') ?>"
                                  method="post"
                                  enctype="multipart/form-data">

                                <input type="hidden"
                                       name="id_ujian"
                                       value="<?= esc($item['id_ujian']) ?>">


                                <label class="form-label fw-semibold">

                                    Upload Jawaban

                                </label>


                                <input type="file"
                                       name="file_jawaban"
                                       class="form-control mb-3"
                                       accept=".pdf,application/pdf"
                                       required>


                                <button type="submit"
                                        class="btn btn-success">

                                    <i class="bi bi-upload me-1"></i>

                                    Kumpulkan Jawaban

                                </button>

                            </form>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>


            <?php else: ?>

                <div class="alert alert-info mb-0">

                    <i class="bi bi-info-circle me-2"></i>

                    Belum ada ujian yang tersedia.

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


                <!-- ========================================= -->
                <!-- TAB 4 : SERTIFIKAT -->
                <!-- ========================================= -->

                <div class="tab-pane fade"
                     id="sertifikat"
                     role="tabpanel">

                    <div class="card border-0 shadow-sm">

                        <div class="card-body p-4">

                            <h5 class="fw-bold mb-3">
                                2. Sertifikat Pelatihan
                            </h5>


                            <?php if ($sertifikatAcademy): ?>

                                <div class="alert alert-success">

                                    Selamat! Anda lulus dan dapat mengunduh sertifikat Anda.

                                </div>

                                <a href="<?= base_url('pelatihan/sertifikat') ?>"
                                   class="btn btn-success">

                                    Unduh Sertifikat

                                </a>

                            <?php else: ?>

                                <div class="alert alert-warning mb-0">

                                    Sertifikat belum dapat diunduh.
                                    Pastikan Anda lulus ujian
                                    (minimal nilai 70)
                                    dan sudah mengisi angket evaluasi.

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