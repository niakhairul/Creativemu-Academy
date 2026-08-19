<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\PendaftaranModel;
use App\Models\PengumpulanTugasModel;
use App\Models\JadwalKelasModel;
use App\Models\AbsensiModel;
use App\Models\AngketModel;
use App\Models\HasilUjianModel;

class Pelatihan extends BaseController
{
    // ==========================
    // LOGIN
    // ==========================
    public function login()
    {
        return view('auth/login');
    }

    // ==========================
    // REGISTER
    // ==========================
    public function register()
    {
        return view('auth/register');
    }

   // ==========================
// FORM PENDAFTARAN
// ==========================
public function pendaftaran()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $userModel = new UserModel();
    $kelasModel = new KelasModel();

    // Data user yang login
    $user = $userModel->find(session()->get('id'));

    // Ambil id kelas dari URL
    $id = $this->request->getGet('id');

    // Ambil satu data kelas
    $kelas = $kelasModel->find($id);

    if (!$kelas) {
        return redirect()->to(base_url('pelatihan/daftar-kelas'))
                         ->with('error', 'Kelas tidak ditemukan.');
    }

    return view('peserta/pendaftaran', [
        'user'  => $user,
        'kelas' => $kelas
    ]);
}
    // ==========================
    // STATUS PENDAFTARAN
    // ==========================
    public function status()
    {
        return view('peserta/status_pendaftaran');
    }

    // ==========================
    // KELAS SAYA
    // ==========================
    public function kelas()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();

    $kelas = $pendaftaranModel
        ->select('pendaftaran.*, kelas.nama_kelas, kelas.mentor, kelas.metode, kelas.jadwal, kelas.jam')
        ->join('kelas', 'kelas.id = pendaftaran.kelas_id')
       ->where('user_id', session()->get('id'))
        ->first();

    return view('peserta/kelas', [
        'kelas' => $kelas
    ]);
}
    // ==========================
    // DETAIL KELAS
    // ==========================
    public function detailKelas()
    {
        return view('peserta/detail_kelas');
    }

    // ==========================
// DAFTAR MATERI
// ==========================
public function daftarMateri()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    return view('peserta/daftar_materi');
}

    // ==========================
    // MATERI
    // ==========================
    public function materi()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();
    $kelasModel = new KelasModel();

    $pendaftaran = $pendaftaranModel
        ->where('pendaftaran.user_id', session()->get('id'))
        ->where('status_pendaftaran', 'Disetujui')
        ->first();

    if (!$pendaftaran) {
        return redirect()->to(base_url('pelatihan/kelas'));
    }

    $kelas = $kelasModel->find($pendaftaran['kelas_id']);

    return view('peserta/materi', [
        'kelas' => $kelas
    ]);
}

   
// ==========================
// TUGAS
// ==========================
public function tugas()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();
    $kelasModel = new KelasModel();
    $pengumpulanModel = new PengumpulanTugasModel();

    $userId = session()->get('id');

    $pendaftaran = $pendaftaranModel
        ->where('id_users', $userId)
        ->where('status_pendaftaran', 'Disetujui')
        ->first();

    if (!$pendaftaran) {
        return redirect()->to(base_url('pelatihan/kelas'));
    }

    $kelas = $kelasModel->find($pendaftaran['kelas_id']);

    // Cek apakah peserta sudah mengumpulkan tugas
    $pengumpulan = $pengumpulanModel
        ->where('id_users', $userId)
        ->where('tugas_id', 1)
        ->first();

    return view('peserta/tugas', [
        'kelas' => $kelas,
        'pengumpulan' => $pengumpulan
    ]);
}
// ==========================
// UPLOAD TUGAS
// ==========================


    // ==========================
    // DASHBOARD PESERTA
    // ==========================
    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $userModel = new UserModel();
        $kelasModel = new KelasModel();
        $pendaftaranModel = new PendaftaranModel();

        // Data user login
        $user = $userModel->find(session()->get('id'));

        // Semua kelas aktif
        $kelas = $kelasModel
                    ->where('status', 'Aktif')
                    ->findAll();

        // Status pendaftaran user
        // Status pendaftaran user
