<?php

namespace System\Response;

class HttpResponse {

	/**
	 * @var string
	 */
	private $content;

	/**
	 * @var System\Response\Json
	 */
	public $json;

	public function __construct(Json $json)
	{
		$this->json = $json;
	}

	/**
	 * @param string $header
	 * @return $this
	 */
	public function addHeader($header)
	{
		header($header);
		return $this;
	}

	/**
	 * @param string $statusCode
	 * @return $this
	 */
	public function setStatus($statusCode)
	{
		http_response_code($statusCode);
		return $this;
	}

	/**
	 * @param string $content
	 * @return $this
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * @param  string $data
	 * @return string      
	 */
	public function json($data = null)
	{

		$this->json->setCode(http_response_code());
		$this->addHeader('Content-Type: application/json; charset=UTF-8');
		if(is_null($data)) {
			return $this->json;
		}

		return $this->json->setData($data)
											->__toString();
	}

	/**
	 * Flush response
	 * 
	 * @return void
	 */
	public function send()
	{
		echo $this->content;
		die();
	}

}