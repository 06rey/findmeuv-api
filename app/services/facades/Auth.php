<?php

namespace App\Services\Facades;

use System\Facades\Facade;

class Auth extends Facade {

	protected static function getAccessor() 
	{
		return 'auth';
	}

}