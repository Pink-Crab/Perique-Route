<?php

declare(strict_types=1);

/**
 * Setter and Callable Compiler for the authentication of routes and groups.
 *
 * @package PinkCrab\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Route;

trait Route_Authentication_Trait {

	/**
	 * Generates the authentication callback.
	 *
	 * @return callable
	 */
	public function compile_authentication(): callable {
		return function( \WP_REST_Request $request ): bool {

			foreach ( $this->authentication as $callable ) {
				$result = (bool) $callable( $request );
				if ( $result === false ) {
					return false;
				}
			}

			// Fallback to true
			return true;
		};
	}

	/**
	 * Add a single callback authentication stack
	 *
	 * @param callable(request:WP_REST_Request):bool $authentication
	 *
	 * @return self
	 */
	public function add_authentication( callable $auth_callback ): self {
		$this->authentication[] = $auth_callback;
		return $this;
	}
}
