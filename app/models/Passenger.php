<?php

namespace App\Models;
use System\Models\Model;

class Passenger extends Model {

	/**
	 * Overrided from Model
	 * 
	 * @return array
	 */
	protected function column()
	{
		return [ 'passenger_id', 'f_name', 'l_name', 'gender', 'contact', 'email' ];
	}
	
}