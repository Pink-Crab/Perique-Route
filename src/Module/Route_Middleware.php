<?php

declare(strict_types=1);

/**
 * Route Registration Middleware
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Route
 */

namespace PinkCrab\Route\Module;

use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route_Collection;
use PinkCrab\Route\Route_Controller;
use PinkCrab\Route\Route\Route_Group;
use PinkCrab\Route\Route\Abstract_Route;
use PinkCrab\Route\Registration\Route_Manager;
use PinkCrab\Perique\Interfaces\Registration_Middleware;

class Route_Middleware implements Registration_Middleware {

	protected Route_Manager $route_manager;

	public function __construct( Route_Manager $route_manager ) {
		$this->route_manager = $route_manager;
	}

	/**
	 * Add all valid route calls to the dispatcher.
	 *
	 * @param object $class
	 * @return object
	 */
	public function process( object $class ): object { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.classFound

		if ( is_a( $class, Route_Controller::class ) ) {
			$routes = $class->get_routes( new Route_Collection() );
			$routes->each(
				function ( Abstract_Route $route ) {
					if ( is_a( $route, Route::class ) ) {
						$this->route_manager->from_route( $route );
						return;
					}

					if ( is_a( $route, Route_Group::class ) ) {
						$this->route_manager->from_group( $route );
						return;
					}
				}
			);
		}

		return $class;
	}

	public function setup(): void {
		/*noOp*/
	}

	/**
	 * Register all routes with WordPress calls.
	 *
	 * @return void
	 */
	public function tear_down(): void {
		$this->route_manager->execute();
	}
}
