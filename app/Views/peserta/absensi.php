<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="fw-bold">
        Absensi
    </h2>

    <p class="text-muted mb-0">
        Lakukan presensi sesuai jadwal yang telah ditentukan.
    </p>
</div>


<?php if (session()->getFlashdata('success')): ?>

<div class="alert alert-success alert-dismissible fade show">
    <strong>✅ Berhasil!</strong>
    <?= session()->getFlashdata('success') ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>
</div>

<?php endif; ?>


<?php if (session()->getFlashdata('error')): ?>

<div class="alert alert-danger alert-dismissible fade show">
    <strong>❌ Gagal!</strong>
    <?= session()->getFlashdata('error') ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>
</div>

<?php endif; ?>


<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">
            📋 Jadwal Absensi
        </h5>


        <?php if (!empty($jadwal)): ?>

            <?php foreach ($jadwal as $j): ?>

                <?php
                    $waktuMulai = strtotime($j['tanggal_kbm']);
                    $waktuSelesai = strtotime(
                        date('Y-m-d', $waktuMulai) . ' ' . $j['jam_selesai']
                    );

                    $sekarang = time();

                    $absenDibuka =
                        $sekarang >= $waktuMulai &&
                        $sekarang <= $waktuSelesai;
                ?>


                <div class="border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6 class="fw-bold mb-1">
                                Pertemuan <?= esc($j['pertemuan_ke']) ?>
                            </h6>

                            <p class="text-muted mb-1">
                                <?= esc($j['materi']) ?>
                            </p>

                            <small class="text-muted">
                                📅 <?= date('d-m-Y H:i', $waktuMulai) ?>
                                -
                                <?= date('H:i', $waktuSelesai) ?>
                            </small>

                        </div>


                        <div>

                            <?php if (!empty($j['absensi'])): ?>

    <button class="btn btn-success" disabled>
        ✅ Sudah Absen
    </button>

<?php elseif ($absenDibuka): ?>

    <form action="<?= base_url('pelatihan/absensi/simpan') ?>"
          method="post">

        <?= csrf_field() ?>

        <input type="hidden"
               name="id_jadwal_kelas"
               value="<?= esc($j['id_jadwal_kelas']) ?>">

        <button type="submit"
                class="btn btn-success">

            ✅ Absen Sekarang

        </button>

    </form>

<?php elseif ($sekarang < $waktuMulai): ?>

    <button class="btn btn-secondary" disabled>
        ⏳ Belum Dibuka
    </button>

<?php else: ?>

    <button class="btn btn-danger" disabled>
        ❌ Sudah Ditutup
    </button>

<?php endif; ?>
                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="text-center py-5">

                <div style="font-size: 50px;">
                    📅
                </div>

                <h6 class="fw-bold mt-3">
                    Belum ada jadwal absensi
                </h6>

                <p class="text-muted">
                    Jadwal absensi belum ditentukan.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>