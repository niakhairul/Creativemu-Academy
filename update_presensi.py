import sys

with open('app/Views/peserta/kelas.php', 'r', encoding='utf-8') as f:
    content = f.read()

old_block = """                                                  <p class="text-muted mb-2" style="font-size: 12px;">
                                                      <i class="bi bi-clock me-1" style="color: var(--color-orange);"></i>
                                                      Jam Absensi: <?= !empty($item['waktu_mulai']) ? date('H.i', strtotime($item['waktu_mulai'])) : '--.--' ?> - <?= !empty($item['waktu_selesai']) ? date('H.i', strtotime($item['waktu_selesai'])) : '--.--' ?>
                                                  </p>"""

new_block = """                                                  <p class="text-muted mb-1" style="font-size: 12px;">
                                                      <i class="bi bi-clock me-1" style="color: var(--color-orange);"></i>
                                                      Jam Absensi: <?= !empty($item['waktu_mulai']) ? date('H.i', strtotime($item['waktu_mulai'])) : '--.--' ?> - <?= !empty($item['waktu_selesai']) ? date('H.i', strtotime($item['waktu_selesai'])) : '--.--' ?>
                                                  </p>
                                                  <?php if ($statusAbsensi === 'hadir' && !empty($absensi['waktu_absen'])): ?>
                                                  <p class="text-success mb-2 fw-semibold" style="font-size: 12px;">
                                                      <i class="bi bi-check-circle me-1"></i>
                                                      Jam Presensi: <?= date('H.i', strtotime($absensi['waktu_absen'])) ?> WIB
                                                  </p>
                                                  <?php endif; ?>"""

if old_block in content:
    content = content.replace(old_block, new_block)
    with open('app/Views/peserta/kelas.php', 'w', encoding='utf-8') as f:
        f.write(content)
    print("Replacement successful.")
else:
    print("Could not find the target block.")
