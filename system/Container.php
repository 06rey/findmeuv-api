<?php

namespace System;

abstract class Container {

	/** 
	 * @var array
	 */
	protected $services = [];

	/** 
	 * @var array
	 */
	protected $resolvedServiceInstance = [];

	/** 
	 * @var array
	 */
	protected $models;

	/** 
	 * @var array
	 */
	protected $middlewares;

	/** 
	 * @var array
	 */
	protected $routeRegistry;

	/**
	 * @param  string $name    
	 * @param  mixed[closure|string] $concrete
	 * @return void
	 */
	public function bind($name, $concrete, $singleton = false)
	{
		if (! is_callable($concrete) ) {
			$concrete = $this->createClosure($concrete);
		}
		$this->services[$name] = array($concrete, $singleton);
	}

	/**
	 * @param  string $name
	 * @return object      
	 */
	public function make($name)
	{
		if ( array_key_exists($name, $this->resolvedServiceInstance) ) {
			return $this->resolvedServiceInstance[$name];
		}
		return $this->resolve($name);
	}

	/**
	 * @param  string $name
	 * @return object    
	 */
	private function resolve($name) 
	{
		$instance = call_user_func($this->services[$name][0]);

		if($this->services[$name][1]) {
			$this->resolvedServiceInstance[$name] = $instance;
		}
		return $instance;
	}

	/**
	 * @param  string $concrete
	 * @return closure         
	 */
	private function createClosure($concrete)
	{
		return function() use($concrete) {
			return new $concrete;
		};
	}

	/**
	 * @param  string $name
	 * @return object      
	 */
	public function resolveFacadeRoot($name)
	{
		return $this->make($name);
	}

	/**
	 * @param  string $uri      
	 * @param  closure $callback
	 */
	public function bindRoute($uri, $callback)
	{
		$this->routeRegistry[$uri] = $callback;
	}

	/**
	 * @param  string $uri      
	 */
	protected function resolveRoute($request, $abortEvent)
	{
		if (! isset( $this->routeRegistry[ $request->header('PATH_INFO') ]) ) {
			$abortEvent->dispatch('abort', 404);
		}
		return $this->routeRegistry[ $request->header('PATH_INFO') ]($request);
	}

	/**
	 * @param  string $name      
	 * @param  string $modelClass
	 */
	protected function bindModel($name, $modelClass)
	{
		$this->models[$name] = function() use ($modelClass) {
			return new $modelClass;
		};
	}

	/**
	 * @param  string $name
	 */
	public function makeModel($name)
	{
		return call_user_func($this->models[$name]);
	}

	/**
	 * @param  string $name      
	 * @param  string $middleware
	 */
	protected function bindMiddleware($name, $middleware)
	{
		$this->middlewares[$name] = function() use ($middleware) {
			return new $middleware;
		};
	}

	/**
	 * @param  string $name
	 */
	public function makeMiddleware($name)
	{
		return call_user_func($this->middlewares[$name]);
	}

	/**
	 * @param  string $name
	 */
	public function __get($name)
	{
		return $this->make($name);
	}

}