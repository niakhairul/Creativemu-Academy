<?= $this->extend('layouts/dashboard_template') ?>
<?= $this->section('content') ?>

<style>
    .kbm-page{max-width:1120px;margin:0 auto}.kbm-hero{background:linear-gradient(125deg,#24123f,#6135a5);border-radius:22px;color:#fff;overflow:hidden;position:relative}.kbm-hero:after{content:'';position:absolute;width:250px;height:250px;border:38px solid rgba(255,255,255,.08);border-radius:50%;right:-65px;top:-125px}.kbm-hero>*{position:relative;z-index:1}.kbm-shell{border:1px solid #ece6f4;border-radius:18px;box-shadow:0 9px 25px rgba(37,19,64,.05)}.kbm-tabs{border:0;gap:8px;flex-wrap:wrap}.kbm-tabs .nav-link{border:1px solid #e6dcef;border-radius:11px;color:#665a73;font-size:.9rem;padding:.75rem 1rem}.kbm-tabs .nav-link.active{background:#7042b4;border-color:#7042b4;color:#fff}.kbm-item{border:1px solid #eee8f4;border-radius:14px;padding:1rem}.kbm-icon{align-items:center;background:#f0e8fb;border-radius:12px;color:#7042b4;display:flex;height:42px;justify-content:center;width:42px}.kbm-table thead th{background:#faf8fd;color:#675a73;font-size:.78rem;text-transform:uppercase}.kbm-table{border:1px solid #eee8f4;border-radius:12px;overflow:hidden}
    @media (max-width: 767.98px){.kbm-page{padding-left:12px;padding-right:12px}.kbm-tabs{display:grid;grid-template-columns:1fr 1fr}.kbm-tabs .nav-link{width:100%;font-size:.78rem;padding:.65rem .45rem}.kbm-shell{padding:1rem!important}.kbm-hero{border-radius:16px}}
</style>

<div class="container-fluid py-4 kbm-page">
    <!-- Header Kelas -->
    <section class="kbm-hero p-4 p-lg-5 mb-4">
        <div class="card-body">
            <span class="badge rounded-pill text-bg-light text-primary mb-3">RUANG BELAJAR</span>
            <h2 class="fw-bold mb-2"><?= esc($kelas['nama_kelas']) ?></h2>
            <p class="text-muted mb-1"><?= esc($kelas['ringkasan'] ?? $kelas['deskripsi']) ?></p>
            <span class="badge bg-light text-primary">Mentor: <?= esc($kelas['nama_mentor'] ?? '-') ?></span>
        </div>
    </section>

    <!-- Navigasi Tab -->
    <ul class="nav kbm-tabs mb-3" id="kbmTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="materi-tab" data-bs-toggle="tab" data-bs-target="#materi" type="button" role="tab">📚 Materi Pembelajaran</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="absensi-tab" data-bs-toggle="tab" data-bs-target="#absensi" type="button" role="tab">📅 Absensi & Riwayat</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="tugas-tab" data-bs-toggle="tab" data-bs-target="#tugas" type="button" role="tab">📝 Ujian & Tugas</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="sertifikat-tab" data-bs-toggle="tab" data-bs-target="#sertifikat" type="button" role="tab">🏆 Sertifikat & Angket</button>
        </li>
    </ul>

    <!-- Konten Tab -->
    <div class="tab-content kbm-shell bg-white p-4" id="kbmTabContent">
        
        <!-- TAB 1: MATERI -->
        <div class="tab-pane fade show active" id="materi" role="tabpanel">
            <h4 class="mb-3">Daftar Modul & Materi Sesi</h4>
            <p class="text-muted">Unduh atau pelajari modul materi yang telah diunggah oleh mentor.</p>
            <!-- Anda bisa melakukan looping daftar materi di sini -->
            <?php if (empty($jadwal)): ?><div class="alert alert-info">Belum ada sesi materi.</div><?php else: ?><?php foreach ($jadwal as $j): ?><div class="kbm-item d-flex gap-3 mb-3"><div class="kbm-icon"><i class="fa-solid fa-book-open"></i></div><div><div class="fw-bold">Pertemuan <?= esc($j['pertemuan_ke']) ?></div><div class="text-muted small"><?= esc($j['materi'] ?? 'Materi sesi') ?></div><div class="text-muted small mt-1"><i class="fa-regular fa-calendar me-1"></i><?= !empty($j['tanggal_kbm']) ? esc(date('d M Y, H:i', strtotime($j['tanggal_kbm']))) : 'Jadwal belum ditentukan' ?></div></div></div><?php endforeach; ?><?php endif; ?>
        </div>

        <!-- TAB 2: ABSENSI -->
        <div class="tab-pane fade" id="absensi" role="tabpanel">
            <h4 class="mb-3">Absensi Kehadiran & Riwayat</h4>
            <div class="mb-3">
                <span class="badge bg-secondary">Total Hadir: <?= $jumlahHadir ?> dari <?= $totalPertemuan ?> Pertemuan</span>
                <span class="badge bg-info">Persentase: <?= $persentaseKehadiran ?>%</span>
            </div>
            
            <div class="table-responsive">
                <table class="table kbm-table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pertemuan</th>
                            <th>Topik / Tanggal</th>
                            <th>Status Kehadiran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jadwal as $j): ?>
                        <tr>
                            <td><?= $j['pertemuan_ke'] ?></td>
                            <td><?= esc($j['topik'] ?? 'Sesi ' . $j['pertemuan_ke']) ?></td>
                            <td>
                                <?php if(isset($j['absensi']['status'])): ?>
                                    <span class="badge bg-success text-capitalize"><?= $j['absensi']['status'] ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum Absen</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if(!isset($j['absensi'])): ?>
                                <form action="<?= base_url('pelatihan/absensi/simpan') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_jadwal" value="<?= $j['id_jadwal'] ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Hadir</button>
                                </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: UJIAN & TUGAS -->
        <div class="tab-pane fade" id="tugas" role="tabpanel">
            <h4 class="mb-3">Daftar Tugas & Ujian</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card border">
                        <div class="card-body">
                            <h5>Tugas Praktikum</h5>
                            <p class="text-muted">Status: <?= !empty($pengumpulan) ? 'Sudah Diunggah' : 'Belum Dikerjakan' ?></p>
                            <a href="<?= base_url('pelatihan/tugas') ?>" class="btn btn-sm btn-outline-primary">Kelola Tugas</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border">
                        <div class="card-body">
                            <h5>Ujian Akhir Kelas</h5>
                            <p class="text-muted">Nilai: <?= $hasilUjian['nilai'] ?? 'Belum Ujian' ?></p>
                            <a href="<?= base_url('pelatihan/ujian') ?>" class="btn btn-sm btn-outline-primary">Mulai / Cek Ujian</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: SERTIFIKAT & ANGKET -->
        <div class="tab-pane fade" id="sertifikat" role="tabpanel">
            <h4 class="mb-3">Evaluasi & Sertifikat Kelulusan</h4>
            
            <div class="mb-4">
                <h5>1. Angket Evaluasi Kepuasan</h5>
                <?php if ($sudahIsiAngket): ?>
                    <div class="alert alert-success">Terima kasih, Anda telah mengisi angket evaluasi pelatihan ini.</div>
                <?php else: ?>
                    <p class="text-muted">Silakan isi angket kepuasan terlebih dahulu untuk membuka akses unduh sertifikat.</p>
                    <a href="<?= base_url('pelatihan/angket') ?>" class="btn btn-warning">Isi Angket Evaluasi</a>
                <?php endif; ?>
            </div>

            <hr>

            <div>
                <h5>2. Sertifikat Pelatihan</h5>
                <?php if ($sertifikatAcademy): ?>
                    <div class="alert alert-success">Selamat! Anda lulus dan dapat mengunduh sertifikat Anda.</div>
                    <a href="<?= base_url('pelatihan/sertifikat') ?>" class="btn btn-success">Unduh Sertifikat</a>
                <?php else: ?>
                    <div class="alert alert-warning">Sertifikat belum dapat diunduh. Pastikan Anda lulus ujian (minimal nilai 70) dan sudah mengisi angket evaluasi.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>