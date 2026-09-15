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
                                <?php if ($item['sudah_ujian']): ?>
                                    <!-- Jika Peserta Sudah Mengerjakan Ujian -->
                                    <small class="text-muted fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.5px;">
                                        Nilai Ujian
                                    </small>

                                    <!-- Nilai Utama dalam Format Persentase -->
                                    <h1 class="fw-extrabold my-2 display-4" style="color: <?= $item['is_lulus'] ? '#15803d' : '#b91c1c' ?>; font-weight: 800;">
                                        <?= (int)$item['nilai_terbaru'] ?>%
                                    </h1>

                                    <!-- Status Kelulusan -->
                                    <div class="my-3">
                                        <?php if ($item['is_lulus']): ?>
                                            <span class="badge bg-success px-4 py-2 fs-6 rounded-pill shadow-sm">
                                                <i class="bi bi-check-circle-fill me-1"></i> LULUS
                                            </span>
                                            <p class="text-success small fw-semibold mt-2 mb-0">
                                                Selamat! Anda telah mencapai batas kelulusan minimal (≥ 70%). Anda tidak perlu mengikuti remidi.
                                            </p>
                                        <?php else: ?>
                                            <span class="badge bg-danger px-4 py-2 fs-6 rounded-pill shadow-sm">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> BELUM LULUS — REMIDI
                                            </span>
                                            <p class="text-danger small fw-semibold mt-2 mb-0">
                                                Nilai Anda masih di bawah standar kelulusan (70%). Anda wajib mengikuti remidi untuk perbaikan nilai.
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Rincian Nilai Jika Ada Remidi -->
                                    <?php if ($item['nilai_remidi'] !== null): ?>
                                        <div class="card border-0 bg-white shadow-sm p-3 mt-3 text-start rounded-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">Nilai Ujian Awal:</small>
                                                <strong class="text-dark"><?= (int)$item['nilai_awal'] ?>%</strong>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="text-muted">Nilai Remidi:</small>
                                                <strong class="text-primary"><?= (int)$item['nilai_remidi'] ?>%</strong>
                                            </div>
                                            <div class="d-flex justify-content-between border-top pt-1 mt-1">
                                                <small class="fw-bold">Status Akhir:</small>
                                                <strong class="<?= $item['is_lulus'] ? 'text-success' : 'text-danger' ?>">
                                                    <?= $item['is_lulus'] ? 'LULUS' : 'BELUM LULUS' ?>
                                                </strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Tombol Aksi -->
                                    <div class="mt-4 d-flex flex-column gap-2">
                                        <!-- Tombol Ikuti Remidi HANYA tampil jika peserta BELUM LULUS (< 70%) -->
                                        <?php if ($item['bisa_remidi']): ?>
                                            <a href="<?= base_url('pelatihan/ujian/mulai?id_ujian=' . $item['id_ujian'] . '&remidi=1') ?>"
                                               class="btn btn-warning fw-bold text-dark py-2 px-4 rounded-3 shadow-sm">
                                                <i class="bi bi-arrow-repeat me-1"></i> Ikuti Remidi
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= base_url('pelatihan/ujian/hasil') ?>"
                                           class="btn btn-outline-secondary btn-sm py-2 rounded-3">
                                            <i class="bi bi-file-earmark-bar-graph me-1"></i> Lihat Rincian Hasil
                                        </a>
                                    </div>

                                <?php else: ?>
                                    <!-- Jika Peserta Belum Mengerjakan Ujian -->
                                    <div class="py-3">
                                        <i class="bi bi-pencil-square text-purple mb-2" style="font-size: 3rem; color: #7c3aed;"></i>
                                        <h5 class="fw-bold text-dark mt-2 mb-1">Siap untuk Ujian?</h5>
                                        <p class="text-muted small mb-4">
                                            Kerjakan ujian secara mandiri untuk mengukur kompetensi Anda.
                                        </p>

                                        <a href="<?= base_url('pelatihan/ujian/mulai?id_ujian=' . $item['id_ujian']) ?>"
                                           class="btn btn-primary py-2 px-4 rounded-3 fw-semibold shadow"
                                           style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); border: none;">
                                            Mulai Ujian Sekarang →
                                        </a>
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