<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-4">Daftar Kelas Diampu</h3>
<div class="row g-3">
<?php if(empty($kelas)): ?><div class="col-12"><div class="alert alert-info">Belum ada kelas yang diampu.</div></div><?php endif; ?>
<?php foreach(($kelas ?? []) as $item): ?>
    <div class="col-md-6"><div class="card border-0 shadow-sm h-100"><div class="card-body">
        <h5 class="fw-bold"><?= esc($item['nama_kelas']) ?></h5>
        <p class="text-muted"><?= esc($item['ringkasan'] ?? $item['deskripsi'] ?? '-') ?></p>
        <a href="<?= base_url('mentor/kelas/' . $item['id_kelas']) ?>" class="btn btn-primary btn-sm">Detail Kelas</a>
        <a href="<?= base_url('mentor/kelas/' . $item['id_kelas'] . '/kbm') ?>" class="btn btn-outline-primary btn-sm">KBM</a>
    </div></div></div>
<?php endforeach; ?>
</div>
<?= $this->endSection() ?>