$pendaftaran = $pendaftaranModel
                    ->where('user_id', session()->get('id'))
                    ->first();
        $data = [
            'user'         => $user,
            'kelas'        => $kelas,
            'pendaftaran'  => $pendaftaran
        ];

        return view('peserta/dashboard', $data);
    }

    

    // ==========================
    // PROFIL
    // ==========================
    public function profil()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $userModel = new UserModel();

        $user = $userModel->find(session()->get('id'));

        return view('peserta/profil', [
            'user' => $user
        ]);
    }

    public function editProfil()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $userModel = new UserModel();

    $user = $userModel->find(session()->get('id'));

    return view('peserta/edit_profil', [
        'user' => $user
    ]);
}

public function updateProfil()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $userModel = new UserModel();

    $id = session()->get('id');

    // Data profil
    $data = [
        'nama'          => $this->request->getPost('nama'),
        'email'         => $this->request->getPost('email'),
        'no_hp'         => $this->request->getPost('no_hp'),
        'asal_sekolah'  => $this->request->getPost('asal_sekolah'),
    ];

    // Ambil file foto
    $foto = $this->request->getFile('foto');

    // Kalau peserta memilih foto
    if ($foto && $foto->isValid() && !$foto->hasMoved()) {

        // Validasi ukuran maksimal 2 MB
        if ($foto->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()
                ->with('error', 'Ukuran foto maksimal 2 MB.');
        }

        // Validasi ekstensi
        $ext = strtolower($foto->getClientExtension());

        if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
            return redirect()->back()
                ->with('error', 'Format foto harus JPG, JPEG, atau PNG.');
        }

        // Folder penyimpanan foto
        $folder = FCPATH . 'uploads/profil';

        // Buat folder jika belum ada
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // Nama file acak
        $namaFoto = $foto->getRandomName();

        // Pindahkan foto
        $foto->move($folder, $namaFoto);

        // Simpan nama foto ke database
        $data['foto'] = $namaFoto;
    }

    // Update data user
    $userModel->update($id, $data);

    // Update session
session()->set([
    'nama'          => $data['nama'],
    'email'         => $data['email'],
    'no_hp'         => $data['no_hp'],
    'asal_sekolah'  => $data['asal_sekolah'],
    'foto'          => $data['foto'] ?? null,
]);

    return redirect()->to(base_url('pelatihan/profil'))
        ->with('success', 'Profil berhasil diperbarui.');
}
    // ==========================
    // DAFTAR KELAS
    // ==========================
    public function daftarKelas()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $kelasModel = new KelasModel();
    $pendaftaranModel = new PendaftaranModel();

    $kelas = $kelasModel
                ->where('status','Aktif')
                ->findAll();

    foreach($kelas as &$k){

        $terdaftar = $pendaftaranModel
                ->where('kelas_id', $k['id'])
                ->where('status_pendaftaran !=', 'Ditolak')
                ->countAllResults();

        $k['terdaftar'] = $terdaftar;
        $k['sisa'] = $k['kuota'] - $terdaftar;
    }

    return view('peserta/daftar_kelas',[
        'kelas'=>$kelas
    ]);
}
// ==========================
// KBM
// ==========================
public function kbm()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();
    $kelasModel = new KelasModel();

    // Ambil pendaftaran peserta
    $pendaftaran = $pendaftaranModel
        ->where('id_users', session()->get('id'))
        ->where('status_pendaftaran', 'Disetujui')
        ->first();

    // Kalau belum disetujui, tidak boleh masuk KBM
    if (!$pendaftaran) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Kelas Anda belum disetujui admin.');
    }

    // Ambil data kelas
    $kelas = $kelasModel->find($pendaftaran['kelas_id']);

    return view('peserta/kbm', [
        'kelas' => $kelas
    ]);
}


// ==========================
// UJIAN PESERTA
// ==========================
public function ujian()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $data = [
        'title' => 'Ujian Peserta',
        'ujian_selesai' => session()->get('ujian_selesai')
    ];

    return view('peserta/ujian', $data);
}

