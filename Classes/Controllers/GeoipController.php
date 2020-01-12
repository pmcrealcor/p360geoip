<?php

namespace Palmeida\Geoip\Controllers;

use Palmeida\Geoip\Models\Location;
use Palmeida\Geoip\Responses\Response;

/**
 *
 */
final class GeoipController extends Controller
{

	/**
	 *
	 */
	public function locationByIp()
	{
		$ip = $_GET['IP'] ?? "255.255.255.255";

		$ip_long = ip2long($ip);

		$location = new Location();

		$location->findByIP($ip_long);

		if ($location->isLoaded()) {
			return new Response(200, ["data" => $location->asTransformedArray()]);
		} else {
			return new Response(404, ["message" => "Resource does not exist"], $ip);
		}
	}

}