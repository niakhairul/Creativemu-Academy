<?php
$path = 'app/Views/admin/angket/index.php';
$content = file_get_contents($path);
if (strpos($content, 'function updateDate()') === false) {
    $js = "    <script>\n        function updateDate() {\n            const now = new Date();\n            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };\n            const dateStr = now.toLocaleDateString('id-ID', options);\n            document.querySelectorAll('.current-date').forEach(el => {\n                el.innerText = dateStr;\n            });\n        }\n        setInterval(updateDate, 1000);\n        updateDate();\n    </script>\n</body>";
    $content = preg_replace('/<\/body>/i', $js, $content);
    file_put_contents($path, $content);
}

