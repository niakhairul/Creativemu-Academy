import re

with open("app/Views/admin/KBM/index.php", "r", encoding="utf-8") as f:
    content = f.read()

old_768 = """        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                padding: 12px;
            }
            .top-navbar {"""

new_768 = """        @media (max-width: 768px) {
            /* Let admin-responsive.css handle main-content margins & padding */
            .top-navbar {"""

if old_768 in content:
    content = content.replace(old_768, new_768)
    print("Fixed 768px media query")

with open("app/Views/admin/KBM/index.php", "w", encoding="utf-8") as f:
    f.write(content)
