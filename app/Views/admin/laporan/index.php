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
            --sidebar-active: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --primary-purple: #794bc4;
            --dark-purple: #1e0f33;
            --bg-light: #f7f5fd;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
            margin: 0;
        }

        /* SIDEBAR UTAMA */
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: #c8bfe7;
            z-index: 1000;
            box-shadow: 8px 0 30px rgba(121, 75, 196, 0.08);
            overflow-y: auto;
        }
        .sidebar-header {
            padding: 25px 20px;
            background: rgba(0, 0, 0, 0.2);
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        #sidebar .nav { padding: 20px 14px; }
        #sidebar .nav-item { margin-bottom: 6px; }
        #sidebar .nav-link {
            color: #c8bfe7;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.9rem;
            text-decoration: none;
        }
        #sidebar .nav-link i { margin-right: 14px; width: 22px; text-align: center; font-size: 1.1rem; }
        #sidebar .nav-link:hover {
            background-color: rgba(121, 75, 196, 0.2);
            color: #ffffff;
            transform: translateX(6px);
        }
        #sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            font-weight: 600;
        }

        /* MAIN CONTENT AREA */
        #main-content {
            margin-left: 275px;
            padding: 35px;
        }

        .top-navbar {
            background: #ffffff;
            padding: 22px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
            animation: fadeInDown 0.6s ease;
        }

        /* LAPORAN LAYOUT */
        .laporan-wrapper {
            display: grid;
            grid-template-columns: 290px 1fr;
            gap: 25px;
            align-items: start;
        }

        /* MENU SEBELAH KIRI (PILIHAN LAPORAN) */
        .menu-laporan-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.04);
            animation: fadeInLeft 0.6s ease;
        }
        .menu-laporan-item {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: #6c757d;
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .menu-laporan-item i {
            font-size: 1.1rem;
            margin-right: 14px;
            width: 24px;
            transition: transform 0.3s ease;
        }
        .menu-laporan-item:hover {
            background-color: #f4f0fc;
            color: var(--primary-purple);
            transform: translateX(4px);
        }
        .menu-laporan-item:hover i {
            transform: scale(1.2);
        }
        .menu-laporan-item.active {
            background: var(--sidebar-active);
            color: #ffffff;
            box-shadow: 0 6px 15px rgba(121, 75, 196, 0.3);
            font-weight: 600;
        }

        /* KONTEN TABEL SEBELAH KANAN */
        .content-laporan-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.04);
            animation: fadeInRight 0.6s ease;
            min-height: 450px;
        }

        .laporan-section {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }
        .laporan-section.active-section {
            display: block;
        }

        /* Custom Table Styling */
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        .table thead th {
            background-color: var(--dark-purple);
            color: #ffffff;
            border: none;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table tbody td {
            padding: 14px 16px;
            color: #495057;
            vertical-align: middle;
            font-size: 0.9rem;
            border-color: #f0ecfa;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #faf8fd;
        }
        .table tbody tr:hover {
            background-color: #f2edf9;
            transition: background 0.2s ease;
        }

        /* Tombol Cetak Modern */
        .btn-print-custom {
            background: var(--sidebar-active);
            border: none;
            border-radius: 12px;
            padding: 10px 22px;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.3);
            transition: all 0.3s ease;
        }
        .btn-print-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            color: #fff;
        }

        /* Animasi Keyframes */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Mode Cetak (Print) */
        @media print {
            #sidebar, .top-navbar, .menu-laporan-card, .btn-print-custom {
                display: none !important;
            }
            #main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .content-laporan-card {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }
            .laporan-section {
                display: block !important;
            }
        }
    </style>
