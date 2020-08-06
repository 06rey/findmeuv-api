<?php

namespace App\Middlewares;

use App\Services\Auth\AuthMiddleware;
use Route;

class Auth extends AuthMiddleware {

	protected function guest($request)
	{
		redirect(app_url('api/unauthorized'));
	}

}