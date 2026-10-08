<?php
$path = 'c:/Users/LENOVO/Creativemu-Academy/app/Views/admin/validasi/index.php';
$content = file_get_contents($path);

$search = "<?php\r\n  \$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];\r\n  \$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];\r\n  \$tanggal_indo = \$hari[date('w')] . ', ' . date('d') . ' ' . \$bulan[date('n')] . ' ' . date('Y');\r\n  ?>\r\n  <div class=\"d-flex align-items-center gap-3\">\r\n      <div class=\"text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light\" style=\"font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;\">\r\n          <i class=\"far fa-calendar-alt me-2\"></i><?= \$tanggal_indo ?>\r\n      </div>";

$content = str_replace($search, "", $content);

$search2 = "<?php\n  \$hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];\n  \$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];\n  \$tanggal_indo = \$hari[date('w')] . ', ' . date('d') . ' ' . \$bulan[date('n')] . ' ' . date('Y');\n  ?>\n  <div class=\"d-flex align-items-center gap-3\">\n      <div class=\"text-muted d-none d-md-block px-3 py-2 rounded-pill bg-light\" style=\"font-size: 0.85rem; font-weight: 600; color: #794bc4 !important; white-space: nowrap; min-width: max-content;\">\n          <i class=\"far fa-calendar-alt me-2\"></i><?= \$tanggal_indo ?>\n      </div>";

$content = str_replace($search2, "", $content);
$content = str_replace("</div>\r\n      </section>", "</section>", $content);
$content = str_replace("</div>\n      </section>", "</section>", $content);
file_put_contents($path, $content);
echo "Done validasi str_replace";

