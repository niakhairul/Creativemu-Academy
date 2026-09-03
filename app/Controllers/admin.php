<?php

namespace App\Controllers;

use App\Models\MentorModel;
use App\Models\KelasModel;
use App\Models\PesertaModel;
use App\Models\AngketModel;
use App\Models\SertifikatModel;
use App\Models\PendaftaranModel;

class Admin extends BaseController
{
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
{
    // Do Not Edit This Line
    parent::initController($request, $response, $logger);

    // Cek apakah session session 'logged_in' ada DAN rolenya adalah 'admin'
    $session = session();
    if (!$session->get('logged_in') || $session->get('role') != 'admin') {
        // Jika belum login / bukan admin, arahkan ke halaman login
        header('Location: ' . base_url('pelatihan/login'));
        exit();
    }
}

    public function index()
{
    // 1. Panggil model mentor (sesuaikan nama modelnya jika berbeda)
    $mentorModel = new \App\Models\MentorModel();
    
    // 2. Masukkan data mentor ke dalam array $data
    $data = [
        'title' => 'Master Kelas',
        'data_mentor' => $mentorModel->findAll() // <-- INI YANG KURANG
    ];

    // 3. Kirim $data ke view
    return view('admin/master_kelas/index', $data);
}

    public function __construct()
    {
        helper(['url', 'form']);
    }

    // --- DASHBOARD ---
    public function dashboard()
    {
        $mentorModel       = new MentorModel();
        $kelasModel        = new KelasModel();
        $pendaftaranModel  = new PendaftaranModel();
        
        $db = \Config\Database::connect();
        $userId = session()->get('id_users');
        
        $currentUser = $db->table('users')->where('id_users', $userId)->get()->getRowArray();
        $foto = (!empty($currentUser['foto_profil'])) ? $currentUser['foto_profil'] : 'admin-profile.jpg';

        $data = [
            'title'            => 'Dashboard Admin',
            'total_kelas'      => $kelasModel->countAllResults(), 
            'total_mentor'     => $mentorModel->where('status', 'Aktif')->countAllResults(),
            'total_peserta'    => $pendaftaranModel->countAllResults(),
            'pending_validasi' => $pendaftaranModel->where('status_pembayaran', 'pending')->countAllResults(), // Dihitung otomatis
            'admin_name'       => $currentUser['nama'] ?? 'Super Admin',
            'admin_photo'      => base_url('assets/img/' . $foto),
        ];
        
        return view('admin/dashboard', $data);
    }

    // --- MASTER KELAS ---
    // --- MASTER KELAS ---
public function masterKelas()
{
    $kelasModel  = new \App\Models\KelasModel();
    $mentorModel = new \App\Models\MentorModel();

    $data = [
        'title'  => 'Master Kelas',
        'kelas'  => $kelasModel->select('kelas.*, mentor.nama_mentor, mentor.keahlian')
                                ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
                                ->findAll(),
        // Ubah kembali menjadi 'mentor' agar cocok dengan view
        'mentor' => $mentorModel->findAll() 
    ];

    return view('admin/master_kelas/index', $data);
}

    public function simpanKelas()
    {
        $allPostData = $this->request->getPost();
        
        // Cek apakah id_mentor ada dalam data yang dikirim
        if (!isset($allPostData['id_mentor']) || empty($allPostData['id_mentor'])) {
            die("Error: 'id_mentor' tidak ditemukan atau kosong. Data yang diterima: " . print_r($allPostData, true));
        }

        $kelasModel = new KelasModel();

        $fileThumbnail = $this->request->getFile('foto');
        $namaThumbnail = 'default-kelas.jpg';
        
        if ($fileThumbnail && $fileThumbnail->isValid() && !$fileThumbnail->hasMoved()) {
            $namaThumbnail = $fileThumbnail->getRandomName();
            $fileThumbnail->move('uploads/kelas/', $namaThumbnail);
        }

        $data = [
            'id_mentor'           => $this->request->getPost('id_mentor'),
            'kategori'            => $this->request->getPost('kategori'),
            'nama_kelas'          => $this->request->getPost('nama_kelas'),
            'deskripsi'           => $this->request->getPost('deskripsi'),
            'kapasitas'           => $this->request->getPost('kapasitas'),
            'jumlah_pertemuan'    => $this->request->getPost('jumlah_pertemuan'),
            'harga_reguler'       => $this->request->getPost('harga_reguler'), // <-- Diubah ke harga reguler
            'harga_privat'        => $this->request->getPost('harga_privat'),   // <-- Ditambahkan harga privat
            'tanggal_mulai_kelas' => $this->request->getPost('tanggal_mulai_kelas'), 
            'ringkasan'           => $this->request->getPost('ringkasan'),
            'status'              => $this->request->getPost('status'),
            'tipe_kelas'          => $this->request->getPost('tipe_kelas'), 
            'lokasi_media'        => '-', 
            'thumbnail'           => $namaThumbnail,
        ];

        if (!$kelasModel->insert($data)) {
            dd($kelasModel->errors());
        }

        return redirect()->to('/admin/master-kelas')->with('success', 'Master kelas berhasil ditambahkan!');
    }

