<?php

namespace App\Services\Auth;

use System\Contracts\MiddlewareContract;

class AuthMiddleware extends Authentication 
	implements MiddlewareContract {

	/**
	 * @param  mixed $args
	 */
	public function handle($request, $args = null)
	{
		$this->authenticate($request);
		$this->isAllowed($request, $args);
	}

	/**
	 * @param  System\Request $request
	 */
	private function authenticate($request)
	{
		if ($this->isAuthenticated()) {
			$this->forbidden($request);
		}else{
			$this->guest($request);
		}
	}

	/**
	 * @param  System\Request $request
	 */
	private function isAllowed($request, $args = null)
	{
		if (! is_null($args)) {
			if (isset($args['role'])) {
				if (strtolower($this->user->role) !== strtolower($args['role'])) {
					$this->notAllowed($request, $args['role']);
				}
			}
		}
	}

	/**
	 * @param  System\Request $request
	 */
	protected function guest($request)
	{
		//
	}

	/**
	 * @param  System\Request $request
	 */
	protected function forbidden($request)
	{
		//
	}

	/**
	 * @param  System\Request $request
	 * @param  string 				$role    
	 */
	protected function notAllowed($request, $role)
	{
		//
	}

}