<?php
$mysqli = new mysqli("localhost", "root", "", "creativemu_academy");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$result = $mysqli->query("DESCRIBE nilai_ujian");
while($row = $result->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
$mysqli->close();
?>

