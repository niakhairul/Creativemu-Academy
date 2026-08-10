<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="mb-4">

    <h2 class="fw-bold">
        Ubah Password
    </h2>

    <p class="text-muted">
        Perbarui password akun Anda untuk menjaga keamanan akun.
    </p>

</div>


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">
            🔒 Keamanan Akun
        </h5>


        <!-- Pesan Error -->
        <?php if (session()->getFlashdata('error')): ?>

            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>

        <?php endif; ?>


        <!-- Pesan Success -->
        <?php if (session()->getFlashdata('success')): ?>

            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>

        <?php endif; ?>


        <!-- Form Ubah Password -->
        <form
            action="<?= base_url('pelatihan/update-password') ?>"
            method="post"
        >

            <!-- Password Lama -->
            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Password Lama
                </label>

                <input
                    type="password"
                    name="password_lama"
                    class="form-control"
                    placeholder="Masukkan password lama"
                    required
                >

            </div>


            <!-- Password Baru -->
            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password_baru"
                    class="form-control"
                    placeholder="Masukkan password baru"
                    required
                >

            </div>


            <!-- Konfirmasi Password -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Konfirmasi Password Baru
                </label>

                <input
                    type="password"
                    name="konfirmasi_password"
                    class="form-control"
                    placeholder="Ulangi password baru"
                    required
                >

            </div>


            <hr>


            <!-- Tombol -->
            <div class="d-flex gap-2 mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    🔒 Ubah Password
                </button>


                <a
                    href="<?= base_url('pelatihan/pengaturan') ?>"
                    class="btn btn-light"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

<?= $this->endSection() ?>