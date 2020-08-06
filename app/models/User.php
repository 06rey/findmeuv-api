<?php

namespace App\Models;
use System\Models\Model;

class User extends Model {

	public function employee()
	{
		return $this->has('employee');
	}

}