import sys
import re

with open('app/Controllers/Pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "->select('pendaftaran.*, kelas.nama_kelas, kelas.metode_pembelajaran, kelas.lokasi_pelatihan')",
    "->select('pendaftaran.*, kelas.nama_kelas')"
)

with open('app/Controllers/Pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)