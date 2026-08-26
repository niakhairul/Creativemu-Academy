<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #22133c;
            --sidebar-active-gradient: linear-gradient(135deg, #794bc4 0%, #5931a0 100%);
            --sidebar-text: #c8bfe7;
            --primary-purple: #794bc4;
            --light-purple: #f4f0fc;
            --dark-purple: #1e0f33;
        }

        body { font-family: 'Poppins', sans-serif; background-color: #f7f5fd; margin: 0; }
        
        #sidebar {
            width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-header { padding: 25px 20px; background: rgba(0, 0, 0, 0.25); text-align: center; }
        .nav-link { color: var(--sidebar-text); padding: 12px 18px; display: flex; align-items: center; border-radius: 12px; margin: 0 14px 6px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: var(--sidebar-active-gradient); color: #ffffff; }
        
        #main-content { margin-left: 275px; padding: 35px; }
        .top-navbar { background: #ffffff; padding: 22px 30px; border-radius: 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
        }
        .btn-purple:hover { color: #ffffff; opacity: 0.9; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/img/logo-creativemu.png'); ?>" alt="Logo" class="img-fluid" style="max-width: 150px;">
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie me-3"></i> Dashboard</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book me-3"></i> Master Kelas</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user me-3"></i> Mentor</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link"><i class="fas fa-users me-3"></i> Data Peserta</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/validasi'); ?>" class="nav-link"><i class="fas fa-clipboard-check me-3"></i> Validasi</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/angket'); ?>" class="nav-link active"><i class="fas fa-award me-3"></i> Angket</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-certificate me-3"></i> Sertifikat</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/laporan'); ?>" class="nav-link"><i class="fas fa-file-lines me-3"></i> Laporan</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear me-3"></i> Pengaturan</a></li>
            <li class="nav-item mt-4"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket me-3"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <div class="top-navbar">
            <div>
                <h3 class="fw-bold text-dark mb-1">Buat Konfigurasi Angket Baru</h3>
                <p class="text-muted mb-0">Tentukan daftar pertanyaan untuk menilai kinerja mentor maupun fasilitas tempat.</p>
            </div>
            <a href="<?= base_url('admin/angket'); ?>" class="btn btn-light border px-3 py-2 rounded-pill">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="card p-4 shadow-sm border-0">
            <form action="<?= base_url('admin/angket/simpan'); ?>" method="POST">
                <?= csrf_field(); ?>

                <div class="card p-4 shadow-sm border-0">
    <form action="<?= base_url('admin/angket/simpan'); ?>" method="POST">
        <?= csrf_field(); ?>

        <!-- TAMBAHKAN INPUT JUDUL ANGKET DI SINI -->
        <div class="form-group">
    <label>Judul Angket</label>
    <input type="text" name="judul_angket" class="form-control" placeholder="Contoh: Evaluasi Belajar Kelas Web" required>
</div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">Pilih Mentor Terkait</label>
                <select name="id_mentor" class="form-select" required>
                    <option value="">-- Pilih Mentor --</option>
                    <?php if (!empty($mentor)) : foreach ($mentor as $m) : ?>
                        <option value="<?= $m['id_mentor']; ?>"><?= esc($m['nama_mentor']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Pilih Kelas Pelatihan</label>
                <select name="id_kelas" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    <?php if (!empty($kelas)) : foreach ($kelas as $k) : ?>
                        <option value="<?= $k['id_kelas']; ?>"><?= esc($k['nama_kelas']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
        </div>
        
        <!-- Sisa kode form selanjutnya tetap sama... -->

                <hr class="my-4">
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold mb-1" style="color: var(--primary-purple);">Daftar Pertanyaan Angket</h5>
                        <p class="text-muted small mb-0">Admin dapat menambah atau mengurangi daftar pertanyaan sesuai kebutuhan.</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary fw-semibold px-3 py-2" id="tambah-pertanyaan-btn" style="border-radius: 10px;">
                        <i class="fas fa-plus me-1"></i> Tambah Pertanyaan
                    </button>
                </div>

                <!-- Container Pertanyaan Dinamis -->
                <div id="list-pertanyaan">
                    <!-- Baris Pertanyaan Pertama (Default) -->
                    <div class="pertanyaan-item mb-3 p-3 rounded bg-light border position-relative">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge text-white" style="background-color: var(--primary-purple);">Pertanyaan #1</span>
                            <button type="button" class="btn btn-sm btn-outline-danger hapus-item" style="display:none;"><i class="fas fa-trash"></i></button>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="form-label small fw-bold text-muted">Kategori Penilaian:</label>
                                <select name="kategori[]" class="form-select form-select-sm" required>
                                    <option value="mentor">Kinerja Mentor</option>
                                    <option value="tempat">Tempat / Fasilitas</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-2">
                                <label class="form-label small fw-bold text-muted">Isi Pertanyaan:</label>
                                <input type="text" name="pertanyaan[]" class="form-control form-control-sm" placeholder="Contoh: Ketepatan waktu mentor / Kebersihan kelas..." required>
                            </div>
                        </div>
                        <small class="text-muted" style="font-size: 0.78rem;"><i class="fas fa-info-circle me-1"></i> Peserta akan menjawab poin ini menggunakan skala 1 - 5 bintang.</small>
                    </div>
                </div>

                <div class="mb-4 mt-4">
                    <label class="form-label fw-bold">Kotak Kritik & Saran Peserta</label>
                    <textarea name="saran_peserta" class="form-control" rows="3" placeholder="Aktifkan kolom teks saran bebas di bagian bawah formulir angket..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/angket'); ?>" class="btn btn-light px-4 border">Batal</a>
                    <button type="submit" class="btn btn-purple px-5">Simpan Angket</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script JavaScript untuk Tambah/Hapus Baris Pertanyaan Dinamis -->
    <script>
        document.getElementById('tambah-pertanyaan-btn').addEventListener('click', function() {
            const container = document.getElementById('list-pertanyaan');
            const items = container.getElementsByClassName('pertanyaan-item');
            const newIndex = items.length + 1;

            const newItem = document.createElement('div');
            newItem.className = 'pertanyaan-item mb-3 p-3 rounded bg-light border position-relative';
            newItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge text-white" style="background-color: var(--primary-purple);">Pertanyaan #${newIndex}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger hapus-item"><i class="fas fa-trash"></i></button>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label small fw-bold text-muted">Kategori Penilaian:</label>
                        <select name="kategori[]" class="form-select form-select-sm" required>
                            <option value="mentor">Kinerja Mentor</option>
                            <option value="tempat">Tempat / Fasilitas</option>
                        </select>
                    </div>
                    <div class="col-md-8 mb-2">
                        <label class="form-label small fw-bold text-muted">Isi Pertanyaan:</label>
                        <input type="text" name="pertanyaan[]" class="form-control form-control-sm" placeholder="Tuliskan isi pertanyaan..." required>
                    </div>
                </div>
                <small class="text-muted" style="font-size: 0.78rem;"><i class="fas fa-info-circle me-1"></i> Peserta akan menjawab poin ini menggunakan skala 1 - 5 bintang.</small>
            `;

            container.appendChild(newItem);
            updateHapusButtons();
        });

        document.getElementById('list-pertanyaan').addEventListener('click', function(e) {
            if (e.target.closest('.hapus-item')) {
                e.target.closest('.pertanyaan-item').remove();
                reorderPertanyaan();
            }
        });

        function updateHapusButtons() {
            const items = document.querySelectorAll('.pertanyaan-item');
            items.forEach((item) => {
                const deleteBtn = item.querySelector('.hapus-item');
                deleteBtn.style.display = (items.length > 1) ? 'block' : 'none';
            });
        }

        function reorderPertanyaan() {
            const items = document.querySelectorAll('.pertanyaan-item');
            items.forEach((item, index) => {
                item.querySelector('.badge').textContent = `Pertanyaan #${index + 1}`;
            });
            updateHapusButtons();
        }
    </script>
</body>
</html>