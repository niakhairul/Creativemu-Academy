<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold">Ujian Saya</h2>
        <p class="text-muted">
            Kerjakan ujian yang tersedia untuk kelas yang Anda ikuti.
        </p>
    </div>

    <!-- Card Ujian -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge bg-primary mb-2">
                        Ujian Akhir
                    </span>

                    <h4 class="fw-bold mb-2">
                        Ujian Akhir Digital Marketing
                    </h4>

                    <p class="text-muted mb-4">
                        Ujian akhir untuk mengukur pemahaman peserta
                        setelah mengikuti pembelajaran Digital Marketing.
                    </p>
                </div>

                <div class="text-end">
    <small class="text-muted d-block">
        Status
    </small>

    <?php if ($ujian_selesai): ?>

        <span class="badge bg-warning text-dark">
            Menunggu Penilaian
        </span>

    <?php else: ?>

        <span class="badge bg-warning text-dark">
            Belum Dikerjakan
        </span>

    <?php endif; ?>
</div>

            <hr>

            <!-- Informasi Ujian -->
            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block">
                            Jumlah Soal
                        </small>
                        <strong>
                            20 Soal
                        </strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block">
                            Durasi
                        </small>
                        <strong>
                            30 Menit
                        </strong>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3">
                        <small class="text-muted d-block">
                            Nilai Minimal
                        </small>
                        <strong>
                            70
                        </strong>
                    </div>
                </div>

            </div>

            <!-- Tombol -->
            <div class="text-end">

    <?php if ($ujian_selesai): ?>

        <a href="<?= base_url('pelatihan/ujian/hasil') ?>"
           class="btn btn-success">
            Lihat Hasil →
        </a>

    <?php else: ?>

        <a href="<?= base_url('pelatihan/ujian/mulai') ?>"
           class="btn btn-primary">
            Mulai Ujian →
        </a>

    <?php endif; ?>

</div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>