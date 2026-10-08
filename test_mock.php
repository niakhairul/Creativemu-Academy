<?php
// Mocking the behavior inside the controller
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);

// Find jadwal 1
$stmt = $pdo->query("SELECT id_jadwal, id_kelas FROM jadwal WHERE id_jadwal = 1");
$jadwal = $stmt->fetch(PDO::FETCH_ASSOC);

// Find pendaftaran
$stmt = $pdo->query("SELECT p.id_pendaftaran, p.lokasi_pelatihan, p.metode_pembelajaran FROM pendaftaran p WHERE p.id_users = 18 AND p.id_kelas = " . $jadwal['id_kelas']);
$pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);

print_r($pendaftaran);

function resolveLokasiPelatihan($namaLokasi, $metodePembelajaran) {
    $cleanMetode = strtolower(trim((string) $metodePembelajaran));
    $cleanNama   = strtolower(trim((string) $namaLokasi));
    if (str_contains($cleanNama, 'pusat')) {
        return [
            'latitude'     => -7.818933,
            'longitude'    => 110.285813,
            'radius_meter' => 100,
        ];
    }
    return ['error' => 'not found'];
}

$lokasiInfo = resolveLokasiPelatihan($pendaftaran['lokasi_pelatihan'] ?? null, $pendaftaran['metode_pembelajaran'] ?? null);
print_r($lokasiInfo);