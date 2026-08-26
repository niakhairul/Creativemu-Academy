<?= $this->extend('mentor/layout') ?>
<?= $this->section('content') ?>
<h3 class="fw-bold mb-3">KBM - <?= esc($kelas['nama_kelas'] ?? '-') ?></h3>
<div class="row g-4">
<div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body"><h5 class="fw-bold">Upload Materi / Jadwal</h5>
<form action="<?= base_url('mentor/kelas/' . $kelas['id_kelas'] . '/kbm/jadwal') ?>" method="post" enctype="multipart/form-data">
<?= csrf_field() ?>
<input class="form-control mb-2" name="pertemuan_ke" type="number" min="1" max="6" placeholder="Pertemuan ke" required>
<input class="form-control mb-2" name="tanggal_kbm" type="datetime-local" required>
<input class="form-control mb-2" name="jam_selesai" type="time" required>
<textarea class="form-control mb-2" name="materi" placeholder="Materi / tugas"></textarea>
<input class="form-control mb-3" name="materi_file" type="file">
<button class="btn btn-primary w-100">Simpan KBM</button>
</form></div></div></div>
<div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body"><h5 class="fw-bold">6x Pertemuan</h5>
<?php foreach(($jadwal ?? []) as $j): ?><div class="border-top py-2"><strong>Pertemuan <?= esc($j['pertemuan_ke']) ?></strong><br><?= esc($j['tanggal_kbm']) ?> - <?= esc($j['materi'] ?? '-') ?></div><?php endforeach; ?>
<?php if(count($jadwal ?? []) >= 6): ?><div class="alert alert-success mt-3">Pertemuan lengkap. Ujian sudah bisa diproses.</div><?php endif; ?>
</div></div></div>
</div>
<div class="card border-0 shadow-sm mt-4"><div class="card-body"><h5 class="fw-bold">Input Nilai Ujian</h5>
<form action="<?= base_url('mentor/kelas/' . $kelas['id_kelas'] . '/kbm/nilai') ?>" method="post">
<?= csrf_field() ?>
<div class="table-responsive"><table class="table"><thead><tr><th>ID Peserta</th><th>Nilai</th><th>Catatan</th></tr></thead><tbody>
<?php if(empty($hasil)): ?><tr><td colspan="3" class="text-muted text-center">Belum ada hasil ujian.</td></tr><?php endif; ?>
<?php foreach(($hasil ?? []) as $h): ?><tr><td><?= esc($h['id_user'] ?? $h['id_users'] ?? '-') ?></td><td><input class="form-control" type="number" name="nilai[<?= $h['id_hasil_ujian'] ?>]" value="<?= esc($h['nilai'] ?? 0) ?>"></td><td><input class="form-control" name="catatan[<?= $h['id_hasil_ujian'] ?>]" value="<?= esc($h['catatan_mentor'] ?? '') ?>"></td></tr><?php endforeach; ?>
</tbody></table></div><button class="btn btn-success">Simpan Nilai</button></form>
</div></div>
<?= $this->endSection() ?>
