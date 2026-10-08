import sys
import re

with open('app/Controllers/Pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace userLat casting
old_lat_cast = """        $userLat = (float) $userLat;
        $userLng = (float) $userLng;"""

new_lat_cast = """        $userLat = (float) str_replace(',', '.', (string)$userLat);
        $userLng = (float) str_replace(',', '.', (string)$userLng);"""

content = content.replace(old_lat_cast, new_lat_cast)

with open('app/Controllers/Pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)