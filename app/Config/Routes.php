<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('pelatihan/login', 'Pelatihan::login');
$routes->get('pelatihan/register', 'Pelatihan::register');
$routes->post('register/save', 'Auth::saveRegister');
$routes->post('login/process', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');
$routes->get('/pelatihan/pendaftaran', 'Pelatihan::pendaftaran');
$routes->get('pelatihan/status', 'Pelatihan::status');
$routes->get('pelatihan/detail-kelas', 'Pelatihan::detailKelas');
$routes->get('pelatihan/materi', 'Pelatihan::materi');
$routes->get('pelatihan/tugas', 'Pelatihan::tugas');
$routes->get('peserta/dashboard', 'Pelatihan::dashboard');
