<?= $this->extend('admin/layouts/sidebar') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Data Peserta</h3>
        <p class="text-muted mb-0">Daftar peserta yang sudah divalidasi.</p>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Peserta</th>
                    <th>No HP</th>
                    <th>Kelas</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($peserta)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada peserta yang divalidasi.</td></tr>
                <?php endif; ?>
                <?php foreach(($peserta ?? []) as $i => $item): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong><?= esc($item['nama'] ?? '-') ?></strong><br><small class="text-muted"><?= esc($item['email'] ?? '-') ?></small></td>
                        <td><?= esc($item['no_hp'] ?? '-') ?></td>
                        <td><?= esc($item['nama_kelas'] ?? '-') ?></td>
                        <td><?= esc($item['metode_pembayaran'] ?? '-') ?> / <?= esc($item['status_pembayaran'] ?? '-') ?></td>
                        <td><span class="badge bg-success">Sudah divalidasi</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