public function angket()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $userId = session()->get('id');

    // Ambil data pendaftaran peserta
    $pendaftaranModel = new \App\Models\PendaftaranModel();

    $pendaftaran = $pendaftaranModel
        ->where('user_id', $userId)
        ->first();

    if (!$pendaftaran) {
        return redirect()->to(base_url('pelatihan/dashboard'))
            ->with('error', 'Data pendaftaran kelas tidak ditemukan.');
    }

    // Cek apakah peserta sudah mengisi angket
    $angketModel = new \App\Models\AngketModel();

    $sudahIsi = $angketModel
        ->where('user_id', $userId)
        ->where('kelas_id', $pendaftaran['kelas_id'])
        ->first();

    return view('peserta/angket', [
        'pendaftaran' => $pendaftaran,
        'sudahIsi'    => $sudahIsi
    ]);
}

public function sertifikat()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $userId = session()->get('id');

    // Model
    $pendaftaranModel = new PendaftaranModel();
    $hasilUjianModel = new HasilUjianModel();
    $angketModel = new AngketModel();

    // Ambil kelas peserta yang sudah disetujui
    $pendaftaran = $pendaftaranModel
        ->where('user_id', $userId)
        ->where('status_pendaftaran', 'Disetujui')
        ->first();

    // Default status
    $statusLulus = false;
    $statusAngket = false;
    $sertifikatAcademy = false;

    if ($pendaftaran) {

        $kelasId = $pendaftaran['kelas_id'];

        // Ambil hasil ujian peserta
        $hasilUjian = $hasilUjianModel
            ->where('id_user', $userId)
            ->where('id_kelas', $kelasId)
            ->first();

        // Cek apakah peserta sudah dinyatakan lulus
        if (
            $hasilUjian &&
            $hasilUjian['status_kelulusan'] === 'lulus'
        ) {
            $statusLulus = true;
        }

        // Cek apakah peserta sudah mengisi angket
        $angket = $angketModel
            ->where('user_id', $userId)
            ->where('kelas_id', $kelasId)
            ->first();

        if ($angket) {
            $statusAngket = true;
        }

        // Sertifikat Academy hanya tersedia jika:
        // 1. Lulus
        // 2. Sudah mengisi angket
        if ($statusLulus && $statusAngket) {
            $sertifikatAcademy = true;
        }
    }

    return view('peserta/sertifikat', [
        'statusLulus' => $statusLulus,
        'statusAngket' => $statusAngket,
        'sertifikatAcademy' => $sertifikatAcademy
    ]);
}
public function simpanAngket()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $model = new \App\Models\AngketModel();

    $model->save([
        'user_id'     => session()->get('id'),
        'kelas_id'    => $this->request->getPost('kelas_id'),
        'materi'      => $this->request->getPost('materi'),
        'mentor'      => $this->request->getPost('mentor'),
        'penyampaian' => $this->request->getPost('penyampaian'),
        'manfaat'     => $this->request->getPost('manfaat'),
        'saran'       => $this->request->getPost('saran')
    ]);

    return redirect()->to(base_url('pelatihan/angket'))
        ->with('success', 'Angket berhasil dikirim. Terima kasih sudah memberikan penilaian dan masukan.');
}
// ==========================
// KERJAKAN UJIAN PESERTA
// ==========================
public function kerjakanUjian()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    // Contoh soal sementara
    // Nanti soal ini akan diambil dari database yang dibuat oleh mentor
    $soal = [
        [
            'id' => 1,
            'pertanyaan' => 'Apa yang dimaksud dengan Digital Marketing?',
            'pilihan_a' => 'Pemasaran menggunakan media digital',
            'pilihan_b' => 'Pemasaran menggunakan koran saja',
            'pilihan_c' => 'Pemasaran secara langsung',
            'pilihan_d' => 'Pemasaran tanpa internet',
        ],
        [
            'id' => 2,
            'pertanyaan' => 'Manakah yang termasuk media sosial untuk pemasaran?',
            'pilihan_a' => 'Instagram',
            'pilihan_b' => 'Kalkulator',
            'pilihan_c' => 'Notepad',
            'pilihan_d' => 'File Explorer',
        ],
        [
            'id' => 3,
            'pertanyaan' => 'Apa tujuan utama promosi melalui media sosial?',
            'pilihan_a' => 'Mengurangi pelanggan',
            'pilihan_b' => 'Meningkatkan jangkauan pemasaran',
            'pilihan_c' => 'Menghapus produk',
            'pilihan_d' => 'Mengurangi informasi produk',
        ],
    ];

    return view('peserta/kerjakan_ujian', [
        'soal' => $soal
    ]);
}

