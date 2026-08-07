<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow border-0 rounded-4 mb-4">

                <div class="card-body p-4">

                    <h2 class="fw-bold text-primary">
                        Halo, <?= esc($user['nama']) ?> 👋
                    </h2>

                    <p class="text-muted">
                        Selamat datang di Sistem Pelatihan Creativemu Academy.
                    </p>

                </div>

            </div>

        </div>

    </div>

    <div class="row">

        <!-- Profil -->

        <div class="col-md-6 mb-4">

            <div class="card shadow border-0 rounded-4 h-100">

                <div class="card-body">

                    <h4 class="fw-bold mb-4">
                        👤 Profil Saya
                    </h4>

                    <table class="table table-borderless">

                        <tr>

                            <td width="120">Nama</td>

                            <td><?= esc($user['nama']) ?></td>

                        </tr>

                        <tr>

                            <td>Email</td>

                            <td><?= esc($user['email']) ?></td>

                        </tr>

                        <tr>

                            <td>No HP</td>

                            <td><?= esc($user['no_hp']) ?></td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

        <!-- Status -->

        <div class="col-md-6 mb-4">

            <div class="card shadow border-0 rounded-4 h-100">

                <div class="card-body">

                    <h4 class="fw-bold mb-4">
                        📋 Status Pendaftaran
                    </h4>

                    <?php if ($pendaftaran == null): ?>

                        <span class="badge bg-warning fs-6">

                            Belum Mendaftar

                        </span>

                        <p class="mt-3">

                            Silakan memilih kelas terlebih dahulu.

                        </p>

                        <a href="<?= base_url('pelatihan/daftar-kelas') ?>"
                           class="btn btn-primary">

                            Daftar Kelas

                        </a>

                    <?php else: ?>

                        <?php if($pendaftaran['status']=="Menunggu"): ?>

                            <span class="badge bg-warning fs-6">

                                Menunggu Validasi Admin

                            </span>

                        <?php elseif($pendaftaran['status']=="Disetujui"): ?>

                            <span class="badge bg-success fs-6">

                                Sudah Divalidasi

                            </span>

                        <?php else: ?>

                            <span class="badge bg-danger fs-6">

                                Ditolak

                            </span>

                        <?php endif; ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row mt-2">

    <div class="col-md-3 mb-3">

        <div class="card shadow border-0 rounded-4 text-center">

            <div class="card-body">

                <i class="bi bi-book fs-1 text-primary"></i>

                <h3><?= $pendaftaran ? 1 : 0 ?></h3>

                <p>Kelas Saya</p>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card shadow border-0 rounded-4 text-center">

            <div class="card-body">

                <i class="bi bi-calendar-check fs-1 text-success"></i>

                <h3>0</h3>

                <p>Absensi</p>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card shadow border-0 rounded-4 text-center">

            <div class="card-body">

                <i class="bi bi-file-earmark-text fs-1 text-warning"></i>

                <h3>0</h3>

                <p>Ujian</p>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card shadow border-0 rounded-4 text-center">

            <div class="card-body">

                <i class="bi bi-award fs-1 text-danger"></i>

                <h3>0</h3>

                <p>Sertifikat</p>

            </div>

        </div>

    </div>

</div>

<div class="card shadow border-0 rounded-4 mt-4">

    <div class="card-body">

        <h4 class="fw-bold mb-4">

            🚀 Menu Cepat

        </h4>

        <div class="row">

            <div class="col-md-4 mb-3">

                <a href="<?= base_url('pelatihan/daftar-kelas') ?>"
                   class="btn btn-outline-primary w-100 p-4">

                    <i class="bi bi-grid fs-2"></i>

                    <br>

                    Daftar Kelas

                </a>

            </div>

            <div class="col-md-4 mb-3">

                <a href="#"
                   class="btn btn-outline-success w-100 p-4">

                    <i class="bi bi-book fs-2"></i>

                    <br>

                    Kelas Saya

                </a>

            </div>

            <div class="col-md-4 mb-3">

                <a href="#"
                   class="btn btn-outline-warning w-100 p-4">

                    <i class="bi bi-gear fs-2"></i>

                    <br>

                    Pengaturan

                </a>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>