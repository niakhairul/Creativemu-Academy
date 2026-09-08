<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<?php
$mentor = $mentor ?? [];
$user = $user ?? [];
$nama = $mentor['nama_mentor'] ?? $user['nama'] ?? 'Mentor';
$email = $mentor['email'] ?? $user['email'] ?? '-';
$telepon = $mentor['telepon'] ?? $user['no_hp'] ?? '-';
$inisial = strtoupper(substr(trim($nama), 0, 1));
$status = $mentor['status'] ?? 'Aktif';
?>
<style>
	.profil-hero{background:linear-gradient(125deg,#24123f,#6135a5);border-radius:22px;color:#fff;overflow:hidden;position:relative}.profil-hero:after{content:'';position:absolute;width:280px;height:280px;border:42px solid rgba(255,255,255,.08);border-radius:50%;right:-75px;top:-135px}.profil-hero>*{position:relative;z-index:1}.profil-avatar{align-items:center;background:rgba(255,255,255,.18);border:3px solid rgba(255,255,255,.4);border-radius:22px;display:flex;font-size:2.4rem;font-weight:700;height:92px;justify-content:center;width:92px}.profil-panel{border:1px solid #ece6f4;border-radius:18px;box-shadow:0 9px 25px rgba(37,19,64,.05)}.profil-label{color:#8a819a;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.profil-info{align-items:center;border-bottom:1px solid #f0ecf4;display:flex;gap:14px;padding:15px 0}.profil-info:last-child{border-bottom:0;padding-bottom:0}.profil-info:first-child{padding-top:0}.profil-icon{align-items:center;background:#f1eafb;border-radius:11px;color:#7042b4;display:flex;height:40px;justify-content:center;width:40px}.keahlian-chip{background:#f4f0fc;border:1px solid #e6dcf3;border-radius:10px;color:#6636a7;display:inline-block;font-size:.88rem;font-weight:600;padding:.55rem .75rem}.bio-box{background:#fbf9fd;border-left:4px solid #794bc4;border-radius:0 12px 12px 0;color:#5d5367;line-height:1.8;padding:1rem 1.1rem}.stat-mini{background:#fbf9fd;border-radius:14px;padding:1rem}.cv-link{align-items:center;background:#f9f6fd;border:1px solid #e6dcf3;border-radius:12px;color:#6636a7;display:flex;gap:10px;padding:.85rem;text-decoration:none}.cv-link:hover{background:#f0e8fb;color:#542991}
</style>

<div class="d-flex justify-content-between align-items-center mb-4 gap-3"><div><h3 class="fw-bold mb-1">Profil Mentor</h3><p class="text-muted mb-0">Informasi pribadi dan kompetensi pengajar Anda.</p></div><span class="badge rounded-pill text-bg-success px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i><?= esc($status) ?></span></div>

<section class="profil-hero p-4 p-lg-5 mb-4"><div class="d-flex flex-column flex-md-row align-items-md-center gap-4"><div class="profil-avatar"><?= esc($inisial) ?></div><div><span class="badge rounded-pill text-bg-light text-primary mb-2">MENTOR CREATIVEMU</span><h2 class="fw-bold mb-1"><?= esc($nama) ?></h2><p class="mb-0 opacity-75"><i class="fa-solid fa-graduation-cap me-2"></i><?= esc($mentor['keahlian'] ?? 'Instruktur Pelatihan') ?></p></div></div></section>

<div class="row g-4">
	<div class="col-lg-7">
		<div class="profil-panel bg-white p-4 mb-4"><div class="d-flex align-items-center gap-3 mb-4"><div class="profil-icon"><i class="fa-solid fa-id-card"></i></div><div><h5 class="fw-bold mb-1">Informasi Kontak</h5><p class="text-muted small mb-0">Data utama yang terhubung dengan akun Anda.</p></div></div><div class="profil-info"><div class="profil-icon"><i class="fa-solid fa-user"></i></div><div><div class="profil-label">Nama lengkap</div><div class="fw-semibold"><?= esc($nama) ?></div></div></div><div class="profil-info"><div class="profil-icon"><i class="fa-solid fa-envelope"></i></div><div><div class="profil-label">Email</div><div class="fw-semibold text-break"><?= esc($email) ?></div></div></div><div class="profil-info"><div class="profil-icon"><i class="fa-solid fa-phone"></i></div><div><div class="profil-label">Nomor telepon</div><div class="fw-semibold"><?= esc($telepon) ?></div></div></div></div>

		<div class="profil-panel bg-white p-4"><div class="d-flex align-items-center gap-3 mb-3"><div class="profil-icon"><i class="fa-solid fa-quote-left"></i></div><div><h5 class="fw-bold mb-1">Tentang Saya</h5><p class="text-muted small mb-0">Profil singkat dan pengalaman mengajar.</p></div></div><div class="bio-box"><?= nl2br(esc($mentor['bio'] ?? 'Bio mentor belum ditambahkan.')) ?></div></div>
	</div>
	<div class="col-lg-5">
		<div class="profil-panel bg-white p-4 mb-4"><h5 class="fw-bold mb-3">Ringkasan Kompetensi</h5><div class="stat-mini mb-3"><div class="profil-label mb-1">Keahlian utama</div><div class="keahlian-chip"><i class="fa-solid fa-star me-1"></i><?= esc($mentor['keahlian'] ?? 'Belum diisi') ?></div></div><div class="stat-mini"><div class="profil-label mb-1">Pengalaman kerja</div><div class="fs-4 fw-bold" style="color:#7042b4"><?= esc($mentor['pengalaman'] ?? '0') ?> <span class="fs-6 fw-normal text-muted">tahun</span></div></div></div>
		<div class="profil-panel bg-white p-4"><div class="d-flex justify-content-between align-items-center mb-3"><h5 class="fw-bold mb-0">Dokumen Pendukung</h5><i class="fa-solid fa-folder-open" style="color:#794bc4"></i></div><?php if (!empty($mentor['cv'])): ?><a class="cv-link" href="<?= base_url('uploads/cv/' . rawurlencode($mentor['cv'])) ?>" target="_blank"><i class="fa-solid fa-file-pdf fs-4 text-danger"></i><span><strong class="d-block">Curriculum Vitae</strong><small class="text-muted">Buka dokumen CV mentor</small></span><i class="fa-solid fa-arrow-up-right-from-square ms-auto"></i></a><?php else: ?><div class="text-center text-muted py-3"><i class="fa-regular fa-file fs-2 mb-2"></i><p class="small mb-0">Belum ada CV yang diunggah.</p></div><?php endif; ?></div>
	</div>
</div>
<?= $this->endSection() ?>
