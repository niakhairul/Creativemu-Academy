<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Pengaturan
        </h2>

        <p class="text-muted mb-0">
            Kelola informasi profil dan akun Anda.
        </p>
    </div>


    <!-- NOTIFIKASI -->
    <?php if (session()->getFlashdata('success')): ?>

        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm">
            <strong>✅ Berhasil!</strong>
            <?= session()->getFlashdata('success') ?>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>

    <?php endif; ?>


    <!-- KARTU PROFIL -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4 p-md-5">

            <?php
            if (!empty($user['foto_profil'])) {
                $fotoUrl = base_url('uploads/profil/' . $user['foto_profil']);
            } elseif (!empty($pendaftaran['pas_foto'])) {
                $fotoUrl = base_url('uploads/foto/' . $pendaftaran['pas_foto']);
            } else {
                $fotoUrl = base_url('assets/img/logo creativemu academy.jpg');
            }
            ?>


            <!-- HEADER PROFIL -->
            <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 mb-4">

                <!-- FOTO -->
                <div class="text-center">

                    <img src="<?= $fotoUrl ?>"
                         class="rounded-circle shadow-sm border"
                         width="130"
                         height="130"
                         style="object-fit: cover;">

                </div>


                <!-- NAMA DAN STATUS -->
                <div class="text-center text-md-start flex-grow-1">

                    <h3 class="fw-bold mb-1">
                        <?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?>
                    </h3>

                    <p class="text-muted mb-2">
                        Peserta CreativeMU Academy
                    </p>

                    <?php if (!empty($pendaftaran['status'])): ?>

                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                            <?= esc($pendaftaran['status']) ?>
                        </span>

                    <?php endif; ?>

                </div>


                <!-- TOMBOL -->
                <div class="d-flex gap-2">

                    <a href="<?= base_url('pelatihan/edit-profil') ?>"
                       class="btn btn-primary">

                        ✏️ Edit Profil

                    </a>

                </div>

            </div>


            <hr class="my-4">


            <!-- INFORMASI PRIBADI -->
            <h5 class="fw-bold mb-4">
                Informasi Pribadi
            </h5>


            <div class="row g-4">

                <!-- NAMA -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Nama Lengkap
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['nama'] ?? $user['nama'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- EMAIL -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Email
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['email'] ?? $user['email'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- NO HP -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Nomor HP / WhatsApp
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['no_hp'] ?? $user['no_hp'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- JENIS KELAMIN -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Jenis Kelamin
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['jenis_kelamin'] ?? $user['jenis_kelamin'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- TTL -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Tempat, Tanggal Lahir
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['ttl'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- PENDIDIKAN -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Pendidikan Terakhir
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['pendidikan_terakhir'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- ASAL SEKOLAH -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Asal Sekolah / Kampus
                        </small>

                        <span class="fw-semibold">
                            <?= esc($user['asal_sekolah'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- LOKASI -->
                <div class="col-md-6">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Lokasi Pelatihan
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['lokasi_pelatihan'] ?? '-') ?>
                        </span>

                    </div>

                </div>


                <!-- ALAMAT -->
                <div class="col-12">

                    <div class="p-3 bg-light rounded-3">

                        <small class="text-muted d-block mb-1">
                            Alamat Lengkap
                        </small>

                        <span class="fw-semibold">
                            <?= esc($pendaftaran['alamat'] ?? '-') ?>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- PENGATURAN AKUN -->
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-1">
                Pengaturan Akun
            </h5>

            <p class="text-muted mb-4">
                Kelola keamanan akun Anda.
            </p>

            <a href="<?= base_url('pelatihan/ubah-password') ?>"
               class="btn btn-warning">

                🔒 Ubah Password

            </a>

        </div>

    </div>

</div>

<?= $this->endSection() ?>