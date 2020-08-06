<?php

namespace System\Db;
use PDO;
use Response;

abstract class Connection {

	/** 
	 * @var PDO
	 */
	private static $pdoConn;

	/** 
	 * @var mixed
	 */
	protected $conn;

	/**
	 * @var const
	 */
	protected $fetchMode;

	protected $event;

	public function createConnection($name, $config) 
	{
		$name = strtolower($name);

		if ($name === 'pdo') {
			$this->conn = $this->pdoConnection($config);
		}else if ($name === 'sqlite') {
			$this->conn = $this->sqliteConnection($config);
		}

		if (!$this->conn) {
			$this->event->dispatch('abort', 500);
		}
	}

	/**
	 * PDO Connection
	 * 
	 * @param  array $config
	 */
	private function pdoConnection($config)
	{
		if (self::$pdoConn) {
			return self::$pdoConn;
		}

		$option = [
			PDO::MYSQL_ATTR_FOUND_ROWS => true
		];

		$attribute = [
			PDO::ATTR_ERRMODE, 
			PDO::ERRMODE_EXCEPTION
		];

		$this->fetchMode =  PDO::FETCH_OBJ;

		self::$pdoConn = PdoDriver::create($config, $option, $attribute);
		return self::$pdoConn;
	}

	/**
	 * Sqlite Connection
	 * 
	 * @param  array $config
	 */
	private function sqliteConnection($config)
	{
	}

}