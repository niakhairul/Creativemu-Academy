<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg border-0 rounded-4">

    <div class="card-body p-5">

        <h2 class="fw-bold text-primary">
            Tugas Digital Marketing
        </h2>

        <p class="text-muted">
            Mentor : Bapak Andi Saputra
        </p>

        <hr>

        <h4>📝 Deskripsi Tugas</h4>

        <p>
            Buatlah strategi promosi sebuah produk menggunakan media sosial
            (Instagram atau TikTok) kemudian simpan dalam bentuk PDF.
        </p>

        <hr>

        <h4>📤 Upload Tugas</h4>

        <form action="<?= base_url('pelatihan/upload-tugas') ?>"
              method="post"
              enctype="multipart/form-data">

            <?= csrf_field(); ?>

            <input
                type="file"
                name="tugas"
                class="form-control mb-3"
                required>

            <button
                class="btn btn-success">

                Upload Tugas

            </button>

        </form>

        <hr>

        <div class="d-flex justify-content-between">

            <a href="<?= base_url('pelatihan/materi?id=' . $kelas['id']) ?>"
               class="btn btn-secondary">

                ← Kembali ke Materi

            </a>

            <button
                class="btn btn-primary"
                disabled>

                Menunggu Penilaian Mentor

            </button>

        </div>

    </div>

</div>
<?= $this->endSection() ?>