<?php

namespace System;

use System\Events\EventManager;
use System\Facades\Facade;

/**
 * @author Junrey Algario algario.devs@gmail.com 2020.7.23
 */

class Application extends Container {

	/** 
	 * @var array
	 */
	public $path = [
		'config' => APP_PATH.DS.'app'.DS.'config',
		'sessions' => APP_PATH.DS.'storage'.DS.'sessions',
		'providers' => APP_PATH.DS.'app'.DS.'providers',
		'routes' => APP_PATH.DS.'app'.DS.'routes.php',
		'models' => APP_PATH.DS.'app'.DS.'models',
		'views' => APP_PATH.DS.'app'.DS.'views'.DS,
		'middlewares' => APP_PATH.DS.'app'.DS.'middlewares',
		'logs' => APP_PATH.DS.'storage'.DS.'logs'.DS,
	];

	/**
	 * @var array
	 */
	public $dir = [
		'controllers' => 'App\\Controllers\\',
		'models' => 'App\\Models\\'
	];

	/** 
	 * @var array
	 */
	public $config;

	public function __construct()
	{
		// System layer
		$this->loadConfiguration();
		$this->registerEventHandlers();
		$this->facadeApplication();
		$this->registerProviders(new SystemServiceProvider($this));
		$this->registerModels();
		$this->registerAliases();

		// App layer
		$this->registerAppServiceProvider();
		$this->registermiddlewares();
		$this->registerRoutes();
	}

	/**
	 * @return void
	 */
	private function loadConfiguration()
	{
		$this->config['app'] = require_once $this->path['config'].DS.'app.php';
		$this->config['validation'] = require_once $this->path['config'].DS.'validation.php';
		$this->config['app']['base_path'] = APP_PATH;
	}

	private function registerEventHandlers()
	{
		$this->bind('event',  function(){
			return new EventManager;
		}, true);

		$this->event->create('app');
		$this->event->create('route');
	}

	/**
	 * @param  System\Core\ServiceProvider $provider
	 * @return void
	 */
	private function registerProviders($provider)
	{
		$provider->register();
	}

	/**
	 * @return void
	 */
	private function facadeApplication()
	{
		$this->bind('app', function(){
			return $this;
		}, true);

		Facade::setApplication($this);
	}

	/**
	 * Register custom service providers
	 */
	private function registerAppServiceProvider()
	{
		$appServiceProvider = $this->config['app']['providers'];
		foreach ($appServiceProvider  as $serviceProvider) {
			$this->registerProviders(new $serviceProvider($this));
		}
	}

	/**
	 * Load services aliases
	 */
	private function registerAliases()
	{
		$aliases = $this->config['app']['aliases'];
		foreach ($aliases as $alias => $className) {
			class_alias($className, $alias);
		}
	}

	/**
	 * @return void
	 */
	private function registerRoutes()
	{
		require_once $this->path['routes'];
	}

	/**
	 * Register all app models
	 */
	private function  registerModels()
	{
		$models = $this->config['app']['models'];
		foreach ($models as $model) {
			$modelClass = $this->dir['models'].$this->util->to_camel_case($model, true);
			$this->bindModel($model, $modelClass);
		}
	}

	/**
	 * Register middlewares
	 */
	private function  registermiddlewares()
	{
		$middlewares = $this->config['app']['middlewares'];
		foreach ($middlewares as $name => $middleware) {
			$this->bindMiddleware($name, $middleware);
		}
	}

	/**
	 * @return System\Response\HttpResponse
	 */
	public function handleRequest()
	{
		$this->logRequest();

		$this->event->app()->listen('abort', function($status) {
			$this->response->setContent(response()
									->setStatus($status)
									->json()
									->setError(true)
								)->send();
		});

		$route = $this->resolveRoute($this->request, $this->event->getHandler('app'));
		$result = $route->run();

		$this->logResponse($result);

		return $this->response->setContent($result);
	}

	private function logRequest()
	{
		$this->log->request('log.txt');
		$this->log->input('input.txt');
	}

	private function logResponse($result)
	{
		$this->log->response($result, 'response.txt');
		$this->log->data('ROUTE', str_replace('/', '.', $this->request->header('PATH_INFO')));
		$this->log->write();
	}

}