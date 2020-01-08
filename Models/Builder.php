<?php

/**
 *
 */
final class Builder extends Model
{
	private $identifier;

	public function __construct()
	{
		$this->__tablename = "GeoIpCountryWhois";
		parent::__construct();
	}

	/**
	 * Creates Table
	 */
	public function createTable()
	{
		echo $query = "CREATE TABLE " . $this->__tablename . " (
			id INT(11) NOT NULL AUTO_INCREMENT,
			ip_str_from VARCHAR(15) NOT NULL,
			ip_str_to VARCHAR(15) NOT NULL,
			ip_long_from bigInt(15) NOT NULL,
			ip_long_to VARCHAR(15) NOT NULL,
			country_code VARCHAR(5) NOT NULL,
			country_name VARCHAR(100) NOT NULL,
			PRIMARY KEY (`id`)
		)";
		$res = $this->__connection->query($query);
	}

	/**
	 * Verify is table has records
	 */
	public function isTablePopulated()
	{
		$query = "SELECT count(id) FROM " . $this->__tablename;
		$result = $this->__connection->query($query);
		// var_dump($this->__connection, $result);
	}

	/**
	 *
	 */
	public function importData()
	{
		// 4 steps: download, unzip, readfile, db insert
		$this->identifier = uniqid();

		$this->downloadFile($this->identifier);
		$this->unzipFiles($this->identifier);
		$data = $this->readFiles($this->identifier);
		$this->seedTable($data);
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
		}
		return $filescontent;
	}


	/**
	 *
	 */
	public function seedTable($data)
	{
		$query_start = "INSERT INTO " . $this->__tablename . "(ip_str_from, ip_str_to, ip_long_from, ip_long_to, country_code, country_name) VALUES ";
		$query_lines = [];
		foreach ($data as $record) {
			$parts = explode(",", $record);
			$query_line = "(" .
				$parts[0] . "," .
				$parts[1] . "," .
				$parts[2] . "," .
				$parts[3] . "," .
				$parts[4] . "," .
				$parts[5] . ")";

			$this->__connection->query($query_start . $query_line);
		}
		// $this->__connection->query($query_start . implode(",",$query_lines));
		// var_dump($this->__connection);
	}
}