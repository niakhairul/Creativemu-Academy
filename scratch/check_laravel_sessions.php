<?php
$apiKey = '73b6ffca074be0f406455e5d92bff2f1247cd0aadbbdb05d7fcac35f8299eba3';
$baseUrl = 'https://project.creativemu.id/api/';

function callApi($ep) {
    global $apiKey, $baseUrl;
    $ch = curl_init($baseUrl . $ep);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-KEY: ' . $apiKey, 'Accept: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 8);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $code, 'body' => json_decode($res, true), 'raw' => $res];
}

$classesRes = callApi('classes');
$classes = $classesRes['body']['data'] ?? [];
echo "Total classes: " . count($classes) . "\n";

$tested = 0;
foreach ($classes as $c) {
    $id = $c['id'];
    $sess = callApi("classes/{$id}/sessions");
    echo "Class $id ({$c['name']}) -> sessions HTTP {$sess['code']}\n";
    if ($sess['code'] == 200) {
        print_r($sess['body']);
        break;
    }
    $tested++;
    if ($tested >= 5) break;
}

