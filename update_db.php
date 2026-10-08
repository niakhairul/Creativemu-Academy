<?php
$host = 'localhost';
$db = 'creativemu_academy';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Find class Laravel Web
    $stmt = $pdo->query("SELECT id_kelas, nama_kelas FROM kelas WHERE nama_kelas LIKE '%Laravel%'");
    $row = $stmt->fetch();
    
    if ($row) {
        $id_kelas = $row['id_kelas'];
        echo "Found id_kelas: $id_kelas for " . $row['nama_kelas'] . "\n";
        
        $pdo->exec("UPDATE jadwal SET waktu_mulai = '13:00:00', waktu_selesai = '21:00:00' WHERE id_kelas = $id_kelas AND pertemuan_ke = 1");
        echo "Successfully updated jadwal.\n";
    } else {
        echo "Class not found.\n";
    }

} catch (\PDOException $e) {
    echo $e->getMessage();
}