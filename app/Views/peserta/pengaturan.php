<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>


<div class="mb-4">
    <h2 class="fw-bold">
        Pengaturan
    </h2>

    <p class="text-muted">
        Kelola pengaturan akun Anda.
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

<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">
            Pengaturan Akun
        </h5>

        <div class="row mb-4">

            <div class="col-md-4">
                <strong>Nama</strong>
            </div>

            <div class="col-md-8">
                <?= esc($user['nama'] ?? '-') ?>
            </div>

        </div>

        <div class="row mb-4">

            <div class="col-md-4">
                <strong>Email</strong>
            </div>

            <div class="col-md-8">
                <?= esc($user['email'] ?? '-') ?>
            </div>

        </div>

        <hr>

        <h6 class="fw-bold mt-4 mb-3">
            Akun
        </h6>

        <a href="<?= base_url('pelatihan/edit-profil') ?>"
           class="btn btn-primary">

            ✏️ Edit Profil

        </a>

        <a href="<?= base_url('pelatihan/ubah-password') ?>"
   class="btn btn-warning ms-2">

    🔒 Ubah Password

</a>

    </div>

</div>

<?= $this->endSection() ?>