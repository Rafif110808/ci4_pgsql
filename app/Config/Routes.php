<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('user', 'UserController::index'); // halaman utama
$routes->get('user/fetch', 'UserController::fetch'); // ambil semua data / search
$routes->post('user/store', 'UserController::store'); // simpan data baru
$routes->get('user/edit/(:num)', 'UserController::edit/$1'); // ambil data untuk edit
$routes->post('user/update/(:num)', 'UserController::update/$1'); // update data
$routes->get('user/delete/(:num)', 'UserController::delete/$1'); // hapus data
