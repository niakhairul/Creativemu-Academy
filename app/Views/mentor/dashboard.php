<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<style>
    .mentor-hero{background:linear-gradient(125deg,#22133c,#5d2f9d 58%,#9057d3);border-radius:22px;color:#fff;overflow:hidden;position:relative}.mentor-hero:after{content:'';position:absolute;width:260px;height:260px;border:45px solid rgba(255,255,255,.09);border-radius:50%;right:-70px;top:-105px}.mentor-hero>*{position:relative;z-index:1}.hero-icon{width:62px;height:62px;background:rgba(255,255,255,.16);border-radius:18px;display:grid;place-items:center;font-size:1.7rem}.stat-card{border:0;border-radius:18px;box-shadow:0 8px 24px rgba(34,19,60,.07);height:100%}.stat-icon{height:48px;width:48px;border-radius:14px;display:grid;place-items:center;font-size:1.15rem}.class-card{border:1px solid #eee8f7;border-radius:17px;transition:.2s}.class-card:hover{border-color:#bba0e4;box-shadow:0 10px 22px rgba(79,45,128,.08);transform:translateY(-2px)}.progress{height:8px;border-radius:8px;background:#eee8f7}.progress-bar{background:linear-gradient(90deg,#794bc4,#a878e0)}.muted-label{font-size:.72rem;font-weight:700;letter-spacing:.08em;color:#8a819a}.empty-state{border:2px dashed #ded3ef;border-radius:18px;background:#fcfaff}
</style>

<section class="mentor-hero p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div><span class="badge rounded-pill text-bg-light text-primary mb-3">PORTAL INSTRUKTUR</span><h2 class="fw-bold mb-2">Selamat datang, <?= esc(session()->get('nama')) ?>!</h2><p class="mb-0 opacity-75">Pantau kelas, peserta, dan materi pembelajaran Anda dalam satu tempat.</p></div>
        <div class="hero-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon text-primary" style="background:#eee7fb"><i class="fa-solid fa-book-open"></i></div><div><div class="muted-label">KELAS DIAMPU</div><div class="fs-2 fw-bold text-dark"><?= $total_kelas ?></div></div></div></div></div>
    <div class="col-md-4"><div class="card stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon text-success" style="background:#e4f7ee"><i class="fa-solid fa-users"></i></div><div><div class="muted-label">PESERTA AKTIF</div><div class="fs-2 fw-bold text-dark"><?= $total_peserta ?></div></div></div></div></div>
    <div class="col-md-4"><div class="card stat-card"><div class="card-body d-flex align-items-center gap-3"><div class="stat-icon text-warning" style="background:#fff4d9"><i class="fa-solid fa-folder-open"></i></div><div><div class="muted-label">MATERI TERSEDIA</div><div class="fs-2 fw-bold text-dark"><?= $total_materi ?></div></div></div></div></div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3"><div><h4 class="fw-bold mb-1">Kelas Saya</h4><p class="text-muted mb-0">Ringkasan kapasitas dan peserta pada kelas yang Anda ampu.</p></div><a href="<?= base_url('mentor/kelas') ?>" class="btn btn-outline-primary d-none d-sm-inline-block">Semua Kelas <i class="fa-solid fa-arrow-right ms-1"></i></a></div>

<?php if (empty($kelas_terbaru)): ?>
    <div class="empty-state p-5 text-center"><div class="fs-2 text-primary mb-2"><i class="fa-solid fa-book"></i></div><h5 class="fw-bold">Belum ada kelas yang ditugaskan</h5><p class="text-muted mb-0">Kelas yang ditugaskan oleh Admin akan tampil pada bagian ini.</p></div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($kelas_terbaru as $kelas): ?>
            <div class="col-lg-6"><article class="class-card bg-white p-4 h-100"><div class="d-flex justify-content-between gap-3 mb-3"><div><span class="badge rounded-pill mb-2" style="background:#f0eafb;color:#6135a5"><?= esc($kelas['kategori'] ?: 'Pelatihan') ?></span><h5 class="fw-bold mb-1"><?= esc($kelas['nama_kelas']) ?></h5><div class="small text-muted"><i class="fa-regular fa-calendar me-1"></i><?= esc($kelas['tanggal_mulai_kelas'] ?: 'Jadwal belum ditentukan') ?></div></div><a href="<?= base_url('mentor/kelas/' . $kelas['id_kelas']) ?>" class="btn btn-light btn-sm align-self-start" title="Lihat detail"><i class="fa-solid fa-arrow-up-right-from-square"></i></a></div><div class="d-flex justify-content-between small mb-2"><span class="text-muted"><i class="fa-solid fa-users me-1"></i><?= $kelas['jumlah_peserta'] ?> peserta</span><span class="fw-semibold"><?= $kelas['jumlah_peserta'] ?>/<?= (int) ($kelas['kapasitas'] ?? 0) ?> kapasitas</span></div><div class="progress mb-3"><div class="progress-bar" style="width:<?= $kelas['persentase_peserta'] ?>%"></div></div><a href="<?= base_url('mentor/kelas/' . $kelas['id_kelas'] . '/materi') ?>" class="small text-decoration-none fw-semibold" style="color:#794bc4">Kelola materi <i class="fa-solid fa-arrow-right ms-1"></i></a></article></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
