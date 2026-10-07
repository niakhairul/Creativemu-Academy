<?php
$kelas = file_get_contents('app/Views/peserta/kelas.php');
$angket = file_get_contents('app/Views/peserta/angket.php');

// Extract top from kelas.php (up to line 372: <div class="container-fluid py-1">)
// The split text could be: <div class="container-fluid py-1">
$kelas_parts = explode('<div class="container-fluid py-1">', $kelas);
$top_shell = $kelas_parts[0] . '<div class="container-fluid py-1">' . "\n";

// Extract bottom from kelas.php (from JS to end)
// Wait, the bottom is just closing tags and JS for sidebar toggle.
$bottom_shell = '
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Toggle Sidebar Mobile
    const toggleBtn = document.getElementById("sidebarToggle");
    const closeBtn = document.getElementById("sidebarClose");
    const sidebar = document.getElementById("sidebarMenu");
    const backdrop = document.getElementById("sidebarBackdrop");

    function openSidebar() {
        if (sidebar) sidebar.classList.add("show");
        if (backdrop) backdrop.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove("show");
        if (backdrop) backdrop.classList.remove("show");
        document.body.style.overflow = "";
    }

    if (toggleBtn) toggleBtn.addEventListener("click", openSidebar);
    if (closeBtn) closeBtn.addEventListener("click", closeSidebar);
    if (backdrop) backdrop.addEventListener("click", closeSidebar);
});
</script>
</body>
</html>
';

// Remove dashboard_template extend, section('content') and endSection()
$angket = preg_replace('/<\?= \$this->extend\([^)]+\) \?>/i', '', $angket);
$angket = preg_replace('/<\?= \$this->section\([\'"]content[\'"]\) \?>/i', '', $angket);
$angket = str_replace('<?= $this->endSection() ?>', '', $angket);

// Remove the <style> I added earlier since it will be in the top shell now
$angket = preg_replace('/<style>.*?<\/style>/is', '', $angket);

// I noticed the container in angket.php is <div class="container-fluid">
// Let's replace <div class="container-fluid"> with empty string, since top_shell has it, OR just keep it and let it be nested, but it's better to remove it.
$angket = preg_replace('/<div class="container-fluid">/', '', $angket, 1);
$angket = preg_replace('/<\/div>[\s]*<script>/', '<script>', $angket); // attempt to remove the closing div of container-fluid

$final_angket = $top_shell . $angket . $bottom_shell;

file_put_contents('app/Views/peserta/angket.php', $final_angket);
echo "Angket updated successfully.\n";

