<?php
// Mocking the behavior inside the controller
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);

function resolveLokasiPelatihan($namaLokasi, $metodePembelajaran) {
    $isOnline = false;
    $cleanMetode = strtolower(trim((string) $metodePembelajaran));
    $cleanNama   = strtolower(trim((string) $namaLokasi));

    if ($cleanMetode === 'online' || str_contains($cleanNama, 'online') || str_contains($cleanNama, 'daring')) {
        $isOnline = true;
    }

    if ($isOnline) {
        return [
            'nama_lokasi'  => $namaLokasi ?: 'Online / Daring',
            'alamat'       => 'Online / Jarak Jauh',
            'latitude'     => 0.000000,
            'longitude'    => 0.000000,
            'radius_meter' => 9999999,
            'is_online'    => 1,
        ];
    }

    if (str_contains($cleanNama, 'pusat')) {
        return [
            'nama_lokasi'  => 'Kantor Pusat',
            'alamat'       => 'Jl. Gn. Bulu No.89...',
            'latitude'     => -7.818933,
            'longitude'    => 110.285813,
            'radius_meter' => 100,
            'is_online'    => 0,
        ];
    }
    
    // ...
    
    return [
        'nama_lokasi'  => 'Lokasi Tidak Diketahui',
        'alamat'       => '-',
        'latitude'     => 0.0,
        'longitude'    => 0.0,
        'radius_meter' => 0,
        'is_online'    => 0,
    ];
}

$stmt = $pdo->query("SELECT p.lokasi_pelatihan, p.metode_pembelajaran FROM pendaftaran p WHERE p.id_users = 18 AND p.id_kelas = 2");
$pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);

var_dump(resolveLokasiPelatihan($pendaftaran['lokasi_pelatihan'], $pendaftaran['metode_pembelajaran']));