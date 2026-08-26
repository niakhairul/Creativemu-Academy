<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<!-- Tambahan Styling CSS Khusus untuk Animasi Halus & Estetik -->
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important;
    }
    .welcome-banner {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
    }
</style>

<div class="container-fluid py-2">

    <!-- Header Sambutan dengan Gradasi dan Animasi Halus -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 rounded-4 shadow-sm welcome-banner">
                <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div class="mb-3 mb-md-0">
                        <span class="badge bg-white text-primary px-3 py-1 rounded-pill fw-semibold mb-2 shadow-sm">Dashboard Peserta</span>
                        <h2 class="fw-bold mb-1">
                            Halo, <?= esc($user['nama']) ?> 👋
                        </h2>
                        <p class="text-white-50 mb-0">
                            Selamat datang kembali di Creativemu Academy. Terus semangat mengejar target belajarmu hari ini!
                        </p>
                    </div>
                    <div>
                        <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm transition">
                            <i class="bi bi-search me-2"></i> Cari Kelas Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kotak Statistik Ringkas dengan Efek Hover -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 rounded-4 text-center h-100 hover-card">
                <div class="card-body py-4">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary mb-3 mx-auto">
                        <i class="bi bi-book fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-1"><?= $pendaftaran ? 1 : 0 ?></h3>
                    <p class="text-muted mb-0 small fw-semibold">Kelas Saya</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 rounded-4 text-center h-100 hover-card">
                <div class="card-body py-4">
                    <div class="stat-icon bg-success bg-opacity-10 text-success mb-3 mx-auto">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-1">0</h3>
                    <p class="text-muted mb-0 small fw-semibold">Absensi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card shadow-sm border-0 rounded-4 text-center h-100 hover-card">
                <div class="card-body py-4">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning mb-3 mx-auto">
                        <i class="bi bi-file-earmark-text fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-1">0</h3>
                    <p class="text-muted mb-0 small fw-semibold">Ujian</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 text-center h-100 hover-card">
                <div class="card-body py-4">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger mb-3 mx-auto">
                        <i class="bi bi-award fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-1">0</h3>
                    <p class="text-muted mb-0 small fw-semibold">Sertifikat</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Utama: Profil & Status Pendaftaran -->
    <div class="row">

        <!-- Profil Saya -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 me-3">
                            <i class="bi bi-person-circle fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">Profil Saya</h4>
                    </div>

                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <td width="120" class="text-muted fw-semibold">Nama</td>
                            <td class="fw-bold text-dark">: <?= esc($user['nama']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">Email</td>
                            <td class="fw-bold text-dark">: <?= esc($user['email']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-semibold">No HP</td>
                            <td class="fw-bold text-dark">: <?= esc($user['no_hp'] ?? '-') ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Status Pendaftaran (Dalam Bentuk Card Eksklusif) -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 rounded-4 h-100 hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-3 me-3">
                            <i class="bi bi-clipboard-check fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">Status Pendaftaran</h4>
                    </div>

                    <?php if ($pendaftaran == null): ?>
                        <div class="text-center py-3">
                            <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill mb-3 shadow-sm">
                                Belum Mendaftar
                            </span>
                            <p class="text-muted small mb-3">
                                Anda belum terdaftar di kelas pelatihan apapun. Mari mulai langkah belajarmu sekarang.
                            </p>
                            <a href="<?= base_url('pelatihan/daftar-kelas') ?>" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                Pilih Kelas Sekarang
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <?php 
                                // Ambil nilai dari database untuk pengecekan status
                                $statusDaftar = $pendaftaran['status_pendaftaran'] ?? '';
                                $statusBayar  = $pendaftaran['status_pembayaran'] ?? '';
                            ?>

                            <?php if ($statusBayar == 'terkonfirmasi' || strtolower($statusDaftar) == 'disetujui'): ?>
                                <span class="badge bg-success fs-6 px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Sudah Divalidasi / Disetujui
                                </span>
                            <?php elseif ($statusBayar == 'batal' || strtolower($statusDaftar) == 'ditolak'): ?>
                                <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-clock-history me-1"></i> Menunggu Validasi Admin
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Kotak Informasi Kelas yang Dipilih -->
                        <div class="p-3 border rounded-4 bg-light shadow-sm">
                            <h6 class="fw-bold text-primary mb-2"><?= esc($pendaftaran['nama_kelas'] ?? 'Kelas Pelatihan') ?></h6>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-person-badge me-1"></i> Mentor: <strong class="text-dark"><?= esc($pendaftaran['mentor'] ?? '-') ?></strong>
                            </p>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar-event me-1"></i> Jadwal: <strong class="text-dark"><?= esc($k['jadwal'] ?? $pendaftaran['jadwal'] ?? '-') ?></strong>
                            </p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>