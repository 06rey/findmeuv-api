<?php

namespace App\Models;
use System\Models\Model;

class Employee extends Model {

	public function trip()
	{
		return $this->has('trip', 'driver_id');
	}

	public function user()
	{
		return $this->has('user', 'user_id');
	}

	/**
	 * Overrided from Model
	 * 
	 * @return array
	 */
	protected function column()
	{
		return [ 'employee_id', 'f_name', 'm_name', 'l_name', 'license_no', 'contact_no', 'address', 'role', 'img_url', 'company_id', 'user_id' ];
	}

}