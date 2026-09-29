<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Mencegah zoom berlebih saat di-zoom atau disentuh di mobile -->
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
            --sidebar-bg: #22133c;
            --sidebar-active: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --primary-purple: #794bc4;
            --dark-purple: #1e0f33;
            --bg-light: #f7f5fd;
        }

        * {
            box-sizing: border-box;
        }

        html, body { font-family: 'Poppins', sans-serif; background-color: #f7f5fd; font-size: 14px; overflow-x: hidden; margin: 0; }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #f7f5fd;
        }

        ::-webkit-scrollbar-thumb {
            background: #b293f0;
            border-radius: 10px;
        }

        /* SIDEBAR UTAMA */
        
        
        
        
        
        
        
        
        

        /* MAIN CONTENT AREA */
        

        .top-navbar {
            background: #ffffff;
            padding: 14px 20px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
            animation: fadeInDown 0.5s ease;
        }

        /* CARD STYLE */
        .card-custom {
            background: #ffffff;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            border: 1px solid rgba(121, 75, 196, 0.04);
            animation: fadeIn 0.5s ease;
            margin-bottom: 20px;
        }

        /* FOTO PREVIEW - Ukuran Ringkas */
        .profile-avatar-container {
            position: relative;
            width: 90px;
            height: 90px;
            margin: 0 auto 15px;
        }
        .profile-avatar-container img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0ecfa;
            box-shadow: 0 4px 10px rgba(121, 75, 196, 0.15);
        }
        .upload-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--primary-purple);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            transition: transform 0.2s ease;
            font-size: 0.75rem;
        }
        .upload-badge:hover {
            transform: scale(1.1);
        }

        /* FORM CONTROLS */
        .form-label {
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.78rem;
            margin-bottom: 4px;
        }
        .form-control {
            border-radius: 8px;
            padding: 7px 12px;
            border: 1px solid #e2d9f3;
            font-size: 0.78rem;
        }
        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.15rem rgba(121, 75, 196, 0.15);
        }

        /* TOMBOL UTAMA */
        .btn-purple {
            background: var(--sidebar-active);
            border: none;
            border-radius: 8px;
            padding: 7px 18px;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(121, 75, 196, 0.3);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
        }
        .btn-purple:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(121, 75, 196, 0.4);
            color: #fff;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 992px) {
            
            
            
        }

        #main-content {
            margin-left: 240px;
            padding: 20px;
            min-height: 100vh;
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

        /* STANDAR TYPOGRAPHY */
        .page-title, .top-navbar h3, .top-navbar h4, h3.fw-bold, .dash-header h3 { font-size: 1.25rem !important; font-weight: 700 !important; }
        .page-subtitle, .top-navbar p, p.text-muted, .dash-header p { font-size: 0.8rem !important; }
        .admin-info h6 { font-size: 0.88rem !important; font-weight: 600 !important; }
        small, .text-muted, .admin-info small, .form-text { font-size: 0.78rem !important; font-weight: 400 !important; }
        .form-label { font-size: 0.8rem !important; font-weight: 600 !important; }
        .form-control, .form-select { font-size: 0.85rem !important; font-weight: 400 !important; }
        .btn { font-size: 0.82rem !important; font-weight: 600 !important; padding: 8px 16px !important; }
        .card-custom h6.fw-bold { font-size: 1rem !important; font-weight: 700 !important; }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- === SIDEBAR UTAMA === -->
        <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

    <!-- === KONTEN UTAMA === -->
    <div id="main-content">
        
        <!-- TOP NAVBAR -->
        <div class="top-navbar">
            <div>
                <h3 class="fw-bold m-0" style="color: var(--dark-purple); font-size: 1.25rem;">Pengaturan Akun</h3>
                <p class="text-muted m-0" style="font-size: 0.75rem;">Perbarui informasi profil, foto, dan keamanan sandi akun administrator.</p>
            </div>
        </div>

        <!-- FORM PENGATURAN -->
        <div class="row g-3">
            <!-- Kolom Kiri: Profil & Ganti Foto -->
            <div class="col-lg-4">
                <div class="card-custom text-center">
                    <h6 class="fw-bold mb-3 text-start" style="color: var(--dark-purple); font-size: 0.9rem;">
                        <i class="fas fa-user-circle me-1" style="color: var(--primary-purple);"></i> Foto Profil
                    </h6>
                    
                    <div class="profile-avatar-container">
                        <!-- Tampilkan foto profil admin saat ini -->
                        <img src="<?= base_url('assets/img/' . (!empty($user['foto_profil']) ? $user['foto_profil'] : 'admin-profile.jpg')); ?>" alt="Admin Profile" id="previewImage">
                        <label for="fotoInput" class="upload-badge" title="Ganti Foto">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 0.72rem;">Format: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
                </div>
            </div>

            <!-- Kolom Kanan: Form Edit Nama & Password -->
            <div class="col-lg-8">
                <div class="card-custom">
                    <h6 class="fw-bold mb-3" style="color: var(--dark-purple); font-size: 0.9rem;">
                        <i class="fas fa-sliders me-1" style="color: var(--primary-purple);"></i> Informasi & Keamanan Akun
                    </h6>
                    
                    <form action="<?= base_url('admin/pengaturan/update'); ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        
                        <!-- Input File Tersembunyi -->
                        <input type="file" id="fotoInput" name="foto_profil" class="d-none" accept="image/*" onchange="previewFile(this)">

                        <div class="mb-2">
                            <label class="form-label">Nama Lengkap Administrator</label>
                            <input type="text" name="nama_admin" class="form-control" value="<?= esc($user['nama']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email_admin" class="form-control" value="<?= esc($user['email']); ?>" required>
                        </div>

                        <hr class="my-3" style="border-color: #f0ecfa;">

                        <h6 class="fw-bold mb-2" style="color: var(--dark-purple); font-size: 0.85rem;">
                            <i class="fas fa-lock me-1" style="color: var(--primary-purple);"></i> Ubah Kata Sandi (Opsional)
                        </h6>
                        
                        <div class="mb-2">
                            <label class="form-label">Kata Sandi Saat Ini</label>
                            <input type="password" name="password_lama" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah sandi">
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Kata Sandi Baru</label>
                                <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi sandi baru">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-purple">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
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


