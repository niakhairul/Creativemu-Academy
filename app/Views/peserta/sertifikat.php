<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow border-0 rounded-4">

            <div class="card-body p-5 text-center">

                <!-- Icon -->
                <div class="mb-4">
                    <i class="bi bi-award-fill text-warning"
                       style="font-size: 80px;"></i>
                </div>

                <!-- Judul -->
                <h2 class="fw-bold text-primary">
                    Sertifikat Pelatihan
                </h2>

                <p class="text-muted mt-3">
                    Sertifikat pelatihan akan tersedia setelah peserta
                    memenuhi seluruh persyaratan yang telah ditentukan.
                </p>

                <hr class="my-4">

                <!-- Informasi Persyaratan -->
                <div class="alert alert-info text-start">

                    <div class="fw-bold mb-2">
                        <i class="bi bi-info-circle-fill"></i>
                        Persyaratan Sertifikat
                    </div>

                    <ul class="mb-0">
                        <li>Dinyatakan lulus ujian oleh sistem/mentor.</li>
                        <li>Telah mengisi angket evaluasi pelatihan.</li>
                        <li>Memenuhi persyaratan pelatihan lainnya.</li>
                    </ul>

                </div>

                <!-- Status -->
                <div class="card bg-light border-0 rounded-3 mt-4">

                    <div class="card-body">

                        <i class="bi bi-hourglass-split text-warning"
                           style="font-size: 35px;"></i>

                        <h5 class="fw-bold mt-3">
                            Sertifikat Belum Tersedia
                        </h5>

                        <p class="text-muted mb-0">
                            Hasil kelulusan ujian belum ditentukan.
                            Silakan menunggu informasi dari mentor.
                        </p>

                    </div>

                </div>

                <!-- Tombol Download -->
                <div class="mt-4">

                    <button type="button"
                            class="btn btn-secondary"
                            disabled>

                        <i class="bi bi-download"></i>
                        Download Sertifikat

                    </button>

                </div>

                <!-- Kembali -->
                <div class="mt-4">

                    <a href="<?= base_url('pelatihan/kbm') ?>"
                       class="btn btn-outline-primary">

                        <i class="bi bi-arrow-left"></i>
                        Kembali ke KBM

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>