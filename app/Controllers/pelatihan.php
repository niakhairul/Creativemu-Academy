<?php

namespace App\Controllers;

use App\Models\AbsensiModel;
use App\Models\AngketModel;
use App\Models\HasilUjianModel;
use App\Models\JadwalKelasModel;
use App\Models\KelasModel;
use App\Models\PendaftaranModel;
use App\Models\PengumpulanTugasModel;
use App\Models\UserModel;

class Pelatihan extends BaseController
{
    protected $db;

    // TAMBAHKAN KODE INI SUPAYA $this->db OTOMATIS AKTIF DI SEMUA FUNGSI
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->db = \Config\Database::connect();
    }    

    public function store()
    {
        $pendaftaranModel = new PendaftaranModel();

        // 1. Tangkap file Pas Foto
        $fileFoto = $this->request->getFile('pas_foto');
        $namaFoto = null;
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/foto/', $namaFoto);
        }

        // 2. Tangkap file Bukti Pembayaran (jika metode pembayarannya transfer)
        $fileBukti = $this->request->getFile('bukti_pembayaran');
        $namaBukti = null;
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $namaBukti = $fileBukti->getRandomName();
            $fileBukti->move('uploads/bukti/', $namaBukti);
        }

        // 3. Simpan data ke database
        $pendaftaranModel->save([
            'nama'                 => $this->request->getPost('nama'),
            'email'                => $this->request->getPost('email'),
            'no_hp'                => $this->request->getPost('no_hp'),
            'alamat'               => $this->request->getPost('alamat'),
            'ttl'                  => $this->request->getPost('ttl'),
            'jenis_kelamin'        => $this->request->getPost('jenis_kelamin'),
            'pendidikan_terakhir'  => $this->request->getPost('pendidikan_terakhir'),
            'pas_foto'             => $namaFoto,
            'pilihan_status'       => $this->request->getPost('pilihan_status'),
            'pilihan_lokasi'       => $this->request->getPost('pilihan_lokasi'),
            'pilihan_pelatihan'    => $this->request->getPost('pilihan_pelatihan'),
            'jenis_kelas'          => $this->request->getPost('jenis_kelas'),
            'metode_pembelajaran'  => $this->request->getPost('metode_pembelajaran'),
            'pilihan_kelas'        => $this->request->getPost('pilihan_kelas'),
            'kategori_kelas'       => $this->request->getPost('kategori_kelas'),
            'tanggal_mulai_kelas'  => $this->request->getPost('tanggal_mulai_kelas'),
            'metode_pembayaran'    => $this->request->getPost('metode_pembayaran'),
            'bukti_pembayaran'     => $namaBukti,
            'status_pembayaran'    => 'pending',
            'persetujuan_syarat'   => $this->request->getPost('persetujuan_syarat') ? 1 : 0
        ]);

        return redirect()->to('/pendaftaran/sukses')->with('success', 'Pendaftaran berhasil dikirim! Menunggu validasi admin.');
    }

    public function sukses()
    {
        return view('pendaftaran_sukses');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    protected function requireLogin()
{
    // Pastikan session 'logged_in' atau 'id_user' sesuai dengan saat proses login berhasil
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('pelatihan/login'))->with('error', 'Sesi habis, silakan login kembali.');
    }
}

