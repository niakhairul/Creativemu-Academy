<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pendaftaran Kelas Disetujui - Creativemu Academy</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; -webkit-font-smoothing: antialiased; line-height: 1.6;">

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9; width: 100%; padding: 30px 10px;">
        <tr>
            <td align="center">
                <!-- Main Container (Max 600px) -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner with Gradient -->
                    <tr>
                        <td style="background: #7c3aed; background: linear-gradient(135deg, #6366f1 0%, #7c3aed 50%, #9333ea 100%); padding: 36px 30px 28px 30px; text-align: center; color: #ffffff;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <!-- Logo / Brand Icon -->
                                        <div style="display: inline-block; width: 48px; height: 48px; line-height: 48px; border-radius: 12px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); margin-bottom: 12px; font-size: 24px; text-align: center;">
                                            🎓
                                        </div>
                                        <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: 0.5px; color: #ffffff; text-transform: uppercase;">
                                            Creativemu Academy
                                        </h1>
                                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #e0e7ff; font-weight: 500; letter-spacing: 0.3px;">
                                            Sistem Informasi Manajemen Pelatihan
                                        </p>
                                        <div style="margin-top: 18px;">
                                            <span style="display: inline-block; background-color: rgba(255, 255, 255, 0.95); color: #7c3aed; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                                ✓ Pendaftaran Disetujui
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 30px;">
                            
                            <!-- Salutation -->
                            <h2 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 700; color: #0f172a;">
                                Halo, <?= esc($nama ?? 'Peserta') ?>! 👋
                            </h2>

                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #334155; line-height: 1.6;">
                                Selamat! Pendaftaran Anda untuk mengikuti pelatihan di <strong>Creativemu Academy</strong> telah berhasil diverifikasi dan resmi <strong>DISETUJUI</strong> oleh Tim Akademik. Anda kini telah aktif sebagai peserta resmi.
                            </p>

                            <!-- NIS Card Box -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #faf5ff; border: 2px dashed #a855f7; border-radius: 12px; margin: 24px 0;">
                                <tr>
                                    <td style="padding: 20px; text-align: center;">
                                        <span style="display: block; font-size: 11px; font-weight: 700; color: #7e22ce; text-transform: uppercase; letter-spacing: 1.2px; margin-bottom: 6px;">
                                            Nomor Induk Siswa (NIS) Anda
                                        </span>
                                        <span style="display: block; font-size: 28px; font-weight: 800; color: #581c87; letter-spacing: 2px; font-family: 'Courier New', Courier, monospace;">
                                            <?= esc($nis ?? '-') ?>
                                        </span>
                                        <span style="display: block; font-size: 12px; color: #6b21a8; margin-top: 6px;">
                                            Simpan NIS ini sebagai nomor identitas resmi Anda selama pelatihan.
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Registration Details Table -->
                            <h3 style="margin: 26px 0 12px 0; font-size: 15px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                                📋 Rincian Pendaftaran
                            </h3>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; font-size: 14px;">
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; width: 40%; font-weight: 500;">
                                        Nama Kelas
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                                        <?= esc($nama_kelas ?? 'Kelas Pelatihan') ?>
                                    </td>
                                </tr>
                                <?php if (!empty($kategori)): ?>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">
                                        Kategori Pelatihan
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                                        <?= esc($kategori) ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">
                                        Jenis Kelas
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; background-color: #ede9fe; color: #6d28d9; font-weight: 700;">
                                            <?= esc(ucfirst($jenis_kelas ?? 'Reguler')) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">
                                        Metode Pembelajaran
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                                        <?= esc(ucfirst($metode_pembelajaran ?? 'Offline')) ?>
                                    </td>
                                </tr>
                                <?php if (!empty($lokasi_pelatihan)): ?>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">
                                        Lokasi Pelatihan
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                                        <?= esc($lokasi_pelatihan) ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #64748b; font-weight: 500;">
                                        Tanggal Disetujui
                                    </td>
                                    <td style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: 600;">
                                        <?= esc($tanggal_disetujui ?? date('d F Y')) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; color: #64748b; font-weight: 500;">
                                        Status Keaktifan
                                    </td>
                                    <td style="padding: 12px 16px; color: #15803d; font-weight: 700;">
                                        <span style="display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; background-color: #dcfce7; color: #15803d;">
                                            ● Disetujui &amp; Siap Diaktivasi
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Next Steps Guide -->
                            <h3 style="margin: 28px 0 12px 0; font-size: 15px; font-weight: 700; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                                🚀 Langkah Selanjutnya
                            </h3>

                            <?php $isRegistered = !empty($is_registered); ?>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 24px;">
                                <?php if (!$isRegistered): ?>
                                <!-- Alur Peserta Baru: Registrasi & Buat Password Terlebih Dahulu -->
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 32px;">
                                        <span style="display: inline-block; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: #ede9fe; color: #7c3aed; font-size: 12px; font-weight: 800; text-align: center;">1</span>
                                    </td>
                                    <td style="padding: 8px 0 8px 10px; font-size: 14px; color: #334155; line-height: 1.5;">
                                        <strong>Aktivasi Akun &amp; Buat Kata Sandi:</strong> Klik tombol aktivasi di bawah untuk membuat password akun peserta Anda (data nama, nomor HP, dan email Anda sudah otomatis terisi).
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 32px;">
                                        <span style="display: inline-block; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: #ede9fe; color: #7c3aed; font-size: 12px; font-weight: 800; text-align: center;">2</span>
                                    </td>
                                    <td style="padding: 8px 0 8px 10px; font-size: 14px; color: #334155; line-height: 1.5;">
                                        <strong>Login ke Portal Peserta:</strong> Setelah kata sandi berhasil dibuat, masuk ke portal peserta menggunakan email (<strong><?= esc($email ?? '') ?></strong>) dan kata sandi baru Anda.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 32px;">
                                        <span style="display: inline-block; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: #ede9fe; color: #7c3aed; font-size: 12px; font-weight: 800; text-align: center;">3</span>
                                    </td>
                                    <td style="padding: 8px 0 8px 10px; font-size: 14px; color: #334155; line-height: 1.5;">
                                        <strong>Pantau KBM &amp; Presensi Mandiri:</strong> Akses jadwal pertemuan kelas, materi modul, dan lakukan presensi GPS setiap sesi kelas berlangsung.
                                    </td>
                                </tr>
                                <?php else: ?>
                                <!-- Alur Peserta yang Sudah Pernah Buat Password -->
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 32px;">
                                        <span style="display: inline-block; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: #ede9fe; color: #7c3aed; font-size: 12px; font-weight: 800; text-align: center;">1</span>
                                    </td>
                                    <td style="padding: 8px 0 8px 10px; font-size: 14px; color: #334155; line-height: 1.5;">
                                        <strong>Masuk ke Portal Peserta:</strong> Login menggunakan email (<strong><?= esc($email ?? '') ?></strong>) dan kata sandi yang telah Anda miliki sebelumnya.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 32px;">
                                        <span style="display: inline-block; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: #ede9fe; color: #7c3aed; font-size: 12px; font-weight: 800; text-align: center;">2</span>
                                    </td>
                                    <td style="padding: 8px 0 8px 10px; font-size: 14px; color: #334155; line-height: 1.5;">
                                        <strong>Pantau Jadwal &amp; Modul KBM:</strong> Periksa jadwal pertemuan dan berkas materi pelatihan kelas baru Anda di dashboard.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 32px;">
                                        <span style="display: inline-block; width: 24px; height: 24px; line-height: 24px; border-radius: 50%; background-color: #ede9fe; color: #7c3aed; font-size: 12px; font-weight: 800; text-align: center;">3</span>
                                    </td>
                                    <td style="padding: 8px 0 8px 10px; font-size: 14px; color: #334155; line-height: 1.5;">
                                        <strong>Presensi Mandiri:</strong> Lakukan presensi mandiri saat sesi kelas berlangsung sesuai radius GPS.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>

                            <!-- Primary Call-to-Action (CTA) Button -->
                            <?php 
                                $targetUrl = $action_url ?? ($isRegistered ? base_url('pelatihan/login') : base_url('pelatihan/register?email=' . urlencode($email ?? '')));
                                $btnLabel  = $button_text ?? ($isRegistered ? 'Masuk ke Portal Peserta &rarr;' : 'Aktivasi Akun &amp; Buat Kata Sandi &rarr;');
                            ?>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 28px 0 20px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="<?= esc($targetUrl) ?>" target="_blank" style="display: inline-block; background-color: #7c3aed; background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%); color: #ffffff; text-decoration: none; padding: 14px 34px; border-radius: 50px; font-size: 15px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35); text-align: center;">
                                            <?= $btnLabel ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 10px;">
                                        <span style="font-size: 12px; color: #94a3b8;">
                                            Tautan: <a href="<?= esc($targetUrl) ?>" style="color: #7c3aed; text-decoration: underline;"><?= esc($targetUrl) ?></a>
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Information Box -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9; border-left: 4px solid #6366f1; border-radius: 6px; margin-top: 24px;">
                                <tr>
                                    <td style="padding: 14px 16px; font-size: 13px; color: #475569; line-height: 1.5;">
                                        <?php if (!$isRegistered): ?>
                                            💡 <strong>Catatan Pembuatan Kata Sandi:</strong> Karena kata sandi belum dibuat saat Anda mengisi formulir pendaftaran kelas, silakan klik tombol di atas untuk mendaftarkan kata sandi baru Anda agar akun peserta Anda aktif.
                                        <?php else: ?>
                                            💡 <strong>Tips Keamanan:</strong> Demi kenyamanan dan keamanan, jangan bagikan kata sandi akun Anda kepada siapa pun. Anda dapat memperbarui kata sandi di menu <strong>Pengaturan &rsaquo; Keamanan Akun</strong>.
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 30px; text-align: center; color: #94a3b8; font-size: 12px; line-height: 1.6;">
                            <p style="margin: 0 0 6px 0; font-weight: 600; color: #64748b; font-size: 13px;">
                                Creativemu Academy
                            </p>
                            <p style="margin: 0 0 10px 0;">
                                Pusat Pelatihan & Pengembangan Keterampilan Kreatif Digital
                            </p>
                            <p style="margin: 0; color: #cbd5e1; font-size: 11px;">
                                Email ini dikirim secara otomatis oleh Sistem Informasi Manajemen Pelatihan Creativemu Academy.<br>
                                Mohon untuk tidak membalas langsung ke alamat email ini.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->

                <!-- Small Copyright Sub-footer -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; margin-top: 16px;">
                    <tr>
                        <td align="center" style="font-size: 11px; color: #94a3b8;">
                            &copy; <?= date('Y') ?> Creativemu Academy. All rights reserved.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>
