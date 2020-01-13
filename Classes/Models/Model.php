<?php

namespace Palmeida\Geoip\Models;

use MySQLi;

/**
 * Base model for extension
 *
 * @author Paulo Almeida <palmeida@growin.com>
 */
class Model
{
	/** A mysqli connection resource */
	protected $__connection;

	/** The table name that the model will refer to */
	protected $__tablename;

	/** A boolean tha indicates if the model is loaded*/
	protected $__loaded;

	/** The data retrieved from database for this model */
	protected $__data;


	/**
	 * Constructor
	 */
	public function __construct($tablename)
	{
		$this->__connection = new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connect failed: %s\n". $$this->connection->error);
		$this->__tablename = $tablename;
		$this->__loaded = false;
		$this->__data = [];
	}


	/**
	 * Load a record data to the model instance
	 *
	 * @param int $id - Unique identifier for the record on database
	 */
	public function load($id)
	{
		$query = "SELECT * FROM " . $this->__tablename . " where id=$id";

		$result = $this->__connection->query($query);

		$this->__loaded = true;
		$this->__data = $result;
	}


	/**
	 * Checks if an instance has data refering to a database record
	 *
	 * @return Bool
	 */
	public function isLoaded() {
		return $this->__loaded;
	}


	/**
	 * Gets the data attribute of an instance
	 *
	 * @return Array|Object
	 */
	public function data() {
		return $this->__data;
	}


	/**
	 * Gets the connection resource for database interactions
	 * @return resource
	 */
	public function conn() {
		return $this->__connection;
	}

}