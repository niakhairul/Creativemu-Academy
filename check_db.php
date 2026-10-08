<?php
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);

// Find Aliando's id_user
$stmt = $pdo->query("SELECT id_user, nama FROM users WHERE nama LIKE '%Aliando%'");
$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
$id_user = $user_row['id_user'];
echo "User: " . $user_row['nama'] . " (ID: $id_user)\n";

// Find Aliando's pendaftaran for Laravel Web
$stmt = $pdo->query("SELECT p.id_pendaftaran, p.id_kelas, k.nama_kelas, p.status FROM pendaftaran p JOIN kelas k ON p.id_kelas = k.id_kelas WHERE p.id_user = $id_user");
$pendaftaran = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($pendaftaran);

// Find jadwal for that id_kelas
if (count($pendaftaran) > 0) {
    $id_kelas = $pendaftaran[0]['id_kelas'];
    $stmt = $pdo->query("SELECT id_jadwal, id_kelas, pertemuan_ke FROM jadwal WHERE id_kelas = $id_kelas");
    $jadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($jadwal);
}
