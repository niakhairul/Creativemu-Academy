<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>


<div class="container py-5">

    <!-- Hero Dashboard -->
    <div class="card border-0 shadow-lg rounded-4 mb-4">

        <div class="card-body p-5">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h2 class="fw-bold text-primary">
                        Halo, <?= esc(session('nama')) ?> 👋
                    </h2>

                    <p class="text-muted mb-4">
                        Selamat datang kembali di Creativemu Academy.
                    </p>

                    <h6 class="fw-bold">
                        Progress Belajar
                    </h6>

                    <div class="progress mb-3" style="height:22px;">

                        <div class="progress-bar bg-success"
                             style="width:40%;">

                            40%

                        </div>

                    </div>

                    <a href="<?= base_url('pelatihan/kelas') ?>"
                       class="btn btn-primary">

                        📚 Lanjut Belajar

                    </a>

                </div>

                <div class="col-md-4 text-center">

                    <i class="bi bi-mortarboard-fill text-primary"
                       style="font-size:120px;"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- Statistik -->
    <div class="row g-4 mb-5">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm text-center h-100">

                <div class="card-body">

                    <i class="bi bi-book-fill text-primary fs-1"></i>

                    <h3 class="mt-3">1</h3>

                    <p class="text-muted mb-0">
                        Kelas Aktif
                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm text-center h-100">

                <div class="card-body">

                    <i class="bi bi-journal-bookmark-fill text-success fs-1"></i>

                    <h3 class="mt-3">3</h3>

                    <p class="text-muted mb-0">
                        Materi
                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm text-center h-100">

                <div class="card-body">

                    <i class="bi bi-file-earmark-text-fill text-warning fs-1"></i>

                    <h3 class="mt-3">1</h3>

                    <p class="text-muted mb-0">
                        Tugas
                    </p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm text-center h-100">

                <div class="card-body">

                    <i class="bi bi-award-fill text-danger fs-1"></i>

                    <h3 class="mt-3">0</h3>

                    <p class="text-muted mb-0">
                        Sertifikat
                    </p>

                </div>

            </div>

        </div>

    </div>

    <div class="row mt-5">

    <!-- Card Kelas Aktif -->
    <div class="col-lg-7 mb-4">

        <div class="card border-0 shadow rounded-4 h-100">

            <div class="card-body p-4">

                <h4 class="fw-bold text-primary mb-4">
                    📚 Kelas Aktif
                </h4>

                <h3 class="fw-bold">
                    Digital Marketing
                </h3>

                <p class="mb-2">
                    <strong>Mentor :</strong> Bapak Andi Saputra
                </p>

                <p class="mb-2">
                    <strong>Metode :</strong> Online
                </p>

                <p class="mb-4">
                    <strong>Progress :</strong> 40%
                </p>

                <a href="<?= base_url('pelatihan/detail-kelas') ?>"
                   class="btn btn-primary rounded-pill px-4">

                    <i class="bi bi-play-circle-fill"></i>

                    Masuk Kelas

                </a>

            </div>

        </div>

    </div>

    <!-- Card Jadwal -->
    <div class="col-lg-5 mb-4">

        <div class="card border-0 shadow rounded-4 h-100">

            <div class="card-body p-4">

                <h4 class="fw-bold text-warning mb-4">
                    📅 Jadwal Hari Ini
                </h4>

                <h5 class="fw-bold">
                    Senin
                </h5>

                <p>
                    19.00 WIB
                </p>

                <hr>

                <h6 class="fw-bold">
                    Pertemuan 2
                </h6>

                <p class="text-muted">
                    SEO Dasar
                </p>

                <span class="badge bg-success">
                    Online
                </span>

            </div>

        </div>

    </div>

</div>
<?= $this->endSection() ?>