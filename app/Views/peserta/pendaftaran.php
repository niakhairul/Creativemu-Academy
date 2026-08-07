<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card shadow border-0 rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>"
                             width="120"
                             class="mb-3">

                        <h2 class="fw-bold text-primary">
                            Form Pendaftaran Pelatihan
                        </h2>

                        <p class="text-muted">
                            Lengkapi data berikut untuk mendaftar kelas.
                        </p>

                    </div>

                    <form action="<?= base_url('pelatihan/daftar') ?>"
                          method="post"
                          enctype="multipart/form-data">

                        <?= csrf_field(); ?>

                        <input type="hidden"
                               name="kelas_id"
                               value="<?= $kelas['id']; ?>">

                        <!-- ===================== -->
                        <!-- DATA PESERTA -->
                        <!-- ===================== -->

                        <h4 class="fw-bold mb-3">
                            Data Peserta
                        </h4>

                        <div class="mb-3">

                            <label class="form-label">
                                Nama
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= esc($user['nama']) ?>"
                                   readonly>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   class="form-control"
                                   value="<?= esc($user['email']) ?>"
                                   readonly>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Nomor HP
                            </label>

                            <input type="text"
                                   class="form-control"
                                   value="<?= esc($user['no_hp']) ?>"
                                   readonly>

                        </div>

                        <hr>

                        <!-- ===================== -->
                        <!-- DATA KELAS -->
                        <!-- ===================== -->

                        <h4 class="fw-bold mb-3">
                            Data Kelas
                        </h4>

                        <div class="card bg-light border-0 mb-4">

                            <div class="card-body">

                                <p>
                                    <strong>Nama Kelas :</strong><br>
                                    <?= esc($kelas['nama_kelas']) ?>
                                </p>

                                <p>
                                    <strong>Mentor :</strong><br>
                                    <?= esc($kelas['mentor']) ?>
                                </p>

                                <p>
                                    <strong>Metode :</strong>
                                    <?= esc($kelas['metode']) ?>
                                </p>

                                <p>
                                    <strong>Jadwal :</strong><br>
                                    <?= esc($kelas['jadwal']) ?>
                                </p>

                                <p>
                                    <strong>Jam :</strong>
                                    <?= esc($kelas['jam']) ?>
                                </p>

                            </div>

                        </div>

                        <!-- ===================== -->
                        <!-- METODE PEMBELAJARAN -->
                        <!-- ===================== -->

                        <h4 class="fw-bold mb-3">
                            Metode Pembelajaran
                        </h4>

                        <div class="form-check">

                            <input class="form-check-input"
                                   type="radio"
                                   name="metode"
                                   value="Online"
                                   required>

                            <label class="form-check-label">
                                Online
                            </label>

                        </div>

                        <div class="form-check mb-4">

                            <input class="form-check-input"
                                   type="radio"
                                   name="metode"
                                   value="Offline">

                            <label class="form-check-label">
                                Offline
                            </label>

                        </div>

                        <hr>

                        <!-- ===================== -->
                        <!-- PEMBAYARAN -->
                        <!-- ===================== -->

                        <h4 class="fw-bold mb-3">
                            Metode Pembayaran
                        </h4>

                        <div class="form-check">

                            <input class="form-check-input"
                                   type="radio"
                                   name="pembayaran"
                                   value="COD"
                                   onclick="togglePembayaran()"
                                   required>

                            <label class="form-check-label">
                                COD (Bayar di Tempat)
                            </label>

                        </div>

                        <div class="form-check mb-3">

                            <input class="form-check-input"
                                   type="radio"
                                   name="pembayaran"
                                   value="Transfer"
                                   onclick="togglePembayaran()">

                            <label class="form-check-label">
                                Transfer Bank
                            </label>

                        </div>

                        <div id="rekening"
                             class="alert alert-info"
                             style="display:none;">

                            <strong>Transfer ke :</strong><br>

                            Bank BCA<br>

                            1234567890<br>

                            a.n. Creativemu Academy

                        </div>

                        <div id="uploadBukti"
                             style="display:none;"
                             class="mb-4">

                            <label class="form-label">
                                Upload Bukti Pembayaran
                            </label>

                            <input type="file"
                                   name="bukti"
                                   class="form-control">

                        </div>

                        <button class="btn btn-primary w-100">

                            Kirim Pendaftaran

                        </button>

                                    </form>

                <script>
                function togglePembayaran() {

                    let pembayaran = document.querySelector('input[name="pembayaran"]:checked').value;

                    let rekening = document.getElementById('rekening');
                    let upload = document.getElementById('uploadBukti');

                    if (pembayaran == "Transfer") {

                        rekening.style.display = "block";
                        upload.style.display = "block";

                    } else {

                        rekening.style.display = "none";
                        upload.style.display = "none";

                    }

                }
                </script>

            </div>

        </div>

    </div>

</div>
</div>

<script>

function togglePembayaran(){

    const pembayaran =
        document.querySelector('input[name="pembayaran"]:checked').value;

    const upload =
        document.getElementById('uploadBukti');

    const rekening =
        document.getElementById('rekening');

    if(pembayaran == "Transfer"){

        upload.style.display = "block";
        rekening.style.display = "block";

    }else{

        upload.style.display = "none";
        rekening.style.display = "none";

    }

}

</script>

<?= $this->endSection() ?>