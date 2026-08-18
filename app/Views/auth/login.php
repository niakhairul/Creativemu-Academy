<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">

                    <?php if(session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mb-4">
                        <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>" width="170" class="mb-3">
                        <h3 class="fw-bold text-primary">
                            Selamat Datang
                        </h3>
                        <p class="text-muted">
                            Sistem Pelatihan Creativemu Academy
                        </p>
                    </div>

                    <form action="<?= base_url('pelatihan/login/process') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="Masukkan Email" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="Masukkan Password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            Login
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        Belum punya akun?
                        <a href="<?= base_url('pelatihan/register') ?>">
                            Daftar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>