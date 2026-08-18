<?php

namespace App\Controllers;

use App\Models\MentorModel;
use App\Models\KelasModel; // Pastikan Model Kelas dipanggil di atas

class Admin extends BaseController
{
    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function dashboard()
    {
        $mentorModel = new MentorModel(); // Panggil MentorModel di dashboard

        $data = [
            'title'            => 'Dashboard Admin',
            'total_kelas'      => 12,
            'total_mentor'     => $mentorModel->where('status', 'Aktif')->countAllResults(), // <-- DIPERBAIKI: Menghitung mentor yang statusnya 'Aktif' saja dari database
            'total_peserta'    => 48,
            'pending_validasi' => 3,
            'admin_name'       => 'Super Admin',
            'admin_photo'      => base_url('assets/img/admin-profile.jpg'),
        ];

        return view('admin/dashboard', $data);
    }

    public function masterKelas()
    {
        $kelasModel  = new KelasModel();
        $mentorModel = new MentorModel();

        $data = [
            'title'  => 'Master Kelas - Panel Admin',
            'kelas'  => $kelasModel->findAll(),
            'mentor' => $mentorModel->where('status', 'Aktif')->findAll() // <-- Opsional: jika di master kelas pilihan mentor yang muncul hanya yang aktif
        ];
        
        return view('admin/master_kelas/index', $data);
    }

    public function tambahKelas()
    {
        $mentorModel = new MentorModel();

        $data = [
            'title'  => 'Tambah Kelas Baru',
            'mentor' => $mentorModel->where('status', 'Aktif')->findAll() // <-- DIPERBAIKI: Hanya mengambil mentor yang statusnya 'Aktif' untuk form kelas
        ];

        return view('admin/kelas/tambah', $data);
    }

    public function simpanKelas()
    {
        $kelasModel = new KelasModel();

        $data = [
            'nama_kelas'    => $this->request->getVar('nama_kelas'),
            'id_mentor'     => $this->request->getVar('id_mentor'),
            'kategori'      => $this->request->getVar('kategori'),
            'kapasitas'     => $this->request->getVar('kapasitas'),
            'tanggal_kelas' => $this->request->getVar('tanggal_kelas'),
            'ringkasan'     => $this->request->getVar('ringkasan'),
            'deskripsi'     => $this->request->getVar('deskripsi'),
        ];

        if (empty($data['nama_kelas'])) {
            return redirect()->back()->with('error', 'Form nama kelas wajib diisi!');
        }

        $kelasModel->insert($data);

        return redirect()->to(base_url('admin/master-kelas'))->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    public function mentor()
    {
        $mentorModel = new MentorModel();

        $data = [
            'title'  => 'Data Mentor - Panel Admin',
            'mentor' => $mentorModel->findAll()
        ];
        
        return view('admin/mentor/index', $data);
    }

    public function simpanMentor()
    {
        $namaMentor = $this->request->getPost('nama_mentor');
        if (empty($namaMentor)) {
            return redirect()->back()->with('error', 'Nama mentor wajib diisi!');
        }

        $fileCv = $this->request->getFile('cv');
        $namaCv = null;

        if ($fileCv && $fileCv->isValid() && !$fileCv->hasMoved()) {
            $namaCv = $fileCv->getRandomName();
            $fileCv->move('uploads/cv', $namaCv);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('mentor');

        $data = [
            'id_user'     => session()->get('id_user') ?? 1,
            'nama_mentor' => $namaMentor,
            'email'       => $this->request->getPost('email'),
            'telepon'     => $this->request->getPost('telepon'),
            'keahlian'    => $this->request->getPost('keahlian'),
            'pengalaman'  => $this->request->getPost('pengalaman'),
            'status'      => $this->request->getPost('status'),
            'cv'          => $namaCv,
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $builder->insert($data);

        return redirect()->to(base_url('admin/mentor'))->with('success', 'Data mentor berhasil ditambahkan!');
    }

    // ==========================================
    // TAMBAHAN METHOD AKSI MENTOR (DETAIL, EDIT, UPDATE, DELETE)
    // ==========================================

    public function detailMentor($id)
    {
        $mentorModel = new MentorModel();
        $data = [
            'title'  => 'Detail Mentor - Panel Admin',
            'mentor' => $mentorModel->find($id)
        ];

        return view('admin/mentor/detail', $data);
    }

    public function editMentor($id)
    {
        $mentorModel = new MentorModel();
        $data = [
            'title'  => 'Edit Data Mentor - Panel Admin',
            'mentor' => $mentorModel->find($id)
        ];

        return view('admin/mentor/edit', $data);
    }

    public function updateMentor($id)
    {
        $mentorModel = new MentorModel();
        
        $mentorModel->update($id, [
            'nama_mentor' => $this->request->getPost('nama_mentor'),
            'email'       => $this->request->getPost('email'),
            'telepon'     => $this->request->getPost('telepon'),
            'keahlian'    => $this->request->getPost('keahlian'),
            'pengalaman'  => $this->request->getPost('pengalaman'),
            'status'      => $this->request->getPost('status')
        ]);

        return redirect()->to(base_url('admin/mentor'))->with('success', 'Data mentor berhasil diperbarui!');
    }

    public function deleteMentor($id)
    {
        $mentorModel = new MentorModel();
        $mentorModel->delete($id);

        return redirect()->to(base_url('admin/mentor'))->with('success', 'Data mentor berhasil dihapus!');
    }

    // ==========================================

    public function dataPeserta()
    {
        $data = ['title' => 'Data Peserta - Panel Admin'];
        return view('admin/data_peserta/index', $data);
    }

    public function validasi()
    {
        $data = ['title' => 'Validasi Pendaftaran - Panel Admin'];
        return view('admin/validasi/index', $data);
    }

    public function sertifikat()
    {
        $data = ['title' => 'Manajemen Sertifikat - Panel Admin'];
        return view('admin/sertifikat/index', $data);
    }

    public function laporan()
    {
        $data = ['title' => 'Laporan - Panel Admin'];
        return view('admin/laporan/index', $data);
    }

    public function pengaturan()
    {
        $data = ['title' => 'Pengaturan Akun - Panel Admin'];
        return view('admin/pengaturan/index', $data);
    }

    public function updateValidasi($id = null, $status = null)
    {
        if ($status == 'Diterima') {
            session()->setFlashdata('success', 'Pendaftaran berhasil disetujui/divalidasi!');
        } else {
            session()->setFlashdata('warning', 'Pendaftaran telah ditolak.');
        }

        return redirect()->to(base_url('admin/validasi'));
    }
}