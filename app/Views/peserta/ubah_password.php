<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf5ff;
            background-image: radial-gradient(#d8b4fe 1px, transparent 1px);
            background-size: 24px 24px;
            margin: 0;
            padding: 0;
        }

        .app-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #581c87 0%, #7c3aed 100%);
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 20px;
            box-shadow: 4px 0 20px rgba(124, 58, 237, 0.1);
            overflow-y: auto;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }

        .sidebar-menu a i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
            width: calc(100% - 260px);
        }
        /* =========================
           RESPONSIVE & MOBILE DRAWER
        ========================= */
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        .sidebar-close-btn {
            display: none;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background 0.2s ease;
        }
        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        @media (max-width: 991.98px) {
            .mobile-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: linear-gradient(135deg, #4a2fc9 0%, #7440e6 100%);
                color: white;
                padding: 14px 18px;
                position: sticky;
                top: 0;
                z-index: 990;
                box-shadow: 0 4px 18px rgba(116, 64, 230, 0.25);
            }

            .app-wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 280px;
                max-width: 82vw;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
                box-shadow: 10px 0 40px rgba(0, 0, 0, 0.35);
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
                max-width: 100% !important;
                padding: 20px 14px !important;
            }

            .top-navbar-desktop {
                display: none !important;
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 14px 10px !important;
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
        <span class="fw-bold fs-6 tracking-wide">
            <i class="bi bi-mortarboard-fill text-warning me-1"></i> Creativemu
        </span>
    </div>
    <span class="text-white small bg-white bg-opacity-20 px-2 py-1 rounded-pill">
        <i class="bi bi-shield-lock"></i> Ubah Password
    </span>
</div>

<!-- BACKDROP UNTUK MENUTUP SIDEBAR DI MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
<nav class="sidebar" id="sidebarMenu">

    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-white border-opacity-15">
        <a href="<?= base_url('peserta/dashboard') ?>" class="sidebar-brand p-0 m-0 border-0">
            <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i> Creativemu
        </a>
        <button type="button" class="sidebar-close-btn" id="sidebarClose" aria-label="Tutup Menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="sidebar-menu">

        <li>
            <a href="<?= base_url('peserta/dashboard') ?>">
                <i class="bi bi-grid-fill"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="<?= base_url('pelatihan/daftar-kelas-peserta') ?>">
                <i class="bi bi-journals"></i>
                Daftar Kelas Saya
            </a>
        </li>

        <li>
            <a href="<?= base_url('pelatihan/kelas') ?>">
                <i class="bi bi-mortarboard-fill"></i>
                KBM
            </a>
        </li>

        <li>
            <a href="<?= base_url('pelatihan/pengaturan') ?>" class="active">
                <i class="bi bi-gear-fill"></i>
                Pengaturan
            </a>
        </li>

        <li class="mt-5">
            <a href="<?= base_url('auth/logout') ?>"
               class="text-danger-subtle bg-danger bg-opacity-10">
                <i class="bi bi-box-arrow-left"></i>
                Keluar
            </a>
        </li>

    </ul>

</nav>

<div class="app-wrapper">


    <!-- CONTENT -->
    <div class="main-content">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-4 mb-4 px-4 py-3 top-navbar-desktop">
            <div class="container-fluid">

                <span class="navbar-brand mb-0 h5 fw-bold">
                    Ubah Password
                </span>

                <span class="text-muted">
                    <i class="fa-solid fa-user-circle me-1"></i>
                    Peserta
                </span>

            </div>
        </nav>


        <div class="container-fluid px-0">

            <!-- NOTIFIKASI ERROR -->
            <?php if (session()->getFlashdata('error')): ?>

                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4">

                    <strong>⚠️ Perhatian!</strong>
                    <?= session()->getFlashdata('error') ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

            <?php endif; ?>


            <!-- NOTIFIKASI SUCCESS -->
            <?php if (session()->getFlashdata('success')): ?>

                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4">

                    <strong>✅ Berhasil!</strong>
                    <?= session()->getFlashdata('success') ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

            <?php endif; ?>


            <!-- KARTU UBAH PASSWORD -->
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4 p-md-5">

                    <!-- HEADER -->
                    <div class="text-center mb-5">

                        <div class="d-inline-flex align-items-center justify-content-center
                                    rounded-circle bg-primary-subtle text-primary mb-3"
                             style="width: 70px; height: 70px; font-size: 30px;">
                            🔐
                        </div>

                        <h4 class="fw-bold mb-2">
                            Keamanan Akun
                        </h4>

                        <p class="text-muted mb-0">
                            Perbarui password akun Anda untuk menjaga keamanan akun.
                        </p>

                    </div>


                    <!-- FORM -->
                    <form
                        action="<?= base_url('pelatihan/update-password') ?>"
                        method="post"
                    >

                        <?= csrf_field() ?>


                        <!-- PASSWORD LAMA -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Password Lama
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password_lama"
                                    id="passwordLama"
                                    class="form-control"
                                    placeholder="Masukkan password lama"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-light border"
                                    onclick="togglePassword('passwordLama', this)"
                                >
                                    👁️
                                </button>

                            </div>

                        </div>


                        <!-- PASSWORD BARU -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Password Baru
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="password_baru"
                                    id="passwordBaru"
                                    class="form-control"
                                    placeholder="Masukkan password baru"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-light border"
                                    onclick="togglePassword('passwordBaru', this)"
                                >
                                    👁️
                                </button>

                            </div>

                            <small class="text-muted">
                                Gunakan minimal 8 karakter agar lebih aman.
                            </small>

                        </div>


                        <!-- KONFIRMASI PASSWORD -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Konfirmasi Password Baru
                            </label>

                            <div class="input-group">

                                <input
                                    type="password"
                                    name="konfirmasi_password"
                                    id="konfirmasiPassword"
                                    class="form-control"
                                    placeholder="Ulangi password baru"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn btn-light border"
                                    onclick="togglePassword('konfirmasiPassword', this)"
                                >
                                    👁️
                                </button>

                            </div>

                        </div>


                        <!-- TIPS -->
                        <div class="p-3 rounded-3 bg-light mb-4">

                            <div class="fw-semibold mb-2">
                                💡 Tips Password
                            </div>

                            <small class="text-muted">
                                Jangan gunakan password yang mudah ditebak dan
                                jangan membagikan password kepada orang lain.
                            </small>

                        </div>


                        <hr class="my-4">


                        <!-- BUTTON -->
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary rounded-pill px-4"
                            >
                                <i class="bi bi-shield-lock-fill me-1"></i>
                                Ubah Password
                            </button>

                            <a
                                href="<?= base_url('pelatihan/pengaturan') ?>"
                                class="btn btn-light border rounded-pill px-4"
                            >
                                Batal
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(id, button) {

    const input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
        button.innerHTML = "🙈";
    } else {
        input.type = "password";
        button.innerHTML = "👁️";
    }

}

// Mobile Sidebar Drawer Toggle
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebarMenu');
    const backdrop = document.getElementById('sidebarBackdrop');

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

    const navLinks = sidebar ? sidebar.querySelectorAll('li a') : [];
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });
});
</script>

</body>
</html>