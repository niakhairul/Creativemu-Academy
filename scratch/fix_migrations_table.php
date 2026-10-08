<?php
$mysqli = new mysqli('localhost', 'root', '', 'creativemu_academy');
$res = $mysqli->query('ALTER TABLE migrations MODIFY id bigint(20) unsigned AUTO_INCREMENT;');
if($res) echo "Success";
else echo "Error: " . $mysqli->error;
?>

