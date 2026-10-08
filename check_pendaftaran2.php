<?php
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);

// Find Aliando's id_users
$stmt = $pdo->query("SELECT id_users, nama FROM users WHERE nama LIKE '%Aliando%'");
$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
$id_users = $user_row['id_users'];
echo "User: " . $user_row['nama'] . " (ID: $id_users)\n";

// Find Aliando's pendaftaran for Laravel Web
$stmt = $pdo->query("SELECT p.id_pendaftaran, p.id_kelas, p.status FROM pendaftaran p WHERE p.id_users = $id_users");
$pendaftaran = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($pendaftaran);