public function submitUjian()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    // Jawaban peserta
    $jawaban = $this->request->getPost('jawaban');

    // Kunci jawaban sementara
    $kunci = [
        1 => 'A',
        2 => 'A',
        3 => 'B'
    ];

    $benar = 0;

    if (is_array($jawaban)) {
        foreach ($kunci as $nomor => $jawabanBenar) {

            if (
                isset($jawaban[$nomor]) &&
                $jawaban[$nomor] === $jawabanBenar
            ) {
                $benar++;
            }
        }
    }

    // Hitung nilai
    $jumlahSoal = count($kunci);
    $nilai = ($benar / $jumlahSoal) * 100;

    // Ambil user yang sedang login
    $userId = session()->get('id');

    // Cari kelas peserta yang sudah disetujui
    $pendaftaranModel = new PendaftaranModel();

    $pendaftaran = $pendaftaranModel
        ->where('user_id', $userId)
        ->where('status_pendaftaran', 'Disetujui')
        ->first();

    if (!$pendaftaran) {
        return redirect()->back()
            ->with('error', 'Data kelas peserta tidak ditemukan.');
    }

    // Ambil ID kelas
    $idKelas = $pendaftaran['kelas_id'];

    // Model hasil ujian
    $hasilUjianModel = new HasilUjianModel();

    // Cek apakah peserta sudah pernah mengumpulkan ujian
    $hasilSebelumnya = $hasilUjianModel
        ->where('id_user', $userId)
        ->where('id_kelas', $idKelas)
        ->first();

    if ($hasilSebelumnya) {
        return redirect()->to(base_url('pelatihan/hasil-ujian'))
            ->with('error', 'Anda sudah mengumpulkan ujian.');
    }

    // Simpan hasil ujian ke database
    $hasilUjianModel->insert([
        'id_user'           => $userId,
        'id_kelas'          => $idKelas,
        'benar'             => $benar,
        'jumlah_soal'       => $jumlahSoal,
        'nilai'             => $nilai,
        'status_penilaian'  => 'menunggu',
        'status_kelulusan'  => 'menunggu',
        'catatan_mentor'    => null
    ]);

    // Simpan juga ke session untuk menampilkan hasil
    session()->set([
        'ujian_selesai'      => true,
        'ujian_benar'        => $benar,
        'ujian_jumlah_soal'  => $jumlahSoal,
        'ujian_nilai'        => $nilai
    ]);

    return view('peserta/hasil_ujian', [
        'benar'      => $benar,
        'jumlahSoal' => $jumlahSoal,
        'nilai'      => $nilai
    ]);
}
// ==========================
// HASIL UJIAN PESERTA
// ==========================
public function hasilUjian()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    return view('peserta/hasil_ujian', [
        'benar' => session()->get('ujian_benar') ?? 0,
        'jumlahSoal' => session()->get('ujian_jumlah_soal') ?? 0,
        'nilai' => session()->get('ujian_nilai') ?? 0
    ]);
}
// ==========================
// ABSENSI PESERTA
// ==========================
public function absensi()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();
    $jadwalModel = new JadwalKelasModel();
    $absensiModel = new AbsensiModel();

    // Cek peserta sudah memiliki kelas yang disetujui
    $pendaftaran = $pendaftaranModel
        ->where('id_users', session()->get('id'))
        ->where('status_pendaftaran', 'Disetujui')
        ->first();

    if (!$pendaftaran) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Anda belum memiliki kelas yang disetujui.');
    }

    // Ambil jadwal berdasarkan kelas peserta
    $jadwal = $jadwalModel
        ->where('id_kelas', $pendaftaran['kelas_id'])
        ->orderBy('pertemuan_ke', 'ASC')
        ->findAll();

    // Ambil absensi peserta yang sedang login
    $userId = session()->get('id');

    foreach ($jadwal as &$j) {

        $j['absensi'] = $absensiModel
            ->where('id_jadwal_kelas', $j['id_jadwal_kelas'])
            ->where('id_user', $userId)
            ->first();
    }

    return view('peserta/absensi', [
        'jadwal' => $jadwal
    ]);
}

