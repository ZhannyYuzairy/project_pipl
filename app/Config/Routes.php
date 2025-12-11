<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Routing\RouteCollection;
use Config\Services;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// Default
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // disarankan manual

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// root → redirect ke login atau dashboard sesuai sesi
$routes->get('/', 'Auth::login');

// AUTH
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// ================= OWNER =================
$routes->group('owner', [
    'namespace' => 'App\Controllers\Owner',
    'filter'    => ['auth', 'owner'], // wajib login + role owner
], static function ($routes) {

    // DASHBOARD
    $routes->get('dashboard', 'Dashboard::index');

    // ============================================================
    // PRODUK
    // ============================================================
    $routes->get('products', 'Products::index');
    $routes->get('products/create', 'Products::create');
    $routes->post('products/store', 'Products::store');

    $routes->get('products/edit/(:num)', 'Products::edit/$1');
    $routes->post('products/update/(:num)', 'Products::update/$1');

    // Hapus via GET (popup UI)
    $routes->get('products/delete/(:num)', 'Products::delete/$1');

    // Hapus final (POST nyata)
    $routes->post('products/destroy/(:num)', 'Products::destroy/$1');

    // Riwayat stok per produk
    $routes->get('products/history/(:num)', 'Products::history/$1');


    // ============================================================
    // LAPORAN PENJUALAN
    // ============================================================
    $routes->get('reports/penjualan', 'Reports::penjualan');

    // Export CSV
    $routes->get('reports/penjualan/export', 'Reports::penjualanExport');

    // Export PDF
    $routes->get('reports/penjualan/pdf', 'Reports::penjualanPdf');


    // ============================================================
    // LAPORAN STOK
    // ============================================================
    $routes->get('reports/stok', 'Reports::stok');

    // Export PDF stok
    $routes->get('reports/stok/pdf', 'Reports::stokPdf');


    // ============================================================
    // LAPORAN LABA RUGI
    // ============================================================
    $routes->get('reports/laba-rugi', 'Reports::labaRugi');

    // Export PDF laba rugi
    $routes->get('reports/laba-rugi/pdf', 'Reports::labaRugiPdf');


    // ============================================================
    // MANAJEMEN KASIR
    // ============================================================
    $routes->get('cashiers', 'Cashiers::index');
    $routes->get('cashiers/create', 'Cashiers::create');
    $routes->post('cashiers/store', 'Cashiers::store');

    $routes->get('cashiers/edit/(:num)', 'Cashiers::edit/$1');
    $routes->post('cashiers/update/(:num)', 'Cashiers::update/$1');

    // Aktif/nonaktif
    $routes->post('cashiers/activate/(:num)', 'Cashiers::activate/$1');
    $routes->post('cashiers/deactivate/(:num)', 'Cashiers::deactivate/$1');

    // Reset password kasir
    $routes->post('cashiers/resetpass/(:num)', 'Cashiers::resetPassword/$1');
});

// ================= KASIR =================
$routes->group('kasir', [
    'namespace' => 'App\Controllers\Kasir',
    'filter'    => ['auth', 'kasir'],
], static function ($routes) {

    // Dashboard kasir
    $routes->get('dashboard', 'Dashboard::index');

    // POS utama
    $routes->get('pos',            'Pos::index');
    $routes->post('pos/addItem',   'Pos::addItem');
    $routes->post('pos/checkout',  'Pos::checkout');

    // === CART (PAKAI GET) ===
    $routes->get('pos/updateQty',  'Pos::updateQty');   // ?product_id=...&qty=...
    $routes->get('pos/removeItem', 'Pos::removeItem');  // ?product_id=...
    $routes->get('pos/clearCart',  'Pos::clearCart');   // tanpa param

    // Struk
    $routes->get('pos/struk/(:num)',         'Pos::struk/$1');
    $routes->get('pos/struk-thermal/(:num)', 'Pos::strukThermal/$1');
    $routes->get('pos/struk-pdf/(:num)',     'Pos::strukPdf/$1');

    // AJAX search produk untuk autocomplete
    $routes->get('pos/search-product', 'Pos::searchProductAjax');

    // Riwayat transaksi kasir
    $routes->get('transaksi', 'Transaksi::index');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}
