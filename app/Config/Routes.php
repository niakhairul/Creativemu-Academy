<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'Pelatihan::login');

$routes->get('pelatihan/login', 'Pelatihan::login');
$routes->get('pelatihan/register', 'Pelatihan::register');

$routes->post('register/save', 'Auth::saveRegister');
// Menggunakan match untuk menangani metode GET maupun POST agar tidak 404
$routes->match(['get', 'post'], 'login/process', 'Auth::loginProcess');
$routes->match(['get', 'post'], 'pelatihan/login/process', 'Auth::loginProcess');

$routes->get('logout', 'Auth::logout');

$routes->get('pelatihan/pendaftaran', 'Pelatihan::pendaftaran');

$routes->get('pelatihan/status', 'Pelatihan::status');
$routes->get('pelatihan/detail-kelas', 'Pelatihan::detailKelas');

$routes->get('pelatihan/materi', 'Pelatihan::materi');
$routes->get('pelatihan/daftar-materi', 'Pelatihan::daftarMateri');
$routes->get('pelatihan/tugas', 'Pelatihan::tugas');
$routes->post('pelatihan/upload-tugas', 'Pelatihan::uploadTugas');
$routes->get('peserta/dashboard', 'Pelatihan::dashboard');

// ===== MENU BARU PESERTA =====

$routes->get('pelatihan/profil', 'Pelatihan::profil');
$routes->get('pelatihan/edit-profil', 'Pelatihan::editProfil');
$routes->post('pelatihan/update-profil', 'Pelatihan::updateProfil');
$routes->get('pelatihan/daftar-kelas', 'Pelatihan::daftarKelas');
$routes->get('pelatihan/kbm', 'Pelatihan::kbm');
$routes->get('pelatihan/ujian', 'Pelatihan::ujian');
$routes->get('pelatihan/ujian/mulai', 'Pelatihan::kerjakanUjian');
$routes->post('pelatihan/ujian/kumpulkan', 'Pelatihan::submitUjian');
$routes->get('pelatihan/ujian/hasil', 'Pelatihan::hasilUjian');
$routes->get('pelatihan/angket', 'Pelatihan::angket');
$routes->post('pelatihan/angket/simpan', 'Pelatihan::simpanAngket');
$routes->get('pelatihan/sertifikat', 'Pelatihan::sertifikat');
$routes->get('pelatihan/pengaturan', 'Pelatihan::pengaturan');
$routes->get('pelatihan/ubah-password', 'Pelatihan::ubahPassword');
$routes->post('pelatihan/update-password', 'Pelatihan::updatePassword');
$routes->post('pelatihan/daftar', 'Pelatihan::simpanPendaftaran');
$routes->get('pelatihan/kelas', 'Pelatihan::kelas');
$routes->get('pelatihan/absensi', 'pelatihan::absensi');
$routes->post('pelatihan/absensi/simpan', 'pelatihan::simpanAbsensi');
$routes->get('pelatihan/riwayat-absensi', 'Pelatihan::riwayatAbsensi');

// ===== MENU ADMIN =====
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('pendaftaran', 'Admin::pendaftaran');
    $routes->get('master-kelas', 'Admin::masterKelas');
    
    // Ubah dari $routes->post menjadi $routes->match(['get', 'post'], ...)
    $routes->match(['get', 'post'], 'master-kelas/store', 'Admin::simpanKelas');
    $routes->match(['get', 'post'], 'master-kelas/simpan', 'Admin::simpanKelas');

    // ===== RUTE MENTOR ADMIN =====
    $routes->get('mentor', 'Admin::mentor');
    $routes->match(['get', 'post'], 'mentor/store', 'Admin::simpanMentor');
    $routes->match(['get', 'post'], 'mentor/simpan', 'Admin::simpanMentor');
    
    // Rute Tombol Aksi Mentor (Detail, Edit, Update, Delete)
    $routes->get('mentor/detail/(:num)', 'Admin::detailMentor/$1');
    $routes->get('mentor/edit/(:num)', 'Admin::editMentor/$1');
    $routes->post('mentor/update/(:num)', 'Admin::updateMentor/$1');
    $routes->match(['get', 'post'], 'mentor/delete/(:num)', 'Admin::deleteMentor/$1');
    // =============================

    $routes->get('data-peserta', 'Admin::dataPeserta');
    $routes->get('validasi', 'Admin::validasi');
    $routes->get('validasi/update/(:num)/(:alphanum)', 'Admin::updateValidasi/$1/$2');
});