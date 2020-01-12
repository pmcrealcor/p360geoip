<?php

	define('EXEC_START', microtime(true));

	define("DB_HOST", "192.168.1.69");
	define("DB_USER", "root");
	define("DB_PASS", "creazy.1");
	define("DB_NAME", "p360geoip");
	define("DB_TABLE", "GeoIpCountryWhois");

	define("TMP_DIR", "./tmp/");

	define("SOURCE_FILE", "https://php-dev-task.s3-eu-west-1.amazonaws.com/GeoIPCountryCSV.zip");