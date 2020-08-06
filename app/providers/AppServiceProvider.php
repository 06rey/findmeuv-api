<?php

namespace App\Providers;

use System\ServiceProvider;
use App\Services\Auth\Authentication;
use Session;
use Event;
use App;
use Auth;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Register Services
	 */
	public function register()
	{
		$this->auth();
	}

	/**
	 * Register authentication
	 */
	private function auth()
	{
		$session = Session::getInstance();
		$session->start();

		App::bind('auth', function() use ($session) {
			return new Authentication($session);
		});
	}

}