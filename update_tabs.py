import sys

with open('app/Views/peserta/kelas.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Add PHP block above the tabs if not present
if "<?php $activeTab" not in content:
    content = content.replace('<!-- NAV TABS -->', '<?php $activeTab = session()->getFlashdata(\'active_tab\') ?? \'materi\'; ?>\n            <!-- NAV TABS -->')

# Update Nav Links
content = content.replace('<button class="nav-link active" id="materi-tab"', '<button class="nav-link <?= $activeTab === \'materi\' ? \'active\' : \'\' ?>" id="materi-tab"')
content = content.replace('<button class="nav-link" id="absensi-tab"', '<button class="nav-link <?= $activeTab === \'absensi\' ? \'active\' : \'\' ?>" id="absensi-tab"')

# Update Tab Panes
content = content.replace('<div class="tab-pane fade show active" id="materi" role="tabpanel">', '<div class="tab-pane fade <?= $activeTab === \'materi\' ? \'show active\' : \'\' ?>" id="materi" role="tabpanel">')
content = content.replace('<div class="tab-pane fade" id="absensi" role="tabpanel">', '<div class="tab-pane fade <?= $activeTab === \'absensi\' ? \'show active\' : \'\' ?>" id="absensi" role="tabpanel">')

with open('app/Views/peserta/kelas.php', 'w', encoding='utf-8') as f:
    f.write(content)