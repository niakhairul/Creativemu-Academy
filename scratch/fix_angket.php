<?php
$path = 'app/Views/admin/angket/index.php';
$content = file_get_contents($path);
$replacement = '        <section class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= base_url(\'assets/img/logo_creativemu_admin.png\'); ?>" alt="Logo Creativemu" style="height: 40px; object-fit: contain;">
                <div>
                    <h1 class="page-title">
                        Monitoring Angket
                    </h1>

                    <p class="page-subtitle">
                        Ringkasan evaluasi instruktur, tempat pelatihan, dan saran peserta.
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
                <div class="text-muted d-none d-md-block px-3 py-1 rounded-pill bg-light current-date" style="font-size: 0.78rem; font-weight: 600; color: #794bc4 !important;">
                    Memuat tanggal...
                </div>
                <div class="admin-profile">';

$content = preg_replace('/<section class="top-navbar">\s*<div>\s*<h1 class="page-title">\s*Monitoring Angket\s*<\/h1>\s*<p class="page-subtitle">\s*Ringkasan evaluasi instruktur, tempat pelatihan, dan saran peserta\.\s*<\/p>\s*<\/div>\s*<div class="admin-profile">/is', $replacement, $content);
file_put_contents($path, $content);

