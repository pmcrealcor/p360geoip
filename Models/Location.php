<?php

/**
 *
 */
final class Location extends Model
{

	public function __construct()
	{
		$this->__tablename = "GeoIpCountryWhois";
		parent::__construct();
	}

	/**
	 *
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

	public function asTransformedArray()
	{
		if ($this->__loaded) {
			return [
				'country' => $this->__data['country_name'],
				'countryCode' => $this->__data['country_code'],
			];
		}
	}
}