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

use PinkCrab\Route\Utils;
use PinkCrab\Route\Route\Route;


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
	 */
	protected function parse_options( Route $route ): array {
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
		$args = array();
		foreach ( $route->get_arguments() as $argument ) {

			$arg = array();

			if ( $argument->get_validation() ) {
				$arg['validate_callback'] = $argument->get_validation();
			}

			if ( $argument->get_sanitization() ) {
				$arg['sanitize_callback'] = $argument->get_sanitization();
			}

			if ( ! is_null( $argument->get_type() ) ) {
				$arg['type'] = $argument->get_type();
			}

			if ( ! is_null( $argument->get_required() ) ) {
				$arg['required'] = $argument->get_required();
			}

			if ( '' !== $argument->get_description() ) {
				$arg['description'] = $argument->get_description();
			}

			if ( ! is_null( $argument->get_default() ) ) {
				$arg['default'] = $argument->get_default();
			}

			if ( ! is_null( $argument->get_format() ) ) {
				$arg['format'] = $argument->get_format();
			}

			if ( is_array( $argument->get_expected() ) && ! empty( $argument->get_expected() ) ) {
				$arg['enum'] = $argument->get_expected();
			}

			if ( ! is_null( $argument->get_minimum() ) ) {
				$arg['minimum']          = $argument->get_minimum();
				$arg['minimumExclusive'] = $argument->get_exclusive_minimum();
			}

			if ( ! is_null( $argument->get_maximum() ) ) {
				$arg['maximum']          = $argument->get_maximum();
				$arg['maximumExclusive'] = $argument->get_exclusive_maximum();
			}

			$args[ $argument->get_key() ] = $arg;
		}

		return $args;
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
			return $callbacks[0];
		}

		return Utils::compose_conditional_all_true( ...$callbacks );
	}


}
