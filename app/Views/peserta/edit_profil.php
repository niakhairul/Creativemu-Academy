<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold">Edit Profil</h2>
        <p class="text-muted">
            Perbarui informasi pribadi Anda.
        </p>
    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form action="<?= base_url('pelatihan/update-profil') ?>" method="post">

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Lengkap
                    </label>

                    <input
    type="text"
    name="nama"
    class="form-control"
    value="<?= esc($user['nama'] ?? '') ?>"
    placeholder="Masukkan nama lengkap"
>
                </div>


                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <input
    type="email"
    name="email"
    class="form-control"
    value="<?= esc($user['email'] ?? '') ?>"
    placeholder="Masukkan email"
>
                </div>


                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nomor HP
                    </label>

                    <input
    type="text"
    name="no_hp"
    class="form-control"
    value="<?= esc($user['no_hp'] ?? '') ?>"
    placeholder="08xxxxxxxxxx"
>
                </div>


                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Asal Sekolah / Kampus
                    </label>

                    <input
    type="text"
    name="asal_sekolah"
    class="form-control"
    value="<?= esc($user['asal_sekolah'] ?? '') ?>"
    placeholder="Masukkan asal sekolah/kampus"
>
                </div>


                <div class="d-flex gap-2">

                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Perubahan
                    </button>

                    <a href="<?= base_url('pelatihan/profil') ?>"
                       class="btn btn-light">
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<?= $this->endSection() ?>