protected function userId()
{
    // Sesuaikan dengan nama session saat user login (misal: 'id' atau 'id_user')
    return session()->get('id_users') ?? session()->get('id');
}

    private function approvedEnrollment()
    {
        return (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.deskripsi, kelas.tipe_kelas, kelas.lokasi_media, kelas.tanggal_mulai_kelas, kelas.jumlah_pertemuan, kelas.ringkasan, kelas.thumbnail, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->where('pendaftaran.status_pendaftaran', 'Disetujui')
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();
    }

    public function index()
{
    $kelasModel = new \App\Models\KelasModel();
    
    // Ambil data dengan join mentor
    $data['kelas'] = $kelasModel->getKelasWithMentor();

    // DEBUG: Cek isi datanya di layar (hapus/komentar jika sudah selesai dicek)
    echo "<pre>";
    print_r($data['kelas']);
    echo "</pre>";
    exit();

    return view('nama_view_kamu', $data);
}

    public function dashboard()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $userId = $this->userId();
        $user = (new UserModel())->find($userId);
        $kelas = (new KelasModel())->where('status', 'aktif')->findAll();
        
        // Mengambil data dengan join ke tabel kelas dan tabel mentor
        $pendaftaran = (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas, kelas.tanggal_mulai_kelas as jadwal, mentor.nama_mentor as mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $userId)
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();

        return view('peserta/dashboard', [
            'user' => $user,
            'kelas' => $kelas,
            'pendaftaran' => $pendaftaran,
        ]);
    }

    public function profil()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();

        return view('peserta/profil', [
            'user' => (new UserModel())->find($this->userId()),
            'pendaftaran' => $pendaftaran,
        ]);
    }

    public function editProfil()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/edit_profil', ['user' => (new UserModel())->find($this->userId())]);
    }

    public function updateProfil()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'asal_sekolah' => $this->request->getPost('asal_sekolah'),
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && ! $foto->hasMoved()) {
            if ($foto->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran foto maksimal 2 MB.');
            }

            $ext = strtolower($foto->getClientExtension());
            if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                return redirect()->back()->with('error', 'Format foto harus JPG, JPEG, atau PNG.');
            }

            $folder = FCPATH . 'uploads/profil';
            if (! is_dir($folder)) {
                mkdir($folder, 0777, true);
            }

            $data['foto'] = $foto->getRandomName();
            $foto->move($folder, $data['foto']);
        }

        (new UserModel())->update($this->userId(), $data);
        session()->set(array_filter($data, static fn ($value) => $value !== null));

        return redirect()->to(base_url('pelatihan/profil'))->with('success', 'Profil berhasil diperbarui.');
    }

    public function daftar($id_kelas = null)
{
    // Pastikan ID kelas ada
    if (!$id_kelas) {
        return redirect()->back()->with('error', 'ID Kelas tidak ditemukan.');
    }

    $modelKelas = new \App\Models\KelasModel();
    $data['kelas'] = $modelKelas->find($id_kelas);

    // Jika data kelas di database tidak ada
    if (!$data['kelas']) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Kelas tidak ditemukan');
    }

    // Ambil data user yang sedang login (jika ada)
    $userId = session()->get('id_user'); // Sesuaikan dengan session Anda
    $modelUser = new \App\Models\UserModel();
    $data['user'] = $modelUser->find($userId);

    return view('pelatihan/form_daftar', $data);
}
    public function pendaftaran($id_kelas = null)
{
    if ($id_kelas === null) {
        return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'Pilih kelas terlebih dahulu.');
    }

    $db = \Config\Database::connect();

    // Ambil data kelas dan mentor
    $kelas = $db->table('kelas')
        ->select('kelas.*, mentor.*') 
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->where('kelas.id_kelas', $id_kelas)
        ->get()
        ->getRowArray();

    if (!$kelas) {
        return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('error', 'Kelas tidak ditemukan.');
    }

    // Normalisasi nama mentor
    $kelas['nama_mentor'] = $kelas['nama_mentor'] 
        ?? $kelas['nama'] 
        ?? $kelas['nama_lengkap'] 
        ?? $kelas['username'] 
        ?? 'Mentor';

    $data['kelas'] = $kelas;

    // Ambil data user yang sedang login
    $userId = method_exists($this, 'userId') ? $this->userId() : session()->get('id_users');
    
    $data['user'] = [];
    if ($userId) {
        $data['user'] = $db->table('users')->where('id_users', $userId)->get()->getRowArray();
    }

    return view('peserta/pendaftaran', $data);
}

    public function simpanPendaftaran()
{
    $db = \Config\Database::connect();
    
    // Proses upload file Pas Foto (3x4)
    $namaFoto = null;
    $fileFoto = $this->request->getFile('pas_foto'); 
    if ($fileFoto && $fileFoto->isValid() && ! $fileFoto->hasMoved()) {
        if ($fileFoto->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Ukuran pas foto maksimal 2 MB.');
        }

        $folderFoto = 'uploads/foto/';
        if (! is_dir(FCPATH . $folderFoto)) {
            mkdir(FCPATH . $folderFoto, 0777, true);
        }

        $namaFoto = $fileFoto->getRandomName();
        $fileFoto->move(FCPATH . $folderFoto, $namaFoto);
    }

    // Proses upload file bukti pembayaran
    $namaBukti = null;
    $fileBukti = $this->request->getFile('bukti_pembayaran'); 
    if ($fileBukti && $fileBukti->isValid() && ! $fileBukti->hasMoved()) {
        if ($fileBukti->getSize() > 2 * 1024 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Ukuran file bukti pembayaran maksimal 2 MB.');
        }

        $folderBukti = 'uploads/bukti/';
        if (! is_dir(FCPATH . $folderBukti)) {
            mkdir(FCPATH . $folderBukti, 0777, true);
        }

        $namaBukti = $fileBukti->getRandomName();
        $fileBukti->move(FCPATH . $folderBukti, $namaBukti);
    }

    // Siapkan array data disesuaikan dengan nama input form & struktur database
    $dataPendaftaran = [
        'id_kelas'            => $this->request->getPost('id_kelas'),
        'id_users'            => $this->request->getPost('id_users') ?: (session()->get('id_users') ?? null),
        'nama'                => $this->request->getPost('nama'),
        'email'               => $this->request->getPost('email'),
        'no_hp'               => $this->request->getPost('no_hp'),
        'alamat'              => $this->request->getPost('alamat'),
        'ttl'                 => $this->request->getPost('ttl'),
        'jenis_kelamin'       => $this->request->getPost('jenis_kelamin'),
        'pendidikan_terakhir' => $this->request->getPost('pendidikan_terakhir'),
        'pas_foto'            => $namaFoto,
        'status'              => $this->request->getPost('pilihan_status'),
        'lokasi_pelatihan'    => $this->request->getPost('pilihan_lokasi'),
        'pilihan_pelatihan'   => $this->request->getPost('pilihan_pelatihan'),
        'jenis_kelas'         => $this->request->getPost('jenis_kelas'),
        'metode_pembelajaran' => strtolower($this->request->getPost('metode_pembelajaran')), // Dipaksa huruf kecil agar sesuai ENUM database ('online'/'offline')
        'pilihan_kelas'       => $this->request->getPost('pilihan_kelas'),
        'kategori_kelas'      => $this->request->getPost('kategori_kelas'),
        'tanggal_mulai_kelas' => $this->request->getPost('tanggal_mulai_kelas'),
        'metode_pembayaran'   => $this->request->getPost('metode_pembayaran'),
        'bukti_pembayaran'    => $namaBukti,
        'status_pembayaran'   => 'pending',
        'alasan_penolakan'    => null,
        'persetujuan_syarat'  => $this->request->getPost('persetujuan_syarat') ? 1 : 0,
    ];

    $pendaftaranModel = new \App\Models\PendaftaranModel();

    try {
        if ($pendaftaranModel->insert($dataPendaftaran)) {
            return redirect()->to(base_url('pelatihan/daftar-kelas'))->with('success', 'Pendaftaran berhasil dikirim! Silakan menunggu validasi admin.');
        } else {
            $errors = $pendaftaranModel->errors();
            return redirect()->back()->withInput()->with('error', 'Gagal validasi database: ' . json_encode($errors));
        }
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Database Exception: ' . $e->getMessage());
    }
}

    public function status()
{
    $keyword = $this->request->getGet('keyword') ?? $this->request->getPost('keyword');
    $pendaftaran = [];

    if ($keyword) {
        $pendaftaran = (new \App\Models\PendaftaranModel())
            ->select('pendaftaran.*, kelas.nama_kelas')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->groupStart()
                ->where('pendaftaran.email', $keyword)
                ->orWhere('pendaftaran.no_hp', $keyword)
            ->groupEnd()
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->findAll();
    }

    return view('peserta/status_pendaftaran', [
        'pendaftaran' => $pendaftaran,
        'keyword'     => $keyword
    ]);
}

    public function updateBukti($id)
{
    // Ambil data pendaftaran berdasarkan ID
    $pendaftaranModel = new \App\Models\PendaftaranModel();
    $pendaftaran = $pendaftaranModel->find($id);

    if (!$pendaftaran) {
        return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
    }

    // Ambil file bukti pembayaran yang baru diunggah
    $fileBukti = $this->request->getFile('bukti_pembayaran');

    if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
        // Hapus file bukti lama jika ada
        if (!empty($pendaftaran['bukti_pembayaran']) && file_exists(FCPATH . 'uploads/pembayaran/' . $pendaftaran['bukti_pembayaran'])) {
            unlink(FCPATH . 'uploads/pembayaran/' . $pendaftaran['bukti_pembayaran']);
        }

        // PERBAIKAN DI SINI: Menambahkan tanda dolar ($) pada namaBaru
        $namaBaru = $fileBukti->getRandomName();
        $fileBukti->move(FCPATH . 'uploads/pembayaran', $namaBaru);

        // Update data di database: masukkan file baru, ubah status jadi 'pending' / 'menunggu verifikasi', dan kosongkan alasan penolakan sebelumnya
        $pendaftaranModel->update($id, [
            'bukti_pembayaran' => $namaBaru,
            'status' => 'pending', 
            'alasan_penolakan' => null
        ]);

        return redirect()->to(base_url('pelatihan/status?keyword=' . $pendaftaran['email']))
                         ->with('success', 'Bukti pembayaran berhasil dikirim ulang! Silakan tunggu verifikasi admin.');
    }

    return redirect()->back()->with('error', 'Gagal mengunggah file. Pastikan format dan ukuran file sudah sesuai.');
}

    public function ajax_cek_status()
{
    $keyword = $this->request->getGet('keyword');
    
    // Pastikan model terpanggil dengan benar
    $pendaftaranModel = new \App\Models\PendaftaranModel(); 
    
    // Cari data berdasarkan email atau no_hp
    $pendaftaran = $pendaftaranModel->groupStart()
                                    ->like('email', $keyword)
                                    ->orLike('no_hp', $keyword)
                                    ->groupEnd()
                                    ->first();

    // Set header agar browser tahu ini adalah format JSON
    header('Content-Type: application/json');

    if ($pendaftaran) {
        // Jika data ditemukan
        echo json_encode([
            'status' => 'success',
            'data' => $pendaftaran
        ]);
        exit;
    } else {
        // Jika data tidak ditemukan
        echo json_encode([
            'status' => 'error',
            'message' => 'Data pendaftaran dengan email atau nomor HP tersebut tidak ditemukan.'
        ]);
        exit;
    }
}

    public function kelas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = (new PendaftaranModel())
            ->select('pendaftaran.*, kelas.*, mentor.nama_mentor')
            ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
            ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
            ->where('pendaftaran.id_users', $this->userId())
            ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
            ->first();

        return view('peserta/kelas', ['kelas' => $kelas]);
    }

    public function daftarKelas()
{
    $kelasModel = new \App\Models\KelasModel();

    // Harus mengambil banyak data (array multidimensi)
    $data['kelas'] = $kelasModel->getKelasWithMentor(); 

    return view('peserta/daftar_kelas', $data);
}

    public function detail($id)
{
    $kelasModel = new \App\Models\KelasModel();
    
    // Mengambil data kelas berdasarkan ID beserta data mentornya
    // Pastikan nama tabel mentor di database kamu sesuai (misal: 'mentor' atau 'tb_mentor')
    $data['kelas'] = $kelasModel->select('kelas.*, mentor.nama_mentor')
                                ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
                                ->find($id);

    // Jika data kelas tidak ditemukan
    if (empty($data['kelas'])) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("Kelas dengan ID $id tidak ditemukan.");
    }

    // Tampilkan ke view detail (sesuaikan dengan nama file view detail kamu, misal: 'pelatihan/detail')
    return view('peserta/detail_kelas', $data);
}

    public function kbm()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (! $kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas Anda belum disetujui admin.');
        }

        return view('peserta/kbm', ['kelas' => $kelas]);
    }

    public function daftarMateri()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/daftar_materi', ['kelas' => $this->approvedEnrollment()]);
    }

    public function materi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/materi', ['kelas' => $this->approvedEnrollment()]);
    }

    public function tugas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $kelas = $this->approvedEnrollment();
        if (! $kelas) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Kelas Anda belum disetujui admin.');
        }

        $pengumpulan = (new PengumpulanTugasModel())
            ->where('id_users', $this->userId())
            ->first();

        return view('peserta/tugas', ['kelas' => $kelas, 'pengumpulan' => $pengumpulan]);
    }

    public function uploadTugas()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $file = $this->request->getFile('tugas');
        if (! $file || ! $file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $folder = FCPATH . 'uploads/tugas';
        if (! is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $namaFile = $file->getRandomName();
        $file->move($folder, $namaFile);

        (new PengumpulanTugasModel())->save([
            'id_tugas' => 1,
            'id_users' => $this->userId(),
            'file_tugas' => $namaFile,
            'status' => 'Belum Dinilai',
        ]);

        return redirect()->to(base_url('pelatihan/tugas'))->with('success', 'Tugas berhasil diupload.');
    }

    public function ujian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/ujian', ['title' => 'Ujian Peserta', 'ujian_selesai' => session()->get('ujian_selesai')]);
    }

    public function kerjakanUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $soal = [
            ['id' => 1, 'pertanyaan' => 'Apa yang dimaksud dengan Digital Marketing?', 'pilihan_a' => 'Pemasaran menggunakan media digital', 'pilihan_b' => 'Pemasaran menggunakan koran saja', 'pilihan_c' => 'Pemasaran secara langsung', 'pilihan_d' => 'Pemasaran tanpa internet'],
            ['id' => 2, 'pertanyaan' => 'Manakah yang termasuk media sosial untuk pemasaran?', 'pilihan_a' => 'Instagram', 'pilihan_b' => 'Kalkulator', 'pilihan_c' => 'Notepad', 'pilihan_d' => 'File Explorer'],
            ['id' => 3, 'pertanyaan' => 'Apa tujuan utama promosi melalui media sosial?', 'pilihan_a' => 'Mengurangi pelanggan', 'pilihan_b' => 'Meningkatkan jangkauan pemasaran', 'pilihan_c' => 'Menghapus produk', 'pilihan_d' => 'Mengurangi informasi produk'],
        ];

        return view('peserta/kerjakan_ujian', ['soal' => $soal]);
    }

    public function submitUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $jawaban = $this->request->getPost('jawaban');
        $kunci = [1 => 'A', 2 => 'A', 3 => 'B'];
        $benar = 0;

        if (is_array($jawaban)) {
            foreach ($kunci as $nomor => $jawabanBenar) {
                if (($jawaban[$nomor] ?? null) === $jawabanBenar) {
                    $benar++;
                }
            }
        }

        $jumlahSoal = count($kunci);
        $nilai = ($benar / $jumlahSoal) * 100;
        $kelas = $this->approvedEnrollment();

        if ($kelas) {
            (new HasilUjianModel())->insert([
                'id_user' => $this->userId(),
                'id_users' => $this->userId(),
                'id_kelas' => $kelas['id_kelas'],
                'benar' => $benar,
                'jumlah_soal' => $jumlahSoal,
                'nilai' => $nilai,
                'status_penilaian' => 'menunggu',
                'status_kelulusan' => $nilai >= 70 ? 'lulus' : 'belum_lulus',
            ]);
        }

        session()->set(['ujian_selesai' => true, 'ujian_benar' => $benar, 'ujian_jumlah_soal' => $jumlahSoal, 'ujian_nilai' => $nilai]);

        return view('peserta/hasil_ujian', ['benar' => $benar, 'jumlahSoal' => $jumlahSoal, 'nilai' => $nilai]);
    }

    public function hasilUjian()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/hasil_ujian', [
            'benar' => session()->get('ujian_benar') ?? 0,
            'jumlahSoal' => session()->get('ujian_jumlah_soal') ?? 0,
            'nilai' => session()->get('ujian_nilai') ?? 0,
        ]);
    }

    public function angket()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        if (! $pendaftaran) {
            return redirect()->to(base_url('peserta/dashboard'))->with('error', 'Kelas Anda belum divalidasi.');
        }

        $sudahIsi = (new AngketModel())
            ->where('id_users', $this->userId())
            ->where('id_kelas', $pendaftaran['id_kelas'])
            ->first();

        return view('peserta/angket', ['pendaftaran' => $pendaftaran, 'sudahIsi' => $sudahIsi]);
    }

    public function simpanAngket()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        (new AngketModel())->save([
            'id_users' => $this->userId(),
            'id_kelas' => $this->request->getPost('kelas_id'),
            'materi' => $this->request->getPost('materi'),
            'mentor' => $this->request->getPost('mentor'),
            'penyampaian' => $this->request->getPost('penyampaian'),
            'manfaat' => $this->request->getPost('manfaat'),
            'saran' => $this->request->getPost('saran'),
        ]);

        return redirect()->to(base_url('pelatihan/angket'))->with('success', 'Angket berhasil dikirim.');
    }

    public function sertifikat()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        $hasilUjian = null;
        if ($pendaftaran) {
            $hasilUjian = (new HasilUjianModel())
                ->where('id_kelas', $pendaftaran['id_kelas'])
                ->groupStart()
                    ->where('id_users', $this->userId())
                    ->orWhere('id_users', $this->userId())
                ->groupEnd()
                ->orderBy('id_hasil_ujian', 'DESC')
                ->first();
        }

        $statusLulus = (bool) ($hasilUjian && $hasilUjian['status_kelulusan'] === 'lulus');
        $statusAngket = (bool) ($pendaftaran && (new AngketModel())->where('id_users', $this->userId())->where('id_kelas', $pendaftaran['id_kelas'])->first());

        return view('peserta/sertifikat', [
            'statusLulus' => $statusLulus,
            'statusAngket' => $statusAngket,
            'sertifikatAcademy' => $statusLulus && $statusAngket,
        ]);
    }

    public function absensi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        if (! $pendaftaran) {
            return redirect()->to(base_url('pelatihan/kelas'))->with('error', 'Anda belum memiliki kelas yang disetujui.');
        }

        $jadwal = (new JadwalKelasModel())->where('id_kelas', $pendaftaran['id_kelas'])->orderBy('pertemuan_ke', 'ASC')->findAll();
        $absensiModel = new AbsensiModel();
        foreach ($jadwal as &$item) {
            $item['absensi'] = $absensiModel->where('id_jadwal_kelas', $item['id_jadwal_kelas'])->where('id_users', $this->userId())->first();
        }

        return view('peserta/absensi', ['jadwal' => $jadwal]);
    }

    public function simpanAbsensi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $idJadwal = $this->request->getPost('id_jadwal_kelas');
        $sudahAbsen = (new AbsensiModel())->where('id_jadwal_kelas', $idJadwal)->where('id_user', $this->userId())->first();
        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah melakukan absensi pada pertemuan ini.');
        }

        (new AbsensiModel())->insert([
            'id_jadwal_kelas' => $idJadwal,
            'id_users' => $this->userId(),
            'status' => 'hadir',
            'waktu_absen' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('pelatihan/absensi'))->with('success', 'Absensi berhasil.');
    }

    public function riwayatAbsensi()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $pendaftaran = $this->approvedEnrollment();
        $jadwal = [];
        if ($pendaftaran) {
            $jadwal = (new JadwalKelasModel())->where('id_kelas', $pendaftaran['id_kelas'])->orderBy('pertemuan_ke', 'ASC')->findAll();
        }

        $absensiModel = new AbsensiModel();
        $jumlahHadir = $jumlahIzin = $jumlahAlpa = 0;
        foreach ($jadwal as &$item) {
            $item['absensi'] = $absensiModel->where('id_jadwal_kelas', $item['id_jadwal_kelas'])->where('id_users', $this->userId())->first();
            $status = $item['absensi']['status'] ?? null;
            if ($status === 'hadir') $jumlahHadir++;
            if ($status === 'izin') $jumlahIzin++;
            if ($status === 'alpa') $jumlahAlpa++;
        }

        $totalPertemuan = count($jadwal);
        return view('peserta/riwayat_absensi', [
            'jadwal' => $jadwal,
            'totalPertemuan' => $totalPertemuan,
            'jumlahHadir' => $jumlahHadir,
            'jumlahIzin' => $jumlahIzin,
            'jumlahAlpa' => $jumlahAlpa,
            'persentaseKehadiran' => $totalPertemuan > 0 ? round(($jumlahHadir / $totalPertemuan) * 100) : 0,
        ]);
    }

    public function pengaturan()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/pengaturan', ['user' => (new UserModel())->find($this->userId())]);
    }

    public function ubahPassword()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        return view('peserta/ubah_password');
    }

    public function updatePassword()
    {
        if ($redirect = $this->requireLogin()) {
            return $redirect;
        }

        $userModel = new UserModel();
        $user = $userModel->find($this->userId());
        $passwordLama = $this->request->getPost('password_lama');
        $passwordBaru = $this->request->getPost('password_baru');
        $konfirmasi = $this->request->getPost('konfirmasi_password');

        if (! password_verify($passwordLama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah.');
        }

        if ($passwordBaru !== $konfirmasi) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $userModel->update($this->userId(), ['password' => password_hash($passwordBaru, PASSWORD_DEFAULT)]);

        return redirect()->to(base_url('pelatihan/pengaturan'))->with('success', 'Password berhasil diubah.');
    }
}
