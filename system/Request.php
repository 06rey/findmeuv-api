<?php

namespace System;

class Request {

	/**
	 * @var array
	 */
	private $get = [];

	/**
	 * @var array
	 */
	private $post = [];

	/**
	 * @var array
	 */
	private $header = [];

	public function __construct()
	{
		$this->setHeader();
		$this->setGet();
		$this->setPost();
		$this->validateUri();
	}

	/**
	 * @param  string $key
	 * @return string      
	 */
	public function get($key)
	{
		return isset($this->get[$key]) ? $this->get[$key] : null;
	}

	/**
	 * @param  string $key
	 * @return string      
	 */
	public function post($key)
	{
		return isset($this->post[$key]) ? $this->post[$key] : null;
	}

	/**
	 * @param string $key  
	 * @return string
	 */
	public function header($key)
	{
		return isset($this->header[$key]) ? $this->header[$key] : null;
	}

	/**
	 * @return string
	 */
	public function pathInfo()
	{
		return $this->header['PATH_INFO'];
	}

	/**
	 * @return void
	 */
	private function setHeader()
	{
		$this->header = $_SERVER;
	}

	/**
	 * @return void
	 */
	private function setGet()
	{
		if ($this->header['REQUEST_METHOD'] === 'GET') {
			foreach ($_GET as $key => $value) {
				$this->get[strtolower($key)] = filter_var($value, FILTER_SANITIZE_STRING);
			}
		}
	}

	/**
	 * @return void
	 */
	private function setPost()
	{
		if ($this->header['REQUEST_METHOD'] === 'POST') {
			foreach ($_POST as $key => $value) {
				$this->post[strtolower($key)] = filter_var($value, FILTER_SANITIZE_STRING);
			}
		}
	}

	/**
	 * Validate path info
	 * 
	 * @return void
	 */
	private function validateUri() 
	{
		if ( ! isset($this->header['PATH_INFO']) ) {
			$this->header['PATH_INFO'] = '/';
		}else{
			$this->header['PATH_INFO'] = filter_var( 
												$this->header['PATH_INFO'], 
												FILTER_SANITIZE_STRING 
											);
			$this->header['PATH_INFO'] = strtolower(rtrim($this->header['PATH_INFO'], '/'));
		}
	}

}