// ==========================
// SIMPAN ABSENSI PESERTA
// ==========================
public function simpanAbsensi()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $jadwalModel = new JadwalKelasModel();
    $absensiModel = new AbsensiModel();

    $userId = session()->get('id');

    // Ambil ID jadwal dari form
    $idJadwal = $this->request->getPost('id_jadwal_kelas');

    // Cari jadwal
    $jadwal = $jadwalModel->find($idJadwal);

    if (!$jadwal) {
        return redirect()->back()
            ->with('error', 'Jadwal tidak ditemukan.');
    }

    // Waktu sekarang
    $sekarang = time();

    // Waktu mulai
    $waktuMulai = strtotime($jadwal['tanggal_kbm']);

    // Waktu selesai
    $waktuSelesai = strtotime(
        date('Y-m-d', $waktuMulai) . ' ' . $jadwal['jam_selesai']
    );

    // Pastikan peserta hanya bisa absen sesuai jadwal
    if ($sekarang < $waktuMulai) {
        return redirect()->back()
            ->with('error', 'Absensi belum dibuka.');
    }

    if ($sekarang > $waktuSelesai) {
        return redirect()->back()
            ->with('error', 'Waktu absensi sudah ditutup.');
    }

    // Cek apakah peserta sudah pernah absen
    $sudahAbsen = $absensiModel
        ->where('id_jadwal_kelas', $idJadwal)
        ->where('id_user', $userId)
        ->first();

    if ($sudahAbsen) {
        return redirect()->back()
            ->with('error', 'Anda sudah melakukan absensi pada pertemuan ini.');
    }

    // Simpan absensi
    $absensiModel->insert([
        'id_jadwal_kelas' => $idJadwal,
        'id_user'         => $userId,
        'status'          => 'hadir',
        'waktu_absen'     => date('Y-m-d H:i:s')
    ]);

    return redirect()->to(base_url('pelatihan/absensi'))
        ->with('success', 'Absensi berhasil. Anda tercatat hadir.');
}

// ==========================
// RIWAYAT ABSENSI PESERTA
// ==========================
public function riwayatAbsensi()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();
    $jadwalModel = new JadwalKelasModel();
    $absensiModel = new AbsensiModel();

    $userId = session()->get('id');

    // Cek kelas peserta
    $pendaftaran = $pendaftaranModel
        ->where('id_users', $userId)
        ->where('status_pendaftaran', 'Disetujui')
        ->first();

    if (!$pendaftaran) {
        return redirect()->to(base_url('pelatihan/kelas'))
            ->with('error', 'Anda belum memiliki kelas yang disetujui.');
    }

    // Ambil semua jadwal kelas peserta
    $jadwal = $jadwalModel
        ->where('id_kelas', $pendaftaran['kelas_id'])
        ->orderBy('pertemuan_ke', 'ASC')
        ->findAll();

    // Variabel rekap
    $totalPertemuan = count($jadwal);
    $jumlahHadir = 0;
    $jumlahIzin = 0;
    $jumlahAlpa = 0;

    // Ambil status absensi masing-masing pertemuan
    foreach ($jadwal as &$j) {

        $j['absensi'] = $absensiModel
            ->where('id_jadwal_kelas', $j['id_jadwal_kelas'])
            ->where('id_user', $userId)
            ->first();

        // Hitung status absensi
        if (!empty($j['absensi'])) {

            if ($j['absensi']['status'] === 'hadir') {
                $jumlahHadir++;
            } elseif ($j['absensi']['status'] === 'izin') {
                $jumlahIzin++;
            } elseif ($j['absensi']['status'] === 'alpa') {
                $jumlahAlpa++;
            }
        }
    }

    // Hitung persentase kehadiran
    $persentaseKehadiran = $totalPertemuan > 0
        ? round(($jumlahHadir / $totalPertemuan) * 100)
        : 0;

    return view('peserta/riwayat_absensi', [
        'jadwal' => $jadwal,
        'totalPertemuan' => $totalPertemuan,
        'jumlahHadir' => $jumlahHadir,
        'jumlahIzin' => $jumlahIzin,
        'jumlahAlpa' => $jumlahAlpa,
        'persentaseKehadiran' => $persentaseKehadiran
    ]);
}
    // ==========================
    // PENGATURAN AKUN
    // ==========================
    public function pengaturan()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $userModel = new UserModel();

        $user = $userModel->find(session()->get('id'));

        return view('peserta/pengaturan', [
            'user' => $user
        ]);
    }

    public function ubahPassword()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    return view('peserta/ubah_password');
}

