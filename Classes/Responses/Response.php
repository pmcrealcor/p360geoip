<?php

namespace Palmeida\Geoip\Responses;

/**
 * Application base response
 *
 * @author Paulo Almeida <palmeida@growin.com>
 */
final class Response
{
	public $status;
	public $data;

	/**
	 * Constructor
	 */
	public function __construct($status = 204, $data = "", $ip = "")
	{
		$this->status = $status;
		$this->data = $data;
	}

	/**
	 * Handover a response
	 */
	public function handover()
	{
		http_response_code($this->status);

		if($this->data) {
			$this->data['execution_time'] = $this->execTime();
		}

		echo json_encode($this->data);
	}

	/**
	 * Retrieves de execution time
	 *
	 * @return float
	 */
	private function execTime()
	{
		return microtime(true) - EXEC_START;
	}

}