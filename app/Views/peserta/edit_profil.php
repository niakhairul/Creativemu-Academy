<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Edit Profil
        </h2>

        <p class="text-muted mb-0">
            Perbarui informasi pribadi dan foto profil Anda.
        </p>
    </div>


    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4 p-md-5">

            <!-- Header Profil -->
            <div class="d-flex align-items-center mb-4">

                <div>
                    <h5 class="fw-bold mb-1">
                        Informasi Profil
                    </h5>

                    <p class="text-muted mb-0">
                        Pastikan data yang Anda masukkan sudah benar.
                    </p>
                </div>

            </div>


            <form action="<?= base_url('pelatihan/update-profil') ?>"
                  method="post"
                  enctype="multipart/form-data">

                <?= csrf_field() ?>


                <!-- FOTO PROFIL -->
                <div class="text-center mb-4">

                    <?php if (!empty($user['foto_profil'])): ?>

                        <img src="<?= base_url('uploads/profil/' . $user['foto_profil']) ?>"
                             class="rounded-circle shadow-sm"
                             width="120"
                             height="120"
                             style="object-fit: cover;">

                    <?php else: ?>

                        <img src="<?= base_url('assets/img/logo creativemu academy.jpg') ?>"
                             class="rounded-circle shadow-sm"
                             width="120"
                             height="120"
                             style="object-fit: cover;">

                    <?php endif; ?>

                    <div class="mt-3">

                        <label class="form-label fw-semibold">
                            Foto Profil
                        </label>

                        <input
                            type="file"
                            name="foto"
                            class="form-control"
                            accept=".jpg,.jpeg,.png">

                        <small class="text-muted">
                            JPG, JPEG, atau PNG. Maksimal 2 MB.
                        </small>

                    </div>

                </div>


                <hr class="my-4">


                <!-- NAMA -->
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        class="form-control form-control-lg"
                        value="<?= esc($user['nama'] ?? '') ?>"
                        placeholder="Masukkan nama lengkap"
                        required>

                </div>


                <!-- EMAIL & NO HP -->
                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control form-control-lg"
                            value="<?= esc($user['email'] ?? '') ?>"
                            placeholder="Masukkan email"
                            required>

                    </div>


                    <div class="col-md-6 mb-4">

                        <label class="form-label fw-semibold">
                            Nomor HP / WhatsApp
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            class="form-control form-control-lg"
                            value="<?= esc($user['no_hp'] ?? '') ?>"
                            placeholder="08xxxxxxxxxx">

                    </div>

                </div>


                <!-- JENIS KELAMIN -->
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Jenis Kelamin
                    </label>

                    <select name="jenis_kelamin"
                            class="form-select form-select-lg">

                        <option value="">
                            Pilih Jenis Kelamin
                        </option>

                        <option value="Laki-laki"
                            <?= ($user['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' ?>>
                            Laki-laki
                        </option>

                        <option value="Perempuan"
                            <?= ($user['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' ?>>
                            Perempuan
                        </option>

                    </select>

                </div>


                <!-- ASAL SEKOLAH -->
                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Asal Sekolah / Kampus
                    </label>

                    <input
                        type="text"
                        name="asal_sekolah"
                        class="form-control form-control-lg"
                        value="<?= esc($user['asal_sekolah'] ?? '') ?>"
                        placeholder="Masukkan asal sekolah/kampus">

                </div>


                <!-- TOMBOL -->
                <div class="d-flex gap-2 mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary px-4">

                        💾 Simpan Perubahan

                    </button>


                    <a
                        href="<?= base_url('pelatihan/pengaturan') ?>"
                        class="btn btn-light border px-4">

                        Batal

                    </a>

                </div>


            </form>

        </div>

    </div>

</div>

<?= $this->endSection() ?>