<?php

declare(strict_types=1);

/**
 * The abstract route dispatcher used to create routes.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route;

use PinkCrab\Loader\Hook_Loader;

class Route_Dispatcher {

	/** @var Hook_Loader */
	protected $loader;

	/** @var Route_Collection */
	protected $routes;

	public function __construct() {
		$this->loader = new Hook_Loader();
		$this->routes = new Route_Collection();

	}

	/**
	 * Undocumented function
	 *
	 * @param Route|Route_Group $route
	 * @return void
	 */
	public function add_route( ...$route ): void {
		# code...
	}

	public function define_routes(): self {
		# code...
	}

	/**
	 * Registers all hooks.
	 *
	 * @return void
	 */
	public function execute(): void {
		$this->loader->register_hooks();
	}
}
