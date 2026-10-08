<?php
$path = 'c:/Users/LENOVO/Creativemu-Academy/app/Views/admin/validasi/index.php';
$content = file_get_contents($path);
$pattern = '/<\?php\s*\$hari.*?\$tanggal_indo\s*\?>\s*<div class="d-flex align-items-center gap-3">\s*<div class="text-muted d-none d-md-block[^>]+>\s*<i class="far fa-calendar-alt me-2"><\/i><\?=\s*\$tanggal_indo\s*\?>\s*<\/div>\s*/is';
$new_content = preg_replace($pattern, '', $content);
$new_content = str_replace('</div></section>', '</section>', $new_content);
file_put_contents($path, $new_content);
echo "Updated validasi\n";

