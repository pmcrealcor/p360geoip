<?php

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{

 	public function testResponseForPortugueseIP(): void
    {
    	$ip = "87.103.122.191"; // portuguese ip

		$client = new GuzzleHttp\Client();
		$response = $client->get(BASE_URL . 'locationByIP?IP=' . $ip);

        $this->assertEquals(200, $response->getStatusCode());

	    $contentType = $response->getHeaders()["Content-Type"][0];
	    $this->assertEquals("application/json", $contentType);

		$data = json_decode($response->getBody(), true);
		$this->assertEquals(['country' => 'Portugal', 'countryCode' => 'PT'], $data['data']);
    }

}