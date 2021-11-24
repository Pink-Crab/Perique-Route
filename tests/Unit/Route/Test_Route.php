<?php

declare(strict_types=1);

/**
 * Tests a Route model
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

 */

namespace PinkCrab\Route\Tests\Unit\Route;

use WP_UnitTestCase;
use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route\Argument;
use PinkCrab\Route\Registration\Route_Manager;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;

class Test_Route extends WP_UnitTestCase {

	/** @testdox It should be possible to create a route using the method and route and be able to access those values. */
	public function test_get_route_get_method(): void {
		$route = new Route( 'GET', '/test/(?P<id>[\d]+)' );
		$this->assertEquals( 'GET', $route->get_method() );
		$this->assertEquals( '/test/(?P<id>[\d]+)', $route->get_route() );
	}

	/** @testdox It should be possible to stackup multiple authentication callbacks. */
	public function test_add_authentication(): void {
		$route = new Route( 'GET', '/route' );

		$route->authentication( 'is_string' );
		$route->authentication( 'is_bool' );

		$this->AssertCount( 2, $route->get_authentication() );
		$this->assertContains( 'is_string', $route->get_authentication() );
		$this->assertContains( 'is_bool', $route->get_authentication() );
	}

	/** @testdox It should be possible to define and get the base namespace for a route. */
	public function test_set_get_namespace(): void {
		$route = new Route( 'GET', '/route' );
		$route->namespace( 'namespace' );
		$this->assertEquals( 'namespace', $route->get_namespace() );
	}

	/** @testdox It should be possible to set and use the primary callback. */
	public function test_set_get_callback(): void {
		$route = new Route( 'GET', '/route' );
		$route->callback(
			function( \WP_REST_Request $request ) {
				return array( 'success', 200 );
			}
		);

		$this->assertContains( 200, $route->get_callback()( $this->createMock( \WP_REST_Request::class ) ) );
		$this->assertContains( 'success', $route->get_callback()( $this->createMock( \WP_REST_Request::class ) ) );
	}

	/** @testdox It  should be possible to set and get all arguments to a route. */
	public function test_can_set_get_arguments(): void {
		$route = new Route( 'GET', '/route' );
		$arg1  = String_Type::on( 'arg1' );
		$arg2  = String_Type::on( 'arg2' );
		$route->argument( $arg1 );
		$route->argument( $arg2 );

		$this->assertSame( $arg1, $route->get_arguments()['arg1'] );
		$this->assertSame( $arg2, $route->get_arguments()['arg2'] );
	}

	/** @testdox It should be possible to create a copy of a route wth a different method type and have all other values retained. */
	public function test_with_method(): void {

		$route = new Route( 'GET', 'test/' );
		$route->callback( 'is_string' );
		$route->namespace( 'NS' );
		$route->argument( String_Type::on( 'arg1' ) );
		$route->argument( String_Type::on( 'arg2' ) );
		$route->authentication( 'is_string' );
		$route->authentication( 'is_bool' );

		// Create clone.
		$put_route = $route->with_method( 'PUT' );

		$this->assertEquals( 'PUT', $put_route->get_method() );
		$this->assertEquals( 'NS', $put_route->get_namespace() );
		$this->assertSame( $put_route->get_callback(), $route->get_callback() );
		$this->assertSame( $put_route->get_arguments(), $route->get_arguments() );
		$this->assertSame( $put_route->get_authentication(), $route->get_authentication() );

	}

	/** @testdox When registering a route, a leading slash should always be set, even if not entered. */
	public function test_always_have_leading_slash(): void {
		$route_without = new Route( 'get', 'without' );
		$this->assertEquals( '/without', $route_without->get_route() );

		$route_with = new Route( 'get', '/with' );
		$this->assertEquals( '/with', $route_with->get_route() );
	}

	/** @testdox It should be possible to use shortcut argument deifnitions. */
	public function test_shortcut_arg_definition() {
		$curlies = new Route( 'get', 'curlies/{param1}/{param2}' );
		$this->assertEquals( '/curlies/(?<param1>[\@a-zA-Z0-9&.?:-_=#]*)/(?<param2>[\@a-zA-Z0-9&.?:-_=#]*)', $curlies->get_route() );

		$curlies = new Route( 'get', 'named/:param1' );
		$this->assertEquals( '/named/(?<param1>[\@a-zA-Z0-9&.?:-_=#]*)', $curlies->get_route() );
	}
}
