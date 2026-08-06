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

    <img src="<?= base_url('assets/img/logo creativemu academy.jpg') ?>"
         class="rounded-circle shadow"
         width="90">

    <h5 class="mt-3 mb-1 fw-bold">
        Ika Cahya
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

                <a href="<?= base_url('pelatihan/kelas') ?>">

                    <i class="bi bi-book-fill"></i>

                    Kelas Saya

                </a>

            </li>

            <li>

                <a href="<?= base_url('pelatihan/materi') ?>">

                    <i class="bi bi-journal-bookmark-fill"></i>

                    Materi

                </a>

            </li>

            <li>

                <a href="#">

                    <i class="bi bi-file-earmark-text-fill"></i>

                    Tugas

                </a>

            </li>

            <li>

                <a href="#">

                    <i class="bi bi-person-circle"></i>

                    Profil

                </a>

            </li>

            <li>

                <a href="#">

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