<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f7f5fd; }
        ::-webkit-scrollbar-thumb { background: #b293f0; border-radius: 10px; }

        /* --- Sidebar Styling --- */
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
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
            max-width: 170px;
            height: auto;
            filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
        }

        #sidebar .nav { padding: 20px 14px; }
        #sidebar .nav-item { margin-bottom: 6px; }
        
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
            transition: transform 0.3s ease;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
            transform: translateX(6px);
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        #sidebar .nav-link.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #ff6b6b !important;
        }

        /* --- Main Content Area --- */
        #main-content {
            margin-left: 275px;
            padding: 35px;
            transition: all 0.4s ease;
            animation: mainFadeIn 0.7s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        /* --- Top Navbar --- */
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
            letter-spacing: -0.5px;
        }
        
        .dash-header p {
            color: #8c83a5;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-profile img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--primary-purple);
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.2);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 0.98rem;
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.78rem;
        }

        /* --- Content Cards --- */
        .content-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            margin-bottom: 30px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        /* --- Table Styling --- */
        .table-custom {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table-custom th {
            background-color: var(--light-purple);
            color: var(--dark-purple);
            font-weight: 700;
            padding: 15px;
            border: none;
        }

        .table-custom td {
            padding: 15px;
            border-bottom: 1px solid #f0edf6;
            color: #4a4259;
        }

        @keyframes mainFadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo-creativemu.png'); ?>" alt="Creativemu Academy" class="img-fluid">
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
                    <i class="fas fa-chalkboard-user"></i> <span>Mentor</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link">
                    <i class="fas fa-users"></i> <span>Data Peserta</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/validasi'); ?>" class="nav-link active">
                    <i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link">
                    <i class="fas fa-award"></i> <span>Angket</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link">
                    <i class="fas fa-award"></i> <span>Sertifikat</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/laporan'); ?>" class="nav-link">
                    <i class="fas fa-file-lines"></i> <span>Laporan</span>
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
                <h3>Validasi Pendaftaran</h3>
                <p>Kelola dan setujui peserta yang sedang menunggu konfirmasi admin.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" id="current-date" style="font-size: 0.82rem; font-weight: 600; color: #794bc4 !important;">
                    Memuat tanggal...
                </div>
                <div class="admin-profile">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                    <div class="admin-info">
                        <h6><?= esc(session()->get('nama')); ?></h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifikasi Sukses -->
        <?php if(session()->getFlashdata('success') || session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-5 border-success mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> 
                <?= session()->getFlashdata('success') ?? session()->getFlashdata('pesan') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Card Tabel -->
        <div class="content-card">
            <div class="table-responsive">
                <table class="table table-hover table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th>Data Peserta</th>
                            <th>Kelas</th>
                            <th>Pembayaran</th>
                            <th class="text-center">Bukti</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($pendaftaran)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada data pendaftaran yang masuk.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach(($pendaftaran ?? []) as $i => $item): ?>
                            <tr>
                                <td class="text-center fw-semibold text-muted"><?= $i + 1 ?></td>
                                <td>
                                    <!-- Bagian Nama, Email, dan No HP -->
                                    <div class="fw-bold text-dark mb-1"><?= esc($item['nama']) ?></div>
                                    <div class="text-muted small">
                                        <i class="bi bi-envelope me-1"></i><?= esc($item['email']) ?> <br>
                                        <i class="bi bi-telephone me-1"></i><?= esc($item['no_hp']) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                                        <?= esc($item['nama_kelas'] ?? '-') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><?= esc($item['metode_pembayaran'] ?? '-') ?></div>
                                    <small class="text-muted text-capitalize"><?= esc($item['status_pembayaran'] ?? 'pending') ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if(!empty($item['bukti_pembayaran'])): ?>
                                       <a href="<?= base_url('uploads/bukti_pembayaran/' . $item['bukti_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-image me-1"></i> Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-3">Belum Upload</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- LOGIKA STATUS DINAMIS -->
                                <td class="text-center">
                                    <?php 
                                        $statusBayar = strtolower(trim($item['status_pembayaran'] ?? 'pending'));
                                    ?>

                                    <?php if ($statusBayar == 'valid'): ?>
                                        <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">
                                            <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                        </span>

                                    <?php elseif ($statusBayar == 'rejected'): ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm" title="<?= esc($item['alasan_penolakan'] ?? '') ?>">
                                            <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                        </span>

                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm">
                                            <i class="bi bi-clock-history me-1"></i> Menunggu
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- TOMBOL AKSI & MODAL -->
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Tombol Pemicu Modal Validasi -->
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalValidasi<?= $item['id_pendaftaran'] ?>" title="Validasi">
                                            <i class="bi bi-gear-fill me-1"></i> Validasi
                                        </button>
                                    </div>

                                    <!-- Modal Validasi Admin di dalam baris iterasi -->
                                    <div class="modal fade text-start" id="modalValidasi<?= $item['id_pendaftaran'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="<?= base_url('admin/pendaftaran/proses_validasi/' . $item['id_pendaftaran']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-header border-0">
                                                        <h5 class="fw-bold fs-6">Validasi Pendaftaran: <?= esc($item['nama']) ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                        <!-- Pilihan Status -->
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Ubah Status</label>
                                                            <select name="status_pembayaran" class="form-select status-select" data-id="<?= $item['id_pendaftaran'] ?>" required>
                                                                <option value="valid" <?= ($statusBayar == 'valid') ? 'selected' : '' ?>>Terima / Valid</option>
                                                                <option value="rejected" <?= ($statusBayar == 'rejected') ? 'selected' : '' ?>>Tolak (Rejected)</option>
                                                            </select>
                                                        </div>

                                                        <!-- Input Alasan Penolakan -->
                                                        <div class="mb-3 alasan-wrapper" id="wrapperAlasan<?= $item['id_pendaftaran'] ?>" style="display: <?= ($statusBayar == 'rejected') ? 'block' : 'none' ?>;">
                                                            <label class="form-label fw-semibold text-danger">Alasan Penolakan <span class="text-danger">*</span></label>
                                                            <textarea name="alasan_penolakan" class="form-control" rows="3" placeholder="Contoh: Bukti transfer tidak jelas, nominal kurang, atau salah rekening tujuan."><?= esc($item['alasan_penolakan'] ?? '') ?></textarea>
                                                            <div class="form-text small">Alasan ini akan dibaca oleh peserta saat mereka mengecek status pendaftarannya.</div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

<!-- Script JavaScript untuk memunculkan textarea alasan saat opsi 'rejected' dipilih -->
<script>
document.querySelectorAll('.status-select').forEach(function(select) {
    // Jalankan pengecekan awal saat modal dimuat jika statusnya sudah rejected
    let id = select.getAttribute('data-id');
    let wrapper = document.getElementById('wrapperAlasan' + id);
    let textarea = wrapper ? wrapper.querySelector('textarea') : null;

    if (select.value === 'rejected') {
        if(wrapper) wrapper.style.display = 'block';
        if(textarea) textarea.setAttribute('required', 'required');
    }

    select.addEventListener('change', function() {
        let currentId = this.getAttribute('data-id');
        let currentWrapper = document.getElementById('wrapperAlasan' + currentId);
        let currentTextarea = currentWrapper ? currentWrapper.querySelector('textarea') : null;
        
        if (this.value === 'rejected') {
            if(currentWrapper) currentWrapper.style.display = 'block';
            if(currentTextarea) currentTextarea.setAttribute('required', 'required');
        } else {
            if(currentWrapper) currentWrapper.style.display = 'none';
            if(currentTextarea) currentTextarea.removeAttribute('required');
        }
    });
});
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>