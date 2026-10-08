import sys
import re

with open('app/Controllers/Pelatihan.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Fix prosesAbsenGps
old_gps = """        $pendaftaran = $this->approvedEnrollment();
        if (!$pendaftaran) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Anda belum memiliki kelas yang disetujui.'
            ]);
        }

        $db = \Config\Database::connect();
        $jadwal = $db->table('jadwal')
            ->where('id_jadwal', $idJadwal)
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->get()
            ->getRowArray();

        if (!$jadwal && $db->tableExists('jadwal_kelas')) {
            $jadwal = $db->table('jadwal_kelas')
                ->where('id_jadwal_kelas', $idJadwal)
                ->where('id_kelas', $pendaftaran['id_kelas'])
                ->get()
                ->getRowArray();
        }"""

new_gps = """        $db = \Config\Database::connect();
        $jadwal = $db->table('jadwal')
            ->where('id_jadwal', $idJadwal)
            ->get()
            ->getRowArray();

        if (!$jadwal && $db->tableExists('jadwal_kelas')) {
            $jadwal = $db->table('jadwal_kelas')
                ->where('id_jadwal_kelas', $idJadwal)
                ->get()
                ->getRowArray();
        }

        if (!$jadwal) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Jadwal pertemuan tidak ditemukan.'
            ]);
        }

        $pendaftaran = (new \App\Models\PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.metode_pembelajaran, kelas.lokasi_pelatihan')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $userId)
            ->where('pendaftaran.id_kelas', $jadwal['id_kelas'])
            ->groupStart()
                ->where('pendaftaran.status', 'Disetujui')
                ->orWhere('pendaftaran.status_pembayaran', 'valid')
            ->groupEnd()
            ->first();

        if (!$pendaftaran) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Anda tidak terdaftar atau belum disetujui untuk kelas ini.'
            ]);
        }"""

# Fix prosesAbsen
old_absen = """        $pendaftaran = $this->approvedEnrollment();
        if (!$pendaftaran) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Anda belum memiliki kelas yang disetujui.');
        }

        $db = \Config\Database::connect();

        // 1. Ambil jadwal dari tabel jadwal (atau fallback jadwal_kelas)
        $jadwal = $db->table('jadwal')
            ->where('id_jadwal', $idJadwal)
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->get()
            ->getRowArray();

        if (!$jadwal && $db->tableExists('jadwal_kelas')) {
            $jadwal = $db->table('jadwal_kelas')
                ->where('id_jadwal_kelas', $idJadwal)
                ->where('id_kelas', $pendaftaran['id_kelas'])
                ->get()
                ->getRowArray();
        }"""

new_absen = """        $db = \Config\Database::connect();

        // 1. Ambil jadwal dari tabel jadwal (atau fallback jadwal_kelas)
        $jadwal = $db->table('jadwal')
            ->where('id_jadwal', $idJadwal)
            ->get()
            ->getRowArray();

        if (!$jadwal && $db->tableExists('jadwal_kelas')) {
            $jadwal = $db->table('jadwal_kelas')
                ->where('id_jadwal_kelas', $idJadwal)
                ->get()
                ->getRowArray();
        }

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal pertemuan tidak ditemukan untuk kelas Anda.')->with('active_tab', 'absensi');
        }

        $pendaftaran = (new \App\Models\PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.metode_pembelajaran, kelas.lokasi_pelatihan')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->where('pendaftaran.id_kelas', $jadwal['id_kelas'])
            ->groupStart()
                ->where('pendaftaran.status', 'Disetujui')
                ->orWhere('pendaftaran.status_pembayaran', 'valid')
            ->groupEnd()
            ->first();

        if (!$pendaftaran) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Anda belum memiliki kelas yang disetujui untuk sesi ini.');
        }"""

content = content.replace(old_gps, new_gps)
content = content.replace(old_absen, new_absen)

# Also remove the duplicate empty check for jadwal in prosesAbsen (since we moved it above pendaftaran check)
# Let's just find `if (!$jadwal) { \n            return redirect()->back()->with('error', 'Jadwal pertemuan tidak ditemukan untuk kelas Anda.')->with('active_tab', 'absensi'); \n        }` after our new insertion and remove it to avoid double check.
content = re.sub(r"        if \(!\$jadwal\) \{\s*return redirect\(\)->back\(\)->with\('error', 'Jadwal pertemuan tidak ditemukan untuk kelas Anda\.'\)->with\('active_tab', 'absensi'\);\s*\}\s*// 2\. Cek apakah absensi dibuka", r"        // 2. Cek apakah absensi dibuka", content)

with open('app/Controllers/Pelatihan.php', 'w', encoding='utf-8') as f:
    f.write(content)