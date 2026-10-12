<?php
$apiKey = '73b6ffca074be0f406455e5d92bff2f1247cd0aadbbdb05d7fcac35f8299eba3';
$baseUrl = 'https://project.creativemu.id/api/';

function testEndpoint($ep) {
    global $apiKey, $baseUrl;
    $ch = curl_init($baseUrl . $ep);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-KEY: ' . $apiKey,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $json = json_decode($res, true);
    echo "$ep -> HTTP $code | ";
    if ($code == 200) {
        if (is_array($json)) {
            echo "Keys: " . implode(', ', array_keys($json)) . " | ";
            if (isset($json['data'])) {
                echo "Data count: " . (is_array($json['data']) ? count($json['data']) : 'not array') . " | ";
                if (is_array($json['data']) && !empty($json['data'][0])) {
                    echo "Sample keys: " . implode(', ', array_keys($json['data'][0]));
                }
            }
        }
    }
    echo "\n";
}

$endpoints = [
    'trainers',
    'instructors',
    'mentors',
    'employees',
    'users',
    'attendances',
    'trainer-attendances',
    'mentor-attendances',
    'instructor-attendances',
    'classes/183/sessions',
    'classes/166/sessions',
    'sessions',
    'dashboard',
    'monitoring',
    'presence',
    'absensi',
    'schedule',
    'schedules',
    'reports/attendance',
    'reports/trainers',
    'trainers/54/attendance',
    'trainers/108/attendance'
];

foreach ($endpoints as $ep) {
    testEndpoint($ep);
}

