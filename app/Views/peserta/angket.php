<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Terima kasih!</strong>
        <?= session()->getFlashdata('success') ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>

<?php endif; ?>


<?php if (session()->getFlashdata('error')): ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>❌ Gagal!</strong>
        <?= session()->getFlashdata('error') ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>

<?php endif; ?>


<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">

        <h2 class="fw-bold">
            Angket Evaluasi Pelatihan
        </h2>

        <p class="text-muted">
            Silakan isi angket sebagai evaluasi terhadap pelatihan yang telah Anda ikuti.
        </p>

    </div>


    <!-- Informasi -->
    <div class="alert alert-info">

        <i class="bi bi-info-circle-fill"></i>

        Jawablah setiap pertanyaan sesuai dengan pengalaman Anda selama mengikuti
        pelatihan.

    </div>


    <?php if ($sudahIsi): ?>

        <!-- Jika Sudah Mengisi -->
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-5 text-center">

                <div class="mb-3">

                    <i class="bi bi-check-circle-fill text-success"
                       style="font-size: 70px;"></i>

                </div>

                <h4 class="fw-bold text-success">
                    Angket Sudah Diisi
                </h4>

                <p class="text-muted mb-0">
                    Terima kasih, Anda sudah mengisi angket evaluasi pelatihan.
                </p>

            </div>

        </div>


    <?php else: ?>


        <!-- Form Angket -->
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <form action="<?= base_url('pelatihan/angket/simpan') ?>"
                      method="post">

                    <?= csrf_field(); ?>


                    <!-- ID Kelas -->
                    <input type="hidden"
                           name="kelas_id"
                           value="<?= esc($pendaftaran['kelas_id']) ?>">


                    <!-- Pertanyaan 1 -->
                    <div class="mb-4">

                        <label class="fw-bold mb-2">

                            1. Bagaimana penilaian Anda terhadap materi pelatihan?

                        </label>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="materi"
                                value="Sangat Baik"
                                required
                            >

                            <label class="form-check-label">
                                Sangat Baik
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="materi"
                                value="Baik"
                            >

                            <label class="form-check-label">
                                Baik
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="materi"
                                value="Cukup"
                            >

                            <label class="form-check-label">
                                Cukup
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="materi"
                                value="Kurang"
                            >

                            <label class="form-check-label">
                                Kurang
                            </label>

                        </div>

                    </div>


                    <!-- Pertanyaan 2 -->
                    <div class="mb-4">

                        <label class="fw-bold mb-2">

                            2. Bagaimana penilaian Anda terhadap mentor?

                        </label>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="mentor"
                                value="Sangat Baik"
                                required
                            >

                            <label class="form-check-label">
                                Sangat Baik
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="mentor"
                                value="Baik"
                            >

                            <label class="form-check-label">
                                Baik
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="mentor"
                                value="Cukup"
                            >

                            <label class="form-check-label">
                                Cukup
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="mentor"
                                value="Kurang"
                            >

                            <label class="form-check-label">
                                Kurang
                            </label>

                        </div>

                    </div>


                    <!-- Pertanyaan 3 -->
                    <div class="mb-4">

                        <label class="fw-bold mb-2">

                            3. Bagaimana kualitas penyampaian materi selama pelatihan?

                        </label>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="penyampaian"
                                value="Sangat Baik"
                                required
                            >

                            <label class="form-check-label">
                                Sangat Baik
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="penyampaian"
                                value="Baik"
                            >

                            <label class="form-check-label">
                                Baik
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="penyampaian"
                                value="Cukup"
                            >

                            <label class="form-check-label">
                                Cukup
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="penyampaian"
                                value="Kurang"
                            >

                            <label class="form-check-label">
                                Kurang
                            </label>

                        </div>

                    </div>


                    <!-- Pertanyaan 4 -->
                    <div class="mb-4">

                        <label class="fw-bold mb-2">

                            4. Apakah pelatihan ini bermanfaat bagi Anda?

                        </label>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="manfaat"
                                value="Sangat Bermanfaat"
                                required
                            >

                            <label class="form-check-label">
                                Sangat Bermanfaat
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="manfaat"
                                value="Bermanfaat"
                            >

                            <label class="form-check-label">
                                Bermanfaat
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="manfaat"
                                value="Cukup Bermanfaat"
                            >

                            <label class="form-check-label">
                                Cukup Bermanfaat
                            </label>

                        </div>


                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="radio"
                                name="manfaat"
                                value="Tidak Bermanfaat"
                            >

                            <label class="form-check-label">
                                Tidak Bermanfaat
                            </label>

                        </div>

                    </div>


                    <!-- Saran -->
                    <div class="mb-4">

                        <label class="fw-bold mb-2">

                            5. Saran dan masukan

                        </label>


                        <textarea
                            name="saran"
                            class="form-control"
                            rows="5"
                            placeholder="Tuliskan saran atau masukan Anda..."
                            required
                        ></textarea>

                    </div>


                    <!-- Tombol -->
                    <div class="text-end">

                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                        >

                            <i class="bi bi-send"></i>
                            Kirim Angket

                        </button>

                    </div>


                </form>

            </div>

        </div>

    <?php endif; ?>


</div>


<?= $this->endSection() ?>