<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="card shadow border-0 rounded-4" style="border: 1px solid #ddd6fe !important;">
            <div class="card-body p-4 p-md-5">

                <!-- Header Ujian -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <?php if (!empty($isRemidi)): ?>
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                                    <i class="bi bi-arrow-repeat me-1"></i> Mode Remidi
                                </span>
                            <?php else: ?>
                                <span class="badge bg-primary px-3 py-2 rounded-pill fw-semibold" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;">
                                    Ujian Utama
                                </span>
                            <?php endif; ?>

                            <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">
                                Kelas: <?= esc($kelas['nama_kelas'] ?? 'CreativeMU Academy') ?>
                            </span>
                        </div>

                        <h2 class="fw-bold mb-1" style="color: #3b0764;">
                            <?= esc($judulUjian ?? 'Ujian Akhir Pelatihan') ?>
                            <?= !empty($isRemidi) ? ' (Remidial)' : '' ?>
                        </h2>
                        <p class="text-muted mb-0">
                            Pilihlah salah satu jawaban yang paling tepat pada setiap pertanyaan di bawah ini.
                        </p>
                    </div>

                    <!-- Timer Countdown -->
                    <div class="text-end bg-light p-3 rounded-3 border">
                        <small class="text-muted d-block fw-medium">Waktu Tersisa</small>
                        <h3 class="fw-bold text-danger mb-0" id="timer">
                            30:00
                        </h3>
                    </div>
                </div>

                <hr class="mb-4 opacity-25">

                <!-- Informasi Petunjuk -->
                <div class="alert alert-light border rounded-3 p-3 mb-4 d-flex align-items-center">
                    <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                    <div>
                        <strong>Petunjuk:</strong> Nilai minimal kelulusan adalah <strong>70%</strong>. Kerjakan dengan teliti dan tekan tombol <strong>"Kumpulkan Ujian"</strong> di bagian bawah setelah selesai.
                    </div>
                </div>

                <!-- Form Ujian -->
                <form id="ujianForm" action="<?= base_url('pelatihan/ujian/kumpulkan') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_ujian" value="<?= esc($idUjian ?? 1) ?>">
                    <input type="hidden" name="is_remidi" value="<?= esc($isRemidi ?? 0) ?>">

                    <?php foreach ($soal as $index => $s): ?>
                        <div class="card border-0 shadow-sm mb-4 rounded-3 p-4" style="background: #faf5ff; border: 1px solid #e9d5ff !important;">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-purple text-white rounded-pill px-3 py-1" style="background: #7c3aed;">
                                    Soal <?= $index + 1 ?> dari <?= count($soal) ?>
                                </span>
                            </div>

                            <h5 class="fw-bold mb-4" style="color: #1e1b4b; line-height: 1.5;">
                                <?= esc($s['pertanyaan']) ?>
                            </h5>

                            <!-- Opsi A -->
                            <div class="form-check mb-3 p-3 rounded-3 bg-white border">
                                <input class="form-check-input ms-0 me-3"
                                       type="radio"
                                       name="jawaban[<?= $s['id'] ?>]"
                                       value="A"
                                       id="soal<?= $s['id'] ?>A"
                                       required>
                                <label class="form-check-label w-100" for="soal<?= $s['id'] ?>A" style="cursor: pointer;">
                                    <strong>A.</strong> <?= esc($s['pilihan_a']) ?>
                                </label>
                            </div>

                            <!-- Opsi B -->
                            <div class="form-check mb-3 p-3 rounded-3 bg-white border">
                                <input class="form-check-input ms-0 me-3"
                                       type="radio"
                                       name="jawaban[<?= $s['id'] ?>]"
                                       value="B"
                                       id="soal<?= $s['id'] ?>B">
                                <label class="form-check-label w-100" for="soal<?= $s['id'] ?>B" style="cursor: pointer;">
                                    <strong>B.</strong> <?= esc($s['pilihan_b']) ?>
                                </label>
                            </div>

                            <!-- Opsi C -->
                            <div class="form-check mb-3 p-3 rounded-3 bg-white border">
                                <input class="form-check-input ms-0 me-3"
                                       type="radio"
                                       name="jawaban[<?= $s['id'] ?>]"
                                       value="C"
                                       id="soal<?= $s['id'] ?>C">
                                <label class="form-check-label w-100" for="soal<?= $s['id'] ?>C" style="cursor: pointer;">
                                    <strong>C.</strong> <?= esc($s['pilihan_c']) ?>
                                </label>
                            </div>

                            <!-- Opsi D -->
                            <div class="form-check p-3 rounded-3 bg-white border">
                                <input class="form-check-input ms-0 me-3"
                                       type="radio"
                                       name="jawaban[<?= $s['id'] ?>]"
                                       value="D"
                                       id="soal<?= $s['id'] ?>D">
                                <label class="form-check-label w-100" for="soal<?= $s['id'] ?>D" style="cursor: pointer;">
                                    <strong>D.</strong> <?= esc($s['pilihan_d']) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Tombol Aksi Submit -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="<?= base_url('pelatihan/ujian') ?>" class="btn btn-outline-secondary px-4 py-2" onclick="return confirm('Apakah Anda yakin ingin membatalkan ujian? Progres Anda tidak akan tersimpan.')">
                            <i class="bi bi-x-circle me-1"></i> Batalkan
                        </a>

                        <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold shadow" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); border: none;">
                            <i class="bi bi-check2-circle me-1"></i> Kumpulkan Ujian
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<!-- Countdown Timer Script -->
<script>
    let waktu = 30 * 60; // 30 Menit
    const timerElem = document.getElementById('timer');

    const countdown = setInterval(function () {
        let menit = Math.floor(waktu / 60);
        let detik = waktu % 60;

        if (detik < 10) {
            detik = '0' + detik;
        }

        if (timerElem) {
            timerElem.innerHTML = menit + ':' + detik;
        }

        waktu--;

        if (waktu < 0) {
            clearInterval(countdown);
            alert('Waktu pengerjaan ujian telah habis! Sistem akan mengumpulkan jawaban Anda otomatis.');
            document.getElementById('ujianForm').submit();
        }
    }, 1000);
</script>

<?= $this->endSection() ?>