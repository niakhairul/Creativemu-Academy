import sys
import re

with open('app/Controllers/Pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Extract prosesAbsen method
start_idx = content.find('public function prosesAbsen(')
end_idx = content.find('public function hitungJarakGPS', start_idx)

if start_idx != -1 and end_idx != -1:
    method_content = content[start_idx:end_idx]
    
    # Replace return redirect()->back()->with('error', ...)
    # using regex to add ->with('active_tab', 'absensi')
    new_method_content = re.sub(
        r"return redirect\(\)->back\(\)->with\(\s*'error',\s*(.*?)\s*\);", 
        r"return redirect()->back()->with('error', \1)->with('active_tab', 'absensi');", 
        method_content
    )
    
    content = content[:start_idx] + new_method_content + content[end_idx:]
    
    with open('app/Controllers/Pelatihan.php', 'w', encoding='utf-8') as f:
        f.write(content)
        print("Updated error redirects in prosesAbsen.")
else:
    print("Could not find method.")