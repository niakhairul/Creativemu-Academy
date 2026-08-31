<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Ulang Bukti Pembayaran - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            min-height: 100vh;
        }
        .card-custom { 
            border-radius: 24px; 
            border: 1px solid rgba(221, 214, 254, 0.8); 
            box-shadow: 0 15px 35px rgba(124, 58, 237, 0.08); 
        }
        .text-purple { color: #7c3aed; }
        .bg-purple-soft { background-color: #f3e8ff; border: 1px solid #e9d5ff; }
        .form-control:disabled, .form-control[readonly] {
            background-color: #f8fafc;
            color: #475569;
            border-color: #e2e8f0;
        }
        .btn-custom { 
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); 
            color: #fff; 
            border-radius: 12px; 
            font-weight: 700; 
            padding: 12px 20px; 
            border: none; 
            transition: all 0.3s ease;
        }
        .btn-custom:hover { 
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); 
            color: #fff; 
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        .btn-outline-custom {
            border-radius: 12px;
            font-weight: 600;
            padding: 12px 20px;
            border-color: #cbd5e1;
            color: #64748b;
        }
        .btn-outline-custom:hover {
            background-color: #f1f5f9;
            color: #334155;
            border-color: #94a3b8;
        }
        .upload-box {
            border: 2px dashed #c4b5fd;
            background-color: #faf5ff;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .upload-box:hover {
            border-color: #7c3aed;
            background-color: #f3e8ff;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-8">
            <div class="card card-custom p-4 p-md-5 bg-white">
                
                <!-- Header Title -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-purple-soft text-purple rounded-circle mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-cloud-arrow-up fs-3"></i>
                    </div>
                    <h3 class="fw-bold text-dark">Upload Ulang Bukti Pembayaran</h3>
                    <p class="text-muted small">Periksa kembali data pendaftaran Anda di bawah ini dan unggah bukti pembayaran yang valid.</p>
                </div>

                <!-- Flash Message Error -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger small mb-4 rounded-4 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Form Start -->
                <form action="<?= base_url('pelatihan/proses_upload_ulang/' . $pendaftaran['id_pendaftaran']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <!-- Nama Lengkap -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Nama Lengkap</label>
                            <input type="text" class="form-control rounded-10" value="<?= esc($pendaftaran['nama'] ?? '') ?>" readonly>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Email</label>
                            <input type="text" class="form-control rounded-10" value="<?= esc($pendaftaran['email'] ?? '') ?>" readonly>
                        </div>

                        <!-- No HP -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Nomor WhatsApp / HP</label>
                            <input type="text" class="form-control rounded-10" value="<?= esc($pendaftaran['no_hp'] ?? '') ?>" readonly>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Jenis Kelamin</label>
                            <input type="text" class="form-control rounded-10" value="<?= esc($pendaftaran['jenis_kelamin'] ?? '') ?>" readonly>
                        </div>

                        <!-- Status Pendaftaran / Status -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Status</label>
                            <input type="text" class="form-control rounded-10 text-danger fw-semibold" value="<?= esc($pendaftaran['status'] ?? $pendaftaran['status_pembayaran'] ?? '') ?>" readonly>
                        </div>

                        <!-- Tempat / Lokasi Pelatihan -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Tempat / Lokasi</label>
                            <input type="text" class="form-control rounded-10" value="<?= esc($pendaftaran['lokasi_pelatihan'] ?? '-') ?>" readonly>
                        </div>

                        <!-- Kelas Reguler atau Privat (Jenis Kelas / Metode) -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-secondary">Jenis / Kelas</label>
                            <input type="text" class="form-control rounded-10" value="<?= esc($pendaftaran['jenis_kelas'] ?? $pendaftaran['metode_pembelajaran'] ?? '-') ?>" readonly>
                        </div>

                        <!-- Bagian yang bisa diganti: Upload Bukti Pembayaran Baru -->
                        <div class="col-12 mt-4">
                            <div class="upload-box">
                                <label for="bukti_pembayaran" class="form-label fw-bold text-purple mb-2 d-block">
                                    <i class="bi bi-file-earmark-arrow-up me-1"></i> Pilih File Bukti Baru (JPG / PNG / PDF)
                                </label>
                                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control" required>
                                <div class="form-text text-muted small mt-2">Maksimal ukuran file 2 MB. Pastikan foto atau dokumen terbaca dengan jelas.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Button Actions -->
                    <div class="d-flex gap-3 mt-4 pt-2">
                        <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-outline-custom w-50">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-custom w-50">
                            <i class="bi bi-send-check me-1"></i> Kirim Bukti Baru
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>