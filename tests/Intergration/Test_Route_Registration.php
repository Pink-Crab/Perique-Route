<?php

declare(strict_types=1);

/**
 * Intergration tests for single routes.
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
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Route
 *
 * @docs https://www.advancedcustomfields.com/resources/acf_add_options_page/
 */

namespace PinkCrab\Route\Tests\Unit\Registration;

use WP_REST_Response;
use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route\Argument;
use PinkCrab\Route\Tests\Fixtures\HTTP_TestCase;


class Test_Route_Registration extends HTTP_TestCase {

	/** @testdox A basic GET route with no auth or arguments should be accessible via a rest call. */
	public function test_get_route_no_arguments(): void {

		// Mocked route.
		$route = new Route( 'GET', '/test' );
		$route->namespace( 'pinkcrab/v1' );
		$route->callback(
			function( $request ) {
				return new WP_REST_Response( array( 'hi' => 'hello' ) );
			}
		);

		// Register the rout with WP.
		$this->route_manager->from_route( $route );
		$this->route_manager->execute();
		$this->register_routes();

		// Check the route exits.
		$this->assertNotEmpty( $this->server->get_routes( 'pinkcrab/v1' ) );

		// Do a request.
		$response = $this->dispatch_request( 'GET', '/pinkcrab/v1/test' );

		// Check response has the expected data.
		$this->assertEquals( 200, $response->get_status() );
		$this->assertArrayHasKey( 'hi', $response->get_data() );
		$this->assertEquals( 'hello', $response->get_data()['hi'] );
	}

	/** @testdox A route which has an authentication callback should pass or fail based on the request sent. */
	public function test_put_with_authentication(): void {

		// Create route.
		$route = new Route( 'PUT', '/put-test' );
		$route->namespace( 'pinkcrab/v1' );
		$route->callback(
			function( $request ) {
				return new WP_REST_Response( array( 'key_is' => '3' ) );
			}
		);
		$route->authentication(
			function( $request ): bool {
				return $request->get_header( 'key' ) === '3';
			}
		);

		// Register the rout with WP.
		$this->route_manager->from_route( $route );
		$this->route_manager->execute();
		$this->register_routes();

		// Send valid request with key.
		$response = $this->dispatch_request(
			'PUT',
			'/pinkcrab/v1/put-test',
			array(),
			function( $request ) {
				$request->set_header( 'key', 3 );
				return $request;
			}
		);

		$this->assertEquals( 200, $response->get_status() );
		$this->assertArrayHasKey( 'key_is', $response->get_data() );
		$this->assertEquals( '3', $response->get_data()['key_is'] );

		// Check request with invalid header
		$response = $this->dispatch_request(
			'PUT',
			'/pinkcrab/v1/put-test',
			array(),
			function( $request ) {
				$request->set_header( 'key', 12 );
				return $request;
			}
		);

		$this->assertEquals( 401, $response->get_status() );
	}

    /** @testdox A route with arguments should allow arguments to validated and sanitized. */
	public function test_post_with_arguments(): void {
		$route = new Route( 'POST', '/post-test/(?P<id>[\d]+)' );
		$route->namespace( 'pinkcrab/v1' );
		$route->callback(
			function( $request ) {
				return new WP_REST_Response( $request->get_params() );
			}
		);
		$route->argument(
			Argument::on( 'id' )
				->type( 'number' )
				->validation(
					function( $value, $request, $key ) {
						return $value !== '9';
					}
				)
				->sanitization( // Return 12 if value is 4
					function( $value, $request, $key ) {
						return '4' === $value ? '12' : $value;
					}
				)
		);

		// Register the route with WP.
		$this->route_manager->from_route( $route );
		$this->route_manager->execute();
		$this->register_routes();

		// Check the call is made and id is passed
		$response = $this->dispatch_request( 'POST', '/pinkcrab/v1/post-test/2' );
		$this->assertEquals( 200, $response->get_status() );
		$this->assertArrayHasKey( 'id', $response->get_data() );
		$this->assertEquals( '2', $response->get_data()['id'] );

		// Should fail if 9 is passed (validation)
        $response = $this->dispatch_request( 'POST', '/pinkcrab/v1/post-test/9' );
		$this->assertEquals( 400, $response->get_status() );
		$this->assertTrue( $response->is_error() );

        // Should return 12 if 4 is passed
        $response = $this->dispatch_request( 'POST', '/pinkcrab/v1/post-test/4');
		$this->assertEquals( 200, $response->get_status() );
		$this->assertEquals( '12', $response->get_data()['id'] );
	}


}
