<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-5 text-center">

                    <div class="mb-4">

                        <i class="bi bi-clock-history text-warning"
                           style="font-size:80px;"></i>

                    </div>

                    <h2 class="fw-bold text-warning">

                        Menunggu Validasi Admin

                    </h2>

                    <p class="text-muted mt-3">

                        Pendaftaran pelatihan berhasil dikirim.

                        <br>

                        Silakan menunggu admin melakukan validasi data dan pembayaran.

                    </p>

                    <hr>

                    <div class="row text-start mt-4">

                        <div class="col-md-6 mb-3">

                            <strong>Status Pendaftaran</strong>

                            <br>

                            <span class="badge bg-warning text-dark">

                                Menunggu Validasi

                            </span>

                        </div>

                        <div class="col-md-6 mb-3">

                            <strong>Status Pembayaran</strong>

                            <br>

                            <span class="badge bg-secondary">

                                Belum Diverifikasi

                            </span>

                        </div>

                    </div>

                    <div class="d-flex justify-content-between mt-4">

    <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Beranda
    </a>

    <a href="<?= base_url('pelatihan/kelas') ?>" class="btn btn-primary">
        Kelas Saya <i class="bi bi-arrow-right"></i>
    </a>

</div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>