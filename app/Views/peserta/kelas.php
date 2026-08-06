<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-5">

                    <h2 class="fw-bold text-primary">
                        Kelas Saya
                    </h2>

                    <p class="text-muted mb-4">
                        Selamat datang di kelas pelatihan Creativemu Academy.
                    </p>

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <div class="border rounded-4 p-4 h-100">

                                <h4 class="fw-bold">
                                    Digital Marketing
                                </h4>

                                <p class="mb-2">
                                    <strong>Mentor :</strong><br>
                                    Bapak Andi Saputra
                                </p>

                                <p class="mb-2">
                                    <strong>Metode :</strong><br>
                                    Online
                                </p>

                                <p class="mb-2">
                                    <strong>Jadwal :</strong><br>
                                    Senin & Rabu
                                </p>

                                <p class="mb-3">
                                    <strong>Jam :</strong><br>
                                    19.00 WIB
                                </p>

                                <div class="progress mb-3">

                                    <div class="progress-bar bg-success"
                                         style="width:40%">
                                        40%
                                    </div>

                                </div>

                                <a href="<?= base_url('pelatihan/detail-kelas') ?>" class="btn btn-success w-100">
                                Masuk Kelas
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>