<?php
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->query("SELECT id_pendaftaran, lokasi_pelatihan, metode_pembelajaran FROM pendaftaran WHERE id_kelas = 2 AND id_users = 18");
print_r($stmt->fetch(PDO::FETCH_ASSOC));