<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent-purple: #8E5EC7;
            --accent-hover: #7B4EC0;
            --text-dark: #2B2240;
            --text-muted: #8275A3;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #8E5EC7; 
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Ornamen Latar Belakang */
        .bg-circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }
        .circle-1 { width: 150px; height: 150px; top: 10%; left: 5%; }
        .circle-2 { width: 100px; height: 100px; bottom: 10%; right: 5%; }
        .circle-3 { width: 200px; height: 200px; bottom: -50px; left: 15%; }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(60, 30, 100, 0.25);
            z-index: 10;
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }

        .brand-logo-container {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px auto;
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid #ffffff;
            box-shadow: 0 6px 15px rgba(111, 82, 168, 0.2);
        }

        .brand-logo { width: 100%; height: 100%; object-fit: cover; }

        .main-heading { font-weight: 800; font-size: 1.6rem; color: var(--text-dark); margin-bottom: 5px; }
        .sub-heading { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 25px; }
        .form-label { font-weight: 600; font-size: 0.8rem; color: var(--text-dark); margin-bottom: 6px; }

        .form-control, .form-select {
            background: #F4F1FB;
            border: 1px solid #E4DCF5;
            color: var(--text-dark);
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            background: #ffffff;
            border-color: var(--accent-purple);
            box-shadow: 0 0 0 3px rgba(142, 94, 199, 0.15);
        }

        .btn-custom-register {
            background: var(--accent-purple);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
            margin-top: 10px;
        }

        .btn-custom-register:hover { background: var(--accent-hover); color: #ffffff; }

        .card-footer-text { text-align: center; margin-top: 20px; font-size: 0.85rem; color: var(--text-muted); }
        .card-footer-text a { color: var(--accent-purple); font-weight: 700; text-decoration: none; }
    </style>
</head>
<body>

    <!-- Background Elements -->
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>
    <div class="bg-circle circle-3"></div>

    <div class="container d-flex align-items-center justify-content-center min-vh-100 py-5">
        <div class="card register-card border-0">
            <div class="card-body p-0">

                <div class="text-center">
                    <div class="brand-logo-container">
                        <!-- Pastikan path gambar ini benar -->
                        <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>" class="brand-logo" alt="Logo">
                    </div>
                    <h3 class="main-heading">Registrasi Peserta</h3>
                    <p class="sub-heading">Bergabunglah dengan Creativemu Academy</p>
                </div>

                <?php if(session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger border-0 rounded-3 small mb-3">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('register/save') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
    <label class="form-label">Nama Lengkap</label>
    <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="08..." required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Konfirmasi</label>
                            <input type="password" name="konfirmasi_password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-custom-register w-100">Daftar Sekarang</button>
                </form>

                <div class="card-footer-text">
                    Sudah punya akun? <a href="<?= base_url('pelatihan/login') ?>">Login di sini</a>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>