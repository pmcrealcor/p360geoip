<?php

include_once("../Models/Location.php");

/**
 *
 */
final class LocationByIP extends Controller
{

	/**
	 *
	 */
	public function show()
	{
		$ip = $_GET['ip'] ?? "255.255.255.255";

		$ip_long = ip2long($ip);

		$location = new Location();

		$location->findByIP($ip_long);

		if ($location->isLoaded()) {
			return new Response(200, ["data" => $location->asTransformedArray()]);
		} else {
			return new Response(404, ["message" => "Resource does not exist"]);
		}
	}

}