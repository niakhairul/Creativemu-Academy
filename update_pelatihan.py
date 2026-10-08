import sys

with open('app/Controllers/Pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

# For Online
old_online = "return redirect()->back()->with('success', 'Absensi berhasil! Anda berhasil melakukan absensi Pertemuan ' . $jadwal['pertemuan_ke'] . '.');"
new_online = "return redirect()->back()->with('success', 'Absensi berhasil! Anda berhasil melakukan absensi Pertemuan ' . $jadwal['pertemuan_ke'] . '.')->with('active_tab', 'absensi');"
content = content.replace(old_online, new_online)

# For Offline
old_offline = "return redirect()->back()->with(\n            'success',\n            'Absensi berhasil! Anda berhasil melakukan absensi Pertemuan ' . $jadwal['pertemuan_ke'] . ' (Terverifikasi ' . $jarakMeter . ' meter dari pusat area).'\n        );"
new_offline = "return redirect()->back()->with(\n            'success',\n            'Absensi berhasil! Anda berhasil melakukan absensi Pertemuan ' . $jadwal['pertemuan_ke'] . ' (Terverifikasi ' . $jarakMeter . ' meter dari pusat area).'\n        )->with('active_tab', 'absensi');"
content = content.replace(old_offline, new_offline)

with open('app/Controllers/Pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)