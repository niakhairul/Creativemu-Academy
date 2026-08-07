<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('pelatihan/login', 'Pelatihan::login');
$routes->get('pelatihan/register', 'Pelatihan::register');

$routes->post('register/save', 'Auth::saveRegister');
$routes->post('login/process', 'Auth::loginProcess');

$routes->get('logout', 'Auth::logout');

$routes->get('pelatihan/pendaftaran', 'Pelatihan::pendaftaran');

$routes->get('pelatihan/status', 'Pelatihan::status');
$routes->get('pelatihan/detail-kelas', 'Pelatihan::detailKelas');

$routes->get('pelatihan/materi', 'Pelatihan::materi');
$routes->get('pelatihan/tugas', 'Pelatihan::tugas');

$routes->get('peserta/dashboard', 'Pelatihan::dashboard');

// ===== MENU BARU PESERTA =====

$routes->get('pelatihan/profil', 'Pelatihan::profil');
$routes->get('pelatihan/daftar-kelas', 'Pelatihan::daftarKelas');
$routes->get('pelatihan/kbm', 'Pelatihan::kbm');
$routes->get('pelatihan/pengaturan', 'Pelatihan::pengaturan');
$routes->post('pelatihan/daftar', 'Pelatihan::simpanPendaftaran');
$routes->get('pelatihan/kelas', 'Pelatihan::kelas');
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('admin/pendaftaran', 'Admin::pendaftaran');
