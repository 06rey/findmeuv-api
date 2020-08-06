<?php

namespace App\Controllers;

use System\Controller;
use Event;
use Auth;
use Session;

class DriverController extends Controller {

	public function __construct()
	{
		// $this->middleware('auth');
		// $this->middleware('allow', ['role'=>'driver']);
	}

	public function index($request)
	{
		$data = model('passenger')->all()->result();
		return response()->json(['user'=>$data]);
	}

}