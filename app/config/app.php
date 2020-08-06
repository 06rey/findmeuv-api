<?php

// Pocket wifi ip: 192.168.8.100
// Localhost ip: 192.168.42.140

return [

	'app_name' => 'fmu_api',
	'app_url'	 => 'http://192.168.42.140',

	/**
	 * -------------------------------------------------------
	 * Application databases
	 * -------------------------------------------------------
	 */
	
	'mysql' => [
		'host' 		 => 'localhost',
		'db_name'  => 'findme_uv',
		'user'		 => 'root',
		'password' => ''
	],

	'sqlite' => [
		'host' 		 => 'localhost',
		'db_name'  => 'findme_uv',
		'user'		 => 'root',
		'password' => ''
	],

	/**
	 * -------------------------------------------------------
	 * Autoloading classes and aliases
	 * -------------------------------------------------------
	 */

	'providers' => [
		App\Providers\AppServiceProvider::class
	],

  'aliases' => [
  	'App' => System\Facades\App::class,
  	'Event' => System\Facades\Event::class,
		'Session'  => System\Facades\Session::class,
	  'Request'  => System\Facades\Request::class,
	  'DB' 			 => System\Facades\DB::class,
	  'Response' => System\Facades\Response::class,
	  'Route' => System\Facades\Route::class,
	  'Auth' 		 => App\Services\Facades\Auth::class,
	  'Log' 		 => System\Facades\Log::class
  ],

  'middlewares' => [
  	'auth' => App\Middlewares\Auth::class,
  	'guest' => App\Middlewares\Guest::class,
  	'allow' => App\Middlewares\Allow::class,
  ],

  'models' => ['user', 'employee', 'trip', 'booking', 'seat_reservation', 'passenger']

];