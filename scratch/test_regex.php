<?php
$file = 'app/Views/admin/master_kelas/index.php';
$content = file_get_contents($file);
preg_match('/<div class="top-navbar">.*?<\/div>\s*<\/div>/is', $content, $match);
echo $match[0];

