<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('DESCRIBE jadwal_kelas');
while($row = $res->fetch_assoc()) { 
    echo $row['Field'] . " - " . $row['Type'] . "\n"; 
}
?>

