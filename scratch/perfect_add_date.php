<?php
$dir = new RecursiveDirectoryIterator('c:/Users/LENOVO/Creativemu-Academy/app/Views/admin');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        $normalizedPath = str_replace('\\', '/', $path);
        
        // Skip sidebar and laporan
        if (strpos(basename($path), 'sidebar') !== false) {
            continue;
        }
        if (strpos($normalizedPath, '/laporan/') !== false) {
            continue;
        }

        $content = file_get_contents($path);

        $replacement = <<<'HTML'
<?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
</div>
HTML;

        $patternOldDate = '/<div[^>]*>[\s\S]*?Memuat tanggal\.\.\.[\s\S]*?<\/div>/i';

        if (preg_match($patternOldDate, $content)) {
            $new_content = preg_replace($patternOldDate, $replacement, $content);
            file_put_contents($path, $new_content);
            echo "Replaced old date in: $path\n";
        } else {
            // Not found, maybe it doesn't have it. Inject before admin-profile
            // Only if admin-profile exists and is not laporan
            if (preg_match('/<div class="admin-profile"/i', $content)) {
                // If it's validasi/index.php, wrap it
                if (strpos($normalizedPath, 'validasi/index.php') !== false) {
                    $replacementWrapped = "<div class=\"d-flex align-items-center gap-3\">\n" . $replacement . "\n<div class=\"admin-profile\"";
                    $new_content = preg_replace('/<div class="admin-profile"/i', $replacementWrapped, $content, 1);
                    $new_content = preg_replace('/<\/section>/i', "</div>\n    </section>", $new_content, 1);
                    file_put_contents($path, $new_content);
                    echo "Injected wrapped date in: $path\n";
                } else {
                    $new_content = preg_replace('/<div class="admin-profile"/i', $replacement . "\n<div class=\"admin-profile\"", $content, 1);
                    file_put_contents($path, $new_content);
                    echo "Injected date in: $path\n";
                }
            }
        }
    }
}

