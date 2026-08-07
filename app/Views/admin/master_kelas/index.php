<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <!-- Google Fonts & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-pastel: #f8f6fc;
            --purple-main: #8c7ae6;
            --purple-dark: #6c5ce7;
            --purple-light: #e0dcf8;
            --text-dark: #2d3436;
            --text-muted: #636e72;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-pastel);
            color: var(--text-dark);
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #ffffff;
            border-right: 1px solid #e9e5f5;
            padding: 24px 16px;
            box-shadow: 4px 0 20px rgba(140, 122, 230, 0.05);
        }

        .brand-logo {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--purple-dark);
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            border-radius: 12px;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            font-weight: 600;
        }

        /* Main Content Styling */
        .main-content {
            margin-left: 260px;
            padding: 32px 40px;
        }

        /* Custom Cards & Containers */
        .content-card {
            background: #ffffff;
            border: 1px solid #e9e5f5;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        }

        .card-header-custom {
            font-weight: 700;
            color: var(--purple-dark);
            font-size: 1.15rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Form Styling */
        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            border: 1px solid #e9e5f5;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--purple-main);
            box-shadow: 0 0 0 0.25rem rgba(140, 122, 230, 0.2);
        }

        /* Buttons */
        .btn-purple {
            background-color: var(--purple-dark);
            color: #ffffff;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            background-color: var(--purple-main);
            color: #ffffff;
        }

        .btn-pastel-purple {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            border-radius: 8px;
            font-weight: 600;
            border: none;
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .btn-pastel-purple:hover {
            background-color: var(--purple-main);
            color: #ffffff;
        }

        /* Table Styling */
        .table-custom {
            vertical-align: middle;
        }

        .table-custom thead th {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            font-weight: 600;
            border: none;
            padding: 12px 16px;
        }

        .table-custom tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #e9e5f5;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-active {
            background-color: #e3f9e5;
            color: #1f9254;
        }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <div class="sidebar">
        <div class="brand-logo">
            <i class="fa-solid fa-graduation-cap fs-4"></i>
            <span>Creativemu</span>
        </div>
        <nav>
            <a href="/admin/dashboard" class="nav-link-custom <?= (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="/admin/master-kelas" class="nav-link-custom <?= (uri_string() == 'admin/master-kelas') ? 'active' : ''; ?>">
                <i class="fa-solid fa-book-bookmark"></i> Master Kelas
            </a>
            <a href="/admin/data-peserta" class="nav-link-custom <?= (uri_string() == 'admin/data-peserta') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i> Data Peserta
            </a>
            <a href="/admin/validasi" class="nav-link-custom <?= (uri_string() == 'admin/validasi') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-check"></i> Validasi Pendaftaran
            </a>
            <a href="/admin/sertifikat" class="nav-link-custom <?= (uri_string() == 'admin/sertifikat') ? 'active' : ''; ?>">
                <i class="fa-solid fa-award"></i> Sertifikat
            </a>
            <a href="/admin/laporan" class="nav-link-custom <?= (uri_string() == 'admin/laporan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-lines"></i> Laporan
            </a>
            <a href="/admin/pengaturan" class="nav-link-custom <?= (uri_string() == 'admin/pengaturan') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear"></i> Pengaturan
            </a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1" style="color: var(--purple-dark);">Master Kelas</h3>
                <p class="text-muted mb-0">Kelola pembuatan kelas baru, data kelas, serta penjadwalan KBM.</p>
            </div>
        </div>

        <!-- 1. Form Tambah Kelas -->
        <div class="content-card">
            <div class="card-header-custom">
                <span><i class="fa-solid fa-square-plus me-2"></i>Tambah Kelas Baru</span>
            </div>
            <form action="/admin/master-kelas/simpan" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" name="nama_kelas" placeholder="Contoh: Graphic Design Fundamentals" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pilih Mentor</label>
                        <select class="form-select" name="id_mentor" required>
                            <option value="" selected disabled>-- Pilih Mentor --</option>
                            <option value="1">Sarah Amelia, S.Ds.</option>
                            <option value="2">Budi Santoso, M.Kom.</option>
                            <option value="3">Rian Hidayat</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kategori</label>
                        <select class="form-select" name="kategori" required>
                            <option value="" selected disabled>-- Pilih Kategori --</option>
                            <option value="Design">Design & Arts</option>
                            <option value="Programming">Programming</option>
                            <option value="Marketing">Digital Marketing</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Kapasitas Maksimal Siswa</label>
                        <input type="number" class="form-control" name="kuota" placeholder="Contoh: 30" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Kelas</label>
                        <select class="form-select" name="status">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Ringkas Kelas</label>
                        <textarea class="form-control" name="deskripsi" rows="2" placeholder="Tuliskan deskripsi singkat mengenai silabus atau gambaran kelas..."></textarea>
                    </div>
                    <div class="col-12 text-end mt-3">
                        <button type="reset" class="btn btn-light me-2">Reset</button>
                        <button type="submit" class="btn btn-purple"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Kelas</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Tabel Data Kelas -->
        <div class="content-card">
            <div class="card-header-custom">
                <span><i class="fa-solid fa-list me-2"></i>Data Kelas Terdaftar</span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Mentor</th>
                            <th>Kategori</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th class="text-center">Aksi & Jadwal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <strong>UI/UX Design Masterclass</strong><br>
                                <small class="text-muted">ID Kelas: KLS-001</small>
                            </td>
                            <td>Sarah Amelia, S.Ds.</td>
                            <td>Design</td>
                            <td>25 / 30 Siswa</td>
                            <td><span class="badge-status badge-active">Aktif</span></td>
                            <td class="text-center">
                                <button class="btn btn-pastel-purple me-1" data-bs-toggle="modal" data-bs-target="#modalKelolaJadwal" onclick="setKelasData('UI/UX Design Masterclass')">
                                    <i class="fa-solid fa-calendar-days me-1"></i> Kelola Jadwal
                                </button>
                                <button class="btn btn-sm btn-outline-warning me-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <strong>Web Development dengan CodeIgniter 4</strong><br>
                                <small class="text-muted">ID Kelas: KLS-002</small>
                            </td>
                            <td>Budi Santoso, M.Kom.</td>
                            <td>Programming</td>
                            <td>18 / 25 Siswa</td>
                            <td><span class="badge-status badge-active">Aktif</span></td>
                            <td class="text-center">
                                <button class="btn btn-pastel-purple me-1" data-bs-toggle="modal" data-bs-target="#modalKelolaJadwal" onclick="setKelasData('Web Development dengan CodeIgniter 4')">
                                    <i class="fa-solid fa-calendar-days me-1"></i> Kelola Jadwal
                                </button>
                                <button class="btn btn-sm btn-outline-warning me-1"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- 3. Modal Kelola Jadwal -->
    <div class="modal fade" id="modalKelolaJadwal" tabindex="-1" aria-labelledby="modalKelolaJadwalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background-color: var(--purple-light); border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold" id="modalKelolaJadwalLabel" style="color: var(--purple-dark);">
                        <i class="fa-solid fa-calendar-check me-2"></i>Kelola Jadwal - <span id="namaKelasModal">Kelas</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Form Tambah Pertemuan Jadwal -->
                    <form action="/admin/master-kelas/simpan-jadwal" method="post" class="mb-4">
                        <h6 class="fw-bold mb-3" style="color: var(--purple-dark);">Tambah Pertemuan Baru</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Hari</label>
                                <select class="form-select" name="hari" required>
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" name="jam_mulai" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" name="jam_selesai" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Materi / Topik Pertemuan</label>
                                <input type="text" class="form-control" name="topik" placeholder="Contoh: Pengenalan Wireframing & Layouting" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ruangan / Link Meet</label>
                                <input type="text" class="form-control" name="ruangan" placeholder="Lab 1 / Zoom Link">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-purple"><i class="fa-solid fa-plus me-1"></i> Tambahkan Jadwal</button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4" style="color: #e9e5f5;">

                    <!-- Daftar Jadwal yang Sudah Ada -->
                    <h6 class="fw-bold mb-3" style="color: var(--purple-dark);">Jadwal Pertemuan Terdaftar</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Hari & Waktu</th>
                                    <th>Topik / Pertemuan</th>
                                    <th>Ruangan / Platform</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="fw-semibold">Senin</span>, 09:00 - 11:00</td>
                                    <td>Pertemuan 1: User Research & Personas</td>
                                    <td>Lab Komputer 02</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="fw-semibold">Rabu</span>, 09:00 - 11:00</td>
                                    <td>Pertemuan 2: High-Fidelity UI Design</td>
                                    <td>Google Meet (Online)</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9e5f5;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function untuk mengoper nama kelas ke title modal
        function setKelasData(namaKelas) {
            document.getElementById('namaKelasModal').innerText = namaKelas;
        }
    </script>
</body>
</html>