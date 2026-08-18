<?php

namespace App\Controllers;

use App\Models\MentorModel;
use App\Models\KelasModel;
use App\Models\PesertaModel;
use App\Models\SertifikatModel;

class Admin extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function dashboard()
    {
        $mentorModel = new MentorModel();
        $data = [
            'title'            => 'Dashboard Admin',
            'total_kelas'      => 12,
            'total_mentor'     => $mentorModel->where('status', 'Aktif')->countAllResults(),
            'total_peserta'    => 48,
            'pending_validasi' => 3,
            'admin_name'       => 'Super Admin',
            'admin_photo'      => base_url('assets/img/admin-profile.jpg'),
        ];
        return view('admin/dashboard', $data);
    }

    public function masterKelas()
    {
        $kelasModel  = new \App\Models\KelasModel();
        $mentorModel = new \App\Models\MentorModel(); // <-- Panggil model mentor

        $data = [
            'title'  => 'Master Kelas - Panel Admin',
            'kelas'  => $kelasModel->findAll(),
            'mentor' => $mentorModel->findAll()  // <-- Kirim variabel mentor ke view
        ];
        
        return view('admin/master_kelas/index', $data);
    }

    public function mentor()
    {
        $mentorModel = new \App\Models\MentorModel();
        
        $data = [
            'title'  => 'Manajemen Mentor - Panel Admin',
            'mentor' => $mentorModel->findAll()
        ];
        
        return view('admin/mentor/index', $data); // Sesuaikan path view mentor Anda jika berbeda
    }

    public function dataPeserta()
    {
        $pesertaModel = new \App\Models\PesertaModel();
        
        $data = [
            'title'   => 'Data Peserta - Panel Admin',
            'peserta' => $pesertaModel->findAll()
        ];
        
        // Sesuaikan dengan nama folder view yang ada di dalam folder app/Views/admin/
        return view('admin/data_peserta/index', $data); 
    }

    public function validasi()
    {
        // Panggil model yang sesuai untuk validasi pendaftaran, contoh: PendaftaranModel
        $pendaftaranModel = new \App\Models\PendaftaranModel();
        
        $data = [
            'title'      => 'Validasi Pendaftaran - Panel Admin',
            'pendaftaran' => $pendaftaranModel->findAll()
        ];
        
        // Sesuaikan path view dengan struktur folder Anda di app/Views/admin/
        return view('admin/validasi/index', $data);
    }
    
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

    // FUNGSI LAPORAN (Pindahkan dari file lain ke sini)
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
        $data = ['title' => 'Pengaturan Akun - Panel Admin'];
        return view('admin/pengaturan/index', $data);
    }

   public function updatePengaturan()
    {
        $session = session();
        $emailLogin = $session->get('email') ?? 'admin@creativemu.ac.id';

        // Tangkap data dari form
        $namaAdmin    = $this->request->getPost('nama_admin');
        $emailAdmin   = $this->request->getPost('email_admin');
        $passwordBaru = $this->request->getPost('password_baru');

        $dataUpdate = [
            'nama'  => $namaAdmin,   
            'email' => $emailAdmin
        ];

        // Upload foto profil baru jika ada
        $fileFoto = $this->request->getFile('foto_profil');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move('assets/img', $namaFile);
            
            // UBAH 'foto' DI BAWAH INI MENJADI NAMA KOLOM ASLI DI DATABASE ANDA (contoh: 'foto_profil' atau 'avatar')
            $dataUpdate['foto_profil'] = $namaFile; 
            
            $session->set('foto', $namaFile);
        }

        // Perubahan password
        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        // Simpan ke database
        $db = \Config\Database::connect();
        $db->table('users') // Ganti dengan nama tabel Anda jika berbeda
           ->where('email', $emailLogin) 
           ->update($dataUpdate);

        $session->set('nama', $namaAdmin);
        $session->set('email', $emailAdmin);

        return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function logout()
{
    // Menghapus session
    session()->destroy();

    // Redirect ke halaman login
    return redirect()->to('/')->with('success', 'Berhasil logout.');
}
}