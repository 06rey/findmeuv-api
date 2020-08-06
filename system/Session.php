<?php

namespace System;

class Session {

	/** 
	 * Session name
	 * @var [type]
	 */
	private $name;

	/**
	 * Session save path
	 * @var string
	 */
	private $savePath;

	public function __construct($name, $path)
	{
		$this->name = $name.'_session';
		$this->savePath = $path;
		ini_set('session.use_strict_mode', 1);
	}

	/**
	 * Generate token
	 * 
	 * @return string
	 */
	public function token($len = 60)
	{
		if (function_exists('openssl_random_pseudo_bytes')) {
			$randomBytes = openssl_random_pseudo_bytes(($len/2)+1);
		}else{
			$randomBytes = random_bytes(mt_rand(10, $len));
		}
		return bin2hex($randomBytes).time();
	}

	/**
	 * Set session data
	 * 
	 * @param string $name 
	 * @param string $value
	 */
	public function set($name, $value) 
	{
		$_SESSION[$name] = $value;
	}

	/**
	 * Get session data
	 * 
	 * @param  string $name
	 * @return mixed[]      
	 */
	public function get($name = null) 
	{
		if ( is_null($name) ) {
			return $_SESSION;
		}

		if( isset($_SESSION[$name]) ) {
			return $_SESSION[$name];
		}

		return false;
	}

	/**
	 * Set application session
	 * 
	 * @param  string $name
	 */
	public function start() 
	{
		session_name($this->name);
		session_save_path($this->savePath);
		session_start();

		if ( !empty($_SESSION['session_last_access_at'])
					&& $_SESSION['session_last_access_at'] < (time() - 86400) ) {
      $this->regenerateId();
    }

    $_SESSION['session_last_access_at'] = time();
	}

	/**
	 * Generate new session id
	 * 
	 * @return [type] [description]
	 */
	public function regenerateId() 
	{
		$this->end();
		$session_id = $this->token();
		session_commit();
		session_id($session_id);
		session_start();
	}

	/**
	 * Destroy application session
	 */
	public function end()
	{
		session_unset();
		session_destroy();
	}

}