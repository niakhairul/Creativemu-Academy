<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-12">

        <div class="card shadow border-0 rounded-4">

            <div class="card-body p-4">

                <!-- HEADER UJIAN -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h2 class="fw-bold text-primary mb-1">
                            Ujian Akhir Digital Marketing
                        </h2>

                        <p class="text-muted mb-0">
                            Jawablah semua soal dengan benar.
                        </p>
                    </div>

                    <!-- TIMER -->
                    <div class="text-end">
                        <small class="text-muted">
                            Waktu Tersisa
                        </small>

                        <h4 class="fw-bold text-danger" id="timer">
                            30:00
                        </h4>
                    </div>

                </div>

                <hr>

                <!-- INFORMASI UJIAN -->
                <div class="alert alert-info">
                    <strong>Petunjuk:</strong>
                    Pilih satu jawaban yang paling tepat pada setiap soal.
                </div>

                <!-- FORM UJIAN -->
                <form action="<?= base_url('pelatihan/ujian/kumpulkan') ?>" method="post">

                    <?php foreach ($soal as $index => $s): ?>

                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-body">

                                <h5 class="fw-bold mb-3">
                                    Soal <?= $index + 1 ?>
                                </h5>

                                <p class="mb-4">
                                    <?= esc($s['pertanyaan']) ?>
                                </p>

                                <!-- PILIHAN A -->
                                <div class="form-check mb-3">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="jawaban[<?= $s['id'] ?>]"
                                        value="A"
                                        id="soal<?= $s['id'] ?>A"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="soal<?= $s['id'] ?>A"
                                    >
                                        <strong>A.</strong>
                                        <?= esc($s['pilihan_a']) ?>
                                    </label>

                                </div>

                                <!-- PILIHAN B -->
                                <div class="form-check mb-3">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="jawaban[<?= $s['id'] ?>]"
                                        value="B"
                                        id="soal<?= $s['id'] ?>B"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="soal<?= $s['id'] ?>B"
                                    >
                                        <strong>B.</strong>
                                        <?= esc($s['pilihan_b']) ?>
                                    </label>

                                </div>

                                <!-- PILIHAN C -->
                                <div class="form-check mb-3">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="jawaban[<?= $s['id'] ?>]"
                                        value="C"
                                        id="soal<?= $s['id'] ?>C"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="soal<?= $s['id'] ?>C"
                                    >
                                        <strong>C.</strong>
                                        <?= esc($s['pilihan_c']) ?>
                                    </label>

                                </div>

                                <!-- PILIHAN D -->
                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="jawaban[<?= $s['id'] ?>]"
                                        value="D"
                                        id="soal<?= $s['id'] ?>D"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="soal<?= $s['id'] ?>D"
                                    >
                                        <strong>D.</strong>
                                        <?= esc($s['pilihan_d']) ?>
                                    </label>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                    <!-- TOMBOL KUMPULKAN -->
                    <div class="text-end mt-4">

                        <button
                            type="submit"
                            class="btn btn-danger px-4"
                        >
                            Kumpulkan Ujian
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
</div>


<!-- TIMER -->
<script>

let waktu = 30 * 60;

const timer = document.getElementById('timer');

const hitungMundur = setInterval(function () {

    let menit = Math.floor(waktu / 60);

    let detik = waktu % 60;

    if (detik < 10) {
        detik = '0' + detik;
    }

    timer.innerHTML = menit + ':' + detik;

    waktu--;

    // Waktu habis
    if (waktu < 0) {

        clearInterval(hitungMundur);

        alert('Waktu ujian telah habis.');

        // Untuk sementara kembali ke halaman ujian
        window.location.href = "<?= base_url('pelatihan/ujian') ?>";

    }

}, 1000);

</script>


<?= $this->endSection() ?>