    public function editKelas($id)
    {
        $kelasModel  = new KelasModel();
        $mentorModel = new MentorModel();

        $data = [
            'title'  => 'Edit Kelas',
            'kelas'  => $kelasModel->find($id),
            'mentor' => $mentorModel->findAll()
        ];

        return view('admin/master_kelas/edit', $data);
    }

    public function updateKelas($id)
    {
        $kelasModel = new KelasModel();

        $idMentor = $this->request->getPost('id_mentor');
        if (empty($idMentor)) {
            return redirect()->back()->withInput()->with('error', 'Silakan pilih mentor pengampu terlebih dahulu!');
        }
        
        // Ambil data kelas lama untuk pengecekan gambar
        $kelasLama = $kelasModel->find($id);

        $data = [
            'nama_kelas'          => $this->request->getPost('nama_kelas'),
            'id_mentor'           => $this->request->getPost('id_mentor'),
            'kategori'            => $this->request->getPost('kategori'),
            'tipe_kelas'          => $this->request->getPost('tipe_kelas'),
            'harga_reguler'       => $this->request->getPost('harga_reguler'), // <-- Diubah ke harga reguler
            'harga_privat'        => $this->request->getPost('harga_privat'),   // <-- Ditambahkan harga privat
            'jumlah_pertemuan'    => $this->request->getPost('jumlah_pertemuan'),
            'kapasitas'           => $this->request->getPost('kapasitas'),
            'tanggal_mulai_kelas' => $this->request->getPost('tanggal_mulai_kelas'),
            'ringkasan'           => $this->request->getPost('ringkasan'), 
            'deskripsi'           => $this->request->getPost('deskripsi'), 
            'status'              => $this->request->getPost('status'),
        ];

        // Cek apakah ada file foto/thumbnail banner baru yang di-upload
        $fileThumbnail = $this->request->getFile('foto');
        if ($fileThumbnail && $fileThumbnail->isValid() && !$fileThumbnail->hasMoved()) {
            $namaThumbnail = $fileThumbnail->getRandomName();
            $fileThumbnail->move('uploads/kelas/', $namaThumbnail);
            
            $data['thumbnail'] = $namaThumbnail;

            if (!empty($kelasLama['thumbnail']) && $kelasLama['thumbnail'] != 'default-kelas.jpg') {
                $pathLama = 'uploads/kelas/' . $kelasLama['thumbnail'];
                if (file_exists($pathLama)) {
                    unlink($pathLama);
                }
            }
        }

        $kelasModel->update($id, $data);

        return redirect()->to('/admin/master-kelas')->with('success', 'Data kelas berhasil diperbarui!');
    }

    // --- MENTOR ---
    public function mentor()
    {
        $mentorModel = new MentorModel();
        
        $data = [
            'title'       => 'Manajemen Mentor - Panel Admin',
            'mentor'      => $mentorModel->findAll(),
            'total_aktif' => $mentorModel->where('status', 'Aktif')->countAllResults(false)
        ];
        
        return view('admin/mentor/index', $data); 
    }

    public function simpan()
{
    $db = \Config\Database::connect();

    $data = [
        'id_users'     => session()->get('id_users') ? session()->get('id_users') : 1,
        'nama_mentor' => $this->request->getPost('nama_mentor'),
        'email'       => $this->request->getPost('email'),
        'telepon'     => $this->request->getPost('telepon'),
        'keahlian'    => $this->request->getPost('keahlian'),
        'pengalaman'  => $this->request->getPost('pengalaman'),
        'bio'         => $this->request->getPost('bio'), 
        'status'      => $this->request->getPost('status'),
    ];

    $fileCv = $this->request->getFile('cv');
    if ($fileCv && $fileCv->isValid() && !$fileCv->hasMoved()) {
        $namaFileCv = $fileCv->getRandomName();
        $fileCv->move('uploads/cv', $namaFileCv);
        $data['cv'] = $namaFileCv;
    }

    // Insert langsung ke tabel database (bypass model)
    $db->table('mentor')->insert($data);

    return redirect()->to(base_url('admin/mentor'))->with('success', 'Mentor baru berhasil ditambahkan.');
}
    public function editMentor($id)
    {
        $mentorModel = new MentorModel();
        
        $data = [
            'title'  => 'Edit Mentor',
            'mentor' => $mentorModel->find($id)
        ];

        return view('admin/mentor/edit', $data);
    }

