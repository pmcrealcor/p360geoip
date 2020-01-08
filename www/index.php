<?php

/**
 * @author Paulo Almeida - palmeida @ growin.com
 *
 * 
 */

include_once("../env.php");
include_once("../Controller.php");
include_once("../Model.php");
include_once("../Router.php");
include_once("../Response.php");


/*
$response = (new Router())->execute();
$response->handover();
*/


include_once("../Models/Builder.php");
$builder = new Builder();
//$builder->createTable();
$builder->importData();
