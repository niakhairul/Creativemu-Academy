<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('SELECT * FROM kelas LIMIT 1');
if (!$res) {
    echo "Error: " . $mysqli->error . "\n";
} else {
    echo "Success! Columns:\n";
    while ($f = $res->fetch_field()) {
        echo $f->name . "\n";
    }
}
?>

