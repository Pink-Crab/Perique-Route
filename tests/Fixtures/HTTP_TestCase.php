<?php

declare(strict_types=1);

/**
 * Base class for Rest Integration tests.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Route
 */

namespace PinkCrab\Route\Tests\Fixtures;

use PinkCrab\Route\Registration\Route_Manager;
use Gin0115\WPUnit_Helpers\Objects;

use PinkCrab\Perique\Application\App;

use PinkCrab\Loader\Hook_Loader;
use PinkCrab\Route\Registration\WP_Rest_Registrar;
use WP_REST_Response;

abstract class HTTP_TestCase extends \WP_UnitTestCase {


	/** @var Spy_REST_Server */
	protected $server;

	/** @var Route_Manager */
	protected $route_manager;

	public function setUp(): void {
		parent::setUp();
		add_filter( 'rest_url', array( $this, 'filter_rest_url_for_leading_slash' ), 10, 2 );

		// Create route manager.
		$this->route_manager = new Route_Manager(
			new WP_Rest_Registrar(),
			new Hook_Loader()
		);

		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$wp_rest_server = new \Spy_REST_Server();
		$this->server   = $wp_rest_server;
	}

	public function tearDown(): void {
		parent::tearDown();
		remove_filter( 'rest_url', array( $this, 'test_rest_url_for_leading_slash' ), 10, 2 );
		/** @var WP_REST_Server $wp_rest_server */
		global $wp_rest_server;
		$wp_rest_server = null;

		// Unset any active instance of Perique App.
		$app = new App();
		Objects::set_property( $app, 'app_config', null );
		Objects::set_property( $app, 'container', null );
		Objects::set_property( $app, 'registration', null );
		Objects::set_property( $app, 'loader', null );
		Objects::set_property( $app, 'booted', false );
		$app = null;


	}

	public function register_routes(): void {
		do_action( 'rest_api_init', $this->server );
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

	public function get_server(): \WP_REST_Server {
		return $this->server;
	}
}
