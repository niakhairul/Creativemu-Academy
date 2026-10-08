<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$tables = $mysqli->query('SHOW TABLES');
while($table = $tables->fetch_array()) { 
    $res = $mysqli->query('DESCRIBE ' . $table[0]);
    while($row = $res->fetch_assoc()) {
        if ($row['Field'] == 'nama_kelas' || $row['Field'] == 'kategori') {
            echo $table[0] . " has " . $row['Field'] . "\n";
        }
    }
}
?>

