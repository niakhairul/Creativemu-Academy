<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-body p-5">

            <h2 class="fw-bold text-primary">
                Digital Marketing
            </h2>

            <p class="text-muted">
                Mentor : Bapak Andi Saputra
            </p>

            <hr>

<h5 class="mb-3">Progress Belajar</h5>

<div class="progress mb-4" style="height:20px;">

    <div class="progress-bar bg-success"
         style="width:40%;">

        40%

    </div>

</div>

<div class="row mb-4">

    <div class="col-md-4">

        <strong>Metode</strong><br>
        Online

    </div>

    <div class="col-md-4">

        <strong>Jadwal</strong><br>
        Senin & Rabu

    </div>

    <div class="col-md-4">

        <strong>Jam</strong><br>
        19.00 WIB

    </div>

</div>

            <hr>

            <h4 class="mb-4">
                Daftar Materi
            </h4>

            <div class="list-group">

    <!-- Pertemuan 1 -->
    <div class="list-group-item d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-1">📘 Pertemuan 1</h5>

            <p class="mb-1">Pengenalan Digital Marketing</p>

            <span class="badge bg-success">
                Selesai
            </span>

        </div>

        <a href="<?= base_url('pelatihan/materi') ?>"
           class="btn btn-primary">

            Lihat Materi

        </a>

    </div>

    <!-- Pertemuan 2 -->
    <div class="list-group-item d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-1">📘 Pertemuan 2</h5>

            <p class="mb-1">SEO Dasar</p>

            <span class="badge bg-warning text-dark">
                Sedang Dipelajari
            </span>

        </div>

        <a href="<?= base_url('pelatihan/materi') ?>"
           class="btn btn-primary">

            Lihat Materi

        </a>

    </div>

    <!-- Pertemuan 3 -->
    <div class="list-group-item d-flex justify-content-between align-items-center">

        <div>

            <h5 class="mb-1">📘 Pertemuan 3</h5>

            <p class="mb-1">Social Media Marketing</p>

            <span class="badge bg-secondary">
                Belum Dibuka
            </span>

        </div>

        <a href="<?= base_url('pelatihan/materi') ?>"
           class="btn btn-primary">

            Lihat Materi

        </a>

    </div>

</div>

            <hr class="my-4">

<div class="d-flex justify-content-between">

    <a href="<?= base_url('pelatihan/kelas') ?>"
       class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left"></i>

        Kembali ke Kelas Saya

    </a>

</div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>