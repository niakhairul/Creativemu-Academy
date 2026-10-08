<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('DESCRIBE migrations');
while($row = $res->fetch_assoc()) { 
    echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Extra'] . "\n"; 
}
?>

