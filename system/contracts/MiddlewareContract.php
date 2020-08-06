<?php

namespace System\Contracts;

interface MiddlewareContract {

	/**
	 * Handle middleware business
	 *
	 * @param mixed $args
	 */
	public function handle($request, $args = null);

}