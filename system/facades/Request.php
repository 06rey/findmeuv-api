<?php

namespace System\Facades;

class Request extends Facade {

	protected static function getAccessor() 
	{
		return 'request';
	}

}