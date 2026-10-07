with open("app/Controllers/admin.php", "r", encoding="utf-8") as f:
    lines = f.readlines()

in_validasi = False
for line in lines:
    if "public function validasi(" in line:
        in_validasi = True
    if in_validasi and "return view(" in line:
        print(f"validasi returns: {line.strip()}")
        break

# Let's find KBM related methods
for i, line in enumerate(lines):
    if "public function " in line and "kbm" in line.lower():
        print(f"Method: {line.strip()}")
