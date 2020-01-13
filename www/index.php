<?php

/**
 * A PHP exercise - A simple API that gives you de location (country) of a given IP
 * 
 * @author Paulo Almeida - palmeida @ growin.com
 */

include_once("../env.php");

include_once("../vendor/autoload.php");

use Palmeida\Geoip\Router\Router;

$response = (new Router())->execute();

$response->handover();
