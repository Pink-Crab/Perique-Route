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

use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route\Route_Group;


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
	 * Static constructor.
	 *
	 * @param string $namespace
	 * @return Route_Factory
	 */
	public static function for( string $namespace ): Route_Factory {
		return new self( $namespace );
	}

	/**
	 * Creates a request
	 *
	 * @param string $method
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	protected function request( string $method, string $route, callable $callback ): Route {
		$route = new Route( $method, $route );
		return $route
			->callback( $callback )
			->namespace( $this->namespace );
	}

	/**
	 * Creates a get request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function get( string $route, callable $callback ): Route {
		return $this->request( Route::GET, $route, $callback );
	}

	/**
	 * Creates a post request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function post( string $route, callable $callback ): Route {
		return $this->request( Route::POST, $route, $callback );
	}

	/**
	 * Creates a put request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function put( string $route, callable $callback ): Route {
		return $this->request( Route::PUT, $route, $callback );
	}

	/**
	 * Creates a patch request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function patch( string $route, callable $callback ): Route {
		return $this->request( Route::PATCH, $route, $callback );
	}

	/**
	 * Creates a delete request route with the defined namespace.
	 *
	 * @param string $route
	 * @param callable $callback
	 * @return Route
	 */
	public function delete( string $route, callable $callback ): Route {
		return $this->request( Route::DELETE, $route, $callback );
	}

	/**
	 * Allows the building of a group.
	 *
	 * @param string $route
	 * @param callable|null $config
	 * @return Route_Group
	 */
	public function group_builder( string $route, ?callable $config ): Route_Group {
		$group = new Route_Group( $this->namespace, $route );

		// Apply the callback.
		if ( ! is_null( $config ) ) {
			$config( $group );
		}

		return $group;
	}
}
