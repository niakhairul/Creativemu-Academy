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

            <h4 class="mb-4">
                Daftar Materi
            </h4>

            <div class="list-group">

                <div class="list-group-item d-flex justify-content-between align-items-center">

                    <div>

                        <strong>Pertemuan 1</strong>

                        <br>

                        Pengenalan Digital Marketing

                    </div>

                    <button class="btn btn-primary">

                        Lihat Materi

                    </button>

                </div>

                <div class="list-group-item d-flex justify-content-between align-items-center">

                    <div>

                        <strong>Pertemuan 2</strong>

                        <br>

                        SEO Dasar

                    </div>

                    <button class="btn btn-primary">

                        Lihat Materi

                    </button>

                </div>

                <div class="list-group-item d-flex justify-content-between align-items-center">

                    <div>

                        <strong>Pertemuan 3</strong>

                        <br>

                        Social Media Marketing

                    </div>

                    <button class="btn btn-primary">

                        Lihat Materi

                    </button>

                </div>

            </div>

            <hr class="my-5">

            <h4>
                Tugas
            </h4>

            <p>
                Upload tugas setelah menyelesaikan materi.
            </p>

            <input type="file" class="form-control mb-3">

            <button class="btn btn-success">

                Upload Tugas

            </button>

        </div>

    </div>

</div>

<?= $this->endSection() ?>