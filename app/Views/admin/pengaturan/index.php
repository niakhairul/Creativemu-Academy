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

        /* CARD STYLE */
        .card-custom {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.04);
            animation: fadeIn 0.6s ease;
            margin-bottom: 25px;
        }

        /* FOTO PREVIEW */
        .profile-avatar-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
        }
        .profile-avatar-container img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f0ecfa;
            box-shadow: 0 5px 15px rgba(121, 75, 196, 0.15);
        }
        .upload-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--primary-purple);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
        }
        .upload-badge:hover {
            transform: scale(1.1);
        }

        /* FORM CONTROLS */
        .form-label {
            font-weight: 500;
            color: var(--dark-purple);
            font-size: 0.9rem;
        }
        .form-control {
            border-radius: 10px;
            padding: 11px 15px;
            border: 1px solid #e2d9f3;
            font-size: 0.9rem;
        }
        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.25rem rgba(121, 75, 196, 0.15);
        }

        /* TOMBOL UTAMA */
        .btn-purple {
            background: var(--sidebar-active);
            border: none;
            border-radius: 12px;
            padding: 11px 25px;
            color: #fff;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(121, 75, 196, 0.3);
            transition: all 0.3s ease;
        }
        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.4);
            color: #fff;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
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
            <li class="nav-item"><a href="<?= base_url('admin/laporan'); ?>" class="nav-link"><i class="fas fa-file-lines"></i> <span>Laporan</span></a></li>
            <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link active"><i class="fas fa-gear"></i> <span>Pengaturan</span></a></li>
            <li class="nav-item mt-4"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <!-- === KONTEN UTAMA === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div>
                <h3 class="fw-bold m-0" style="color: var(--dark-purple);">Pengaturan Akun</h3>
                <p class="text-muted m-0 small">Perbarui informasi profil, foto, dan keamanan sandi akun administrator.</p>
            </div>
        </div>

        <!-- FORM PENGATURAN -->
        <div class="row">
            <!-- Kolom Kiri: Profil & Ganti Foto -->
            <div class="col-lg-4">
                <div class="card-custom text-center">
                    <h5 class="fw-bold mb-4 text-start" style="color: var(--dark-purple);"><i class="fas fa-user-circle text-purple me-2"></i> Foto Profil</h5>
                    
                    <div class="profile-avatar-container">
                        <!-- Tampilkan foto profil admin saat ini -->
                        <img src="<?= base_url('assets/img/' . (!empty($user['foto_profil']) ? $user['foto_profil'] : 'admin-profile.jpg')); ?>" alt="Admin Profile" id="previewImage">
                        <label for="fotoInput" class="upload-badge" title="Ganti Foto">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <p class="text-muted small mb-2">Format: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                </div>
            </div>

            <!-- Kolom Kanan: Form Edit Nama & Password -->
            <div class="col-lg-8">
                <div class="card-custom">
                    <h5 class="fw-bold mb-4" style="color: var(--dark-purple);"><i class="fas fa-sliders text-purple me-2"></i> Informasi & Keamanan Akun</h5>
                    
                    <form action="<?= base_url('admin/pengaturan/update'); ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        
                        <!-- Input File Tersembunyi -->
                        <input type="file" id="fotoInput" name="foto_profil" class="d-none" accept="image/*" onchange="previewFile(this)">

                        <div class="mb-3">
    <label class="form-label">Nama Lengkap Administrator</label>
    <input type="text" name="nama_admin" class="form-control" value="<?= esc($user['nama']); ?>" required>
</div>

<div class="mb-3">
    <label class="form-label">Alamat Email</label>
    <input type="email" name="email_admin" class="form-control" value="<?= esc($user['email']); ?>" required>
</div>

                        <hr class="my-4" style="border-color: #f0ecfa;">

                        <h6 class="fw-bold mb-3" style="color: var(--dark-purple);"><i class="fas fa-lock text-purple me-2"></i> Ubah Kata Sandi (Opsional)</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi Saat Ini</label>
                            <input type="password" name="password_lama" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah sandi">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kata Sandi Baru</label>
                                <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi sandi baru">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-purple">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Script JavaScript Pratinjau Foto Otomatis -->
    <script>
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>