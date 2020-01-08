# p360GeoIP


## Installation

For this application, PHP 7  is needed, and Mysqli and Zip extensions must be loaded.
// MySql configuration max_allowed_packet must be set to a higher value, such as 15000000
For development and testing purposes, install PHPUnit and all dependencies needed. Run

> composer install



## Configuration

File env.php defines all configurations needed:

	DB_HOST -> mysql hostname
	DB_USER -> mysql username
	DB_PASS -> mysql password
	DB_NAME -> mysql database name
	TMP_DIR -> Temp directory, used for downloaded files
	SOURCE_FILE -> URL for the database file, if needed



## Testing

One test is included. Run

> phpunit Test



## Usage

Access the project URL, at URI /locationByIP, give an IP address param, as follows:

> http://your-domain/locationByIP?ip=12.34.56.78

At first access, if database has no records, it will automaticaly download an insert the records.
For this reason, first access may take a little longer.

The response for a request will be one of the following:

404		{"message":"Resource does not exist"}

200		{"data":{"country":"Australia","countryCode":"AU"}}