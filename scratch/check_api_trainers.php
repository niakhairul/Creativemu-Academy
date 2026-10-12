<?php
$apiKey = '73b6ffca074be0f406455e5d92bff2f1247cd0aadbbdb05d7fcac35f8299eba3';
$baseUrl = 'https://project.creativemu.id/api/';

$ch = curl_init($baseUrl . 'trainers');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-KEY: ' . $apiKey, 'Accept: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = json_decode(curl_exec($ch), true);

$allKeys = [];
foreach (($res['data'] ?? []) as $t) {
    foreach (array_keys($t) as $k) {
        $allKeys[$k] = true;
    }
}
echo "All keys in trainers list: " . implode(', ', array_keys($allKeys)) . "\n";

// Print first 5 trainers completely
print_r(array_slice($res['data'] ?? [], 0, 5));

