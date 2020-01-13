<?php

	/**
	 * Configuration for this app
	 *
	 * @author Paulo Almeida <palmeida@growin.com>
	 */

	define('EXEC_START', microtime(true));
	define('INC_EXEC_TIME', true);
	
	define('ENVIRONMENT', 'local');

	define("DB_HOST", "192.168.19.221");
	define("DB_USER", "root");
	define("DB_PASS", "creazy.1");
	define("DB_NAME", "p360geoip");
	define("DB_TABLE", "GeoIpCountryWhois");

	define("TMP_DIR", "./tmp/");

	define("SOURCE_FILE", "https://php-dev-task.s3-eu-west-1.amazonaws.com/GeoIPCountryCSV.zip");