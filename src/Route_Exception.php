<?php

declare(strict_types=1);

/**
 * Route Exceptions
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

use Exception;
use PinkCrab\Route\Route\Route;

class Route_Exception extends Exception {

	/**
	 * Returns Route_Exception for namespace not defined.
	 *
	 * @param string $route
	 * @return self
	 * @code 101
	 */
	public static function namespace_not_defined( string $route ): self {
		return new self(
			sprintf( 'Namespace not defined in %s', $route ),
			101
		);
	}

	/**
	 * Returns an exception for a route with no callback defined.
	 *
	 * @param Route $route
	 * @return self
	 * @code 102
	 */
	public static function callback_not_defined( Route $route ): self {
		// Set the namespace if exists.
		$namespace = '' !== $route->get_namespace()
				? $route->get_namespace()
				: '_MISSING_NAMESPACE_';

		return new self(
			sprintf(
				'Callback not defined for [%s] %s%s',
				strtoupper( $route->get_method() ),
				strtoupper( $namespace ),
				strtoupper( $route->get_route() )
			),
			102
		);
	}

	/**
	 * Returns an exception for a route with an invlaid/unsupported HTTP method.
	 *
	 * @param Route $route
	 * @return self
	 */
	public static function invalid_http_method( Route $route ): self {
		return new self(
			sprintf(
				'%s is a none supported HTTP Mehtod.',
				strtoupper( $route->get_method() )
			),
			103
		);
	}
}
