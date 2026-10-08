<?php
define('FCPATH', __DIR__ . '/../public' . DIRECTORY_SEPARATOR);
require realpath(FCPATH . '../app/Config/Paths.php');
$paths = new Config\Paths();
require realpath($paths->systemDirectory . '/bootstrap.php');

$api = new \App\Services\LaravelApiService();
$res = $api->request('GET', '/kelas'); // Trying /kelas
print_r($res);
$res = $api->request('GET', '/classes'); // Trying /classes 
print_r($res);
$res = $api->request('GET', '/peserta/' . session()->get('id_user') . '/kelas'); // Just trying
print_r($res);
?>

