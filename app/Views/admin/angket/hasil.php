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
        html, body { font-family: 'Poppins', sans-serif; background-color: #f7f5fd; font-size: 14px; }
        
        
        
        .nav-link { color: var(--sidebar-text); padding: 12px 18px; display: flex; align-items: center; border-radius: 12px; margin: 0 14px 6px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: var(--sidebar-active-gradient); color: #ffffff; }

        
        .content-card { background: #ffffff; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .text-purple { color: var(--primary-purple); }

        .page-title, .top-navbar h3, .top-navbar h4, h3.fw-bold { font-size: 1.25rem; font-weight: 700; }
        .page-subtitle, .top-navbar p, p.text-muted { font-size: 0.8rem; }
        .admin-info h6 { font-size: 0.88rem; font-weight: 600; }
        small, .text-muted { font-size: 0.78rem; }
        .form-label { font-size: 0.8rem !important; font-weight: 600 !important; }
        .form-control, .form-select { font-size: 0.85rem; }
        .btn { font-size: 0.82rem; font-weight: 600; }
        .table thead th { font-size: 0.75rem; font-weight: 600; padding: 10px 12px; }
        .table tbody td { font-size: 0.8rem; padding: 10px 12px; }
        .badge { font-size: 0.75rem; font-weight: 600; }

        #main-content {
            margin-left: 240px;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        @media (max-width: 992px) {
            #main-content {
                margin-left: 70px;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            #main-content {
                padding: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>
<body>

    <!-- Sidebar -->
        <?= view('admin/layouts/sidebar_universal', ['isMentor' => isset($isMentor) ? $isMentor : false]); ?>

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



