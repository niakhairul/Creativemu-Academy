<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KBM Kelas - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* 4 Warna Sesuai Dashboard */
            --color-purple: #7b5af6;
            --color-pink: #df6be0;
            --color-orange: #e2a048;
            --color-cyan: #5ac5e8;
            
            --purple-deep: #5b3fd6;
            --purple-mid: #7b5af6;
            --purple-light: #9a7fff;
            --purple-soft: #f3f0ff;
            --purple-soft2: #e4deff;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(123, 90, 246, 0.4) transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(120deg, #f7f5ff, #f2efff, #faf8ff, #f0ebff);
            background-size: 300% 300%;
            animation: bgFlow 22s ease infinite;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #1e293b;
            font-size: 14px;
            zoom: 1;
        }

        @keyframes bgFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar disamakan persis dengan Dashboard (lebar 220px) */
        .sidebar {
            width: 220px;
            background:
                radial-gradient(circle at 15% 12%, rgba(90, 197, 232, 0.2) 0%, rgba(255, 255, 255, 0) 45%),
                linear-gradient(165deg, #5b3fd6 0%, #7b5af6 35%, #df6be0 75%, #5ac5e8 100%);
            background-size: 200% 200%, 220% 220%;
            animation: sidebarGlow 14s ease infinite;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px 14px;
            box-shadow: 6px 0 34px rgba(123, 90, 246, 0.25);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.035) 0px,
                rgba(255, 255, 255, 0.035) 2px,
                transparent 2px,
                transparent 14px
            );
            pointer-events: none;
        }

        @keyframes sidebarGlow {
            0% { background-position: 0% 0%, 0% 0%; }
            50% { background-position: 100% 100%, 100% 100%; }
            100% { background-position: 0% 0%, 0% 0%; }
        }

        .sidebar-brand {
            font-size: 16px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu li {
            margin-bottom: 6px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 9px 12px;
            border-radius: 9px;
            font-weight: 500;
            font-size: 13.5px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateX(4px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(226, 160, 72, 0.4), rgba(90, 197, 232, 0.3));
            box-shadow: 0 6px 18px rgba(20, 5, 60, 0.2), inset 3px 0 0 var(--color-orange);
        }

        .sidebar-menu a i {
            font-size: 15px;
            margin-right: 9px;
        }

        /* Menyesuaikan margin konten utama dengan lebar sidebar (220px) */
        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 24px 28px;
            width: calc(100% - 220px);
        }

        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(123, 90, 246, 0.05);
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(123, 90, 246, 0.12) !important;
        }

        .materi-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(123, 90, 246, 0.15);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(123, 90, 246, 0.04);
        }

        .materi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(123, 90, 246, 0.1);
            border-color: rgba(123, 90, 246, 0.35);
        }

        .materi-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: rgba(90, 197, 232, 0.15);
            color: var(--color-cyan);
            font-size: 16px;
        }

        .materi-badge {
            display: inline-block;
            width: fit-content;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(223, 107, 224, 0.15);
            color: var(--color-pink);
            font-size: 11px;
            font-weight: 700;
        }

        /* Nav Tabs disamakan nuansanya dengan Dashboard */
        .nav-tabs {
            border-bottom: none;
            gap: 6px;
        }

        .nav-tabs .nav-link {
            border: 1px solid rgba(123, 90, 246, 0.2);
            color: #64748b;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8);
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: var(--color-purple);
            background: var(--purple-soft);
            border-color: rgba(123, 90, 246, 0.4);
        }

        .nav-tabs .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--color-purple) 0%, var(--color-pink) 100%);
            border-color: transparent;
            box-shadow: 0 4px 16px rgba(123, 90, 246, 0.25);
        }

        .exam-status-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(243,240,255,0.6) 100%);
            border: 1px solid rgba(123, 90, 246, 0.15);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .exam-status-card:hover {
            box-shadow: 0 8px 24px rgba(123, 90, 246, 0.1);
            border-color: rgba(123, 90, 246, 0.35);
        }

        .mobile-topbar { display: none; width: 100%; }
        .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(15, 7, 35, 0.55); backdrop-filter: blur(4px); z-index: 1040; }
        .sidebar-backdrop.show { display: block; }
        .sidebar-close-btn { 
            display: none; 
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 15px;
        }

        @media (max-width: 991.98px) {
            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #5b3fd6 0%, #7b5af6 100%);
                color: white;
                padding: 12px 16px;
                position: sticky;
                top: 0;
                z-index: 990;
                box-shadow: 0 4px 18px rgba(123, 90, 246, 0.25);
            }
            .app-wrapper { flex-direction: column; }
            .sidebar {
                width: 260px;
                max-width: 82vw;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
            }
            .sidebar.show { transform: translateX(0); }
            .sidebar-close-btn { display: flex; }
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 16px 12px !important;
            }
            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 6px;
            }
            .nav-tabs .nav-link { white-space: nowrap; }
        }
    </style>
