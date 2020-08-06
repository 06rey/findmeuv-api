<?php

namespace App\Middlewares;

use App\Services\Auth\AuthMiddleware;
use System\Request;
use Route;

class Guest extends AuthMiddleware {

	protected function forbidden($args)
	{
		redirect(app_url('api/forbidden'));
	}

}