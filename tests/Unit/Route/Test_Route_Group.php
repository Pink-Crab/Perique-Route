<?php

declare(strict_types=1);

/**
 * Tests a Route Group model
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
use PinkCrab\Route\Route_Factory;

use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Route\Route\Route_Group;
use PinkCrab\WP_Rest_Schema\Argument\String_Type;

class Test_Route_Group extends WP_UnitTestCase {

	/** @testdox It should be possible to create a group with a single route and be able to access the route */
	public function test_get_route(): void {
		$group = new Route_Group( 'namespace', 'route' );
		$this->assertEquals( 'route', $group->get_route() );
	}

	/** @testdox It should be possible to set and get the namespace */
	public function test_can_set_get_namespace(): void {
		$group = new Route_Group( 'namespace', 'route' );
		$this->assertEquals( 'namespace', $group->get_namespace() );
	}

	/** @testdox It should be possible to add multiple routes to the group, check if routes exist and recall them. */
	public function test_can_add_routes(): void {
		$route1 = new Route( 'GET', 'test' );
		$route2 = new Route( 'POST', 'test' );

		$group = new Route_Group( 'namespace', 'test' );

		$this->assertFalse( $group->has_routes() );

		$group->add_rest_route( $route1 );
		$group->add_rest_route( $route2 );

		$this->assertTrue( $group->has_routes() );

		$this->assertCount( 2, $group->get_rest_routes() );
		$this->assertContains( $route1, $group->get_rest_routes() );
		$this->assertContains( $route2, $group->get_rest_routes() );
	}

	/** @testdox It should be possible to set group wide authentication applied to all defined routes. */
	public function test_group_authentication(): void {
		$group = new Route_Group( 'namespace', 'route' );
		$group->authentication( 'is_null' );
		$group->authentication( 'is_float' );

		$this->assertCount( 2, $group->get_authentication() );
		$this->assertContains( 'is_null', $group->get_authentication() );
		$this->assertContains( 'is_float', $group->get_authentication() );
	}

	/** @testdox It should be possible to set group wide arguments applied to all defined routes. */
	public function test_group_arguments(): void {
		$group = new Route_Group( 'namespace', 'route' );
		$arg1  = String_Type::on( 'arg1' );
		$arg2  = String_Type::on( 'arg2' );
		$group->argument( $arg1 );
		$group->argument( $arg2 );

		$this->assertSame( $arg1, $group->get_arguments()['arg1'] );
		$this->assertSame( $arg2, $group->get_arguments()['arg2'] );
	}

	/** @testdox It should be possible to use helper methods to create get, post, delete, put and patch routes for the group. */
	public function test_method_helpers(): void {
		$group = new Route_Group( 'namespace', 'route' );
		$group->get( 'is_string' );
		$group->post( 'is_null' );
		$group->put( 'is_float' );
		$group->patch( 'is_int' );
		$group->delete( 'is_array' );

		// Check all set.
		$this->assertArrayHasKey( 'GET', $group->get_rest_routes() );
		$this->assertArrayHasKey( 'POST', $group->get_rest_routes() );
		$this->assertArrayHasKey( 'DELETE', $group->get_rest_routes() );
		$this->assertArrayHasKey( 'PUT', $group->get_rest_routes() );
		$this->assertArrayHasKey( 'PATCH', $group->get_rest_routes() );

		// Check are valid routes.
		$this->assertInstanceOf( Route::class, $group->get_rest_routes()['GET'] );
		$this->assertInstanceOf( Route::class, $group->get_rest_routes()['POST'] );
		$this->assertInstanceOf( Route::class, $group->get_rest_routes()['DELETE'] );
		$this->assertInstanceOf( Route::class, $group->get_rest_routes()['PUT'] );
		$this->assertInstanceOf( Route::class, $group->get_rest_routes()['PATCH'] );

		// Check Values.
		$this->assertEquals( 'is_string', $group->get_rest_routes()['GET']->get_callback() );
		$this->assertEquals( 'is_null', $group->get_rest_routes()['POST']->get_callback() );
		$this->assertEquals( 'is_array', $group->get_rest_routes()['DELETE']->get_callback() );
		$this->assertEquals( 'is_float', $group->get_rest_routes()['PUT']->get_callback() );
		$this->assertEquals( 'is_int', $group->get_rest_routes()['PATCH']->get_callback() );
	}

	/** @testdox It should be possible to check if a specified route is defined. */
	public function test_method_exists(): void {
		$group = new Route_Group( 'namespace', 'route' );
		$group->post( 'is_string' );

		$this->assertTrue( $group->method_exists( Route::POST ) );
		$this->assertFalse( $group->method_exists( Route::PATCH ) );

		// Test with mixed case.
		$this->assertTrue( $group->method_exists( 'post' ) );
		$this->assertFalse( $group->method_exists( 'patch' ) );
		$this->assertTrue( $group->method_exists( 'PoSt' ) );
		$this->assertFalse( $group->method_exists( 'pAtCh' ) );
	}
}