</head>

<body>

<!-- TOPBAR KHUSUS MOBILE -->
<div class="mobile-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-sm btn-link text-white p-0 fs-3 text-decoration-none lh-1 shadow-none" id="sidebarToggle" aria-label="Buka Menu">
            <i class="bi bi-list"></i>
        </button>
        <span class="fw-bold fs-6">
            <i class="bi bi-mortarboard-fill me-1" style="color: var(--color-orange);"></i> Creativemu
        </span>
    </div>
    <a href="<?= base_url('pelatihan/pengaturan') ?>" class="text-white text-decoration-none small d-flex align-items-center gap-1 bg-white bg-opacity-20 px-2.5 py-1 rounded-pill" style="font-size: 13px;">
        <i class="bi bi-person-circle"></i> Peserta
    </a>
</div>

<!-- BACKDROP SIDEBAR MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR (DISAMAKAN PERSIS DENGAN DASHBOARD) -->
<nav class="sidebar" id="sidebarMenu">
    <div class="d-flex align-items-start justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-10 position-relative">
        <a href="#" class="sidebar-brand text-decoration-none d-flex flex-column align-items-center w-100 mb-0 pb-0 border-0 text-center">
            <div style="background-color: #ffffff; border-radius: 14px; padding: 10px 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.22); width: 90%; margin: 0 auto 12px auto;">
                <img src="<?= base_url('assets/img/logo_creativemu_admin.png'); ?>" alt="Logo" style="width: 100%; max-width: 180px; height: auto; max-height: 55px; object-fit: contain; display: block;">
            </div>
            <div>
                <span class="fs-6 fw-bold d-block text-white lh-1 mb-1">Creativemu</span>
                <span style="font-size: 9.5px; letter-spacing: 1px; color: var(--color-cyan); font-weight: 600;">ACADEMY</span>
            </div>
        </a>
        <button type="button" class="sidebar-close-btn mt-1 position-absolute end-0" style="top: 0;" id="sidebarClose" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="<?= base_url('peserta/dashboard') ?>"><i class="bi bi-grid-fill" style="color: var(--color-cyan);"></i> Dashboard</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>"><i class="bi bi-journals" style="color: var(--color-pink);"></i> Daftar Kelas Saya</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/kelas') ?>" class="active"><i class="bi bi-mortarboard-fill" style="color: var(--color-orange);"></i> KBM</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/pengaturan') ?>"><i class="bi bi-gear-fill" style="color: var(--color-cyan);"></i> Pengaturan</a>
        </li>
        <li class="mt-3">
            <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </li>
    </ul>
</nav>

<div class="app-wrapper">
    <div class="main-content">
        <div class="container-fluid py-1">




<?php
$groupedPertanyaan = [];
if (!empty($pertanyaan)) {
    foreach ($pertanyaan as $q) {
        $groupedPertanyaan[$q['kategori']][] = $q;
    }
}
?>



