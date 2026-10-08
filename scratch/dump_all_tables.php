<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$tables = $mysqli->query('SHOW TABLES');
while($table = $tables->fetch_array()) { 
    $res = $mysqli->query('DESCRIBE ' . $table[0]);
    $fields = [];
    while($row = $res->fetch_assoc()) {
        $fields[] = $row['Field'];
    }
    echo $table[0] . ": " . implode(", ", $fields) . "\n";
}
?>

