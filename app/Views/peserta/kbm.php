<?= $this->extend('layouts/app') // Sesuaikan layout utama Anda ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <!-- Header Kelas -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="fw-bold text-primary"><?= esc($kelas['nama_kelas']) ?></h3>
            <p class="text-muted mb-1"><?= esc($kelas['ringkasan'] ?? $kelas['deskripsi']) ?></p>
            <span class="badge bg-success">Mentor: <?= esc($kelas['nama_mentor'] ?? '-') ?></span>
        </div>
    </div>

    <!-- Navigasi Tab -->
    <ul class="nav nav-tabs" id="kbmTab" role="tablist">
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
    <div class="tab-content bg-white p-4 border border-top-0 rounded-bottom shadow-sm" id="kbmTabContent">
        
        <!-- TAB 1: MATERI -->
        <div class="tab-pane fade show active" id="materi" role="tabpanel">
            <h4 class="mb-3">Daftar Modul & Materi Sesi</h4>
            <p class="text-muted">Unduh atau pelajari modul materi yang telah diunggah oleh mentor.</p>
            <!-- Anda bisa melakukan looping daftar materi di sini -->
            <div class="alert alert-info">Silakan cek modul berkala sesuai jadwal pertemuan kelas.</div>
        </div>

        <!-- TAB 2: ABSENSI -->
        <div class="tab-pane fade" id="absensi" role="tabpanel">
            <h4 class="mb-3">Absensi Kehadiran & Riwayat</h4>
            <div class="mb-3">
                <span class="badge bg-secondary">Total Hadir: <?= $jumlahHadir ?> dari <?= $totalPertemuan ?> Pertemuan</span>
                <span class="badge bg-info">Persentase: <?= $persentaseKehadiran ?>%</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
                                <form action="<?= base_url('pelatihan/simpanAbsensi') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_jadwal_kelas" value="<?= $j['id_jadwal_kelas'] ?>">
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