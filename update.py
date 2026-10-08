import sys

with open('app/Views/peserta/kelas.php', 'r', encoding='utf-8') as f:
    lines = f.readlines()

for i in range(len(lines)):
    if '<p class="text-muted mb-2" style="font-size: 12px;">' in lines[i] and '<i class="bi bi-calendar-event me-1"' in lines[i+1]:
        lines[i+1] = '                                                    <i class="bi bi-clock me-1" style="color: var(--color-orange);"></i>\n'
        lines[i+2] = '                                                    Jam Absensi: <?= !empty([\'waktu_mulai\']) ? date(\'H.i\', strtotime([\'waktu_mulai\'])) : \'--.--\' ?> - <?= !empty([\'waktu_selesai\']) ? date(\'H.i\', strtotime([\'waktu_selesai\'])) : \'--.--\' ?>\n'
        lines[i+3] = ''
        lines[i+4] = ''
        lines[i+5] = ''

with open('app/Views/peserta/kelas.php', 'w', encoding='utf-8') as f:
    f.writelines(lines)