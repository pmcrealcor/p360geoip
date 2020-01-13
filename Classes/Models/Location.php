<?php

namespace Palmeida\Geoip\Models;

/**
 * A Model representation of a databasa table
 *
 * @author Paulo Almeida <palmeida@growin.com>
 */
final class Location extends Model
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct("GeoIpCountryWhois");
	}

	/**
	 * Helper to make a specific query
	 *
	 * @param string $ip - An IP address
	 */
	public function findByIP($ip)
	{
		$query = "SELECT * FROM " . $this->__tablename . " where $ip BETWEEN ip_long_from AND ip_long_to";

		$result = $this->__connection->query($query);

		if ($result->num_rows == 1) {
			$this->__loaded = true;
			while ($row = $result->fetch_assoc()) {
				break;
			}

			$this->__data = $row;
		}
	}

	/**
	 * Retrieves this object data transformed as an array
	 *
	 * @return Array
	 */
	public function asTransformedArray()
	{
		if ($this->__loaded) {
			return [
				'country' => $this->__data['country_name'],
				'countryCode' => $this->__data['country_code'],
			];
		}
		return [];
	}

}