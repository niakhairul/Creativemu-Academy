<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<style>
    .absensi-hero { background: linear-gradient(125deg,#24123f,#6135a5); border-radius: 20px; color: #fff; position: relative; overflow: hidden; }
    .absensi-hero::after { content: ''; position: absolute; width: 220px; height: 220px; border: 32px solid rgba(255,255,255,.06); border-radius: 50%; right: -50px; top: -100px; }
    .content-card { background: #fff; border: 1px solid #eae2f6; border-radius: 16px; box-shadow: 0 4px 20px rgba(37,19,64,.03); }
    .pertemuan-card { border: 1px solid #eee8f4; border-radius: 14px; background: #fff; transition: all 0.2s ease; }
    .pertemuan-card:hover { border-color: #d2bfe8; box-shadow: 0 6px 15px rgba(79,45,128,.04); }
    .badge-hadir { background: #e5f7ed; color: #167345; }
    .badge-belum { background: #fff4d9; color: #936b00; }
    .btn-main { background: linear-gradient(135deg,#7042b4,#8d5ccc); border: 0; color: #fff; font-weight: 600; }
    .btn-main:hover { background: linear-gradient(135deg,#5e349d,#794bc4); color: #fff; }
</style>

<!-- Header & Tombol Kembali -->
<div class="d-flex justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Absensi Mengajar</h3>
        <p class="text-muted mb-0"><i class="fa-solid fa-book-open me-1" style="color:#794bc4"></i><?= esc($kelas['nama_kelas']) ?></p>
    </div>
    <a class="btn btn-outline-secondary btn-sm px-3" href="<?= base_url('mentor/kelas/' . $kelas['id_kelas']) ?>">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<!-- Kotak Ungu (Hero Section) yang dikembalikan -->
<section class="absensi-hero p-4 p-lg-5 mb-4 shadow-sm">
    <div class="position-relative z-1 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <span class="badge rounded-pill text-bg-light text-primary mb-2 px-3 py-1 fw-semibold">KELOLA KELAS</span>
            <h4 class="fw-bold mb-1">Absensi & Perbarui Materi Sesi</h4>
            <p class="mb-0 opacity-75 small">Lakukan presensi kehadiran Anda dan atur materi pembelajaran untuk setiap pertemuan.</p>
        </div>
        <div class="fs-1 opacity-75 d-none d-md-block">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
    </div>
</section>

<!-- Layout Utama Dibagi Menjadi Dua Bagian yang Lega -->
<div class="row g-4">
    <!-- Bagian Kiri: Kelola Materi & Status Absen Mentor -->
    <div class="col-lg-7">
        <div class="content-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Pertemuan</h5>
                    <p class="text-muted small mb-0">Absen mentor & buka sesi untuk peserta.</p>
                </div>
                <span class="badge rounded-pill text-bg-light text-primary px-3 py-2 fw-semibold">
                    <?= count($jadwal ?? []) ?> Sesi
                </span>
            </div>

            <?php if (empty($jadwal)): ?>
                <div class="text-center text-muted py-5">
                    <i class="fa-regular fa-calendar-xmark fs-1 mb-3 text-secondary opacity-50"></i>
                    <h6 class="fw-bold">Belum ada jadwal mengajar</h6>
                    <p class="small mb-0">Jadwal akan muncul setelah ditambahkan Admin.</p>
                </div>
            <?php else: ?>
                <div class="d-grid gap-3">
                    <?php foreach ($jadwal as $item): ?>
                        <?php 
                            $sesiDibuka = (int) ($item['absensi_dibuka'] ?? 0) === 1 && !empty($item['absensi_selesai']) && strtotime($item['absensi_selesai']) > time(); 
                            $mentorSudahAbsen = !empty($item['id_absensi']) && ($item['status_absen'] ?? '') === 'hadir'; 
                        ?>
                        <div class="pertemuan-card p-3">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-purple fw-bold px-2 py-1">#<?= esc($item['pertemuan_ke']) ?></span>
                                    <h6 class="fw-bold mb-0 text-dark">Pertemuan <?= esc($item['pertemuan_ke']) ?></h6>
                                </div>
                                <div>
                                    <span class="badge rounded-pill <?= $mentorSudahAbsen ? 'badge-hadir' : 'badge-belum' ?> small">
                                        <?= $mentorSudahAbsen ? 'Hadir' : 'Belum Absen' ?>
                                    </span>
                                    <?php if ($sesiDibuka): ?>
                                        <span class="badge rounded-pill badge-hadir small">Sesi Dibuka</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-muted small mb-3">
                                <div><i class="fa-solid fa-book me-1 text-secondary"></i> <?= esc($item['materi'] ?: 'Materi belum ditentukan') ?></div>
                                <div>
                                    <i class="fa-regular fa-calendar me-1 text-secondary"></i> 
                                    <?php if (!empty($item['tanggal_kbm'])): ?>
                                        <?= esc(date('d M Y', strtotime($item['tanggal_kbm']))) ?>
                                        <?php if (!empty($item['waktu_mulai'])): ?>
                                            , Pukul <?= esc(date('H:i', strtotime($item['waktu_mulai']))) ?>
                                            <?php if (!empty($item['waktu_selesai'])): ?>
                                                - <?= esc(date('H:i', strtotime($item['waktu_selesai']))) ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Jadwal belum diatur
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Tombol Aksi Mentor -->
                            <div class="bg-light p-2 rounded-2 mb-3">
                                <?php if (! $mentorSudahAbsen): ?>
                                    <form action="<?= base_url('mentor/kelas/' . $kelas['id_kelas'] . '/absensi/' . $item['id_jadwal']) ?>" method="post">
                                        <button class="btn btn-main btn-sm w-100"><i class="fa-solid fa-fingerprint me-1"></i> Klik untuk Absen Mentor</button>
                                    </form>
                                <?php elseif ($sesiDibuka): ?>
                                    <form action="<?= base_url('mentor/kelas/' . $kelas['id_kelas'] . '/absensi/' . $item['id_jadwal'] . '/tutup') ?>" method="post">
                                        <button class="btn btn-outline-danger btn-sm w-100"><i class="fa-solid fa-lock me-1"></i> Tutup Sesi Absen Peserta</button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= base_url('mentor/kelas/' . $kelas['id_kelas'] . '/absensi/' . $item['id_jadwal'] . '/buka') ?>" method="post" class="d-flex align-items-center gap-2">
                                        <input type="time" name="jam_mulai_absensi" class="form-control form-control-sm" required style="width: 85px;">
                                        <span class="text-muted small">s/d</span>
                                        <input type="time" name="jam_selesai_absensi" class="form-control form-control-sm" required style="width: 85px;">
                                        <button class="btn btn-main btn-sm text-nowrap flex-grow-1"><i class="fa-solid fa-lock-open me-1"></i> Buka Sesi</button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <!-- Form Update Materi Ringkas (Judul selalu terbuka, File/Link dibatasi tanggal KBM) -->
                            <form action="<?= base_url('mentor/jadwal/update-materi/' . $item['id_jadwal']) ?>" method="post" enctype="multipart/form-data">
                                <div class="row g-2">
                                    <!-- Input Judul Materi: Selalu terbuka agar bisa diisi kapan saja -->
                                    <div class="col-12">
                                        <input type="text" name="materi" class="form-control form-control-sm" value="<?= esc($item['materi'] ?? '') ?>" placeholder="Topik / Materi Pembelajaran" required>
                                    </div>

                                    <?php 
                                        $tanggalHariIni = date('Y-m-d');
                                        $tanggalKbm = !empty($item['tanggal_kbm']) ? $item['tanggal_kbm'] : null;
                                        $sudahWaktunya = ($tanggalKbm && $tanggalHariIni >= $tanggalKbm);
                                    ?>

                                    <?php if ($sudahWaktunya): ?>
                                        <!-- Input File & Link Google Drive: Hanya terbuka saat hari H atau setelahnya -->
                                        <div class="col-sm-6">
                                            <input type="url" name="link_materi" class="form-control form-control-sm" value="<?= esc($item['link_materi'] ?? '') ?>" placeholder="Link Google Drive">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="file" name="file_pdf" class="form-control form-control-sm" accept=".pdf">
                                        </div>
                                    <?php else: ?>
                                        <!-- Pesan jika berkas belum bisa diunggah -->
                                        <div class="col-12">
                                            <div class="alert alert-light border small text-muted py-1 mb-0 text-center">
                                                <i class="fa-solid fa-clock me-1"></i> Unggah file & link Google Drive akan dibuka pada tanggal <strong><?= $tanggalKbm ? date('d M Y', strtotime($tanggalKbm)) : 'pelatihan' ?></strong>.
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="col-12 text-end mt-2">
                                        <button type="submit" class="btn btn-dark btn-sm px-3 py-1"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Materi</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bagian Kanan: Rekap Kehadiran Peserta (Accordion Bersih) -->
    <div class="col-lg-5">
        <div class="content-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1">Rekap Peserta</h5>
                    <p class="text-muted small mb-0">Daftar kehadiran siswa per pertemuan.</p>
                </div>
                <i class="fa-solid fa-users fs-5 text-purple"></i>
            </div>

            <?php if (empty($pesertaAbsensi)): ?>
                <div class="text-center text-muted py-5">
                    <i class="fa-solid fa-user-clock fs-2 mb-2 opacity-50"></i>
                    <p class="small mb-0">Belum ada data absensi peserta.</p>
                </div>
            <?php else: ?>
                <div class="accordion" id="accordionPeserta">
                    <?php 
                        $groupedPeserta = [];
                        foreach ($pesertaAbsensi as $p) {
                            $groupedPeserta[$p['id_jadwal']]['pertemuan_ke'] = $p['pertemuan_ke'];
                            $groupedPeserta[$p['id_jadwal']]['data'][] = $p;
                        }
                    ?>
                    <?php $i = 0; foreach ($groupedPeserta as $idJadwal => $group): $i++; ?>
                        <div class="accordion-item border mb-2 rounded-3 overflow-hidden shadow-none">
                            <h2 class="accordion-header" id="heading<?= $idJadwal ?>">
                                <button class="accordion-button <?= $i > 1 ? 'collapsed' : '' ?> fw-semibold py-2 px-3 bg-light text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $idJadwal ?>" aria-expanded="<?= $i === 1 ? 'true' : 'false' ?>">
                                    Pertemuan <?= esc($group['pertemuan_ke']) ?> 
                                    <span class="badge text-bg-secondary ms-auto me-2 font-monospace"><?= count($group['data']) ?> Siswa</span>
                                </button>
                            </h2>
                            <div id="collapse<?= $idJadwal ?>" class="accordion-collapse collapse <?= $i === 1 ? 'show' : '' ?>" aria-labelledby="heading<?= $idJadwal ?>" data-bs-parent="#accordionPeserta">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0 text-small" style="font-size: 0.85rem;">
                                            <thead class="table-light text-muted">
                                                <tr>
                                                    <th class="ps-3 py-2">Nama</th>
                                                    <th class="py-2">Status</th>
                                                    <th class="pe-3 py-2 text-end">Waktu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($group['data'] as $peserta): ?>
                                                    <tr>
                                                        <td class="ps-3 fw-semibold text-truncate" style="max-width: 120px;"><?= esc($peserta['nama_peserta']) ?></td>
                                                        <td>
                                                            <?php if (($peserta['status'] ?? null) === 'hadir'): ?>
                                                                <span class="badge badge-hadir px-2">Hadir</span>
                                                            <?php else: ?>
                                                                <span class="badge text-bg-light text-muted px-2">Belum</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="pe-3 text-muted text-end"><?= !empty($peserta['waktu_absen']) ? esc(date('H:i', strtotime($peserta['waktu_absen']))) : '-' ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>