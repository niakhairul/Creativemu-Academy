<?php
$dir = new RecursiveDirectoryIterator('c:/Users/LENOVO/Creativemu-Academy/app/Views/admin');
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $path = $file->getPathname();
        
        $content = file_get_contents($path);

        // Cari script yang mengeset tanggal
        $pattern = '/\/\/\s*Format Tanggal Bahasa Indonesia.*?today\.toLocaleDateString.*?;/is';
        
        if (preg_match($pattern, $content)) {
            $new_content = preg_replace($pattern, '', $content);
            file_put_contents($path, $new_content);
            echo "Removed date JS from: $path\n";
        } else {
            // Coba pattern lain jika ada
            $pattern2 = '/const options = \{.*?\};.*?toLocaleDateString.*?;/is';
            if (preg_match($pattern2, $content)) {
                $new_content = preg_replace($pattern2, '', $content);
                file_put_contents($path, $new_content);
                echo "Removed date JS (pattern 2) from: $path\n";
            }
        }
    }
}

