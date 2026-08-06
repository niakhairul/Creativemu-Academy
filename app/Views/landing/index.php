<?= $this->extend('layouts/template'); ?>

<?= $this->section('content'); ?>

<div class="container py-5">

    <div class="row align-items-center">

        <div class="col-lg-6">

            <h1 class="fw-bold display-5">
                Selamat Datang di
                <span class="text-primary">Creativemu Academy</span>
            </h1>

            <p class="text-muted mt-3">
                Tingkatkan kemampuanmu melalui berbagai pelatihan
                bersama mentor yang berpengalaman.
            </p>

            <a href="#" class="btn btn-primary btn-lg mt-3">
                Daftar Sekarang
            </a>

        </div>

        <div class="col-lg-6 text-center">

            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=700"
                 class="img-fluid rounded">

        </div>

    </div>

</div>

<?= $this->endSection(); ?>