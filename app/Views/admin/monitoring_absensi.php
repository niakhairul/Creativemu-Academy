<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <h2 class="mb-4">Monitoring Absensi Mentor</h2>

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Data Absensi Mentor</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mentor</th>
                            <th>Kelas</th>
                            <th>Pertemuan</th>
                            <th>Materi</th>
                            <th>Tanggal</th>
                            <th>Waktu Absen</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($absensi)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($absensi as $row): ?>

                                <tr>

                                    <td>
                                        <?= $no++ ?>
                                    </td>

                                    <td>
                                        <?= esc($row['nama_mentor']) ?>
                                    </td>

                                    <td>
                                        <?= esc($row['nama_kelas']) ?>
                                    </td>

                                    <td>
                                        Pertemuan
                                        <?= esc($row['pertemuan_ke']) ?>
                                    </td>

                                    <td>
                                        <?= esc($row['materi']) ?>
                                    </td>

                                    <td>
                                        <?= date(
                                            'd-m-Y H:i',
                                            strtotime($row['tanggal_kbm'])
                                        ) ?>
                                    </td>

                                    <td>
                                        <?= !empty($row['waktu_absen'])
                                            ? date(
                                                'd-m-Y H:i',
                                                strtotime($row['waktu_absen'])
                                            )
                                            : '-'
                                        ?>
                                    </td>

                                    <td>
                                        <?= esc($row['status']) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="8" class="text-center">
                                    Belum ada data absensi mentor.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>