<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="fw-bold">
        Riwayat Absensi
    </h2>

    <p class="text-muted mb-0">
        Lihat riwayat kehadiran Anda pada setiap pertemuan.
    </p>
</div>

<div class="card border-0 shadow-sm">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">
            📋 Riwayat Kehadiran
        </h5>

        <!-- Rekap Kehadiran -->
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="border rounded p-3 text-center">
            <div class="text-muted small">Total Pertemuan</div>
            <h3 class="fw-bold mb-0">
                <?= esc($totalPertemuan) ?>
            </h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="border rounded p-3 text-center">
            <div class="text-muted small">Hadir</div>
            <h3 class="fw-bold text-success mb-0">
                <?= esc($jumlahHadir) ?>
            </h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="border rounded p-3 text-center">
            <div class="text-muted small">Izin</div>
            <h3 class="fw-bold text-warning mb-0">
                <?= esc($jumlahIzin) ?>
            </h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="border rounded p-3 text-center">
            <div class="text-muted small">Alpa</div>
            <h3 class="fw-bold text-danger mb-0">
                <?= esc($jumlahAlpa) ?>
            </h3>
        </div>
    </div>

</div>

<!-- Persentase Kehadiran -->
<div class="border rounded p-3 mb-4">

    <div class="d-flex justify-content-between mb-2">
        <span class="fw-bold">
            Persentase Kehadiran
        </span>

        <span class="fw-bold text-success">
            <?= esc($persentaseKehadiran) ?>%
        </span>
    </div>

    <div class="progress" style="height: 10px;">
        <div
            class="progress-bar bg-success"
            role="progressbar"
            style="width: <?= esc($persentaseKehadiran) ?>%;"
        ></div>
    </div>

</div>

        <?php if (!empty($jadwal)): ?>

            <?php foreach ($jadwal as $j): ?>

                <?php
                    $waktuMulai = strtotime($j['tanggal_kbm']);
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
                            </small>

                            <?php if (!empty($j['absensi'])): ?>

                                <div class="mt-2">
                                    <small class="text-muted">
                                        🕐 Absen:
                                        <?= date(
                                            'd-m-Y H:i',
                                            strtotime($j['absensi']['waktu_absen'])
                                        ) ?>
                                    </small>
                                </div>

                            <?php endif; ?>

                        </div>

                        <div>

                            <?php if (!empty($j['absensi'])): ?>

                                <?php if ($j['absensi']['status'] === 'hadir'): ?>

                                    <span class="badge bg-success px-3 py-2">
                                        ✅ Hadir
                                    </span>

                                <?php elseif ($j['absensi']['status'] === 'izin'): ?>

                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        🟡 Izin
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-danger px-3 py-2">
                                        ❌ Alpa
                                    </span>

                                <?php endif; ?>

                            <?php else: ?>

                                <span class="badge bg-secondary px-3 py-2">
                                    ⚪ Belum Absen
                                </span>

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
                    Belum ada riwayat absensi
                </h6>

                <p class="text-muted">
                    Belum terdapat jadwal pertemuan.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

<?= $this->endSection() ?>