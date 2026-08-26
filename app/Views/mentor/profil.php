<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-4">Profil Mentor</h3>
<div class="card border-0 shadow-sm"><div class="card-body">
<p><strong>Nama:</strong> <?= esc($mentor['nama_mentor'] ?? $user['nama'] ?? '-') ?></p>
<p><strong>Email:</strong> <?= esc($mentor['email'] ?? $user['email'] ?? '-') ?></p>
<p><strong>Telepon:</strong> <?= esc($mentor['telepon'] ?? $user['no_hp'] ?? '-') ?></p>
<p><strong>Keahlian:</strong> <?= esc($mentor['keahlian'] ?? '-') ?></p>
<p><strong>Bio:</strong> <?= esc($mentor['bio'] ?? '-') ?></p>
</div></div>
<?= $this->endSection() ?>
