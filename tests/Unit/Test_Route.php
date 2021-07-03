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
 * @docs https://www.advancedcustomfields.com/resources/acf_add_options_page/
 */

namespace PinkCrab\Route\Tests\Unit;

use WP_UnitTestCase;
use PinkCrab\Route\Route;
use PinkCrab\Route\Route_Argument;
use Gin0115\WPUnit_Helpers\Objects;

class Test_Route extends WP_UnitTestCase {

	/** @testdox It should be possible to create a route using the method and route and be able to access those values. */
	public function test_get_route_get_method(): void {
		$route = new Route( 'GET', '/test/(?P<id>[\d]+)' );
		$this->assertEquals( 'GET', $route->get_method() );
		$this->assertEquals( '/test/(?P<id>[\d]+)', $route->get_route() );
	}

	/** @testdox It should be possible to stackup multiple authentication calls and execute them as a group. */
	public function test_add_authentication(): void {
		$route = new Route( 'GET', '/route' );
		$route->add_authentication(
			function( $request ): bool {
				return is_numeric( $request->get_body() );
			}
		);
		$route->add_authentication(
			function( $request ): bool {
				return (int) $request->get_body() === 123;
			}
		);

		$request = $this->createMock( \WP_REST_Request::class );
		$request->method( 'get_body' )->willReturn( '123' );

		$this->assertTrue( $route->compile_authentication()( $request ) );

		// Run again as a failure (partial)
		unset( $request );

		$request = $this->createMock( \WP_REST_Request::class );
		$request->method( 'get_body' )->willReturn( '789' );

		$this->assertFalse( $route->compile_authentication()( $request ) );
	}

	/** @testdox It should be possible to define and get the base namespace for a route. */
	public function test_set_get_namespace(): void {
		$route = new Route( 'GET', '/route' );
		$route->set_namespace( 'namespace' );
		$this->assertEquals( 'namespace', $route->get_namespace() );
	}

	/** @testdox It should be possible to set and use the primary callback. */
	public function test_set_get_callback(): void {
		$route = new Route( 'GET', '/route' );
		$route->set_callback(
			function( \WP_REST_Request $request ) {
				return  array( 'success', 200 );
			}
		);

		$this->assertContains( 200, $route->get_callback()( $this->createMock( \WP_REST_Request::class ) ) );
		$this->assertContains( 'success', $route->get_callback()( $this->createMock( \WP_REST_Request::class ) ) );
	}

    /** @testdox It  should be possible to set and get all arguments to a route. */
    public function test_can_set_get_arguemnts(): void
    {
        $route = new Route( 'GET', '/route' );
        $arg1 = Route_Argument::on('arg1');
        $arg2 = Route_Argument::on('arg2');
        $route->add_argument($arg1);
        $route->add_argument($arg2);
        
        $this->assertSame($arg1, $route->get_arguments()[0]);
        $this->assertSame($arg2, $route->get_arguments()[1]);
    }
}
