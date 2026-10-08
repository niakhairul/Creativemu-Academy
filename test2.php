<?php
require 'public/index.php';
$api = new \App\Services\LaravelApiService();
$res = $api->request('GET', 'classes');
echo "Count: " . count($res['data'] ?? []) . "\n";
var_dump($res);

