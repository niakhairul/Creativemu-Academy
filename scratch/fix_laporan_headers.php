<?php
$files = [
    'app/Views/admin/buku_induk/index.php',
    'app/Views/admin/laporan/index.php',
    'app/Views/admin/laporan/laporan_angket.php',
    'app/Views/admin/laporan/laporan_kehadiran.php',
    'app/Views/admin/laporan/laporan_mentor.php',
    'app/Views/admin/laporan/laporan_peserta.php',
];

foreach ($files as $path) {
    if (!file_exists($path)) continue;
    $content = file_get_contents($path);
    
    // Check if it already has the logo
    if (strpos($content, 'logo_creativemu_admin.png') !== false) {
        continue;
    }
    
    // Find the header structure:
    // <div class="top-navbar">
    //      <div class="d-flex align-items-center">
    //          <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    //          <div>
    
    $pattern = '/<div class="d-flex align-items-center">\s*<button class="mobile-toggle-btn" onclick="toggleSidebar\(\)"><i class="fas fa-bars"><\/i><\/button>\s*<div>/s';
    
    $replacement = '<div class="d-flex align-items-center gap-3">
                  <button class="mobile-toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                  <img src="<?= base_url(\'assets/img/logo_creativemu_admin.png\'); ?>" alt="Logo Creativemu" style="height: 40px; object-fit: contain;">
                  <div>';
                  
    $content = preg_replace($pattern, $replacement, $content);
    
    // Next, add the date element. The right-side container is:
    // <div class="d-flex align-items-center gap-2 flex-wrap">
    $patternDate = '/<div class="d-flex align-items-center gap-2 flex-wrap">/s';
    $replacementDate = '<div class="d-flex align-items-center gap-2 flex-wrap">
                  <div class="text-muted d-none d-md-block px-3 py-1 rounded-pill bg-light current-date" style="font-size: 0.78rem; font-weight: 600; color: #794bc4 !important;">Memuat tanggal...</div>';
                  
    $content = preg_replace($patternDate, $replacementDate, $content);
    
    // Add JS script before </body>
    if (strpos($content, 'function updateDate()') === false) {
        $js = "    <script>\n        function updateDate() {\n            const now = new Date();\n            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };\n            const dateStr = now.toLocaleDateString('id-ID', options);\n            document.querySelectorAll('.current-date').forEach(el => {\n                el.innerText = dateStr;\n            });\n        }\n        setInterval(updateDate, 1000);\n        updateDate();\n    </script>\n</body>";
        $content = preg_replace('/<\/body>/i', $js, $content);
    }
    
    file_put_contents($path, $content);
    echo "Updated $path\n";
}

