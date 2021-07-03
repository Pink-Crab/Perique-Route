<?php

declare(strict_types=1);

/**
 * The primary route controller and processor
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

use PinkCrab\Loader\Hook_Loader;

class Route_Manager {

	/** @var Hook_Loader */
	protected $loader;

	/** @var Route_Collection */
	protected $routes;

	public function __construct() {
		$this->loader = new Hook_Loader();
		$this->routes = new Route_Collection();

	}

	/**
	 * Adds a route to the collection.
	 *
	 * @param Route|Route_Group $routes
	 * @return void
	 */
	public function add_route( $route ): void {
		$this->routes->add_route( $route );
	}

	/**
	 * Pass an array of routes in.
	 *
	 * @param array $routes
	 * @return void
	 */
	public function define_routes( array $routes ): void {
		foreach ( $routes as $route ) {
			$this->add_route( $route );
		}
	}

	/**
	 * Registers all hooks.
	 *
	 * @return void
	 */
	public function execute(): void {
		$this->loader->register_hooks();
	}
}
