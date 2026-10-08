<?php
$dir = new RecursiveDirectoryIterator('app/Views/admin');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Check if it has dash-header and no logo
    if (strpos($content, 'class="dash-header"') !== false && strpos($content, 'logo_creativemu_admin.png') === false) {
        echo "Updating $path\n";
        
        $pattern = '/<div class="dash-header">\s*<h([1-6])(.*?)>(.*?)<\/h\1>\s*<p(.*?)>(.*?)<\/p>\s*<\/div>/is';
        $replacement = '<div class="d-flex align-items-center gap-3 dash-header">
                <img src="<?= base_url(\'assets/img/logo_creativemu_admin.png\'); ?>" alt="Logo Creativemu" style="height: 40px; object-fit: contain;">
                <div>
                    <h$1$2>$3</h$1>
                    <p$4>$5</p>
                </div>
            </div>';
            
        $newContent = preg_replace($pattern, $replacement, $content);
        
        if ($newContent !== $content) {
            file_put_contents($path, $newContent);
        } else {
            echo "Regex didn't match perfectly for $path\n";
        }
    }
}

