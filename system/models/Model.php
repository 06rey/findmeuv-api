<?php

namespace System\Models;

use System\Facades\DB;
use System\Facades\App;

abstract class Model {

	/**
	 * Single model result
	 * 
	 * @var array
	 */
	private $result;

	/**
	 * Relation mapping reults
	 * 
	 * @var array
	 */
	protected static $relationResults = array();

	/**
	 *Relation root
	 * 
	 * @var string
	 */
	protected static $relationRoot;

	
	public function __construct()
	{	
		if(! isset($this->table)) {
			$tableName = get_called_class();
			$this->table = ltrim( strrchr(to_snake_case($tableName), '\\'), '\\' );
		}

		if(! isset($this->primaryKey)) {
			$this->primaryKey = $this->table.'_id';
		}
	}

	/**
	 * Fetch one record with one condition
	 * 
	 * @param  string $columnValue
	 * @param  string $columnName 
	 */
	public function find($columnValue, $columnName = null)
	{
		$findBy = $columnName;

		if (is_null($columnName) ){
			$findBy = $this->primaryKey;
		}

		$this->result = DB::select(...$this->column())
						->from($this->table)
						->where($findBy, '=', $columnValue)
						->get();

		$this->setRelationResults();
		return $this;
	}

	/**
	 * Fetch all records 
	 */
	public function all()
	{
		$this->result = DB::select(...$this->column())
						->from($this->table)
						->get();

		$this->setRelationResults();
		return $this;
	}

	/** 
	 * Fetch one record with multiple conditions
	 * 
	 * @param array $where
	 */
	public function get($where)
	{
		$this->result = DB::select(...$this->column())
						->from($this->table)
						->where($where)
						->get();

		$this->setRelationResults();
		return $this;
	}

	/** 
	 * Fetch one record with multiple conditions
	 * 
	 * @param array $where
	 */
	public function getOne($where)
	{
		$this->result = DB::select(...$this->column())
						->from($this->table)
						->where($where)
						->get();

		$this->setRelationResults();
		return $this;
	}

	/**
	 * @param  array $data 
	 * @param  array $where
	 */
	public function update($data, $where)
	{
		DB::set($data)
			->where($where)
			->update($this->table);
		return DB::getRowCount() > 0 ? true : false;
	}

	/**
	 * @param  string $tableName 
	 * @param  mixed[string|null] $columnName
	 * @return  System|model [<description>]
	 */
	public function has($modelName, $columnName = null)
	{	
		$model = App::makeModel($modelName);

		$primaryKey = $this->primaryKey;
		$columnName = $columnName ?? $this->primaryKey;

		foreach (self::$relationResults[$this->table] as $key => $value) {
			$value->{$modelName} = $model->get([$columnName => $value->$primaryKey])->result;
		}
		
		return $model;
	}

	/**
	 * Merge model single results
	 */
	private function setRelationResults()
	{
		if (! isset(self::$relationResults[$this->table])) {
			self::$relationResults[$this->table] = array();

			if (is_null(self::$relationRoot)) {
				self::$relationRoot = $this->table;
			}
		}

		foreach ($this->result as $key => $value) {
			array_push(self::$relationResults[$this->table], $value);
		}
	}

	/**
	 * Return and terminate shared result
	 * 
	 * @return array
	 */
	public function result()
	{
		$resultCopy = self::$relationResults[self::$relationRoot];
		self::$relationResults = null;
		self::$relationRoot = null;
		return $resultCopy;
	}

	/**
	 * @return array
	 */
	protected function column()
	{
		return [' *'];
	}

}