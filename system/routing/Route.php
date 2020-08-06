<?php

namespace System\Routing;

use System\Application;
use System\Events\EventInterface;
use System\Request;

class Route {

	/**
	 * @var System\Application
	 */
	private $app;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	public $uri;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var string
	 */
	private $action;

	/**
	 * @var System\Request
	 */
	private $request;

	/**
	 * @var System\Events\EventInterface;
	 */
	private $event;

	/**
	 * @var string
	 */
	private $middleware;

	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * @param string $uri
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $uri
	 */
	public function setUri($uri)
	{
		$this->uri = $uri;
		return $this;
	}

	/**
	 * @param string $method
	 */
	public function setMethod($method)
	{
		$this->method = $method;
		return $this;
	}

	/**
	 * @param string $uri
	 */
	public function setAction($action)
	{
		$this->action = $action;
		return $this;
	}

	/**
	 * @param System\Request $request
	 */
	public function setRequest(Request $request)
	{
		$this->request = $request;
		return $this;
	}

	/**
	 * @param string $uri
	 */
	public function setEvent(EventInterface $event)
	{
		$this->event = $event;
		return $this;
	}

	/**
	 * @param string $middleware
	 */
	public function setMiddleware($middleware)
	{
		$this->middleware = $middleware;
		return $this;
	}

	/**
	 * @return string $method
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * @return string $method
	 */
	public function getUri()
	{
		return $this->method;
	}

	/**
	 * @return  string <Response content>
	 */
	public function run()
	{
		if (is_callable($this->action)) {
			return call_user_func($this->action, $this->request);
		}
		return $this->resolveController();
	}

	private function resolveController()
	{
		$controller = new $this->action['controller'];
		$controllerMethod = $this->action['method'];

		if(! is_null($middlewares = $controller->getMiddlewares())) {

			foreach ($middlewares as $name => $options) {
				if (is_numeric(array_search($controllerMethod, $options['except']))) {
					continue;
				}else{
					$this->app->makeMiddleware($name)->handle($this->request, ...$options['args']);
				}
			}
		}
		
		$eventName = str_replace('/', '.', trim($this->uri, '/'));
		$this->event->dispatch($eventName, $this->request);

		return $controller->$controllerMethod($this->request);
	}

}