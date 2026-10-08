<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('SELECT * FROM migrations');
while($row = $res->fetch_assoc()) { 
    echo $row['version'] . " - " . $row['class'] . "\n"; 
}
?>

