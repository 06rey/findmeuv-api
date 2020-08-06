<?php

namespace Core\Traits;
use Core\Facade\Request;
use DB;

trait InputValidator {
  
  /**
   * @var array
   */
  protected $validationMessages;

  /**
   * Store validation messages
   * @var array
   */
  public $validationErrors = [];

  /**
   * Request body
   * @var array
   */
  private $input = [];

  /**
   * Validate post form
   */
  public function validateForm($rules) {
    $this->input = Request::post();
    return $this->validateInput($rules);
  }

  /**
   * Validate url query
   */
  public function validateQuery($rules) {
    $this->input = Request::query();
    return $this->validateInput($rules);
  }

  private function validateInput($rules) {
   
    foreach ($rules as $key => $rule) {
      $rule = explode('|', $rule);

      foreach($rule as $item) {
        $ar = explode(':', $item);
        $method = str_replace('-', '_', $ar[0]);
        $this->$method($key, isset($ar[1]) ? $ar[1] : null);
      }
    }

    if (count($this->validationErrors) > 0){
      return false;
    }

    return $this->input;
  }

  /**
   * Validation methods
   */

  private function min($key, $rule = null) {
    e('key: '.$key . ' | rule: ' .$rule);
  }

  private function max($key, $rule = null) {
    if ($this->input[$key] > $rule) {
      array_push($this->validationErrors, ucfirst($key).' '.$this->errorMessages['max'].' '.str_replace('_', ' ', $rule).'.');
    }
  }

  private function match($key, $rule = null) {
    if ($this->input[$key] != $this->input[$rule]) {
      array_push($this->validationErrors, ucfirst($key).' '.$this->errorMessages['match'].' '.str_replace('_', ' ', $rule).'.');
    }
  }

  private function max_length($key, $rule = null) {
    if (strlen($this->input[$key]) > $rule) {
      array_push($this->validationErrors, ucfirst($key).' must be less than '.($rule + 1).' character(s) long.');
    }
  }

  private function required($key, $rule = null) {
    if ( str_replace(' ', '', $this->input[$key]) == '' ) {
      array_push($this->validationErrors, ucfirst($key).' '.$this->errorMessages['required']);
    }
  }

  private function unique($key, $rule = null) {
    if ( $this->input[$key] !==  $this->input[$key] ) {
      array_push($this->validationErrors, ucfirst($key).' '.$this->errorMessages['unique']);
    }
  } 

  private function exists($key, $rule = null) {
    $res = DB::select($key)
      ->from($rule)
      ->where($key, '=', $this->input[$key])
      ->get();
    if (count($res) < 1) {
      array_push($this->validationErrors, ucfirst($key).' '.$this->errorMessages['exists']);
    }
  }
  
} 