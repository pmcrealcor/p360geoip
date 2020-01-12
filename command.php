<?php

/**
 * @author Paulo Almeida - palmeida @ growin.com
 *
 * 
 */

include_once("./env.php");

include_once("./vendor/autoload.php");

use Palmeida\Geoip\Commands\Command;

(new Command())->execute($argv);
