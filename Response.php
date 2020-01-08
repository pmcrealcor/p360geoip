<?php

final class Response
{
	public $status;
	public $data;

	/**
	 * Constructor
	 */
	public function __construct($status = 204, $data = "")
	{
		$this->status = $status;
		$this->data = $data;
	}

	/**
	 * Constructor
	 */
	public function handover()
	{
		http_response_code($this->status);
		echo json_encode($this->data);
	}



}