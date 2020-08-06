<?php

namespace System\Response;

class Json {

	/**
	 * @var array
	 */
	private $response = [
		'meta' => [
			'code' => 200,
			'error' => false,
			'prev_page' => 0,
			'current_page' => 0,
			'count' => 1
		],
		'data' => []
	];

	/**
	 * @param string $message
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->response['meta']['message'] = $message;
		return $this;
	}

	/**
	 * @param mixed[string|int] $code
	 * @return $this
	 */
	public function setCode($code = 200)
	{
		$this->response['meta']['code'] = $code;
		return $this;
	}

	/**
	 * @param boolean $error
	 * @return $this
	 */
	public function setError($error = false)
	{
		$this->response['meta']['error'] = $error;
		return $this;
	}

	/**
	 * Json response meta data
	 * 
	 * @param string $key  
	 * @param string $value
	 * @return $this
	 */
	public function addMeta($key, $value)
	{
		$this->response['meta'][$key] = $value;
		return $this;
	}

	/**
	 * @param mixed[array|string] $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->response['data'] = $data;
		$this->response['meta']['count'] = count($data);
		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return json_encode($this->response, JSON_PRETTY_PRINT);
	}

}