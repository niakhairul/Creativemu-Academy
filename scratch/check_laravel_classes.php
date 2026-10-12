<?php
$apiKey = '73b6ffca074be0f406455e5d92bff2f1247cd0aadbbdb05d7fcac35f8299eba3';

function req($ep) {
    global $apiKey;
    $ch = curl_init("https://project.creativemu.id/api/$ep");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-KEY: ' . $apiKey,
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$code, json_decode($res, true)];
}

$endpoints = ['classes', 'trainers', 'trainings', 'materials', 'media', 'locations'];
foreach ($endpoints as $ep) {
    list($code, $data) = req($ep);
    echo "$ep: HTTP $code - success=" . ($data['success'] ?? 'n/a') . "\n";
    if ($code === 200 && !empty($data['data'])) {
        $first = is_array($data['data']) ? ($data['data'][0] ?? $data['data']) : null;
        if (is_array($first)) {
            echo "  Keys: " . implode(', ', array_keys($first)) . "\n";
            // Check if any key or value has lokasi_media
            $json = json_encode($data['data']);
            if (stripos($json, 'lokasi_media') !== false) {
                echo "  FOUND 'lokasi_media' in $ep!\n";
            }
        }
    }
}

