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

namespace PinkCrab\Route;

use PinkCrab\Route\Route;
use PinkCrab\Route\Route_Factory;
use PinkCrab\Route\Route\Route_Authentication_Trait;

class Route_Group {

	/**
	 * Seter and Compiler for Authentication.
	 * @method add_authentication( callable $auth_callback ): self
	 * @method compile_authentication(): callable
	 */
	use Route_Authentication_Trait;

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var Route[]
	 */
	protected $routes = array();

	/**
	 * @var callable[]
	 */
	protected $authentication;

	/** @var Route_Factory */
	protected $route_factory;

	/**
	 * @var string
	 */
	protected $route;

	public function __construct( string $namespace, string $route ) {
		$this->route         = $route;
		$this->namespace     = $namespace;
		$this->route_factory = new Route_Factory( $namespace );
	}

	/**
	 * Get the value of namespace
	 *
	 * @return string
	 */
	public function get_namespace(): string {
		return $this->namespace;
	}

	/**
	 * Set the value of namespace
	 *
	 * @param string $namespace
	 *
	 * @return self
	 */
	public function set_namespace( string $namespace ): self {
		$this->namespace = $namespace;
		return $this;
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
	 * @param callable $callable
	 * @return \PinkCrab\Route\Route
	 */
	public function get( callable $callable ): Route {
		$route = $this->route_factory->get( $this->route, $callable );
		$route->set_namespace( $this->namespace );
		$this->routes[ Route::GET ] = $route;
		return $route;
	}

	/**
	 * Creates a post request.
	 *
	 * @param callable $callable
	 * @return \PinkCrab\Route\Route
	 */
	public function post( callable $callable ): Route {
		$route = $this->route_factory->post( $this->route, $callable );
		$route->set_namespace( $this->namespace );
		$this->routes[ Route::POST ] = $route;
		return $route;
	}

	/**
	 * Creates a put request.
	 *
	 * @param callable $callable
	 * @return \PinkCrab\Route\Route
	 */
	public function put( callable $callable ): Route {
		$route = $this->route_factory->put( $this->route, $callable );
		$route->set_namespace( $this->namespace );
		$this->routes[ Route::PUT ] = $route;
		return $route;
	}

	 /**
	 * Creates a patch  request.
	 *
	 * @param callable $callable
	 * @return \PinkCrab\Route\Route
	 */
	public function patch( callable $callable ): Route {
		$route = $this->route_factory->patch( $this->route, $callable );
		$route->set_namespace( $this->namespace );
		$this->routes[ Route::PATCH ] = $route;
		return $route;
	}

	/**
	 * Creates a delete  request.
	 *
	 * @param callable $callable
	 * @return \PinkCrab\Route\Route
	 */
	public function delete( callable $callable ): Route {
		$route = $this->route_factory->delete( $this->route, $callable );
		$route->set_namespace( $this->namespace );
		$this->routes[ Route::DELETE ] = $route;
		return $route;
	}

	/**
	 * Adds a route ot the collection
	 *
	 * @param \PinkCrab\Route\Route $route
	 * @return self
	 */
	public function add_rest_route( Route $route ): self {
		$this->routes[] = $route;
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
	 * Checks we have routes.
	 *
	 * @return bool
	 */
	public function has_routes(): bool {
		return ! empty( $this->routes );
	}
}
