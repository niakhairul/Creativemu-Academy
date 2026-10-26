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

                            <!-- Indikator Ketentuan Penilaian -->
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <small class="text-muted d-block fw-medium">Bentuk Penilaian</small>
                                        <strong class="text-purple fs-6" style="color: #6d28d9;">Berkas Nilai Instruktur</strong>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <small class="text-muted d-block fw-medium">Verifikasi Berkas</small>
                                        <strong class="fs-6 text-dark">Validasi Admin CI4</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Status Kelulusan & Berkas Nilai -->
                        <div class="col-lg-5">
                            <div class="p-4 rounded-4 text-center" style="background: #faf5ff; border: 1.5px dashed #c084fc;">
                                <?php 
                                    $st = strtolower($item['status_admin'] ?? 'menunggu');
                                    if (empty($st)) $st = 'menunggu';
                                ?>
                                <div class="mb-3">
                                    <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Status Kelulusan Admin</small>
                                    <?php if ($st === 'lulus'): ?>
                                        <span class="badge bg-success px-3 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                                            <i class="bi bi-check-circle-fill me-1"></i> LULUS / KOMPETEN
                                        </span>
                                    <?php elseif ($st === 'remidi'): ?>
                                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> REMIDI
                                        </span>
                                    <?php elseif ($st === 'tidak lulus'): ?>
                                        <span class="badge bg-danger px-3 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                                            <i class="bi bi-x-circle-fill me-1"></i> TIDAK LULUS
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-15 text-secondary px-3 py-1.5 rounded-pill fw-bold" style="font-size: 12px;">
                                            <i class="bi bi-clock-history me-1"></i> MENUNGGU KEPUTUSAN ADMIN
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <hr class="my-3 opacity-25">
                                <?php if (!empty($grade_file_result['file']) && !empty($grade_file_result['file']['id'])): ?>
                                    <div class="rounded-circle p-3 mx-auto mb-2 text-white shadow-sm" style="background: linear-gradient(135deg, #7c3aed, #4c1d95); width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-file-earmark-check-fill fs-3"></i>
                                    </div>
                                    <span class="badge bg-success px-3 py-1.5 rounded-pill fw-bold mb-2">
                                        <i class="bi bi-patch-check-fill me-1"></i> Berkas Resmi Disetujui
                                    </span>
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 14.5px;">
                                        <?= esc($grade_file_result['file']['file_name'] ?? 'Berkas_Nilai_Kelas.pdf'); ?>
                                    </h5>
                                    <p class="text-muted small mb-3" style="font-size: 11.5px;">
                                        Versi <?= esc($grade_file_result['file']['version'] ?? '1'); ?>
                                        <?php if (!empty($grade_file_result['file']['uploaded_at'])): ?>
                                            • <?= date('d M Y, H.i', strtotime($grade_file_result['file']['uploaded_at'])); ?> WIB
                                        <?php endif; ?>
                                    </p>
                                    <a href="<?= base_url('pelatihan/ujian/unduh-file-nilai/' . $kelas['id_kelas'] . '/' . $grade_file_result['file']['id']); ?>"
                                       class="btn btn-sm text-white px-4 py-2 rounded-pill fw-semibold shadow-sm w-100"
                                       style="background: linear-gradient(135deg, #7c3aed, #4c1d95);">
                                        <i class="bi bi-cloud-arrow-down-fill me-1.5"></i> Unduh File Nilai
                                    </a>
                                    <div class="mt-3">
                                        <a href="<?= base_url('pelatihan/angket?id_kelas=' . $kelas['id_kelas']) ?>" class="btn btn-sm btn-outline-primary px-3 py-1.5 rounded-pill fw-semibold w-100" style="font-size: 12px;">
                                            <i class="fas fa-clipboard-list me-1"></i> Isi Angket Evaluasi
                                        </a>
                                    </div>
                                <?php elseif (($grade_file_result['status_code'] ?? 0) === 403): ?>
                                    <div class="py-3">
                                        <i class="bi bi-shield-exclamation text-warning mb-2" style="font-size: 2.5rem;"></i>
                                        <h6 class="fw-bold text-dark mt-1 mb-1">Akses Berkas Dibatasi (403)</h6>
                                        <p class="text-muted small mb-0">Akun peserta belum terdaftar pada kelas ini di server instruktur.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="py-3">
                                        <i class="bi bi-hourglass-split text-purple mb-2" style="font-size: 2.8rem; color: #7c3aed;"></i>
                                        <h6 class="fw-bold text-dark mt-2 mb-1">Berkas Nilai Belum Tersedia</h6>
                                        <span class="badge px-3 py-1.5 rounded-pill my-1" style="background-color: #fef3c7; color: #92400e; font-size: 11px;">
                                            <i class="bi bi-clock me-1"></i> Menunggu Unggahan & Validasi Admin
                                        </span>
                                        <p class="text-muted small mb-0 mt-1" style="font-size: 11.5px;">
                                            <?= esc($grade_file_result['message'] ?? 'Instruktur belum mengunggah berkas nilai atau berkas masih diverifikasi admin.'); ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
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