<?php

// Load CI4 bootstrap
require 'public/index.php';

$apiService = new \App\Services\LaravelApiService();
$response = $apiService->request('GET', 'classes');
echo json_encode($response, JSON_PRETTY_PRINT);


