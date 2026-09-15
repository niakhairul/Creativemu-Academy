<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi KBM Peserta - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-purple: #7c3aed;
            --primary-dark: #581c87;
            --pastel-purple-light: #f5f3ff;
            --pastel-purple-subtle: #ede9fe;
            --pastel-purple-border: #ddd6fe;
            --text-dark: #1e1b4b;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf5ff;
            background-image: radial-gradient(#d8b4fe 1px, transparent 1px);
            background-size: 24px 24px;
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #581c87 0%, #7c3aed 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 24px 20px;
            box-shadow: 4px 0 20px rgba(124, 58, 237, 0.15);
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
            margin-bottom: 24px;
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
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu a i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
        }

        @media (max-width: 991px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }
            .app-wrapper {
                flex-direction: column;
            }
        }

        .header-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid var(--pastel-purple-border);
            box-shadow: 0 4px 20px rgba(124, 58, 237, 0.06);
            margin-bottom: 24px;
        }

        .absensi-card {
            background: #ffffff;
            border: 1px solid #e9d5ff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(108, 63, 200, 0.04);
            transition: all 0.25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .absensi-card:hover {
            transform: translateY(-3px);
            border-color: #c084fc;
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.10);
        }

        .pertemuan-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 50px;
            background: #ede9fe;
            color: #6b21a8;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-row i {
            color: var(--primary-purple);
            font-size: 16px;
            margin-right: 10px;
            margin-top: 2px;
            width: 18px;
            text-align: center;
        }

        .info-label {
            color: var(--text-muted);
            min-width: 130px;
            font-weight: 500;
        }

        .info-value {
            color: var(--text-dark);
            font-weight: 600;
            flex: 1;
        }

        .btn-hadir {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 18px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .btn-hadir:hover {
            background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(124, 58, 237, 0.35);
        }

        .btn-tidak-hadir {
            background: #fff;
            color: #dc2626;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 9px 18px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-tidak-hadir:hover {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #f87171;
        }

        .badge-status {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-status-hadir {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }

        .badge-status-tidakhadir {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .badge-status-belum {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .gps-loading-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
        }

        .spinner-purple {
            width: 3.5rem;
            height: 3.5rem;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: #c084fc;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- GPS Detection Loading Overlay -->
<div id="gpsLoadingOverlay" class="gps-loading-overlay">
    <div class="spinner-purple mb-3"></div>
    <h5 class="fw-bold mb-1">Mendeteksi Lokasi GPS Anda...</h5>
    <p class="text-light opacity-75 small">Pastikan izin akses lokasi (GPS) diaktifkan pada browser Anda.</p>
</div>

<div class="app-wrapper">

    <!-- Sidebar -->
    <nav class="sidebar">
        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4"></i>
            Creativemu
        </a>

        <ul class="sidebar-menu">
            <li>
                <a href="<?= base_url('peserta/dashboard') ?>">
                    <i class="bi bi-grid-fill"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>">
                    <i class="bi bi-journals"></i>
                    Daftar Kelas Saya
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/kbm') ?>">
                    <i class="bi bi-mortarboard-fill"></i>
                    KBM
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/absensi') ?>" class="active">
                    <i class="bi bi-calendar-check-fill"></i>
                    Absensi
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/ujian') ?>">
                    <i class="bi bi-file-earmark-text-fill"></i>
                    Ujian
                </a>
            </li>
            <li>
                <a href="<?= base_url('pelatihan/pengaturan') ?>">
                    <i class="bi bi-gear-fill"></i>
                    Pengaturan
                </a>
            </li>
            <li class="mt-4">
                <a href="<?= base_url('auth/logout') ?>" class="text-danger-subtle bg-danger bg-opacity-10">
                    <i class="bi bi-box-arrow-left"></i>
                    Keluar
                </a>
            </li>
        </ul>
    </nav>

    <!-- Konten Utama -->
    <div class="main-content">

        <!-- Top Header Card -->
        <div class="header-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <span class="badge bg-purple text-white mb-2" style="background-color: var(--primary-purple);">
                        <i class="bi bi-mortarboard-fill me-1"></i> Hak Akses Peserta → KBM → Absen
                    </span>
                    <h3 class="fw-bold mb-1" style="color: var(--primary-dark);">
                        <?= esc($pendaftaran['nama_kelas'] ?? 'Kelas Pelatihan') ?>
                    </h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        Tempat Pelatihan: <strong><?= esc($pendaftaran['lokasi_pelatihan'] ?? $lokasiInfo['nama_lokasi']) ?></strong>
                        <?php if (!empty($lokasiInfo['is_online'])): ?>
                            <span class="badge bg-info text-dark ms-2"><i class="bi bi-globe me-1"></i> Kelas Online</span>
                        <?php else: ?>
                            <span class="badge bg-secondary ms-2"><i class="bi bi-building me-1"></i> Kelas Offline (Radius Maks: <?= esc($lokasiInfo['radius_meter'] ?? 100) ?>m)</span>
                        <?php endif; ?>
                    </p>


                    <?php if (session()->getFlashdata('success')): ?>

                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>

                    <?php endif; ?>


                    <?php if (session()->getFlashdata('error')): ?>

                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>

                    <?php endif; ?>


                    <?php if (!empty($jadwal)): ?>

                        <div class="row g-4">

                            <?php foreach ($jadwal as $item): ?>

                                <?php
                                $absensi = $item['absensi'] ?? null;
                                ?>

                                <div class="col-lg-6">

                                    <div class="absensi-card h-100">

                                        <span class="pertemuan-badge">
                                            Pertemuan
                                            <?= esc($item['pertemuan_ke'] ?? '-') ?>
                                        </span>


                                        <h5 class="fw-bold mt-3 mb-2">
                                            Pertemuan
                                            <?= esc($item['pertemuan_ke'] ?? '-') ?>
                                        </h5>


                                        <p class="text-muted mb-2">

                                            <i class="bi bi-calendar-event me-1"></i>

                                            <?= !empty($item['tanggal_kbm'])
                                                ? date('d F Y H:i', strtotime($item['tanggal_kbm']))
                                                : '-'
                                            ?>

                                        </p>


                                        <?php if (!empty($absensi)): ?>

                                            <?php if (($absensi['status'] ?? '') === 'hadir'): ?>

                                                <div class="alert alert-success mb-0">

                                                    <i class="bi bi-check-circle-fill me-2"></i>

                                                    Anda sudah hadir pada pertemuan ini.

                                                </div>

                                            <?php else: ?>

                                                <div class="alert alert-warning mb-0">

                                                    Status:
                                                    <strong>
                                                        <?= esc(ucfirst($absensi['status'] ?? '-')) ?>
                                                    </strong>

                                                </div>

                                            <?php endif; ?>

                                        <?php else: ?>

                                            <?php if (!empty($item['absensi_dibuka']) && $item['absensi_dibuka'] == 1): ?>

                                                <form action="<?= base_url('pelatihan/prosesAbsen') ?>" method="POST" class="mt-3">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="id_jadwal" value="<?= esc($item['id_jadwal'] ?? $item['id']) ?>">
                                                    
                                                    <div class="input-group">
                                                        <input type="text" name="token" class="form-control" placeholder="Masukkan Token Absensi" required>
                                                        <button class="btn btn-primary" type="submit">
                                                            <i class="bi bi-check2-square me-1"></i> Kirim Absen
                                                        </button>
                                                    </div>
                                                </form>

                                            <?php else: ?>

                                                <div class="alert alert-secondary mb-0">
                                                    <i class="bi bi-clock me-2"></i> Absensi belum dibuka oleh mentor.
                                                </div>

                                            <?php endif; ?>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php else: ?>

                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x fs-1 mb-3 d-block"></i>
                            Belum ada jadwal pertemuan yang tersedia.
                        </div>

                    <?php endif; ?>


                </div>

                <div class="d-flex gap-2">
                    <a href="<?= base_url('pelatihan/riwayat-absensi') ?>" class="btn btn-outline-purple btn-sm py-2 px-3 border-secondary-subtle">
                        <i class="bi bi-clock-history me-1"></i> Riwayat Absensi
                    </a>
                    <a href="<?= base_url('pelatihan/kbm') ?>" class="btn btn-light btn-sm py-2 px-3 border">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke KBM
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 py-3 px-4 mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                <div>
                    <strong class="d-block mb-1">Berhasil!</strong>
                    <span><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 py-3 px-4 mb-4 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                <div>
                    <strong class="d-block mb-1">Perhatian!</strong>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Tersembunyi untuk Submit Absen -->
        <form id="attendanceForm" action="<?= base_url('pelatihan/absensi/simpan') ?>" method="POST" style="display: none;">
            <?= csrf_field() ?>
            <input type="hidden" name="id_jadwal" id="formIdJadwal" value="">
            <input type="hidden" name="status_absen" id="formStatusAbsen" value="">
            <input type="hidden" name="latitude" id="formLatitude" value="">
            <input type="hidden" name="longitude" id="formLongitude" value="">
            <input type="hidden" name="gps_error" id="formGpsError" value="">
        </form>

        <!-- Daftar Jadwal / Pertemuan -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3" style="color: var(--primary-dark);">
                <i class="bi bi-calendar-range-fill me-2 text-primary"></i>
                Jadwal & Presensi Pertemuan
            </h5>

            <?php if (!empty($jadwal)): ?>
                <div class="row g-4">
                    <?php foreach ($jadwal as $item): ?>
                        <?php
                            $absensi = $item['absensi'] ?? null;
                            $sudahAbsen = !empty($absensi);
                            $status = strtolower($absensi['status'] ?? '');
                            $idJadwal = $item['id_jadwal'] ?? $item['id_jadwal_kelas'];
                        ?>
                        <div class="col-lg-6">
                            <div class="absensi-card h-100">
                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="pertemuan-pill">
                                            <i class="bi bi-bookmark-check-fill me-1"></i>
                                            Pertemuan <?= esc($item['pertemuan_ke'] ?? '-') ?>
                                        </span>

                                        <!-- Status Absensi Badge -->
                                        <?php if ($sudahAbsen): ?>
                                            <?php if ($status === 'hadir'): ?>
                                                <span class="badge-status badge-status-hadir">
                                                    <i class="bi bi-check-circle-fill"></i> Hadir
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-status badge-status-tidakhadir">
                                                    <i class="bi bi-x-circle-fill"></i> <?= esc(ucwords($status)) ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge-status badge-status-belum">
                                                <i class="bi bi-hourglass-split"></i> Belum Absen
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <h5 class="fw-bold mb-3" style="color: #3b0764;">
                                        <?= esc($item['materi'] ?: ('Materi Pertemuan Ke-' . ($item['pertemuan_ke'] ?? ''))) ?>
                                    </h5>

                                    <hr class="opacity-25 mb-3">

                                    <!-- Detail Informasi Pertemuan -->
                                    <div class="info-row">
                                        <i class="bi bi-mortarboard"></i>
                                        <span class="info-label">Nama Kelas</span>
                                        <span class="info-value">: <?= esc($pendaftaran['nama_kelas'] ?? 'CreativeMU Academy') ?></span>
                                    </div>

                                    <div class="info-row">
                                        <i class="bi bi-journal-text"></i>
                                        <span class="info-label">Materi / Sesi</span>
                                        <span class="info-value">: <?= esc($item['materi'] ?: ('Pertemuan ' . ($item['pertemuan_ke'] ?? '-'))) ?></span>
                                    </div>

                                    <div class="info-row">
                                        <i class="bi bi-calendar3"></i>
                                        <span class="info-label">Tanggal</span>
                                        <span class="info-value">:
                                            <?= !empty($item['tanggal_kbm'])
                                                ? date('d F Y', strtotime($item['tanggal_kbm']))
                                                : '-'
                                            ?>
                                        </span>
                                    </div>

                                    <div class="info-row">
                                        <i class="bi bi-clock"></i>
                                        <span class="info-label">Jam</span>
                                        <span class="info-value">:
                                            <?= !empty($item['waktu_mulai'])
                                                ? date('H:i', strtotime($item['waktu_mulai'])) . ' - ' . date('H:i', strtotime($item['waktu_selesai'] ?? $item['waktu_mulai'])) . ' WIB'
                                                : 'Sesuai Jadwal'
                                            ?>
                                        </span>
                                    </div>

                                    <div class="info-row">
                                        <i class="bi bi-geo-alt"></i>
                                        <span class="info-label">Tempat Pelatihan</span>
                                        <span class="info-value">: <?= esc($pendaftaran['lokasi_pelatihan'] ?? $lokasiInfo['nama_lokasi']) ?></span>
                                    </div>
                                </div>

                                <!-- Bagian Aksi / Status Hasil Absen -->
                                <div class="mt-4 pt-3 border-top">
                                    <?php if ($sudahAbsen): ?>
                                        <div class="alert alert-light border rounded-3 p-3 mb-0 text-center">
                                            <div class="text-success fw-bold mb-1">
                                                <i class="bi bi-shield-check me-1"></i> Anda sudah melakukan absensi.
                                            </div>
                                            <small class="text-muted d-block">
                                                Status: <strong><?= esc(ucwords($status)) ?></strong>
                                                <?php if (!empty($absensi['waktu_absen'])): ?>
                                                    • Waktu: <?= date('d M Y, H:i', strtotime($absensi['waktu_absen'])) ?> WIB
                                                <?php endif; ?>
                                                <?php if (isset($absensi['jarak']) && $absensi['jarak'] !== null): ?>
                                                    • Jarak: <?= (int)$absensi['jarak'] ?> m
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex gap-2 justify-content-end">
                                            <!-- Tombol Tidak Hadir -->
                                            <button type="button"
                                                    class="btn-tidak-hadir"
                                                    onclick="submitTidakHadir(<?= esc($idJadwal) ?>, <?= esc($item['pertemuan_ke']) ?>)">
                                                <i class="bi bi-x-circle me-1"></i> Tidak Hadir
                                            </button>

                                            <!-- Tombol Hadir (Trigger GPS) -->
                                            <button type="button"
                                                    class="btn-hadir"
                                                    onclick="submitHadirGPS(<?= esc($idJadwal) ?>, <?= !empty($lokasiInfo['is_online']) ? 'true' : 'false' ?>)">
                                                <i class="bi bi-geo-alt-fill me-1"></i> Hadir
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 3.5rem;"></i>
                    <h5 class="fw-bold mt-3 mb-1">Belum Ada Jadwal Pertemuan</h5>
                    <p class="text-muted mb-0">Jadwal pertemuan untuk kelas ini belum dibuka oleh mentor.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<script>
    const form = document.getElementById('attendanceForm');
    const formIdJadwal = document.getElementById('formIdJadwal');
    const formStatusAbsen = document.getElementById('formStatusAbsen');
    const formLatitude = document.getElementById('formLatitude');
    const formLongitude = document.getElementById('formLongitude');
    const formGpsError = document.getElementById('formGpsError');
    const loadingOverlay = document.getElementById('gpsLoadingOverlay');

    function showLoading() {
        loadingOverlay.style.display = 'flex';
    }

    function hideLoading() {
        loadingOverlay.style.display = 'none';
    }

    // Submit pilihan TIDAK HADIR
    function submitTidakHadir(idJadwal, pertemuanKe) {
        if (!confirm('Apakah Anda yakin memilih status TIDAK HADIR pada Pertemuan ' + pertemuanKe + '?')) {
            return;
        }
        formIdJadwal.value = idJadwal;
        formStatusAbsen.value = 'tidak hadir';
        formLatitude.value = '';
        formLongitude.value = '';
        formGpsError.value = '';
        form.submit();
    }

    // Submit pilihan HADIR dengan verifikasi GPS
    function submitHadirGPS(idJadwal, isOnline) {
        formIdJadwal.value = idJadwal;
        formStatusAbsen.value = 'hadir';

        // Jika kelas online, langsung kirim tanpa validasi koordinat fisik
        if (isOnline) {
            formLatitude.value = '0';
            formLongitude.value = '0';
            formGpsError.value = '';
            form.submit();
            return;
        }

        // Kelas Offline: Meminta izin GPS browser
        if (!navigator.geolocation) {
            alert('Perangkat atau browser Anda tidak mendukung fitur Geolocation GPS.');
            formLatitude.value = '';
            formLongitude.value = '';
            formGpsError.value = '1';
            form.submit();
            return;
        }

        showLoading();

        navigator.geolocation.getCurrentPosition(
            function(position) {
                hideLoading();
                formLatitude.value = position.coords.latitude;
                formLongitude.value = position.coords.longitude;
                formGpsError.value = '';
                form.submit();
            },
            function(error) {
                hideLoading();
                // Jika izin GPS ditolak atau gagal
                formLatitude.value = '';
                formLongitude.value = '';
                formGpsError.value = '1';
                form.submit();
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    }
</script>

</body>
</html>