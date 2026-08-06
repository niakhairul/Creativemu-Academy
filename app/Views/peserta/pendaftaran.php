<?= $this->extend('layouts/auth_template') ?>

<?= $this->section('content') ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>"
                             width="160"
                             class="mb-3">

                        <h3 class="fw-bold text-primary">
                            Form Pendaftaran Pelatihan
                        </h3>

                        <p class="text-muted">
                            Silakan lengkapi data berikut.
                        </p>

                    </div>

                    <form>

                        <h5 class="mb-3">Data Peserta</h5>

                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text"
                                   class="form-control"
                                   value="Nama Peserta"
                                   readonly>
                        </div>

                        <div class="mb-4">
                            <label>Email</label>
                            <input type="email"
                                   class="form-control"
                                   value="email@gmail.com"
                                   readonly>
                        </div>

                        <hr>

                        <h5 class="mb-3 mt-4">Data Pelatihan</h5>

                        <div class="mb-3">

                            <label>Pilih Kelas</label>

                            <select class="form-select">

                                <option selected disabled>
                                    -- Pilih Kelas --
                                </option>

                                <option>
                                    Digital Marketing
                                </option>

                                <option>
                                    UI / UX Design
                                </option>

                                <option>
                                    Web Development
                                </option>

                                <option>
                                    Mobile Development
                                </option>

                            </select>

                        </div>

                        <div class="mb-4">

                            <label class="mb-2">
                                Metode Pembelajaran
                            </label>

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="metode">

                                <label class="form-check-label">
                                    Online
                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="metode">

                                <label class="form-check-label">
                                    Offline
                                </label>

                            </div>

                        </div>

                        <hr>

                        <h5 class="mb-3 mt-4">
                            Metode Pembayaran
                        </h5>

                        <div class="form-check">

                            <input class="form-check-input"
                                   type="radio"
                                   name="pembayaran"
                                   value="cod"
                                   onclick="toggleUpload()">

                            <label class="form-check-label">
                                COD (Bayar di Tempat)
                            </label>

                        </div>

                        <div class="form-check mb-3">

                            <input class="form-check-input"
                                   type="radio"
                                   name="pembayaran"
                                   value="transfer"
                                    onclick="toggleUpload()">

                            <label class="form-check-label">
                                Transfer Bank
                            </label>

                        </div>
                        <small class="text-muted d-block mb-3">
    Jika memilih Transfer, silakan upload bukti pembayaran.
</small>

                        <div class="mb-4" id="uploadBukti" style="display:none;">

    <label class="form-label">
        Upload Bukti Pembayaran
    </label>

    <input
        type="file"
        class="form-control">

</div>

                        <button class="btn btn-primary w-100">

                            Kirim Pendaftaran

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
<script>

function toggleUpload(){

    const pembayaran =
        document.querySelector('input[name="pembayaran"]:checked').value;

    const upload =
        document.getElementById('uploadBukti');

    if(pembayaran == "transfer"){

        upload.style.display = "block";

    }else{

        upload.style.display = "none";

    }

}

</script>
<?= $this->endSection() ?>