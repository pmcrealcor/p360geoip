<?php

final class Router
{
	private $routes = [];

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->routes = [
			"GET" => [],
			"POST" => [],
			"PUT" => [],
			"PATCH" => [],
			"DELETE" => [],
		];

		$this->register();
	}

	/**
	 * Register possible routes
	 */
	public function register()
	{
		// for this app, only one route will be registered
		$this->routes["GET"] = [
			'locationByIP' => [
				'Controller' => 'LocationByIP',
				'Action' => 'show',
			]
		];
	}

	/**
	 * Execute the request
	 */
	public function execute()
	{
		$method = $this->requestMethod();
		$endpoint = $this->requestEndpoint();
		$route = $this->routes[$method][$endpoint] ?? null;

		if ($route
			&& file_exists("../Controllers/" . $route['Controller'] . ".php") 
			&& is_readable("../Controllers/" . $route['Controller'] . ".php")) {
			include_once("../Controllers/" . $route['Controller'] . ".php");
			return (new $route['Controller'])->{$route['Action']}();
		} else {
			return new Response(404, ["message" => "Resource does not exist"]);
		}
	}

	/**
	 * Retrieve the request method
	 */
	private function requestMethod()
	{
		return $_SERVER['REQUEST_METHOD'] ?? "GET";
	}

	/**
	 * Retrieve the request endpoint
	 */
	private function requestEndpoint()
	{
		return trim(strtok($_SERVER['REQUEST_URI'], "?"), "/");
	}


}