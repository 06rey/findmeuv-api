<?php

namespace System\Db;

class Database extends Connection {

	use QueryBuilder;

  private static $rowCount = 0;

  public function __construct($config, $appEvent)
  {
    $this->event = $appEvent;
    $this->createConnection('pdo', $config);
  }

	/**
   * Get result of sql query
   * 
   * @return mixed[array|object]
   */
  public function get() 
  {
  	$sql = $this->getSelectQuery();
    $stmt = $this->prepareAndExecute($sql);
    $res = $stmt->fetchAll($this->fetchMode);
    $this->clearSelectQuery();
    return $res;
  }

  /**
   * Get result of sql query
   * 
   * @return mixed[array|object]
   */
  public function fetch() 
  {
    $sql = $this->getSelectQuery();
    $stmt = $this->prepareAndExecute($sql);
    $res = $stmt->fetch($this->fetchMode);
    $this->clearSelectQuery();
    return $res;
  }

  /**
   * @param  string $table
   * @return $this       
   */
  public function update($table) 
  {
    $sql = "UPDATE {$table}".$this->getUpdateQuery();
    $stmt = $this->prepareAndExecute($sql);
    return $stmt->rowCount();
  }

  private function prepareAndExecute($sql) 
  {

    $stmt = $this->conn->prepare($sql);
    foreach ($this->param as $param => &$value) {
      $stmt->bindParam($param, $value);
    }
    $stmt->execute();
    self::$rowCount = $stmt->rowCount();
    return $stmt;
  }

  /**
   * Get last query result count
   * 
   * @return int
   */
  public function getRowCount()
  {
    return self::$rowCount;
  }

  /**
   * @return object Database connection
   */
  public function connection() 
  {
  	return $this->conn;
  }
	
}