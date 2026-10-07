with open("app/Controllers/admin.php", "r", encoding="utf-8") as f:
    lines = f.readlines()

in_manajemen = False
for line in lines:
    if "public function manajemenKbm(" in line:
        in_manajemen = True
    if in_manajemen and "return view(" in line:
        print(f"manajemenKbm returns: {line.strip()}")
        break
