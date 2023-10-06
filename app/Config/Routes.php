<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('login', 'Login::index');
$routes->get('me', 'User::me');
// $routes->resource('letter', ['only' => 'show','filter' => 'role:supervisor']);
$routes->post('letter','Letter::create',['filter'=>'role:operator']);
$routes->put('approveLetter','Letter::approveLetter',['filter'=>'role:supervisor']);
$routes->delete('letter/(:any)','Letter::delete/$1',['filter'=>'role:admin']);
$routes->resource('user', ['except' => 'new,edit', 'filter' => 'role:admin,pimpinan']);
$routes->get('approvedLetter', 'Letter::approvedLetter',['filter'=>'role:pimpinan']);
$routes->post('dispose', 'Disposisi::dispose',['filter'=>'role:pimpinan']);
$routes->get('disposedLetter', 'Disposisi::index');
// $routes->post('user/(:num)','User::update/$1',['filter'=>'role:admin']);
$routes->get('surat_(:segment)', 'Letter::index/$1',[], ['segment' => '(masuk|keluar)']);
$routes->get('viewSurat/(:num)','Letter::viewPdf/$1');
$routes->get('dashboard','Letter::dashboard');
