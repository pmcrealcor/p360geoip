<?php

/**
 * @author Paulo Almeida - palmeida @ growin.com
 *
 * 
 */

include_once("../env.php");

include_once("../vendor/autoload.php");

use Palmeida\Geoip\Router\Router;

$response = (new Router())->execute();

$response->handover();
