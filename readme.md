# p360geoIP

This project is an exercise.
It's' an API with one single endpoint and works like a microservice.
The intend is to translate an IP address into a Location/Country

## Installation

For this application, PHP 7 is needed, and Mysqli and Zip extensions must be loaded.
For development and testing purposes, install PHPUnit and all dependencies needed. Just run

> composer install

Start by configuring the env.php with database credentials and main options

You can use a command line tool bundled with this project to install the database. Just run

> php command.php install

´´´Se «php command.php help» for options
This will download a data file, create a database table and import all data.


## Configuration

File env.php defines all configurations needed:

	DB_HOST -> mysql hostname
	DB_USER -> mysql username
	DB_PASS -> mysql password
	DB_NAME -> mysql database name

	DB_NAME -> mysql table name

	TMP_DIR -> Temp directory, used for downloaded files. MUST BE WRITABLE
	SOURCE_FILE -> URL for the data file



## Testing (work in progress)

One test is included. Run

> phpunit Test


## Usage

Access the project URL, at URI /locationByIP, give an IP address param, as follows:

> http://your-domain/locationByIP?ip=12.34.56.78

The response for a request will be one of the following:

404		{"message":"Resource does not exist"}

200		{"data":{"country":"Australia","countryCode":"AU"}}


## Exercise task list

1 - Skills
1.1 - Retrieving and working with remote resources [check]
1.2 - Building and populating databases [check]
1.3 - Structuring PHP applications [check]
1.4 - Designing and serving REST APIs [check]
1.5 - Use of Github (public or private repo) or other public code repository service [check]
1.6 - Documenting web services and applications [check]

2 - Output
2.1 - A process for constructing a database to hold the GeoLite Country reference data [check]
2.2 - A process that checks if the GeoLite Country database is populated and if not, downloads the
GeoLite Country database file from the following url and populates the database from the data file
https://php-dev-task.s3-eu-west-1.amazonaws.com/GeoIPCountryCSV.zip [check]
2.3 - An API that supports a RESTful GET endpoint that returns the country when supplied with an IP
address (GET /locationByIP?IP=127.0.0.1) [check]
2.4 - Automated tests to confirm an IP address in Portugal [x]
2.5 - An accessible online source code repository containing the output of this task and appropriate
installation, configuration and usage documentation along with explanation of approach and any
decisions made [check]
