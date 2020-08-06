<?php

namespace System;

/**
 * @author Junrey Algario algario.devs@gmail.com 2020.7.24
 */

class Log {

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $txt = '';

	private $file = null;

	private static $data = "";

	public function __construct($path)
	{
		$this->path = $path;
	}

	public function request($file)
	{
		$this->file = fopen($this->path.$file, "w");
		$this->txt .= 'COOKIE: '.json_encode($_COOKIE, JSON_PRETTY_PRINT)."\n";
		$this->txt .= 'REQUEST: '.json_encode($_SERVER, JSON_PRETTY_PRINT)."\n";
		fwrite($this->file, $this->txt."\n");
		fclose($this->file);
	}

	public function input($file)
	{
		$this->txt .= !$_POST ? "POST: No Value \n" : "POST: ". json_encode($_POST, JSON_PRETTY_PRINT)."\n";
		$this->txt .= !$_GET ? "GET: No Value \n" : "GET: ". json_encode($_GET, JSON_PRETTY_PRINT)."\n";
		$this->file = fopen($this->path.$file, "w");
		fwrite($this->file, $this->txt);
		fclose($this->file);
	}

	public function response($response, $file)
	{
		$this->file = fopen($this->path.$file, "w");
		fwrite($this->file, $response);
		fclose($this->file);
	}

	public function data($name, $value)
	{
		self::$data .= json_encode([$name=>$value], JSON_PRETTY_PRINT)."\n";
		return $this;
	}

	public function write()
	{
		$this->file = fopen($this->path.'data.txt', "w");
		fwrite($this->file, self::$data);
		fclose($this->file);
	}

}