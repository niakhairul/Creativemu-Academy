<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --accent-purple: #9b6fd9;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
        }

        html, body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            color: #2b263b;
            overflow-x: hidden;
            margin: 0;
            font-size: 14px;
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f7f5fd; }
        ::-webkit-scrollbar-thumb { background: #b293f0; border-radius: 10px; }

        /* --- Main Content Area --- */
        #main-content {
            margin-left: 240px;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        @media (max-width: 992px) {
            #main-content { margin-left: 70px; padding: 15px; }
        }

        @media (max-width: 576px) {
            #main-content { padding: 10px; }
        }

        /* --- Top Navbar --- */
        .top-navbar {
            background: #ffffff;
            padding: 16px 24px;
            border-radius: 14px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        .admin-profile { display: flex; align-items: center; gap: 12px; }
        .admin-profile img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary-purple); }
        .admin-info { display: flex; flex-direction: column; }
        .admin-info h6 { margin: 0; font-weight: 600; color: var(--dark-purple); font-size: 0.88rem; }
        .admin-info small { color: #8c83a5; font-size: 0.78rem; }

        /* --- Content Cards --- */
        .content-card, .card-custom {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(121, 75, 196, 0.04);
            margin-bottom: 20px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        /* --- Form Styling --- */
        .form-label {
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.8rem !important;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 8px 12px;
            border: 1px solid #e2d9f3;
            font-size: 0.85rem !important;
            transition: all 0.3s ease;
            background-color: #fcfbfe;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(121, 75, 196, 0.1);
            background-color: #ffffff;
        }

        .btn {
            font-size: 0.82rem !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            border-radius: 8px;
        }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            box-shadow: 0 3px 10px rgba(121, 75, 196, 0.25);
            transition: all 0.25s ease;
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(121, 75, 196, 0.35);
            color: white;
        }

        /* STANDAR TYPOGRAPHY */
        .dash-header h3 { font-size: 1.25rem !important; font-weight: 700 !important; color: var(--dark-purple); margin-bottom: 4px; }
        .dash-header p { font-size: 0.8rem !important; color: #8c83a5; margin-bottom: 0; }
        .card-title-custom, h4.card-title-custom { font-size: 1rem !important; font-weight: 700 !important; color: var(--dark-purple); display: flex; align-items: center; gap: 8px; }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- === SIDEBAR MENU === -->
        <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

    <!-- === MAIN CONTENT === -->
    <div id="main-content">

        <!-- === TOP NAVBAR === -->
        <div class="top-navbar">
            <div class="dash-header">
                <h3>Edit Angket</h3>
                <p>Ubah dan perbarui data pertanyaan angket yang tersedia.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" id="current-date" style="font-size: 0.82rem; font-weight: 600; color: #794bc4 !important;">
                    Memuat tanggal...
                </div>
                <div class="admin-profile">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                    <div class="admin-info">
                        <h6><?= esc(session()->get('nama')); ?></h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- === FORM EDIT CONTENT === -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="content-card">
                        <h4 class="card-title-custom mb-4">
                            <i class="fas fa-pen-to-square"></i> Form Edit Angket
                        </h4>

                        <form action="<?= base_url('admin/angket/update/' . $id); ?>" method="post">
    <!-- Judul Angket -->
    <div class="mb-3">
        <label class="form-label">Judul Angket</label>
        <input type="text" name="judul_angket" class="form-control" value="<?= esc($angket['judul_angket'] ?? ''); ?>" required>
    </div>

    <!-- Kelas (Opsional) -->
    <div class="mb-4">
        <label class="form-label">Berlaku untuk Kelas</label>
        <select name="id_kelas" class="form-select">
            <option value="">-- Berlaku Untuk Semua Kelas (Global) --</option>
            <?php foreach ($kelas as $k) : ?>
                <option value="<?= $k['id_kelas']; ?>" <?= ($k['id_kelas'] == $angket['id_kelas']) ? 'selected' : ''; ?>>
                    <?= esc($k['nama_kelas']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <hr class="my-4">

    <!-- Pertanyaan -->
    <div class="border rounded p-3 mb-4" style="background-color: #fcfbfe; border-color: #e2d9f3 !important;">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Kategori Penilaian</label>
                <select name="kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Customer Insight" <?= ($angket['kategori'] === 'Customer Insight') ? 'selected' : ''; ?>>Customer Insight</option>
                    <option value="Penilaian Instruktur" <?= ($angket['kategori'] === 'Penilaian Instruktur') ? 'selected' : ''; ?>>Penilaian Instruktur</option>
                    <option value="Penilaian Lembaga" <?= ($angket['kategori'] === 'Penilaian Lembaga') ? 'selected' : ''; ?>>Penilaian Lembaga</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Isi Pertanyaan</label>
                <input type="text" name="pertanyaan" class="form-control" value="<?= esc($angket['pertanyaan']); ?>" required>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Jenis Jawaban</label>
                <select name="tipe" class="form-select" id="jenis_jawaban" required>
                    <option value="rating" <?= ($angket['tipe'] === 'rating') ? 'selected' : ''; ?>>Rating (Bintang 1-4)</option>
                    <option value="pilihan" <?= ($angket['tipe'] === 'pilihan') ? 'selected' : ''; ?>>Pilihan Ganda</option>
                    <option value="essay" <?= ($angket['tipe'] === 'essay') ? 'selected' : ''; ?>>Essay / Paragraf</option>
                </select>
            </div>

            <div class="col-md-12 mb-3" id="opsi_jawaban_container" style="display: <?= ($angket['tipe'] === 'pilihan') ? 'block' : 'none'; ?>;">
                <label class="form-label">Opsi Jawaban</label>
                <div id="opsi_list">
                    <?php
                    $opsi = json_decode($angket['opsi_jawaban'], true) ?? [];
                    if (empty($opsi)) $opsi = ['', '']; // Default 2 fields
                    foreach ($opsi as $o): ?>
                        <div class="input-group mb-2">
                            <input type="text" name="opsi_jawaban[]" class="form-control" value="<?= esc($o); ?>" placeholder="Teks opsi...">
                            <button type="button" class="btn btn-outline-danger hapus-opsi"><i class="fas fa-trash"></i></button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-outline-primary mt-2 btn-sm" id="tambah_opsi">+ Tambah Opsi</button>
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="mb-4">
        <label class="form-label">Status Pertanyaan</label>
        <select name="status" class="form-select">
            <option value="Aktif" <?= ($angket['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
            <option value="Nonaktif" <?= ($angket['status'] == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
        </select>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="<?= base_url('admin/angket'); ?>" class="btn btn-light border">Batal</a>
        <button type="submit" class="btn btn-purple">Simpan Perubahan</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jenisJawaban = document.getElementById('jenis_jawaban');
    const opsiContainer = document.getElementById('opsi_jawaban_container');
    const opsiList = document.getElementById('opsi_list');
    const btnTambahOpsi = document.getElementById('tambah_opsi');

    jenisJawaban.addEventListener('change', function() {
        if (this.value === 'pilihan') {
            opsiContainer.style.display = 'block';
        } else {
            opsiContainer.style.display = 'none';
        }
    });

    btnTambahOpsi.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = '<input type="text" name="opsi_jawaban[]" class="form-control" placeholder="Teks opsi..."><button type="button" class="btn btn-outline-danger hapus-opsi"><i class="fas fa-trash"></i></button>';
        opsiList.appendChild(div);
    });

    opsiList.addEventListener('click', function(e) {
        if (e.target.closest('.hapus-opsi')) {
            e.target.closest('.input-group').remove();
        }
    });
});
</script>
                    </div>
                </div>
            </div>
        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


