<?php

declare(strict_types=1);

/**
 * Tests a Route Factory model
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
use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route_Factory;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Route\Route\Route_Group;

class Test_Route_Factory extends WP_UnitTestCase {

	/** @testdox It should be possible to create the factory with a defined namespace. */
	public function test_create_with_static(): void {
		$factory        = new Route_Factory( 'constructor' );
		$factory_static = Route_Factory::for( 'static' );

		$this->assertEquals( 'constructor', Objects::get_property( $factory, 'namespace' ) );
		$this->assertEquals( 'static', Objects::get_property( $factory_static, 'namespace' ) );
	}

	/** @testdox It should be possible to create a get request and have namespace and methd defined. */
	public function test_create_get_route(): void {
		$route = Route_Factory::for( 'static' )
			->get( 'route', 'is_string' );

		$this->assertInstanceOf( Route::class, $route );
		$this->assertEquals( 'GET', $route->get_method() );
		$this->assertEquals( 'route', $route->get_route() );
		$this->assertEquals( 'static', $route->get_namespace() );
		$this->assertEquals( 'is_string', $route->get_callback() );
	}

	/** @testdox It should be possible to create a post request and have namespace and methd defined. */
	public function test_create_post_route(): void {
		$route = Route_Factory::for( 'static' )
			->post( 'route', 'is_string' );

		$this->assertInstanceOf( Route::class, $route );
		$this->assertEquals( 'POST', $route->get_method() );
		$this->assertEquals( 'route', $route->get_route() );
		$this->assertEquals( 'static', $route->get_namespace() );
		$this->assertEquals( 'is_string', $route->get_callback() );
	}

	/** @testdox It should be possible to create a delete request and have namespace and methd defined. */
	public function test_create_delete_route(): void {
		$route = Route_Factory::for( 'static' )
			->delete( 'route', 'is_string' );

		$this->assertInstanceOf( Route::class, $route );
		$this->assertEquals( 'DELETE', $route->get_method() );
		$this->assertEquals( 'route', $route->get_route() );
		$this->assertEquals( 'static', $route->get_namespace() );
		$this->assertEquals( 'is_string', $route->get_callback() );
	}

	/** @testdox It should be possible to create a patch request and have namespace and methd defined. */
	public function test_create_patch_route(): void {
		$route = Route_Factory::for( 'static' )
			->patch( 'route', 'is_string' );

		$this->assertInstanceOf( Route::class, $route );
		$this->assertEquals( 'PATCH', $route->get_method() );
		$this->assertEquals( 'route', $route->get_route() );
		$this->assertEquals( 'static', $route->get_namespace() );
		$this->assertEquals( 'is_string', $route->get_callback() );
	}

	/** @testdox It should be possible to create a put request and have namespace and methd defined. */
	public function test_create_put_route(): void {
		$route = Route_Factory::for( 'static' )
			->put( 'route', 'is_string' );

		$this->assertInstanceOf( Route::class, $route );
		$this->assertEquals( 'PUT', $route->get_method() );
		$this->assertEquals( 'route', $route->get_route() );
		$this->assertEquals( 'static', $route->get_namespace() );
		$this->assertEquals( 'is_string', $route->get_callback() );
	}

	/** @testdox It should be possible to create a group with the namespace passed to the factory. */
	public function test_build_group(): void {
		$group = Route_Factory::for( 'badger/v1' )->group_builder(
			'newNS',
			function( Route_Group $group ) {
				$group->get( 'is_string' );
				$group->delete( 'is_float' );
			}
		);

		$this->assertInstanceOf( Route_Group::class, $group );
		$this->assertTrue( $group->method_exists( 'GET' ) );
		$this->assertTrue( $group->method_exists( 'DELETE' ) );
		$this->assertFalse( $group->method_exists( 'PATCH' ) );
		$this->assertEquals( 'badger/v1', $group->get_namespace() );
		$this->assertEquals( 'newNS', $group->get_route() );
	}
}
