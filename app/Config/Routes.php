<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('login', 'Login::index');
$routes->get('me', 'User::me');
$routes->resource('letter', ['only' => 'show,update','filter' => 'role:supervisor']);
$routes->post('letter','Letter::create',['filter'=>'role:operator']);
$routes->delete('letter/(:any)','Letter::delete/$1',['filter'=>'role:admin']);
$routes->resource('user', ['except' => 'new,edit', 'filter' => 'role:admin']);
$routes->get('approvedLetter', 'Letter::approvedLetter',['filter'=>'role:pimpinan']);
$routes->post('dispose/(:any)', 'Disposisi::dispose/$1',['filter'=>'role:pimpinan']);
$routes->get('disposedLetter', 'Disposisi::index');
$routes->get('surat_(:segment)', 'Letter::index/$1',[], ['segment' => '(masuk|keluar)']);
