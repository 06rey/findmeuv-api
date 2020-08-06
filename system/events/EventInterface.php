<?php

namespace System\Events;

interface EventInterface {

	/**
	 * @param  string $name    
	 * @param  closure $callback
	 */
	public function listen($name, $callback);

	/**
	 * @param  string $name
	 * @param  mixed[] $args
	 */
	public function dispatch($name, $args = null);
	
}