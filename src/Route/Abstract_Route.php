<?php

declare(strict_types=1);

/**
 * THe Abstract base for all routes and groups.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Route;

use PinkCrab\Route\Route\Argument;

abstract class Abstract_Route {

	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var Argument[]
	 */
	protected $arguments = array();

	/**
	 * @var callable[]
	 */
	protected $authentication = array();

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
	 * @return static
	 */
	public function namespace( string $namespace ) {
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * Get the value of arguments
	 *
	 * @return Argument[]
	 */
	public function get_arguments(): array {
		return $this->arguments;
	}

	/**
	 * Adds a single argument to the arguments list.
	 *
	 * @param Argument $argument
	 * @return static
	 */
	public function argument( Argument $argument ) {
		$this->arguments[] = $argument;
		return $this;
	}

	/**
	 * Add a single callback authentication stack
	 *
	 * @param callable(\WP_REST_Request): bool $auth_callback
	 * @return static
	 */
	public function authentication( callable $auth_callback ) {
		$this->authentication[] = $auth_callback;
		return $this;
	}

	/**
	 * Get the value of authentication
	 *
	 * @return  callable[]
	 */
	public function get_authentication(): array {
		return $this->authentication;
	}
}
