<?php

declare(strict_types=1);

/**
 * Factory to create routes for a namespace
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

class Route_Factory {

	/**
	 * The namespace of all routes from factory
	 *
	 * @var string
	 */
	protected $namespace;

	public function __construct( string $namespace ) {
		$this->namespace = $namespace;
	}

	/**
	 * Creates a request
	 *
	 * @param string $method
	 * @param string $route
	 * @param callable $callback
	 * @return void
	 */
	protected function request( string $method, string $route, callable $callback ) {
		$route = new Route( $method, $route );
		return $route
			->set_callback( $callback )
			->set_namespace( $this->namespace );
	}

	/**
	 * Creates a get request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function get( string $route, callable $callback ): Route {
		return $this->request( 'GET', $route, $callback );
	}

	/**
	 * Creates a post request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function post( string $route, callable $callback ): Route {
		return $this->request( 'POST', $route, $callback );
	}

	/**
	 * Creates a put request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function put( string $route, callable $callback ): Route {
		return $this->request( 'PUT', $route, $callback );
	}

	/**
	 * Creates a patch request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function patch( string $route, callable $callback ): Route {
		return $this->request( 'PATCH', $route, $callback );
	}

	/**
	 * Creates a delete request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function delete( string $route, callable $callback ): Route {
		return $this->request( 'DELETE', $route, $callback );
	}

	/**
	 * Allows the building of a group.
	 *
	 * @param string $route
	 * @param callable $config
	 * @return Route_Group
	 */
	public function group_builder( string $route, callable $config ): Route_Group {
		$group = new Route_Group( $this->namespace, $route );

		// Apply the callback.
		$config( $group );

		return $group;
	}
}
