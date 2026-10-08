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

        // Jika sudah ada calendar, skip
        if (strpos($content, 'fa-calendar-alt') !== false && strpos($content, '$tanggal_indo') !== false) {
            continue;
        }

        if (strpos($normalizedPath, 'validasi/index.php') !== false) {
            // Khusus validasi/index.php, bungkus dengan div d-flex
            $replacement = <<<'HTML'
<?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<div class="d-flex align-items-center gap-3">
    <div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
        <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
    </div>
    <div class="admin-profile"
HTML;
            if (preg_match('/<div class="admin-profile"/i', $content)) {
                $new_content = preg_replace('/<div class="admin-profile"/i', $replacement, $content, 1);
                // tutup div tambahan sebelum </section>
                $new_content = preg_replace('/<\/section>/i', "</div>\n    </section>", $new_content, 1);
                file_put_contents($path, $new_content);
                echo "Added date (wrapped) to: $path\n";
            }
        } else {
            $replacement = <<<'HTML'
<?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
</div>
<div class="admin-profile"
HTML;
            if (preg_match('/<div class="admin-profile"/i', $content)) {
                $new_content = preg_replace('/<div class="admin-profile"/i', $replacement, $content, 1);
                file_put_contents($path, $new_content);
                echo "Added date to: $path\n";
            }
        }
    }
}

