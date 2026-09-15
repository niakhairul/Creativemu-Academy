<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            color: #2b263b;
            overflow-x: hidden;
            margin: 0;
        }

        /* SIDEBAR STYLING */
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            transition: all 0.3s ease;
            z-index: 1050;
            overflow-y: auto;
            box-shadow: 8px 0 30px rgba(121, 75, 196, 0.08);
        }

        #sidebar .sidebar-header {
            padding: 20px;
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
        }

        #sidebar .nav { padding: 18px 14px; }
        #sidebar .nav-item { margin-bottom: 5px; }

        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 11px 16px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.25s ease;
            font-size: 0.88rem;
            text-decoration: none;
        }

        #sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.05rem;
            width: 22px;
            text-align: center;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
            transform: translateX(4px);
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        .submenu-item .nav-link {
            padding-left: 28px !important;
            font-size: 0.84rem;
        }

        /* MAIN CONTENT */
        #main-content {
            margin-left: 275px;
            padding: 30px 35px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .top-navbar {
            background: #ffffff;
            padding: 20px 28px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        /* ROLE TABS */
        .role-nav {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .role-tab-btn {
            background: #ffffff;
            border: 1.5px solid #e2d9f3;
            padding: 12px 22px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.92rem;
            color: #4a4458;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.03);
        }

        .role-tab-btn:hover {
            color: var(--primary-purple);
            border-color: var(--primary-purple);
            transform: translateY(-2px);
        }

        .role-tab-btn.active {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.3);
        }

        /* MODULE CARD */
        .module-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid rgba(121, 75, 196, 0.08);
            box-shadow: 0 8px 24px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .module-card:hover {
            box-shadow: 0 12px 30px rgba(121, 75, 196, 0.1);
            border-color: rgba(121, 75, 196, 0.2);
        }

        .module-header {
            background: #faf8fd;
            padding: 16px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f0ecfa;
            flex-wrap: wrap;
            gap: 10px;
        }

        .module-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 1rem;
        }

        .module-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #ede7f8;
            color: var(--primary-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .module-body {
            padding: 20px 22px;
        }

        /* PERMISSION TOGGLES */
        .perms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 14px;
        }

        .perm-toggle-box {
            background: #faf9fe;
            border: 1.5px solid #e7e0f4;
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .perm-toggle-box:hover {
            background: #f3ecfb;
            border-color: var(--primary-purple);
        }

        .perm-toggle-box.active {
            background: #f2eafc;
            border-color: var(--primary-purple);
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.1);
        }

        .perm-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #332d41;
            margin-bottom: 0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-purple);
            border-color: var(--primary-purple);
        }

        .btn-creative-primary {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-creative-primary:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; padding: 18px; }
            .mobile-toggle-btn { display: inline-block !important; }
        }

        .mobile-toggle-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-purple);
            margin-right: 15px;
        }
    </style>
