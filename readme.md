# p360geoIP


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
	TMP_DIR -> Temp directory, used for downloaded files. MUST BE WRITABLE
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







## Requisites

PHP Development pre-interview task

Introduction
This task is intended for prospective PHP Developers to demonstrate capability in the following skills:
● Retrieving and working with remote resources
● Building and populating databases
● Structuring PHP applications
● Designing and serving REST APIs
● Use of Github (public or private repo) or other public code repository service
● Documenting web services and applications

Output
The output of the task should include the following:
1. A process for constructing a database to hold the GeoLite Country reference data
2. A process that checks if the GeoLite Country database is populated and if not, downloads the
GeoLite Country database file from the following url and populates the database from the data file
https://php-dev-task.s3-eu-west-1.amazonaws.com/GeoIPCountryCSV.zip
3. An API that supports a RESTful GET endpoint that returns the country when supplied with an IP
address (GET /locationByIP?IP=127.0.0.1)
4. Automated tests to confirm an IP address in Portugal
5. An accessible online source code repository containing the output of this task and appropriate
installation, configuration and usage documentation along with explanation of approach and any
decisions made
Support
Please don’t hesitate to ask if any of the instructions are unclear to you. Questions can be sent to the
following address and will be answered as quickly as possible:
michael.pinnell@pure360.com