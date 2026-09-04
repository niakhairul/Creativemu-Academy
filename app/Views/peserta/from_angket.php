<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Isi Angket Evaluasi - Creativemu Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf5ff;
            background-image: radial-gradient(#d8b4fe 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold" style="color: #2e1065;">Angket Evaluasi Pelatihan</h3>
                        <p class="text-muted small">Silakan isi pertanyaan di bawah ini dengan jujur untuk membantu evaluasi kualitas kelas.</p>
                    </div>

                    <form action="<?= base_url('pelatihan/simpan_angket'); ?>" method="POST">
                        <?= csrf_field(); ?>

                        <?php if (!empty($pertanyaan)): ?>
                            <?php foreach ($pertanyaan as $index => $p): ?>
                                <div class="mb-4 p-3 rounded-4 bg-light border border-purple border-opacity-10">
                                    <label class="fw-bold mb-2 text-dark">
                                        <?= ($index + 1) . '. ' . esc($p['pertanyaan']); ?>
                                    </label>
                                    <!-- Jika jenis jawaban teks/esai -->
                                    <textarea name="jawaban[<?= $p['id_pertanyaan']; ?>]" class="form-control rounded-3" rows="3" placeholder="Tuliskan jawaban Anda di sini..." required></textarea>
                                </div>
                            <?php endforeach; ?>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn text-white fw-bold py-3 rounded-3 shadow-sm" style="background-color: #7c3aed;">
                                    Kirim Jawaban Angket
                                </button>
                                <a href="<?= base_url('peserta/dashboard'); ?>" class="btn btn-light text-muted fw-semibold py-2">Kembali ke Dashboard</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-muted">Belum ada pertanyaan angket yang disiapkan oleh admin.</p>
                                <a href="<?= base_url('peserta/dashboard'); ?>" class="btn btn-secondary rounded-pill px-4">Kembali</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>