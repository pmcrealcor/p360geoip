<?php

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{

 	public function testResponseForPortugueseIP(): void
    {
		$response = $this->get("");
        $response->assertStatus(200);
        $this->assertEquals(['country' => 'Portugal', 'countryCode' => 'PT'], json_decode($response->getContent())->data);
    }

}