public function updatePassword()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $userModel = new UserModel();

    $id = session()->get('id');

    $user = $userModel->find($id);

    $passwordLama = $this->request->getPost('password_lama');
    $passwordBaru = $this->request->getPost('password_baru');
    $konfirmasi = $this->request->getPost('konfirmasi_password');

    // Cek password lama
    if (!password_verify($passwordLama, $user['password'])) {
        return redirect()->back()->with('error', 'Password lama salah.');
    }

    // Cek konfirmasi password
    if ($passwordBaru !== $konfirmasi) {
        return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
    }

    // Update password
    $userModel->update($id, [
        'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
    ]);

    return redirect()->to(base_url('pelatihan/pengaturan'))
        ->with('success', 'Password berhasil diubah.');
}
   
// ==========================
// UPLOAD TUGAS
// ==========================
public function uploadTugas()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $file = $this->request->getFile('tugas');

    // Cek file
    if (!$file || !$file->isValid()) {
        return redirect()->back()
            ->with('error', 'File tidak valid.');
    }

    // Folder upload
    $folder = FCPATH . 'uploads/tugas';

    // Buat folder jika belum ada
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    // Buat nama file acak
    $namaFile = $file->getRandomName();

    // Pindahkan file
    $file->move($folder, $namaFile);

    // Simpan ke database
    $model = new PengumpulanTugasModel();

    $model->save([
        'tugas_id'   => 1,
        'id_users'    => session()->get('id'),
        'file_tugas' => $namaFile,
        'status'     => 'Belum Dinilai'
    ]);

    // Kembali ke halaman tugas
    return redirect()->to(base_url('pelatihan/tugas'))
        ->with('success', 'Tugas berhasil diupload.');
}

    // ==========================
// SIMPAN PENDAFTARAN
// ==========================
public function simpanPendaftaran()
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'));
    }

    $pendaftaranModel = new PendaftaranModel();

    $userId = session()->get('id');
    $kelasId = $this->request->getPost('kelas_id');

    // Cek apakah peserta sudah mendaftar kelas tersebut
    $cek = $pendaftaranModel
        ->where('user_id', $userId)
        ->where('kelas_id', $kelasId)
        ->first();

    if ($cek) {
        return redirect()->back()
            ->with('error', 'Anda sudah mendaftar kelas ini.');
    }

    // Upload bukti pembayaran
    $namaFile = null;

    $file = $this->request->getFile('bukti');

    if ($file && $file->isValid() && !$file->hasMoved()) {

        $namaFile = $file->getRandomName();

        $folder = FCPATH . 'uploads/bukti_pembayaran';

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $file->move($folder, $namaFile);
    }

    // Ambil metode pembayaran
    $pembayaran = $this->request->getPost('pembayaran');

    if ($pembayaran == 'Transfer') {
        $statusPembayaran = 'Belum Diverifikasi';
    } else {
        $statusPembayaran = 'Lunas';
    }

    // Simpan pendaftaran
    $pendaftaranModel->insert([
        'user_id'              => $userId,
        'kelas_id'             => $kelasId,
        'metode_pembelajaran'  => $this->request->getPost('metode'),
        'metode_pembayaran'    => $pembayaran,
        'bukti_pembayaran'     => $namaFile,
        'status_pendaftaran'   => 'Menunggu',
        'status_pembayaran'    => $statusPembayaran
    ]);

    return redirect()->to(base_url('peserta/dashboard'))
        ->with(
            'success',
            'Pendaftaran berhasil, silakan menunggu validasi admin.'
        );
}
}