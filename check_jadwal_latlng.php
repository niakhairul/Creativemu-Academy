<?php
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->query("SELECT id_jadwal, latitude, longitude FROM jadwal WHERE id_jadwal = 1");
print_r($stmt->fetch(PDO::FETCH_ASSOC));