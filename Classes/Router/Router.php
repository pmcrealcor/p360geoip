<?php

namespace Palmeida\Geoip\Router;

use Palmeida\Geoip\Responses\Response;

/**
 * Application Router
 * 
 * @author Paulo Almeida <palmeida@growin.com>
 */
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
				'Controller' => 'Palmeida\Geoip\Controllers\GeoipController',
				'Action' => 'locationByIP',
			],
		];
	}

	/**
	 * Execute the request
	 *
	 * @return Object - Response()
	 */
	public function execute()
	{
		$method = $this->requestMethod();
		$endpoint = $this->requestEndpoint();

		$route = $this->routes[$method][$endpoint] ?? null;

		if ($route) {
			$controller = $route['Controller'];
			$action = $route['Action'];

			return (new $controller())->$action();
		} else {
			return new Response(404, ["message" => "Resource does not exist"]);
		}
	}

	/**
	 * Retrieve the request method
	 *
	 * @return string
	 */
	private function requestMethod()
	{
		return $_SERVER['REQUEST_METHOD'] ?? "GET";
	}

	/**
	 * Retrieve the request endpoint
	 *
	 * @return string

	 */
	private function requestEndpoint()
	{
		return trim(strtok($_SERVER['REQUEST_URI'], "?"), "/");
	}

}