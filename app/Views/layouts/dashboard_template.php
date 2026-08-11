<!DOCTYPE html>
<html lang="id">

<head>

    <?= $this->include('layouts/header') ?>

</head>

<body>

<div class="dashboard-wrapper">

    <!-- Sidebar -->

    <div class="sidebar">
    <div class="logo">

    <?php if (!empty(session('foto'))): ?>

    <img src="<?= base_url('uploads/profil/' . session('foto')) ?>"
         class="rounded-circle shadow"
         width="90"
         height="90"
         style="object-fit: cover;">

<?php else: ?>

    <img src="<?= base_url('assets/img/logo creativemu academy.jpg') ?>"
         class="rounded-circle shadow"
         width="90"
         height="90">

<?php endif; ?>
    <h5 class="mt-3 mb-1 fw-bold">
    <?= esc(ucwords(session('nama'))) ?>
</h5>

    <span class="badge bg-light text-dark">
        Peserta
    </span>

    <hr class="text-white mt-4">

</div>

       <ul>

    <li class="active">
        <a href="<?= base_url('peserta/dashboard') ?>">
            <i class="bi bi-house-door-fill"></i>
            Dashboard
        </a>
    </li>

    <li>
        <a href="<?= base_url('pelatihan/profil') ?>">
            <i class="bi bi-person-circle"></i>
            Profil
        </a>
    </li>

    <li>
        <a href="<?= base_url('pelatihan/daftar-kelas') ?>">
            <i class="bi bi-grid-fill"></i>
            Daftar Kelas
        </a>
    </li>

    <li>
        <a href="<?= base_url('pelatihan/kelas') ?>">
            <i class="bi bi-book-fill"></i>
            Kelas Saya
        </a>
    </li>

    <li>
        <a href="<?= base_url('pelatihan/kbm') ?>">
            <i class="bi bi-mortarboard-fill"></i>
            KBM
        </a>
    </li>

    <li>
        <a href="<?= base_url('pelatihan/pengaturan') ?>">
            <i class="bi bi-gear-fill"></i>
            Pengaturan
        </a>
    </li>

    <li>
        <a href="<?= base_url('logout') ?>">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>
    </li>

</ul>

    </div>

    <!-- Content -->

    <div class="content">

        <?= $this->renderSection('content') ?>

    </div>

</div>

</body>

</html>