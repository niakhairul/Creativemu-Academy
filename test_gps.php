<?php
function hitungJarakGPS($lat1, $lon1, $lat2, $lon2): float {
    $earthRadius = 6371000;
    $latFrom = deg2rad((float) $lat1);
    $lonFrom = deg2rad((float) $lon1);
    $latTo   = deg2rad((float) $lat2);
    $lonTo   = deg2rad((float) $lon2);
    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;
    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos($latFrom) * cos($latTo) *
         sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return (float) ($earthRadius * $c);
}

$userLat = -7.818933;
$userLng = 110.285813;
$targetLat = -7.818933;
$targetLng = 110.285813;

echo "Distance: " . round(hitungJarakGPS($userLat, $userLng, $targetLat, $targetLng)) . " meter\n";