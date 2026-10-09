<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Mencegah zoom / layar bergerak di HP/Tablet -->
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
            --sidebar-bg: #1c1032;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
        }

        html, body {
            touch-action: pan-x pan-y;
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            overflow-x: hidden;
            margin: 0;
            font-size: 14px; /* Ukuran font standar compact */
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f7f5fd; }
        ::-webkit-scrollbar-thumb { background: #b293f0; border-radius: 10px; }

        /* --- Sidebar Styling --- */
        

        /* Sidebar Header & Logo Card */
        

        

        

        

        /* Navigation Links */
        
        
        
        

        

        

        

        

        /* --- Main Content Area --- */
        

        /* --- Top Navbar --- */
        .top-navbar {
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
        }

        .dash-header h3 {
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 1.25rem;
            margin: 0;
        }
        
        .dash-header p {
            color: #8c83a5;
            font-size: 0.8rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-purple);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.88rem;
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.72rem;
        }

        /* --- Content Cards --- */
        .content-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .card-title-custom {
            font-weight: 700;
            color: var(--dark-purple);
            margin-bottom: 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title-custom i {
            color: var(--primary-purple);
        }

        /* --- Table Styling --- */
        .table-custom {
            vertical-align: middle;
            font-size: 0.82rem;
        }

        .table-custom th {
            background-color: var(--light-purple);
            color: var(--dark-purple);
            font-weight: 700;
            padding: 10px 12px;
            border: none;
        }

        .table-custom td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0edf6;
            color: #4a4259;
        }

        /* --- Modal Customization --- */
        .modal-content {
            border-radius: 14px;
            border: none;
            box-shadow: 0 10px 30px rgba(30, 15, 51, 0.15);
        }

        .modal-header {
            background-color: var(--light-purple);
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
            padding: 14px 20px;
            border-bottom: 1px solid rgba(121, 75, 196, 0.08);
        }

        .modal-body { padding: 20px; }

        .modal-footer {
            background-color: #fcfbfe;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
            padding: 12px 20px;
            border-top: 1px solid rgba(121, 75, 196, 0.08);
        }

        /* --- Form Styling --- */
        .form-label {
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.8rem;
            margin-bottom: 4px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 8px 12px;
            border: 1.5px solid #e2d9f3;
            font-size: 0.85rem;
            background-color: #fcfbfe;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
            background-color: #ffffff;
        }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.25);
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            opacity: 0.95;
            color: #ffffff;
        }

        /* --- Responsive Mobile --- */
        @media (max-width: 992px) {
            

            

            

            

            

            

            

            

            
        }

        @media (max-width: 576px) {
            

            .top-navbar {
                padding: 12px 15px;
                margin-bottom: 15px;
                gap: 10px;
                flex-direction: column;
                align-items: flex-start;
            }

            .top-navbar > .d-flex {
                width: 100%;
                justify-content: flex-end;
            }

            .dash-header h3 {
                font-size: 1.1rem;
            }

            .dash-header p {
                font-size: 0.75rem;
            }

            .content-card {
                padding: 15px;
                border-radius: 12px;
            }
        }

        /* --- Main Content Area --- */
        #main-content {
            margin-left: 240px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        @media (max-width: 992px) {
            #main-content {
                margin-left: 70px;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            #main-content {
                padding: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
        <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

    <!-- === MAIN CONTENT === -->
    <div id="main-content">
        
        <!-- === TOP NAVBAR === -->
        <div class="top-navbar">
            <div class="dash-header">
                <h3>Data Instruktur</h3>
                <p>Kelola profil instruktur, data keahlian, dan penugasan mengajar di Creativemu Academy.</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
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

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-3 py-2 px-3 small" role="alert">
                <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <div class="card-title-custom">
                    <i class="fas fa-list-check me-1"></i> Daftar Instruktur Terdaftar
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge px-2 py-1 rounded-pill fw-semibold" style="background-color: var(--light-purple); color: var(--primary-purple) !important; font-size: 0.75rem;">
                        Total: <?= isset($total_aktif) ? $total_aktif : 0; ?> Instruktur Aktif
                    </span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>NIP & Nama</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>CV</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($mentor) && is_array($mentor)): ?>
                            <?php $no = 1; foreach ($mentor as $m): ?>
                                <tr>
                                    <td class="fw-semibold text-muted"><?= $no++; ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border mb-1" style="font-size: 0.7rem;">NIP: <?= esc($m['nip'] ?? '-'); ?></span>
                                        <div class="fw-bold" style="color: var(--dark-purple); font-size: 0.88rem;"><?= esc($m['nama_mentor']); ?></div>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block"><i class="fas fa-envelope me-1"></i> <?= esc($m['email']); ?></small>
                                        <small class="text-muted d-block"><i class="fas fa-phone me-1"></i> <?= esc($m['telepon']); ?></small>
                                    </td>
                                    <td>
                                        <?php if($m['status'] == 'Aktif'): ?>
                                            <span class="badge bg-success" style="font-size: 0.72rem;">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary" style="font-size: 0.72rem;">Non-Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($m['cv'])): ?>
                                            <a href="<?= base_url('uploads/cv/' . $m['cv']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                                                <i class="fas fa-file-pdf text-danger me-1"></i> Lihat CV
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Tidak ada</span>
                                        <?php endif; ?>
                                    </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">Belum ada data instruktur yang tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script Tanggal Dinamis Bahasa Indonesia
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date();
        const dateEl = document.getElementById('current-date');
        if (dateEl) {
            dateEl.innerText = today.toLocaleDateString('id-ID', options);
        }
    </script>
</body>
</html>


