<?php

namespace System;

abstract class ServiceProvider {

	/**
	 * @var Core\Application
	 */
	protected $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	public function register() {}

	public function boot() {}

}