<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->post('/auth/login', 'Auth::login');

$routes->group('api', ['namespace' => 'App\Controllers\API'], function($routes){
	$routes->get('users', 'Users::index');
	$routes->post('users/create', 'Users::create');
	$routes->get('users/FindById/(:num)', 'Users::FindById/$1');
	$routes->get('users/edit/(:num)', 'Users::edit/$1');
	$routes->put('users/update/(:num)', 'Users::update/$1');
	$routes->delete('users/delete/(:num)', 'Users::delete/$1');
	//Orders
	$routes->get('orders', 'Orders::index');
	$routes->post('orders/create', 'Orders::create');
	$routes->get('orders/FindById/(:num)', 'Orders::FindById/$1');
	$routes->get('orders/FindByUserId/(:num)', 'Orders::FindByUserId/$1');
	$routes->delete('orders/delete/(:num)', 'Orders::delete/$1');
	$routes->put('orders/closeOrder/(:num)', 'Orders::closeOrder/$1');
	$routes->get('orders/FindByUserIdAndOrders/(:num)', 'Orders::FindByUserIdAndOrders/$1');
	$routes->get('orders/FindByOrderId/(:num)', 'Orders::FindByOrderId/$1');	
	$routes->get('orders/FindOrderAll', 'Orders::FindOrderAll');
	

	//Invoices
	$routes->get('invoices', 'Invoices::index');
	$routes->get('invoices/FindById/(:num)', 'Invoices::FindById/$1');
	$routes->post('invoices/create', 'Invoices::create');
	$routes->put('invoices/update/(:num)', 'Invoices::update/$1');
	$routes->put('invoices/updatePayment/(:num)', 'Invoices::updatePayment/$1');
	$routes->delete('invoices/delete/(:num)', 'Invoices::delete/$1');
	
	//Payments
	$routes->get('payments', 'Payments::index');
	$routes->post('payments/create', 'Payments::create');
	//$routes->put('payments/updatePayment/(:num)', 'Payments::updatePayment/$1');
	$routes->get('payments/FindById/(:num)', 'Payments::FindById/$1');
	$routes->put('payments/update/(:num)', 'Payments::update/$1');
	$routes->delete('payments/delete/(:num)', 'Payments::delete/$1');

});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
