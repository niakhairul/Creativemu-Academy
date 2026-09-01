<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Pelatihan - Creativemu Academy</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #7c3aed; /* Ungu Utama (Violet) */
            --primary-hover: #6d28d9; /* Ungu Gelap saat Hover */
        }
        body {
            /* Background gradasi nuansa ungu modern dipadu dengan pattern titik-titik lembut */
            background-color: #faf5ff;
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(99, 102, 241, 0.08) 0px, transparent 50%),
                url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='1.5' cy='1.5' r='1.5' fill='%23e9d5ff' fill-opacity='0.8'/%3E%3C/svg%3E");
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
            min-height: 100vh;
        }
        .main-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(124, 58, 237, 0.05), 0 10px 10px -5px rgba(124, 58, 237, 0.02);
            background: #ffffff;
            backdrop-filter: blur(10px);
        }
        .hero-banner {
            background: linear-gradient(135deg, #7c3aed 0%, #9333ea 100%); /* Gradasi Ungu */
            color: white;
            border-radius: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(124, 58, 237, 0.35);
        }
        .hero-banner::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.15)"/></svg>');
            opacity: 0.8;
        }
        .btn-check:checked + .option-card {
            border-color: var(--primary-color) !important;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.04) 0%, rgba(124, 58, 237, 0.08) 100%) !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }
        .option-card {
            transition: all 0.25s ease-in-out;
            border: 2px solid #e2e8f0;
            background-color: #ffffff;
            border-radius: 1rem;
        }
        .option-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            background-color: #faf5ff;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
        }
        .section-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(124, 58, 237, 0.1);
            color: var(--primary-color);
        }
        .btn-submit {
            background: var(--primary-color);
            border: none;
            padding: 0.9rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 0.75rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(124, 58, 237, 0.4);
        }

        .ketentuan-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px;
    text-align: center;
    overflow: hidden;
}

.ketentuan-card img {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 8px;
}
    </style>
</head>
<body>

