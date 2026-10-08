import sys

with open('app/Controllers/Pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_error = "return redirect()->back()->with('error', 'Anda berada di luar area pelatihan. Silakan menuju lokasi pelatihan untuk melakukan absensi.')->with('active_tab', 'absensi');"
new_error = "return redirect()->back()->with('error', 'Anda berada di luar area pelatihan. Silakan menuju lokasi pelatihan untuk melakukan absensi. (Jarak: ' . $jarakMeter . 'm | Target: ' . $targetLat . ',' . $targetLng . ' | Anda: ' . $userLat . ',' . $userLng . ')')->with('active_tab', 'absensi');"

content = content.replace(old_error, new_error)

with open('app/Controllers/Pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)