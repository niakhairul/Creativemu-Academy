<?php
$mysqli = new mysqli("localhost", "root", "", "creativemu_academy");
$result = $mysqli->query("DESCRIBE sertifikat");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
$mysqli->close();
?>

