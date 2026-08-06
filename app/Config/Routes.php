<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('pelatihan/login', 'Pelatihan::login');
$routes->get('pelatihan/register', 'Pelatihan::register');
$routes->get('/pelatihan/pendaftaran', 'Pelatihan::pendaftaran');
$routes->get('pelatihan/status', 'Pelatihan::status');
$routes->get('pelatihan/detail-kelas', 'Pelatihan::detailKelas');