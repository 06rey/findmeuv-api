<?php

namespace System\Routing;

use System\Application;
use System\Events\EventManager;

class Router {

	/**
	 * @var System\Application;
	 */
	private $app;

	/** 
	 * @var array
	 */
	private $routes;

	/** 
	 * @var array
	 */
	private static $routeGroup = array();

	/**
	 * @var string
	 */
	private $controllerPath;

	/**
	 * @var System\Events\EventInterface
	 */
	private $events;

	/**
	 * @param string         $controllerPath
	 * @param EventInterface $event     
	 */
	public function __construct(Application $app, EventManager $event, $controllerPath)
	{
		$this->app = $app;
		$this->events = $event;
		$this->controllerPath = $controllerPath;
	}

	/**
	 * @param  string $uri    
	 * @param  mixed[closure|string] $closure
	 * @return void         
	 */
	public function get($uri, $closure)
	{
		$this->addRoute('GET', $uri, $closure);
	}

	/**
	 * @param  string $uri    
	 * @param  mixed[closure|string] $closure
	 * @return void         
	 */
	public function post($uri, $closure)
	{
		$this->addRoute('POST', $uri, $closure);
	}

	/**
	 * @param  string $uri    
	 * @param  closure $closure
	 * @return void         
	 */
	public function group($groupUri, $closure)
	{
		array_push(self::$routeGroup, $groupUri);
		$closure();
		array_pop(self::$routeGroup);
	}

	/**
	 * Register api routes
	 *
	 * * @param  closure $closure
	 */
	public function api($closure)
	{
		array_push(self::$routeGroup, '/api');
		$closure();
		array_pop(self::$routeGroup);
	}

	/**
	 * Register web routes
	 *
	 * * @param  closure $closure
	 */
	public function web($closure)
	{
		$closure();
	}

	/**
	 * @param  string $method
	 * @param  string $uri 
	 * @param  mixed[callable|string] $callback
	 * @param  string $group
	 * @return void
	 */
	private function addRoute($method, $uri, $callback)
	{
		$uri = $this->resolveUri($uri);

		if ( ! is_callable($callback) ) {
			$callback = $this->resolveCallback($callback);
		}

		$event = $this->events;

		$this->app->bindRoute($uri, function($request) use($uri, $method, $callback, $event) {
			
			if ($request->header('REQUEST_METHOD') !== $method) {
				$event->app()->dispatch('abort', 405);
			}

			return (new Route($this->app))->setUri($uri)
												->setMethod($method)
												->setAction($callback)
												->setRequest($request)
												->setEvent($event->route('route'));
		});
	}

	/**
	 * @param  string $uri
	 * @return string     
	 */
	private function resolveUri($uri){
		$groupUri = '';
		if(! is_null(self::$routeGroup)) {
			foreach (self::$routeGroup as $group) {
				$groupUri .= $group;
			}
		}
		$uri = $groupUri.$uri;
		if ($uri !== '/') {
			$uri = rtrim($uri, '/');
		}
		return $uri;
	}

	/**
	 * @param  string $callback
	 * @return closure         
	 */
	private function resolveCallback($callback)
	{
		$temp = explode('@', $callback);
		$controller = $this->controllerPath.$temp[1];
		$method = $temp[0];

		return compact('controller', 'method');
	}

	/**
	 * @param  string $url
	 */
	public function redirectTo($url)
	{
		header('Location: '.$url);
		exit();
	}

}