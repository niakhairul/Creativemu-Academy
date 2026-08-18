<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Creativemu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6f42c1;
            --primary-light: #ebe5f7;
            --bg-color: #f6f5fa;
        }

        body { background-color: var(--bg-color); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; }

        .sidebar {
            width: 260px; height: 100vh; position: fixed; top: 0; left: 0;
            background-color: #ffffff; padding: 24px 16px; border-right: 1px solid #eaeaea;
        }

        .sidebar-brand { font-size: 1.35rem; font-weight: 700; color: var(--primary-color); margin-bottom: 32px; display: flex; align-items: center; gap: 10px; padding-left: 12px; }

        .nav-link-custom {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #6c757d;
            font-weight: 600; text-decoration: none; border-radius: 12px; margin-bottom: 6px; transition: all 0.2s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active { background-color: var(--primary-light); color: var(--primary-color); }

        .main-content { margin-left: 260px; padding: 40px; }
        .page-header h2 { font-weight: 700; color: var(--primary-color); margin-bottom: 4px; }
        .page-header p { color: #888; margin-bottom: 24px; }

        .card-custom {
            background: #ffffff; border-radius: 16px; border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); padding: 28px; margin-bottom: 24px;
        }

        .nav-pills .nav-link { color: #6c757d; font-weight: 600; border-radius: 10px; padding: 10px 20px; }
        .nav-pills .nav-link.active { background-color: var(--primary-color); color: #fff; }

        .table-custom { border-collapse: separate; border-spacing: 0; width: 100%; }
        .table-custom thead th { background-color: var(--primary-light); color: var(--primary-color); font-weight: 700; border: none; padding: 14px 16px; }
        .table-custom thead th:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .table-custom thead th:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
        .table-custom tbody td { padding: 16px; vertical-align: middle; border-bottom: 1px solid #f0f0f0; }

        @media print {
            .sidebar, .filter-section, .nav-pills, .btn-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            .card-custom { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-graduation-cap"></i> Creativemu
        </div>
        <nav class="nav flex-column">
            <a class="nav-link-custom" href="<?= base_url('admin/dashboard') ?>"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            <a class="nav-link-custom" href="<?= base_url('admin/master-kelas') ?>"><i class="fa-solid fa-book"></i> Master Kelas</a>
            <a class="nav-link-custom" href="<?= base_url('admin/data-peserta') ?>"><i class="fa-solid fa-users"></i> Data Peserta</a>
            <a class="nav-link-custom" href="<?= base_url('admin/validasi') ?>"><i class="fa-solid fa-user-check"></i> Validasi Pendaftaran</a>
            <a class="nav-link-custom" href="<?= base_url('admin/sertifikat') ?>"><i class="fa-solid fa-certificate"></i> Sertifikat</a>
            <a class="nav-link-custom active" href="<?= base_url('admin/laporan') ?>"><i class="fa-solid fa-file-lines"></i> Laporan</a>
            <a class="nav-link-custom" href="#"><i class="fa-solid fa-gear"></i> Pengaturan</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h2>Rekapitulasi Laporan</h2>
                <p>Pilih kategori laporan untuk melihat dan mencetak data rekapitulasi.</p>
            </div>
            <button onclick="window.print()" class="btn btn-outline-primary btn-print font-weight-bold">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </button>
        </div>

        <!-- Tab Pilihan Laporan -->
        <ul class="nav nav-pills mb-4 filter-section">
            <li class="nav-item">
                <a class="nav-link <?= ($jenis == 'peserta') ? 'active' : '' ?>" href="<?= base_url('admin/laporan?jenis=peserta') ?>">
                    <i class="fa-solid fa-user-graduate"></i> Laporan Peserta
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($jenis == 'mentor') ? 'active' : '' ?>" href="<?= base_url('admin/laporan?jenis=mentor') ?>">
                    <i class="fa-solid fa-chalkboard-user"></i> Laporan Mentor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($jenis == 'angket') ? 'active' : '' ?>" href="<?= base_url('admin/laporan?jenis=angket') ?>">
                    <i class="fa-solid fa-poll"></i> Laporan Angket / Evaluasi
                </a>
            </li>
        </ul>

        <!-- Filter Periode -->
        <div class="card-custom filter-section">
            <form action="<?= base_url('admin/laporan') ?>" method="get" class="row g-3 align-items-end">
                <input type="hidden" name="jenis" value="<?= esc($jenis) ?>">
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="<?= esc($tglMulai) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="<?= esc($tglSelesai) ?>">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100" style="background-color: var(--primary-color); border: none;">
                        <i class="fa-solid fa-filter"></i> Filter Data
                    </button>
                    <a href="<?= base_url('admin/laporan?jenis=' . $jenis) ?>" class="btn btn-light w-100 border">Reset</a>
                </div>
            </form>
        </div>

        <!-- Tabel Laporan Dynamic -->
        <div class="card-custom">
            
            <?php if ($jenis == 'peserta'): ?>
                <h5 class="fw-bold mb-3 text-purple" style="color: var(--primary-color);"><i class="fa-solid fa-users"></i> Data Laporan Peserta</h5>
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Peserta</th>
                            <th>Kelas</th>
                            <th>Tanggal Daftar</th>
                            <th>Status Validasi</th>
                            <th>No. Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($laporanData)): ?>
                            <?php $no = 1; foreach ($laporanData as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($row['nama'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($row['email'] ?? '-') ?></small></td>
                                    <td><?= esc($row['nama_kelas'] ?? '-') ?></td>
                                    <td><?= !empty($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : '-' ?></td>
                                    <td><span class="badge bg-success"><?= esc($row['status_validasi'] ?? 'Menunggu') ?></span></td>
                                    <td><?= esc($row['no_sertifikat'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Data peserta belum tersedia.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php elseif ($jenis == 'mentor'): ?>
                <h5 class="fw-bold mb-3" style="color: var(--primary-color);"><i class="fa-solid fa-chalkboard-user"></i> Data Laporan Mentor</h5>
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Mentor</th>
                            <th>Email / Kontak</th>
                            <th>Mengajar Kelas</th>
                            <th>Keahlian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($laporanData)): ?>
                            <?php $no = 1; foreach ($laporanData as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($row['nama_mentor'] ?? $row['nama'] ?? '-') ?></strong></td>
                                    <td><?= esc($row['email'] ?? '-') ?></td>
                                    <td><?= esc($row['nama_kelas'] ?? '-') ?></td>
                                    <td><?= esc($row['keahlian'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Data mentor belum tersedia.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            <?php elseif ($jenis == 'angket'): ?>
                <h5 class="fw-bold mb-3" style="color: var(--primary-color);"><i class="fa-solid fa-poll"></i> Hasil Evaluasi / Angket</h5>
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Peserta</th>
                            <th>Kelas</th>
                            <th>Rating</th>
                            <th>Saran / Masukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($laporanData)): ?>
                            <?php $no = 1; foreach ($laporanData as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($row['nama_peserta'] ?? '-') ?></strong></td>
                                    <td><?= esc($row['nama_kelas'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fa-solid fa-star"></i> <?= esc($row['rating'] ?? '5') ?> / 5
                                        </span>
                                    </td>
                                    <td><?= esc($row['saran'] ?? $row['pesan'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Data angket/evaluasi belum tersedia.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>