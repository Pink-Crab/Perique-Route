<?php

declare(strict_types=1);

/**
 * Holds all routes to be dispatched.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

use PinkCrab\Route\Route_Group;
use PinkCrab\Collection\Collection;

class Route_Collection extends Collection {

	protected const ALLOWED_ROUTE_TYPES = array( Route::class, Route_Group::class );

	/**
	 * Overwrite this method in any extended classes, to modify the inital data.
	 *
	 * @param array<int|string, mixed> $data
	 * @return array<int|string, mixed>
	 */
	protected function map_construct( array $data ): array {
		return array_filter(
			$data,
			function( $datum ): bool {
				return in_array(
					get_class( $datum ),
					self::ALLOWED_ROUTE_TYPES,
					true
				);
			}
		);
	}

	/**
	 * Adds a route to the collection
	 *
	 * @param Route|Route_Group $route
	 * @return static
	 */
	public function add_route( $route ) {
		$this->push( $route );
		return $this;
	}
}
