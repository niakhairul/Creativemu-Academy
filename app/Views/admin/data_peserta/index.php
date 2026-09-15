<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Daftar Peserta'); ?> - Creativemu Academy</title>
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

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            overflow-x: hidden;
            margin: 0;
        }

        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(121, 75, 196, 0.08);
            overflow-y: auto;
        }

        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: rgba(0, 0, 0, 0.25);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }

        #sidebar .sidebar-header img {
            width: 240px;
            height: 95px;
            object-fit: cover;
            border-radius: 10px;
            filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
            transition: transform 0.3s ease;
        }

        #sidebar .nav {
            padding: 20px 14px;
        }

        #sidebar .nav-item {
            margin-bottom: 6px;
        }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 12px 18px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        #sidebar .nav-link i {
            margin-right: 14px;
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        #main-content {
            margin-left: 275px;
            padding: 35px;
        }

        .top-navbar {
            background: #ffffff;
            padding: 22px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
        }

        .dash-header h3 {
            font-weight: 800;
            color: var(--dark-purple);
            font-size: 1.6rem;
            margin-bottom: 2px;
        }
        
        .dash-header p {
            color: #8c83a5;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .content-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .table-custom {
            font-size: 0.9rem;
        }

        .table-custom thead th {
            background-color: #f7f5fd;
            color: var(--dark-purple);
            font-weight: 700;
            border-bottom: 2px solid rgba(121, 75, 196, 0.1);
            padding: 14px 12px;
            white-space: nowrap;
        }

        .table-custom tbody td {
            padding: 14px 12px;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-purple);
        }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-purple:hover {
            background: linear-gradient(135deg, #683db3 0%, #4a2788 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.3);
        }

        .badge-kelas {
            background-color: #ede7f6;
            color: #5e35b1;
            font-weight: 600;
            border: 1px solid #d1c4e9;
        }

        .detail-avatar-box {
            width: 100px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            background-color: #f3f0fa;
            border: 2px dashed #b39ddb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .detail-avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-header-custom {
            background: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            color: #ffffff;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }
    </style>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link">
                    <i class="fas fa-chart-pie"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link">
                    <i class="fas fa-book"></i> <span>Master Kelas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/mentor'); ?>" class="nav-link">
                    <i class="fas fa-chalkboard-user"></i> <span>Instruktur</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link active">
                    <i class="fas fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link">
                    <i class="fas fa-book-open"></i> <span>Buku Induk</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link">
                    <i class="fas fa-award"></i> <span>Angket</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link">
                    <i class="fas fa-certificate"></i> <span>Sertifikat</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
                    <i class="fas fa-file-lines"></i> <span>Laporan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/hak-akses'); ?>" class="nav-link">
                    <i class="fas fa-user-shield"></i> <span>Hak Akses</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link">
                    <i class="fas fa-gear"></i> <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="<?= base_url('logout'); ?>" class="nav-link text-danger">
                    <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- === MAIN CONTENT === -->
    <div id="main-content">
        
        <!-- === TOP NAVBAR === -->
        <div class="top-navbar">
            <div class="dash-header">
                <h3>Daftar Peserta</h3>
                <p>Data peserta pelatihan terintegrasi langsung dengan pendaftaran kelas Creativemu Academy.</p>
            </div>
            <div class="admin-profile d-flex align-items-center gap-3">
                <div class="text-end">
                    <h6 class="mb-0 fw-bold text-dark"><?= esc(session()->get('nama') ?? 'Administrator'); ?></h6>
                    <small class="text-muted">Admin Creativemu</small>
                </div>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; font-weight: 700;">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <!-- === FILTER & SEARCH SECTION === -->
        <div class="content-card mb-4">
            <form action="<?= base_url('admin/data-peserta'); ?>" method="get" class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="fas fa-search me-1"></i> Cari Data Peserta
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control bg-light border-start-0" placeholder="Cari NIS, Nama, No HP/WA..." value="<?= esc($keyword ?? ''); ?>">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="fas fa-book-open me-1"></i> Filter Kelas
                    </label>
                    <select name="id_kelas" class="form-select bg-light">
                        <option value="">-- Semua Kelas --</option>
                        <?php foreach(($kelasList ?? []) as $k): ?>
                            <option value="<?= $k['id_kelas']; ?>" <?= ((string)($selectedKelas ?? '') === (string)$k['id_kelas']) ? 'selected' : ''; ?>>
                                <?= esc($k['nama_kelas']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold small text-muted mb-1">
                        <i class="fas fa-filter me-1"></i> Filter Status
                    </label>
                    <select name="status" class="form-select bg-light">
                        <option value="">-- Semua Status --</option>
                        <option value="aktif" <?= (($selectedStatus ?? '') === 'aktif') ? 'selected' : ''; ?>>Aktif / Disetujui</option>
                        <option value="menunggu" <?= (($selectedStatus ?? '') === 'menunggu') ? 'selected' : ''; ?>>Menunggu Validasi</option>
                        <option value="ditolak" <?= (($selectedStatus ?? '') === 'ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6 d-flex gap-2">
                    <button type="submit" class="btn btn-purple w-100 py-2 rounded-3">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <?php if (!empty($keyword) || !empty($selectedKelas) || !empty($selectedStatus)): ?>
                        <a href="<?= base_url('admin/data-peserta'); ?>" class="btn btn-outline-secondary py-2 rounded-3" title="Reset Filter">
                            <i class="fas fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- === TABLE SECTION === -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h5 class="fw-bold mb-0 text-dark">
                    <i class="fas fa-users text-primary me-2"></i> Tabel Daftar Peserta
                </h5>
                <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.85rem;">
                    Total: <?= count($peserta ?? []); ?> Pendaftaran Peserta
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th style="width: 140px;">NIS</th>
                            <th>Nama Lengkap</th>
                            <th style="width: 110px;">Gender</th>
                            <th style="width: 150px;">No. HP / WhatsApp</th>
                            <th>Kelas</th>
                            <th>Alamat</th>
                            <th style="width: 130px;" class="text-center">Status</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($peserta)): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-folder-open fs-2 mb-2 d-block text-secondary"></i>
                                    Tidak ada data peserta yang sesuai dengan filter atau belum ada pendaftaran masuk.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach(($peserta ?? []) as $i => $item): ?>
                            <?php 
                                $statusBayar = strtolower(trim($item['status_pembayaran'] ?? 'pending'));
                                $statusPend  = trim($item['status_pendaftaran'] ?? 'Menunggu');
                                $statusProf  = trim($item['status'] ?? '');
                                $isAktif     = ($statusBayar === 'valid' || strtolower($statusPend) === 'disetujui' || strtolower($statusProf) === 'disetujui');
                                $isDitolak   = ($statusBayar === 'rejected' || strtolower($statusPend) === 'ditolak');
                                $namaTampil  = $item['nama_lengkap'] ?: ($item['nama'] ?: '-');
                                $noHpTampil  = $item['no_hp_terbaru'] ?: ($item['no_hp'] ?: '-');
                                $genderTampil = $item['gender_terbaru'] ?: ($item['jenis_kelamin'] ?: '-');
                                $emailTampil = $item['email_terbaru'] ?: ($item['email'] ?: '-');
                                $nisTampil   = $item['resolved_nis'] ?: ($item['nis'] ?: '');
                                $kelasTampil = $item['nama_kelas'] ?: ($item['pilihan_kelas'] ?: ($item['pilihan_pelatihan'] ?: ($item['kategori_kelas'] ?: '-')));
                            ?>
                            <tr>
                                <!-- 1. NO -->
                                <td class="text-center fw-semibold text-muted"><?= $i + 1 ?></td>

                                <!-- 2. NIS -->
                                <td>
                                    <?php if(!empty($nisTampil)): ?>
                                        <span class="badge bg-light text-dark border fw-bold px-2 py-1" style="letter-spacing: 0.5px;">
                                            <i class="fas fa-id-badge text-primary me-1"></i><?= esc($nisTampil) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border px-2 py-1 small">
                                            <i class="fas fa-clock text-warning me-1"></i>Belum ada NIS
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- 3. NAMA LENGKAP -->
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($namaTampil) ?></div>
                                    <small class="text-muted"><i class="fas fa-envelope me-1"></i><?= esc($emailTampil) ?></small>
                                </td>

                                <!-- 4. GENDER -->
                                <td>
                                    <span><?= esc($genderTampil) ?></span>
                                </td>

                                <!-- 5. NO. HP / WHATSAPP -->
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <span><?= esc($noHpTampil) ?></span>
                                        <?php if (!empty($noHpTampil) && $noHpTampil !== '-'): ?>
                                            <?php 
                                                $cleanHp = preg_replace('/[^0-9]/', '', $noHpTampil);
                                                if (str_starts_with($cleanHp, '0')) {
                                                    $cleanHp = '62' . substr($cleanHp, 1);
                                                }
                                            ?>
                                            <a href="https://wa.me/<?= $cleanHp ?>" target="_blank" class="text-success ms-1" title="Chat WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <!-- 6. KELAS -->
                                <td>
                                    <span class="badge badge-kelas px-3 py-1 rounded-pill">
                                        <?= esc($kelasTampil) ?>
                                    </span>
                                    <?php if(!empty($item['metode_pembelajaran'])): ?>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-<?= strtolower($item['metode_pembelajaran']) === 'online' ? 'globe text-info' : 'location-dot text-danger' ?> me-1"></i>
                                            <?= ucfirst(esc($item['metode_pembelajaran'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <!-- 7. ALAMAT -->
                                <td>
                                    <div class="text-truncate" style="max-width: 170px;" title="<?= esc($item['alamat'] ?? '-') ?>">
                                        <?= esc($item['alamat'] ?? '-') ?>
                                    </div>
                                </td>

                                <!-- 8. STATUS -->
                                <td class="text-center">
                                    <?php if ($isAktif): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-1 shadow-sm">
                                            <i class="fas fa-check-circle me-1"></i> Aktif
                                        </span>
                                    <?php elseif ($isDitolak): ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-1 shadow-sm" title="<?= esc($item['alasan_penolakan'] ?? '') ?>">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-1 shadow-sm">
                                            <i class="fas fa-clock me-1"></i> Menunggu
                                        </span>
                                    <?php endif; ?>

                                    <?php if (!empty($statusProf) && !in_array(strtolower($statusProf), ['disetujui', 'pending', 'menunggu', 'ditolak'])): ?>
                                        <div class="small text-muted mt-1"><?= esc($statusProf) ?></div>
                                    <?php endif; ?>
                                </td>

                                <!-- 9. AKSI (DETAIL) -->
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDetailPeserta<?= $item['id_pendaftaran'] ?>">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>

                            <!-- === MODAL DETAIL PESERTA LENGKAP === -->
                            <div class="modal fade" id="modalDetailPeserta<?= $item['id_pendaftaran'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $item['id_pendaftaran'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <div class="modal-header modal-header-custom px-4 py-3">
                                            <h5 class="modal-title fw-bold" id="modalLabel<?= $item['id_pendaftaran'] ?>">
                                                <i class="fas fa-id-card me-2"></i> Detail Pendaftaran Peserta
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                <!-- Foto & Info Ringkas -->
                                                <div class="col-md-3 text-center border-end">
                                                    <div class="detail-avatar-box mx-auto mb-3">
                                                        <?php 
                                                            $fotoPath = !empty($item['pas_foto']) && file_exists(FCPATH . 'uploads/foto/' . $item['pas_foto'])
                                                                ? base_url('uploads/foto/' . $item['pas_foto'])
                                                                : (!empty($item['foto_profil']) && file_exists(FCPATH . 'uploads/profil/' . $item['foto_profil'])
                                                                    ? base_url('uploads/profil/' . $item['foto_profil'])
                                                                    : null);
                                                        ?>
                                                        <?php if ($fotoPath): ?>
                                                            <a href="<?= $fotoPath ?>" target="_blank" title="Lihat Foto Ukuran Penuh">
                                                                <img src="<?= $fotoPath ?>" alt="Pas Foto <?= esc($namaTampil) ?>">
                                                            </a>
                                                        <?php else: ?>
                                                            <div class="text-center text-muted">
                                                                <i class="fas fa-user-tie fs-1 text-secondary opacity-50 d-block mb-1"></i>
                                                                <small style="font-size: 0.7rem;">Tanpa Foto</small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h6 class="fw-bold mb-1 text-dark"><?= esc($namaTampil) ?></h6>
                                                    <div class="badge bg-light text-dark border mb-2"><?= esc($nisTampil ?: 'Belum Terbit NIS') ?></div>
                                                    <div>
                                                        <?php if ($isAktif): ?>
                                                            <span class="badge bg-success rounded-pill px-2 py-1">Aktif</span>
                                                        <?php elseif ($isDitolak): ?>
                                                            <span class="badge bg-danger rounded-pill px-2 py-1">Ditolak</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Menunggu</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Data Detail -->
                                                <div class="col-md-9">
                                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                                        <i class="fas fa-user me-2"></i> 1. Data Diri Peserta
                                                    </h6>
                                                    <div class="row g-2 small mb-4">
                                                        <div class="col-sm-4 text-muted fw-semibold">NIS:</div>
                                                        <div class="col-sm-8 fw-bold text-dark"><?= esc($nisTampil ?: 'Belum ada NIS') ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Nama Lengkap:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($namaTampil) ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Jenis Kelamin (Gender):</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($genderTampil) ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Tempat, Tanggal Lahir:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($item['ttl'] ?? '-') ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">No. HP / WhatsApp:</div>
                                                        <div class="col-sm-8 text-dark">
                                                            <?= esc($noHpTampil) ?>
                                                            <?php if (!empty($noHpTampil) && $noHpTampil !== '-'): ?>
                                                                <a href="https://wa.me/<?= $cleanHp ?>" target="_blank" class="badge bg-success-subtle text-success ms-2 text-decoration-none">
                                                                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Email:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($emailTampil) ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Pendidikan Terakhir:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($item['pendidikan_terakhir'] ?? '-') ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Status / Profesi:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($statusProf ?: '-') ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Alamat Lengkap:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($item['alamat'] ?? '-') ?></div>
                                                    </div>

                                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                                        <i class="fas fa-graduation-cap me-2"></i> 2. Kelas & Pelatihan
                                                    </h6>
                                                    <div class="row g-2 small mb-4">
                                                        <div class="col-sm-4 text-muted fw-semibold">Kelas yang Dipilih:</div>
                                                        <div class="col-sm-8 fw-bold text-dark"><?= esc($kelasTampil) ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Kategori Kelas:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($item['kategori_kelas'] ?: ($item['kategori_kelas_master'] ?: 'Reguler')) ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Metode Pembelajaran:</div>
                                                        <div class="col-sm-8 text-dark text-capitalize">
                                                            <span class="badge <?= strtolower($item['metode_pembelajaran'] ?? '') === 'online' ? 'bg-info-subtle text-info border border-info' : 'bg-primary-subtle text-primary border border-primary' ?>">
                                                                <?= esc($item['metode_pembelajaran'] ?? 'Offline') ?>
                                                            </span>
                                                        </div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Tempat Pelatihan:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($item['lokasi_pelatihan'] ?? '-') ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Tanggal Daftar:</div>
                                                        <div class="col-sm-8 text-dark"><?= esc($item['created_at'] ?? '-') ?></div>
                                                    </div>

                                                    <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">
                                                        <i class="fas fa-receipt me-2"></i> 3. Pembayaran & Dokumen
                                                    </h6>
                                                    <div class="row g-2 small">
                                                        <div class="col-sm-4 text-muted fw-semibold">Metode Pembayaran:</div>
                                                        <div class="col-sm-8 text-dark text-uppercase fw-semibold"><?= esc($item['metode_pembayaran'] ?? 'Transfer') ?></div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Status Pembayaran:</div>
                                                        <div class="col-sm-8">
                                                            <span class="badge <?= $statusBayar === 'valid' ? 'bg-success' : ($statusBayar === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') ?>">
                                                                <?= strtoupper($statusBayar) ?>
                                                            </span>
                                                        </div>

                                                        <div class="col-sm-4 text-muted fw-semibold">Bukti Pembayaran:</div>
                                                        <div class="col-sm-8">
                                                            <?php if (!empty($item['bukti_pembayaran'])): ?>
                                                                <?php $buktiUrl = base_url('uploads/bukti/' . $item['bukti_pembayaran']); ?>
                                                                <a href="<?= $buktiUrl ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill py-0 px-3">
                                                                    <i class="fas fa-file-invoice me-1"></i> Lihat Bukti Transfer
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3">Belum Ada Bukti</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light px-4 py-2 border-top-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>