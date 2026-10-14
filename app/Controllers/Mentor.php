<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalModel;

class Mentor extends BaseController
{
    protected $db;
    private ?array $mentorLogin = null;

    public function __construct() { $this->db = \Config\Database::connect(); }

    private function requireMentor()
    {
        return (! session()->get('logged_in') || session()->get('role') !== 'mentor')
            ? redirect()->to(base_url('pelatihan/login'))->with('error', 'Silakan masuk sebagai mentor terlebih dahulu.') : null;
    }

    private function mentorLogin(): ?array
    {
        if ($this->mentorLogin !== null) return $this->mentorLogin;
        $userId = (int) session()->get('id_users');
        $userEmail = strtolower(trim((string) session()->get('email')));

        // 1. Cek di tabel lokal mentor
        if ($this->db->tableExists('mentor')) {
            if ($userId > 0 && $this->db->fieldExists('id_users', 'mentor')) {
                $this->mentorLogin = $this->db->table('mentor')->where('id_users', $userId)->get()->getRowArray();
            }
            if (!$this->mentorLogin && $userId > 0 && $this->db->fieldExists('id_user', 'mentor')) {
                $this->mentorLogin = $this->db->table('mentor')->where('id_user', $userId)->get()->getRowArray();
            }
            if (!$this->mentorLogin && $userEmail !== '' && $this->db->fieldExists('email', 'mentor')) {
                $this->mentorLogin = $this->db->table('mentor')->where('LOWER(email)', $userEmail)->get()->getRowArray();
            }
        }

        // 2. Jika tidak ada di lokal, cari dari Laravel API (trainers)
        if (!$this->mentorLogin) {
            try {
                $apiService = new \App\Services\LaravelApiService();
                $respT = $apiService->request('GET', 'trainers');
                $trainersApi = $respT['data'] ?? $respT ?? [];
                if (!empty($trainersApi) && is_array($trainersApi)) {
                    foreach ($trainersApi as $t) {
                        $tEmail = strtolower(trim((string)($t['email'] ?? '')));
                        $tId = (int)($t['id'] ?? 0);
                        if (($userEmail !== '' && $tEmail === $userEmail) || ($userId > 0 && $tId === $userId)) {
                            $this->mentorLogin = [
                                'id_mentor'   => $tId,
                                'id_users'    => $userId > 0 ? $userId : $tId,
                                'nama_mentor' => $t['name'] ?? $t['nama'] ?? session()->get('nama') ?? 'Mentor',
                                'email'       => $t['email'] ?? $userEmail,
                                'nip'         => $t['nip'] ?? '-',
                                'keahlian'    => 'Instruktur Ahli',
                                'classes'     => $t['classes'] ?? []
                            ];
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('notice', 'Mentor::mentorLogin API note: ' . $e->getMessage());
            }
        }

        // 3. Fallback default jika role adalah mentor
        if (!$this->mentorLogin && session()->get('role') === 'mentor') {
            $this->mentorLogin = [
                'id_mentor'   => $userId > 0 ? $userId : 1,
                'id_users'    => $userId > 0 ? $userId : 1,
                'nama_mentor' => session()->get('nama') ?? 'Mentor',
                'email'       => $userEmail,
                'nip'         => '-',
                'keahlian'    => 'Instruktur Ahli',
                'classes'     => []
            ];
        }

        return $this->mentorLogin;
    }

    private function kelasMilikMentor(int $idKelas): ?array
    {
        $mentor = $this->mentorLogin();
        if (!$mentor) return null;

        // 1. Cek dari tabel lokal kelas
        if ($this->db->tableExists('kelas')) {
            $local = $this->db->table('kelas')->where(['id_kelas' => $idKelas, 'id_mentor' => $mentor['id_mentor']])->get()->getRowArray();
            if ($local) return $local;
        }

        // 2. Cek dari Laravel API
        try {
            $apiService = new \App\Services\LaravelApiService();
            $res = $apiService->request('GET', 'classes/' . $idKelas);
            $c = $res['data'] ?? $res ?? null;
            if (!empty($c) && isset($c['id'])) {
                $isMatch = false;
                $trainerClassIds = !empty($mentor['classes']) && is_array($mentor['classes']) ? $mentor['classes'] : [];
                if (in_array((int)$c['id'], $trainerClassIds)) {
                    $isMatch = true;
                } elseif (!empty($c['trainers']) && is_array($c['trainers'])) {
                    $mId = (int)$mentor['id_mentor'];
                    $uId = (int)($mentor['id_users'] ?? 0);
                    $mName = strtolower(trim($mentor['nama_mentor'] ?? ''));
                    $mEmail = strtolower(trim($mentor['email'] ?? ''));
                    foreach ($c['trainers'] as $tr) {
                        $trId = (int)($tr['id'] ?? 0);
                        $trName = strtolower(trim($tr['name'] ?? ''));
                        $trEmail = strtolower(trim($tr['email'] ?? ''));
                        if ($trId === $mId || $trId === $uId || ($mEmail !== '' && $trEmail === $mEmail) || ($mName !== '' && $trName === $mName)) {
                            $isMatch = true;
                            break;
                        }
                    }
                }

                // Jika mentor yang login cocok atau kelas belum memiliki pengampu spesifik
                if ($isMatch || empty($c['trainers']) || session()->get('role') === 'admin' || session()->get('role') === 'mentor') {
                    $cat = $c['training']['name'] ?? $c['jenis_kelas'] ?? 'Umum';
                    $loc = !empty($c['schedule']['location']) ? $c['schedule']['location'] : 'Kantor Pusat';
                    return [
                        'id_kelas'              => (int)$c['id'],
                        'id_mentor'             => $mentor['id_mentor'],
                        'nama_kelas'            => $c['name'] ?? 'Kelas Pelatihan',
                        'kategori'              => $cat,
                        'lokasi_pelatihan'      => $loc,
                        'kapasitas'             => $c['capacity']['target_students'] ?? $c['capacity'] ?? 30,
                        'tanggal_mulai_kelas'   => $c['schedule']['start_date'] ?? null,
                        'tanggal_selesai_kelas' => $c['schedule']['end_date'] ?? null,
                        'jumlah_pertemuan'      => $c['schedule']['meetings'] ?? 10,
                        'waktu_mulai'           => $c['schedule']['start_time'] ?? '08:00',
                        'waktu_selesai'         => $c['schedule']['end_time'] ?? '10:00',
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('notice', 'Mentor::kelasMilikMentor API check note: ' . $e->getMessage());
        }

        return null;
    }

    private function pesertaQuery(int $idKelas)
    {
        return $this->db->table('pendaftaran')->select('pendaftaran.*, users.nama AS nama_user, users.email AS email_user, users.no_hp AS no_hp_user')
            ->join('users', 'users.id_users = pendaftaran.id_users OR users.email = pendaftaran.email', 'left')->where('pendaftaran.id_kelas', $idKelas)
            ->groupStart()
                ->whereIn('pendaftaran.status_pembayaran', ['valid', 'Valid', 'Disetujui', 'approved'])
                ->orWhereIn('pendaftaran.status', ['disetujui', 'Disetujui', 'approved'])
                ->orWhereIn('pendaftaran.status_pendaftaran', ['disetujui', 'Disetujui', 'approved'])
            ->groupEnd();
    }

    public function dashboard()
    {
        if ($r = $this->requireMentor()) return $r;
        $mentor = $this->mentorLogin();
        if (! $mentor) return redirect()->to(base_url('logout'))->with('error', 'Profil mentor tidak ditemukan. Hubungi admin.');
        
        $kelas = [];
        if ($this->db->tableExists('kelas')) {
            $kelas = $this->db->table('kelas')->where('id_mentor', $mentor['id_mentor'])->orderBy('tanggal_mulai_kelas', 'ASC')->get()->getResultArray();
        }

        // Jika lokal kosong, ambil dari Laravel API
        if (empty($kelas)) {
            try {
                $apiService = new \App\Services\LaravelApiService();
                $respC = $apiService->request('GET', 'classes');
                $classesApi = $respC['data'] ?? $respC ?? [];
                if (!empty($classesApi) && is_array($classesApi)) {
                    $mId = (int)$mentor['id_mentor'];
                    $uId = (int)($mentor['id_users'] ?? 0);
                    $mName = strtolower(trim($mentor['nama_mentor'] ?? ''));
                    $mEmail = strtolower(trim($mentor['email'] ?? ''));
                    $trainerClassIds = !empty($mentor['classes']) && is_array($mentor['classes']) ? $mentor['classes'] : [];

                    foreach ($classesApi as $c) {
                        $isMatch = false;
                        if (in_array((int)$c['id'], $trainerClassIds)) {
                            $isMatch = true;
                        } elseif (!empty($c['trainers']) && is_array($c['trainers'])) {
                            foreach ($c['trainers'] as $tr) {
                                $trId = (int)($tr['id'] ?? 0);
                                $trName = strtolower(trim($tr['name'] ?? ''));
                                $trEmail = strtolower(trim($tr['email'] ?? ''));
                                if ($trId === $mId || $trId === $uId || ($mEmail !== '' && $trEmail === $mEmail) || ($mName !== '' && $trName === $mName)) {
                                    $isMatch = true;
                                    break;
                                }
                            }
                        }

                        if ($isMatch || (empty($trainerClassIds) && empty($c['trainers']))) {
                            $cat = $c['training']['name'] ?? $c['jenis_kelas'] ?? 'Umum';
                            $loc = !empty($c['schedule']['location']) ? $c['schedule']['location'] : 'Kantor Pusat';
                            $kelas[] = [
                                'id_kelas'              => (int)$c['id'],
                                'id_mentor'             => $mId,
                                'nama_kelas'            => $c['name'] ?? 'Kelas Pelatihan',
                                'kategori'              => $cat,
                                'lokasi_pelatihan'      => $loc,
                                'kapasitas'             => $c['capacity']['target_students'] ?? $c['capacity'] ?? 30,
                                'tanggal_mulai_kelas'   => $c['schedule']['start_date'] ?? null,
                                'tanggal_selesai_kelas' => $c['schedule']['end_date'] ?? null,
                                'jumlah_pertemuan'      => $c['schedule']['meetings'] ?? 10,
                                'waktu_mulai'           => $c['schedule']['start_time'] ?? '08:00',
                                'waktu_selesai'         => $c['schedule']['end_time'] ?? '10:00',
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('notice', 'Mentor::dashboard API classes fetch error: ' . $e->getMessage());
            }
        }

        $idKelas = array_column($kelas, 'id_kelas');
        $totalPeserta = $idKelas ? $this->db->table('pendaftaran')->whereIn('id_kelas', $idKelas)->groupStart()->whereIn('status_pembayaran', ['valid', 'Valid', 'Disetujui', 'approved'])->orWhereIn('status', ['disetujui', 'Disetujui', 'approved'])->orWhereIn('status_pendaftaran', ['disetujui', 'Disetujui', 'approved'])->groupEnd()->countAllResults() : 0;
        $totalMateri = ($idKelas && $this->db->tableExists('materi')) ? $this->db->table('materi')->whereIn('id_kelas', $idKelas)->countAllResults() : 0;
        foreach ($kelas as &$item) {
            $item['jumlah_peserta'] = $this->pesertaQuery((int) $item['id_kelas'])->countAllResults();
            $kapasitas = (int) ($item['kapasitas'] ?? 0);
            $item['persentase_peserta'] = $kapasitas > 0 ? min(100, (int) round(($item['jumlah_peserta'] / $kapasitas) * 100)) : 0;
        }
        unset($item);
        return view('mentor/dashboard', ['total_kelas' => count($kelas), 'total_peserta' => $totalPeserta, 'total_materi' => $totalMateri, 'kelas_terbaru' => array_slice($kelas, 0, 5)]);
    }

    public function kelas()
    {
        if ($r = $this->requireMentor()) return $r;
        $mentor = $this->mentorLogin();
        if (! $mentor) return redirect()->to(base_url('logout'))->with('error', 'Profil mentor tidak ditemukan. Hubungi admin.');
        
        $kelas = [];
        if ($this->db->tableExists('kelas')) {
            $kelas = $this->db->table('kelas')->where('id_mentor', $mentor['id_mentor'])->orderBy('tanggal_mulai_kelas', 'DESC')->get()->getResultArray();
        }

        // Jika lokal kosong, ambil dari Laravel API
        if (empty($kelas)) {
            try {
                $apiService = new \App\Services\LaravelApiService();
                $respC = $apiService->request('GET', 'classes');
                $classesApi = $respC['data'] ?? $respC ?? [];
                if (!empty($classesApi) && is_array($classesApi)) {
                    $mId = (int)$mentor['id_mentor'];
                    $uId = (int)($mentor['id_users'] ?? 0);
                    $mName = strtolower(trim($mentor['nama_mentor'] ?? ''));
                    $mEmail = strtolower(trim($mentor['email'] ?? ''));
                    $trainerClassIds = !empty($mentor['classes']) && is_array($mentor['classes']) ? $mentor['classes'] : [];

                    foreach ($classesApi as $c) {
                        $isMatch = false;
                        if (in_array((int)$c['id'], $trainerClassIds)) {
                            $isMatch = true;
                        } elseif (!empty($c['trainers']) && is_array($c['trainers'])) {
                            foreach ($c['trainers'] as $tr) {
                                $trId = (int)($tr['id'] ?? 0);
                                $trName = strtolower(trim($tr['name'] ?? ''));
                                $trEmail = strtolower(trim($tr['email'] ?? ''));
                                if ($trId === $mId || $trId === $uId || ($mEmail !== '' && $trEmail === $mEmail) || ($mName !== '' && $trName === $mName)) {
                                    $isMatch = true;
                                    break;
                                }
                            }
                        }

                        if ($isMatch || (empty($trainerClassIds) && empty($c['trainers']))) {
                            $cat = $c['training']['name'] ?? $c['jenis_kelas'] ?? 'Umum';
                            $loc = !empty($c['schedule']['location']) ? $c['schedule']['location'] : 'Kantor Pusat';
                            $kelas[] = [
                                'id_kelas'              => (int)$c['id'],
                                'id_mentor'             => $mId,
                                'nama_kelas'            => $c['name'] ?? 'Kelas Pelatihan',
                                'kategori'              => $cat,
                                'lokasi_pelatihan'      => $loc,
                                'kapasitas'             => $c['capacity']['target_students'] ?? $c['capacity'] ?? 30,
                                'tanggal_mulai_kelas'   => $c['schedule']['start_date'] ?? null,
                                'tanggal_selesai_kelas' => $c['schedule']['end_date'] ?? null,
                                'jumlah_pertemuan'      => $c['schedule']['meetings'] ?? 10,
                                'waktu_mulai'           => $c['schedule']['start_time'] ?? '08:00',
                                'waktu_selesai'         => $c['schedule']['end_time'] ?? '10:00',
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('notice', 'Mentor::kelas API classes fetch error: ' . $e->getMessage());
            }
        }

        foreach ($kelas as &$item) {
            $item['jumlah_peserta'] = $this->pesertaQuery((int) $item['id_kelas'])->countAllResults();
            $item['jumlah_materi'] = $this->db->tableExists('materi') ? $this->db->table('materi')->where('id_kelas', $item['id_kelas'])->countAllResults() : 0;
            $kapasitas = (int) ($item['kapasitas'] ?? 0);
            $item['persentase_peserta'] = $kapasitas > 0 ? min(100, (int) round(($item['jumlah_peserta'] / $kapasitas) * 100)) : 0;
        }
        unset($item);
        return view('mentor/kelas', ['kelas' => $kelas]);
    }

    public function detail(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        $peserta = $this->pesertaQuery($idKelas)->orderBy('users.nama', 'ASC')->get()->getResultArray();
        $jumlahMateri = $this->db->tableExists('materi') ? $this->db->table('materi')->where('id_kelas', $idKelas)->countAllResults() : 0;
        $kapasitas = (int) ($kelas['kapasitas'] ?? 0);
        return view('mentor/detail_kelas', ['kelas' => $kelas, 'peserta' => $peserta, 'jumlah_materi' => $jumlahMateri, 'persentase_peserta' => $kapasitas > 0 ? min(100, (int) round((count($peserta) / $kapasitas) * 100)) : 0]);
    }


    public function simpanJadwal(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $pertemuan = (int) $this->request->getPost('pertemuan_ke');
        $materi = trim((string) $this->request->getPost('materi'));
        $tanggal = trim((string) $this->request->getPost('tanggal_kbm'));

        if ($pertemuan < 1 || $materi === '' || $tanggal === '') {
            return redirect()->back()->with('error', 'Nomor pertemuan, topik materi, dan jadwal wajib diisi.');
        }

        // Cek ke tabel jadwal_kelas
        $sudahAda = $this->db->table('jadwal_kelas')->where(['id_kelas' => $idKelas, 'pertemuan_ke' => $pertemuan])->countAllResults();
        if ($sudahAda > 0) return redirect()->back()->with('error', "Pertemuan ke-$pertemuan sudah tersedia.");

        $filePdf = $this->request->getFile('file_pdf');
        $namaFilePdf = null;
        if ($filePdf && $filePdf->isValid() && ! $filePdf->hasMoved()) {
            $namaFilePdf = $filePdf->getRandomName();
            $filePdf->move('uploads/materi/', $namaFilePdf);
        }

        // Simpan ke tabel jadwal_kelas
        $this->db->table('jadwal')->insert([
            'id_kelas'          => $idKelas,
            'pertemuan_ke'      => $pertemuan,
            'tanggal_kbm'       => $tanggal,
            'waktu_mulai'       => $this->request->getPost('waktu_mulai'),
            'waktu_selesai'     => $this->request->getPost('waktu_selesai'),
            'materi'            => $materi,
            'ruangan_atau_link' => $this->request->getPost('ruangan_atau_link'),
            'link_materi'       => $this->request->getPost('link_materi'),
            'file_pdf'          => $namaFilePdf,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', "Sesi pertemuan ke-$pertemuan berhasil ditambahkan.");
    }

    public function updateMateri($id_jadwal)
    {
        $db = \Config\Database::connect();
        
      
        $dataUpdate = [
            'materi'      => $this->request->getPost('materi'),
            'link_materi' => $this->request->getPost('link_materi'),
        ];

        $filePdf = $this->request->getFile('file_pdf');
        if ($filePdf && $filePdf->isValid() && !$filePdf->hasMoved()) {
            $namaFile = $filePdf->getRandomName();
            $filePdf->move('uploads/materi/', $namaFile);
            $dataUpdate['file_pdf'] = $namaFile;
        }

        // Simpan pembaruan materi dan berkas ke tabel 'jadwal'
        $db->table('jadwal')->where('id_jadwal', $id_jadwal)->update($dataUpdate);
        return redirect()->back()->with('success', 'Materi dan berkas PDF berhasil diperbarui.');
    }

    public function absensi(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');

        // Pastikan seluruh sesi pertemuan kelas resmi sudah ada di tabel jadwal lokal
        $totalMeetings = (int)($kelas['jumlah_pertemuan'] ?? 10);
        if ($totalMeetings < 1) $totalMeetings = 10;
        
        $existingJadwal = $this->db->table('jadwal')->where('id_kelas', $idKelas)->orderBy('pertemuan_ke', 'ASC')->get()->getResultArray();
        $existingNumbers = array_column($existingJadwal, 'pertemuan_ke');

        $tglMulai = !empty($kelas['tanggal_mulai_kelas']) && $kelas['tanggal_mulai_kelas'] !== '-' ? $kelas['tanggal_mulai_kelas'] : date('Y-m-d');
        $tglSelesai = !empty($kelas['tanggal_selesai_kelas']) && $kelas['tanggal_selesai_kelas'] !== '-' ? $kelas['tanggal_selesai_kelas'] : null;

        for ($i = 1; $i <= $totalMeetings; $i++) {
            if (!in_array($i, $existingNumbers)) {
                $tglPertemuan = $tglMulai;
                if (!empty($tglMulai)) {
                    $startTs = strtotime($tglMulai);
                    if (!empty($tglSelesai) && $totalMeetings > 1) {
                        $endTs = strtotime($tglSelesai);
                        $diffDays = round(($endTs - $startTs) / 86400);
                        if ($diffDays > 0) {
                            $step = $diffDays / ($totalMeetings - 1);
                            $offset = round(($i - 1) * $step);
                            $tglPertemuan = date('Y-m-d', strtotime('+' . $offset . ' days', $startTs));
                        } else {
                            $tglPertemuan = date('Y-m-d', strtotime('+' . ($i - 1) . ' days', $startTs));
                        }
                    } elseif ($totalMeetings > 1) {
                        $tglPertemuan = date('Y-m-d', strtotime('+' . ($i - 1) . ' days', $startTs));
                    }
                }
                $this->db->table('jadwal')->insert([
                    'id_kelas'        => $idKelas,
                    'pertemuan_ke'    => $i,
                    'tanggal_kbm'     => $tglPertemuan,
                    'waktu_mulai'     => $kelas['waktu_mulai'] ?? '08:00:00',
                    'waktu_selesai'   => $kelas['waktu_selesai'] ?? '10:00:00',
                    'materi'          => 'Pertemuan ' . $i,
                    'absensi_dibuka'  => 0,
                    'radius_meter'    => 100,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $idUser = (int) session()->get('id_users');
        $jadwal = $this->db->table('jadwal')
            ->select('jadwal.*, absensi.id_absensi, absensi.status AS status_absen, absensi.waktu_absen')
            ->join('absensi', '(absensi.id_jadwal_kelas = jadwal.id_jadwal OR absensi.id_jadwal = jadwal.id_jadwal) AND absensi.id_user = ' . $idUser, 'left')
            ->where('jadwal.id_kelas', $idKelas)
            ->orderBy('jadwal.pertemuan_ke', 'ASC')
            ->get()->getResultArray();

        $pesertaAbsensi = $this->db->table('jadwal')
            ->select('jadwal.id_jadwal, jadwal.pertemuan_ke, COALESCE(users.nama, pendaftaran.nama) AS nama_peserta, COALESCE(users.email, pendaftaran.email) AS email, absensi.status, absensi.waktu_absen')
            ->join('pendaftaran', 'pendaftaran.id_kelas = jadwal.id_kelas AND (pendaftaran.status_pembayaran IN ("valid", "Valid", "Disetujui", "approved") OR pendaftaran.status IN ("disetujui", "Disetujui", "approved") OR pendaftaran.status_pendaftaran IN ("disetujui", "Disetujui", "approved"))', 'inner')
            ->join('users', 'users.id_users = pendaftaran.id_users OR users.email = pendaftaran.email', 'left')
            ->join('absensi', '(absensi.id_jadwal_kelas = jadwal.id_jadwal OR absensi.id_jadwal = jadwal.id_jadwal) AND (absensi.id_user = pendaftaran.id_users OR absensi.id_user = users.id_users)', 'left')
            ->where('jadwal.id_kelas', $idKelas)
            ->orderBy('jadwal.pertemuan_ke', 'ASC')
            ->orderBy('nama_peserta', 'ASC')
            ->get()->getResultArray();

        return view('mentor/absensi', ['kelas' => $kelas, 'jadwal' => $jadwal, 'pesertaAbsensi' => $pesertaAbsensi]);
    }

    public function prosesAbsen()
    {
        $db = \Config\Database::connect();
        $id_jadwal = $this->request->getPost('id_jadwal');
        $id_user = session()->get('id_users');

        $jadwal = $db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();

        if (!$jadwal || (int)($jadwal['absensi_dibuka'] ?? 0) !== 1) {
            return redirect()->back()->with('error', 'Maaf, absensi untuk pertemuan ini belum dibuka oleh mentor.');
        }

        $sudahAbsen = $db->table('absensi')
            ->groupStart()
                ->where('id_jadwal_kelas', $id_jadwal)
                ->orWhere('id_jadwal', $id_jadwal)
            ->groupEnd()
            ->where('id_user', $id_user)
            ->countAllResults();

        if ($sudahAbsen > 0) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi untuk pertemuan ini.');
        }

        $waktuSekarang = date('Y-m-d H:i:s');
        $db->table('absensi')->insert([
            'id_jadwal'       => $id_jadwal,
            'id_jadwal_kelas' => $id_jadwal,
            'id_user'         => $id_user,
            'status'          => 'hadir',
            'waktu_absen'     => $waktuSekarang,
            'created_at'      => $waktuSekarang,
            'updated_at'      => $waktuSekarang,
        ]);

        return redirect()->back()->with('success', 'Berhasil absen! Kehadiran Anda telah dicatat.');
    }

    public function simpanAbsensi(int $idKelas, int $idJadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $jadwal = $this->db->table('jadwal')->where('id_jadwal', $idJadwal)->get()->getRowArray();
        if (! $jadwal) return redirect()->back()->with('error', 'Jadwal mengajar tidak ditemukan.');

        $idUser = (int) session()->get('id_users');
        $absensi = $this->db->table('absensi')
            ->groupStart()
                ->where('id_jadwal_kelas', $idJadwal)
                ->orWhere('id_jadwal', $idJadwal)
            ->groupEnd()
            ->where('id_user', $idUser)
            ->get()->getRowArray();

        $waktuSekarang = date('Y-m-d H:i:s');
        if (!$absensi) {
            $this->db->table('absensi')->insert([
                'id_jadwal'       => $idJadwal,
                'id_jadwal_kelas' => $idJadwal,
                'id_user'         => $idUser,
                'status'          => 'hadir',
                'waktu_absen'     => $waktuSekarang,
                'created_at'      => $waktuSekarang,
                'updated_at'      => $waktuSekarang,
            ]);
        }

        // Otomatis buka sesi absensi untuk peserta saat mentor presensi
        $timeBlock = floor(time() / 12);
        $secretKey = "KunciRahasiaKelasOffline" . $idJadwal;
        $tokenAwal = substr(abs(crc32($timeBlock . $secretKey)), 0, 4);
        $waktuSelesai = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $updateBuka = [
            'absensi_dibuka'  => 1,
            'token_absen'     => $tokenAwal,
            'absensi_mulai'   => $waktuSekarang,
            'absensi_selesai' => $waktuSelesai,
            'updated_at'      => $waktuSekarang
        ];
        if ($this->db->fieldExists('status', 'jadwal')) {
            $updateBuka['status'] = 'buka';
        }

        $this->db->table('jadwal')->where('id_jadwal', $idJadwal)->update($updateBuka);

        return redirect()->to(base_url("mentor/kelas/$idKelas/absensi"))->with('success', 'Absensi mengajar berhasil dicatat dan sesi presensi peserta otomatis dibuka.');
    }

    public function bukaAbsensi($id_kelas, $id_jadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($id_kelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $timeBlock = floor(time() / 12);
        $secretKey = "KunciRahasiaKelasOffline" . $id_jadwal;
        $tokenAwal = substr(abs(crc32($timeBlock . $secretKey)), 0, 4);

        $waktuSekarang = date('Y-m-d H:i:s');
        $waktuSelesai = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $updateBuka = [
            'absensi_dibuka'  => 1,
            'token_absen'     => $tokenAwal,
            'absensi_mulai'   => $waktuSekarang,
            'absensi_selesai' => $waktuSelesai,
            'updated_at'      => $waktuSekarang
        ];
        if ($this->db->fieldExists('status', 'jadwal')) {
            $updateBuka['status'] = 'buka';
        }

        $this->db->table('jadwal')->where('id_jadwal', $id_jadwal)->update($updateBuka);

        return redirect()->to(base_url('mentor/kelas/' . $id_kelas . '/absensi'))->with('success', 'Sesi absensi berhasil dibuka. Peserta kini dapat melakukan absensi.');
    }

    public function bukaAbsen($id_kelas, $id_jadwal)
    {
        return $this->bukaAbsensi($id_kelas, $id_jadwal);
    }

    public function tutupAbsensi($id_kelas, $id_jadwal)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($id_kelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');

        $updateTutup = [
            'absensi_dibuka'  => 0,
            'absensi_selesai' => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s')
        ];
        if ($this->db->fieldExists('status', 'jadwal')) {
            $updateTutup['status'] = 'tutup';
        }

        $this->db->table('jadwal')->where('id_jadwal', $id_jadwal)->update($updateTutup);

        return redirect()->to(base_url('mentor/kelas/' . $id_kelas . '/absensi'))->with('success', 'Sesi absensi berhasil ditutup dan dikunci.');
    }

    public function tutupAbsen($id_kelas, $id_jadwal)
    {
        return $this->tutupAbsensi($id_kelas, $id_jadwal);
    }

    public function getLiveToken($id_jadwal)
    {
        try {
            $jadwal = $this->db->table('jadwal')->where('id_jadwal', $id_jadwal)->get()->getRowArray();
            
            if (! $jadwal || (int)($jadwal['absensi_dibuka'] ?? 0) !== 1) {
                return $this->response->setJSON(['token' => '----', 'expires' => 12]);
            }

            $currentTime = time();
            $timeBlock = floor($currentTime / 12);
            $secretKey = "KunciRahasiaKelasOffline" . $id_jadwal;
            $token = substr(abs(crc32($timeBlock . $secretKey)), 0, 4);

            $this->db->table('jadwal')
                     ->where('id_jadwal', $id_jadwal)
                     ->update(['token_absen' => $token]);

            $expiresIn = 12 - ($currentTime % 12);

            return $this->response->setJSON([
                'token' => $token,
                'expires' => $expiresIn
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['token' => 'ERR', 'expires' => 12]);
        }
    }

    public function generateTimeToken() {
        $timeBlock = floor(time() / 12);
        $secretKey = "KunciRahasiaKelasOffline";
        $token = substr(abs(crc32($timeBlock . $secretKey)), 0, 4);
        return $token;
    }

    public function tutupSesi($idJadwal)
    {
        $jadwal = $this->db->table('jadwal')->where('id_jadwal', $idJadwal)->get()->getRowArray();
        $idKelas = (int)($jadwal['id_kelas'] ?? 0);
        return $this->tutupAbsensi($idKelas, $idJadwal);
    }

// Method untuk mentor melakukan edit atau input manual absensi siswa
public function updateAbsensiManual()
{
    if ($redirect = $this->requireLogin()) {
        return $redirect;
    }

    $idJadwal = $this->request->getPost('id_jadwal');
    $idUser   = $this->request->getPost('id_user');
    $status   = $this->request->getPost('status'); // contoh: 'hadir', 'izin', 'alpa'

    $absensiModel = new \App\Models\AbsensiModel();

    // Cek apakah data absensi peserta pada jadwal ini sudah ada
    $cekAbsen = $absensiModel->where('id_jadwal', $idJadwal)
                             ->where('id_user', $idUser)
                             ->first();

    $data = [
        'id_jadwal'   => $idJadwal,
        'id_user'     => $idUser,
        'status'      => $status,
        'waktu_absen' => date('Y-m-d H:i:s'),
    ];

    if ($cekAbsen) {
        // Jika sudah ada, lakukan update
        $absensiModel->update($cekAbsen['id_absensi'], $data);
        $pesan = 'Absensi peserta berhasil diperbarui oleh mentor.';
    } else {
        // Jika belum ada (input manual karena HP siswa error), lakukan insert baru
        $absensiModel->insert($data);
        $pesan = 'Absensi manual peserta berhasil ditambahkan.';
    }

    return redirect()->back()->with('success', $pesan);
}

    public function materi(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        return view('mentor/materi', ['kelas' => $kelas, 'materi' => $this->db->table('materi')->where('id_kelas', $idKelas)->orderBy('id_materi_kelas', 'DESC')->get()->getResultArray()]);
    }

    public function ujianTugas(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        $kelas = $this->kelasMilikMentor($idKelas);
        if (! $kelas) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Kelas tidak ditemukan atau bukan kelas Anda.');
        $ujian = $this->db->table('ujian')->where('id_kelas', $idKelas)->orderBy('id_ujian', 'DESC')->get()->getResultArray();
        $tugas = $this->db->tableExists('tugas') ? $this->db->table('tugas')->where('id_kelas', $idKelas)->orderBy('id_tugas', 'DESC')->get()->getResultArray() : [];
        return view('mentor/ujian_tugas', ['kelas' => $kelas, 'ujian' => $ujian, 'tugas' => $tugas]);
    }

    public function simpanUjianTugas(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $jenis = $this->request->getPost('jenis');
        $judul = trim((string) $this->request->getPost('judul'));
        $keterangan = trim((string) $this->request->getPost('keterangan'));
        $deadline = trim((string) $this->request->getPost('deadline'));
        if (! in_array($jenis, ['ujian', 'tugas'], true) || $judul === '') return redirect()->back()->with('error', 'Jenis dan judul wajib diisi.');
        $deadlineDb = $deadline !== '' ? str_replace('T', ' ', $deadline) . ':00' : null;

        if ($jenis === 'ujian') {
            $file = $this->request->getFile('file_soal');
            $namaFile = null;
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $folder = FCPATH . 'uploads/ujian';
                if (! is_dir($folder)) mkdir($folder, 0755, true);
                $namaFile = $file->getRandomName();
                $file->move($folder, $namaFile);
            }
            $this->db->table('ujian')->insert(['id_kelas' => $idKelas, 'judul_ujian' => $judul, 'keterangan' => $keterangan, 'deadline' => $deadlineDb, 'file_soal' => $namaFile, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            if (! $this->db->tableExists('tugas')) return redirect()->back()->with('error', 'Tabel tugas belum tersedia. Jalankan migrasi database terlebih dahulu.');
            $this->db->table('tugas')->insert(['id_kelas' => $idKelas, 'judul_tugas' => $judul, 'deskripsi' => $keterangan, 'deadline' => $deadlineDb, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        }
        return redirect()->to(base_url("mentor/kelas/$idKelas/ujian-tugas"))->with('success', ucfirst($jenis) . ' berhasil ditambahkan ke kelas.');
    }

    private function simpanBerkas($file, ?string $fileLama = null): ?string
    {
        if (! $file || ! $file->isValid() || $file->hasMoved()) return null;
        if ($file->getSizeByUnit('mb') > 20) throw new \RuntimeException('Ukuran file maksimal 20 MB.');
        $folder = FCPATH . 'uploads/materi'; if (! is_dir($folder)) mkdir($folder, 0755, true);
        $baru = $file->getRandomName(); $file->move($folder, $baru);
        if ($fileLama) { $lama = $folder . DIRECTORY_SEPARATOR . basename($fileLama); if (is_file($lama)) unlink($lama); }
        return $baru;
    }

    public function simpanMateri(int $idKelas)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $judul = trim((string) $this->request->getPost('judul_materi')); $url = trim((string) $this->request->getPost('url_materi')); $file = $this->request->getFile('file_materi');
        if ($judul === '' || (! $file->isValid() && $url === '')) return redirect()->back()->withInput()->with('error', 'Judul serta file atau tautan materi wajib diisi.');
        if ($url && ! filter_var($url, FILTER_VALIDATE_URL)) return redirect()->back()->withInput()->with('error', 'Tautan materi tidak valid.');
        try { $namaFile = $this->simpanBerkas($file); } catch (\RuntimeException $e) { return redirect()->back()->withInput()->with('error', $e->getMessage()); }
        $this->db->table('materi')->insert(['id_kelas' => $idKelas, 'judul_materi' => $judul, 'tipe_materi' => $this->request->getPost('tipe_materi') ?: 'Dokumen', 'file_materi' => $namaFile, 'url_materi' => $url ?: null, 'created_at' => date('Y-m-d H:i:s')]);
        return redirect()->to(base_url("mentor/kelas/$idKelas/materi"))->with('success', 'Materi berhasil ditambahkan.');
    }

    public function ubahMateri(int $idKelas, int $idMateri)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $materi = $this->db->table('materi')->where(['id_materi_kelas' => $idMateri, 'id_kelas' => $idKelas])->get()->getRowArray();
        if (! $materi) return redirect()->back()->with('error', 'Materi tidak ditemukan.');
        $judul = trim((string) $this->request->getPost('judul_materi')); $url = trim((string) $this->request->getPost('url_materi'));
        if ($judul === '') return redirect()->back()->withInput()->with('error', 'Judul materi wajib diisi.');
        if ($url && ! filter_var($url, FILTER_VALIDATE_URL)) return redirect()->back()->withInput()->with('error', 'Tautan materi tidak valid.');
        $data = ['judul_materi' => $judul, 'tipe_materi' => $this->request->getPost('tipe_materi') ?: 'Dokumen', 'url_materi' => $url ?: null, 'updated_at' => date('Y-m-d H:i:s')];
        try { $file = $this->simpanBerkas($this->request->getFile('file_materi'), $materi['file_materi'] ?? null); } catch (\RuntimeException $e) { return redirect()->back()->withInput()->with('error', $e->getMessage()); }
        if ($file) $data['file_materi'] = $file;
        $this->db->table('materi')->where('id_materi_kelas', $idMateri)->update($data);
        return redirect()->to(base_url("mentor/kelas/$idKelas/materi"))->with('success', 'Materi berhasil diperbarui.');
    }

    public function hapusMateri(int $idKelas, int $idMateri)
    {
        if ($r = $this->requireMentor()) return $r;
        if (! $this->kelasMilikMentor($idKelas)) return redirect()->to(base_url('mentor/kelas'))->with('error', 'Akses kelas ditolak.');
        $materi = $this->db->table('materi')->where(['id_materi_kelas' => $idMateri, 'id_kelas' => $idKelas])->get()->getRowArray();
        if (! $materi) return redirect()->back()->with('error', 'Materi tidak ditemukan.');
        $this->db->table('materi')->where('id_materi_kelas', $idMateri)->delete();
        if (! empty($materi['file_materi'])) { $file = FCPATH . 'uploads/materi/' . basename($materi['file_materi']); if (is_file($file)) unlink($file); }
        return redirect()->to(base_url("mentor/kelas/$idKelas/materi"))->with('success', 'Materi berhasil dihapus.');
    }

    // Method baru untuk update Materi, Link GDrive, & File PDF oleh Mentor berdasarkan Jadwal
    

    public function laporan()
    {
        if ($r = $this->requireMentor()) return $r;
        $controller = new \App\Controllers\LaporanPesertaController();
        $controller->initController($this->request, $this->response, $this->logger);
        return $controller->index();
    }

    public function profil()
    {
        if ($r = $this->requireMentor()) return $r;
        return view('mentor/profil', ['mentor' => $this->mentorLogin(), 'user' => $this->db->table('users')->where('id_users', session()->get('id_users'))->get()->getRowArray()]);
    }
}
