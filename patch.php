<?php
$file = 'app/Controllers/Pelatihan.php';
$c = file_get_contents($file);

$search1 = '/\'id_kelas\' => \$k\[\'id\'\] \?\? null,.*?\'harga_privat\' => .*?,/s';
$replace1 = <<<'EOT'
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

$c = preg_replace($search1, $replace1, $c);

$search2 = '/\'id_kelas\' => \$k\[\'id\'\],\s+\'nama_kelas\' => \$k\[\'name\'\] \?\? \'Kelas\',.*?\'metode_pembelajaran\' => \$k\[\'method\'\] \?\? \$k\[\'type\'\] \?\? \'offline\'/s';
$replace2 = <<<'EOT'
'id_kelas' => $k['id'],
            'nama_kelas' => $k['name'] ?? 'Kelas',
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
            'tanggal_mulai_kelas' => $k['schedule']['start_date'] ?? $k['start_date'] ?? '-',
            'jenis_kelas' => $k['type'] ?? 'Reguler',
            'metode_pembelajaran' => $k['schedule']['method'] ?? $k['method'] ?? 'offline'
EOT;

$c = preg_replace($search2, $replace2, $c);

file_put_contents($file, $c);
echo "Done";

