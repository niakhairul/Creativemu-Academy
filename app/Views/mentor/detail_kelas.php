<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<style>
    .detail-banner{background:linear-gradient(125deg,#251340,#6239a3);border-radius:22px;color:#fff;position:relative;overflow:hidden}.detail-banner:after{content:'';position:absolute;right:-55px;bottom:-110px;width:280px;height:280px;border:42px solid rgba(255,255,255,.08);border-radius:50%}.detail-banner>*{position:relative;z-index:1}.detail-card{border:1px solid #ece6f4;border-radius:16px;box-shadow:0 8px 22px rgba(37,19,64,.05)}.detail-label{font-size:.72rem;font-weight:700;letter-spacing:.08em;color:#8a819a;text-transform:uppercase}.detail-value{font-weight:600;color:#2a2134}.detail-progress{height:9px;border-radius:10px;background:#eee7f6}.detail-progress .progress-bar{background:linear-gradient(90deg,#794bc4,#af7ee6)}
</style>

<section class="detail-banner p-4 p-lg-5 mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
        <div>
            <a href="<?= base_url('mentor/kelas') ?>" class="text-white text-decoration-none small"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke daftar kelas</a>
            <span class="badge rounded-pill text-bg-light text-primary d-block w-fit mt-3 mb-2"><?= esc($kelas['kategori'] ?: 'Pelatihan') ?></span>
            <h2 class="fw-bold mb-2"><?= esc($kelas['nama_kelas']) ?></h2>
            <p class="mb-0 opacity-75"><?= esc($kelas['ringkasan'] ?: $kelas['deskripsi'] ?: 'Informasi kelas belum tersedia.') ?></p>
        </div>
        <a href="<?= base_url('mentor/kelas/' . $kelas['id_kelas'] . '/absensi') ?>" class="btn btn-outline-light fw-semibold"><i class="fa-solid fa-calendar-check me-1"></i> Absensi Mengajar</a>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="detail-card bg-white p-3 h-100"><div class="detail-label mb-2">Jadwal Mulai</div><div class="detail-value"><i class="fa-regular fa-calendar me-2 text-primary"></i><?= esc($kelas['tanggal_mulai_kelas'] ?: 'Belum ditentukan') ?></div></div></div>
    <div class="col-md-4"><div class="detail-card bg-white p-3 h-100"><div class="detail-label mb-2">Peserta Terdaftar</div><div class="detail-value"><i class="fa-solid fa-users me-2 text-success"></i><?= count($peserta ?? []) ?> dari <?= (int) ($kelas['kapasitas'] ?? 0) ?> peserta</div></div></div>
    <div class="col-md-4"><div class="detail-card bg-white p-3 h-100"><div class="detail-label mb-2">Materi Tersedia</div><div class="detail-value"><i class="fa-solid fa-folder-open me-2 text-warning"></i><?= (int) ($jumlah_materi ?? 0) ?> materi</div></div></div>
</div>

<div class="detail-card bg-white p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-2"><h5 class="fw-bold mb-0">Kapasitas Kelas</h5><span class="fw-semibold"><?= (int) ($persentase_peserta ?? 0) ?>%</span></div>
    <div class="progress detail-progress"><div class="progress-bar" style="width:<?= (int) ($persentase_peserta ?? 0) ?>%"></div></div>
</div>

<div class="detail-card bg-white p-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h5 class="fw-bold mb-0">Daftar Peserta</h5><span class="badge rounded-pill text-bg-light text-primary"><?= count($peserta ?? []) ?> peserta</span></div>
    <?php if (empty($peserta)): ?>
        <div class="text-center text-muted py-4"><i class="fa-solid fa-users-slash fs-2 mb-2"></i><p class="mb-0">Belum ada peserta terdaftar pada kelas ini.</p></div>
    <?php else: ?>
        <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Nama Peserta</th><th>Email</th><th>No. HP</th><th>Status</th></tr></thead><tbody>
            <?php foreach ($peserta as $item): ?>
                <tr><td class="fw-semibold"><?= esc($item['nama_user'] ?? '-') ?></td><td><?= esc($item['email_user'] ?? '-') ?></td><td><?= esc($item['no_hp_user'] ?? '-') ?></td><td><span class="badge rounded-pill text-bg-success">Aktif</span></td></tr>
            <?php endforeach; ?>
        </tbody></table></div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
