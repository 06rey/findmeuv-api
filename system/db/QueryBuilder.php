<?php

namespace System\Db;

trait QueryBuilder {

  /** @var string */
  private $select;

  /** @var string */
  private $from;

  /** @var string */
  private $join;

  /** @var string */
  private $where;

   /** @var string */
  private $having;

  /** @var string */
  private $groupBy;

  /** @var string */
  private $orderBy;

  /** @var string */
  private $limit;

  /** @var string */
  private $set;

   /** 
   * SQL parameter bindings
   * 
   * @var array
   */
  protected $param = [];

  /**
   * SELECT QUERY
   */

  /** 
   * @param  array
   * @return $this        
   */
  public function select(...$column) 
  {
    foreach ($column as $name) {
      $this->select .= (is_null($this->select) ? "SELECT ":", "). $name;
    }
    return $this;
  }

  /**
   * @param  array Columns to be selected
   * @return $this        
   */
  public function from(...$table) 
  {
    foreach ($table as $name) {
      $this->from .= (is_null($this->from) ? " FROM ":", " ). $name;
    }
    return $this;
  }

  /**
   * @param  string $table   
   * @param  string $onCond  
   * @param  string $joinType
   * @return $this          
   */
  public function join($table, $onCond, $joinType = null) 
  {
    if (! is_null($joinType)) {
      $joinType = " ".strtoupper($joinType);
    } 
    $this->join .= "{$joinType} JOIN {$table} ON {$onCond}";
    return $this;
  }

  /**
   * @param  array Columns to be selected
   * @return $this        
   */
  public function where($column, $opr = null, $condition = null) 
  {
    $this->parseWhere($column, $opr, $condition, 'AND');
    return $this;
  }

  /**
   * @param  array Columns to be selected
   * @return $this        
   */
  public function orWhere($column, $opr = null, $condition = null) 
  {
    $this->parseWhere($column, $opr, $condition, 'OR');
    return $this;
  }

  /**
   * @param  string $column   
   * @param  string $opr      
   * @param  mixed[numeric|string] $condition
   * @param  string $type     
   */
  private function parseWhere($column, $opr = null, $condition = null, $type) 
  {
    if (is_array($column)) {
      foreach ($column as $key => $cond) {
        $this->addWhere($key, '=', $cond, $type);
      }
    }else{
      $this->addWhere($column, $opr, $condition, $type);
    }
  }

  /** 
   * @param string $str 
   * @param string $opr 
   * @param mixed[numeric|string] $cond
   * @param string $type
   */
  private function addWhere($str, $opr, $cond, $type = null) 
  {
    $param = str_replace('.', '_', $str);

    if (is_null($this->where)) {
      $this->where = " WHERE {$str} $opr :{$param}";
    }else{
      $this->where .= " $type {$str} $opr :{$param}";
    }

    $this->param[":{$param}"] = $cond;
  }

  /** 
   * @param  string $column
   * @return $this     
   */
  public function groupBy(...$column) 
  {
    $group = '';
    foreach ($column as $name) {
      $group .= ($group === '' ? " GROUP BY ":", "). $name;
    }
    $this->groupBy = $group;
    return $this;
  }

  /** 
   * @param  string $column
   * @param  string $sort  
   * @return $this      
   */
  public function orderBy($column, $sort =  null) 
  {
    if (is_array($column)) {
      $order = '';
      foreach ($column as $key => $val) {
        $order .= ($order === '' ? " ORDER BY ":", " )."{$key} {$val}";
      }
    }else{
      $order = " ORDER BY {$column} {$sort}";
    }
    $this->orderBy = $order;
    return $this;
  }

  /** 
   * @param  string $limit
   * @return $this       
   */
  public function limit($limit) 
  {
    $this->limit = " LIMIT $limit";
    return $this;
  }

  /**
   * Merge sql select query
   * 
   * @return string
   */
  public function getSelectQuery() 
  {
    return ($this->select ?? 'SELECT *')."
            ".($this->from ?? $this->tableName)."
            {$this->join}
            {$this->where} 
            {$this->groupBy} 
            {$this->orderBy} 
            {$this->limit}";
  }

  /**
   * UPDATE QUERY
   */

  /**
   * @param array
   * @return $this
   */
  public function set($columns) 
  {
    $this->set = '';
    foreach ($columns as $key => $val) {
      $this->set .= $this->set === '' ? ' SET' : ','; 
      $this->set .= " {$key} = :{$key}";
      $this->param[":{$key}"] = $val;
    }
    return $this;
  }

  /**
   * Merge update query string
   * 
   * @return string
   */
  protected function getUpdateQuery() 
  {
    return  "{$this->set} 
             {$this->where}";
  }


  /**
   * Clear sql select query string
   *
   * @return void
   */
  protected function clearSelectQuery() 
  {
    $this->from = null;
    $this->join = null;
    $this->where = null;
    $this->groupBy = null;
    $this->orderBy = null;
    $this->limit = null;
    $this->param = [];
  }

  /**
   * Clear update query string
   *
   * @return void
   */
  protected function clearUpdateQuery() 
  {
    $this->set = null;
    $this->where = null;
    $this->param = [];
  }

}