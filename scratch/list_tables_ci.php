<?php
define('FCPATH', __DIR__ . '/../public' . DIRECTORY_SEPARATOR);
require realpath(FCPATH . '../app/Config/Paths.php');
$paths = new Config\Paths();
require realpath($paths->systemDirectory . '/bootstrap.php');
$db = \Config\Database::connect();
$tables = $db->listTables();
foreach($tables as $table) {
    echo $table . "\n";
}
?>

