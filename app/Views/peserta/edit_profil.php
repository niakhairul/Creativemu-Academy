<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Creativemu Academy</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #fdf4ff 100%);
            background-attachment: fixed;
            color: #1e293b;
            min-height: 100vh;
            padding: 40px 0;
        }

        .card {
            border: none;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(109, 40, 217, 0.06);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #7c3aed, #4c1d95);
            color: white;
            border: none;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #6d28d9, #3b0764);
            color: white;
        }
    </style>
</head>
<body>

<div class="container" style="max-width: 800px;">
    
    <!-- HEADER & TOMBOL KEMBALI -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: #4c1d95;">Edit Profil</h3>
            <p class="text-muted small mb-0">Perbarui informasi pribadi dan foto profil Anda[cite: 10].</p>
        </div>
        <div>
            <a href="<?= base_url('pelatihan/pengaturan') ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm fw-semibold" style="font-size: 0.85rem;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Pengaturan
            </a>
        </div>
    </div>

    <!-- KARTU UTAMA -->
    <div class="card shadow-sm">
        <div class="card-body p-4 p-md-5">

            <form action="<?= base_url('pelatihan/update-profil') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- FOTO PROFIL -->
                <div class="text-center mb-4">
                    <?php
                    if (!empty($pendaftaran['pas_foto'])) {
                        $fotoUrl = base_url('uploads/foto/' . $pendaftaran['pas_foto']);
                    } elseif (!empty($user['foto_profil'])) {
                        $fotoUrl = base_url('uploads/profil/' . $user['foto_profil']);
                    } else {
                        $fotoUrl = base_url('assets/img/logo creativemu academy.jpg');
                    }
                    ?>

                    <img src="<?= $fotoUrl ?>" class="rounded-circle shadow-sm border" width="100" height="100" style="object-fit: cover;">

                    <div class="mt-3">
                        <label class="form-label fw-semibold small text-muted">Foto Profil</label>
                        <input type="file" name="foto" class="form-control form-control-sm mx-auto" style="max-width: 320px;" accept=".jpg,.jpeg,.png">
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">JPG, JPEG, atau PNG. Maksimal 2 MB[cite: 10].</small>
                    </div>
                </div>

                <hr class="my-4 border-purple border-opacity-10">

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap" required>
                </div>

                <!-- EMAIL & NO HP -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= esc($pendaftaran['email'] ?? $user['email'] ?? '') ?>" placeholder="Masukkan email" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= esc($pendaftaran['no_hp'] ?? $user['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <!-- JENIS KELAMIN & TTL -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" <?= ($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= ($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Tempat, Tanggal Lahir</label>
                        <input type="text" name="ttl" class="form-control" value="<?= esc($pendaftaran['ttl'] ?? '') ?>" placeholder="Contoh: Bali, 12-10-2004">
                    </div>
                </div>

                <!-- PENDIDIKAN & LOKASI -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir" class="form-select">
                            <option value="">Pilih Pendidikan</option>
                            <option value="Smp/Sederajat" <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'Smp/Sederajat' ? 'selected' : '' ?>>SMP/Sederajat</option>
                            <option value="Sma/Smk/Sederajat" <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'Sma/Smk/Sederajat' ? 'selected' : '' ?>>SMA/SMK/Sederajat</option>
                            <option value="D3/D4" <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'D3/D4' ? 'selected' : '' ?>>D3/D4</option>
                            <option value="S1" <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'S1' ? 'selected' : '' ?>>S1</option>
                            <option value="S2" <?= ($pendaftaran['pendidikan_terakhir'] ?? '') == 'S2' ? 'selected' : '' ?>>S2</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold small">Lokasi Pelatihan</label>
                        <input type="text" class="form-control bg-light" value="<?= esc($pendaftaran['lokasi_pelatihan'] ?? '') ?>" readonly>
                        <small class="text-muted" style="font-size: 0.7rem;">Lokasi pelatihan ditentukan oleh penyelenggara.</small>
                    </div>
                </div>

                <!-- ALAMAT -->
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"><?= esc($pendaftaran['alamat'] ?? '') ?></textarea>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="d-flex gap-2 pt-2">
                    <button type="submit" class="btn btn-gradient rounded-pill px-4 shadow-sm fw-semibold">
                        💾 Simpan Perubahan
                    </button>
                    <a href="<?= base_url('pelatihan/pengaturan') ?>" class="btn btn-light border rounded-pill px-4 fw-semibold text-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>