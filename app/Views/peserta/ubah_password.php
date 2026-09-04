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
    </style>
</head>

<body>

<div class="app-wrapper">

    <!-- SIDEBAR -->
    <nav class="sidebar">

        <a href="#" class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2 fs-4"></i>
            Creativemu
        </a>

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


    <!-- CONTENT -->
    <div class="main-content">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-4 mb-4 px-4 py-3">
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
</script>

</body>
</html>