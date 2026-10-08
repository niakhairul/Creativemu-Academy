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

        // Cari blok div yang mengandung "Memuat tanggal..."
        $pattern = '/<div[^>]*>[\s\S]*?Memuat tanggal\.\.\.[\s\S]*?<\/div>/i';
        
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

        if (preg_match($pattern, $content)) {
            $new_content = preg_replace($pattern, $replacement, $content);
            file_put_contents($path, $new_content);
            echo "Updated with 'Memuat tanggal': $path\n";
        }
    }
}

