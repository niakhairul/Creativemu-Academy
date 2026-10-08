import re

with open('app/Views/peserta/daftar_kelas.php', 'r', encoding='utf-8') as f:
    content = f.read()

target = """                            </div>
                            
                            <p class="deskripsi-kelas">
                                <?= esc($k['deskripsi']) ?>
                            </p>

                            <div class="info-list">"""

replacement = """                            </div>
                            
                            <div class="info-list mt-3">"""

content = content.replace(target, replacement)

with open('app/Views/peserta/daftar_kelas.php', 'w', encoding='utf-8') as f:
    f.write(content)
