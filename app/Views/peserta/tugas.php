<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg border-0 rounded-4">

<?php if (session()->getFlashdata('success')): ?>

    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <strong>✅ Berhasil!</strong>
        <?= session()->getFlashdata('success') ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>

<?php endif; ?>


<?php if (session()->getFlashdata('error')): ?>

    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong>❌ Gagal!</strong>
        <?= session()->getFlashdata('error') ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>
    </div>

<?php endif; ?>
    <div class="mb-4">

    <h2 class="fw-bold">
        Tugas Digital Marketing
    </h2>

    <p class="text-muted mb-0">
        Pertemuan 1 &nbsp;•&nbsp; Mentor: Bapak Andi Saputra
    </p>

</div>


<!-- Informasi Tugas -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <div class="d-flex align-items-center gap-3">

            <div class="tugas-icon">
                📝
            </div>

            <div>

                <span class="badge bg-warning text-dark mb-2">
                    Tugas Pertemuan 1
                </span>

                <h5 class="fw-bold mb-1">
                    Strategi Promosi Produk
                </h5>

                <p class="text-muted mb-0">
                    Buat strategi promosi menggunakan media sosial.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- Deskripsi Tugas -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-3">
            📋 Deskripsi Tugas
        </h4>

        <p class="text-muted mb-3">

            Buatlah strategi promosi sebuah produk menggunakan
            media sosial seperti Instagram atau TikTok.

        </p>


        <div class="task-requirement">

            <div class="requirement-icon">
                ✓
            </div>

            <div>

                <strong>Ketentuan Tugas</strong>

                <p class="text-muted mb-0">
                    Buat strategi promosi produk menggunakan
                    Instagram atau TikTok.
                </p>

            </div>

        </div>


        <div class="task-requirement">

            <div class="requirement-icon">
                ✓
            </div>

            <div>

                <strong>Format File</strong>

                <p class="text-muted mb-0">
                    Simpan tugas dalam format PDF.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- Upload Tugas -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-2">
            📤 Upload Tugas
        </h4>

        <p class="text-muted">
            Pilih file tugas yang ingin dikumpulkan.
        </p>


        <form action="<?= base_url('pelatihan/upload-tugas') ?>"
              method="post"
              enctype="multipart/form-data">

            <?= csrf_field(); ?>


            <div class="upload-area">

                <div class="upload-icon">
                    📄
                </div>

                <h6 class="fw-bold">
                    Pilih file tugas
                </h6>

                <p class="text-muted small">
                    Format PDF
                </p>


                <input
                    type="file"
                    name="tugas"
                    class="form-control"
                    accept=".pdf"
                    required
                >

            </div>


            <div class="text-end mt-3">

                <button
                    type="submit"
                    class="btn btn-success">

                    <i class="bi bi-cloud-arrow-up"></i>
                    Upload Tugas

                </button>

            </div>

        </form>

    </div>

</div>


<!-- Status -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

    <div class="d-flex align-items-center gap-3">

        <?php if ($pengumpulan): ?>

            <div class="status-icon bg-success-subtle text-success">
                ✓
            </div>

            <div>

                <h6 class="fw-bold mb-1">
                    Tugas Sudah Dikumpulkan
                </h6>

                <p class="text-muted mb-1">
                    File:
                    <strong>
                        <?= esc($pengumpulan['file_tugas']) ?>
                    </strong>
                </p>

                <span class="badge bg-warning text-dark">
                    <?= esc($pengumpulan['status']) ?>
                </span>

            </div>

        <?php else: ?>

            <div class="status-icon">
                ⏳
            </div>

            <div>

                <h6 class="fw-bold mb-1">
                    Belum Mengumpulkan Tugas
                </h6>

                <p class="text-muted mb-0">
                    Silakan upload tugas Anda terlebih dahulu.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</div>


<!-- Navigasi -->
<div class="d-flex justify-content-between align-items-center mt-4 mb-4">

    <a href="<?= base_url('pelatihan/materi?id=' . $kelas['id']) ?>"
       class="btn btn-light border">

        <i class="bi bi-arrow-left"></i>
        Kembali ke Materi

    </a>


    <?php if ($pengumpulan): ?>

    <button
        class="btn btn-warning text-dark"
        disabled>

        <i class="bi bi-hourglass-split"></i>
        Menunggu Penilaian

    </button>

<?php else: ?>

    <a href="#"
       class="btn btn-success">

        <i class="bi bi-upload"></i>
        Upload Tugas

    </a>

<?php endif; ?>

</div>


<style>

.tugas-icon {

    width: 60px;
    height: 60px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #fff3cd;

    border-radius: 16px;

    font-size: 28px;

}


.task-requirement {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 14px 0;

    border-bottom: 1px solid #eeeeee;

}


.task-requirement:last-child {

    border-bottom: none;

}


.requirement-icon {

    width: 28px;
    height: 28px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #e8f7ef;

    color: #198754;

    font-weight: bold;

    flex-shrink: 0;

}


.task-requirement strong {

    font-size: 15px;

}


.task-requirement p {

    font-size: 14px;

    margin-top: 3px;

}


.upload-area {

    padding: 25px;

    border: 2px dashed #d9d9d9;

    border-radius: 16px;

    text-align: center;

    background: #fafafa;

}


.upload-icon {

    font-size: 35px;

    margin-bottom: 8px;

}


.upload-area input {

    max-width: 500px;

    margin: 15px auto 0;

    text-align: left;

}


.status-icon {

    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #fff3cd;

    font-size: 22px;

}


.card {

    transition: all 0.2s ease;

}


.card:hover {

    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;

}

</style>
<?= $this->endSection() ?>