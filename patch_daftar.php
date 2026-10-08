<?php
$file = 'app/Controllers/pelatihan.php';
$c = file_get_contents($file);

$s = '/\'id_kelas\' => \$k\[\'id\'\] \?\? null,\s+\'nama_kelas\' => \$k\[\'name\'\] \?\? \'Kelas dari SIM\',.*?\'harga_privat\' => \$k\[\'private_price\'\] \?\? 0,/s';
$r = <<<'EOT'
'id_kelas' => $k['id'] ?? null,
                      'nama_kelas' => $k['name'] ?? 'Kelas dari SIM',
                      'thumbnail' => $k['thumbnail'] ?? $k['image'] ?? '',
                      'kategori' => $k['training']['name'] ?? $k['category'] ?? 'Umum',
                      'status' => $k['status'] ?? 'aktif',
                      'deskripsi' => $k['description'] ?? $k['summary'] ?? '-',
                      'nama_mentor' => (!empty($k['trainers']) && isset($k['trainers'][0]['name'])) 
                                        ? $k['trainers'][0]['name'] 
                                        : 'Mentor Belum Ditentukan',
                      'jumlah_pertemuan' => $k['schedule']['meetings'] ?? $k['sessions_count'] ?? $k['meetings'] ?? 0,
                      'kapasitas' => $k['capacity']['target_students'] ?? $k['capacity'] ?? 0,
                      'kapasitas_tersedia' => $k['capacity']['remaining_students'] ?? $k['available_capacity'] ?? $k['capacity'] ?? 0,
                      'harga_reguler' => $k['pricing']['price'] ?? $k['regular_price'] ?? $k['price'] ?? 0,
                      'harga_privat' => $k['pricing']['price'] ?? $k['private_price'] ?? 0,
EOT;
$c = preg_replace($s, $r, $c);
file_put_contents($file, $c);
echo "Done";

