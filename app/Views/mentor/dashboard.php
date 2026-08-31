<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bs-purple-custom: #6366f1; /* Indigo/Ungu Medium yang elegan dan hidup */
            --bs-purple-hover: #4f46e5;
            --bs-purple-light: #e0e7ff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
        }

        .card-stat {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-stat:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.15);
        }

        .bg-purple-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
        }

        .btn-purple {
            background-color: var(--bs-purple-custom);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-purple:hover {
            background-color: var(--bs-purple-hover);
            color: #fff;
        }

        .table-custom th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }

        .badge-purple {
            background-color: var(--bs-purple-light);
            color: #4338ca;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <!-- Navbar Atas -->
    <nav class="navbar navbar-dark navbar-custom py-3 mb-5">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="#">
                <i class="bi bi-mortarboard-fill me-2"></i> Portal Mentor
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= base_url('mentor/profil') ?>" class="btn btn-light btn-sm rounded-pill px-3 fw-semibold text-primary">
                    <i class="bi bi-person-circle me-1"></i> Profil
                </a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Sambutan -->
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="fw-bold text-dark mb-1">Halo, <?= esc(session()->get('nama')); ?>! 👋</h2>
                <p class="text-muted mb-0">Berikut adalah ringkasan aktivitas mengajar dan statistik kelas Anda hari ini.</p>
            </div>
        </div>

        <!-- Kartu Statistik -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card card-stat bg-purple-gradient text-white p-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase tracking-wider opacity-75 fw-semibold mb-1">Total Kelas Diampu</h6>
                            <h2 class="display-5 fw-bold mb-0"><?= $total_kelas ?? 0; ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 p-3 rounded-4">
                            <i class="bi bi-journal-bookmark-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-stat bg-white border p-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase tracking-wider text-muted fw-semibold mb-1">Total Peserta Aktif</h6>
                            <h2 class="display-5 fw-bold text-dark mb-0"><?= $total_peserta ?? 0; ?></h2>
                        </div>
                        <div class="p-3 rounded-4" style="background-color: var(--bs-purple-light); color: var(--bs-purple-custom);">
                            <i class="bi bi-people-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Pintasan Cepat -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-2">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3" style="background-color: var(--bs-purple-light); color: var(--bs-purple-custom);">
                        <i class="bi bi-grid-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Kelola Seluruh Kelas</h5>
                        <p class="text-muted mb-0 small">Akses daftar kelas yang Anda pegang untuk mulai mengajar.</p>
                    </div>
                </div>
                <a href="<?= base_url('mentor/kelas') ?>" class="btn btn-purple shadow-sm">
                    <i class="bi bi-arrow-right-circle me-1"></i> Buka Daftar Kelas
                </a>
            </div>
        </div>

        <!-- Tabel Jadwal Harian -->
        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-purple-light text-primary p-2 rounded-3 me-2">
                        <i class="bi bi-calendar-check-fill fs-5" style="color: var(--bs-purple-custom);"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Jadwal Mengajar Hari Ini</h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0 rounded-3 overflow-hidden">
                        <thead>
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Kelas</th>
                                <th>Waktu</th>
                                <th>Ruangan / Link</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($jadwal_harian)): ?>
                                <?php $no = 1; foreach ($jadwal_harian as $j): ?>
                                    <tr>
                                        <td class="ps-4 fw-semibold text-secondary"><?= $no++; ?></td>
                                        <td>
                                            <span class="fw-bold text-dark"><?= esc($j['nama_kelas'] ?? '-'); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-purple px-2 py-1"><?= esc($j['waktu'] ?? '-'); ?></span>
                                        </td>
                                        <td class="text-muted"><?= esc($j['ruangan'] ?? '-'); ?></td>
                                        <td class="text-center pe-4">
                                            <a href="<?= base_url('mentor/kelas/detail/' . $j['id_kelas']) ?>" class="btn btn-sm btn-light text-primary fw-semibold border shadow-sm px-3">
                                                <i class="bi bi-eye-fill me-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
                                        Tidak ada jadwal mengajar untuk hari ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
