<?php
$files = [
    'app/Views/admin/angket/tambah_angket.php',
    'app/Views/admin/pengaturan/index.php'
];

foreach ($files as $path) {
    if (!file_exists($path)) continue;
    $content = file_get_contents($path);
    
    // Add JS
    if (strpos($content, 'function updateDate()') === false) {
        $js = "    <script>\n        function updateDate() {\n            const now = new Date();\n            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };\n            const dateStr = now.toLocaleDateString('id-ID', options);\n            document.querySelectorAll('.current-date').forEach(el => {\n                el.innerText = dateStr;\n            });\n        }\n        setInterval(updateDate, 1000);\n        updateDate();\n    </script>\n</body>";
        $content = preg_replace('/<\/body>/i', $js, $content);
    }
    
    // Add logo to top-navbar div
    // We're looking for:
    // <div class="top-navbar">
    //     <div>
    //         <h3 class="...">Title</h3>
    //         <p class="...">Subtitle</p>
    //     </div>
    //     <a href="..." / <button ...>
    // </div>
    
    $pattern = '/<div class="top-navbar">\s*<div>\s*<h3(.*?)>(.*?)<\/h3>\s*<p(.*?)>(.*?)<\/p>\s*<\/div>\s*(<(?:a|button).*?>.*?<\/(?:a|button)>)\s*<\/div>/is';
    
    $replacement = '<div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= base_url(\'assets/img/logo_creativemu_admin.png\'); ?>" alt="Logo Creativemu" style="height: 40px; object-fit: contain;">
                <div>
                    <h3$1>$2</h3>
                    <p$3>$4</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-muted d-none d-md-block px-3 py-1 rounded-pill bg-light current-date" style="font-size: 0.78rem; font-weight: 600; color: #794bc4 !important;">Memuat tanggal...</div>
                $5
            </div>
        </div>';
        
    $newContent = preg_replace($pattern, $replacement, $content);
    
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
    } else {
        echo "Failed to match regex for $path\n";
        // Let's see if we can do something else if it doesn't match
    }
}

