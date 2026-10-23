<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ───────── Public routes (no auth required) ─────────
$routes->get('/',          'Home::index');
$routes->get('/login',    'Auth::login');
$routes->post('/login',   'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/register','Auth::register');
$routes->get('/logout',   'Auth::logout');

// ───────── Protected routes (authFilter applied) ─────────
$routes->group('', ['filter' => 'auth'], static function ($routes) {

    // Dashboard → Invoice page list with summary stats
    $routes->get('/dashboard',    'Invoice::index');

    // Create invoice
    $routes->get('/invoices/create',    'Invoice::create');
    $routes->post('/invoice/store',     'Invoice::store');

    // Status toggle (unpaid ↔ paid)
    $routes->post('/invoice/status/(:num)',    'Invoice::updateStatus/$1');

    // Send invoice reminder via WhatsApp
    $routes->post('/invoice/wa/(:num)',    'Invoice::sendWhatsApp/$1');

    // Profile routes
    $routes->get('/profile',   'Profile::index');
    $routes->post('/profile/bank',  'Profile::updateBank');
    $routes->post('/profile/qris',  'Profile::uploadQris');
});
