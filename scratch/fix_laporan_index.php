<?php
$path = 'app/Views/admin/laporan/index.php';
$content = file_get_contents($path);

// Add JS
if (strpos($content, 'function updateDate()') === false) {
    $js = "    <script>\n        function updateDate() {\n            const now = new Date();\n            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };\n            const dateStr = now.toLocaleDateString('id-ID', options);\n            document.querySelectorAll('.current-date').forEach(el => {\n                el.innerText = dateStr;\n            });\n        }\n        setInterval(updateDate, 1000);\n        updateDate();\n    </script>\n</body>";
    $content = preg_replace('/<\/body>/i', $js, $content);
}

$replacement = '<div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= base_url(\'assets/img/logo_creativemu_admin.png\'); ?>" alt="Logo Creativemu" style="height: 40px; object-fit: contain;">
                <div>
                    <h3 class="fw-bold m-0" style="color: var(--dark-purple);">Pusat Laporan Akademik</h3>
                    <p class="text-muted m-0 small">Kelola, tinjau, dan cetak seluruh rekapitulasi data akademik dengan mudah.</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-muted d-none d-md-block px-3 py-1 rounded-pill bg-light current-date" style="font-size: 0.78rem; font-weight: 600; color: #794bc4 !important;">Memuat tanggal...</div>
                <button onclick="window.print()" class="btn btn-print-custom">
                    <i class="fa-solid fa-print me-2"></i> Cetak Laporan
                </button>
            </div>
        </div>';

$content = preg_replace('/<div class="top-navbar">\s*<div>\s*<h3 class="fw-bold m-0" style="color: var\(--dark-purple\);">Pusat Laporan Akademik<\/h3>\s*<p class="text-muted m-0 small">Kelola, tinjau, dan cetak seluruh rekapitulasi data akademik dengan\s*mudah\.<\/p>\s*<\/div>\s*<button onclick="window\.print\(\)" class="btn btn-print-custom">\s*<i class="fa-solid fa-print me-2"><\/i> Cetak Laporan\s*<\/button>\s*<\/div>/is', $replacement, $content);

file_put_contents($path, $content);

