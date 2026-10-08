import sys
import re

with open('app/Views/peserta/kelas.php', 'r', encoding='utf-8') as f:
    content = f.read()

pattern = r'(<p class="text-muted mb-2" style="font-size: 12px;">\s*<i class="bi bi-clock me-1".*?Jam Absensi: .*?</p>)'

def replacer(match):
    original = match.group(1)
    modified = original.replace('mb-2', 'mb-1')
    addition = """\n                                                  <?php if ($statusAbsensi === 'hadir' && !empty($absensi['waktu_absen'])): ?>
                                                  <p class="text-success mb-2 fw-semibold" style="font-size: 12px;">
                                                      <i class="bi bi-check-circle me-1"></i>
                                                      Jam Presensi: <?= date('H.i', strtotime($absensi['waktu_absen'])) ?> WIB
                                                  </p>
                                                  <?php endif; ?>"""
    return modified + addition

new_content, count = re.subn(pattern, replacer, content, flags=re.DOTALL)

if count > 0:
    with open('app/Views/peserta/kelas.php', 'w', encoding='utf-8') as f:
        f.write(new_content)
    print(f"Replacement successful. Replaced {count} occurrence(s).")
else:
    print("Could not find the target block.")