<div class="container py-5">
    <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Gagal!</strong>
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
    <div class="row justify-content-center">
        <div class="col-xl-10">
            
            <!-- Banner Header Profesional -->
            <div class="hero-banner p-4 p-md-5 mb-4 text-center">
                <div class="position-relative z-1">
                    <div class="bg-white d-inline-block p-2 rounded-circle shadow-sm mb-3">
                        <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>" width="75" height="75" class="rounded-circle object-fit-cover" alt="Logo">
                    </div>
                    <h2 class="fw-bold mb-2">Formulir Pendaftaran Pelatihan</h2>
                    <p class="text-white-50 mb-0 mx-auto" style="max-width: 550px;">Lengkapi data diri Anda di bawah ini untuk mengamankan kursi pelatihan eksklusif bersama Creativemu Academy.</p>
                </div>
            </div>

            <?php 
                $kelas = isset($kelas) ? $kelas : [];
                $user  = isset($user) ? $user : [];
            ?>

            <!-- FORM UTAMA -->
            <form action="<?= base_url('pelatihan/simpan-pendaftaran') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <!-- Input Hidden ID Kelas -->
    <input type="hidden" name="id_kelas" value="<?= $kelas['id_kelas'] ?? $kelas['id'] ?? ''; ?>">
    
    <!-- Hidden Fields untuk data otomatis dari sistem/kelas -->
    <input type="hidden" name="pilihan_pelatihan" value="<?= esc($kelas['nama_kelas'] ?? $kelas['nama'] ?? 'Pelatihan Umum') ?>">
    <input type="hidden" name="pilihan_kelas" value="<?= esc($kelas['nama_kelas'] ?? $kelas['nama'] ?? 'Kelas Umum') ?>">
    <input type="hidden" name="tanggal_mulai_kelas" value="<?= esc($kelas['tanggal_mulai_kelas'] ?? $kelas['awal_pelatihan'] ?? date('Y-m-d')) ?>">

    <div class="row g-4">
                
                <!-- KOLOM KIRI: DETAIL KELAS -->
                <div class="col-lg-5">
                    <div class="main-card p-4 sticky-top" style="top: 20px;">
                        <?php 
                            $fotoKelas = !empty($kelas['thumbnail']) ? $kelas['thumbnail'] : (!empty($kelas['foto']) ? $kelas['foto'] : 'default.jpg');
                            $tipeKelas = !empty($kelas['tipe_kelas']) ? $kelas['tipe_kelas'] : 'Online'; 
                        ?>
                        <div class="position-relative rounded-4 overflow-hidden mb-3 shadow-sm" style="height: 180px;">
                            <img src="<?= base_url('uploads/kelas/' . $fotoKelas); ?>" alt="Banner Kelas" class="w-100 h-100 object-fit-cover">
                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 bg-dark bg-opacity-75 rounded-pill backdrop-blur" style="font-size: 0.75rem;">
                                <i class="fas fa-tag me-1 text-warning"></i> <?= esc($kelas['kategori'] ?? 'Umum'); ?>
                            </span>
                        </div>

                        <!-- Nama Kelas & Tipe -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h4 class="fw-bold text-dark mb-0 fs-5"><?= esc($kelas['nama_kelas'] ?? $kelas['nama'] ?? '-') ?></h4>
                            <span class="badge bg-purple text-purple bg-opacity-10 px-2 py-1 fw-bold rounded" style="color: #7c3aed; background-color: rgba(124, 58, 237, 0.1) !important; font-size: 0.7rem;">
                                <?= esc(ucfirst($tipeKelas)) ?>
                            </span>
                        </div>

                        <hr class="text-muted opacity-25 my-3">

                        <!-- Mentor -->
                        <div class="d-flex align-items-center mb-3">
                            <div class="text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm flex-shrink-0" style="width: 40px; height: 40px; background-color: #7c3aed;">
                                <?php 
                                    $mentorName = !empty($kelas['nama_mentor']) ? $kelas['nama_mentor'] : (!empty($kelas['mentor']) ? $kelas['mentor'] : (!empty($kelas['pengajar']) ? $kelas['pengajar'] : 'Mentor'));
                                    echo strtoupper(substr($mentorName, 0, 1));
                                ?>
                            </div>
                            <div class="overflow-hidden">
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Mentor Pengampu</span>
                                <span class="fw-bold text-dark text-truncate d-block small"><?= esc($mentorName) ?></span>
                            </div>
                        </div>

                        <!-- Jadwal & Pertemuan -->
                        <div class="bg-light p-3 rounded-4 mb-3 border border-light-subtle">
                            <div class="row g-2">
                                <div class="col-6 border-end">
                                    <span class="text-muted d-block" style="font-size: 0.65rem;"><i class="far fa-calendar-alt me-1" style="color: #7c3aed;"></i> Mulai Kelas</span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.8rem;">
                                        <?= esc($kelas['tanggal_mulai_kelas'] ?? $kelas['awal_pelatihan'] ?? '-') ?>
                                    </span>
                                </div>
                                <div class="col-6 ps-2">
                                    <span class="text-muted d-block" style="font-size: 0.65rem;"><i class="fas fa-layer-group text-success me-1"></i> Pertemuan</span>
                                    <span class="fw-semibold text-dark" style="font-size: 0.8rem;">
                                        <?= esc($kelas['jumlah_pertemuan'] ?? '-') ?> Sesi
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Rincian Harga Box -->
                        <div class="border-top pt-3">
                            <span class="text-muted d-block mb-2" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Investasi Kelas</span>
                            <div class="d-flex justify-content-between align-items-center mb-1 bg-success bg-opacity-10 p-2 rounded-3">
                                <span class="small text-success fw-semibold">Reguler</span>
                                <span class="fw-bold text-success">Rp <?= number_format($kelas['harga_reguler'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background-color: rgba(124, 58, 237, 0.1);">
                                <span class="small fw-semibold" style="color: #7c3aed;">Privat</span>
                                <span class="fw-bold" style="color: #7c3aed;">Rp <?= number_format($kelas['harga_privat'] ?? 0, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: FORM DATA & PEMBAYARAN -->
                <div class="col-lg-7">
                    <div class="main-card p-4 p-md-5">

  <!-- Ketentuan Pendaftaran -->
<div class="mb-4">
    <div class="d-flex align-items-center mb-3">
        <div class="section-icon me-3">
            <i class="fas fa-file-alt fa-lg"></i>
        </div>
        <div>
            <h5 class="fw-bold text-dark mb-0">Ketentuan Pendaftaran</h5>
            <p class="text-muted small mb-0">
                Silakan baca ketentuan sebelum melanjutkan pendaftaran.
            </p>
        </div>
    </div>

    <div class="ketentuan-card">
        <img src="<?= base_url('uploads/syarat_dan_persetujuan/syarat_dan_persetujuan.jpeg') ?>"
             alt="Syarat dan Ketentuan Pendaftaran"
             class="img-fluid">
    </div>
</div>
<!-- Persetujuan Syarat & Ketentuan -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="1" id="persetujuan_syarat" name="persetujuan_syarat" checked required>
                            <label class="form-check-label small text-muted" for="persetujuan_syarat">
                                Saya menyetujui seluruh <a href="<?= base_url('uploads/syarat_dan_persetujuan/syarat_dan_persetujuan.jpeg') ?>" target="_blank" class="text-decoration-underline" style="color: #7c3aed;">syarat dan ketentuan</a> serta <a href="<?= base_url('kebijakan-privasi') ?>" target="_blank" class="text-decoration-underline" style="color: #7c3aed;">kebijakan privasi</a> yang berlaku di Creativemu Academy.
                            </label>
                        </div>
                        
                        <!-- Bagian 1: Data Diri -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="section-icon me-3">
                                <i class="fas fa-user-graduate fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Informasi Data Diri</h5>
                                <p class="text-muted small mb-0">Pastikan data kontak Anda aktif.</p>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="nama" name="nama" value="<?= esc($user['nama'] ?? '') ?>" required placeholder="Nama Lengkap">
                            <label for="nama">Nama Lengkap</label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control rounded-3" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>" required placeholder="Email">
                                    <label for="email">Alamat Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="no_hp" name="no_hp" value="<?= esc($user['no_hp'] ?? '') ?>" required placeholder="Nomor HP">
                                    <label for="no_hp">Nomor HP / WhatsApp</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control rounded-3" id="alamat" name="alamat" style="height: 100px" required placeholder="Alamat Lengkap"><?= esc($user['alamat'] ?? '') ?></textarea>
                            <label for="alamat">Alamat Lengkap</label>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-3" id="ttl" name="ttl" value="<?= esc($user['ttl'] ?? '') ?>" required placeholder="Tempat, Tanggal Lahir">
                                    <label for="ttl">Tempat, Tanggal Lahir</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select rounded-3" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" <?= (isset($user['jenis_kelamin']) && $user['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Perempuan" <?= (isset($user['jenis_kelamin']) && $user['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                </div>
                            </div>
                        </div>

                        <!-- PILIHAN: Pendidikan Terakhir & Status -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select rounded-3" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
    <option value="" disabled selected>Pilih Pendidikan Terakhir</option>
    <option value="Smp/Sederajat" <?= (isset($user['pendidikan_terakhir']) && $user['pendidikan_terakhir'] == 'Smp/Sederajat') ? 'selected' : '' ?>>SMP/Sederajat</option>
    <option value="Sma/Smk/Sederajat" <?= (isset($user['pendidikan_terakhir']) && $user['pendidikan_terakhir'] == 'Sma/Smk/Sederajat') ? 'selected' : '' ?>>SMA / SMK / Sederajat</option>
    <option value="D3/D4" <?= (isset($user['pendidikan_terakhir']) && $user['pendidikan_terakhir'] == 'D3/D4') ? 'selected' : '' ?>>D3 / D4</option>
    <option value="S1" <?= (isset($user['pendidikan_terakhir']) && $user['pendidikan_terakhir'] == 'S1') ? 'selected' : '' ?>>S1</option>
    <option value="S2" <?= (isset($user['pendidikan_terakhir']) && $user['pendidikan_terakhir'] == 'S2') ? 'selected' : '' ?>>S2</option>
</select>
                                    <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select rounded-3" id="pilihan_status" name="pilihan_status" required>
                                        <option value="" disabled selected>Pilih Status</option>
                                        <option value="Pelajar" <?= (isset($user['pilihan_status']) && $user['pilihan_status'] == 'Pelajar') ? 'selected' : '' ?>>Pelajar SMP</option>
                                        <option value="Pelajar" <?= (isset($user['pilihan_status']) && $user['pilihan_status'] == 'Pelajar SMA/SMK') ? 'selected' : '' ?>>Pelajar SMA/SMK</option>
                                        <option value="Mahasiswa" <?= (isset($user['pilihan_status']) && $user['pilihan_status'] == 'Mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                                        <option value="Umum" <?= (isset($user['pilihan_status']) && $user['pilihan_status'] == 'Guru/Dosen') ? 'selected' : '' ?>>Guru/Dosen</option>
                                        <option value="Karyawan" <?= (isset($user['pilihan_status']) && $user['pilihan_status'] == 'Karyawan Swasta') ? 'selected' : '' ?>>Karyawan Swasta</option>
                                        <option value="Freelance" <?= (isset($user['pilihan_status']) && $user['pilihan_status'] == 'ASN/TNI/POLRI') ? 'selected' : '' ?>>ASN/TNI/POLRI</option>
                                        <option value="Pelajar" <?= (isset($user['pilihan_status']) && $user['pilihan_status'] == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                    <label for="pilihan_status">Status Peserta</label>
                                </div>
                            </div>
                        </div>

                        <!-- PILIHAN: Lokasi Pelatihan & Kategori Kelas -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select rounded-3" id="pilihan_lokasi" name="pilihan_lokasi" required>
                                        <option value="" disabled selected>Pilih Lokasi Pelatihan</option>
                                        <option value="Online / Daring" <?= (isset($kelas['lokasi']) && $kelas['lokasi'] == 'Kantor pusat - Jl. Gn. Bulu No 89, RT.34, Bandut Lor,Argorejo,Sedayu, Bantul, Yogyakarta') ? 'selected' : '' ?>>Kantor pusat - Jl. Gn. Bulu No 89, RT.34, Bandut Lor,Argorejo,Sedayu, Bantul, Yogyakarta</option>
                                        <option value="Kampus Utama Creativemu" <?= (isset($kelas['lokasi']) && $kelas['lokasi'] == 'Kantor Cabang - Jl. Glagahsari No.46C, Warungboto, Kec.umbulharjo, Kota Yogyakarta, Daerah Istimewa Yogyakarta') ? 'selected' : '' ?>>Kantor Cabang - Jl. Glagahsari No.46C, Warungboto, Kec.umbulharjo, Kota Yogyakarta, Daerah Istimewa Yogyakarta</option>
                                        <option value="Cabang Regional" <?= (isset($kelas['lokasi']) && $kelas['lokasi'] == 'Kantor Perwakilan - Jl . Soekarno Hatta, Sawitan, Kabupaten Magelang, Jawa Tengah') ? 'selected' : '' ?>>Kantor Perwakilan - Jl . Soekarno Hatta, Sawitan, Kabupaten Magelang, Jawa Tengah</option>
                                    </select>
                                    <label for="pilihan_lokasi">Lokasi Pelatihan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                               
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="pas_foto" class="form-label small fw-semibold text-secondary">Pas Foto (3x4)</label>
                            <input type="file" class="form-control rounded-3 py-2" id="pas_foto" name="pas_foto" accept="image/png, image/jpeg, image/jpg">
                            <div class="form-text" style="font-size: 0.75rem;">Maksimal 2 MB (JPG/PNG).</div>
                        </div>

                        <hr class="text-muted opacity-25 my-4">

                        <!-- Bagian 2: Pilih Kelas -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="section-icon me-3">
                                <i class="fas fa-layer-group fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Pilih Kategori Kelas</h5>
                                <p class="text-muted small mb-0">Pilih skema belajar yang Anda inginkan.</p>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="jenis_kelas" id="kelas_reguler" value="Reguler" required>
                                <label class="btn option-card w-100 p-3 text-start d-flex align-items-center gap-3" for="kelas_reguler">
                                    <div class="bg-success bg-opacity-10 text-success p-2 rounded-3">
                                        <i class="fas fa-users fa-lg"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Reguler</span>
                                        <small class="text-success fw-bold">Rp <?= number_format($kelas['harga_reguler'] ?? 0, 0, ',', '.') ?></small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="jenis_kelas" id="kelas_privat" value="Privat">
                                <label class="btn option-card w-100 p-3 text-start d-flex align-items-center gap-3" for="kelas_privat">
                                    <div class="p-2 rounded-3" style="background-color: rgba(124, 58, 237, 0.1); color: #7c3aed;">
                                        <i class="fas fa-user-shield fa-lg"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Privat</span>
                                        <small class="fw-bold" style="color: #7c3aed;">Rp <?= number_format($kelas['harga_privat'] ?? 0, 0, ',', '.') ?></small>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Metode Pembelajaran (Huruf kecil agar cocok dengan ENUM database) -->
<div class="mb-4">
    <label for="metode_pembelajaran" class="form-label fw-semibold small text-secondary">Metode Pembelajaran</label>
    <select class="form-select rounded-3" id="metode_pembelajaran" name="metode_pembelajaran" required>
        <option value="" disabled selected>Pilih Metode Pembelajaran</option>
        <option value="online">Online</option>
        <option value="offline">Offline</option>
    </select>
</div>


                        <div class="mb-4">
                            <label for="kategori_kelas" class="form-label fw-semibold small text-secondary">kategori kelas</label>
                            <select class="form-select rounded-3" id="metode_pembelajaran" name="metode_pembelajaran" required>
                                <option value="Basic Pelatihan" <?= (isset($kelas['metode']) && $kelas['metode'] == 'Basic Pelatihan') ? 'selected' : '' ?>>Basic Pelatihan</option>
                                <option value="Pelatihan Sertifikasi" <?= (isset($kelas['metode']) && $kelas['metode'] == 'Pelatihan Sertifikasi') ? 'selected' : '' ?>>Pelatihan Sertifikasi</option>
                            </select>
                        </div>

                        <hr class="text-muted opacity-25 my-4">

                        <!-- Bagian 3: Pembayaran -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="section-icon me-3">
                                <i class="fas fa-wallet fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Metode Pembayaran</h5>
                                <p class="text-muted small mb-0">Pilih opsi transaksi yang tersedia.</p>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="metode_pembayaran" id="bayar_cod" value="COD" onchange="togglePembayaran()" required>
                                <label class="btn option-card w-100 p-3 text-start d-flex align-items-center gap-3" for="bayar_cod">
                                    <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3">
                                        <i class="fas fa-handshake fa-lg"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">COD</span>
                                        <small class="text-muted">Bayar di Tempat</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="metode_pembayaran" id="bayar_transfer" value="Transfer" onchange="togglePembayaran()">
                                <label class="btn option-card w-100 p-3 text-start d-flex align-items-center gap-3" for="bayar_transfer">
                                    <div class="bg-info bg-opacity-10 text-info p-2 rounded-3">
                                        <i class="fas fa-university fa-lg"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">Transfer</span>
                                        <small class="text-muted">Transfer Bank</small>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Informasi Rekening Transfer -->
                        <div id="rekening" class="alert border-0 shadow-sm rounded-4 p-3 mb-3" style="display:none; background-color: #f3e8ff; color: #581c87;">
                            <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-1" style="color: #7c3aed;"></i> Instruksi Transfer Bank:</h6>
                            <p class="mb-1 small">Bank BCA: <strong>1234567890</strong></p>
                            <p class="mb-0 small">Atas Nama: <strong>Creativemu Academy</strong></p>
                        </div>

                        <!-- Informasi COD -->
                        <div id="infoCod" class="alert alert-warning border-0 shadow-sm rounded-4 p-3 mb-3" style="display:none; background-color: #fef3c7;">
                            <h6 class="fw-bold mb-2 text-dark"><i class="fas fa-clock me-1 text-warning"></i> Ketentuan Pembayaran COD</h6>
                            <p class="mb-0 text-dark small">Anda memilih metode COD. Harap melakukan pelunasan dan <strong>konfirmasi pembayaran maksimal 1x24 jam</strong> setelah formulir ini dikirimkan.</p>
                        </div>

                        <!-- Upload Bukti -->
                        <div id="uploadBukti" class="mb-4" style="display:none;">
                            <label class="form-label fw-semibold small text-secondary" id="labelUpload">Upload Bukti Transaksi</label>
                            <input type="file" name="bukti_pembayaran" class="form-control rounded-3 py-2">
                            <div class="form-text text-muted" style="font-size: 0.75rem;">Format yang didukung: JPG, PNG, JPEG (Maks. 2MB).</div>
                        </div>


                        <!-- Tombol Submit -->
                        <button type="submit" class="btn btn-submit text-white w-100 mt-2">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Pendaftaran Sekarang
                        </button>

                    </div>
                </div>

            </div>
            </form>

        </div>
    </div>
</div>

<!-- Script Toggle Pembayaran -->
<script>
function togglePembayaran() {
    const selected = document.querySelector('input[name="metode_pembayaran"]:checked');
    const rekening = document.getElementById('rekening');
    const infoCod = document.getElementById('infoCod');
    const upload = document.getElementById('uploadBukti');
    const labelUpload = document.getElementById('labelUpload');

    if (!selected) return;

    if (selected.value === "Transfer") {
        rekening.style.display = "block";
        infoCod.style.display = "none";
        upload.style.display = "block";
        labelUpload.textContent = "Upload Bukti Transfer Pembayaran";
    } else if (selected.value === "COD") {
        rekening.style.display = "none";
        infoCod.style.display = "block";
        upload.style.display = "block";
        labelUpload.textContent = "Upload Bukti / Konfirmasi Pendaftaran COD (Maks. 1x24 Jam)";
    } else {
        rekening.style.display = "none";
        infoCod.style.display = "none";
        upload.style.display = "none";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    togglePembayaran();
});
</script>