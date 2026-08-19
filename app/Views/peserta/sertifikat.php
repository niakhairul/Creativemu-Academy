<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">

    <div class="col-lg-10">

        <div class="card shadow border-0 rounded-4">

            <div class="card-body p-5">

                <!-- JUDUL -->
                <div class="text-center mb-4">

                    <i class="bi bi-award-fill text-warning"
                       style="font-size: 70px;"></i>

                    <h2 class="fw-bold text-primary mt-3">
                        Sertifikat Pelatihan
                    </h2>

                    <p class="text-muted">
                        Sertifikat yang diperoleh setelah peserta memenuhi
                        persyaratan pelatihan.
                    </p>

                </div>

                <hr>

                <!-- INFORMASI -->
                <div class="alert alert-info">

                    <i class="bi bi-info-circle-fill"></i>

                    <strong>Informasi Sertifikat</strong>

                    <p class="mb-0 mt-2">
                        Peserta harus dinyatakan lulus oleh mentor dan
                        menyelesaikan angket evaluasi sebelum sertifikat
                        pelatihan dapat diterbitkan.
                    </p>

                </div>

                <!-- DUA SERTIFIKAT -->
                <div class="row g-4 mt-2">

                    <!-- SERTIFIKAT CREATIVEMU -->
                    <div class="col-md-6">

                        <div class="card border-primary h-100">

                            <div class="card-body text-center p-4">

                                <i class="bi bi-patch-check-fill text-primary"
                                   style="font-size: 60px;"></i>

                                <h4 class="fw-bold mt-3">
                                    Sertifikat CreativeMU Academy
                                </h4>

                                <p class="text-muted">
                                    Sertifikat pelatihan yang diterbitkan
                                    oleh Admin CreativeMU Academy.
                                </p>

                                <button type="button"
                                        class="btn btn-secondary"
                                        disabled>

                                    <i class="bi bi-download"></i>
                                    Download Sertifikat

                                </button>

                                <div class="mt-3">

                                    <span class="badge bg-warning text-dark">
                                        Belum tersedia
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- SERTIFIKAT BNSP -->
                    <div class="col-md-6">

                        <div class="card border-warning h-100">

                            <div class="card-body text-center p-4">

                                <i class="bi bi-award-fill text-warning"
                                   style="font-size: 60px;"></i>

                                <h4 class="fw-bold mt-3">
                                    Sertifikat BNSP
                                </h4>

                                <p class="text-muted">
                                    Sertifikat kompetensi yang diperoleh
                                    melalui proses sertifikasi BNSP.
                                </p>

                                <button type="button"
                                        class="btn btn-secondary"
                                        disabled>

                                    <i class="bi bi-download"></i>
                                    Download Sertifikat

                                </button>

                                <div class="mt-3">

                                    <span class="badge bg-warning text-dark">
                                        Belum tersedia
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- KEMBALI -->
                <div class="text-center mt-5">

                    <a href="<?= base_url('pelatihan/kbm') ?>"
                       class="btn btn-outline-primary">

                        <i class="bi bi-arrow-left"></i>
                        Kembali ke KBM

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>