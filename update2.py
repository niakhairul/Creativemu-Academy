import sys

with open('app/Views/peserta/kelas.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i in range(len(lines)):
    if '<p class="text-muted mb-2" style="font-size: 12px;">' in lines[i] and '<i class="bi bi-clock me-1"' in lines[i+1]:
        lines[i+2] = '                                                    Jam Absensi: <?= !empty($item[\'waktu_mulai\']) ? date(\'H.i\', strtotime($item[\'waktu_mulai\'])) : \'--.--\' ?> - <?= !empty($item[\'waktu_selesai\']) ? date(\'H.i\', strtotime($item[\'waktu_selesai\'])) : \'--.--\' ?>\n'

with open('app/Views/peserta/kelas.php', 'w', encoding='utf-8') as f:
    f.writelines(lines)