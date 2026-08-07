<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <h1 class="fw-bold mb-3">Daftar Kelas</h1>

    <p class="text-muted mb-4">
        Silakan pilih kelas pelatihan yang ingin Anda ikuti.
    </p>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="row">

        <?php foreach ($kelas as $k) : ?>

            <div class="col-md-6 col-lg-5 mb-4">

                <div class="card shadow border-0 rounded-4 h-100">

                    <div class="card-body">

                        <h3 class="fw-bold">
                            <?= esc($k['nama_kelas']) ?>
                        </h3>

                        <hr>

                        <p>
                            <strong>Mentor :</strong><br>
                            <?= esc($k['mentor']) ?>
                        </p>

                        <p>
                            <strong>Metode :</strong>
                            <?= esc($k['metode']) ?>
                        </p>

                        <p>
                            <strong>Jadwal :</strong><br>
                            <?= esc($k['jadwal']) ?>
                        </p>

                        <p>
                            <strong>Jam :</strong>
                            <?= esc($k['jam']) ?>
                        </p>

                        <p>
                            <strong>Kuota :</strong>
                            <?= $k['kuota'] ?> Orang
                        </p>

                        <p>
                            <strong>Terdaftar :</strong>
                            <?= $k['terdaftar'] ?> Orang
                        </p>

                        <p>
                            <strong>Sisa Kuota :</strong>

                            <?php if ($k['sisa'] > 0) : ?>

                                <span class="badge bg-success">
                                    <?= $k['sisa'] ?> Orang
                                </span>

                            <?php else : ?>

                                <span class="badge bg-danger">
                                    Penuh
                                </span>

                            <?php endif; ?>

                        </p>

                        <p>
                            <?= esc($k['deskripsi']) ?>
                        </p>

                        <?php if ($k['sisa'] > 0) : ?>

    <a href="<?= base_url('pelatihan/pendaftaran?id=' . $k['id']) ?>"
       class="btn btn-primary w-100">

        Daftar Kelas

    </a>

<?php else : ?>

    <button
        class="btn btn-secondary w-100"
        disabled>

        Kuota Penuh

    </button>

<?php endif; ?>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<?= $this->endSection() ?>