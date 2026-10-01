<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Terbitkan Sertifikat'); ?> - Creativemu Academy</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --dark-purple: #1e0f33;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f7fc;
            overflow-x: hidden;
            margin: 0;
            color: #4b4260;
        }

        /* Perbaikan Utama Layout agar tidak tertutup Sidebar */
        #main-content {
            margin-left: 260px; /* Menyesuaikan lebar standar sidebar */
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navbar Modern */
        .top-navbar {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(121, 75, 196, 0.04);
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
        }

        .dash-header h3 {
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 1.5rem;
            margin-bottom: 4px;
            letter-spacing: -0.02em;
        }

        .dash-header p {
            color: #8c83a5;
            font-size: 0.88rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .admin-profile img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-purple);
            box-shadow: 0 4px 10px rgba(121, 75, 196, 0.15);
        }

        /* Form Card Elegan */
        .form-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(121, 75, 196, 0.04);
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            transition: all 0.3s ease;
        }

        .form-label {
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--dark-purple);
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1.5px solid #e8e3f3;
            padding: 12px 16px;
            font-size: 0.88rem;
            background-color: #faf9fd;
            color: #2d2440;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #ffffff;
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 4px rgba(121, 75, 196, 0.08);
        }

        .form-control[readonly] {
            background-color: #f2f0f8;
            border-color: #e4dff2;
            color: #6c6384;
        }

        /* Tombol Modern */
        .btn-primary-custom {
            background: var(--sidebar-active-gradient);
            border: none;
            color: #fff;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 0.88rem;
            box-shadow: 0 6px 15px rgba(121, 75, 196, 0.25);
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(121, 75, 196, 0.35);
            opacity: 0.95;
        }

        .btn-secondary {
            background-color: #ede7f6;
            border: none;
            color: var(--dark-purple);
            border-radius: 12px;
            padding: 12px 22px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #e2d8f3;
            color: var(--dark-purple);
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #f6f1ff 0%, #f0e6ff 100%);
            border: 1px solid #dfd0fc;
            border-left: 4px solid var(--primary-purple);
            border-radius: 14px;
            padding: 16px 18px;
            color: #534277;
            font-size: 0.86rem;
            margin-bottom: 26px;
            box-shadow: 0 2px 8px rgba(121, 75, 196, 0.03);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 45px 20px;
            color: #8c83a5;
        }

        .empty-state i {
            font-size: 48px;
            color: #b293f0;
            margin-bottom: 16px;
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

        #main-content {
            animation: mainFadeIn 0.4s ease-out forwards;
        }

        @media (max-width: 992px) {
            #main-content {
                margin-left: 0;
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .top-navbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 18px;
                position: relative;
            }

            .admin-profile {
                width: 100%;
            }

            .form-card .card-body {
                padding: 18px !important;
            }

            .btn-primary-custom, .btn-secondary {
                width: 100%;
                text-align: center;
            }
        }
    </style>

    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>

<body>

<!-- SIDEBAR -->
<?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

