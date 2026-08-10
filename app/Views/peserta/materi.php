<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg border-0 rounded-4">

    <div class="mb-4">

    <h2 class="fw-bold">
        Pengenalan Digital Marketing
    </h2>

    <p class="text-muted mb-0">
        Pertemuan 1 &nbsp;•&nbsp; Mentor: Bapak Andi Saputra
    </p>

</div>


<!-- Informasi Materi -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <div class="d-flex align-items-center gap-3">

            <div class="materi-icon">
                📚
            </div>

            <div>

                <span class="badge bg-primary mb-2">
                    Pertemuan 1
                </span>

                <h5 class="fw-bold mb-1">
                    Pengenalan Digital Marketing
                </h5>

                <p class="text-muted mb-0">
                    Pelajari dasar-dasar Digital Marketing.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- Video Pembelajaran -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-3">
            🎥 Video Pembelajaran
        </h4>

        <div class="ratio ratio-16x9 rounded-4 overflow-hidden">

            <iframe
                src="https://www.youtube.com/embed/dQw4w9WgXcQ"
                title="Video Pembelajaran Digital Marketing"
                allowfullscreen>
            </iframe>

        </div>

        <p class="text-muted mt-3 mb-0">
            Silakan tonton video pembelajaran sebelum mengerjakan tugas.
        </p>

    </div>

</div>


<!-- Modul -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <div class="d-flex align-items-center justify-content-between">

            <div>

                <h4 class="fw-bold mb-2">
                    📄 Modul Pembelajaran
                </h4>

                <p class="text-muted mb-0">
                    Modul pembelajaran Digital Marketing untuk Pertemuan 1.
                </p>

            </div>

            <div>

                <a href="#"
                   class="btn btn-outline-primary">

                    <i class="bi bi-download"></i>
                    Download Modul

                </a>

            </div>

        </div>

    </div>

</div>


<!-- Ringkasan Materi -->
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <h4 class="fw-bold mb-3">
            📝 Ringkasan Materi
        </h4>

        <p class="text-muted">

            Pada materi pertama ini peserta akan mempelajari
            dasar-dasar Digital Marketing, manfaat,
            serta strategi pemasaran digital.

        </p>


        <div class="materi-point">

            <div class="point-icon">
                ✓
            </div>

            <div>
                <strong>Dasar Digital Marketing</strong>

                <p class="text-muted mb-0">
                    Mengenal pengertian dan konsep dasar pemasaran digital.
                </p>

            </div>

        </div>


        <div class="materi-point">

            <div class="point-icon">
                ✓
            </div>

            <div>
                <strong>Manfaat Digital Marketing</strong>

                <p class="text-muted mb-0">
                    Memahami manfaat pemasaran menggunakan media digital.
                </p>

            </div>

        </div>


        <div class="materi-point">

            <div class="point-icon">
                ✓
            </div>

            <div>
                <strong>Strategi Pemasaran Digital</strong>

                <p class="text-muted mb-0">
                    Mengenal beberapa strategi yang dapat digunakan
                    dalam pemasaran digital.
                </p>

            </div>

        </div>

    </div>

</div>


<!-- Navigasi -->
<div class="d-flex justify-content-between align-items-center mt-4 mb-4">

    <a href="<?= base_url('pelatihan/daftar-materi') ?>"
       class="btn btn-light border">

        <i class="bi bi-arrow-left"></i>
        Kembali ke Materi

    </a>


    <a href="<?= base_url('pelatihan/tugas') ?>"
       class="btn btn-success">

        Lanjut ke Tugas
        <i class="bi bi-arrow-right"></i>

    </a>

</div>


<style>

.materi-icon {

    width: 60px;
    height: 60px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #f0e7ff;

    border-radius: 16px;

    font-size: 28px;

}


.materi-point {

    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 14px 0;

    border-bottom: 1px solid #eeeeee;

}


.materi-point:last-child {

    border-bottom: none;

}


.point-icon {

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


.materi-point strong {

    font-size: 15px;

}


.materi-point p {

    font-size: 14px;

    margin-top: 3px;

}


.card {

    transition: all 0.2s ease;

}


.card:hover {

    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;

}

</style>

<?= $this->endSection() ?>