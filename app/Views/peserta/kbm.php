<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row">

    <div class="col-lg-12">

        <div class="card shadow border-0 rounded-4">

            <div class="card-body p-4">

                <!-- JUDUL -->
                <h2 class="fw-bold text-primary">
                    Kegiatan Belajar Mengajar
                </h2>

                <p class="text-muted">
                    Selamat datang di kelas yang sedang Anda ikuti.
                </p>

                <hr>

                <!-- INFORMASI KELAS -->
                <div class="row">

                    <!-- INFORMASI -->
                    <div class="col-md-6">

                        <h4 class="fw-bold">
                            <?= esc($kelas['nama_kelas']) ?>
                        </h4>

                        <p>
                            <strong>Mentor :</strong><br>
                            <?= esc($kelas['mentor']) ?>
                        </p>

                        <p>
                            <strong>Metode :</strong><br>
                            <?= esc($kelas['metode']) ?>
                        </p>

                        <p>
                            <strong>Jadwal :</strong><br>
                            <?= esc($kelas['jadwal']) ?>
                        </p>

                        <p>
                            <strong>Jam :</strong><br>
                            <?= esc($kelas['jam']) ?>
                        </p>

                    </div>

                    <!-- LOGO -->
                    <div class="col-md-6 text-center">

                        <img
                            src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>"
                            width="180"
                            class="img-fluid rounded"
                        >

                    </div>

                </div>

                <hr>

                <!-- MENU KBM -->
                <div class="row g-3">

                    <!-- ================= MATERI ================= -->
                    <div class="col-md-3 mb-3">

                        <div class="card border-primary h-100">

                            <div class="card-body text-center">

                                <i class="bi bi-book fs-1 text-primary"></i>

                                <h5 class="mt-3">
                                    Materi
                                </h5>

                                <p>
                                    Lihat materi pembelajaran.
                                </p>

                                <a
                                    href="<?= base_url('pelatihan/daftar-materi') ?>"
                                    class="btn btn-primary"
                                >
                                    Buka
                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- ================= TUGAS ================= -->
                    <div class="col-md-3 mb-3">

                        <div class="card border-success h-100">

                            <div class="card-body text-center">

                                <i class="bi bi-journal-check fs-1 text-success"></i>

                                <h5 class="mt-3">
                                    Tugas
                                </h5>

                                <p>
                                    Kerjakan tugas dari mentor.
                                </p>

                                <a
                                    href="<?= base_url('pelatihan/tugas') ?>"
                                    class="btn btn-success"
                                >
                                    Buka
                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- ================= UJIAN ================= -->
                    <div class="col-md-3 mb-3">

                        <div class="card border-danger h-100">

                            <div class="card-body text-center">

                                <i class="bi bi-pencil-square fs-1 text-danger"></i>

                                <h5 class="mt-3">
                                    Ujian
                                </h5>

                                <p>
                                    Kerjakan ujian akhir kelas.
                                </p>

                                <a
                                    href="<?= base_url('pelatihan/ujian') ?>"
                                    class="btn btn-danger"
                                >
                                    Mulai Ujian
                                </a>

                            </div>

                        </div>

                    </div>


                    <!-- ================= PROGRESS ================= -->
                    <div class="col-md-3 mb-3">

                        <div class="card border-warning h-100">

                            <div class="card-body text-center">

                                <i class="bi bi-bar-chart-fill fs-1 text-warning"></i>

                                <h5 class="mt-3">
                                    Progress
                                </h5>

                                <div class="progress mt-4">

                                    <div
                                        class="progress-bar bg-success"
                                        style="width:25%;"
                                    >
                                        25%
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    </div>



                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>