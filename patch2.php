<?php
$file = 'app/Controllers/Pelatihan.php';
$c = file_get_contents($file);

$search3 = '/\'kapasitas\' => \$k\[\'capacity\'\] \?\? 0,.*?\'harga_privat\' => \$k\[\'private_price\'\] \?\? 0,/s';
$replace3 = <<<'EOT'
'kapasitas' => $k['capacity']['target_students'] ?? $k['capacity'] ?? 0,
                        'tanggal_mulai_kelas' => $k['schedule']['start_date'] ?? $k['start_date'] ?? date('Y-m-d'),
                        'jenis_kelas' => $k['type'] ?? 'Reguler',
                        'harga_reguler' => $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? 0,
                        'harga_privat' => $k['pricing']['price'] ?? $k['private_price'] ?? 0,
EOT;

$c = preg_replace($search3, $replace3, $c);
file_put_contents($file, $c);
echo "Done";

