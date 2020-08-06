<?php

namespace App\Services\Auth;

use Session;
use Log;

class Authentication {

	/**
	 * @var System\Session
	 */
	private $session;

	/**
	 * @var array
	 */
	public $user;

	public function __construct($session = null)
	{
		$this->session = Session::getInstance();

		//Check if visitor is athenticated
		if ($data = $this->session->get('user')) {
			$this->user = $data;
		}
		
	}

	/**
	 * Authenticate driver
	 */
	public function driver()
	{
		$user = model('user')->getOne([
							'username' => request()->get('username'),
							'password' => sha1(request()->get('password')),
							'role' => 'Driver',
							'status' => 1
						])->employee()->result();

		if ($user) {
			return $this->setUserSession($user[0]->employee[0], 'driver');
		}

		return $this->authenticationFailed();
	}

	/**
	 * Authenticate passenger
	 */
	public function passenger()
	{
		$user = model('passenger')->getOne([
							'email' => request()->header('PHP_AUTH_USER'),
							'password' => sha1(request()->header('PHP_AUTH_PW'))
						])->result();

		Log::data("username", request()->header('PHP_AUTH_USER'));
		Log::data("password", request()->header('PHP_AUTH_PW'));

		if ($user) {
			return $this->setUserSession($user[0], 'passenger');
		}
		return $this->authenticationFailed();
	}

	/**
	 * @param  array $credentials
	 * @return string
	 */
	private function setUserSession($credentials, $role)
	{	
		$credentials->{'role'} = $role;
		$this->session->regenerateId();
		$token = $this->session->token();
		$credentials->{'token'} = $token;
		$this->session->set('user', $credentials);

		return array('user'=> $credentials);
	}

	/**
	 * @return boolean
	 */
	private function authenticationFailed()
	{
		response()->setStatus(403)
							->json()
							->setMessage('Incorrect username or password.')
							->setError(true);
		return [];
	}

	/**
	 * Logout user
	 */
	public function logout()
	{
		$this->user = null;
		$this->session->end();
	}

	/**
	 * @return boolean
	 */
	public function isAuthenticated()
	{
		return !is_null($this->user);
	}

	/**
	 * @param  string $key
	 * @return string     
	 */
	public function user($key = null)
	{
		return $key === null ? $this->user : $this->user->$key;
	}

}