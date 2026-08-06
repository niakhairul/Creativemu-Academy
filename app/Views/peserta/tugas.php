<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container py-5">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-body p-5">

            <h2 class="fw-bold text-primary">
                Tugas Pertemuan 1
            </h2>

            <p class="text-muted">
                Digital Marketing
            </p>

            <hr>

            <h5>Instruksi Tugas</h5>

            <p>
                Buatlah analisis sederhana mengenai strategi digital marketing
                dari salah satu produk UMKM di sekitar Anda.
            </p>

            <div class="mb-4">

                <label class="form-label">
                    Upload Jawaban
                </label>

                <input type="file"
                       class="form-control">

            </div>

            <button class="btn btn-success">

                Kirim Tugas

            </button>

        </div>

    </div>

</div>

<?= $this->endSection() ?>