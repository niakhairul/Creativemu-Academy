<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('SELECT id_kelas, pilihan_kelas, kategori_kelas FROM pendaftaran LIMIT 10');
while($row = $res->fetch_assoc()) { 
    echo $row['id_kelas'] . " | " . $row['pilihan_kelas'] . " | " . $row['kategori_kelas'] . "\n"; 
}
?>

