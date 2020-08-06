<?php

namespace App\Controllers;

use System\Controller;
use Auth;

class AuthController extends Controller {

	public function __construct()
	{
		// $this->middleware('guest')->except('logout','unauthorized', 'forbidden');
	}

	/**
	 * Authenticate driver
	 */
	public function driverAuth()
	{
		return response()->json(Auth::driver());
	}

	/**
	 * Authenticate passenger
	 */
	public function passengerAuth()
	{
		return response()->json(Auth::passenger());
	}

	public function logout()
	{
		Auth::logout();
		return response()->json([]);
	}

	/**
	 * @return string
	 */
	public function unauthorized()
	{
		return response()->setStatus(401)
										->json()
										->setMessage('Unauthorized')
										->setError(true)
										->__toString();
	}

	/**
	 * @return string
	 */
	public function forbidden()
	{
		return response()->setStatus(403)
										->json()
										->setMessage('Forbidden')
										->setError(true)
										->__toString();
	}

}