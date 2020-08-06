<?php

namespace System\Db;
use PDO;
use PDOException;

class PdoDriver {

	public function __construct() {}

	public static function create($config, $option, $attribute)
	{
		$dsn = "mysql:host=$config[host];dbname=$config[db_name]";
		$user = $config['user'];
		$password = $config['password'];

		try {
			$pdo = new PDO($dsn, $user, $password, $option);
			$pdo->setAttribute($attribute[0], $attribute[1]);
			return $pdo;
		}catch(PDOException $e){
			return false;
		}
	}

}