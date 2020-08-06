<?php

namespace App\Models;
use System\Models\Model;

class Booking extends Model {

	public function seatReservation()
	{
		return $this->has('seat_reservation');
	}
	
}