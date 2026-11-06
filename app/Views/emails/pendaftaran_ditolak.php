<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Status Pendaftaran Kelas - Creativemu Academy</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; -webkit-font-smoothing: antialiased; line-height: 1.6;">

    <!-- Wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9; width: 100%; padding: 30px 10px;">
        <tr>
            <td align="center">
                <!-- Main Container (Max 600px) -->
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: #e11d48; background: linear-gradient(135deg, #e11d48 0%, #be123c 100%); padding: 36px 30px 28px 30px; text-align: center; color: #ffffff;">
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
                                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #ffe4e6; font-weight: 500; letter-spacing: 0.3px;">
                                            Sistem Informasi Manajemen Pelatihan
                                        </p>
                                        <div style="margin-top: 18px;">
                                            <span style="display: inline-block; background-color: rgba(255, 255, 255, 0.95); color: #be123c; font-size: 12px; font-weight: 700; padding: 6px 16px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                                                Informasi Status Pendaftaran
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
                                Halo, <?= esc($nama ?? 'Peserta') ?>
                            </h2>

                            <p style="margin: 0 0 16px 0; font-size: 15px; color: #334155; line-height: 1.6;">
                                Terima kasih atas minat dan antusiasme Anda untuk bergabung dalam program pelatihan di <strong>Creativemu Academy</strong>.
                            </p>

                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #334155; line-height: 1.6;">
                                Berdasarkan hasil verifikasi berkas dan administrasi oleh Tim Akademik, mohon maaf saat ini pendaftaran Anda untuk kelas <strong><?= esc($nama_kelas ?? 'Pelatihan') ?></strong> <strong>belum dapat kami setujui</strong>.
                            </p>

                            <!-- Reason Box -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fff1f2; border: 1px solid #fecdd3; border-left: 4px solid #e11d48; border-radius: 8px; margin: 24px 0;">
                                <tr>
                                    <td style="padding: 18px 20px;">
                                        <span style="display: block; font-size: 12px; font-weight: 700; color: #9f1239; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                            📌 Catatan / Alasan Penolakan:
                                        </span>
                                        <span style="display: block; font-size: 14px; color: #881337; line-height: 1.6;">
                                            <?= esc($alasan ?? 'Berkas pendaftaran atau bukti verifikasi pembayaran belum memenuhi kriteria persyaratan.') ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Recommendations -->
                            <h3 style="margin: 24px 0 12px 0; font-size: 15px; font-weight: 700; color: #0f172a;">
                                Apa yang dapat Anda lakukan selanjutnya?
                            </h3>

                            <ul style="margin: 0 0 24px 0; padding-left: 20px; font-size: 14px; color: #475569; line-height: 1.6;">
                                <li style="margin-bottom: 8px;">Periksa kembali kesesuaian berkas identitas atau bukti pembayaran yang Anda unggah.</li>
                                <li style="margin-bottom: 8px;">Anda dapat mendaftar kembali atau melakukan pembaharuan data melalui sistem.</li>
                                <li style="margin-bottom: 8px;">Jika Anda merasa terdapat kekeliruan, silakan hubungi tim admin layanan akademik kami.</li>
                            </ul>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 20px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="<?= esc($portal_url ?? base_url('pelatihan/login')) ?>" target="_blank" style="display: inline-block; background-color: #475569; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 50px; font-size: 14px; font-weight: 700; letter-spacing: 0.3px;">
                                            Buka Portal Creativemu
                                        </a>
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

