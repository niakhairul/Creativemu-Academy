<?= $this->extend('layouts/dashboard_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-xxl-10">
            
            <!-- Header Banner Minimalis Modern -->
            <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white overflow-hidden">
                <div class="card-body p-4 p-md-4 text-center border-bottom bg-light">
                    <img src="<?= base_url('assets/img/logo_creativemu_academy.jpg') ?>" width="75" class="mb-2 rounded-circle shadow-sm bg-white p-1">
                    <h3 class="fw-bold text-dark mb-1">Formulir Pendaftaran Pelatihan</h3>
                    <p class="text-muted small mb-0">Silakan periksa detail kelas dan lengkapi data diri Anda untuk bergabung bersama Creativemu Academy.</p>
                </div>
            </div>

            <form action="<?= base_url('pelatihan/daftar') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="kelas_id" value="<?= $kelas['id_kelas'] ?? $kelas['id'] ?? ''; ?>">

                <div class="row g-4">
                    
                    <!-- KOLOM KIRI: DETAIL KELAS -->
                    <div class="col-lg-5">
                        <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 20px;">
                            <?php 
                                $fotoKelas = !empty($kelas['thumbnail']) ? $kelas['thumbnail'] : (!empty($kelas['foto']) ? $kelas['foto'] : 'default.jpg');
                                $tipeKelas = $kelas['tipe_kelas'] ?? 'Online'; 
                            ?>
                            <div class="position-relative" style="height: 170px; overflow: hidden; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                <img src="<?= base_url('uploads/kelas/' . $fotoKelas); ?>" alt="Banner Kelas" class="w-100 h-100 object-fit-cover">
                                <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 bg-dark bg-opacity-75 backdrop-blur shadow-sm rounded-pill" style="font-size: 0.75rem;">
                                    <?= esc($kelas['kategori'] ?? 'Umum'); ?>
                                </span>
                            </div>

                            <div class="card-body p-4">
                                <!-- NAMA KELAS & BADGE OFFLINE/ONLINE -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold text-dark mb-0"><?= esc($kelas['nama_kelas'] ?? $kelas['nama'] ?? '-') ?></h5>
    
                                    <?php 
                                    $tipe = !empty($kelas['tipe_kelas']) ? $kelas['tipe_kelas'] : 'Online'; 
                                    ?>
                                    
                                    <span class="badge bg-primary text-white px-3 py-2 fw-bold" style="font-size: 0.75rem;">
                                        <?= esc(ucfirst($tipe)) ?>
                                    </span>
                                </div>

                                <hr class="text-muted opacity-25 my-3">

                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm flex-shrink-0" style="width: 42px; height: 42px;">
                                        <?php 
                                            $mentorName = $kelas['nama_mentor'] ?? $kelas['mentor'] ?? $kelas['pengajar'] ?? 'M';
                                            echo strtoupper(substr($mentorName, 0, 1));
                                        ?>
                                    </div>
                                    <div>
                                        <span class="text-muted small d-block" style="font-size: 0.75rem;">Mentor Pengampu</span>
                                        <span class="fw-bold text-dark text-truncate d-block" style="max-width: 220px;"><?= esc($mentorName) ?></span>
                                    </div>
                                </div>

                                <div class="row g-2 mb-3 bg-light p-3 rounded-4">
                                    <div class="col-6">
                                        <span class="text-muted small d-block" style="font-size: 0.7rem;">Mulai Pelatihan</span>
                                        <span class="fw-semibold text-dark small">
                                            <i class="far fa-calendar-alt text-primary me-1"></i>
                                            <?= esc($kelas['tanggal_mulai_kelas'] ?? $kelas['awal_pelatihan'] ?? '-') ?>
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted small d-block" style="font-size: 0.7rem;">Jumlah Pertemuan</span>
                                        <span class="fw-semibold text-dark small">
                                            <i class="fas fa-rotate text-purple me-1"></i>
                                            <?= esc($kelas['jumlah_pertemuan'] ?? '-') ?>x Sesi
                                        </span>
                                    </div>
                                </div>

                                <!-- Ringkasan Harga -->
                                <div class="border-top pt-3 mt-2">
                                    <span class="text-muted small d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Rincian Harga</span>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="small text-muted">Reguler:</span>
                                            <h6 class="fw-bold text-success mb-0">Rp <?= number_format($kelas['harga_reguler'] ?? 0, 0, ',', '.') ?></h6>
                                        </div>
                                        <div class="text-end">
                                            <span class="small text-muted">Privat:</span>
                                            <h6 class="fw-bold text-primary mb-0">Rp <?= number_format($kelas['harga_privat'] ?? 0, 0, ',', '.') ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: DATA PESERTA & PEMBAYARAN -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm border-0 rounded-4">
                            <div class="card-body p-4 p-md-5">
                                
                                <!-- DATA PESERTA -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">Data Diri Peserta</h5>
                                </div>
                                <p class="text-muted small mb-4">Pastikan data di bawah ini benar dan dapat diubah jika diperlukan.</p>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control rounded-3" id="nama" name="nama" value="<?= esc($user['nama'] ?? '') ?>" required placeholder="Nama Lengkap">
                                    <label for="nama">Nama Lengkap</label>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control rounded-3" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>" required placeholder="Email">
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="no_hp" name="no_hp" value="<?= esc($user['no_hp'] ?? '') ?>" required placeholder="Nomor HP">
                                            <label for="no_hp">Nomor HP / WhatsApp</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Jenis Kelamin & Pendidikan Terakhir -->
                                <div class="row g-2 mb-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select class="form-select rounded-3" id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki" <?= (isset($user['jenis_kelamin']) && $user['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                                <option value="Perempuan" <?= (isset($user['jenis_kelamin']) && $user['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control rounded-3" id="pendidikan_terakhir" name="pendidikan_terakhir" value="<?= esc($user['pendidikan_terakhir'] ?? '') ?>" required placeholder="Pendidikan Terakhir">
                                            <label for="pendidikan_terakhir">Pendidikan Terakhir</label>
                                        </div>
                                    </div>
                                </div>

                                <hr class="text-muted opacity-25 my-4">

                                <!-- PILIHAN KELAS: REGULER ATAU PRIVAT -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">Pilih Jenis Kelas</h5>
                                </div>
                                <p class="text-muted small mb-3">Pilih apakah Anda ingin mengikuti kelas Reguler atau Privat.</p>

                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="jenis_pendaftaran" id="kelas_reguler" value="Reguler" required>
                                        <label class="btn btn-outline-light text-dark border w-100 py-3 rounded-4 fw-semibold shadow-sm text-start px-3 d-flex align-items-center gap-3 option-card" for="kelas_reguler">
                                            <i class="fas fa-users fa-lg text-success"></i>
                                            <div>
                                                <span class="d-block fw-bold">Reguler</span>
                                                <small class="text-success fw-bold" style="font-size: 0.75rem;">Rp <?= number_format($kelas['harga_reguler'] ?? 0, 0, ',', '.') ?></small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="jenis_pendaftaran" id="kelas_privat" value="Privat">
                                        <label class="btn btn-outline-light text-dark border w-100 py-3 rounded-4 fw-semibold shadow-sm text-start px-3 d-flex align-items-center gap-3 option-card" for="kelas_privat">
                                            <i class="fas fa-user-shield fa-lg text-primary"></i>
                                            <div>
                                                <span class="d-block fw-bold">Privat</span>
                                                <small class="text-primary fw-bold" style="font-size: 0.75rem;">Rp <?= number_format($kelas['harga_privat'] ?? 0, 0, ',', '.') ?></small>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <hr class="text-muted opacity-25 my-4">

                                <!-- PEMBAYARAN -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-2 me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">Metode Pembayaran</h5>
                                </div>
                                <p class="text-muted small mb-3">Pilih opsi transaksi yang Anda inginkan.</p>

                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="pembayaran" id="bayar_cod" value="COD" onclick="togglePembayaran()" required>
                                        <label class="btn btn-outline-light text-dark border w-100 py-3 rounded-4 fw-semibold shadow-sm text-start px-3 d-flex align-items-center gap-3 option-card" for="bayar_cod">
                                            <i class="fas fa-handshake fa-lg text-warning"></i>
                                            <div>
                                                <span class="d-block fw-bold">COD</span>
                                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Bayar di Tempat</small>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-6">
                                        <input type="radio" class="btn-check" name="pembayaran" id="bayar_transfer" value="Transfer" onclick="togglePembayaran()">
                                        <label class="btn btn-outline-light text-dark border w-100 py-3 rounded-4 fw-semibold shadow-sm text-start px-3 d-flex align-items-center gap-3 option-card" for="bayar_transfer">
                                            <i class="fas fa-university fa-lg text-info"></i>
                                            <div>
                                                <span class="d-block fw-bold">Transfer</span>
                                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Transfer Bank</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Informasi Rekening Transfer -->
                                <div id="rekening" class="alert alert-info border-0 shadow-sm rounded-4 p-3 mb-3" style="display:none; background-color: #e3f2fd;">
                                    <h6 class="fw-bold mb-2 text-dark"><i class="fas fa-info-circle me-1 text-primary"></i> Silakan Transfer ke Rekening Berikut:</h6>
                                    <p class="mb-1 text-dark small">Bank BCA: <strong>1234567890</strong></p>
                                    <p class="mb-0 text-dark small">Atas Nama: <strong>Creativemu Academy</strong></p>
                                </div>

                                <!-- Informasi COD 1x24 Jam -->
                                <div id="infoCod" class="alert alert-warning border-0 shadow-sm rounded-4 p-3 mb-3" style="display:none; background-color: #fff8e1;">
                                    <h6 class="fw-bold mb-2 text-dark"><i class="fas fa-clock me-1 text-warning"></i> Ketentuan Pembayaran COD</h6>
                                    <p class="mb-0 text-dark small">Anda memilih pembayaran di tempat (COD). Harap melakukan pelunasan dan <strong>upload bukti/konfirmasi pembayaran dalam waktu maksimal 1x24 jam</strong> setelah pendaftaran dikirimkan.</p>
                                </div>

                                <!-- Upload Bukti Pembayaran -->
                                <div id="uploadBukti" class="mb-4" style="display:none;">
                                    <label class="form-label fw-semibold small text-muted" id="labelUpload">Upload Bukti Pembayaran</label>
                                    <input type="file" name="bukti" class="form-control rounded-3 py-2">
                                    <div class="form-text" style="font-size: 0.75rem;">Format file: JPG, PNG, atau JPEG (Maks. 2MB).</div>
                                </div>

                                <!-- TOMBOL SUBMIT DIPERBAIKI (Hapus tag <form> di dalamnya) -->
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm mt-2">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Pendaftaran Sekarang
                                </button>

                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<!-- Tambahan Styling Kecil untuk Efek Radio Button Interaktif -->
<style>
    .btn-check:checked + .option-card {
        border-color: var(--bs-primary) !important;
        background-color: rgba(13, 110, 253, 0.04) !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
    }
</style>

<!-- Script Toggle Pembayaran -->
<script>
function togglePembayaran() {
    const selected = document.querySelector('input[name="pembayaran"]:checked');
    const rekening = document.getElementById('rekening');
    const infoCod = document.getElementById('infoCod');
    const upload = document.getElementById('uploadBukti');
    const labelUpload = document.getElementById('labelUpload');

    if (!selected) return;

    if (selected.value === "Transfer") {
        rekening.style.display = "block";
        infoCod.style.display = "none";
        upload.style.display = "block";
        labelUpload.textContent = "Upload Bukti Transfer Pembayaran";
    } else if (selected.value === "COD") {
        rekening.style.display = "none";
        infoCod.style.display = "block";
        upload.style.display = "block";
        labelUpload.textContent = "Upload Bukti / Konfirmasi Pendaftaran COD (Maks. 1x24 Jam)";
    } else {
        rekening.style.display = "none";
        infoCod.style.display = "none";
        upload.style.display = "none";
    }
}
</script>

<?= $this->endSection() ?>