<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container">

    <div class="row justify-content-center align-items-center py-5">

        <div class="col-lg-7">

            <div class="card shadow-lg">

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

                    <form>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Nama Lengkap</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Nama Lengkap">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">NIS</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Nomor Induk Siswa">

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Jenis Kelamin</label>

                                <select class="form-select">

                                    <option>Pilih</option>
                                    <option>Laki-laki</option>
                                    <option>Perempuan</option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Nomor HP</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="08xxxxxxxxxx">

                            </div>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                class="form-control"
                                placeholder="Email">

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">Password</label>

                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder="Password">

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">Konfirmasi Password</label>

                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder="Konfirmasi Password">

                            </div>

                        </div>

                        <button class="btn btn-primary w-100">

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