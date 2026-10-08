<?php
$dir = new RecursiveDirectoryIterator('c:/Users/LENOVO/Creativemu-Academy/app/Views/admin');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        
        if (strpos(basename($path), 'sidebar') !== false) {
            continue;
        }

        $content = file_get_contents($path);

        // Pattern for the PHP block and its HTML div
        $pattern = '/<\?php\s*\$hari\s*=\s*\[[^\]]+\];\s*\$bulan\s*=\s*\[[^\]]+\];\s*\$tanggal_indo\s*=\s*[^;]+;\s*\?>\s*<div class="text-muted d-none d-md-block[^>]+>\s*<i class="far fa-calendar-alt me-2"><\/i><\?=\s*\$tanggal_indo\s*\?>\s*<\/div>\s*/is';
        
        if (preg_match($pattern, $content)) {
            $new_content = preg_replace($pattern, '', $content);
            file_put_contents($path, $new_content);
            echo "Removed PHP date block from: $path\n";
        }
    }
}

