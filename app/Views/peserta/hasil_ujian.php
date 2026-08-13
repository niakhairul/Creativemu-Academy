<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">

        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-5 text-center">

                <div class="mb-4">
                    <i class="bi bi-trophy-fill text-warning"
                       style="font-size: 70px;"></i>
                </div>

                <h2 class="fw-bold text-primary">
                    Hasil Ujian
                </h2>

                <p class="text-muted">
                    Ujian Akhir Digital Marketing telah selesai.
                </p>

                <hr>

                <div class="row justify-content-center mt-4">

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <small class="text-muted">
                                    Jawaban Benar
                                </small>

                                <h3 class="fw-bold text-success">
                                    <?= $benar ?>
                                </h3>

                                <small>
                                    dari <?= $jumlahSoal ?> soal
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <small class="text-muted">
                                    Nilai
                                </small>

                                <h3 class="fw-bold text-primary">
                                    <?= number_format($nilai, 0) ?>
                                </h3>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-4">

    <div class="alert alert-warning">
        <i class="bi bi-clock-fill"></i>
        <strong>Ujian telah dikumpulkan.</strong>
        Hasil ujian Anda sedang menunggu penilaian dari mentor.
    </div>

</div>

                <div class="mt-4">

                    <a href="<?= base_url('pelatihan/ujian') ?>"
                       class="btn btn-primary">
                        Kembali ke Ujian
                    </a>

                    <a href="<?= base_url('pelatihan/kbm') ?>"
                       class="btn btn-secondary">
                        Kembali ke KBM
                    </a>

                </div>

            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>