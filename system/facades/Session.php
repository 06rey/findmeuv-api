<?php

namespace System\Facades;

class Session extends Facade {

	protected static function getAccessor() 
	{
		return 'session';
	}

}