    public function updateMentor($id)
    {
        $mentorModel = new MentorModel();

        $data = [
            'nama_mentor' => $this->request->getPost('nama_mentor'),
            'email'       => $this->request->getPost('email'),
            'telepon'     => $this->request->getPost('telepon'),
            'keahlian'    => $this->request->getPost('keahlian'),
            'pengalaman'  => $this->request->getPost('pengalaman'),
            'bio'         => $this->request->getPost('bio'), 
            'status'      => $this->request->getPost('status'),
        ];

        $fileCv = $this->request->getFile('cv');
        if ($fileCv && $fileCv->isValid() && !$fileCv->hasMoved()) {
            $namaFileCv = $fileCv->getRandomName();
            $fileCv->move('uploads/cv', $namaFileCv);
            $data['cv'] = $namaFileCv;
        }

        $mentorModel->update($id, $data);

        return redirect()->to(base_url('admin/mentor'))->with('success', 'Data mentor berhasil diperbarui.');
    }

    // --- PESERTA & PENDAFTARAN ---
    public function dataPeserta()
    {
        $pesertaModel = new PesertaModel();
        
        $data = [
            'title'   => 'Data Peserta - Panel Admin',
            'peserta' => $pesertaModel->findAll()
        ];
        
        return view('admin/data_peserta/index', $data); 
    }

    public function pendaftaran()
    {
        $pendaftaranModel = new PendaftaranModel();

        $data = [
            'title' => 'Data Pendaftaran Peserta',
            'pendaftaran' => $pendaftaranModel
                ->select('pendaftaran.*, users.nama, users.email, users.no_hp, kelas.nama_kelas')
                ->join('users', 'users.id_users = pendaftaran.id_users', 'left')
                ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
                ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
                ->findAll()
        ];

        return view('admin/pendaftaran/index', $data);
    }

    public function validasi()
    {
        $pendaftaranModel = new \App\Models\PendaftaranModel();

        $data = [
            'title' => 'Validasi Pendaftaran - Panel Admin',
            'pendaftaran' => $pendaftaranModel
                ->select('pendaftaran.*, kelas.nama_kelas')
                ->join('kelas', 'kelas.id_kelas = pendaftaran.id_kelas', 'left')
                ->orderBy('pendaftaran.id_pendaftaran', 'DESC')
                ->findAll()
        ];

        return view('admin/validasi/index', $data);
    }

