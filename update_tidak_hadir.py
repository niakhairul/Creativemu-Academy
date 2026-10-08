import sys

with open('app/Controllers/Pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("return redirect()->back()->with('success', 'Status TIDAK HADIR berhasil disimpan.');", "return redirect()->back()->with('success', 'Status TIDAK HADIR berhasil disimpan.')->with('active_tab', 'absensi');")

with open('app/Controllers/Pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)