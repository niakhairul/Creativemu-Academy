<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <span class="badge bg-purple-soft text-purple mb-2 px-3 py-2 fw-bold rounded-pill" style="background-color: #ede9fe; color: #6d28d9;">
                <i class="bi bi-mortarboard-fill me-1"></i> Hak Akses Peserta → KBM → Ujian
            </span>
            <h2 class="fw-bold mb-1" style="color: #3b0764;">
                Ujian Kompetensi Kelas
            </h2>
            <p class="text-muted mb-0">
                Kelas Aktif: <strong class="text-dark"><?= esc($kelas['nama_kelas'] ?? 'CreativeMU Academy') ?></strong>
            </p>
        </div>

        <div>
            <a href="<?= base_url('pelatihan/kbm') ?>" class="btn btn-light border py-2 px-3 fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke KBM
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill fs-3 text-success me-3"></i>
            <div>
                <strong class="d-block mb-1">Berhasil!</strong>
                <span><?= esc(session()->getFlashdata('success')) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-3 text-danger me-3"></i>
            <div>
                <strong class="d-block mb-1">Perhatian!</strong>
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- List Ujian -->
    <?php if (!empty($ujian)): ?>
        <?php foreach ($ujian as $item): ?>
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="border: 1px solid #ddd6fe !important;">
                <div class="card-body p-4 p-md-5">

                    <div class="row align-items-center g-4">
                        <!-- Kolom Informasi Ujian -->
                        <div class="col-lg-7">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary px-3 py-2 rounded-pill fw-semibold" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;">
                                    Ujian Akhir
                                </span>
                                <?php if (!empty($item['deadline'])): ?>
                                    <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock me-1"></i> Batas: <?= date('d M Y H:i', strtotime($item['deadline'])) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <h3 class="fw-bold mb-2" style="color: #2e1065;">
                                <?= esc($item['judul_ujian'] ?? 'Ujian Akhir Pelatihan') ?>
                            </h3>

                            <p class="text-muted mb-4">
                                <?= esc($item['keterangan'] ?? 'Ujian akhir untuk mengukur pemahaman peserta terhadap materi pelatihan yang telah dipelajari.') ?>
                            </p>

                            <!-- Indikator Ketentuan Ujian -->
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <small class="text-muted d-block fw-medium">Standar Kelulusan</small>
                                        <strong class="text-primary fs-5">70%</strong>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <small class="text-muted d-block fw-medium">Durasi Pengerjaan</small>
                                        <strong class="fs-5 text-dark">30 Menit</strong>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <small class="text-muted d-block fw-medium">Tipe Soal</small>
                                        <strong class="fs-5 text-dark">Pilihan Ganda</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Status & Hasil Nilai -->
                        <div class="col-lg-5">
                            <div class="p-4 rounded-4 text-center" style="background: #faf5ff; border: 1.5px dashed #c084fc;">
                                <?php if (!empty($item['has_score']) && $item['nilai_terbaru'] !== null && ($item['status_admin'] !== 'menunggu' || (float)$item['nilai_terbaru'] > 0)): ?>
                                    <!-- Jika Nilai Ujian Sudah Ada -->
                                    <small class="text-muted fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.5px;">
                                        Nilai Akhir Ujian
                                    </small>

                                    <!-- Nilai Utama dalam Format Persentase -->
                                    <h1 class="fw-extrabold my-2 display-4" style="color: <?= ($item['status_admin'] === 'lulus') ? '#15803d' : (($item['status_admin'] === 'tidak lulus') ? '#b91c1c' : (($item['status_admin'] === 'remidi') ? '#d97706' : '#7c3aed')); ?>; font-weight: 800;">
                                        <?= (int)$item['nilai_terbaru']; ?>%
                                    </h1>

                                    <!-- Status Kelulusan Ditentukan Admin -->
                                    <div class="my-3">
                                        <?php if ($item['status_admin'] === 'lulus'): ?>
                                            <span class="badge bg-success px-4 py-2 fs-6 rounded-pill shadow-sm">
                                                <i class="bi bi-check-circle-fill me-1"></i> LULUS
                                            </span>
                                            <p class="text-success small fw-semibold mt-2 mb-0">
                                                <?= esc($item['keterangan_status']); ?>
                                            </p>
                                        <?php elseif ($item['status_admin'] === 'tidak lulus'): ?>
                                            <span class="badge bg-danger px-4 py-2 fs-6 rounded-pill shadow-sm">
                                                <i class="bi bi-x-circle-fill me-1"></i> TIDAK LULUS
                                            </span>
                                            <p class="text-danger small fw-semibold mt-2 mb-0">
                                                <?= esc($item['keterangan_status']); ?>
                                            </p>
                                        <?php elseif ($item['status_admin'] === 'remidi'): ?>
                                            <span class="badge bg-warning text-dark px-4 py-2 fs-6 rounded-pill shadow-sm">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> REMIDI
                                            </span>
                                            <p class="text-warning-emphasis small fw-semibold mt-2 mb-0">
                                                <?= esc($item['keterangan_status']); ?>
                                            </p>

                                            <!-- Rincian Nilai Remidi & Aksi -->
                                            <div class="mt-3 p-3 bg-white rounded-3 border text-start shadow-sm" style="border-color: #fde68a !important; background-color: #fffbeb !important;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="small text-muted fw-semibold">Nilai Ujian Saat Ini:</span>
                                                    <span class="fw-bold fs-6" style="color: #d97706;"><?= (int)($item['nilai_terbaru'] ?? 0); ?>%</span>
                                                </div>
                                                <?php if (!empty($item['nilai_awal']) && (float)$item['nilai_awal'] != (float)$item['nilai_terbaru']): ?>
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="small text-muted">Nilai Ujian Awal:</span>
                                                        <span class="fw-semibold text-muted small"><?= (int)$item['nilai_awal']; ?>%</span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (!empty($item['nilai_remidi'])): ?>
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="small text-muted">Nilai Hasil Remidi:</span>
                                                        <span class="fw-bold text-primary small"><?= (int)$item['nilai_remidi']; ?>%</span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex justify-content-between align-items-center border-top pt-1 mt-1">
                                                    <span class="small text-muted">Batas Minimum Kelulusan:</span>
                                                    <span class="small fw-bold text-dark">70%</span>
                                                </div>
                                            </div>

                                            <!-- Keterangan Remidi -->
                                            <div class="mt-3">
                                                <div class="alert alert-warning py-2 px-3 small rounded-3 mb-0 border-0 shadow-sm text-start" style="background-color: #fffbeb; color: #92400e;">
                                                    <i class="fas fa-info-circle me-1 text-warning"></i> 
                                                    <strong>Status Remidi:</strong> Silakan koordinasikan jadwal pelaksanaan ujian remidi perbaikan dengan instruktur atau admin kelas.
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge px-4 py-2 fs-6 rounded-pill shadow-sm" style="background-color: #fef3c7; color: #92400e;">
                                                <i class="bi bi-hourglass-split me-1"></i> MENUNGGU KONFIRMASI ADMIN
                                            </span>
                                            <p class="text-muted small fw-semibold mt-2 mb-0">
                                                <?= esc($item['keterangan_status'] ?? 'Hasil masih menunggu konfirmasi admin.'); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($item['status_admin'] === 'lulus' || !empty($item['is_lulus'])): ?>
                                        <div class="mb-3">
                                            <a href="<?= base_url('pelatihan/angket?id_kelas=' . $kelas['id_kelas']) ?>" class="btn btn-sm text-white fw-bold px-4 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                                                <i class="fas fa-clipboard-list me-1"></i> Isi Angket Evaluasi Sekarang
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-3 p-2 bg-white rounded-3 border small text-muted">
                                        <i class="bi bi-info-circle me-1" style="color: #7c3aed;"></i>
                                        Nilai dan status kelulusan ditentukan langsung oleh Instruktur dan Admin.
                                    </div>

                                <?php else: ?>
                                    <!-- Jika Nilai Belum Masuk atau Belum Ditentukan -->
                                    <div class="py-3">
                                        <i class="bi bi-hourglass-split text-purple mb-2" style="font-size: 3rem; color: #7c3aed;"></i>
                                        <h5 class="fw-bold text-dark mt-2 mb-1">Hasil Belum Tersedia</h5>
                                        <span class="badge px-3 py-2 rounded-pill my-2" style="background-color: #fef3c7; color: #92400e;">
                                            <i class="bi bi-clock me-1"></i> Menunggu Konfirmasi Admin
                                        </span>
                                        <p class="text-muted small mb-0 mt-1">
                                            <?= esc($item['keterangan_status'] ?? 'Hasil masih menunggu konfirmasi admin.'); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <!-- Berkas Nilai Instruktur dari Laravel -->
                                <div class="mt-3 pt-3 border-top text-start">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="small fw-bold text-dark">
                                            <i class="bi bi-file-earmark-text text-purple me-1" style="color: #7c3aed;"></i> Berkas Nilai Instruktur
                                        </span>
                                        <?php if (!empty($grade_file_result['file'])): ?>
                                            <span class="badge bg-success-subtle text-success border px-2 py-1" style="font-size: 0.72rem;">
                                                <i class="bi bi-check-circle me-1"></i> Disetujui
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($grade_file_result['file']) && !empty($grade_file_result['file']['id'])): ?>
                                        <div class="p-2 bg-white rounded-3 border d-flex align-items-center justify-content-between">
                                            <div class="text-truncate me-2">
                                                <div class="small fw-semibold text-dark text-truncate">
                                                    <?= esc($grade_file_result['file']['file_name'] ?? 'Berkas Nilai Kelas'); ?>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.72rem;">
                                                    Versi <?= esc($grade_file_result['file']['version'] ?? '1'); ?>
                                                </small>
                                            </div>
                                            <a href="<?= base_url('pelatihan/ujian/unduh-file-nilai/' . $kelas['id_kelas'] . '/' . $grade_file_result['file']['id']); ?>"
                                               class="btn btn-sm btn-purple text-white text-nowrap"
                                               style="background-color: #7c3aed;">
                                                <i class="bi bi-download me-1"></i> Unduh
                                            </a>
                                        </div>
                                    <?php elseif (($grade_file_result['status_code'] ?? 0) === 403): ?>
                                        <div class="p-2 rounded-3 border bg-white small text-muted" style="font-size: 0.78rem;">
                                            <i class="bi bi-shield-lock me-1 text-warning"></i>
                                            <span>Berkas nilai belum dapat diakses: Menunggu verifikasi identitas siswa (Status 403) di server pusat.</span>
                                        </div>
                                    <?php elseif (($grade_file_result['status_code'] ?? 0) === 404): ?>
                                        <div class="p-2 rounded-3 border bg-white small text-muted" style="font-size: 0.78rem;">
                                            <i class="bi bi-exclamation-circle me-1 text-secondary"></i>
                                            <span>Berkas nilai tidak ditemukan di server pusat.</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-2 rounded-3 border bg-white small text-muted" style="font-size: 0.78rem;">
                                            <i class="bi bi-clock-history me-1 text-secondary"></i>
                                            <span><?= esc($grade_file_result['message'] ?? 'Berkas nilai belum diunggah instruktur atau belum disetujui admin.'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="bi bi-file-earmark-x text-muted" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold mt-3 mb-1">Belum Ada Ujian Tersedia</h5>
            <p class="text-muted mb-0">Ujian untuk kelas Anda saat ini belum dibuka oleh mentor.</p>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>