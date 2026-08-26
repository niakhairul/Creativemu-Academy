<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-4">Dashboard Mentor</h3>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Total Kelas</div><h2><?= esc($total_kelas) ?></h2></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Total Peserta</div><h2><?= esc($total_peserta) ?></h2></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Mentor</div><h5><?= esc($mentor['nama_mentor'] ?? session()->get('nama')) ?></h5></div></div></div>
</div>
<div class="card border-0 shadow-sm"><div class="card-body"><h5 class="fw-bold">Jadwal Harian</h5>
<?php if(empty($jadwal)): ?><p class="text-muted mb-0">Belum ada jadwal.</p><?php endif; ?>
<?php foreach(($jadwal ?? []) as $j): ?><div class="border-top py-2">Pertemuan <?= esc($j['pertemuan_ke'] ?? '-') ?> - <?= esc($j['tanggal_kbm'] ?? '-') ?> <span class="text-muted"><?= esc($j['materi'] ?? '-') ?></span></div><?php endforeach; ?>
</div></div>
<?= $this->endSection() ?>
