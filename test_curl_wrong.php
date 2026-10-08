<?php
$url = "https://project.creativemu.id/classes";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-API-KEY: 73b6ffca074be0f406455e5d92bff2f1247cd0aadbbdb05d7fcac35f8299eba3",
    "Accept: application/json"
]);
$response = curl_exec($ch);
curl_close($ch);
echo substr($response, 0, 500); // Print first 500 chars to see what it is

