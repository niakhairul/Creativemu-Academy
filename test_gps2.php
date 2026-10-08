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

echo "Distance: " . round(hitungJarakGPS(-7.819000, 110.286000, -7.818933, 110.285813)) . " meter\n";