<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('SELECT id_kelas FROM jadwal LIMIT 5');
while($row = $res->fetch_assoc()) { echo $row['id_kelas'] . "\n"; }
?>

