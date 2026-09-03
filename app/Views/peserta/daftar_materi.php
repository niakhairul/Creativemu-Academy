<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="card shadow border-0 rounded-4">

    <div class="mb-4">
        <h2 class="fw-bold">
            Daftar Materi
        </h2>

        <p class="text-muted mb-0">
            Pilih materi pembelajaran yang ingin Anda pelajari.
        </p>
    </div>

    <?php if (empty($materi)): ?>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            Belum ada materi yang diunggah oleh mentor.
        </div>

    <?php else: ?>

        <div class="row g-4">

            <?php foreach ($materi as $item): ?>

                <div class="col-lg-4 col-md-6">

                    <div class="materi-card h-100">

                        <div class="materi-icon">
                            📚
                        </div>

                        <span class="materi-badge">
                            Materi Pembelajaran
                        </span>

                        <h4 class="fw-bold mt-3">
                            <?= esc($item['judul_materi']) ?>
                        </h4>

                        <p class="text-muted">
                            <?= esc($item['deskripsi']) ?>
                        </p>

                        <div class="materi-footer">

                            <?php if (!empty($item['file_materi'])): ?>

                                <span class="status-materi">
                                    ✓ Materi tersedia
                                </span>

                            <?php else: ?>

                                <span class="status-materi text-muted">
                                    File belum tersedia
                                </span>

                            <?php endif; ?>

                            <a href="<?= base_url('pelatihan/materi?id=' . $item['id_materi_kelas']) ?>"
                               class="btn btn-primary">

                                Pelajari
                                <i class="bi bi-arrow-right"></i>

                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>


<style>

.materi-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px;
    border: 1px solid #eeeeee;
    box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);

    display: flex;
    flex-direction: column;

    transition: all 0.25s ease;
}

.materi-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(108, 63, 200, 0.15);
    border-color: #8b5cf6;
}

.materi-icon {
    width: 58px;
    height: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background: #f1eaff;

    font-size: 28px;
}

.materi-badge {
    display: inline-block;

    margin-top: 18px;

    width: fit-content;

    padding: 5px 12px;

    border-radius: 20px;

    background: #eee5ff;
    color: #6f32c9;

    font-size: 13px;
    font-weight: 600;
}

.materi-card h4 {
    font-size: 20px;
    line-height: 1.4;
}

.materi-card p {
    font-size: 14px;
    line-height: 1.6;
}

.materi-footer {
    margin-top: auto;

    padding-top: 20px;

    border-top: 1px solid #eeeeee;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 10px;
}

.status-materi {
    font-size: 12px;
    font-weight: 600;

    color: #198754;
}

.materi-footer .btn {
    border-radius: 9px;

    padding: 8px 14px;

    font-size: 14px;
}

</style>

<?= $this->endSection() ?>