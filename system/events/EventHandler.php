<?php

namespace System\Events;

class EventHandler implements EventInterface {

	/**
	 * @var array
	 */
	private $events;

	/**
	 * @var string
	 */
	private $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * @param  string $name    
	 * @param  closure $callback
	 */
	public function listen($name, $callback)
	{
		$this->events[$name][] = $callback;
	}
	
	/**
	 * @param  string $name
	 * @param  mixed[] $args
	 */
	public function dispatch($name, $args = null)
	{ 
		if ( !isset($this->events[$name]) || is_null($this->events[$name]) ) {
			return false;
		}	

		foreach ($this->events[$name] as $event => $callback) {
      if($args && is_array($args)) {
        $result = call_user_func_array($callback, $args);
      }
      elseif ($args && !is_array($args)) {
        $result = call_user_func($callback, $args);
      }
      else {
        $result = call_user_func($callback);
      }
    }

    return $result;
	}

	/**
	 * @param  string  $name
	 * @return boolean     
	 */
	public function exists($name)
	{
		if (isset($this->events[$name])) {
			return true;
		}
		return false;
	}

	/**
	 * Triggered when instance is unregister from container
	 */
	public function onUnregister()
	{
		e('Unregistering '.$this->name. ' event handler.');
	}

}