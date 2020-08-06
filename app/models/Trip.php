<?php

namespace App\Models;
use System\Models\Model;

class Trip extends Model {

	public function booking()
	{
		return $this->has('booking');
	}
	
}