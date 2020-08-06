<?php

/**
 * @author Junrey Algario <algario.devs@gmail.com> 2020.7.30
 */

namespace System\Events;

use Exception;
use BadMethodCallException;

class EventManager {

	/**
	 * @var array
	 */
	private $eventHandlers;

	/**
	 * @param  string $handlerName
	 */
	public function create($handlerName)
	{
		$this->verifyHandlerName($handlerName);
		$this->eventHandlers[$handlerName] = new EventHandler($handlerName);
	}

	/**
	 * @param  string $name
	 * @param  System\Events\EventInterface $name
	 */
	public function register($name, EventInterface $handler)
	{
		$this->verifyHandlerName($handlerName);
		$this->eventHandlers[$handlerName] = $handler;
	}

	/**
	 * Override existing event handler
	 * 
	 * @param  string $name
	 * @param EventInterface $handler
	 */
	public function override($name, EventInterface $handler = null)
	{
		if (is_null($handler)) {
			$handler = new EventHandler($name);
		}	
		$this->eventHandlers[$name] = $handler;
	}

	/** 
	 * @param string $name
	 * @return System\Events\EventHandler
	 */
	public function getHandler($name)
	{
		if(! isset($this->eventHandlers[$name])) {
			throw new Exception("Event handler '{$name}' does not exists", 1);
		}

		return $this->eventHandlers[$name];
	}

	/**
	 * @param  string $handlerName
	 */
	public function unregister($handlerName)
	{
		$this->eventHandlers[$handlerName]->onUnregister();
		unset($this->eventHandlers[$handlerName]);
	}

	public function __call($handlerName, $args)
	{
		if (isset($this->eventHandlers[$handlerName])) {
			return $this->eventHandlers[$handlerName];
		}

		throw new BadMethodCallException("Event handler '{$handlerName}' does not exists.", 1);
	}

	/**
	 * Verify handler name if already exists
	 * 
	 * @param  string $handlerName
	 */
	private function verifyHandlerName($handlerName)
	{
		if (isset($this->eventHandlers[$handlerName]) 
					&& !is_null($this->eventHandlers[$handlerName])) {

			throw new Exception("Event handler '{$handlerName}' already exists. Use override() method instead to override event handler", 1);
		}
	}

}