</head>
<body>

    <!-- === SIDEBAR ADMIN === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
            <div class="mt-2 text-white-50 small fw-semibold">PANEL ADMINISTRATOR</div>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book"></i> <span>Master Kelas</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user"></i> <span>Instruktur</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link"><i class="fas fa-users"></i> <span>Data Peserta</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/validasi'); ?>" class="nav-link"><i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link"><i class="fas fa-book-open"></i> <span>Buku Induk</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/angket'); ?>" class="nav-link"><i class="fas fa-poll"></i> <span>Angket</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-award"></i> <span>Sertifikat</span></a></li>

            <!-- Submenu Laporan -->
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#submenuLaporanAdmin" role="button" aria-expanded="false">
                    <i class="fas fa-chart-simple"></i> <span>Laporan</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 0.75rem;"></i>
                </a>
                <div class="collapse" id="submenuLaporanAdmin">
                    <ul class="nav flex-column ms-2">
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-peserta'); ?>" class="nav-link"><i class="fas fa-chart-pie me-2"></i> <span>Laporan Peserta</span></a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user me-2"></i> <span>Laporan Instruktur</span></a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-angket'); ?>" class="nav-link"><i class="fas fa-star-half-stroke me-2"></i> <span>Laporan Angket Instruktur</span></a>
                        </li>
                        <li class="nav-item submenu-item">
                            <a href="<?= base_url('admin/laporan-kehadiran'); ?>" class="nav-link"><i class="fas fa-calendar-check me-2"></i> <span>Laporan Kehadiran</span></a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="<?= base_url('admin/hak-akses'); ?>" class="nav-link active">
                    <i class="fas fa-user-shield"></i> <span>Hak Akses</span>
                </a>
            </li>
            <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear"></i> <span>Pengaturan</span></a></li>
            <li class="nav-item mt-4"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <!-- === KONTEN UTAMA === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <div>
                    <h4 class="mb-0 fw-bold" style="color: var(--dark-purple);">Manajemen Hak Akses & Permissions</h4>
                    <p class="text-muted small mb-0">Atur hak akses operasional per peran (Administrator, Instruktur, dan Peserta) untuk seluruh modul sistem</p>
                </div>
            </div>
            <div>
                <a href="<?= base_url('admin/hak-akses/reset?role=' . $selectedRole); ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Apakah Anda yakin ingin mengembalikan hak akses ke standar awal?')">
                    <i class="fas fa-rotate-left me-1"></i> Reset Standar
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- ROLE NAVIGATION TABS -->
        <div class="role-nav">
            <?php foreach ($roles as $key => $r): ?>
                <a href="<?= base_url('admin/hak-akses?role=' . $key); ?>" class="role-tab-btn <?= ($selectedRole === $key) ? 'active' : ''; ?>">
                    <i class="fas fa-shield-halved"></i>
                    <span><?= esc($r['title']); ?></span>
                    <span class="badge bg-light text-dark ms-1" style="font-size: 0.72rem;"><?= esc($r['badge']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- FORM MATRIKS HAK AKSES -->
        <form action="<?= base_url('admin/hak-akses/update'); ?>" method="post">
            <?= csrf_field(); ?>
            <input type="hidden" name="role" value="<?= esc($selectedRole); ?>">

            <!-- ROLE HEADER BAR -->
            <div class="p-4 bg-white rounded-4 shadow-sm border mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--dark-purple);">
                        <i class="fas fa-user-lock me-2 text-primary"></i> Konfigurasi Hak Akses: <?= esc($roleData['title']); ?>
                    </h5>
                    <p class="text-muted small mb-0">Centang atau aktifkan izin yang diperbolehkan untuk peran ini pada masing-masing modul.</p>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="pilihSemuaGlobal(true)">
                        <i class="fas fa-check-double me-1"></i> Pilih Semua Izin
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="pilihSemuaGlobal(false)">
                        <i class="fas fa-xmark me-1"></i> Batal Semua
                    </button>
                    <button type="submit" class="btn btn-creative-primary btn-sm px-4">
                        <i class="fas fa-save me-1"></i> Simpan Hak Akses
                    </button>
                </div>
            </div>

            <!-- MODUL PERMISSION CARDS -->
            <div class="row">
                <?php foreach ($modules as $modKey => $mod): ?>
                    <?php 
                        $modPerms = $roleData['permissions'][$modKey] ?? [];
                        $totalActive = 0;
                        foreach (['lihat', 'tambah', 'edit', 'hapus', 'export'] as $act) {
                            if (!empty($modPerms[$act])) $totalActive++;
                        }
                    ?>
                    <div class="col-xl-6 col-12">
                        <div class="module-card">
                            <div class="module-header">
                                <div class="module-title">
                                    <div class="module-icon">
                                        <i class="fas <?= $mod['icon']; ?>"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?= esc($mod['label']); ?></div>
                                        <div class="text-muted small fw-normal" style="font-size: 0.76rem;"><?= esc($mod['desc']); ?></div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark border px-2 py-1 small" id="badge-<?= $modKey; ?>">
                                        <span class="active-count"><?= $totalActive; ?></span>/5 Izin
                                    </span>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size: 0.78rem;" onclick="toggleModul('<?= $modKey; ?>')">
                                        Pilih/Batal
                                    </button>
                                </div>
                            </div>
                            <div class="module-body">
                                <div class="perms-grid" data-module="<?= $modKey; ?>">
                                    <?php foreach ($actionsList as $actKey => $act): ?>
                                        <?php $isChecked = !empty($modPerms[$actKey]); ?>
                                        <label class="perm-toggle-box <?= $isChecked ? 'active' : ''; ?>" for="perm_<?= $modKey; ?>_<?= $actKey; ?>">
                                            <span class="perm-label">
                                                <i class="fas <?= $act['icon']; ?>" style="color: <?= $act['color']; ?>;"></i>
                                                <?= esc($act['label']); ?>
                                            </span>
                                            <div class="form-check form-switch m-0 p-0" style="min-height: auto;">
                                                <input class="form-check-input perm-switch" type="checkbox" role="switch" 
                                                       name="perms[<?= $modKey; ?>][<?= $actKey; ?>]" 
                                                       id="perm_<?= $modKey; ?>_<?= $actKey; ?>" 
                                                       value="1" 
                                                       data-module="<?= $modKey; ?>"
                                                       <?= $isChecked ? 'checked' : ''; ?>
                                                       onchange="onPermChange(this, '<?= $modKey; ?>')">
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- STICKY BOTTOM SAVE BAR -->
            <div class="p-3 bg-white rounded-4 shadow border mt-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <i class="fas fa-info-circle text-primary me-1"></i> Perubahan izin akan langsung diterapkan pada hak akses peran yang dipilih.
                </div>
                <button type="submit" class="btn btn-creative-primary px-4">
                    <i class="fas fa-save me-1"></i> Simpan Perubahan Hak Akses
                </button>
            </div>
        </form>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Toggle Visual Box & Counter on checkbox change
        function onPermChange(checkbox, moduleKey) {
            const box = checkbox.closest('.perm-toggle-box');
            if (checkbox.checked) {
                box.classList.add('active');
            } else {
                box.classList.remove('active');
            }
            updateModuleBadge(moduleKey);
        }

        // Update Counter Badge per modul
        function updateModuleBadge(moduleKey) {
            const grid = document.querySelector(`.perms-grid[data-module="${moduleKey}"]`);
            if (!grid) return;
            const checkedCount = grid.querySelectorAll('input.perm-switch:checked').length;
            const badge = document.getElementById(`badge-${moduleKey}`);
            if (badge) {
                badge.querySelector('.active-count').textContent = checkedCount;
                if (checkedCount === 5) {
                    badge.className = 'badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 small';
                } else if (checkedCount > 0) {
                    badge.className = 'badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1 small';
                } else {
                    badge.className = 'badge bg-light text-muted border px-2 py-1 small';
                }
            }
        }

        // Toggle semua per-modul
        function toggleModul(moduleKey) {
            const grid = document.querySelector(`.perms-grid[data-module="${moduleKey}"]`);
            if (!grid) return;
            const switches = grid.querySelectorAll('input.perm-switch');
            const anyUnchecked = Array.from(switches).some(s => !s.checked);
            
            switches.forEach(s => {
                s.checked = anyUnchecked;
                const box = s.closest('.perm-toggle-box');
                if (anyUnchecked) {
                    box.classList.add('active');
                } else {
                    box.classList.remove('active');
                }
            });
            updateModuleBadge(moduleKey);
        }

        // Pilih Semua Global
        function pilihSemuaGlobal(status) {
            const allSwitches = document.querySelectorAll('input.perm-switch');
            allSwitches.forEach(s => {
                s.checked = status;
                const box = s.closest('.perm-toggle-box');
                if (status) {
                    box.classList.add('active');
                } else {
                    box.classList.remove('active');
                }
            });

            // Update all badges
            document.querySelectorAll('.perms-grid').forEach(g => {
                const mod = g.getAttribute('data-module');
                if (mod) updateModuleBadge(mod);
            });
        }
    </script>
</body>
</html>
