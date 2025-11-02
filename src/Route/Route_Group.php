<?php

declare(strict_types=1);

/**
 * A custom file of function polyfills to get around php-scoper using global functions
 * in function_exist calls.
 *
 * This file should have the same namespace used in scoper.inc.php config.
 *
 * @package PinkCrab\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Route;

use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route_Factory;
use PinkCrab\Route\Route_Exception;
use PinkCrab\Route\Route\Abstract_Route;

class Route_Group extends Abstract_Route {

	/**
	 * @var Route[]
	 */
	protected array $routes = array();

	protected Route_Factory $route_factory;
	protected string $route;

	public function __construct( string $namespace, string $route ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.namespaceFound
		$this->route         = $route;
		$this->namespace     = $namespace;
		$this->route_factory = new Route_Factory( $namespace );
	}

	/**
	 * Get the value of route
	 *
	 * @return string
	 */
	public function get_route(): string {
		return $this->route;
	}

	/**
	 * Creates a get request.
	 *
	 * @param callable(\WP_REST_Request): (\WP_HTTP_Response|\WP_Error) $callable
	 * @return Route
	 */
	public function get( callable $callable ): Route { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		$route = $this->route_factory->get( $this->route, $callable );
		$route->namespace( $this->namespace );
		$this->routes[ Route::GET ] = $route;
		return $route;
	}

	/**
	 * Creates a post request.
	 *
	 * @param callable(\WP_REST_Request): (\WP_HTTP_Response|\WP_Error) $callable
	 * @return Route
	 */
	public function post( callable $callable ): Route { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		$route = $this->route_factory->post( $this->route, $callable );
		$route->namespace( $this->namespace );
		$this->routes[ Route::POST ] = $route;
		return $route;
	}

	/**
	 * Creates a put request.
	 *
	 * @param callable(\WP_REST_Request): (\WP_HTTP_Response|\WP_Error) $callable
	 * @return Route
	 */
	public function put( callable $callable ): Route { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		$route = $this->route_factory->put( $this->route, $callable );
		$route->namespace( $this->namespace );
		$this->routes[ Route::PUT ] = $route;
		return $route;
	}

	/**
	 * Creates a patch  request.
	 *
	 * @param callable(\WP_REST_Request): (\WP_HTTP_Response|\WP_Error) $callable
	 * @return Route
	 */
	public function patch( callable $callable ): Route { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		$route = $this->route_factory->patch( $this->route, $callable );
		$route->namespace( $this->namespace );
		$this->routes[ Route::PATCH ] = $route;
		return $route;
	}

	/**
	 * Creates a delete  request.
	 *
	 * @param callable(\WP_REST_Request): (\WP_HTTP_Response|\WP_Error) $callable
	 * @return Route
	 */
	public function delete( callable $callable ): Route { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.callableFound
		$route = $this->route_factory->delete( $this->route, $callable );
		$route->namespace( $this->namespace );
		$this->routes[ Route::DELETE ] = $route;
		return $route;
	}

	/**
	 * Adds a route ot the collection
	 *
	 * @param Route $route
	 * @deprecated 0.0.2 This is not really used and should be removed in a future version.
	 * @return self
	 */
	public function add_rest_route( Route $route ): self {
		$this->routes[ $route->get_method() ] = $route;
		return $this;
	}

	/**
	 * Returns all the current routes.
	 *
	 * @return Route[]
	 */
	public function get_rest_routes(): array {
		return $this->routes;
	}

	/**
	 * Checks if a specific method is defined.
	 *
	 * @param string $method
	 * @return boolean
	 */
	public function method_exists( string $method ): bool {
		return array_key_exists( \strtoupper( $method ), $this->routes );
	}

	/**
	 * Checks we have routes.
	 *
	 * @return boolean
	 */
	public function has_routes(): bool {
		return ! empty( $this->routes );
	}
}
