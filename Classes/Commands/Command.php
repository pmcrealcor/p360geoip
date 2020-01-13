<?php

namespace Palmeida\Geoip\Commands;

use Palmeida\Geoip\Models\Model;
use ZipArchive;

/**
 * Allow command line interaction
 *
 * @author Paulo Almeida <palmeida@growin.com>
 */
final class Command
{

	/** Generic model instance, for database connection */
	private $model;


	/** List of available commands */
	private $commands = [
		"help" => "\tLists possible commands (this list)",
		"tableExists" => "Checks if configured table already exists",
		"createTable" => "Create the database table to store ip locations info",
		// "cleanTable" => "Delete all records from the database table (todo)",
		"tableHasData" => "Checks if configured table has data",
		"downloadData" => "Download zip file to local storage",
		"importData" => "Imports data from a downloaded zip file",
		"install" => "Makes all checks and operations",
	];


	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->model = new Model(DB_TABLE);
	}


	/**
	 * Execute - decides the command to process, base on user input
	 * @param Array $args - Arguments passed via php_cli command line ARGV
	 */
	public function execute($args)
	{
		$command = $args[1] ?? '';

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
		echo "\t(" . (microtime(true) - EXEC_START) . " secs)\n";
	}


	/**
	 * Answers the question "Does table exists?"
	 * Answers verbosely
	 */
	private function command_tableExists()
	{
		if($this->tableExists()) {
			echo "Yes, table exists!\n";
		} else {
			echo "No, table doesn't exist.\n";
		}
	}


	/**
	 * Answers the question "Does the table hava any records?"
	 * Answers verbosely
	 */
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


	/**
	 * Try to create the table, if it doesn't already exists. Answers verbosely.
	 * Answers verbosely
	 */
	private function command_createTable()
	{
		if(!$this->tableExists()) {
			$this->createTable();
			echo "Table «" . DB_TABLE . "» created successfuly!\n";
		} else {
			echo "Table «" . DB_TABLE . "» already exists!\n";
		}
	}


	/**
	 * Downloads the data file (zip) to local temporary folder
	 * Answers verbosely
	 */
	private function command_downloadData()
	{
		$identifier = uniqid();
		$this->downloadFile($identifier);
		echo "File downloaded to local /tmp folder!\n";
	}


	/**
	 * Downloads the data file (zip) to local temporary directory,
	 * Unzip the file and scan for csv files,
	 * Gets the data from csv files and
	 * inserts all the records on database.
	 * Answers verbosely
	 */
	private function command_importData()
	{
		$identifier = uniqid();
		$this->downloadFile($identifier);
		echo "File downloaded to local /tmp folder!\n";
		$this->unzipFiles($identifier);
		if(!$this->isTablePopulated()) {
			$data = $this->readFiles($identifier);
			$this->seedTable($data);
			echo "Data imported to database table " . DB_NAME . "." . DB_TABLE . "!\n";
		} else {
			echo "Data ignored - Table «" . DB_TABLE . "» already has records.\n";
		}
	}


	/**
	 * Makes all installation checks and steps
	 * Answers verbosely
	 */
	private function command_install()
	{
		if(!$this->tableExists()) {
			$this->createTable();
			echo "Table «" . DB_TABLE . "» created successfuly!\n";
		} else {
			echo "Table «" . DB_TABLE . "» already exists.\n";
		}

		if(!$this->isTablePopulated()) {
			$identifier = uniqid();
			$this->downloadFile($identifier);
			echo "File downloaded to local /tmp folder!\n";
			$this->unzipFiles($identifier);
			$data = $this->readFiles($identifier);
			$this->seedTable($data);
			echo "Data imported to database table " . DB_NAME . "." . DB_TABLE . "!\n";
		} else {
			echo "Data ignored - Table «" . DB_TABLE . "» already has records.\n";
		}

	}


	/**
	 * Verify is table has records
	 * @return Bool
	 */
	private function tableExists()
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
	 * @return Bool
	 */
	private function isTablePopulated()
	{
		$query = "SELECT * FROM " . DB_TABLE;
		$result = $this->model->conn()->query($query);
		return (bool) $result->num_rows;
	}


	/**
	 * Creates Table
	 */
	private function createTable()
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
	 * Download de datafile to a local directory
	 * @param String $identifier - Unique string to serve as file/folder name
	 */
	private function downloadFile($identifier)
	{
		file_put_contents(TMP_DIR . $identifier . ".zip", file_get_contents(SOURCE_FILE));
	}


	/**
	 * Extracts all files in zip file to a folder with the same name
	 * and deletes de zip file
	 * @param String $identifier - Unique string to serve as file/folder name
	 */
	private function unzipFiles($identifier)
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
	 * Given a directory, scan all files and extract csv data
	 * @param String $identifier - Unique string to serve as file/folder name
	 * @return String - with all data
	 */
	private function readFiles($identifier)
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

		rmdir(TMP_DIR . $identifier . "/");

		return $filescontent;
	}


	/**
	 * Given a set of data, creates the database records
	 * @param String $data A string with all data to be inserted
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
			// and lets us avoid the max_allowed_packet directive on my.ini
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