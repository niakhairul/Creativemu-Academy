<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran — Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts: Inter / Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #ede7f6 100%);
            min-height: 100vh;
        }
        .hero-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(111, 66, 193, 0.08);
            border: 1px solid rgba(111, 66, 193, 0.1);
        }
        .btn-purple {
            background-color: #6f42c1;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-purple:hover {
            background-color: #59339d;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(111, 66, 193, 0.2);
        }
        .form-control:focus {
            border-color: #6f42c1;
            box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.15);
        }
        .result-card {
            border-radius: 1.25rem;
            border: none;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }
        .result-card:hover {
            transform: translateY(-2px);
        }
        .badge-custom-warning {
            background-color: #fff3cd;
            color: #856404;
            font-weight: 500;
        }
        .badge-custom-success {
            background-color: #d4edda;
            color: #155724;
            font-weight: 500;
        }
        .badge-custom-danger {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="container py-5">
        
        <!-- Tombol Kembali ke Beranda -->
        <div class="mb-4">
            <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="text-decoration-none text-muted fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Kotak Form Pencarian Utama -->
                <div class="hero-card p-4 p-md-5 mb-4">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-purple-subtle text-purple rounded-circle mb-3" style="width: 60px; height: 60px; background-color: rgba(111, 66, 193, 0.1); color: #6f42c1;">
                            <i class="bi bi-search fs-3"></i>
                        </div>
                        <h2 class="fw-bold text-dark">Cek Status Pendaftaran</h2>
                        <p class="text-muted">Masukkan Email atau Nomor HP yang Anda gunakan saat mendaftar kelas pelatihan.</p>
                    </div>

                    <form action="<?= base_url('pelatihan/status') ?>" method="get">
                        <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                            <span class="input-group-text bg-white border-0 ps-4 text-muted">
                                <i class="bi bi-envelope-or-phone"></i>
                            </span>
                            <input type="text" name="keyword" class="form-control border-0 py-3" placeholder="Contoh: nama@email.com atau 0812345..." value="<?= esc($keyword ?? '') ?>" required>
                            <button class="btn btn-purple px-4 px-md-5" type="submit">
                                <i class="bi bi-search me-1"></i> Cek Status
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Bagian Hasil Pencarian -->
                <?php if (isset($keyword)) : ?>
                    <div class="d-flex align-items-center justify-content-between mb-3 px-2">
                        <h5 class="fw-bold text-secondary m-0">Hasil Pencarian: <span class="text-dark">"<?= esc($keyword) ?>"</span></h5>
                    </div>

                    <?php if (empty($pendaftaran)) : ?>
                        <div class="card result-card p-4 text-center bg-white">
                            <div class="py-4">
                                <i class="bi bi-exclamation-circle text-warning display-4 mb-3"></i>
                                <h5 class="fw-bold text-dark">Data Tidak Ditemukan</h5>
                                <p class="text-muted mb-0">Tidak ada pendaftaran terdaftar dengan Email atau Nomor HP tersebut. Pastikan penulisan data sudah benar.</p>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php foreach ($pendaftaran as $row) : ?>
                            <div class="card result-card p-4 mb-3 bg-white">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                                    <div>
                                        <span class="text-uppercase text-muted fs-7 fw-bold tracking-wider">Program Pelatihan</span>
                                        <h4 class="fw-bold text-dark mb-0"><?= esc($row['nama_kelas'] ?? 'Kelas Pelatihan') ?></h4>
                                    </div>
                                    
                                    <!-- Badge Status Elegan -->
                                    <div>
                                        <?php if ($row['status_pendaftaran'] == 'Menunggu') : ?>
                                            <span class="badge badge-custom-warning px-3 py-2 rounded-pill fs-6">
                                                <i class="bi bi-clock-history me-1"></i> Menunggu Validasi
                                            </span>
                                        <?php elseif ($row['status_pendaftaran'] == 'Disetujui' || $row['status_pendaftaran'] == 'Diterima') : ?>
                                            <span class="badge badge-custom-success px-3 py-2 rounded-pill fs-6">
                                                <i class="bi bi-check-circle-fill me-1"></i> Disetujui / Diterima
                                            </span>
                                        <?php else : ?>
                                            <span class="badge badge-custom-danger px-3 py-2 rounded-pill fs-6">
                                                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <hr class="text-muted opacity-25">

                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block small">Nama Pendaftar</span>
                                        <strong class="text-dark"><?= esc($row['nama']) ?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block small">Kontak (Email / No HP)</span>
                                        <strong class="text-dark"><?= esc($row['email']) ?> / <?= esc($row['no_hp']) ?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block small">Tanggal Pendaftaran</span>
                                        <strong class="text-dark"><?= esc($row['tanggal_daftar']) ?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted d-block small">Metode Pembayaran</span>
                                        <strong class="text-dark text-capitalize"><?= esc($row['pembayaran']) ?></strong>
                                    </div>
                                </div>

                                <!-- Kotak Informasi Pesan Berdasarkan Status -->
                                <?php if ($row['status_pendaftaran'] == 'Ditolak') : ?>
                                    <div class="alert alert-danger border-0 rounded-3 mb-0 d-flex align-items-center">
                                        <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                                        <div><strong>Pendaftaran Ditolak.</strong> Silakan hubungi admin atau lakukan pendaftaran ulang dengan bukti pembayaran yang valid.</div>
                                    </div>
                                <?php elseif ($row['status_pendaftaran'] == 'Disetujui' || $row['status_pendaftaran'] == 'Diterima') : ?>
                                    <div class="alert alert-success border-0 rounded-3 mb-0 d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                                        <div><strong>Selamat!</strong> Pendaftaran Anda telah divalidasi dan disetujui oleh admin. Silakan bersiap mengikuti kelas.</div>
                                    </div>
                                <?php else : ?>
                                    <div class="alert alert-warning border-0 rounded-3 mb-0 d-flex align-items-center bg-light text-secondary">
                                        <i class="bi bi-hourglass-split fs-5 me-2 text-warning"></i>
                                        <div>Data pendaftaran Anda sudah masuk dan sedang dalam antrean verifikasi oleh admin. Mohon menunggu sebentar ya!</div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>