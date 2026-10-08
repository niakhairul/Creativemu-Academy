<?php
$env = parse_ini_file('.env');
$mysqli = new mysqli($env['database.default.hostname'], $env['database.default.username'], $env['database.default.password'], $env['database.default.database']);
$res = $mysqli->query('SHOW TABLES');
while($row = $res->fetch_array()) { echo $row[0] . "\n"; }
?>
