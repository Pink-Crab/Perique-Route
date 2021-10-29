<?php

declare(strict_types=1);

/**
 * A route definition.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Route;

class Route extends Abstract_Route {

	public const GET    = 'GET';
	public const POST   = 'POST';
	public const PATCH  = 'PATCH';
	public const PUT    = 'PUT';
	public const DELETE = 'DELETE';

	/**
	 * @var string
	 */
	protected $route;

	/**
	 * @var string
	 */
	protected $method;

	/**
	 * @var callable|null
	 */
	protected $callback = null;


	public function __construct( string $method, string $route ) {
		$this->method = $method;
		$this->route  = $this->format_route( $route );
	}

	/**
	 * Formats a route with a leading slash.
	 *
	 * @param string $route
	 * @return string
	 */
	protected function format_route( string $route ): string {
		return '/' . ltrim( $route, '/\\' );
	}

	/**
	 * Get the value of callback
	 *
	 * @return null|callable
	 */
	public function get_callback(): ?callable {
		return $this->callback;
	}

	/**
	 * Set the value of callback
	 *
	 * @param callable $callback
	 *
	 * @return self
	 */
	public function callback( callable $callback ): self {
		$this->callback = $callback;
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
	 * Get the value of method
	 *
	 * @return string
	 */
	public function get_method(): string {
		return $this->method;
	}

	/**
	 * Set the value of namespace
	 *
	 * @param string $namespace
	 * @return static
	 */
	public function namespace( string $namespace ) {
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * Returns the route with a different method.
	 *
	 * @param string $method
	 * @return self
	 */
	public function with_method( string $method ): self {
		$clone = new self( $method, $this->get_route() );
		// Arguments
		if ( ! empty( $this->arguments ) ) {
			foreach ( $this->arguments as $argument ) {
				$clone->argument( $argument );
			}
		}
		// Authentication
		if ( ! empty( $this->authentication ) ) {
			foreach ( $this->authentication as $authentication ) {
				$clone->authentication( $authentication );
			}
		}
		// Callback
		if ( ! empty( $this->callback ) ) {
			$clone->callback( $this->callback );
		}
		// Namespace
		if ( ! empty( $this->namespace ) ) {
			$clone->namespace( $this->namespace );
		}

		return $clone;
	}
}

