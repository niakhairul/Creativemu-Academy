<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container">

    <div class="row justify-content-center align-items-center py-5">

        <div class="col-lg-7">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>"
                             width="170"
                             class="mb-3">

                        <h3 class="fw-bold text-primary">
                            Registrasi Peserta
                        </h3>

                        <p class="text-muted">
                            Sistem Pelatihan Creativemu Academy
                        </p>

                    </div>

                    <?php if(session()->getFlashdata('error')) : ?>

                        <div class="alert alert-danger">

                            <?= session()->getFlashdata('error') ?>

                        </div>

                    <?php endif; ?>

                    <form action="<?= base_url('register/save') ?>" method="post">

                        <div class="mb-3">

                            <label class="form-label">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                placeholder="Nama Lengkap"
                                required>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Jenis Kelamin
                                </label>

                                <select
                                    name="jenis_kelamin"
                                    class="form-select"
                                    required>

                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Nomor HP
                                </label>

                                <input
                                    type="text"
                                    name="no_hp"
                                    class="form-control"
                                    placeholder="08xxxxxxxxxx"
                                    required>

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Email"
                                required>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Password"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Konfirmasi Password
                                </label>

                                <input
                                    type="password"
                                    name="konfirmasi_password"
                                    class="form-control"
                                    placeholder="Konfirmasi Password"
                                    required>

                            </div>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Daftar Sekarang

                        </button>

                    </form>

                    <div class="text-center mt-4">

                        Sudah punya akun?

                        <a href="<?= base_url('pelatihan/login') ?>">

                            Login

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>