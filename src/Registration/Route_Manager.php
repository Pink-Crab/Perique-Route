<?php

declare(strict_types=1);

/**
 * Handle the parsing and registering of route with WordPress.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Registration;

use PinkCrab\Route\Route\Route;
use PinkCrab\Loader\Hook_Loader;
use PinkCrab\Route\Route\Route_Group;
use PinkCrab\Route\Registration\WP_Rest_Registrar;

class Route_Manager {

	/** @var Hook_Loader */
	protected $loader;

	/** @var WP_Rest_Registrar */
	protected $registrar;

	public function __construct( WP_Rest_Registrar $registrar, Hook_Loader $hook_loader ) {
		$this->loader    = $hook_loader;
		$this->registrar = $registrar;
	}

	/**
	 * Registers a WP Rest Route from a passed Route model.
	 *
	 * @param Route $route
	 * @return self
	 */
	public function from_route( Route $route ): self {
		$this->loader->action(
			'rest_api_init',
			$this->registrar->create_callback( $route )
		);

		return $this;
	}

	public function from_group( Route_Group $group ): self {
		foreach ( $this->unpack_group( $group ) as $route ) {
			$this->from_route( $route );
		}

		return $this;
	}

	/**
	 * Registers all routes added to the loader
	 *
	 * @return void
	 */
	public function execute(): void {
		$this->loader->register_hooks();
	}

}
