<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class HakAksesController extends Controller
{
    protected $filePath;

    public function __construct()
    {
        $this->filePath = WRITEPATH . 'permissions.json';
    }

    /**
     * Memeriksa hak akses administrator
     */
    private function checkAdminAccess()
    {
        $role = session()->get('role');
        if (empty($role) || $role !== 'admin') {
            return redirect()->to(base_url('pelatihan/login'))->with('error', 'Akses ditolak. Halaman khusus Administrator.');
        }
        return null;
    }

    /**
     * Definisi daftar modul & izin default
     */
    private function getDefaultPermissions(): array
    {
        $modules = [
            'dashboard'    => ['label' => 'Dashboard Overview', 'icon' => 'fa-chart-pie', 'desc' => 'Ringkasan statistik dan aktivitas sistem'],
            'master_kelas' => ['label' => 'Master Kelas Pelatihan', 'icon' => 'fa-book-bookmark', 'desc' => 'Manajemen kelas, kurikulum, dan sesi jadwal'],
            'mentor'       => ['label' => 'Instruktur', 'icon' => 'fa-chalkboard-user', 'desc' => 'Manajemen data pengampu dan penugasan kelas'],
            'data_peserta' => ['label' => 'Data Peserta', 'icon' => 'fa-users', 'desc' => 'Pengelolaan data profil siswa dan status keaktifan'],
            'validasi'     => ['label' => 'Validasi Pendaftaran', 'icon' => 'fa-clipboard-check', 'desc' => 'Pemeriksaan bukti bayar dan verifikasi berkas'],
            'buku_induk'   => ['label' => 'Buku Induk Peserta', 'icon' => 'fa-book-open', 'desc' => 'Dokumentasi induk, sertifikasi, dan NIS resmi'],
            'angket'       => ['label' => 'Angket & Kuesioner', 'icon' => 'fa-poll', 'desc' => 'Instrumen evaluasi kepuasan peserta dan instruktur'],
            'sertifikat'   => ['label' => 'Manajemen Sertifikat', 'icon' => 'fa-award', 'desc' => 'Penerbitan, penomoran, dan arsip sertifikat kelulusan'],
            'laporan'      => ['label' => 'Laporan & Rekapitulasi', 'icon' => 'fa-file-lines', 'desc' => 'Laporan peserta, instruktur, angket, dan presensi'],
            'pengaturan'   => ['label' => 'Pengaturan Sistem', 'icon' => 'fa-gear', 'desc' => 'Konfigurasi akun administrator dan preferensi sistem'],
        ];

        $actions = ['lihat', 'tambah', 'edit', 'hapus', 'export'];

        // Preset Admin: 100% full akses
        $adminPerms = [];
        foreach (array_keys($modules) as $m) {
            foreach ($actions as $act) {
                $adminPerms[$m][$act] = true;
            }
        }

        // Preset Instruktur (Mentor)
        $instrukturPerms = [];
        foreach (array_keys($modules) as $m) {
            foreach ($actions as $act) {
                $instrukturPerms[$m][$act] = false;
            }
        }
        $instrukturPerms['dashboard']['lihat'] = true;
        $instrukturPerms['master_kelas']['lihat'] = true;
        $instrukturPerms['master_kelas']['edit'] = true;
        $instrukturPerms['mentor']['lihat'] = true;
        $instrukturPerms['mentor']['edit'] = true;
        $instrukturPerms['data_peserta']['lihat'] = true;
        $instrukturPerms['buku_induk']['lihat'] = true;
        $instrukturPerms['angket']['lihat'] = true;
        $instrukturPerms['sertifikat']['lihat'] = true;
        $instrukturPerms['laporan']['lihat'] = true;
        $instrukturPerms['laporan']['export'] = true;
        $instrukturPerms['pengaturan']['lihat'] = true;

        // Preset Peserta
        $pesertaPerms = [];
        foreach (array_keys($modules) as $m) {
            foreach ($actions as $act) {
                $pesertaPerms[$m][$act] = false;
            }
        }
        $pesertaPerms['dashboard']['lihat'] = true;
        $pesertaPerms['master_kelas']['lihat'] = true;
        $pesertaPerms['buku_induk']['lihat'] = true;
        $pesertaPerms['angket']['lihat'] = true;
        $pesertaPerms['angket']['tambah'] = true;
        $pesertaPerms['sertifikat']['lihat'] = true;
        $pesertaPerms['sertifikat']['export'] = true;
        $pesertaPerms['pengaturan']['lihat'] = true;
        $pesertaPerms['pengaturan']['edit'] = true;

        return [
            'modules' => $modules,
            'roles' => [
                'admin' => [
                    'title' => 'Administrator',
                    'badge' => 'Super Admin',
                    'color' => '#794bc4',
                    'permissions' => $adminPerms,
                ],
                'mentor' => [
                    'title' => 'Instruktur',
                    'badge' => 'Pengajar',
                    'color' => '#2563eb',
                    'permissions' => $instrukturPerms,
                ],
                'peserta' => [
                    'title' => 'Peserta Pelatihan',
                    'badge' => 'Siswa',
                    'color' => '#16a34a',
                    'permissions' => $pesertaPerms,
                ],
            ],
        ];
    }

    /**
     * Membaca permissions tersimpan
     */
    private function loadPermissions(): array
    {
        $default = $this->getDefaultPermissions();

        if (!file_exists($this->filePath)) {
            $this->savePermissionsToFile($default);
            return $default;
        }

        $content = file_get_contents($this->filePath);
        $saved = json_decode($content, true);

        if (!is_array($saved) || empty($saved['roles'])) {
            return $default;
        }

        // Gabungkan agar modul baru selalu muncul
        foreach ($default['roles'] as $roleKey => $roleData) {
            if (!isset($saved['roles'][$roleKey])) {
                $saved['roles'][$roleKey] = $roleData;
            } else {
                foreach ($default['modules'] as $modKey => $modInfo) {
                    if (!isset($saved['roles'][$roleKey]['permissions'][$modKey])) {
                        $saved['roles'][$roleKey]['permissions'][$modKey] = $roleData['permissions'][$modKey];
                    }
                }
            }
        }
        $saved['modules'] = $default['modules'];

        return $saved;
    }

    /**
     * Menyimpan permissions ke JSON
     */
    private function savePermissionsToFile(array $data): bool
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return (bool) file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * Halaman Utama Hak Akses
     */
    public function index()
    {
        if ($redirect = $this->checkAdminAccess()) {
            return $redirect;
        }

        $allData = $this->loadPermissions();
        $selectedRole = $this->request->getGet('role') ?? 'admin';

        if (!isset($allData['roles'][$selectedRole])) {
            $selectedRole = 'admin';
        }

        $data = [
            'title'         => 'Manajemen Hak Akses - Panel Admin',
            'activeMenu'    => 'hak-akses',
            'modules'       => $allData['modules'],
            'roles'         => $allData['roles'],
            'selectedRole'  => $selectedRole,
            'roleData'      => $allData['roles'][$selectedRole],
            'actionsList'   => [
                'lihat'  => ['label' => 'Lihat',  'icon' => 'fa-eye',       'color' => '#10b981'],
                'tambah' => ['label' => 'Tambah', 'icon' => 'fa-plus',      'color' => '#3b82f6'],
                'edit'   => ['label' => 'Edit',   'icon' => 'fa-pen-to-square', 'color' => '#f59e0b'],
                'hapus'  => ['label' => 'Hapus',  'icon' => 'fa-trash-can', 'color' => '#ef4444'],
                'export' => ['label' => 'Export', 'icon' => 'fa-file-export', 'color' => '#8b5cf6'],
            ],
        ];

        return view('admin/hak_akses/index', $data);
    }

    /**
     * Simpan Perubahan Hak Akses Role
     */
    public function update()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $role = $this->request->getPost('role');
        $allData = $this->loadPermissions();

        if (!isset($allData['roles'][$role])) {
            return redirect()->to(base_url('admin/hak-akses'))->with('error', 'Role tidak valid.');
        }

        $submittedPerms = $this->request->getPost('perms') ?? [];
        $actions = ['lihat', 'tambah', 'edit', 'hapus', 'export'];

        // Update permissions role terpilih
        $newPerms = [];
        foreach (array_keys($allData['modules']) as $modKey) {
            foreach ($actions as $act) {
                $newPerms[$modKey][$act] = !empty($submittedPerms[$modKey][$act]);
            }
        }

        $allData['roles'][$role]['permissions'] = $newPerms;
        $this->savePermissionsToFile($allData);

        return redirect()->to(base_url('admin/hak-akses?role=' . $role))
            ->with('success', 'Hak akses untuk peran ' . $allData['roles'][$role]['title'] . ' berhasil diperbarui.');
    }

    /**
     * Reset Permissions ke Default
     */
    public function reset()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('pelatihan/login'));
        }

        $role = $this->request->getGet('role') ?? 'admin';
        $default = $this->getDefaultPermissions();
        $this->savePermissionsToFile($default);

        return redirect()->to(base_url('admin/hak-akses?role=' . $role))
            ->with('success', 'Seluruh konfigurasi hak akses berhasil dikembalikan ke standar awal.');
    }
}
