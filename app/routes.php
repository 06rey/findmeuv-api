<?php

use System\Facades\Route;

/**
 * Register API routes
 */

Route::api(function(){

	Route::post('/logout', 'logout@AuthController');
	Route::get('/forbidden', 'forbidden@AuthController');
	Route::get('/unauthorized', 'unauthorized@AuthController');

	// Passenger route
	Route::group('/passenger', function(){

		Route::get('/', 'index@PassengerController');
		Route::post('/auth', 'passengerAuth@AuthController');

	});

	// Driver route
	Route::group('/driver', function(){

		Route::get('/', 'index@DriverController');
		Route::get('/auth', 'driverAuth@AuthController');

	});

});

/**
 * Register WEB routes
 */

Route::web(function(){

	Route::get('/web', function(){
		return 'FIND ME UV Web Routes';
	});

	Route::get('/test', 'index@TestController');

});