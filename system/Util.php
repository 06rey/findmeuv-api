<?php

namespace System;

class Util {

	/**
	 * @param  string $str         
	 * @return string               
	 */
	function to_snake_case($str)
	{
	 	$snakeCase = strtolower( preg_replace_callback('/([a-z])([A-Z])/', function($a) {
	  	return $a[1] . "_" . strtolower ($a[2]); 
	 	}, $str) );

	 	return $snakeCase;
	}

	/**
	 * @param  string $str         
	 * @param  boolean $capFirstChar
	 * @return string               
	 */
	function to_camel_case($str, $capFirstChar = false) {
	  if($capFirstChar) {
	    $str[0] = strtoupper($str[0]);
	  }
	  $camelCase = preg_replace_callback('/_([a-z])/', function($a) {
	  	return strtoupper($a[1]);
	  }, $str);
	  return $camelCase;
	}

	/**
	 * Remove white space in any part of a string
	 * 
	 * @param  string $str
	 * @return string     
	 */
	function rm_space($str = null) 
	{
	  if(is_null($str)) {
	    return $str;
	  }
	  return str_replace(' ', '', $str);
	}

}