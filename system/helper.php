<?php

use System\Facades\App;
use System\Facades\Request;
use System\Facades\Session;
use System\Facades\Response;
use System\Facades\Util;

if (! function_exists('util'))
{
	/**
	 * @return System\Util
	 */
	function util()
	{
		return Util::getInstance();
	}
}

if (! function_exists('to_snake_case')) 
{
	/**
	 * @param  string $str         
	 * @return string               
	 */
	function to_snake_case($str) 
	{
	 	return util()->to_snake_case($str);
	}

}

if (! function_exists('to_camel_case')) 
{
	/**
	 * @param  string $str         
	 * @param  boolean $capFirstChar
	 * @return string               
	 */
	function to_camel_case($str, $capFirstChar = false) {
	  return util()->to_camel_case($str, $capFirstChar = false);
	}

}

if (! function_exists('rm_space')) 
{
	/**
	 * Remove white space in any part of a string
	 * 
	 * @param  string $str
	 * @return string     
	 */
	function rm_space($str = null) 
	{
	  return util()->rm_space($str);
	}

}

if (! function_exists('request')) {

	/**
	 * @return System\Request\Request
	 */
	function request() {
		return Request::getInstance();
	}

}

if (! function_exists('session')) {

	/**
	 * @return System\Session
	 */
	function session() {
		return Session::getInstance();
	}

}

if (! function_exists('app')) {

	/**
	 * @return System\Request\Request
	 */
	function app() {
		return App::getInstance();
	}

}

if (! function_exists('response')) {

	/**
	 * @return System\Response
	 */
	function response() {
		return Response::getInstance();
	}

}

if (! function_exists('model')) {

	/**
	 * @param string
	 */
	function model($model) {
		return app()->makeModel($model);
	}

}

if (! function_exists('view')) {

	/**
	 * @param  array $data    
	 * @param  string $filename
	 * @return string          
	 */
	function view($data, $filename) {
		return response()->view->render($data, $filename);
	}

}

if (! function_exists('app_url')) {

	/**
	 * @param  string $url
	 * @return string     
	 */
	function app_url($url = null) {
		return app()->config['app']['app_url'].'/'. str_replace('\\', '/', $url);
	}

}

if (! function_exists('app_path')) {

	/**
	 * @param  string $file
	 * @return string     
	 */
	function app_path($file = null) {
		return app()->config['app']['base_path'] . '\\'. str_replace('/', '\\', $file);
	}

}

if (! function_exists('redirect')) {

	/**
	 * @param  string $path
	 */
	function redirect($path = null) {
		Route::redirectTo($path);
	}

}







/**
 * Development helper methods
 */

if (! function_exists('e')) {

	function e($thing) {
		echo $thing;
		br();
	}

}

if (! function_exists('pr')) {

	function pr($thing, $desc = null) {
		pre();
		echo strtoupper($desc)."</b>";
		print_r($thing);
		hr();
	}

}

if (! function_exists('hr')) {

	function hr() {
		echo '<br><br><hr><br><br>';
	}

}

if (! function_exists('br')) {

	function br() {
		echo '<br />';
	}

}

if (! function_exists('pre')) {

	function pre() {
		echo '<pre />';
	}

}

if (! function_exists('dd')) {

	function dd($var) {
		echo '<pre />';
		echo '<hr />';
		var_dump($var);
		echo '<hr />';
	}
	
}
