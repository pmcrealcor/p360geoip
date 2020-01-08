<?php

class Model
{
	protected $__connection;
	protected $__tablename;
	protected $__loaded;
	protected $__data;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->__connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connect failed: %s\n". $$this->connection->error);
		$this->__loaded = false;
		$this->__data = [];
	}

	public function load($id)
	{
		$query = "SELECT * FROM " . $this->__tablename . " where id=$id";

		$result = $this->__connection->query($query);

		$this->__loaded = true;
		$this->__data = $result;
	}


	public function isLoaded() {
		return $this->__loaded;
	}

	public function data() {
		return $this->__data;
	}

}