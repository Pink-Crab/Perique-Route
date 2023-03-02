<?php

declare(strict_types=1);

/**
 * Registers routes through WP API from Route mooels.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Registration;

use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route_Exception;
use PinkCrab\WP_Rest_Schema\Argument\Argument;
use PinkCrab\WP_Rest_Schema\Parser\Argument_Parser;
use function PinkCrab\FunctionConstructors\Comparisons\all;

class WP_Rest_Registrar {

	/**
	 * The register wp rest callback.
	 *
	 * @param \PinkCrab\Route\Route\Route $route
	 * @return callable
	 */
	public function create_callback( Route $route ): callable {
		return function() use ( $route ): void {
			$model = $this->map_to_wp_rest( $route );
			register_rest_route( $model->namespace, $model->route, $model->args );
		};
	}

	/**
	 * Maps a wp rest model from Route.
	 *
	 * @param \PinkCrab\Route\Route\Route $route
	 * @return WP_Rest_Route
	 */
	public function map_to_wp_rest( Route $route ): WP_Rest_Route {
		$wp_rest            = new WP_Rest_Route();
		$wp_rest->namespace = $route->get_namespace();
		$wp_rest->route     = $route->get_route();
		$wp_rest->args      = $this->parse_options( $route );
		return $wp_rest;
	}

	/**
	 * Parsed the args array used to register.
	 *
	 * @param Route $route
	 * @return array<mixed>
	 * @throws Route_Exception
	 */
	protected function parse_options( Route $route ): array {

		// If we have no callback defined for route, throw.
		if ( is_null( $route->get_callback() ) ) {
			throw Route_Exception::callback_not_defined( $route );
		}

		// If we have an invlaid method, throw
		if ( ! $this->is_valid_method( $route->get_method() ) ) {
			throw Route_Exception::invalid_http_method( $route );
		}

		$options                        = array();
		$options['methods']             = $route->get_method();
		$options['callback']            = $route->get_callback();
		$options['permission_callback'] = $this->compose_permission_callback( $route );
		$options['args']                = $this->parse_args( $route );

		return $options;
	}

	/**
	 * Parsed the args array of options.
	 *
	 * @param Route $route
	 * @return array<mixed>
	 */
	protected function parse_args( Route $route ): array {
		return array_reduce(
			$route->get_arguments(),
			function( array $args, Argument $argument ) {
				$args[ $argument->get_key() ] = Argument_Parser::as_single( $argument );
				return $args;
			},
			array()
		);
	}

	/**
	 * Checks if a defined HTTP method is valid.
	 *
	 * @param string $method
	 * @return boolean
	 */
	protected function is_valid_method( string $method ): bool {
		return in_array(
			$method,
			apply_filters(
				'pinkcrab/route/accepted_http_methods', // phpcs:ignore WordPress.NamingConventions.ValidHookName
				array( Route::DELETE, Route::POST, Route::PUT, Route::PATCH, Route::GET )
			),
			true
		);
	}

	/**
	 * Compose the permission callback function for the route.
	 *
	 * @param Route $route
	 * @return callable
	 */
	protected function compose_permission_callback( Route $route ): callable {
		$callbacks = $route->get_authentication();

		// If we have no callback defined, use return true.
		if ( count( $callbacks ) === 0 ) {
			return '__return_true';
		}

		// If we only have 1, return as is.
		if ( count( $callbacks ) === 1 ) {
			return reset( $callbacks );
		}

		return all( ...$callbacks );
	}


}
