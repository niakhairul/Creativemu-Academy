<?php
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->query("SELECT id_jadwal, pertemuan_ke, waktu_mulai, waktu_selesai FROM jadwal WHERE id_kelas = 2");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));