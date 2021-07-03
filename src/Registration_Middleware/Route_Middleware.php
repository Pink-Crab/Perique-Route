<?php

declare(strict_types=1);

/**
 * Route Dispatcher Middleware
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Route
 */

namespace PinkCrab\Route\Registration_Middleware;

use PinkCrab\Route\Route_Dispatcher;
use PinkCrab\Perique\Interfaces\Registration_Middleware;

class Route_Middleware implements Registration_Middleware {

	/** @var Route_Dispatcher */
	public $dispatcher;

	public function __construct( Route_Dispatcher $dispatcher ) {
		$this->dispatcher = $dispatcher;
	}

	/**
	 * Add all valid route calls to the dispatcher.
	 *
	 * @param object $class
	 * @return object
	 */
	public function process( $class ) {

	}

	public function setup(): void {
		/*noOp*/
	}

	/**
	 * Register all routes with WordPress calls.
	 *
	 * @return void
	 */
	public function tear_down(): void {
		$this->dispatcher->execute();
	}
}
