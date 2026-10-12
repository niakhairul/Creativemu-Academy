<?php
// Initialize CodeIgniter
define('FCPATH', __DIR__ . '/../public' . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$api = new \App\Services\LaravelApiService();
echo "--- TRAINERS ---\n";
print_r($api->request('GET', 'trainers'));
echo "\n--- CLASSES ---\n";
print_r($api->request('GET', 'classes'));
