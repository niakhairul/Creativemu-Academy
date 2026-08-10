<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg border-0 rounded-4">

    <div class="card-body p-5">

        <h2 class="fw-bold text-primary">
            Pengenalan Digital Marketing
        </h2>

        <p class="text-muted">
            Mentor : Bapak Andi Saputra
        </p>

        <hr>

        <h4>🎥 Video Pembelajaran</h4>

        <div class="ratio ratio-16x9 mb-4">

            <iframe
                src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                allowfullscreen>
            </iframe>

        </div>

        <hr>

        <h4>📄 Modul</h4>

        <p>
            Modul pembelajaran Digital Marketing.
        </p>

        <a href="#"
           class="btn btn-outline-primary mb-4">

            Download Modul

        </a>

        <hr>

        <h4>📝 Ringkasan Materi</h4>

        <p>
            Pada materi pertama ini peserta akan mempelajari
            dasar-dasar Digital Marketing, manfaat,
            serta strategi pemasaran digital.
        </p>

        <hr>

        <div class="d-flex justify-content-between mt-4">

    <a href="<?= base_url('pelatihan/daftar-materi') ?>"
       class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>
        Kembali

    </a>

    <a href="<?= base_url('pelatihan/tugas') ?>"
       class="btn btn-success">

        Lanjut ke Tugas
        <i class="bi bi-arrow-right"></i>

    </a>

</div>

    </div>

</div>

<?= $this->endSection() ?>