<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* 4 Warna Utama Creativemu Academy */
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

        /* Sidebar 220px (Seragam dengan Pengaturan dan Dashboard Peserta) */
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

        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 24px 28px;
            width: calc(100% - 220px);
        }

        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(123, 90, 246, 0.05);
            transition: all 0.3s ease;
        }

        .bg-purple-soft {
            background: linear-gradient(135deg, var(--purple-soft) 0%, var(--purple-soft2) 100%);
        }

        .text-purple-custom {
            color: var(--color-purple);
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--color-purple);
            box-shadow: 0 0 0 3px rgba(123, 90, 246, 0.15);
        }

        .input-group-text-toggle {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            cursor: pointer;
            color: #94a3b8;
            padding: 0 14px;
            transition: color 0.2s ease;
        }

        .input-group-text-toggle:hover {
            color: var(--color-purple);
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .btn-gradient-submit {
            background: linear-gradient(135deg, #7b5af6 0%, #df6be0 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(123, 90, 246, 0.3);
            transition: all 0.25s ease;
        }

        .btn-gradient-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(123, 90, 246, 0.4);
            color: white;
        }

        .btn-cancel-custom {
            background: #f1f5f9;
            color: #64748b;
            border: none;
            font-weight: 600;
            padding: 10px 22px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-cancel-custom:hover {
            background: #e2e8f0;
            color: #334155;
        }

        /* Responsif Mobile & Drawer */
        .mobile-topbar {
            display: none;
            width: 100%;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 7, 35, 0.55);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }

        .sidebar-backdrop.show {
            display: block;
        }

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

            .app-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 260px;
                max-width: 82vw;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-close-btn {
                display: flex;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 16px 12px !important;
            }
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
        <i class="bi bi-person-circle"></i> <?= esc($user['nama'] ?? 'Peserta') ?>
    </a>
</div>

<!-- BACKDROP SIDEBAR MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebarMenu">
    <div class="d-flex align-items-start justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-10 position-relative">
        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand text-decoration-none d-flex flex-column align-items-center w-100 mb-0 pb-0 border-0 text-center">
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
            <a href="<?= base_url('pelatihan/kelas') ?>"><i class="bi bi-mortarboard-fill" style="color: var(--color-orange);"></i> KBM</a>
        </li>
        <li>
            <a href="<?= base_url('pelatihan/pengaturan') ?>" class="active"><i class="bi bi-gear-fill" style="color: var(--color-cyan);"></i> Pengaturan</a>
        </li>
        <li class="mt-3">
            <a href="<?= base_url('auth/logout') ?>" class="text-danger bg-danger bg-opacity-10"><i class="bi bi-box-arrow-left"></i> Keluar</a>
        </li>
    </ul>
</nav>

<div class="app-wrapper">
    <div class="main-content">
        <div class="container-fluid py-1" style="max-width: 860px; margin: 0 auto;">

            <!-- BREADCRUMB / BACK LINK -->
            <div class="d-flex align-items-center justify-content-between mb-3">
                <a href="<?= base_url('pelatihan/pengaturan') ?>" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 hover-text-primary">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Kembali ke Pengaturan Akun</span>
                </a>
                <span class="badge bg-purple-soft text-purple-custom rounded-pill px-3 py-1.5 fw-semibold">
                    <i class="fa-solid fa-shield-halved me-1"></i> Keamanan Akun
                </span>
            </div>

            <!-- NOTIFIKASI -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-3 py-2 small">
                    <strong>✅ Berhasil!</strong> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-3 py-2 small">
                    <strong>⚠️ Perhatian!</strong> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- KARTU FORM UBAH PASSWORD -->
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <!-- HEADER KARTU -->
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-purple-soft text-purple-custom mb-3"
                             style="width: 64px; height: 64px; font-size: 26px;">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h4 class="fw-bold mb-1" style="color: #1e1b4b;">
                            Keamanan Akun
                        </h4>
                        <p class="text-muted small mb-0">
                            Perbarui kata sandi akun Anda untuk menjaga keamanan akses portal peserta.
                        </p>
                    </div>

                    <!-- FORM -->
                    <form action="<?= base_url('pelatihan/update-password') ?>" method="post" id="formUbahPassword">
                        <?= csrf_field() ?>

                        <!-- PASSWORD LAMA -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small" for="passwordLama">
                                Kata Sandi Saat Ini <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    name="password_lama"
                                    id="passwordLama"
                                    class="form-control"
                                    placeholder="Masukkan kata sandi lama Anda"
                                    required
                                    autocomplete="current-password"
                                >
                                <span class="input-group-text input-group-text-toggle" onclick="togglePassword('passwordLama', this)" title="Lihat kata sandi">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <!-- PASSWORD BARU -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small" for="passwordBaru">
                                Kata Sandi Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    name="password_baru"
                                    id="passwordBaru"
                                    class="form-control"
                                    placeholder="Minimal 8 karakter"
                                    minlength="8"
                                    required
                                    autocomplete="new-password"
                                >
                                <span class="input-group-text input-group-text-toggle" onclick="togglePassword('passwordBaru', this)" title="Lihat kata sandi">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                            <small class="text-muted" style="font-size: 11.5px;">
                                <i class="fa-solid fa-circle-info me-1 text-primary"></i> Gunakan minimal 8 karakter agar akun tetap aman.
                            </small>
                        </div>

                        <!-- KONFIRMASI PASSWORD BARU -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small" for="konfirmasiPassword">
                                Ulangi Kata Sandi Baru <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input
                                    type="password"
                                    name="konfirmasi_password"
                                    id="konfirmasiPassword"
                                    class="form-control"
                                    placeholder="Ketik ulang kata sandi baru"
                                    minlength="8"
                                    required
                                    autocomplete="new-password"
                                >
                                <span class="input-group-text input-group-text-toggle" onclick="togglePassword('konfirmasiPassword', this)" title="Lihat kata sandi">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                            </div>
                            <div id="matchStatus" class="small mt-1" style="display: none; font-size: 11.5px;"></div>
                        </div>

                        <!-- TIPS KEAMANAN -->
                        <div class="p-3 rounded-3 mb-4 d-flex align-items-start gap-2.5" style="background-color: #faf5ff; border: 1px solid #f3e8ff;">
                            <i class="fa-solid fa-lightbulb text-warning mt-1 fs-6"></i>
                            <div class="small text-muted" style="font-size: 12px; line-height: 1.5;">
                                <strong class="text-dark d-block mb-0.5">Tips Keamanan:</strong>
                                Hindari menggunakan tanggal lahir atau kata yang mudah ditebak. Jangan bagikan kata sandi akun Anda kepada siapa pun.
                            </div>
                        </div>

                        <!-- TOMBOL AKSI -->
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                            <button type="submit" class="btn btn-gradient-submit d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Simpan Kata Sandi</span>
                            </button>
                            <a href="<?= base_url('pelatihan/pengaturan') ?>" class="btn btn-cancel-custom d-inline-flex align-items-center gap-1.5">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Batal</span>
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Toggle Password Visibility (Eye Icon)
function togglePassword(inputId, toggleElem) {
    const input = document.getElementById(inputId);
    const icon = toggleElem.querySelector('i');
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Real-time Match Validation
document.addEventListener('DOMContentLoaded', function () {
    const passBaru = document.getElementById('passwordBaru');
    const passKonf = document.getElementById('konfirmasiPassword');
    const matchStatus = document.getElementById('matchStatus');
    const form = document.getElementById('formUbahPassword');

    function checkMatch() {
        if (!passBaru || !passKonf || !matchStatus) return;
        const val1 = passBaru.value;
        const val2 = passKonf.value;

        if (val2.length === 0) {
            matchStatus.style.display = 'none';
            return;
        }

        matchStatus.style.display = 'block';
        if (val1 === val2) {
            matchStatus.className = 'small mt-1 text-success fw-semibold';
            matchStatus.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Kata sandi cocok.';
        } else {
            matchStatus.className = 'small mt-1 text-danger fw-semibold';
            matchStatus.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> Kata sandi belum cocok.';
        }
    }

    if (passBaru) passBaru.addEventListener('input', checkMatch);
    if (passKonf) passKonf.addEventListener('input', checkMatch);

    // Mobile Sidebar Drawer Toggle
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn  = document.getElementById('sidebarClose');
    const sidebar   = document.getElementById('sidebarMenu');
    const backdrop  = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('show');
        if (backdrop) backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);
});
</script>

</body>
</html>
