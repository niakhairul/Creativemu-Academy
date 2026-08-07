<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="row">

    <div class="col-lg-8"></div>

        <div class="card shadow border-0 rounded-4">

            <div class="card-body p-4">

                <h2 class="fw-bold text-primary">
                    Kelas Saya
                </h2>

                <p class="text-muted">
                    Daftar kelas yang sedang Anda ikuti.
                </p>

                <hr>

                <?php if (!$kelas): ?>

                    <div class="alert alert-warning">

                        Anda belum mendaftar kelas.

                    </div>

                    <a href="<?= base_url('pelatihan/daftar-kelas') ?>"
                       class="btn btn-primary">

                        Daftar Kelas

                    </a>

                <?php else: ?>

                    <div class="card border-0 shadow-sm">

                        <div class="card-body">

                            <h3 class="fw-bold">
                                <?= esc($kelas['nama_kelas']) ?>
                            </h3>

                            <hr>

                            <p>
                                <strong>Mentor :</strong><br>
                                <?= esc($kelas['mentor']) ?>
                            </p>

                            <p>
                                <strong>Metode :</strong><br>
                                <?= esc($kelas['metode']) ?>
                            </p>

                            <p>
                                <strong>Jadwal :</strong><br>
                                <?= esc($kelas['jadwal']) ?>
                            </p>

                            <p>
                                <strong>Jam :</strong><br>
                                <?= esc($kelas['jam']) ?>
                            </p>

                            <p>
                                <strong>Status :</strong>

                                <?php if($kelas['status_pendaftaran']=="Menunggu"): ?>

                                    <span class="badge bg-warning">
                                        Menunggu Validasi
                                    </span>

                                <?php elseif($kelas['status_pendaftaran']=="Disetujui"): ?>

                                    <span class="badge bg-success">
                                        Disetujui
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-danger">
                                        Ditolak
                                    </span>

                                <?php endif; ?>

                            </p>

                            <hr>

                            <h6 class="fw-bold">
                                Progress Belajar
                            </h6>

                            <div class="progress mb-4" style="height:25px;">

                                <div class="progress-bar bg-success"
                                style="width:25%;">

                                25%

                                </div>

                            </div>

                            <?php if($kelas['status_pendaftaran']=="Disetujui"): ?>

                                <a href="<?= base_url('pelatihan/kbm') ?>"
                                   class="btn btn-success w-100 py-2">

                                    <i class="bi bi-mortarboard-fill"></i>

                                    Masuk KBM

                                </a>

                            <?php elseif($kelas['status_pendaftaran']=="Menunggu"): ?>

                                <button
                                    class="btn btn-secondary w-100 py-2"
                                    disabled>

                                    Menunggu Persetujuan Admin

                                </button>

                            <?php else: ?>

                                <button
                                    class="btn btn-danger w-100 py-2"
                                    disabled>

                                    Pendaftaran Ditolak

                                </button>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>