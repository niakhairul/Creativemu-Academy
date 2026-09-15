<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-9">

        <div class="card shadow border-0 rounded-4" style="border: 1px solid #ddd6fe !important;">
            <div class="card-body p-4 p-md-5 text-center">

                <!-- Icon Status Kelulusan -->
                <div class="mb-4">
                    <?php if ($isLulus): ?>
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                             style="width: 100px; height: 100px; background: #dcfce7; border: 4px solid #86efac;">
                            <i class="bi bi-trophy-fill text-success" style="font-size: 50px;"></i>
                        </div>
                    <?php else: ?>
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                             style="width: 100px; height: 100px; background: #fee2e2; border: 4px solid #fca5a5;">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 48px;"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <span class="badge bg-purple-soft text-purple mb-2 px-3 py-2 fw-bold rounded-pill" style="background-color: #ede9fe; color: #6d28d9;">
                    Evaluasi Ujian & Remidi
                </span>

                <h2 class="fw-bold mb-1" style="color: #3b0764;">
                    Hasil Ujian Kompetensi
                </h2>

                <p class="text-muted mb-4">
                    Kelas: <strong><?= esc($kelas['nama_kelas'] ?? 'CreativeMU Academy') ?></strong>
                </p>

                <hr class="opacity-25 mb-4">

                <!-- Kotak Nilai Utama & Status -->
                <div class="row justify-content-center g-3 mb-4">
                    <div class="col-md-5">
                        <div class="card border-0 p-4 rounded-4 shadow-sm" style="background: #faf5ff; border: 1px solid #e9d5ff !important;">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1">
                                Nilai Ujian
                            </small>
                            <h1 class="display-3 fw-bold mb-0" style="color: <?= $isLulus ? '#15803d' : '#b91c1c' ?>;">
                                <?= (int) $nilaiTerbaru ?>%
                            </h1>
                            <small class="text-muted">Standar Minimal: 70%</small>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="card border-0 p-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-center" style="background: #faf5ff; border: 1px solid #e9d5ff !important;">
                            <small class="text-muted fw-bold text-uppercase d-block mb-2">
                                Status Kelulusan
                            </small>

                            <?php if ($isLulus): ?>
                                <div>
                                    <span class="badge bg-success px-4 py-2 fs-5 rounded-pill shadow-sm">
                                        <i class="bi bi-check-circle-fill me-1"></i> LULUS
                                    </span>
                                </div>
                                <p class="text-success small fw-semibold mt-2 mb-0">
                                    Selamat! Anda telah memenuhi standar kompetensi.
                                </p>
                            <?php else: ?>
                                <div>
                                    <span class="badge bg-danger px-4 py-2 fs-5 rounded-pill shadow-sm">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> BELUM LULUS
                                    </span>
                                </div>
                                <p class="text-danger small fw-semibold mt-2 mb-0">
                                    Nilai di bawah 70%. Anda wajib mengikuti remidi.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Rekap Nilai Ujian Awal & Remidi Terpisah -->
                <?php if ($isRemidiApplied): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 mx-auto" style="max-width: 500px; background: #ffffff; border: 1px solid #ddd6fe !important;">
                        <h6 class="fw-bold mb-3 text-dark text-start border-bottom pb-2">
                            <i class="bi bi-card-checklist me-1 text-primary"></i> Rincian Nilai Terpisah
                        </h6>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Nilai Ujian Awal:</span>
                            <span class="fw-bold text-dark fs-6"><?= (int) $nilaiAwal ?>%</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Nilai Remidi:</span>
                            <span class="fw-bold text-primary fs-6"><?= (int) $nilaiRemidi ?>%</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="fw-bold text-dark">Status Akhir:</span>
                            <span class="badge <?= $isLulus ? 'bg-success' : 'bg-danger' ?> px-3 py-2 fs-6 rounded-pill">
                                <?= $statusTeks ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Bagian Tindakan Selanjutnya -->
                <div class="mt-4 pt-2">
                    <?php if ($bisaRemidi): ?>
                        <!-- Tombol Ikuti Remidi HANYA tampil jika peserta belum lulus (< 70%) -->
                        <div class="alert alert-warning border-0 rounded-3 p-3 mb-4 mx-auto text-start" style="max-width: 600px;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill fs-3 text-warning me-3"></i>
                                <div>
                                    <strong class="d-block text-dark">Kesempatan Perbaikan Nilai</strong>
                                    <span class="small text-muted">Nilai Anda belum mencapai 70%. Klik tombol di bawah ini untuk mengerjakan soal remidi. Nilai awal Anda akan tetap tersimpan aman.</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <a href="<?= base_url('pelatihan/ujian/mulai?id_ujian=' . ($nilaiRow['id_ujian'] ?? 1) . '&remidi=1') ?>"
                               class="btn btn-warning fw-bold text-dark px-5 py-3 rounded-3 shadow"
                               style="font-size: 16px;">
                                <i class="bi bi-arrow-repeat me-2"></i> Ikuti Remidi Sekarang
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success border-0 rounded-3 p-3 mb-4 mx-auto" style="max-width: 600px;">
                            <i class="bi bi-award-fill fs-4 text-success me-2"></i>
                            <strong>Sertifikasi Terbuka:</strong> Anda telah lulus ujian dan dapat mengakses sertifikat pelatihan setelah menyelesaikan angket evaluasi.
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?= base_url('pelatihan/ujian') ?>" class="btn btn-outline-purple px-4 py-2 border rounded-3">
                            <i class="bi bi-journal-text me-1"></i> Kembali ke Menu Ujian
                        </a>

                        <a href="<?= base_url('pelatihan/kbm') ?>" class="btn btn-light px-4 py-2 border rounded-3">
                            <i class="bi bi-mortarboard me-1"></i> Kembali ke KBM
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>