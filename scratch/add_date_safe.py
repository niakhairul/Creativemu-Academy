import os
import re

dir_path = 'c:/Users/LENOVO/Creativemu-Academy/app/Views/admin'

php_date_block = """<?php
$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal_indo = $hari[date('w')] . ', ' . date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
?>
<div class="text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light" style="font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;">
    <i class="far fa-calendar-alt me-2"></i><?= $tanggal_indo ?>
</div>"""

def process_file(filepath):
    normalized = filepath.replace('\\', '/')
    if 'sidebar' in normalized:
        return
    if '/laporan/' in normalized:
        return
        
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    if 'fa-calendar-alt' in content and '$tanggal_indo' in content:
        return

    # Replace Memuat tanggal div safely
    # We look specifically for the div that has current-date or Memuat tanggal...
    # Since they are on multiple lines, we use a regex that doesn't span other divs.
    # Pattern: <div[^>]*current-date[^>]*>.*?Memuat tanggal\.\.\..*?</div>
    # or just replace the inner part? 
    # Actually, the div usually is:
    # <div class="text-muted d-none d-md-block px-3 py-1 rounded-pill bg-light" id="current-date" style="font-size: 0.78rem; font-weight: 600; color: #794bc4 !important;">
    #     Memuat tanggal...
    # </div>
    # We can match <div[^>]*Memuat tanggal\.\.\.<\/div> if we are careful, or better yet, just replace by finding `Memuat tanggal...` and backtracking to the `<div` and forward to `</div>`.

    if "Memuat tanggal..." in content:
        # Find index of "Memuat tanggal..."
        idx = content.find("Memuat tanggal...")
        # Find the preceding "<div "
        start_idx = content.rfind("<div ", 0, idx)
        # Find the succeeding "</div>"
        end_idx = content.find("</div>", idx) + 6
        
        # Replace this slice
        if start_idx != -1 and end_idx != -1:
            content = content[:start_idx] + php_date_block + content[end_idx:]
            with open(filepath, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Replaced Memuat tanggal in {filepath}")
            return

    # If it doesn't have Memuat tanggal...
    if '<div class="admin-profile"' in content:
        if 'validasi/index.php' in normalized:
            # Wrap
            content = content.replace('<div class="admin-profile"', f"<div class=\"d-flex align-items-center gap-3\">\n{php_date_block}\n<div class=\"admin-profile\"", 1)
            content = content.replace('</section>', '</div>\n    </section>', 1)
        else:
            content = content.replace('<div class="admin-profile"', f"{php_date_block}\n<div class=\"admin-profile\"", 1)
            
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Injected date in {filepath}")


for root, dirs, files in os.walk(dir_path):
    for name in files:
        if name.endswith('.php'):
            process_file(os.path.join(root, name))

