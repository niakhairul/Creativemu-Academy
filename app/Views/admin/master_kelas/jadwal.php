<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?> - Creativemu Academy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-purple: #6f42c1;
            --hover-purple: #59359a;
            --light-purple: #f3eef9;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(111, 66, 193, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 6px 25px rgba(111, 66, 193, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-purple), #8557d9);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        .btn-purple {
            background-color: var(--primary-purple);
            color: white;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-purple:hover {
            background-color: var(--hover-purple);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-purple {
            border-color: var(--primary-purple);
            color: var(--primary-purple);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-outline-purple:hover {
            background-color: var(--primary-purple);
            color: white;
            transform: translateY(-2px);
        }

        .table-hover tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-purple);
        }

        .info-box {
            background: white;
            border-left: 5px solid var(--primary-purple);
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .modal-content {
            border-radius: 12px;
            border: none;
        }

        .breadcrumb-item a {
            color: var(--primary-purple);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .badge-purple {
            background-color: var(--light-purple);
            color: var(--primary-purple);
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container-fluid px-4 py-4">
    
    <!-- Header & Tombol Kembali -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Manajemen Jadwal & Materi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard'); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/master-kelas'); ?>">Master Kelas</a></li>
                    <li class="breadcrumb-item active text-muted">Jadwal & Materi</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('admin/master-kelas'); ?>" class="btn btn-outline-purple shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Master Kelas
            </a>
        </div>
    </div>

    <!-- Informasi Detail Kelas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="info-box d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 text-white" style="background-color: var(--primary-purple);">
                        <i class="fas fa-chalkboard-user fa-lg"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-bold">Nama Kelas</span>
                        <h5 class="mb-0 fw-bold text-dark"><?= esc($detail_kelas['nama_kelas'] ?? '-'); ?></h5>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 text-white" style="background-color: #8557d9;">
                        <i class="fas fa-user-tie fa-lg"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-bold">Mentor Pengampu</span>
                        <h5 class="mb-0 fw-bold text-dark"><?= esc($detail_kelas['nama_mentor'] ?? '-'); ?></h5>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle p-3 text-white" style="background-color: #a17fe0;">
                        <i class="fas fa-info-circle fa-lg"></i>
                    </div>
                    <div>
                        <span class="text-muted small text-uppercase fw-bold">Status Kelas</span>
                        <div>
                            <?php 
                                $status =$detail_kelas['status'] ?? 'Aktif';
                                $badgeClass = (strtolower($status) == 'aktif') ? 'bg-success' : 'bg-secondary';
                            ?>
                            <span class="badge <?= $badgeClass; ?> px-3 py-2 mt-1"><?= esc($status); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesan Flashdata -->
    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('pesan'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tabel Daftar Jadwal -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="fw-bold text-white">
                <i class="fas fa-calendar-alt me-2"></i> Daftar Jadwal Pertemuan
            </div>
            <!-- Tombol Trigger Modal Tambah oleh Admin -->
            <button type="button" class="btn btn-light btn-sm fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal" style="color: var(--primary-purple);">
                <i class="fas fa-plus me-1"></i> Tambah Jadwal
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead style="background-color: var(--light-purple); color: var(--primary-purple);">
                        <tr>
                            <th width="5%" class="py-3 ps-4">No</th>
                            <th class="py-3">Pertemuan</th>
                            <th class="py-3">Tanggal & Jam</th>
                            <th class="py-3">Ruangan / Link GMeet <br><span class="badge bg-info text-dark font-monospace" style="font-size: 10px;">Diisi Admin</span></th>
                            <th class="py-3">Materi Pokok <span class="badge bg-secondary font-monospace" style="font-size: 10px;">Diisi Mentor</span></th>
                            <th class="py-3">Berkas & Link <span class="badge bg-secondary font-monospace" style="font-size: 10px;">Diisi Mentor</span></th>
                            <th width="12%" class="text-center py-3 pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($jadwal) && is_array($jadwal)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($jadwal as$row): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?= $no++; ?></td>
                                    <td>
                                        <span class="badge badge-purple px-2 py-1">
                                            Ke-<?= esc($row['pertemuan_ke'] ?? '-'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= esc($row['tanggal_kbm'] ?? '-'); ?></div>
                                        <div class="text-muted small">
                                            <i class="far fa-clock me-1"></i> 
                                            <?= esc($row['waktu_mulai'] ?? '-'); ?> - <?= esc($row['waktu_selesai'] ?? '-'); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['ruangan_atau_link'])): ?>
                                            <?php if (filter_var($row['ruangan_atau_link'], FILTER_VALIDATE_URL)): ?>
                                                <a href="<?= esc($row['ruangan_atau_link']); ?>" target="_blank" class="btn btn-sm btn-outline-success py-0 px-2" title="Klik untuk bergabung">
                                                    <i class="fas fa-video me-1"></i> Gabung GMeet
                                                </a>
                                            <?php else: ?>
                                                <span class="text-dark fw-semibold"><?= esc($row['ruangan_atau_link']); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic small">Belum diisi Admin</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= !empty($row['materi']) ? esc($row['materi']) : '<span class="text-muted fst-italic small">Belum diisi Mentor</span>'; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <?php if (!empty($row['file_pdf'])): ?>
                                                <a href="<?= base_url('uploads/materi/' . esc($row['file_pdf'])); ?>" target="_blank" class="btn btn-sm btn-outline-danger py-0 px-2" title="Download PDF">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($row['link_materi'])): ?>
                                                <a href="<?= esc($row['link_materi']); ?>" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" title="Link Materi (GDrive)">
                                                    <i class="fas fa-link"></i> Link
                                                </a>
                                            <?php endif; ?>
                                            <?php if (empty($row['file_pdf']) && empty($row['link_materi'])): ?>
                                                 <span class="text-muted fst-italic small">Belum ada berkas</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-center pe-4">
                                        <!-- Tombol Edit Admin -->
                                        <button type="button" class="btn btn-warning btn-sm text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditJadwal<?= $row['id_jadwal'] ?? ''; ?>" title="Edit Jadwal (Admin)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Tombol Input Materi Mentor -->
                                        <button type="button" class="btn btn-info btn-sm text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInputMateri<?= $row['id_jadwal'] ?? ''; ?>" title="Input Materi (Mentor)">
                                            <i class="fas fa-chalkboard"></i>
                                        </button>
                                        <!-- Tombol Hapus Admin -->
                                        <a href="<?= base_url('admin/jadwal/hapus/' . ($row['id_jadwal'] ?? '')); ?>" class="btn btn-danger btn-sm shadow-sm" onclick="return confirm('Yakin ingin menghapus jadwal pertemuan ke-<?= esc($row['pertemuan_ke']); ?> ini?')" title="Hapus Jadwal">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-2 text-purple opacity-50"></i>
                                    <p class="mb-0">Belum ada data jadwal untuk kelas ini.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL SECTION ================= -->

<!-- 1. Modal Tambah Jadwal (Oleh Admin) -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <form action="<?= base_url('admin/jadwal/tambah'); ?>" method="post">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_kelas" value="<?= esc($detail_kelas['id_kelas'] ?? ''); ?>">
                <div class="modal-header text-white" style="background-color: var(--primary-purple);">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i> Tambah Jadwal Baru (Admin)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="pertemuan_ke" class="form-label fw-semibold">Pertemuan Ke</label>
                        <input type="number" class="form-control" id="pertemuan_ke" name="pertemuan_ke" min="1" required placeholder="Misal: 1">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_kbm" class="form-label fw-semibold">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal_kbm" name="tanggal_kbm" required>
                        </div>
                    </div>

                            <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="waktu_mulai" class="form-label fw-semibold">Jam Mulai</label>
                            <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
                        </div>
                        <div class="col-md-6">
                            <label for="waktu_selesai" class="form-label fw-semibold">Jam Berakhir</label>
                            <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="ruangan_atau_link" class="form-label fw-semibold">Ruangan / Link GMeet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ruangan_atau_link" name="ruangan_atau_link" placeholder="Masukkan nama ruangan atau URL Google Meet" required>
                        <small class="text-muted">Link akan otomatis menjadi tombol gabung jika berupa URL.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple px-4">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Modal Edit Jadwal (Oleh Admin) -->
<!-- Loop untuk membuat modal unik untuk setiap baris jadwal -->
<?php if (!empty($jadwal) && is_array($jadwal)): ?>
    <?php foreach ($jadwal as $row): ?>
    <div class="modal fade" id="modalEditJadwal<?= $row['id_jadwal']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <form action="<?= base_url('admin/jadwal/update/' . $row['id_jadwal']); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id_kelas" value="<?= esc($row['id_kelas']); ?>">
                    <div class="modal-header text-white" style="background-color: #ffc107;"> <!-- Warna kuning warning -->
                        <h5 class="modal-title text-white"><i class="fas fa-edit me-2"></i> Edit Jadwal (Admin)</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="pertemuan_ke_edit<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Pertemuan Ke</label>
                            <input type="number" class="form-control" id="pertemuan_ke_edit<?= $row['id_jadwal']; ?>" name="pertemuan_ke" value="<?= esc($row['pertemuan_ke']); ?>" min="1" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tanggal_kbm_edit<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal_kbm_edit<?= $row['id_jadwal']; ?>" name="tanggal_kbm" value="<?= esc($row['tanggal_kbm']); ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="waktu_mulai_edit<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Jam Mulai</label>
                                <input type="time" class="form-control" id="waktu_mulai_edit<?= $row['id_jadwal']; ?>" name="waktu_mulai" value="<?= esc($row['waktu_mulai']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="waktu_selesai_edit<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Jam Berakhir</label>
                                <input type="time" class="form-control" id="waktu_selesai_edit<?= $row['id_jadwal']; ?>" name="waktu_selesai" value="<?= esc($row['waktu_selesai']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ruangan_atau_link_edit<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Ruangan / Link GMeet</label>
                            <input type="text" class="form-control" id="ruangan_atau_link_edit<?= $row['id_jadwal']; ?>" name="ruangan_atau_link" value="<?= esc($row['ruangan_atau_link']); ?>" placeholder="Masukkan nama ruangan atau URL Google Meet">
                        </div>
                         <div class="alert alert-warning py-2 small mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i> Data materi dan berkas (di bawah) tidak diubah di sini.
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-white px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>


<!-- 3. Modal Input Materi (Oleh Mentor) -->
<?php if (!empty($jadwal) && is_array($jadwal)): ?>
    <?php foreach ($jadwal as $row): ?>
    <div class="modal fade" id="modalInputMateri<?= $row['id_jadwal']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-info border-3">
                <form action="<?= base_url('mentor/jadwal/update-materi/' . $row['id_jadwal']); ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="modal-header text-white bg-info">
                        <h5 class="modal-title text-white"><i class="fas fa-chalkboard me-2"></i> Input Materi & Berkas (Area Mentor)</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-light border mb-3">
                            <div class="fw-bold text-dark">Pertemuan Ke-<?= esc($row['pertemuan_ke']); ?></div>
                            <div class="text-muted small"><?= esc($row['tanggal_kbm']); ?> | <?= esc($row['waktu_mulai']); ?> - <?= esc($row['waktu_selesai']); ?></div>
                        </div>

                        <div class="mb-3">
                            <label for="materi<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Materi Pokok</label>
                            <input type="text" class="form-control" id="materi<?= $row['id_jadwal']; ?>" name="materi" value="<?= esc($row['materi']); ?>" placeholder="Misal: Pengenalan UI/UX Design">
                        </div>
                        
                        <div class="mb-3">
                            <label for="link_materi<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Link Materi (Google Drive/Lainnya)</label>
                            <input type="url" class="form-control" id="link_materi<?= $row['id_jadwal']; ?>" name="link_materi" value="<?= esc($row['link_materi']); ?>" placeholder="https://drive.google.com/...">
                        </div>

                        <div class="mb-3">
                            <label for="file_pdf<?= $row['id_jadwal']; ?>" class="form-label fw-semibold">Upload File PDF (Opsional)</label>
                            <?php if (!empty($row['file_pdf'])): ?>
                                <div class="d-flex align-items-center text-danger small mb-1 border p-1 rounded bg-light w-50">
                                    <i class="fas fa-file-pdf me-1"></i>
                                    <span class="text-truncate"><?= esc(substr($row['file_pdf'], 0, 20)) . '...'; ?></span>
                                    <a href="<?= base_url('uploads/materi/' . esc($row['file_pdf'])); ?>" target="_blank" class="ms-2 text-primary">[Lihat]</a>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="file_pdf<?= $row['id_jadwal']; ?>" name="file_pdf" accept="application/pdf">
                            <small class="text-muted">Maksimal 5MB. Format .pdf.</small>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-info text-white px-4">Simpan Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>