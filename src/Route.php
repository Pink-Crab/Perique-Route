<?php

declare(strict_types=1);

/**
 * A route definition.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

use PinkCrab\Route\Route_Argument;
use PinkCrab\Route\Route\Route_Authentication_Trait;

class Route {

	public const GET    = 'GET';
	public const POST   = 'POST';
	public const PATCH  = 'PATCH';
	public const PUT    = 'PUT';
	public const DELETE = 'DELETE';

	/**
	 * Seter and Compiler for Authentication.
	 * @method add_authentication( callable $auth_callback ): self
	 * @method compile_authentication(): callable
	 */
	use Route_Authentication_Trait;

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var string
	 */
	protected $route = '';

	/**
	 * @var Route_Argument[]
	 */
	protected $arguments = array();

	/**
	 * @var string
	 */
	protected $method = '';

	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * @var callable[]
	 */
	protected $authentication;

	public function __construct( string $method, string $route ) {
		$this->method = $method;
		$this->route  = $route;
	}


	/**
	 * Get the value of callback
	 *
	 * @return callable
	 */
	public function get_callback(): callable {
		return $this->callback;
	}

	/**
	 * Set the value of callback
	 *
	 * @param callable $callback
	 *
	 * @return self
	 */
	public function set_callback( callable $callback ): self {
		$this->callback = $callback;
		return $this;
	}

	/**
	 * Get the value of arguments
	 *
	 * @return Route_Argument[]
	 */
	public function get_arguments(): array {
		return $this->arguments;
	}

	/**
	 * Adds a single argument to the arguments list.
	 *
	 * @param \PinkCrab\Route\Route_Argument $argument
	 * @return self
	 */
	public function add_argument( Route_Argument $argument ): self {
		$this->arguments[] = $argument;
		return $this;
	}

	public function argument( string $arg, ?callable $config ): self {
		$argument = new Route_Argument( $key );
		if ( ! is_null( $argument ) ) {
			$argument = $config( $argument );
		}
		$this->add_argument( $argument );
		return $this
	}

	/**
	 * Get the value of namespace
	 *
	 * @return string
	 */
	public function get_namespace(): string {
		return $this->namespace;
	}

	/**
	 * Set the value of namespace
	 *
	 * @param string $namespace
	 *
	 * @return self
	 */
	public function set_namespace( string $namespace ): self {
		$this->namespace = $namespace;
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
	 * Returns the route w
	 *
	 * @param string $method
	 * @return self
	 */
	public function with_method( string $method ): self {
		$clone = new self( $method, $this->get_route() );
		// Arguments
		if ( ! empty( $this->arguments ) ) {
			foreach ( \array_reverse( $this->arguments ) as $argument ) {
				$clone->add_argument( $argument );
			}
		}
		// Authentication
		if ( ! empty( $this->authentication ) ) {
			foreach ( \array_reverse( $this->authentication ) as $authentication ) {
				$clone->add_authentication( $authentication );
			}
		}
		// Callback
		if ( ! empty( $this->callback ) ) {
			$clone->set_callback( $this->callback );
		}
		// Namespace
		if ( ! empty( $this->namespace ) ) {
			$clone->set_namespace( $this->namespace );
		}
	}
}

