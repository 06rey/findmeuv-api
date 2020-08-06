<?php

namespace System;

use System\Events\EventManager;
use System\db\Database;
use System\Routing\Router;
use System\Response\HttpResponse;
use System\Response\Json;

class SystemServiceProvider extends ServiceProvider {

	/**
	 * Register application core services
	 * 
	 * @return void
	 */
	public function register()
	{ 
		$this->request();
		$this->session();
		$this->database();
		$this->response();
		$this->logger();
		$this->util();
		$this->router();
	}

	/**
	 * Register System\Request\Request
	 * 
	 * @return void
	 */
	private function request()
	{
		$this->app->bind('request',  function(){
			return new Request;
		}, true);
	}

	/**
	 * Register System\Session
	 * 
	 * @return void
	 */
	private function session()
	{
		$this->app->bind('session',  function(){
			return new Session(
											$this->app->config['app']['app_name'],
											$this->app->path['sessions']
										);
		}, true);
	}

	/**
	 * Register System\Router
	 * 
	 * @return void
	 */
	private function router()
	{
		$app = $this->app;
		$controller = $this->app->dir['controllers'];
		$event = $this->app->event;

		$this->app->bind('route',  function() use ($app, $event, $controller) {
			return new Router($app, $event, $controller);
		});
	}

	/**
	 * Register System\Database\DB
	 * 
	 * @return void
	 */
	private function database()
	{
		$config = $this->app->config['app']['mysql'];
		$appEvent = $this->app->event->getHandler('app');

		$this->app->bind('database',  function() use ($config, $appEvent) {
			return new Database($config, $appEvent);
		});
	}

	/**
	 * Register System\Response
	 * 
	 * @return void
	 */
	private function response()
	{
		$this->app->bind('response',  function() {
			return new HttpResponse(new Json);
		}, true);
	}

	/**
	 * Register System\Util
	 * 
	 * @return void
	 */
	private function util()
	{
		$this->app->bind('util',  function(){
			return new Util;
		});
	}

	/**
	 * Register System\Log
	 * 
	 * @return void
	 */
	private function logger()
	{
		$path = $this->app->path['logs'];
		$this->app->bind('log',  function() use ($path) {
			return new Log($path);
		});
	}

}