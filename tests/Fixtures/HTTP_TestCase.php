<?php

declare(strict_types=1);

/**
 * Base class for Rest Integration tests.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Route
 */

namespace PinkCrab\Route\Tests\Fixtures;

use PinkCrab\Route\Registration\Route_Manager;
use PinkCrab\Loader\Hook_Loader;
use PinkCrab\Route\Registration\WP_Rest_Registrar;
use WP_REST_Response;

abstract class HTTP_TestCase extends \WP_UnitTestCase {

	/** @var Spy_REST_Server */
	protected $server;

	/** @var Route_Manager */
	protected $route_manager;

	public function setUp() {
		parent::setUp();
		add_filter( 'rest_url', array( $this, 'filter_rest_url_for_leading_slash' ), 10, 2 );

		// Create route manager.
		$this->route_manager = new Route_Manager(
			new WP_Rest_Registrar(),
			new Hook_Loader
		);

		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$wp_rest_server = new \Spy_REST_Server;
		$this->server   = $wp_rest_server;
	}

	public function tearDown() {
		parent::tearDown();
		remove_filter( 'rest_url', array( $this, 'test_rest_url_for_leading_slash' ), 10, 2 );
		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$wp_rest_server = null;
	}

	public function register_routes(): void {
		do_action( 'rest_api_init', $this->wp_rest_server );
	}

	public function filter_rest_url_for_leading_slash( $url, $path ) {
		if ( is_multisite() || get_option( 'permalink_structure' ) ) {
			return $url;
		}

		// Make sure path for rest_url has a leading slash for proper resolution.
		$this->assertTrue( 0 === strpos( $path, '/' ), 'REST API URL should have a leading slash.' );

		return $url;
	}

	/**
	 * Will dispatch a request based on the method, route and args.
	 * Returns the WP_REST_Response object or WP_Error
	 *
	 * Ensure your route starts with a / (slash)
	 *
	 * @param string $method
	 * @param string $route
	 * @param array $args
	 * @return WP_REST_Response|WP_Error
	 */
	public function dispatch_request(
		string $method,
		string $route,
		array $args = array(),
		?callable $config = null
	) {
		$request = new \WP_REST_Request( $method, $route, $args );
		if ( $config ) {
			$request = $config( $request );
		}
		return $this->server->dispatch( $request );
	}
}
