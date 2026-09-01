<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="fw-bold mb-1">
        Ubah Password
    </h2>

    <p class="text-muted mb-0">
        Perbarui password akun Anda untuk menjaga keamanan akun.
    </p>
</div>


<div class="card border-0 shadow-sm rounded-4">

    <div class="card-body p-4 p-md-5">

        <!-- Header -->
        <div class="text-center mb-4">

            <div class="d-inline-flex align-items-center justify-content-center
                        rounded-circle bg-primary-subtle text-primary mb-3"
                 style="width: 70px; height: 70px; font-size: 30px;">
                🔐
            </div>

            <h4 class="fw-bold mb-2">
                Keamanan Akun
            </h4>

            <p class="text-muted mb-0">
                Gunakan password yang kuat dan mudah Anda ingat.
            </p>

        </div>


        <!-- Pesan Error -->
        <?php if (session()->getFlashdata('error')): ?>

            <div class="alert alert-danger rounded-3">
                ⚠️ <?= session()->getFlashdata('error') ?>
            </div>

        <?php endif; ?>


        <!-- Pesan Success -->
        <?php if (session()->getFlashdata('success')): ?>

            <div class="alert alert-success rounded-3">
                ✅ <?= session()->getFlashdata('success') ?>
            </div>

        <?php endif; ?>


        <!-- Form -->
        <form
            action="<?= base_url('pelatihan/update-password') ?>"
            method="post"
        >

            <!-- Password Lama -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Password Lama
                </label>

                <div class="input-group">

                    <input
                        type="password"
                        name="password_lama"
                        id="passwordLama"
                        class="form-control"
                        placeholder="Masukkan password lama"
                        required
                    >

                    <button
                        type="button"
                        class="btn btn-light border"
                        onclick="togglePassword('passwordLama', this)"
                    >
                        👁️
                    </button>

                </div>

            </div>


            <!-- Password Baru -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Password Baru
                </label>

                <div class="input-group">

                    <input
                        type="password"
                        name="password_baru"
                        id="passwordBaru"
                        class="form-control"
                        placeholder="Masukkan password baru"
                        required
                    >

                    <button
                        type="button"
                        class="btn btn-light border"
                        onclick="togglePassword('passwordBaru', this)"
                    >
                        👁️
                    </button>

                </div>

                <small class="text-muted">
                    Gunakan minimal 8 karakter agar lebih aman.
                </small>

            </div>


            <!-- Konfirmasi Password -->
            <div class="mb-4">

                <label class="form-label fw-semibold">
                    Konfirmasi Password Baru
                </label>

                <div class="input-group">

                    <input
                        type="password"
                        name="konfirmasi_password"
                        id="konfirmasiPassword"
                        class="form-control"
                        placeholder="Ulangi password baru"
                        required
                    >

                    <button
                        type="button"
                        class="btn btn-light border"
                        onclick="togglePassword('konfirmasiPassword', this)"
                    >
                        👁️
                    </button>

                </div>

            </div>


            <!-- Tips -->
            <div class="p-3 rounded-3 bg-light mb-4">

                <div class="fw-semibold mb-2">
                    💡 Tips Password
                </div>

                <small class="text-muted">
                    Jangan gunakan password yang mudah ditebak dan
                    jangan membagikan password kepada orang lain.
                </small>

            </div>


            <hr class="my-4">


            <!-- Tombol -->
            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary px-4"
                >
                    🔒 Ubah Password
                </button>

                <a
                    href="<?= base_url('pelatihan/pengaturan') ?>"
                    class="btn btn-light border px-4"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>


<!-- Script lihat password -->
<script>
function togglePassword(id, button) {

    const input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
        button.innerHTML = "🙈";
    } else {
        input.type = "password";
        button.innerHTML = "👁️";
    }

}
</script>


<?= $this->endSection() ?>