<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Terima kasih!</strong>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>❌ Gagal!</strong>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>


    <!-- Header -->
    <div class="card shadow-sm mb-4" style="border-radius: 16px; border: 1px solid rgba(0,0,0,0.05);">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <?php
                $fallback_url = base_url('pelatihan/daftar-kelas-peserta');
                $back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $fallback_url;
                // Mencegah infinite loop jika di-refresh
                if (strpos($back_url, 'pelatihan/angket') !== false) {
                    $back_url = base_url('pelatihan/kelas'); 
                }
                ?>
                <a href="<?= esc($back_url) ?>" class="btn btn-sm mb-2 shadow-sm text-white" style="background: var(--color-purple, #7c5cfa); border: none; border-radius: 8px; font-weight: 500; padding: 6px 16px;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <h4 class="fw-bold mb-1 mt-2 text-dark">Angket Evaluasi Pelatihan</h4>
                <p class="text-muted mb-0 small">Silakan isi angket sebagai evaluasi terhadap pelatihan yang telah Anda ikuti.</p>
            </div>
            <div class="d-none d-md-block">
                <i class="bi bi-ui-checks-grid" style="font-size: 3rem; opacity: 0.5; color: var(--color-purple, #7c5cfa);"></i>
            </div>
        </div>
    </div>

    <!-- Informasi -->
    <div class="alert shadow-sm mb-4 border-0" style="background-color: #f0ebff; border-left: 4px solid var(--color-purple, #7c5cfa) !important; color: #4b368c; border-radius: 8px;">
        <i class="bi bi-info-circle-fill me-2"></i>
        Jawablah setiap pertanyaan sesuai dengan pengalaman Anda selama mengikuti pelatihan.
    </div>

    <?php if ($sudahIsi): ?>
        <!-- Jika Sudah Mengisi -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 70px;"></i>
                </div>
                <h4 class="fw-bold text-success">Angket Sudah Diisi</h4>
                <p class="text-muted mb-0">Terima kasih, Anda sudah mengisi angket evaluasi pelatihan.</p>
            </div>
        </div>
    <?php else: ?>
        <!-- Form Angket -->
        <form action="<?= base_url('pelatihan/angket/simpan') ?>" method="post" id="formAngket">
            <?= csrf_field(); ?>
            <input type="hidden" name="kelas_id" value="<?= esc($pendaftaran['id_kelas'] ?? '') ?>">

            <?php $no = 1; ?>
            <?php foreach ($groupedPertanyaan as $kategori => $items): ?>
                <div class="card shadow-sm mb-4 border-0" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important; border: 1px solid rgba(0,0,0,0.05) !important;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-4" style="font-size: 16px; border-bottom: 2px solid #f0ebff; padding-bottom: 12px;">
                            <i class="bi bi-chat-square-text-fill me-2 text-opacity-75" style="color: var(--color-purple);"></i><?= esc($kategori) ?>
                        </h5>
                        <?php foreach ($items as $q): ?>
                            <?php
                            $idPertanyaan = $q['id_angket_pertanyaan'];
                            $tipe = $q['tipe'];
                            $opsi = json_decode($q['opsi_jawaban'], true) ?? [];
                            $isMahasiswaCheck = (strpos($q['pertanyaan'], 'Semester') !== false);
                            ?>
                            <div class="mb-4 pertanyaan-item" <?= $isMahasiswaCheck ? 'id="pertanyaan-semester" style="display:none;"' : '' ?>>
                                <label class="fw-semibold mb-3 text-dark" style="font-size: 14.5px;">
                                    <?= $no++ ?>. <?= esc($q['pertanyaan']) ?>
                                    <span class="text-danger">*</span>
                                </label>

                                <?php if ($tipe === 'pilihan'): ?>
                                    <?php
                                    // Check marker kelas database
                                    if (!empty($opsi) && $opsi[0] === '__KELAS_DATABASE__') {
                                        echo '<select name="jawaban['.$idPertanyaan.']" class="form-select" required>';
                                        echo '<option value="">-- Pilih Kelas --</option>';
                                        if (!empty($semuaKelas)) {
                                            foreach ($semuaKelas as $kls) {
                                                echo '<option value="'.esc($kls['nama_kelas']).'">'.esc($kls['nama_kelas']).'</option>';
                                            }
                                        }
                                        echo '</select>';
                                    } else {
                                        foreach ($opsi as $op):
                                            $isStatusInput = (strpos(strtolower($q['pertanyaan']), 'status') !== false || strpos(strtolower($q['pertanyaan']), 'kesibukan') !== false);
                                    ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input <?= $isStatusInput ? 'status-kesibukan' : '' ?>"
                                                   type="radio"
                                                   name="jawaban[<?= $idPertanyaan ?>]"
                                                   value="<?= esc($op) ?>"
                                                   <?= $isMahasiswaCheck ? '' : 'required' ?>
                                                   style="cursor: pointer;">
                                            <label class="form-check-label text-muted" style="cursor: pointer; font-size: 14px;"><?= esc($op) ?></label>
                                        </div>
                                    <?php
                                        endforeach;
                                    }
                                    ?>

                                <?php elseif ($tipe === 'rating'): ?>
                                    <div class="d-flex flex-wrap gap-3">
                                        <?php
                                        $skalaLabel = [
                                            1 => 'Kurang Puas',
                                            2 => 'Cukup Puas',
                                            3 => 'Puas',
                                            4 => 'Sangat Puas'
                                        ];
                                        for ($i = 1; $i <= 4; $i++):
                                        ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jawaban[<?= $idPertanyaan ?>]" value="<?= $i ?>" required style="cursor: pointer;">
                                                <label class="form-check-label text-muted" style="cursor: pointer; font-size: 14px;"><?= $i ?> - <?= $skalaLabel[$i] ?></label>
                                            </div>
                                        <?php endfor; ?>
                                    </div>

                                <?php elseif ($tipe === 'essay'): ?>
                                    <textarea name="jawaban[<?= $idPertanyaan ?>]" class="form-control" rows="3" placeholder="Tuliskan jawaban Anda di sini..." required style="border-radius: 8px;"></textarea>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="text-end mb-5">
                <button type="submit" class="btn px-4 btn-lg rounded-pill shadow-sm text-white hover-shadow" style="background: var(--color-purple, #7c5cfa); border: none; font-weight: 600; transition: all 0.3s ease;">
                    <i class="bi bi-send me-2"></i> Kirim Angket Evaluasi
                </button>
            </div>
        </form>
    <?php endif; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusInputs = document.querySelectorAll('.status-kesibukan');
    const semesterQuestion = document.getElementById('pertanyaan-semester');

    if (statusInputs.length > 0 && semesterQuestion) {
        statusInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                if (this.value === 'Mahasiswa Aktif') {
                    semesterQuestion.style.display = 'block';
                    const semInputs = semesterQuestion.querySelectorAll('input, select, textarea');
                    semInputs.forEach(el => el.setAttribute('required', 'required'));
                } else {
                    semesterQuestion.style.display = 'none';
                    const semInputs = semesterQuestion.querySelectorAll('input, select, textarea');
                    semInputs.forEach(el => el.removeAttribute('required'));
                }
            });
        });
    }
});
</script>



    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Toggle Sidebar Mobile
    const toggleBtn = document.getElementById("sidebarToggle");
    const closeBtn = document.getElementById("sidebarClose");
    const sidebar = document.getElementById("sidebarMenu");
    const backdrop = document.getElementById("sidebarBackdrop");

    function openSidebar() {
        if (sidebar) sidebar.classList.add("show");
        if (backdrop) backdrop.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove("show");
        if (backdrop) backdrop.classList.remove("show");
        document.body.style.overflow = "";
    }

    if (toggleBtn) toggleBtn.addEventListener("click", openSidebar);
    if (closeBtn) closeBtn.addEventListener("click", closeSidebar);
    if (backdrop) backdrop.addEventListener("click", closeSidebar);
});
</script>
</body>
</html>
