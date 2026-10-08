<?php
$path = 'app/Views/admin/angket/detail.php';
$content = file_get_contents($path);

// Add JS
if (strpos($content, 'function updateDate()') === false) {
    $js = "    <script>\n        function updateDate() {\n            const now = new Date();\n            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };\n            const dateStr = now.toLocaleDateString('id-ID', options);\n            document.querySelectorAll('.current-date').forEach(el => {\n                el.innerText = dateStr;\n            });\n        }\n        setInterval(updateDate, 1000);\n        updateDate();\n    </script>\n</body>";
    $content = preg_replace('/<\/body>/i', $js, $content);
}

$replacement = '<section class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= base_url(\'assets/img/logo_creativemu_admin.png\'); ?>" alt="Logo Creativemu" style="height: 40px; object-fit: contain;">
                <div>
                    <h1 class="page-title">Detail Angket</h1>
                    <p class="page-subtitle"><?= esc($angket[\'judul_angket\'] ?? \'Angket Evaluasi\'); ?></p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
                <div class="text-muted d-none d-md-block px-3 py-1 rounded-pill bg-light current-date" style="font-size: 0.78rem; font-weight: 600; color: #794bc4 !important;">
                    Memuat tanggal...
                </div>
                <div class="admin-profile">
                    <img src="<?= base_url(\'assets/img/\' . (session()->get(\'foto_profil\') ? session()->get(\'foto_profil\') : \'admin-profile.jpg\')); ?>" alt="Foto Profil">
                    <div class="admin-info"><h6><?= esc(session()->get(\'nama\') ?: \'Administrator\'); ?></h6><small>Administrator</small></div>
                </div>
            </div>
        </section>';

$content = preg_replace('/<section class="top-navbar">\s*<div>\s*<h1 class="page-title">Detail Angket<\/h1>\s*<p class="page-subtitle"><\?= esc\(\$angket\[\'judul_angket\'\] \?\? \'Angket Evaluasi\'\); \?><\/p>\s*<\/div>\s*<div class="admin-profile">\s*<img src="<\?= base_url\(\'assets\/img\/\' \. \(session\(\)->get\(\'foto_profil\'\) \? session\(\)->get\(\'foto_profil\'\) : \'admin-profile\.jpg\'\)\); \?>" alt="Foto Profil">\s*<div class="admin-info"><h6><\?= esc\(session\(\)->get\(\'nama\'\) \?: \'Administrator\'\); \?><\/h6><small>Administrator<\/small><\/div>\s*<\/div>\s*<\/section>/is', $replacement, $content);

file_put_contents($path, $content);

