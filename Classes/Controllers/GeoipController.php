<?php

namespace Palmeida\Geoip\Controllers;

use Palmeida\Geoip\Models\Location;
use Palmeida\Geoip\Responses\Response;

/**
 * Controller for the main application features
 *
 * @author Paulo Almeida <palmeida@growin.com>
 */
final class GeoipController extends Controller
{

	/**
	 * The main action for this API
	 * @return Response
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