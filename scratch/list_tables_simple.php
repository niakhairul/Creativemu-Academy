<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('SHOW TABLES');
while($row = $res->fetch_array()) { echo $row[0] . "\n"; }
?>

