<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-3"><?= esc($kelas['nama_kelas'] ?? '-') ?></h3>
<div class="card border-0 shadow-sm mb-4"><div class="card-body">
    <p><strong>Jadwal:</strong> <?= esc($kelas['tanggal_mulai_kelas'] ?? '-') ?></p>
    <p><strong>Ruangan/Media:</strong> <?= esc($kelas['lokasi_media'] ?? '-') ?></p>
    <p><strong>Deskripsi:</strong> <?= esc($kelas['deskripsi'] ?? '-') ?></p>
</div></div>
<div class="card border-0 shadow-sm"><div class="card-body"><h5 class="fw-bold">Data Peserta</h5>
<div class="table-responsive"><table class="table"><thead><tr><th>Nama</th><th>Email</th><th>No HP</th><th>Rekap Absen</th></tr></thead><tbody>
<?php if(empty($peserta)): ?><tr><td colspan="4" class="text-muted text-center">Belum ada peserta tervalidasi.</td></tr><?php endif; ?>
<?php foreach(($peserta ?? []) as $p): ?><tr><td><?= esc($p['nama'] ?? '-') ?></td><td><?= esc($p['email'] ?? '-') ?></td><td><?= esc($p['no_hp'] ?? '-') ?></td><td><span class="badge bg-secondary">Belum direkap</span></td></tr><?php endforeach; ?>
</tbody></table></div></div></div>
<?= $this->endSection() ?>
