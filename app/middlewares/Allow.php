<?php

namespace App\Middlewares;

use App\Services\Auth\AuthMiddleware;

class Allow extends AuthMiddleware {

	/**
	 * @param  System\Request $request
	 * @param  string 				$role    
	 */
	protected function notAllowed($request, $role)
	{
		redirect(app_url('api/forbidden'));
	}

}