<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('pelatihan/login', 'Pelatihan::login');
$routes->get('pelatihan/register', 'Pelatihan::register');