</head>
<body>

    <!-- === SIDEBAR UTAMA === -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h5 class="text-white fw-bold m-0" style="letter-spacing: -0.5px;">Creativemu Academy</h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie"></i> <span>Dashboard</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book"></i> <span>Master Kelas</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user"></i> <span>Mentor</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link"><i class="fas fa-users"></i> <span>Data Peserta</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/validasi'); ?>" class="nav-link"><i class="fas fa-clipboard-check"></i> <span>Validasi Pendaftaran</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-award"></i> <span>Sertifikat</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/laporan'); ?>" class="nav-link active"><i class="fas fa-file-lines"></i> <span>Laporan</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear"></i> <span>Pengaturan</span></a></li>
            <li class="nav-item mt-4"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <!-- === KONTEN UTAMA === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div>
                <h3 class="fw-bold m-0" style="color: var(--dark-purple);">Pusat Laporan Akademik</h3>
                <p class="text-muted m-0 small">Kelola, tinjau, dan cetak seluruh rekapitulasi data akademik dengan mudah.</p>
            </div>
            <button onclick="window.print()" class="btn btn-print-custom">
                <i class="fa-solid fa-print me-2"></i> Cetak Laporan
            </button>
        </div>

        <!-- LAYOUT UTAMA LAPORAN (KIRI: MENU, KANAN: TABEL) -->
        <div class="laporan-wrapper">
            
            <!-- MENU PILIHAN LAPORAN DI KIRI -->
            <div class="menu-laporan-card">
                <span class="d-block text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Kategori Laporan</span>
                
                <a class="menu-laporan-item active" onclick="switchTab(event, 'peserta')">
                    <i class="fas fa-users"></i> Laporan Peserta
                </a>
                <a class="menu-laporan-item" onclick="switchTab(event, 'mentor')">
                    <i class="fas fa-chalkboard-user"></i> Laporan Mentor
                </a>
                <a class="menu-laporan-item" onclick="switchTab(event, 'angket')">
                    <i class="fas fa-poll"></i> Laporan Angket Mentor
                </a>
                <a class="menu-laporan-item" onclick="switchTab(event, 'absen')">
                    <i class="fas fa-clipboard-user"></i> Laporan Absen Siswa
                </a>
            </div>

            <!-- KONTEN TABEL DI KANAN -->
            <div class="content-laporan-card">
                
                <!-- 1. SECTION LAPORAN PESERTA -->
                <div id="section-peserta" class="laporan-section active-section">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold m-0" style="color: var(--dark-purple);"><i class="fas fa-users text-purple me-2"></i> Rekapitulasi Data Peserta</h5>
                            <small class="text-muted">Daftar seluruh siswa/peserta yang terdaftar di sistem.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Peserta</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($peserta)): ?>
                                    <?php $no = 1; foreach ($peserta as $p) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="fw-semibold"><?= $p['nama_peserta']; ?></td>
                                        <td><?= $p['email']; ?></td>
                                        <td><?= $p['no_whatsapp'] ?? '-'; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data peserta.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. SECTION LAPORAN MENTOR -->
                <div id="section-mentor" class="laporan-section">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold m-0" style="color: var(--dark-purple);"><i class="fas fa-chalkboard-user text-purple me-2"></i> Rekapitulasi Data Mentor</h5>
                            <small class="text-muted">Daftar mentor ahli yang aktif mengajar di akademi.</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Mentor</th>
                                    <th>Keahlian</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($mentor)): ?>
                                    <?php $no = 1; foreach ($mentor as $m) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="fw-semibold"><?= $m['nama_mentor']; ?></td>
                                        <td><span class="badge bg-light text-dark border px-2 py-1"><?= $m['keahlian']; ?></span></td>
                                        <td><?= $m['email']; ?></td>
                                        <td><?= $m['telepon']; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data mentor.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. SECTION LAPORAN ANGKET MENTOR -->
                <div id="section-angket" class="laporan-section">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold m-0" style="color: var(--dark-purple);"><i class="fas fa-poll text-purple me-2"></i> Laporan Angket / Penilaian Mentor</h5>
                            <small class="text-muted">Hasil rekap umpan balik dan evaluasi kualitas pengajaran mentor.</small>
                        </div>
                    </div>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                        <p class="m-0">Belum ada data angket atau penilaian mentor yang terekam.</p>
                    </div>
                </div>

                <!-- 4. SECTION LAPORAN ABSEN SISWA -->
                <div id="section-absen" class="laporan-section">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                        <div>
                            <h5 class="fw-bold m-0" style="color: var(--dark-purple);"><i class="fas fa-clipboard-user text-purple me-2"></i> Laporan Absensi Siswa</h5>
                            <small class="text-muted">Rekapitulasi tingkat kehadiran peserta dalam sesi kelas.</small>
                        </div>
                    </div>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-calendar-xmark fa-3x mb-3 text-secondary opacity-50"></i>
                        <p class="m-0">Belum ada data absensi siswa yang tersedia saat ini.</p>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Script JavaScript Interaktif untuk Animasi Tab -->
    <script>
        function switchTab(event, sectionId) {
            // Hapus class active dari semua menu di kiri
            document.querySelectorAll('.menu-laporan-item').forEach(el => {
                el.classList.remove('active');
            });
            
            // Tambahkan class active ke menu yang sedang diklik
            event.currentTarget.classList.add('active');
            
            // Sembunyikan semua section tabel di kanan dengan efek transisi
            document.querySelectorAll('.laporan-section').forEach(el => {
                el.classList.remove('active-section');
            });
            
            // Tampilkan section tabel yang dipilih
            const targetSection = document.getElementById('section-' + sectionId);
            if (targetSection) {
                targetSection.classList.add('active-section');
            }
        }
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>