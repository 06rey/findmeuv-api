<?php

namespace System;

use System\Facades\App;

abstract class Controller {

	/**
	 * @var array
	 */
	private $middlewares;

	/**
	 * @var string
	 */
	private static $middlewareName;

	/**
	 * @var array
	 */
	private $args;

	/**
	 * @param  string $name
	 * @return $this
	 */
	protected function middleware($name, ...$args)
	{
		$this->middlewares[$name]['except'] = array();
		$this->middlewares[$name]['args'] = $args;
		self::$middlewareName = $name;
		return $this;
	}

	/**
	 * @param  array $methods
	 */
	protected function except(...$methods)
	{
		foreach ($methods as $method) {
			$this->middlewares[self::$middlewareName]['except'][] = $method;
		}
	}

	/**
	 * @return array $this->middlewares
	 */
	public function getMiddlewares()
	{
		return $this->middlewares;
	}

}