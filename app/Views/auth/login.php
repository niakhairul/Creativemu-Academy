<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent-purple: #8C5ECF;
            --accent-hover: #7B4EC0;
            --text-dark: #2B2240;
            --text-muted: #8275A3;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Latar belakang persis warna ungu dari sampel Anda */
            background: #8E5EC7;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Ornamen Lingkaran Minimalis di Latar Belakang (Gaya Asli) */
        .bg-circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }

        .circle-1 { width: 120px; height: 120px; top: 12%; left: 10%; }
        .circle-2 { width: 80px; height: 80px; bottom: 15%; right: 12%; }
        .circle-3 { width: 160px; height: 160px; bottom: 10%; left: 15%; }

        /* Ornamen Kapsul Melayang (Gaya Asli) */
        .bg-capsule {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            z-index: 0;
            pointer-events: none;
        }

        .capsule-1 { width: 90px; height: 36px; top: 18%; right: 15%; transform: rotate(-15deg); }

        /* Container Utama */
        .login-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 940px;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 20px 50px rgba(60, 30, 100, 0.2);
            overflow: hidden;
            display: flex;
            flex-direction: row;
        }

        /* Sisi Kiri: Banner Ungu */
        .login-banner {
            flex: 1.1;
            background: #8E5EC7;
            padding: 45px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-banner::after {
            content: "";
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .banner-logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .banner-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            object-fit: cover;
            background: #ffffff;
            padding: 2px;
        }

        .banner-title-sm {
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
            letter-spacing: -0.2px;
        }

        .banner-subtitle-sm {
            font-size: 0.75rem;
            opacity: 0.85;
            margin: 0;
        }

        .banner-center-content h2 {
            font-weight: 800;
            font-size: 1.9rem;
            line-height: 1.25;
            margin-bottom: 14px;
            letter-spacing: -0.5px;
        }

        .banner-center-content p {
            font-size: 0.88rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .banner-footer {
            font-size: 0.75rem;
            opacity: 0.75;
        }

        /* Sisi Kanan: Form Login */
        .login-form-side {
            flex: 1;
            padding: 45px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            font-weight: 800;
            font-size: 1.65rem;
            color: var(--text-dark);
            letter-spacing: -0.3px;
            margin-bottom: 4px;
        }

        .form-subheading {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group-custom .form-control {
            background: #F4F1FB;
            border: 1px solid #E4DCF5;
            color: var(--text-dark);
            padding: 11px 16px 11px 42px;
            border-radius: 12px;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #A696CC;
            font-size: 0.9rem;
            z-index: 5;
            transition: color 0.2s;
        }

        .input-group-custom .form-control:focus {
            background: #ffffff;
            border-color: var(--accent-purple);
            box-shadow: 0 0 0 3px rgba(140, 94, 207, 0.15);
            color: var(--text-dark);
        }

        .input-group-custom .form-control:focus ~ .input-icon {
            color: var(--accent-purple);
        }

        .form-control::placeholder {
            color: #BCAEDC;
        }

        .btn-custom-login {
            background: #8E5EC7;
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.9rem;
            box-shadow: 0 6px 16px rgba(142, 94, 199, 0.3);
            transition: all 0.2s ease;
            margin-top: 8px;
            width: 100%;
        }

        .btn-custom-login:hover {
            background: #7B4EC0;
            box-shadow: 0 8px 20px rgba(142, 94, 199, 0.4);
            color: #ffffff;
        }

        .card-footer-text {
            text-align: center;
            margin-top: 22px;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .card-footer-text a {
            color: #8E5EC7;
            font-weight: 700;
            text-decoration: none;
        }

        .card-footer-text a:hover {
            text-decoration: underline;
        }

        /* Responsif Mobile */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }
            .login-banner, .login-form-side {
                padding: 30px;
            }
            .bg-circle, .bg-capsule {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Ornamen Latar Belakang Sesuai Konsep Asli -->
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>
    <div class="bg-circle circle-3"></div>
    <div class="bg-capsule capsule-1"></div>

    <div class="login-wrapper">
        <div class="login-card">
            
            <!-- Sisi Kiri: Banner -->
            <div class="login-banner">
                <div class="banner-logo-box">
                    <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>" class="banner-logo" alt="Logo">
                    <div>
                        <h6 class="banner-title-sm">Creativemu</h6>
                        <p class="banner-subtitle-sm">Academy Platform</p>
                    </div>
                </div>

                <div class="banner-center-content my-4">
                    <h2>Tingkatkan Skill dan Bangun Portofolio Bersama Creativemu Academy.</h2>
                    <p>Siapkan dirimu dengan skill yang mumpuni dan bangun portofolio yang berkualitas.</p>
                </div>

                <div class="banner-footer">
                    &copy; 2026 Creativemu Academy. All rights reserved.
                </div>
            </div>

            <!-- Sisi Kanan: Form Login -->
            <div class="login-form-side">
                
                <?php if(session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success border-0 shadow-sm rounded-3 small mb-3 py-2">
                        <i class="fas fa-check-circle me-1"></i> <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 small mb-3 py-2">
                        <i class="fas fa-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <h1 class="form-heading">Selamat Datang</h1>
                <p class="form-subheading">Silakan masukkan akun Anda untuk melanjutkan</p>

                <form action="<?= base_url('pelatihan/login/process') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group-custom">
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-custom-login">
                        <i class="fas fa-arrow-right-to-bracket me-2"></i> Masuk Sekarang
                    </button>
                </form>

                <div class="card-footer-text">
                    Belum punya akun? <a href="<?= base_url('pelatihan/register') ?>">Daftar di sini</a>
                </div>

            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>