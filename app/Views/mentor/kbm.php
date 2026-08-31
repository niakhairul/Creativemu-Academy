<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola KBM Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h3 class="mb-4">Kegiatan Belajar Mengajar (KBM)</h3>

        <!-- Notifikasi Pesan -->
        <?php if (session()->getFlashdata('pesan')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('pesan'); ?></div>
        <?php endif; ?>

        <!-- Form Upload Materi -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Upload Materi Pembelajaran</h5>
                <!-- WAJIB ADA enctype="multipart/form-data" AGAR FILE TIDAK KOSONG -->
                <form action="<?= base_url('mentor/kelas/kbm/simpan/' . $id_kelas) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Judul Materi</label>
                        <input type="text" name="judul_materi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File Materi (PDF / Doc / Video / Gambar)</label>
                        <input type="file" name="file_materi" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Unggah Materi</button>
                </form>
            </div>
        </div>

        <!-- Daftar Materi yang Sudah Diupload -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Daftar Materi Diunggah</h5>
                <ul class="list-group">
                    <?php if (!empty($materi)): ?>
                        <?php foreach ($materi as $m): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= esc($m['judul_materi']); ?>
                                <a href="<?= base_url('uploads/materi/' . $m['file_materi']) ?>" target="_blank" class="btn btn-sm btn-outline-info">Download / Lihat</a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="list-group-item text-muted">Belum ada materi yang diunggah.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="<?= base_url('mentor/kelas/detail/' . $id_kelas) ?>" class="btn btn-secondary">Kembali ke Detail Kelas</a>
        </div>
    </div>
</body>
</html>