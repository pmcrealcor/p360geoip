<?php

namespace Palmeida\Geoip\Commands;

use Palmeida\Geoip\Models\Model;
use ZipArchive;

/**
 *
 */
final class Command
{

	private $identifier;
	private $model;
	private $commands = [
		"help" => "\tLists possible commands (this list)",
		"tableExists" => "Checks if configured table already exists",
		"createTable" => "Create the database table to store ip locations info",
		"tableHasData" => "Checks if configured table has data",
		"downloadData" => "Download zip file to local storage",
		"importData" => "Imports data from a downloaded zip file",
		"install" => "Makes all checks and operations",
	];


	public function __construct()
	{
		$this->model = new Model(DB_TABLE);
	}


	public function execute($args)
	{
		$command = $args[1] ?? 'help';

		if (isset($this->commands[$command])) {
			$method = "command_" . $command;
			$this->$method();
		} else {
			echo "> php command.php [commands]\n\n";
			echo "COMMANDS:\n";
			foreach ($this->commands as $commandname => $description) {
				echo "\t" . $commandname . " \t\t" . $description . "\n";
			}
		}
	}

	private function command_tableExists()
	{
		if($this->tableExists()) {
			echo "Yes, table exists!\n";
		} else {
			echo "No, table doesn't exist.\n";
		}
	}

	private function command_tablehasdata()
	{
		if($this->tableExists()) {
			if($this->isTablePopulated()) {
				echo "Yes, table has records!\n";
			} else {
				echo "No, table has no records.\n";
			}
		} else {
			echo "Table doesn't exist.\n";
		}
	}

	private function command_createTable()
	{
		if(!$this->tableExists()) {
			$this->createTable();
			echo "Table «" . DB_TABLE . "» created successfuly!\n";
		} else {
			echo "Table «" . DB_TABLE . "» already exists!\n";
		}
	}

	private function command_downloadData()
	{
		$this->identifier = uniqid();
		$this->downloadFile($this->identifier);
		echo "File downloaded to local /tmp folder!\n";
	}

	private function command_importData()
	{
		$this->identifier = uniqid();
		$this->downloadFile($this->identifier);
		echo "File downloaded to local /tmp folder!\n";
		$this->unzipFiles($this->identifier);
		if(!$this->isTablePopulated()) {
			$data = $this->readFiles($this->identifier);
			$this->seedTable($data);
			echo "Data imported to database table " . DB_NAME . "." . DB_TABLE . "!\n";
		} else {
			echo "Data ignored - Table «" . DB_TABLE . "» already has records.\n";
		}
	}

	private function command_install()
	{
		if(!$this->tableExists()) {
			$this->createTable();
			echo "Table «" . DB_TABLE . "» created successfuly!\n";
		} else {
			echo "Table «" . DB_TABLE . "» already exists.\n";
		}

		if(!$this->isTablePopulated()) {
			$this->identifier = uniqid();
			$this->downloadFile($this->identifier);
			echo "File downloaded to local /tmp folder!\n";
			$this->unzipFiles($this->identifier);
			$data = $this->readFiles($this->identifier);
			$this->seedTable($data);
			echo "Data imported to database table " . DB_NAME . "." . DB_TABLE . "!\n";
		} else {
			echo "Data ignored - Table «" . DB_TABLE . "» already has records.\n";
		}

	}





		














	/**
	 * Verify is table has records
	 */
	public function tableExists()
	{
		$query = "SELECT * FROM " . DB_TABLE;
		$result = $this->model->conn()->query($query);

		if($result === false && $this->model->conn()->errno === 1146) {
			return false;
		}

		return true;
	}

	/**
	 * Verify is table has records
	 */
	public function isTablePopulated()
	{
		$query = "SELECT * FROM " . DB_TABLE;
		$result = $this->model->conn()->query($query);
		return (bool) $result->num_rows;
	}


	/**
	 * Creates Table
	 */
	public function createTable()
	{
		$query = "CREATE TABLE " . DB_TABLE . " (
			id INT(11) NOT NULL AUTO_INCREMENT,
			ip_str_from VARCHAR(15) NOT NULL,
			ip_str_to VARCHAR(15) NOT NULL,
			ip_long_from bigInt(15) NOT NULL,
			ip_long_to VARCHAR(15) NOT NULL,
			country_code VARCHAR(5) NOT NULL,
			country_name VARCHAR(100) NOT NULL,
			PRIMARY KEY (`id`)
		)";
		$res = $this->model->conn()->query($query);
	}


	/**
	 *
	 */
	public function downloadFile($identifier)
	{
		file_put_contents(TMP_DIR . $identifier . ".zip", file_get_contents(SOURCE_FILE));
	}


	/**
	 *
	 */
	public function unzipFiles($identifier)
	{
		$zip = new ZipArchive;
		
		$resource = $zip->open(TMP_DIR . $identifier . ".zip");

		if ($resource === TRUE) {
			$zip->extractTo(TMP_DIR . $identifier . "/");
  			$zip->close();
  		}

  		unlink(TMP_DIR . $identifier . ".zip");
	}


	/**
	 *
	 */
	public function readFiles($identifier)
	{
		// scan dir for csv files
		$files = scandir(TMP_DIR . $identifier . "/");
		$filescontent = [];

		foreach ($files as $file) {
			if (is_file(TMP_DIR . $identifier . "/" . $file)
				&& strpos($file, ".csv") !== FALSE) {
				$filescontent += file(TMP_DIR . $identifier . "/" . $file);
			}
			if($file != "." && $file != "..") {
		  		unlink(TMP_DIR . $identifier . "/" . $file);
			}
		}

		// delete files
		// remove folder

		return $filescontent;
	}


	/**
	 *
	 */
	public function seedTable($data)
	{
		$query_start = "INSERT INTO " . DB_TABLE . "(ip_str_from, ip_str_to, ip_long_from, ip_long_to, country_code, country_name) VALUES ";
		$query_lines = [];
		foreach ($data as $record) {
			$record = str_replace('","', '";"', $record);
			$parts = explode(';', $record);
			$query_lines[] = "(" .
				trim($parts[0]) . "," .
				trim($parts[1]) . "," .
				trim($parts[2]) . "," .
				trim($parts[3]) . "," .
				trim($parts[4]) . "," .
				trim($parts[5]) . ")";

			// lets make 1000 lines blocks, because is far better than individual
			// and lets us avoid the max_
			if (count($query_lines) == 1000) {
				$query = $query_start . implode(",", $query_lines);
				$this->model->conn()->query($query);
				$query_lines = [];
			}
		}


		if (count($query_lines) > 0) {
			$this->model->conn()->query($query_start . implode(",",$query_lines));
			$query_lines = [];
		}

	}

}