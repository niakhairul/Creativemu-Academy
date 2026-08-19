<?php

namespace App\Controllers;

use App\Models\MentorModel;
use App\Models\KelasModel;
use App\Models\PesertaModel;
use App\Models\SertifikatModel;
use App\Models\PendaftaranModel;

class Admin extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function dashboard()
    {
        $mentorModel = new MentorModel();
        $db = \Config\Database::connect();
        $userId = session()->get('id_users');
        
        // Ambil data user yang sedang login dari database
        $currentUser = $db->table('users')->where('id_users', $userId)->get()->getRowArray();

        // Tentukan foto profil (gunakan bawaan jika kosong)
        $foto = (!empty($currentUser['foto_profil'])) ? $currentUser['foto_profil'] : 'admin-profile.jpg';

        $data = [
            'title'            => 'Dashboard Admin',
            'total_kelas'      => 12,
            'total_mentor'     => $mentorModel->where('status', 'Aktif')->countAllResults(),
            'total_peserta'    => 48,
            'pending_validasi' => 3,
            'admin_name'       => $currentUser['nama'] ?? 'Super Admin',
            'admin_photo'      => base_url('assets/img/' . $foto),
        ];
        
        return view('admin/dashboard', $data);
    }

    public function masterKelas()
    {
        $kelasModel  = new KelasModel();
        $mentorModel = new MentorModel(); // Panggil model mentor

        $data = [
            'title'  => 'Master Kelas - Panel Admin',
            'kelas'  => $kelasModel->findAll(),
            'mentor' => $mentorModel->findAll()  // Kirim variabel mentor ke view
        ];
        
        return view('admin/master_kelas/index', $data);
    }

    public function mentor()
    {
        $mentorModel = new MentorModel();
        
        $data = [
            'title'  => 'Manajemen Mentor - Panel Admin',
            'mentor' => $mentorModel->findAll()
        ];
        
        return view('admin/mentor/index', $data); 
    }

    // Menampilkan halaman form edit mentor berdasarkan ID
    public function editMentor($id)
    {
        $mentorModel = new MentorModel();
        
        $data = [
            'title'  => 'Edit Mentor',
            'mentor' => $mentorModel->find($id)
        ];

        return view('admin/mentor/edit', $data);
    }

    // Memproses update data mentor ke database
    // Memproses update data mentor ke database
    public function updateMentor($id)
    {
        $mentorModel = new MentorModel();

        // Ambil data dari form
        $data = [
            'nama_mentor' => $this->request->getPost('nama_mentor'),
            'email'       => $this->request->getPost('email'),
            'telepon'     => $this->request->getPost('telepon'),
            'keahlian'    => $this->request->getPost('keahlian'),
            'pengalaman'  => $this->request->getPost('pengalaman'),
            'status'      => $this->request->getPost('status'),
        ];

        // Tangkap file CV jika ada yang di-upload baru
        $fileCv = $this->request->getFile('cv');
        if ($fileCv && $fileCv->isValid() && !$fileCv->hasMoved()) {
            $namaFileCv = $fileCv->getRandomName();
            $fileCv->move('uploads/cv', $namaFileCv);
            $data['cv'] = $namaFileCv;
        }

        // Simpan perubahan ke database menggunakan Model
        $mentorModel->update($id, $data);

        // Redirect kembali dengan pesan sukses
        return redirect()->to(base_url('admin/mentor'))->with('success', 'Data mentor berhasil diperbarui.');
    }

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
    $pendaftaranModel = new \App\Models\PendaftaranModel();

    $data = [
        'title' => 'Data Pendaftaran Peserta',

        'pendaftaran' => $pendaftaranModel
            ->select('pendaftaran.*, users.nama, users.email, users.no_hp, kelas.nama_kelas')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->join('kelas', 'kelas.id = pendaftaran.kelas_id', 'left')
            ->orderBy('pendaftaran.id', 'DESC')
            ->findAll()
    ];

    return view('admin/pendaftaran/index', $data);
}
    public function validasi()
{
    $pendaftaranModel = new PendaftaranModel();

    $data = [
        'title' => 'Validasi Pendaftaran - Panel Admin',

        'pendaftaran' => $pendaftaranModel
            ->select('pendaftaran.*, users.nama, users.email, users.no_hp, kelas.nama_kelas')
            ->join('users', 'users.id_users = pendaftaran.user_id', 'left')
            ->join('kelas', 'kelas.id = pendaftaran.kelas_id', 'left')
            ->orderBy('pendaftaran.id', 'DESC')
            ->findAll()
    ];

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
        $userId = $session->get('id_users'); // Ambil ID user dari session login

        $db = \Config\Database::connect();
        $user = $db->table('users')->where('id_users', $userId)->get()->getRowArray();

        $data = [
            'title' => 'Pengaturan Akun - Panel Admin',
            'user'  => $user // Pastikan baris ini ada agar $user terbaca di view
        ];

        return view('admin/pengaturan/index', $data);
    }

    public function updatePengaturan()
    {
        $session = session();
        $userId = $session->get('id_users'); // Gunakan ID agar aman

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
            
            $dataUpdate['foto_profil'] = $namaFile; 
            $session->set('foto_profil', $namaFile);
        }

        // Perubahan password jika diisi
        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        // Simpan ke database berdasarkan id_users
        $db = \Config\Database::connect();
        $db->table('users')
           ->where('id_users', $userId) 
           ->update($dataUpdate);

        // Perbarui data session agar langsung berubah di navbar/tampilan
        $session->set('nama', $namaAdmin);
        $session->set('email', $emailAdmin);

        return redirect()->to(base_url('admin/pengaturan'))->with('success', 'Pengaturan berhasil diperbarui!');
    }

    public function logout()
    {
        // Menghapus session
        session()->destroy();

        // Redirect ke halaman login (atau root '/')
        return redirect()->to('/')->with('success', 'Berhasil logout.');
    }
}