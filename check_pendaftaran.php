<?php
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass);
$stmt = $pdo->query("DESCRIBE pendaftaran");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));