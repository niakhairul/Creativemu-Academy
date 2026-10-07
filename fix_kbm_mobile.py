import re

with open("app/Views/admin/KBM/index.php", "r", encoding="utf-8") as f:
    content = f.read()

# Add CSS and JS to <head>
head_addition = """    <!-- Admin Responsive Styles & Scripts -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-responsive.css'); ?>">
    <script defer src="<?= base_url('assets/js/admin-responsive.js'); ?>"></script>
</head>"""

if "</head>" in content and "admin-responsive.css" not in content:
    content = content.replace("</head>", head_addition)
    print("Added responsive assets to head")

# Fix the media queries to avoid overlapping
old_mq = """        @media (max-width: 1200px) {
            #main-content {
                margin-left: 70px;
            }
            .table-custom {
                min-width: 1050px;
            }
        }"""

new_mq = """        @media (max-width: 1200px) {
            /* Let admin-responsive.css handle main-content margins */
            .table-custom {
                min-width: 1050px;
            }
        }"""

if old_mq in content:
    content = content.replace(old_mq, new_mq)
    print("Fixed 1200px media query")

with open("app/Views/admin/KBM/index.php", "w", encoding="utf-8") as f:
    f.write(content)
