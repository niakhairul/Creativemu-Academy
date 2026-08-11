<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="mb-4">

    <h2 class="fw-bold">
        Profil Saya
    </h2>

    <p class="text-muted">
        Kelola informasi profil peserta Anda.
    </p>

    <?php if (session()->getFlashdata('success')): ?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">

        <strong>✅ Berhasil!</strong>
        <?= session()->getFlashdata('success') ?>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
        </button>

    </div>

<?php endif; ?>

</div>


<div class="row">

    <!-- Foto Profil -->
    <div class="col-lg-4 mb-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body text-center p-4">

                <div class="profile-avatar mx-auto mb-3">

    <?php if (!empty($user['foto'])): ?>

        <img src="<?= base_url('uploads/profil/' . $user['foto']) ?>"
             alt="Foto Profil"
             class="rounded-circle"
             width="100"
             height="100"
             style="object-fit: cover;">

    <?php else: ?>

        <span style="font-size: 60px;">
            👤
        </span>

    <?php endif; ?>

</div>

                <h5 class="fw-bold">
                    <?= esc($user['nama'] ?? 'Peserta') ?>
                </h5>

                <p class="text-muted mb-0">
                    Peserta Creativemu Academy
                </p>

            </div>

        </div>

    </div>


    <!-- Informasi Profil -->
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-4">
                    Informasi Pribadi
                </h5>


                <!-- Nama -->
                <div class="row mb-3">

                    <div class="col-md-4">
                        <span class="text-muted">
                            Nama Lengkap
                        </span>
                    </div>

                    <div class="col-md-8 fw-semibold">
                        <?= esc($user['nama'] ?? '-') ?>
                    </div>

                </div>


                <!-- Email -->
                <div class="row mb-3">

                    <div class="col-md-4">
                        <span class="text-muted">
                            Email
                        </span>
                    </div>

                    <div class="col-md-8 fw-semibold">
                        <?= esc($user['email'] ?? '-') ?>
                    </div>

                </div>


                <!-- Nomor HP -->
                <div class="row mb-3">

                    <div class="col-md-4">
                        <span class="text-muted">
                            Nomor HP
                        </span>
                    </div>

                    <div class="col-md-8 fw-semibold">
                        <?= esc($user['no_hp'] ?? '-') ?>
                    </div>

                </div>


                <!-- Jenis Kelamin -->
                <div class="row mb-3">

                    <div class="col-md-4">
                        <span class="text-muted">
                            Jenis Kelamin
                        </span>
                    </div>

                    <div class="col-md-8 fw-semibold">
                        <?= esc($user['jenis_kelamin'] ?? '-') ?>
                    </div>

                </div>


                <!-- Asal Sekolah -->
                <div class="row mb-3">

                    <div class="col-md-4">
                        <span class="text-muted">
                            Asal Sekolah / Kampus
                        </span>
                    </div>

                    <div class="col-md-8 fw-semibold">
                        <?= esc($user['asal_sekolah'] ?? '-') ?>
                    </div>

                </div>


                <hr>


                <!-- Tombol Edit -->
                <a href="<?= base_url('pelatihan/edit-profil') ?>"
                   class="btn btn-primary">

                    ✏️ Edit Profil

                </a>


            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>