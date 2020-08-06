<?php

namespace System\Facades;

abstract class Facade {

	/**
	 * @var System\Application
	 */
	protected static $app;

	/**
	 * @param  string $name
	 * @param  mixed[] $args
	 */
	public static function __callStatic($name, $args)
	{
		return static::getFacadeAccessor()->$name(...$args);
	}

	protected static function getAccessor() {}

	/**
	 * @return object
	 */
	private static function getFacadeAccessor() 
	{
		return static::$app->resolveFacadeRoot(static::getAccessor());
	}

	/**
	 * @return object
	 */
	public static function getInstance()
	{
		return static::getFacadeAccessor();
	}

	/**
	 * @param Core\App
	 */
	public static function setApplication($app)
	{
		static::$app = $app;
	}

}