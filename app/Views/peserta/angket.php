<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<?php
$groupedPertanyaan = [];
if (!empty($pertanyaan)) {
    foreach ($pertanyaan as $q) {
        $groupedPertanyaan[$q['kategori']][] = $q;
    }
}
?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Terima kasih!</strong>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>❌ Gagal!</strong>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold">Angket Evaluasi Pelatihan</h2>
        <p class="text-muted">Silakan isi angket sebagai evaluasi terhadap pelatihan yang telah Anda ikuti.</p>
    </div>

    <!-- Informasi -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        Jawablah setiap pertanyaan sesuai dengan pengalaman Anda selama mengikuti pelatihan.
    </div>

    <?php if ($sudahIsi): ?>
        <!-- Jika Sudah Mengisi -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 70px;"></i>
                </div>
                <h4 class="fw-bold text-success">Angket Sudah Diisi</h4>
                <p class="text-muted mb-0">Terima kasih, Anda sudah mengisi angket evaluasi pelatihan.</p>
            </div>
        </div>
    <?php else: ?>
        <!-- Form Angket -->
        <form action="<?= base_url('pelatihan/angket/simpan') ?>" method="post" id="formAngket">
            <?= csrf_field(); ?>
            <input type="hidden" name="kelas_id" value="<?= esc($pendaftaran['id_kelas'] ?? '') ?>">

            <?php $no = 1; ?>
            <?php foreach ($groupedPertanyaan as $kategori => $items): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-primary text-white rounded-top-4">
                        <h5 class="mb-0 fw-bold"><?= esc($kategori) ?></h5>
                    </div>
                    <div class="card-body p-4">
                        <?php foreach ($items as $q): ?>
                            <?php
                            $idPertanyaan = $q['id_angket_pertanyaan'];
                            $tipe = $q['tipe'];
                            $opsi = json_decode($q['opsi_jawaban'], true) ?? [];
                            $isMahasiswaCheck = (strpos($q['pertanyaan'], 'Semester') !== false);
                            ?>
                            <div class="mb-4 pertanyaan-item" <?= $isMahasiswaCheck ? 'id="pertanyaan-semester" style="display:none;"' : '' ?>>
                                <label class="fw-bold mb-2">
                                    <?= $no++ ?>. <?= esc($q['pertanyaan']) ?>
                                    <span class="text-danger">*</span>
                                </label>

                                <?php if ($tipe === 'pilihan'): ?>
                                    <?php
                                    // Check marker kelas database
                                    if (!empty($opsi) && $opsi[0] === '__KELAS_DATABASE__') {
                                        echo '<select name="jawaban['.$idPertanyaan.']" class="form-select" required>';
                                        echo '<option value="">-- Pilih Kelas --</option>';
                                        if (!empty($semuaKelas)) {
                                            foreach ($semuaKelas as $kls) {
                                                echo '<option value="'.esc($kls['nama_kelas']).'">'.esc($kls['nama_kelas']).'</option>';
                                            }
                                        }
                                        echo '</select>';
                                    } else {
                                        foreach ($opsi as $op):
                                            $isStatusInput = (strpos(strtolower($q['pertanyaan']), 'status') !== false || strpos(strtolower($q['pertanyaan']), 'kesibukan') !== false);
                                    ?>
                                        <div class="form-check">
                                            <input class="form-check-input <?= $isStatusInput ? 'status-kesibukan' : '' ?>"
                                                   type="radio"
                                                   name="jawaban[<?= $idPertanyaan ?>]"
                                                   value="<?= esc($op) ?>"
                                                   <?= $isMahasiswaCheck ? '' : 'required' ?>>
                                            <label class="form-check-label"><?= esc($op) ?></label>
                                        </div>
                                    <?php
                                        endforeach;
                                    }
                                    ?>

                                <?php elseif ($tipe === 'rating'): ?>
                                    <div class="d-flex gap-3">
                                        <?php
                                        $skalaLabel = [
                                            1 => 'Kurang Puas',
                                            2 => 'Cukup Puas',
                                            3 => 'Puas',
                                            4 => 'Sangat Puas'
                                        ];
                                        for ($i = 1; $i <= 4; $i++):
                                        ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jawaban[<?= $idPertanyaan ?>]" value="<?= $i ?>" required>
                                                <label class="form-check-label"><?= $i ?> - <?= $skalaLabel[$i] ?></label>
                                            </div>
                                        <?php endfor; ?>
                                    </div>

                                <?php elseif ($tipe === 'essay'): ?>
                                    <textarea name="jawaban[<?= $idPertanyaan ?>]" class="form-control" rows="3" placeholder="Jawaban Anda..." required></textarea>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-primary px-4 btn-lg rounded-pill shadow-sm">
                    <i class="bi bi-send"></i> Kirim Angket
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusInputs = document.querySelectorAll('.status-kesibukan');
    const semesterQuestion = document.getElementById('pertanyaan-semester');

    if (statusInputs.length > 0 && semesterQuestion) {
        statusInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                if (this.value === 'Mahasiswa Aktif') {
                    semesterQuestion.style.display = 'block';
                    const semInputs = semesterQuestion.querySelectorAll('input, select, textarea');
                    semInputs.forEach(el => el.setAttribute('required', 'required'));
                } else {
                    semesterQuestion.style.display = 'none';
                    const semInputs = semesterQuestion.querySelectorAll('input, select, textarea');
                    semInputs.forEach(el => el.removeAttribute('required'));
                }
            });
        });
    }
});
</script>

<?= $this->endSection() ?>
