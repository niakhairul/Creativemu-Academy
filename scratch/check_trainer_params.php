<?php
$apiKey = '73b6ffca074be0f406455e5d92bff2f1247cd0aadbbdb05d7fcac35f8299eba3';
$baseUrl = 'https://project.creativemu.id/api/';

function getJson($url) {
    global $apiKey;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-KEY: ' . $apiKey, 'Accept: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    return json_decode($res, true);
}

// Test with parameters
$res1 = getJson($baseUrl . 'trainers?all=1');
$res2 = getJson($baseUrl . 'trainers?full=1');
$res3 = getJson($baseUrl . 'trainers?details=1');

echo "With all=1 keys: " . implode(', ', array_keys($res1['data'][0] ?? [])) . "\n";
echo "With full=1 keys: " . implode(', ', array_keys($res2['data'][0] ?? [])) . "\n";
echo "With details=1 keys: " . implode(', ', array_keys($res3['data'][0] ?? [])) . "\n";

