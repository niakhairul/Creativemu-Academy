<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title ?? 'Edit Sertifikat'); ?> - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
        }

        * {
            box-sizing: border-box;
        }

        html, body { font-family: 'Poppins', sans-serif; background-color: #f7f5fd; font-size: 14px; overflow-x: hidden; margin: 0; }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f7f5fd;
        }

        ::-webkit-scrollbar-thumb {
            background: #b293f0;
            border-radius: 10px;
        }

        /* SIDEBAR */
        

        

        

        

        

        

        

        

        

        /* MAIN */
        

        .top-navbar {
            background: #fff;
            padding: 22px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
        }

        .dash-header h3 {
            font-weight: 800;
            color: var(--dark-purple);
            font-size: 1.6rem;
            margin-bottom: 4px;
        }

        .dash-header p {
            color: #8c83a5;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-profile img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--primary-purple);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-purple);
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.78rem;
        }

        /* EDIT CARD */
        .page-card {
            background: #fff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.06);
        }

        .section-title {
            color: var(--dark-purple);
            font-weight: 700;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--dark-purple);
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #e4dff0;
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 0.2rem rgba(121, 75, 196, 0.12);
        }

        .readonly-field {
            background-color: #f4f1f8 !important;
            color: #6c6680;
        }

        .current-file {
            background-color: #f4f1f8;
            border: 1px solid #e4dff0;
            border-radius: 10px;
            padding: 11px 13px;
            font-size: 0.85rem;
            word-break: break-all;
        }

        .btn-primary-custom {
            background: var(--sidebar-active-gradient);
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .btn-primary-custom:hover {
            color: #fff;
            opacity: 0.92;
        }

        .btn-back {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .required {
            color: #dc3545;
        }

        @keyframes mainFadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {

            

            

            .top-navbar {
                padding: 18px;
            }
        }

        @media (max-width: 576px) {

            

            .top-navbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 18px;
                padding-top: 58px;
                position: relative;
            }

            .admin-profile {
                width: 100%;
            }

            .edit-card-body {
                padding: 18px !important;
            }

            .button-group {
                flex-direction: column;
            }

            .button-group .btn {
                width: 100%;
            }
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
        small, .text-muted, .admin-info small { font-size: 0.78rem !important; }
        .form-label { font-size: 0.8rem !important; font-weight: 600 !important; }
        .form-control, .form-select { font-size: 0.85rem !important; }
        .btn { font-size: 0.82rem; font-weight: 600; }
        .table thead th { font-size: 0.75rem !important; font-weight: 600 !important; padding: 10px 12px !important; }
        .table tbody td { font-size: 0.8rem !important; padding: 10px 12px !important; }
        .badge { font-size: 0.75rem; font-weight: 600; }
    </style>

    <link rel="stylesheet"
          href="<?= base_url('assets/css/admin-responsive.css'); ?>">

    <script defer
            src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>

<body>

<!-- SIDEBAR -->
    <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

<!-- MAIN -->
<div id="main-content">

    <!-- TOP NAVBAR -->
    <div class="top-navbar">

        <div class="dash-header">
            <h3>Edit Sertifikat</h3>
            <p>Perbarui informasi sertifikat kelulusan peserta.</p>
        </div>

        <div class="d-flex align-items-center gap-4">

            <?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
</div>

            <div class="admin-profile">

                <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ?: 'admin-profile.jpg')); ?>"
                     alt="Foto Profil">

                <div class="admin-info">
                    <div style="font-weight: 700; color: #1e0f33;">
                        <?= esc(session()->get('nama')); ?>
                    </div>
                    <small>Administrator</small>
                </div>

            </div>

        </div>

    </div>

    <!-- ALERT -->
    <?php if (session()->getFlashdata('error')) : ?>

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-circle-exclamation me-2"></i>
            <?= esc(session()->getFlashdata('error')); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>

        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-circle-check me-2"></i>
            <?= esc(session()->getFlashdata('success')); ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>

    <?php endif; ?>

    <!-- CONTENT -->
    <div class="page-card">

        <div class="p-4 border-bottom">

            <div class="d-flex justify-content-between align-items-center gap-3">

                <div>
                    <h5 class="section-title mb-1">
                        <i class="fas fa-certificate me-2"></i>
                        Informasi Sertifikat
                    </h5>

                    <small class="text-muted">
                        Data peserta dan kelulusan tidak dapat diubah dari halaman ini.
                    </small>
                </div>

                <a href="<?= base_url('admin/sertifikat'); ?>"
                   class="btn btn-secondary btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali
                </a>

            </div>

        </div>

        <div class="p-4 edit-card-body">

            <form action="<?= base_url('admin/sertifikat/update/' . $sertifikat['id_sertifikat']); ?>"
                  method="post"
                  enctype="multipart/form-data">

                <?= csrf_field(); ?>

                <!-- PESERTA -->
                <div class="mb-3">

                    <label class="form-label">
                        Peserta
                    </label>

                    <input type="text"
                           class="form-control readonly-field"
                           value="<?= esc($sertifikat['nama_peserta'] ?? '-'); ?>"
                           readonly>

                </div>

                <!-- EMAIL -->
                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input type="text"
                           class="form-control readonly-field"
                           value="<?= esc($sertifikat['email'] ?? '-'); ?>"
                           readonly>

                </div>

                <!-- KELAS -->
                <div class="mb-3">

                    <label class="form-label">
                        Kelas
                    </label>

                    <input type="text"
                           class="form-control readonly-field"
                           value="<?= esc($sertifikat['nama_kelas'] ?? '-'); ?>"
                           readonly>

                </div>

                <!-- NILAI -->
                <div class="mb-3">

                    <label class="form-label">
                        Nilai
                    </label>

                    <input type="text"
                           class="form-control readonly-field"
                           value="<?= esc($sertifikat['nilai'] ?? '-'); ?>"
                           readonly>

                </div>

                <!-- STATUS KELULUSAN -->
                <div class="mb-3">

                    <label class="form-label">
                        Status Kelulusan
                    </label>

                    <input type="text"
                           class="form-control readonly-field"
                           value="<?= esc($sertifikat['status_kelulusan'] ?? '-'); ?>"
                           readonly>

                </div>

                <!-- NOMOR SERTIFIKAT -->
                <div class="mb-3">

                    <label class="form-label">
                        Nomor Sertifikat
                        <span class="required">*</span>
                    </label>

                    <input type="text"
                           name="nomor_sertifikat"
                           class="form-control"
                           value="<?= old('nomor_sertifikat', $sertifikat['nomor_sertifikat'] ?? ''); ?>"
                           required>

                </div>

                <!-- TANGGAL TERBIT -->
                <div class="mb-3">

                    <label class="form-label">
                        Tanggal Terbit
                        <span class="required">*</span>
                    </label>

                    <input type="date"
                           name="tanggal_terbit"
                           class="form-control"
                           value="<?= old('tanggal_terbit', $sertifikat['tanggal_terbit'] ?? ''); ?>"
                           required>

                </div>

                <!-- FILE SAAT INI -->
                <div class="mb-3">

                    <label class="form-label">
                        File Sertifikat Saat Ini
                    </label>

                    <div class="current-file">

                        <?php if (!empty($sertifikat['file_sertifikat'])) : ?>

                            <i class="fas fa-file me-2"
                               style="color: #794bc4;"></i>

                            <?= esc($sertifikat['file_sertifikat']); ?>

                        <?php else : ?>

                            <span class="text-muted">
                                Belum ada file sertifikat.
                            </span>

                        <?php endif; ?>

                    </div>

                </div>

                <!-- GANTI FILE -->
                <div class="mb-4">

                    <label class="form-label">
                        Ganti File Sertifikat
                    </label>

                    <input type="file"
                           name="file_sertifikat"
                           class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti file sertifikat.
                    </small>

                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2 button-group">

                    <a href="<?= base_url('admin/sertifikat'); ?>"
                       class="btn btn-light border">

                        <i class="fas fa-times me-1"></i>
                        Batal

                    </a>

                    <button type="submit"
                            class="btn btn-primary-custom">

                        <i class="fas fa-save me-1"></i>
                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Tanggal
    const currentDate = document.getElementById('current-date');

    if (currentDate) {

        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        currentDate.innerText =
            new Date().toLocaleDateString('id-ID', options);
    }
</script>

</body>
</html>


