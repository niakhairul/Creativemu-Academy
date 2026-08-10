<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="card shadow border-0 rounded-4">

    <div class="card-body p-4">

        <h2 class="fw-bold text-primary">
            Daftar Materi
        </h2>

        <p class="text-muted">
            Pilih materi yang ingin dipelajari.
        </p>

        <hr>

        <div class="list-group">

            <a href="<?= base_url('pelatihan/materi?id=1') ?>"
               class="list-group-item list-group-item-action">

                <h5 class="fw-bold mb-1">
                    📚 Pertemuan 1
                </h5>

                <p class="mb-1">
                    Pengenalan Digital Marketing
                </p>

            </a>

            <a href="<?= base_url('pelatihan/materi?id=2') ?>"
               class="list-group-item list-group-item-action">

                <h5 class="fw-bold mb-1">
                    📚 Pertemuan 2
                </h5>

                <p class="mb-1">
                    Social Media Marketing
                </p>

            </a>

            <a href="<?= base_url('pelatihan/materi?id=3') ?>"
               class="list-group-item list-group-item-action">

                <h5 class="fw-bold mb-1">
                    📚 Pertemuan 3
                </h5>

                <p class="mb-1">
                    Facebook Ads
                </p>

            </a>

        </div>

    </div>

</div>

<?= $this->endSection() ?>