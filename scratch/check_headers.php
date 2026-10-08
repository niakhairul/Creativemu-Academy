<?php
$dir = new RecursiveDirectoryIterator('app/Views/admin');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    // Check if it's a page layout (contains main-content)
    if(strpos($content, 'id="main-content"') !== false || strpos($content, '<div id="main-content">') !== false || strpos($content, 'class="main-content"') !== false || strpos($content, 'id="content"') !== false) {
        
        // If it doesn't have the logo
        if(strpos($content, 'logo_creativemu_admin.png') === false) {
            echo "Needs update: $path\n";
        }
    }
}

