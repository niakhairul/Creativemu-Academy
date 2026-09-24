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
        }
        body { font-family: 'Poppins', sans-serif; background-color: #f7f5fd; }
        #sidebar { width: 275px; height: 100vh; position: fixed; top: 0; left: 0; background-color: var(--sidebar-bg); color: var(--sidebar-text); z-index: 1000; overflow-y: auto; }
        .sidebar-header { padding: 25px 20px; background: rgba(0, 0, 0, 0.25); text-align: center; }
        #sidebar .sidebar-header img {
    width: 240px;
    height: 95px;
    object-fit: cover;
    border-radius: 10px;
    filter: drop-shadow(0 2px 8px rgba(121, 75, 196, 0.4));
    transition: transform 0.3s ease;
}
        .nav-link { color: var(--sidebar-text); padding: 12px 18px; display: flex; align-items: center; border-radius: 12px; margin: 0 14px 6px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: var(--sidebar-active-gradient); color: #ffffff; }

        #main-content { margin-left: 275px; padding: 35px; }
        .content-card { background: #ffffff; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .text-purple { color: var(--primary-purple); }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
    <img src="<?= base_url('assets/img/logo_creativemu.jpg'); ?>" alt="Creativemu Academy" class="img-fluid">
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="<?= base_url('admin/dashboard'); ?>" class="nav-link"><i class="fas fa-chart-pie me-3"></i> Dashboard</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/master-kelas'); ?>" class="nav-link"><i class="fas fa-book me-3"></i> Master Kelas</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/mentor'); ?>" class="nav-link"><i class="fas fa-chalkboard-user me-3"></i> Instruktur</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/data-peserta'); ?>" class="nav-link"><i class="fas fa-users me-3"></i> Data Peserta</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/validasi'); ?>" class="nav-link"><i class="fas fa-clipboard-check me-3"></i> Validasi</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/buku-induk'); ?>" class="nav-link"><i class="fas fa-book-open me-3"></i> Buku Induk</a></li>

            <!-- Menu Angket dengan Sub-menu -->
            <li class="nav-item">
                <a href="<?= base_url('admin/angket'); ?>" class="nav-link"><i class="fas fa-award me-3"></i> Angket</a>
                <ul class="nav flex-column ms-4">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/hasil_angket'); ?>" class="nav-link active bg-dark bg-opacity-25">
                            <i class="fas fa-poll-h me-2"></i> Hasil Angket
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item"><a href="<?= base_url('admin/sertifikat'); ?>" class="nav-link"><i class="fas fa-certificate me-3"></i> Sertifikat</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/laporan'); ?>" class="nav-link"><i class="fas fa-file-lines me-3"></i> Laporan</a></li>
            <li class="nav-item"><a href="<?= base_url('admin/pengaturan'); ?>" class="nav-link"><i class="fas fa-gear me-3"></i> Pengaturan</a></li>
            <li class="nav-item mt-4"><a href="<?= base_url('logout'); ?>" class="nav-link text-danger"><i class="fas fa-right-from-bracket me-3"></i> Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Hasil Angket Siswa</h3>
                <p class="text-muted mb-0">Daftar rekapitulasi penilaian peserta.</p>
            </div>
            <a href="<?= base_url('admin/angket'); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-dark m-0"><i class="fas fa-poll-h me-2 text-purple"></i> Data Jawaban</h5>
                <span class="badge bg-purple text-white px-3 py-2">Total Respon: <?= !empty($hasil) ? count($hasil) : 0; ?></span>
            </div>

                        <form action="<?= base_url('admin/hasil_angket'); ?>" method="get" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-bold" style="color: var(--dark-purple); font-size: 0.85rem;">Cari Peserta/Kelas</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Nama peserta atau kelas..." value="<?= esc($filters['search'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label for="kelas" class="form-label fw-bold" style="color: var(--dark-purple); font-size: 0.85rem;">Kelas</label>
                    <select name="kelas" id="kelas" class="form-select">
                        <option value="">Semua kelas</option>
                        <?php foreach (($kelasOptions ?? []) as $k): ?>
                            <option value="<?= esc($k['nama_kelas']); ?>" <?= (($filters['kelas'] ?? '') === $k['nama_kelas']) ? 'selected' : ''; ?>><?= esc($k['nama_kelas']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tanggal" class="form-label fw-bold" style="color: var(--dark-purple); font-size: 0.85rem;">Tanggal Pengisian</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= esc($filters['tanggal'] ?? '') ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn text-white w-100" style="background: var(--sidebar-active-gradient);"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="<?= base_url('admin/hasil_angket') ?>" class="btn btn-outline-secondary w-100"><i class="fas fa-undo me-1"></i> Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Kelas yang Diikuti</th>
                            <th>Judul Angket</th>
                            <th>Jml Jawaban</th>
                            <th>Tanggal Pengisian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($hasil) && is_array($hasil)) : ?>
                            <?php $no = 1; foreach ($hasil as $index => $h): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><div class="fw-bold"><?= esc($h['nama_siswa'] ?? 'Tanpa Nama'); ?></div></td>
                                <td><span class="badge bg-light text-purple border"><?= esc($h['kelas_diikuti'] ?? '-'); ?></span></td>
                                <td><?= esc($h['judul_angket'] ?? '-'); ?></td>
                                <td><span class="badge bg-primary rounded-pill"><?= esc($h['jumlah_jawaban']); ?></span></td>
                                <td><?= date('d M Y, H:i', strtotime($h['tanggal_pengisian'])); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#detailModal<?= $index ?>">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Belum ada data tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modals Section -->
        <?php if (!empty($hasil) && is_array($hasil)) : ?>
            <?php foreach ($hasil as $index => $h): ?>
            <!-- Modal Detail Angket -->
            <div class="modal fade" id="detailModal<?= $index ?>" tabindex="-1" aria-labelledby="detailModalLabel<?= $index ?>" aria-hidden="true">
              <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                  <div class="modal-header text-white" style="background: var(--primary-purple);">
                    <h5 class="modal-title" id="detailModalLabel<?= $index ?>">Detail Angket: <?= esc($h['nama_siswa']) ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body p-4">

                      <div class="mb-4">
                          <h6 class="text-muted mb-1">Kelas yang Diikuti</h6>
                          <div class="fw-bold fs-5 text-dark"><?= esc($h['kelas_diikuti']) ?></div>
                      </div>

                      <?php
                      // Kelompokkan jawaban berdasarkan kategori untuk modal
                      $groupedJawaban = [];
                      foreach ($h['jawaban'] as $j) {
                          $kategori = $j['kategori'] ?: 'Lainnya';
                          $groupedJawaban[$kategori][] = $j;
                      }
                      ?>

                      <?php foreach ($groupedJawaban as $kategori => $jawabans): ?>
                          <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2 text-purple"><?= esc($kategori) ?></h5>

                          <?php foreach ($jawabans as $j): ?>
                              <div class="mb-3 bg-light p-3 rounded border-start border-4 border-primary shadow-sm">
                                  <div class="fw-bold mb-2 text-dark"><?= esc($j['pertanyaan']) ?></div>

                                  <?php if (isset($j['tipe']) && $j['tipe'] == 'rating'): ?>
                                      <div class="text-warning fs-5">
                                          <?php
                                          $rating = (int) $j['jawaban'];
                                          for($i=1; $i<=4; $i++) {
                                              if($i <= $rating) echo '<i class="fas fa-star"></i>';
                                              else echo '<i class="far fa-star"></i>';
                                          }
                                          ?>
                                          <span class="text-dark ms-2 fw-bold fs-6">(<?= $rating ?>/4)</span>
                                      </div>
                                  <?php else: ?>
                                      <div class="text-secondary"><?= nl2br(esc($j['jawaban'])) ?></div>
                                  <?php endif; ?>
                              </div>
                          <?php endforeach; ?>
                      <?php endforeach; ?>

                  </div>
                  <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
