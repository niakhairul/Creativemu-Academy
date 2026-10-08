<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
        }

        html, body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            color: #2b263b;
            overflow-x: hidden;
            margin: 0;
            font-size: 14px;
        }

        .top-navbar {
            background: #ffffff;
            padding: 16px 24px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .top-navbar h4 {
            font-size: 1.25rem;
        }

        .creative-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.05);
            margin-bottom: 20px;
        }

        .btn-creative-primary, .btn-excel, .btn-print {
            padding: 7px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.82rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-creative-primary {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            box-shadow: 0 3px 10px rgba(121, 75, 196, 0.25);
        }

        .btn-excel {
            background: #107c41;
            color: #ffffff;
            border: none;
        }

        .btn-print {
            background: #475569;
            color: #ffffff;
            border: none;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background-color: #22133c;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 10px 12px;
            border: none;
            white-space: nowrap;
        }

        .table-custom thead th:first-child { border-top-left-radius: 10px; }
        .table-custom thead th:last-child { border-top-right-radius: 10px; }

        .table-custom tbody td {
            padding: 10px 12px;
            font-size: 0.78rem;
            border-bottom: 1px solid #f0ecfa;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-custom tbody td.kolom-alamat,
        .table-custom tbody td.kolom-lokasi {
            white-space: normal !important;
            max-width: 180px;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .table-custom tbody tr:nth-of-type(even) { background-color: #fbf9fe; }
        .table-custom tbody tr:hover { background-color: #f3ecfb; }

        .badge-status {
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.72rem;
            display: inline-block;
        }

        .badge-selesai { background: #eafaf1; color: #2e7d32; }
        .badge-menunggu { background: #fef8e7; color: #d97706; }
        .badge-ditolak { background: #fee2e2; color: #dc2626; }
        .badge-aktif { background: #ede9fe; color: #6d28d9; }

        .nis-tag {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            background: #f1ecfa;
            color: #5931a0;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.78rem;
        }

        .mobile-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--dark-purple);
            margin-right: 12px;
        }

        #main-content {
            margin-left: 240px;
            padding: 20px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        @media (max-width: 992px) {
            #main-content {
                margin-left: 70px;
                padding: 15px;
            }
            .mobile-toggle-btn { display: inline-block !important; }
        }

        @media (max-width: 576px) {
            #main-content { padding: 10px; }
        }
        @media (max-width: 768px) {
            .top-navbar { flex-direction: column; align-items: stretch; gap: 12px; }
            .top-navbar .d-flex { justify-content: flex-start; }
            .btn-excel, .btn-print { flex: 1; text-align: center; justify-content: center; }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- SIDEBAR -->
    <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

    <!-- KONTEN UTAMA -->
    <div id="main-content">
        
        <div class="top-navbar d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-toggle-btn d-lg-none" onclick="toggleSidebar()" style="background: none; border: none; font-size: 1.3rem; color: var(--dark-purple);"><i class="fas fa-bars"></i></button>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: var(--dark-purple); font-size: 1.25rem;">Buku Induk Peserta</h4>
                    <p class="text-muted small mb-0" style="font-size: 0.75rem;">Dokumentasi data induk, status sertifikasi, dan riwayat pendaftaran peserta</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <?php $exportQuery = http_build_query($filters); ?>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('admin/buku-induk/export-excel?' . $exportQuery); ?>" class="btn btn-excel btn-sm" target="_blank" style="background: #107c41; color: #fff; padding: 7px 14px; border-radius: 8px; font-weight: 600; font-size: 0.82rem; text-decoration: none;">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="<?= base_url('admin/buku-induk/cetak?' . $exportQuery); ?>" class="btn btn-print btn-sm" target="_blank" style="background: #475569; color: #fff; padding: 7px 14px; border-radius: 8px; font-weight: 600; font-size: 0.82rem; text-decoration: none;">
                        <i class="fas fa-print"></i> Cetak
                    </a>
                </div>
                
                <?php
                $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
                ?>
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light border" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
                    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
                </div>

                <div class="admin-profile d-flex align-items-center gap-2">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-purple);">
                    <div class="admin-info d-none d-sm-block">
                        <h6 class="mb-0 fw-bold" style="font-size: 0.85rem; color: var(--dark-purple);"><?= esc(session()->get('nama')); ?></h6>
                        <small class="text-muted" style="font-size: 0.7rem;">Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER CARD -->
        <div class="creative-card mb-3">
            <form action="<?= base_url('admin/buku-induk'); ?>" method="get" id="filterForm">
                <!-- Baris Pertama Filter (5 Kolom Sejajar) -->
                <div class="row g-3 align-items-end">
                    <div class="col-lg col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['tahun'] === 'all') ? 'selected' : ''; ?>>Semua Tahun</option>
                            <?php foreach ($filterYears as $yr): ?>
                                <option value="<?= $yr; ?>" <?= ((string)$filters['tahun'] === (string)$yr) ? 'selected' : ''; ?>><?= $yr; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['bulan'] === 'all') ? 'selected' : ''; ?>>Semua Bulan</option>
                            <?php 
                                $namaBulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                                foreach ($namaBulan as $num => $nm): 
                            ?>
                                <option value="<?= $num; ?>" <?= ((string)$filters['bulan'] === (string)$num) ? 'selected' : ''; ?>><?= $nm; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Kelas</label>
                        <select name="id_kelas" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['id_kelas'] === 'all') ? 'selected' : ''; ?>>Semua Kelas</option>
                            <?php foreach ($filterClasses as $k): ?>
                                <option value="<?= $k['id_kelas']; ?>" <?= ((string)$filters['id_kelas'] === (string)$k['id_kelas']) ? 'selected' : ''; ?>><?= esc($k['nama_kelas']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Kategori</label>
                        <select name="kategori" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['kategori'] === 'all') ? 'selected' : ''; ?>>Semua Kategori</option>
                            <?php foreach ($filterCategories as $kat): ?>
                                <option value="<?= esc($kat); ?>" <?= ($filters['kategori'] === $kat) ? 'selected' : ''; ?>><?= esc($kat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <label class="form-label mb-1 small fw-bold text-muted">Sertifikat</label>
                        <select name="status_sertifikat" class="form-select form-select-sm">
                            <option value="all" <?= ($filters['status_sertifikat'] === 'all') ? 'selected' : ''; ?>>Semua Status</option>
                            <option value="Selesai" <?= ($filters['status_sertifikat'] === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Menunggu" <?= ($filters['status_sertifikat'] === 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                        </select>
                    </div>
                </div>

                <!-- Baris Kedua: Pencarian & Tombol Aksi -->
                <div class="row g-2 mt-3 align-items-center">
                    <div class="col-lg-8 col-md-7">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="keyword" class="form-control border-start-0" placeholder="Cari berdasarkan NIS, Nama Peserta, WhatsApp, Alamat..." value="<?= esc($filters['keyword']); ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5 d-flex gap-2 justify-content-md-end">
                        <button type="submit" class="btn btn-creative-primary btn-sm px-3 flex-fill flex-md-grow-0">
                            <i class="fas fa-filter"></i> Terapkan
                        </button>
                        <a href="<?= base_url('admin/buku-induk'); ?>" class="btn btn-light btn-sm border px-3 flex-fill flex-md-grow-0">
                            <i class="fas fa-rotate-left"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- TABEL BUKU INDUK -->
        <div class="creative-card p-0 overflow-hidden">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom bg-light">
                <div class="fw-bold small" style="color: var(--dark-purple);">
                    <i class="fas fa-table me-2 text-primary"></i> Rekap Data Buku Induk (Total: <?= number_format($totalData); ?> Peserta)
                </div>
                <div class="text-muted small" style="font-size: 0.75rem;">
                    Halaman <?= $currentPage; ?> dari <?= $totalPages; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 35px;">No</th>
                            <th>NIS</th>
                            <th>Nama Peserta</th>
                            <th>Tanggal Masuk</th>
                            <th>Selesai</th>
                            <th>Status</th>
                            <th>Sertifikat</th>
                            <th>Kategori Kelas</th>
                            <th>Pilihan Kelas</th>
                            <th>Metode</th>
                            <th>Jenis Kelas</th>
                            <th>Lokasi Pelatihan</th>
                            <th>Pendidikan Terakhir</th>
                            <th>Status Peserta</th>
                            <th>No. WhatsApp</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat, Tgl Lahir</th>
                            <th>Alamat</th>
                            <th class="text-center" style="min-width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pesertaList)): ?>
                            <tr>
                                <td colspan="19" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 text-secondary d-block"></i>
                                    <strong>Tidak ada data peserta yang sesuai dengan filter pencarian.</strong>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                                $nomor = ($currentPage - 1) * $perPage + 1;
                                foreach ($pesertaList as $item): 
                            ?>
                                <tr>
                                    <td class="text-center fw-semibold text-muted"><?= $nomor++; ?></td>
                                    <td><span class="nis-tag"><?= esc($item['nis']); ?></span></td>
                                    <td class="fw-bold" style="color: var(--dark-purple);"><?= esc($item['nama_peserta']); ?></td>
                                    
                                    <!-- Tanggal Masuk -->
                                    <td>
                                        <i class="fas fa-calendar-alt text-muted me-1 small"></i>
                                        <?php 
                                            $tglMasuk = !empty($item['tanggal_masuk']) && $item['tanggal_masuk'] !== '-' ? $item['tanggal_masuk'] : ($item['created_at'] ?? '');
                                            if (!empty($tglMasuk) && $tglMasuk !== '0000-00-00' && $tglMasuk !== '0000-00-00 00:00:00') {
                                                echo date('d/m/Y', strtotime($tglMasuk));
                                            } else {
                                                echo '-';
                                            }
                                        ?>
                                    </td>

                                    <!-- Tanggal Selesai -->
                                    <td>
                                        <?php 
                                            $isSelesai = (strtolower($item['status_peserta'] ?? '') === 'selesai' || strtolower($item['status_sertifikat'] ?? '') === 'selesai');
                                            $tglSelesai = $item['tanggal_selesai_kelas'] ?? '';

                                            if ($isSelesai && !empty($tglSelesai) && $tglSelesai !== '-' && $tglSelesai !== '0000-00-00') {
                                                echo date('d/m/Y', strtotime($tglSelesai));
                                            } else {
                                                echo '<span class="text-muted fw-bold">-</span>';
                                            }
                                        ?>
                                    </td>

                                    <td>
                                        <span class="badge-status badge-aktif"><?= ucfirst(esc($item['status_peserta'])); ?></span>
                                    </td>
                                    <td>
                                        <?php if ($item['status_sertifikat'] === 'Selesai'): ?>
                                            <span class="badge-status badge-selesai"><i class="fas fa-check-circle me-1"></i> Selesai</span>
                                        <?php else: ?>
                                            <span class="badge-status badge-menunggu"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge bg-light text-dark border" style="font-size: 0.7rem;"><?= esc($item['kategori_kelas']); ?></span></td>
                                    <td><?= esc($item['pilihan_kelas']); ?></td>
                                    <td>
                                        <span class="badge <?= (strtolower($item['metode']) === 'online') ? 'bg-info text-dark' : 'bg-primary'; ?> bg-opacity-10 text-primary fw-bold" style="font-size: 0.7rem;">
                                            <?= ucfirst(esc($item['metode'])); ?>
                                        </span>
                                    </td>
                                    <td><?= ucfirst(esc($item['jenis_kelas'])); ?></td>
                                    <td class="kolom-lokasi"><?= esc($item['lokasi_pelatihan']); ?></td>
                                    <td><?= esc($item['pendidikan_terakhir']); ?></td>
                                    <td>
                                        <span class="badge-status badge-aktif"><?= ucfirst(esc($item['status_peserta'])); ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($item['no_whatsapp']) && $item['no_whatsapp'] !== '-'): ?>
                                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $item['no_whatsapp']); ?>" target="_blank" class="text-success text-decoration-none fw-semibold">
                                                <i class="fab fa-whatsapp me-1"></i> <?= esc($item['no_whatsapp']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($item['jenis_kelamin']); ?></td>
                                    <td><?= esc($item['tempat_tanggal_lahir']); ?></td>
                                    <td class="kolom-alamat"><?= esc($item['alamat']); ?></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-light border text-primary py-0 px-2" title="Lihat Detail" onclick="bukaDetail(<?= $item['id_pendaftaran']; ?>)">
                                                <i class="fas fa-eye small"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border text-warning py-0 px-2" title="Edit Peserta" onclick="bukaEdit(<?= $item['id_pendaftaran']; ?>)">
                                                <i class="fas fa-edit small"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border text-danger py-0 px-2" title="Hapus Peserta" onclick="hapusPeserta(<?= $item['id_pendaftaran']; ?>)">
                                                <i class="fas fa-trash small"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>
                <div class="p-3 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top bg-light">
                    <div class="small text-muted" style="font-size: 0.75rem;">
                        Menampilkan <?= ($currentPage - 1) * $perPage + 1; ?> - <?= min($currentPage * $perPage, $totalData); ?> dari <?= $totalData; ?> data
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <?php
                                $queryParams = $filters;
                                $buildPageUrl = function($p) use ($queryParams) {
                                    $queryParams['page'] = $p;
                                    return base_url('admin/buku-induk?' . http_build_query($queryParams));
                                };
                            ?>
                            <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $buildPageUrl($currentPage - 1); ?>">&laquo;</a>
                            </li>
                            <?php for ($p = max(1, $currentPage - 2); $p <= min($totalPages, $currentPage + 2); $p++): ?>
                                <li class="page-item <?= ($p === $currentPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?= $buildPageUrl($p); ?>"><?= $p; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $buildPageUrl($currentPage + 1); ?>">&raquo;</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function hapusPeserta(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data peserta ini dari buku induk?')) {
                window.location.href = '<?= base_url('admin/buku-induk/delete/'); ?>' + id;
            }
        }
    </script>
</body>
</html>