<!-- MAIN -->
<div id="main-content">

    <!-- TOP NAVBAR -->
    <div class="top-navbar">
        <div class="dash-header">
            <h3>Terbitkan Sertifikat</h3>
            <p>Terbitkan sertifikat untuk peserta yang telah dinyatakan lulus.</p>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light"
                 id="current-date"
                 style="font-size: 0.82rem; font-weight: 600; color: #794bc4 !important; border: 1px solid #eee;">
                Memuat tanggal...
            </div>

            <div class="admin-profile">
                <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ?: 'admin-profile.jpg')); ?>"
                     alt="Foto Profil">
                <div>
                    <div class="fw-bold" style="color: var(--dark-purple); font-size: 0.92rem;">
                        <?= esc(session()->get('nama') ?? 'Super Admin'); ?>
                    </div>
                    <small class="text-muted" style="font-size: 0.75rem;">
                        Administrator
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- KEMBALI -->
    <div class="mb-3">
        <a href="<?= base_url('admin/sertifikat'); ?>"
           class="btn btn-secondary shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Kembali
        </a>
    </div>

    <!-- FORM -->
    <div class="form-card">
        <div class="card-body p-4 p-md-5">

            <div class="info-box d-flex align-items-center">
                <div>
                    <i class="fas fa-circle-info me-2 fs-5" style="color: var(--primary-purple);"></i>
                </div>
                <div>
                    Hanya peserta yang sudah dinyatakan <strong class="text-dark">LULUS</strong>
                    yang dapat diterbitkan sertifikatnya.
                </div>
            </div>

            <?php if (empty($pesertaLulus)) : ?>

                <div class="empty-state">
                    <i class="fas fa-user-graduate d-block"></i>
                    <h5 class="fw-bold mb-2" style="color: var(--dark-purple);">
                        Belum Ada Peserta Lulus
                    </h5>
                    <p class="mb-4 text-muted" style="font-size: 0.9rem;">
                        Belum terdapat data peserta yang dinyatakan lulus pada hasil ujian.
                    </p>
                    <a href="<?= base_url('admin/sertifikat'); ?>"
                       class="btn btn-primary-custom">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Sertifikat
                    </a>
                </div>

            <?php else : ?>

                <form action="<?= base_url('admin/sertifikat/store'); ?>"
                      method="post"
                      enctype="multipart/form-data">

                    <?= csrf_field(); ?>

                    <!-- PESERTA -->
                    <div class="mb-4">
                        <label for="id_users" class="form-label">
                            Pilih Peserta Lulus
                        </label>
                        <select name="id_users"
                                id="id_users"
                                class="form-select"
                                required>
                            <option value="">
                                -- Pilih Peserta --
                            </option>
                            <?php foreach ($pesertaLulus as $p) : ?>
                                <option value="<?= esc($p['id_user']); ?>"
                                        data-id-kelas="<?= esc($p['id_kelas']); ?>"
                                        data-nama-kelas="<?= esc($p['nama_kelas'] ?? ''); ?>"
                                        <?= (isset($selectedUser) && $selectedUser == $p['id_user']
                                            && isset($selectedKelas) && $selectedKelas == $p['id_kelas'])
                                            ? 'selected'
                                            : ''; ?>>
                                    <?= esc($p['nama_peserta'] ?? '-'); ?>
                                    —
                                    <?= esc($p['nama_kelas'] ?? '-'); ?>
                                    (Nilai: <?= esc($p['nilai'] ?? '-'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted mt-1" style="font-size: 0.78rem;">
                            Daftar diambil otomatis dari peserta yang sudah dinyatakan LULUS.
                        </div>
                    </div>

                    <!-- KELAS -->
                    <div class="mb-4">
                        <label for="nama_kelas" class="form-label">
                            Kelas
                        </label>
                        <input type="text"
                               id="nama_kelas"
                               class="form-control"
                               placeholder="Kelas akan terisi otomatis"
                               readonly>
                        <input type="hidden"
                               name="id_kelas"
                               id="id_kelas">
                    </div>

                    <!-- NOMOR SERTIFIKAT -->
                    <div class="mb-4">
                        <label class="form-label">
                            Nomor Sertifikat
                        </label>
                        <input type="text"
                               class="form-control"
                               value="Nomor akan dibuat otomatis saat diterbitkan"
                               readonly>
                        <div class="form-text text-muted mt-1" style="font-size: 0.78rem;">
                            Nomor sertifikat dibuat otomatis oleh sistem.
                        </div>
                    </div>

                    <!-- FILE -->
                    <div class="mb-4">
                        <label for="file_sertifikat" class="form-label">
                            File Sertifikat
                        </label>
                        <input type="file"
                               class="form-control"
                               id="file_sertifikat"
                               name="file_sertifikat"
                               accept=".pdf,.jpg,.jpeg,.png"
                               required>
                        <div class="form-text text-muted mt-1" style="font-size: 0.78rem;">
                            Format yang diperbolehkan: PDF, JPG, JPEG, PNG.
                        </div>
                    </div>

                    <!-- TOMBOL -->
                    <div class="d-flex gap-3 flex-wrap pt-2">
                        <a href="<?= base_url('admin/sertifikat'); ?>"
                           class="btn btn-secondary px-4">
                            Batal
                        </a>
                        <button type="submit"
                                class="btn btn-primary-custom">
                            <i class="fas fa-certificate me-2"></i>
                            Terbitkan Sertifikat
                        </button>
                    </div>

                </form>

            <?php endif; ?>

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

    // Otomatis mengisi kelas berdasarkan peserta yang dipilih
    const pesertaSelect = document.getElementById('id_users');
    const idKelasInput = document.getElementById('id_kelas');
    const namaKelasInput = document.getElementById('nama_kelas');

    function isiDataPeserta() {
        if (!pesertaSelect) return;

        const selectedOption =
            pesertaSelect.options[pesertaSelect.selectedIndex];

        if (!selectedOption || !pesertaSelect.value) {
            idKelasInput.value = '';
            namaKelasInput.value = '';
            return;
        }

        const idKelas =
            selectedOption.getAttribute('data-id-kelas') || '';

        const namaKelas =
            selectedOption.getAttribute('data-nama-kelas') || '';

        idKelasInput.value = idKelas;
        namaKelasInput.value = namaKelas;
    }

    if (pesertaSelect) {
        pesertaSelect.addEventListener('change', isiDataPeserta);

        // Isi otomatis saat halaman dibuka
        isiDataPeserta();
    }
</script>

</body>
</html>