    public function updateValidasi($id_pendaftaran, $aksi)
    {
        $pendaftaranModel = new \App\Models\PendaftaranModel();

        // Pastikan aksi benar
        if ($aksi === 'setuju') {
            $statusBaru = 'valid';
            $statusPendaftaranBaru = 'Disetujui';
        } elseif ($aksi === 'tolak') {
            $statusBaru = 'rejected';
            $statusPendaftaranBaru = 'Ditolak';
        } else {
            return redirect()->to(base_url('admin/validasi'))
                ->with('error', 'Aksi tidak valid: ' . $aksi);
        }

        // Ambil data pendaftaran untuk pengecekan NIS dan Email peserta
        $pendaftaran = $pendaftaranModel->find($id_pendaftaran);

        if (!$pendaftaran) {
            return redirect()->to(base_url('admin/validasi'))
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $nisBaru = $pendaftaran['nis'];

        // Jika disetujui dan peserta belum punya NIS, generate NIS baru
        if ($aksi === 'setuju' && empty($nisBaru)) {
            $tanggalHariIni = date('Ymd');

            $pendaftaranTerakhir = $pendaftaranModel
                ->like('nis', $tanggalHariIni, 'after')
                ->orderBy('id_pendaftaran', 'DESC')
                ->first();

            if ($pendaftaranTerakhir && !empty($pendaftaranTerakhir['nis'])) {
                $urutanTerakhir = (int) substr($pendaftaranTerakhir['nis'], -3);
                $urutanBaru = $urutanTerakhir + 1;
            } else {
                $urutanBaru = 1;
            }

            $nisBaru = $tanggalHariIni . str_pad($urutanBaru, 3, '0', STR_PAD_LEFT);
        }

        // Siapkan data yang akan di-update
        $dataUpdate = [
            'status_pembayaran'   => $statusBaru,
            'status_pendaftaran'  => $statusPendaftaranBaru,
        ];

        // Masukkan NIS jika aksinya disetujui
        if ($aksi === 'setuju') {
            $dataUpdate['nis'] = $nisBaru;
        }

        // Update database menggunakan model
        $hasil = $pendaftaranModel->update($id_pendaftaran, $dataUpdate);

        if (!$hasil) {
            return redirect()->to(base_url('admin/validasi'))
                ->with('error', 'Database gagal diperbarui.');
        }

        // =======================================================
        // LETAKKAN KODE PENGIRIMAN EMAIL DI SINI
        // =======================================================
        $email = \Config\Services::email();
        $email->setTo($pendaftaran['email']);
        $email->setFrom('email_anda@gmail.com', 'Creativemu Academy');

        if ($aksi === 'setuju') {
            $email->setSubject('Selamat! Pendaftaran Kelas Anda di Creativemu Academy Telah Diterima');
            $email->setMessage('
                <div style="font-family: Arial, sans-serif; padding: 20px; color: #333; background-color: #faf5ff; border-radius: 10px;">
                    <h2 style="color: #7c3aed;">Halo, ' . esc($pendaftaran['nama'] ?? 'Peserta') . '</h2>
                    <p>Kabar gembira! Pendaftaran Anda untuk mengikuti pelatihan di <strong>Creativemu Academy</strong> telah <strong>DISETUJUI</strong>.</p>
                    <p>NIS Anda adalah: <strong>' . $nisBaru . '</strong></p>
                    <p>Silakan lanjutkan proses registrasi atau login akun Anda melalui tautan di bawah ini:</p>
                    <div style="margin: 30px 0;">
                        <a href="' . base_url('pelatihan/login') . '" style="background-color: #7c3aed; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login ke Akun</a>
                    </div>
                    <p>Salam hangat,<br><strong>Tim Akademik Creativemu</strong></p>
                </div>
            ');
        } else {
            $email->setSubject('Informasi Status Pendaftaran Kelas Creativemu Academy');
            $email->setMessage('
                <div style="font-family: Arial, sans-serif; padding: 20px; color: #333; background-color: #fff1f2; border-radius: 10px;">
                    <h2 style="color: #e11d48;">Halo, ' . esc($pendaftaran['nama'] ?? 'Peserta') . '</h2>
                    <p>Mohon maaf, pendaftaran Anda di <strong>Creativemu Academy</strong> belum dapat kami setujui saat ini.</p>
                    <p>Salam hangat,<br><strong>Tim Akademik Creativemu</strong></p>
                </div>
            ');
        }

        $email->send(); // Ganti bagian ini atau bungkus dengan if di bawah:

        if (!$email->send()) {
            print_r($email->printDebugger(['headers', 'subject', 'body']));
            exit;

        return redirect()->to(base_url('admin/validasi'))
            ->with('pesan', $pesanSukses);
        }
    }

    public function proses_validasi($id_pendaftaran)
{
    $status = $this->request->getPost('status_pembayaran'); 
    $alasan = $this->request->getPost('alasan_penolakan');

    $dataUpdate = [
        'status_pembayaran' => $status,
        'status_pendaftaran' => ($status === 'valid' || $status === 'Disetujui' || $status === 'approved') ? 'Disetujui' : 'Pending'
    ];

    if ($status === 'rejected' || $status === 'Ditolak') {
        $dataUpdate['alasan_penolakan'] = $alasan;
    } else {
        $dataUpdate['alasan_penolakan'] = null; 
    }

    $pendaftaranModel = new \App\Models\PendaftaranModel(); 
    $pendaftaranLama = $pendaftaranModel->find($id_pendaftaran);

    $nisBaru = $pendaftaranLama['nis'] ?? null;

    if (($status === 'valid' || $status === 'Disetujui' || $status === 'approved') && empty($nisBaru)) {
        $tanggalHariIni = date('Ymd');
        $pendaftaranTerakhir = $pendaftaranModel
            ->like('nis', $tanggalHariIni, 'after')
            ->orderBy('id_pendaftaran', 'DESC')
            ->first();

        $urutanBaru = ($pendaftaranTerakhir && !empty($pendaftaranTerakhir['nis'])) 
            ? (int) substr($pendaftaranTerakhir['nis'], -3) + 1 
            : 1;

        $nisBaru = $tanggalHariIni . str_pad($urutanBaru, 3, '0', STR_PAD_LEFT);
        $dataUpdate['nis'] = $nisBaru;
    }

    $fileBukti = $this->request->getFile('bukti_pembayaran');
    if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
        $newName = $fileBukti->getRandomName();
        $folderTujuan = FCPATH . 'uploads/bukti/';
        if (!is_dir($folderTujuan)) {
            mkdir($folderTujuan, 0777, true);
        }
        $fileBukti->move($folderTujuan, $newName);
        $dataUpdate['bukti_pembayaran'] = $newName;
    }

    // Update ke database
    $pendaftaranModel->update($id_pendaftaran, $dataUpdate);

    // =======================================================
    // KIRIM EMAIL NOTIFIKASI
    // =======================================================
    if ($pendaftaranLama && !empty($pendaftaranLama['email'])) {
        $email = \Config\Services::email();
        $email->setTo($pendaftaranLama['email']);
        $email->setFrom('email_anda@gmail.com', 'Creativemu Academy'); // Ganti dengan email Anda

        $isDisetujui = ($status === 'valid' || $status === 'Disetujui' || $status === 'approved');

        if ($isDisetujui) {
            $email->setSubject('Selamat! Pendaftaran Kelas Anda di Creativemu Academy Telah Diterima');
            $email->setMessage('
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 25px; color: #333; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <h2 style="color: #7c3aed; margin-top: 0;">Halo, ' . esc($pendaftaranLama['nama'] ?? 'Peserta') . '</h2>
                    <p>Kabar gembira! Pendaftaran Anda untuk mengikuti pelatihan di <strong>Creativemu Academy</strong> telah resmi <strong>DISETUJUI</strong>.</p>
                    
                    <p>Berikut adalah detail informasi pendaftaran Anda:</p>
                    <table style="width: 100%; border-collapse: collapse; background-color: #faf5ff; border-radius: 6px; overflow: hidden; margin: 15px 0;">
                        <tr>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff; font-weight: bold; width: 40%;">Nama Lengkap</td>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff;">' . esc($pendaftaranLama['nama'] ?? '-') . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff; font-weight: bold;">No HP / WhatsApp</td>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff;">' . esc($pendaftaranLama['no_hp'] ?? '-') . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff; font-weight: bold;">Kelas yang Diambil</td>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff;">' . esc($pendaftaranLama['pilihan_pelatihan'] ?? '-') . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff; font-weight: bold;">Tempat Pelatihan</td>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff;">' . esc($pendaftaranLama['lokasi_pelatihan'] ?? '-') . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff; font-weight: bold;">Jenis Kelas</td>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff;">' . esc($pendaftaranLama['jenis_kelas'] ?? '-') . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff; font-weight: bold;">Metode Pelatihan</td>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff;">' . esc($pendaftaranLama['metode_pembelajaran'] ?? '-') . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff; font-weight: bold;">Kategori Kelas</td>
                            <td style="padding: 10px 15px; border-bottom: 1px solid #e9d5ff;">' . esc($pendaftaranLama['kategori_kelas'] ?? '-') . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 15px; font-weight: bold;">Tanggal Mulai</td>
                            <td style="padding: 10px 15px;">' . esc($pendaftaranLama['tanggal_mulai_kelas'] ?? '-') . '</td>
                        </tr>
                    </table>

                    <p>Langkah selanjutnya, silakan melakukan registrasi akun peserta Anda melalui tautan di bawah ini:</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . base_url('pelatihan/register') . '" style="background-color: #7c3aed; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Registrasi Akun Sekarang</a>
                    </div>
                    
                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 0;">Salam hangat,<br><strong>Tim Akademik Creativemu Academy</strong></p>
                </div>
            ');
        } else {
            $email->setSubject('Informasi Penting: Status Pendaftaran Creativemu Academy');
            $email->setMessage('
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 25px; color: #333; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                    <h2 style="color: #e11d48; margin-top: 0;">Halo, ' . esc($pendaftaranLama['nama'] ?? 'Peserta') . '</h2>
                    <p>Terima kasih telah mendaftar di <strong>Creativemu Academy</strong>. Mohon maaf, pendaftaran Anda saat ini <strong>belum dapat kami setujui</strong>.</p>
                    
                    <div style="background-color: #fff1f2; border-left: 4px solid #e11d48; padding: 15px; margin: 20px 0; border-radius: 4px;">
                        <p style="margin: 0 0 5px 0; font-weight: bold; color: #9f1239;">Catatan / Alasan Penolakan:</p>
                        <p style="margin: 0; color: #881337;">' . esc($alasan ?? 'Tidak ada catatan khusus.') . '</p>
                    </div>

                    <p>Anda dapat memperbarui data atau mengunggah ulang bukti pembayaran melalui halaman cek status:</p>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="' . base_url('pelatihan/cek-status') . '" style="background-color: #e11d48; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">Cek Status & Upload Ulang</a>
                    </div>

                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;">
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 0;">Salam hangat,<br><strong>Tim Akademik Creativemu Academy</strong></p>
                </div>
            ');
        }

        if (!$email->send()) {
            print_r($email->printDebugger(['headers', 'subject', 'body']));
            exit;
        }
    }
    // =======================================================

    if ($status === 'rejected' || $status === 'Ditolak') {
        $pesan = 'Pendaftaran berhasil ditolak.';
    } else {
        $pesan = 'Pendaftaran berhasil disetujui.';
    }

    return redirect()->to(base_url('admin/validasi'))
                     ->with('success', $pesan);
}
    

   public function angket()
{
    $db = \Config\Database::connect();
    
    // Sambungkan angket ke kelas, lalu kelas ke mentor
    $data['angket'] = $db->table('angket_pertanyaan')
                         ->select('angket_pertanyaan.*, kelas.nama_kelas, mentor.nama_mentor')
                         ->join('kelas', 'kelas.id_kelas = angket_pertanyaan.id_kelas', 'left')
                         ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left') // <- Ambil mentor lewat kelas
                         ->groupBy('angket_pertanyaan.judul_angket') 
                         ->get()
                         ->getResultArray();

    $data['title'] = 'Monitoring Angket';
    return view('admin/angket/index', $data);
}

    // --- TAMBAH ANGKET ---
    public function tambahAngket()
{
    $kelasModel  = new \App\Models\KelasModel();
    $mentorModel = new \App\Models\MentorModel(); // Pastikan model mentor dipanggil

    $data = [
        'title'  => 'Buat Angket Evaluasi',
        'kelas'  => $kelasModel->findAll(),
        'mentor' => $mentorModel->findAll() // Pastikan variabel 'mentor' dikirim ke view
    ];

    return view('admin/angket/tambah_angket', $data);
}

public function simpanAngket()
{
    // 1. Menangkap data utama dari form
    $judulAngket = $this->request->getPost('judul_angket'); // <-- Tangkap judul angket
    $idKelas     = $this->request->getPost('id_kelas');
    $kategori    = $this->request->getPost('kategori');   // Berbentuk Array
    $pertanyaan  = $this->request->getPost('pertanyaan'); // Berbentuk Array

    $db = \Config\Database::connect();
    $builder = $db->table('angket_pertanyaan'); 

    // 3. Simpan setiap baris pertanyaan dinamis menggunakan perulangan (looping)
    if (!empty($pertanyaan)) {
        for ($i = 0; $i < count($pertanyaan); $i++) {
            $dataSimpan = [
                'judul_angket' => $judulAngket,          // <-- Masukkan judul angket di sini
                'id_kelas'     => $idKelas,
                'kategori'     => $kategori[$i] ?? null,
                'pertanyaan'   => $pertanyaan[$i],
                'created_at'   => date('Y-m-d H:i:s')
            ];

            // Masukkan ke database
            $builder->insert($dataSimpan);
        }
    }

    // 4. Arahkan kembali ke halaman daftar angket dengan pesan sukses
    return redirect()->to(base_url('admin/angket'))->with('success', 'Konfigurasi angket berhasil disimpan!');
}
    public function getAngket()
{
    return $this->db->table('angket_pertanyaan')
        ->select('angket_pertanyaan.*, kelas.id_kelas, kelas.nama_kelas, mentor.id_mentor, mentor.nama_mentor')
        ->join('kelas', 'kelas.id_kelas = angket_pertanyaan.id_kelas', 'left')
        ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
        ->get()
        ->getResultArray();
}

public function edit($id)
{
    $db = \Config\Database::connect();
    
    // 1. Ambil data angket utama berdasarkan ID yang diklik
    $angketUtama = $db->table('angket_pertanyaan')
                      ->where('id_angket_pertanyaan', $id)
                      ->get()
                      ->getRowArray();

    if (empty($angketUtama)) {
        return redirect()->to('admin/angket')->with('error', 'Data angket tidak ditemukan.');
    }

    $judulTarget = $angketUtama['judul_angket'];

    // 2. Ambil SEMUA baris pertanyaan yang memiliki judul_angket yang sama
    $data['semua_pertanyaan'] = $db->table('angket_pertanyaan')
                                   ->where('judul_angket', $judulTarget)
                                   ->get()
                                   ->getResultArray();

    $data['angket'] = $angketUtama;
    $data['title']  = 'Edit Angket Evaluasi';
    $data['id']     = $id; // <--- TAMBAHKAN BARIS INI AGAR $id DIKENALI DI VIEW
    
    // 3. Ambil data kelas dan mentor untuk pilihan dropdown
    $data['kelas']  = $db->table('kelas')->get()->getResultArray();
    $data['mentor'] = $db->table('mentor')->get()->getResultArray();

    return view('admin/angket/edit', $data);
}

public function update($id)
{
    $db = \Config\Database::connect();
    
    // Ambil judul lama untuk acuan data yang mau di-update
    $angketLama = $db->table('angket_pertanyaan')->where('id_angket_pertanyaan', $id)->get()->getRowArray();
    $judulLama  = $angketLama['judul_angket'] ?? '';

    // Tangkap data dari form edit
   
    $judulBaru  = $this->request->getPost('judul_angket');
    $idKelas    = $this->request->getPost('id_kelas');
    $kategori   = $this->request->getPost('kategori');   // Array
    $pertanyaan = $this->request->getPost('pertanyaan'); // Array

    // Hapus dulu data lama yang memiliki judul tersebut agar bisa diganti dengan yang baru dikirim
    if (!empty($judulLama)) {
        $db->table('angket_pertanyaan')->where('judul_angket', $judulLama)->delete();
    } else {
        $db->table('angket_pertanyaan')->where('id_angket_pertanyaan', $id)->delete();
    }

    // Masukkan kembali data yang sudah diperbarui melalui looping
    if (!empty($pertanyaan)) {
        for ($i = 0; $i < count($pertanyaan); $i++) {
            $dataSimpan = [
                'judul_angket' => $judulBaru,
                'id_kelas'     => $idKelas,
                'kategori'     => $kategori[$i] ?? 'Umum',
                'pertanyaan'   => $pertanyaan[$i],
                'created_at'   => date('Y-m-d H:i:s')
            ];
            $db->table('angket_pertanyaan')->insert($dataSimpan);
        }
    }

    return redirect()->to('admin/angket')->with('success', 'Data angket berhasil diperbarui.');
}

    public function detailAngket($id)
{
    $db = \Config\Database::connect();

    // 1. Ambil data utama berdasarkan ID baris yang diklik, lengkap dengan join ke kelas dan mentor
    $data['angket'] = $db->table('angket_pertanyaan')
                         ->select('angket_pertanyaan.*, kelas.nama_kelas, mentor.nama_mentor')
                         ->join('kelas', 'kelas.id_kelas = angket_pertanyaan.id_kelas', 'left')
                         ->join('mentor', 'mentor.id_mentor = kelas.id_mentor', 'left')
                         ->where('angket_pertanyaan.id_angket_pertanyaan', $id)
                         ->get()
                         ->getRowArray();

    if (empty($data['angket'])) {
        return redirect()->to('admin/angket')->with('error', 'Data angket tidak ditemukan.');
    }

    $judulTarget = $data['angket']['judul_angket'];

    // 2. Ambil SEMUA daftar pertanyaan yang memiliki judul_angket yang sama (untuk ditampilkan di tabel bawah)
    $data['semua_pertanyaan'] = $db->table('angket_pertanyaan')
                                   ->where('judul_angket', $judulTarget)
                                   ->get()
                                   ->getResultArray();

    $data['title'] = 'Detail Angket';
    return view('admin/angket/detail', $data);
}
public function delete($id)
{
    $db = \Config\Database::connect();
    
    // Hapus langsung menggunakan query builder berdasarkan primary key
    $db->table('angket_pertanyaan')->where('id_angket_pertanyaan', $id)->delete();
    
    return redirect()->to('admin/angket')->with('success', 'Data angket berhasil dihapus.');
}

public function hasilAngket()
{
    $db = \Config\Database::connect();
    
    // Menggunakan tabel 'peserta' dan menyesuaikan kolom relasinya
    $data['hasil'] = $db->table('jawaban_angket')
                        ->select('jawaban_angket.*, angket_pertanyaan.judul_angket, peserta.nama_peserta as nama_siswa')
                        ->join('angket_pertanyaan', 'angket_pertanyaan.id_angket_pertanyaan = jawaban_angket.id_pertanyaan', 'left')
                        ->join('peserta', 'peserta.id_peserta = jawaban_angket.id_siswa', 'left')
                        ->get()
                        ->getResultArray();
    
    $data['title'] = 'Hasil Angket Siswa';
    return view('admin/angket/hasil', $data);
}

// --- SERTIFIKAT ---
    
    public function sertifikat()
    {
        $sertifikatModel = new SertifikatModel();
        $data['sertifikat'] = $sertifikatModel->select('sertifikat.*, peserta.nama_peserta, peserta.email, peserta.telepon, kelas.nama_kelas')
                                            ->join('peserta', 'peserta.id_peserta = sertifikat.id_peserta')
                                            ->join('kelas', 'kelas.id_kelas = sertifikat.id_kelas')
                                            ->findAll();
        $data['title'] = 'Manajemen Sertifikat Peserta';
        return view('admin/sertifikat/index', $data);
    }

    public function uploadSertifikat()
    {
        $pesertaModel = new PesertaModel();
        $kelasModel   = new KelasModel();
        
        $data = [
            'title'   => 'Upload Sertifikat',
            'peserta' => $pesertaModel->findAll(),
            'kelas'   => $kelasModel->findAll()
        ];
        
        return view('admin/sertifikat/upload', $data);
    }

    public function storeSertifikat()
    {
        $fileSertifikat = $this->request->getFile('file_sertifikat');
        if ($fileSertifikat && $fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
            $namaFile = $fileSertifikat->getRandomName();
            $fileSertifikat->move('uploads/sertifikat', $namaFile);
            
            $sertifikatModel = new SertifikatModel();
            $sertifikatModel->save([
                'nomor_sertifikat' => $this->request->getPost('nomor_sertifikat'),
                'id_peserta'       => $this->request->getPost('id_peserta'),
                'id_kelas'         => $this->request->getPost('id_kelas'),
                'tanggal_terbit'   => $this->request->getPost('tanggal_terbit'),
                'file_sertifikat'  => $namaFile
            ]);
            
            return redirect()->to(base_url('admin/sertifikat'))->with('success', 'Sertifikat berhasil diunggah!');
        }
        
        return redirect()->back()->with('error', 'Gagal mengunggah file sertifikat.');
    }

    public function downloadSertifikat($id)
    {
        $sertifikatModel = new SertifikatModel();
        $sertifikat = $sertifikatModel->find($id);
        
        if ($sertifikat) {
            $path = 'uploads/sertifikat/' . $sertifikat['file_sertifikat'];
            return $this->response->download($path, null);
        }
        
        return redirect()->to(base_url('admin/sertifikat'))->with('error', 'Sertifikat tidak ditemukan.');
    }

    // --- LAPORAN & PENGATURAN ---
    public function laporan()
    {
        $pesertaModel = new PesertaModel();
        $mentorModel  = new MentorModel();

        $data = [
            'title'   => 'Laporan Data Peserta & Mentor',
            'peserta' => $pesertaModel->findAll(),
            'mentor'  => $mentorModel->findAll(),
        ];

        return view('admin/laporan/index', $data);
    }

    public function pengaturan()
    {
        $session = session();
        $userId = $session->get('id_users');

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id_users', $userId)->get()->getRowArray();

        $data = [
            'title' => 'Pengaturan Akun - Panel Admin',
            'user'  => $user 
        ];

        return view('admin/pengaturan/index', $data);
    }

    public function updatePengaturan()
    {
        $session = session();
        $userId = $session->get('id_users');

        $namaAdmin    = $this->request->getPost('nama_admin');
        $emailAdmin   = $this->request->getPost('email_admin');
        $passwordBaru = $this->request->getPost('password_baru');

        $dataUpdate = [
            'nama'  => $namaAdmin,   
            'email' => $emailAdmin
        ];

        $fileFoto = $this->request->getFile('foto_profil');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move('assets/img', $namaFile);
            
            $dataUpdate['foto_profil'] = $namaFile; 
            $session->set('foto_profil', $namaFile);
        }

        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        $db = \Config\Database::connect();
        $db->table('users')
           ->where('id_users', $userId) 
           ->update($dataUpdate);

        $session->set('nama', $namaAdmin);
        $session->set('email', $emailAdmin);

        return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Berhasil logout.');
    }
}