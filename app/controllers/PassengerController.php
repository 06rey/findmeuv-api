<?php

namespace App\Controllers;

use System\Controller;
use Event;
use Auth;
use Session;

class PassengerController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
		// $this->middleware('allow', ['role'=>'passenger']);
	}

	public function index()
	{
		// $user = model('passenger')->find(1)->result();
		return response()->json(['user'=>Auth::user()]);
	}

}