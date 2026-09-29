<?= $this->extend('admin/layout/template'); ?>
<?= $this->section('content'); ?>

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

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f5fd;
            overflow-x: hidden;
            margin: 0;
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f7f5fd; }
        ::-webkit-scrollbar-thumb { background: #b293f0; border-radius: 10px; }

        /* --- Sidebar Styling --- */
        

        

        

        
        
        
        

        

        

        

        

        /* --- Main Content Area --- */
        

        /* --- Top Navbar --- */
        .top-navbar {
            background: #ffffff;
            padding: 22px 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(121, 75, 196, 0.04);
        }

        .dash-header h3 {
            font-weight: 800;
            color: var(--dark-purple);
            font-size: 1.6rem;
            letter-spacing: -0.5px;
        }
        
        .dash-header p {
            color: #8c83a5;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-profile img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid var(--primary-purple);
            box-shadow: 0 4px 12px rgba(121, 75, 196, 0.2);
        }

        .admin-info h6 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-purple);
            font-size: 0.98rem;
        }

        .admin-info small {
            color: #8c83a5;
            font-size: 0.78rem;
        }

        /* --- Content Cards --- */
        .content-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(121, 75, 196, 0.04);
            margin-bottom: 30px;
            border: 1px solid rgba(121, 75, 196, 0.05);
        }

        /* --- Form Styling --- */
        .form-label {
            font-weight: 600;
            color: var(--dark-purple);
            font-size: 0.88rem;
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1.5px solid #e2d9f3;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: #fcfbfe;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 4px rgba(121, 75, 196, 0.1);
            background-color: #ffffff;
        }

        .btn-purple {
            background: var(--sidebar-active-gradient);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 6px 20px rgba(121, 75, 196, 0.3);
            transition: all 0.3s ease;
        }

        .btn-purple:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(121, 75, 196, 0.4);
            color: #ffffff;
        }

        @keyframes mainFadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
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
                <h3>Tambah Master Kelas</h3>
                <p>Formulir penambahan data pelatihan baru ke sistem.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <div class="admin-profile">
                    <img src="<?= base_url('assets/img/' . (session()->get('foto_profil') ? session()->get('foto_profil') : 'admin-profile.jpg')); ?>" alt="Foto Profil">
                    <div class="admin-info">
                        <h6><?= esc(session()->get('nama')); ?></h6>
                        <small>Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- === FORM KELAS CONTAINER === -->
        <div class="container-fluid px-0">
            <div class="card content-card mb-4">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="fw-bold text-dark"><i class="fas fa-plus-circle me-2 text-purple"></i> Form Tambah Kelas</h5>
                </div>
                <div class="card-body">
                    
                    <!-- FORM UTAMA -->
                    <form action="<?= base_url('admin/kelas/store'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <!-- Nama Kelas -->
                        <div class="mb-3">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" class="form-control" name="nama_kelas" required placeholder="Contoh: Fullstack Web Development">
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label class="form-label">Kategori Kelas</label>
                            <input type="text" class="form-control" name="kategori" required placeholder="Contoh: Pemrograman, Desain, atau Bisnis">
                        </div>

                        <!-- Pilihan Jenis Kelas (Online / Offline) -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pelatihan</label>
                                <select class="form-select" id="jenis_kelas" name="jenis_kelas" required onchange="toggleJenisKelas()">
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="wrapper-lokasi-media">
                                <label class="form-label" id="label-lokasi-media">Link Zoom / Platform Online</label>
                                <input type="text" class="form-control" name="lokasi_pelatihan" id="input-lokasi-media" placeholder="Contoh: https://zoom.us/j/xxxxxx">
                            </div>
                        </div>

                        <!-- Thumbnail / Foto Kelas -->
                        <div class="mb-3">
                            <label class="form-label">Foto / Thumbnail Kelas</label>
                            <input type="file" class="form-control" name="thumbnail" accept="image/*" required>
                        </div>

                        <!-- Jadwal Pertemuan Dinamis -->
                        <div class="mb-3">
                            <label class="form-label">Daftar Jadwal Sesi Pertemuan</label>
                            <div id="container-jadwal">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">Pertemuan 1</span>
                                    <input type="datetime-local" class="form-control" name="tanggal_pertemuan[]">
                                    <button type="button" class="btn btn-outline-danger" onclick="hapusBaris(this)" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-purple mt-2" id="btn-tambah-pertemuan">
                                <i class="fas fa-plus me-1"></i> Tambah Sesi Pertemuan
                            </button>
                            <small class="text-muted d-block mt-1">*(Tanggal bisa dikosongkan jika belum pasti, dan bisa diisi menyusul)*</small>
                        </div>

                        <!-- Kapasitas & Status -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kapasitas Peserta</label>
                                <input type="number" class="form-control" name="kapasitas" required placeholder="Contoh: 25">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Kelas</label>
                                <select class="form-select" name="status">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Selesai">Non Aktif</option>
                                    <option value="Aktif">Draft</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-purple mt-3">Simpan Kelas</button>
                    </form>

                </div>
            </div>
        </div>

    </div>

    <!-- JavaScript untuk Dinamis Form (Jenis Kelas & Tambah Pertemuan) -->
    <script>
        function toggleJenisKelas() {
            const jenis = document.getElementById('jenis_kelas').value;
            const label = document.getElementById('label-lokasi-media');
            const input = document.getElementById('input-lokasi-media');

            if (jenis === 'Online') {
                label.innerText = 'Link Zoom / Google Meet';
                input.placeholder = 'Contoh: https://zoom.us/j/xxxxxx';
            } else {
                label.innerText = 'Lokasi / Ruangan (Offline)';
                input.placeholder = 'Contoh: Lab Komputer Lt. 2 / Gedung A';
            }
        }

        let jumlahPertemuan = 1;
        document.getElementById('btn-tambah-pertemuan').addEventListener('click', function() {
            jumlahPertemuan++;
            const container = document.getElementById('container-jadwal');
            
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <span class="input-group-text">Pertemuan ${jumlahPertemuan}</span>
                <input type="datetime-local" class="form-control" name="tanggal_pertemuan[]">
                <button type="button" class="btn btn-outline-danger" onclick="hapusBaris(this)">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(div);
        });

        function hapusBaris(button) {
            button.closest('.input-group').remove();
        }
    </script>
</body>
</html>

<?= $this->endSection(); ?>


