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
		$this->checkData($ip);
	}

	/**
	 * Handover a response
	 */
	public function handover()
	{
		http_response_code($this->status);
		header('Content-Type: application/json');
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

	/**
	 * Checks is data is set, and adds the exec time metric
	 */
	private function checkData($ip)
	{
		if($this->data &&
			$ip && 
			$ip == "127.0.0.1") {
			$this->data['message'] = "There's no place like home! < L. Frank Baum >";
		}

		if($this->data && INC_EXEC_TIME) {
			$this->data['execution_time'] = $this->execTime();
		}
	}
}