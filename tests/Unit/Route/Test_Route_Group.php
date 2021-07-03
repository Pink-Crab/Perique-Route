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
 * @docs https://www.advancedcustomfields.com/resources/acf_add_options_page/
 */

namespace PinkCrab\Route\Tests\Unit\Route;

use WP_UnitTestCase;
use PinkCrab\Route\Route;
use PinkCrab\Route\Route_Group;
use PinkCrab\Route\Route_Factory;
use PinkCrab\Route\Argument;
use Gin0115\WPUnit_Helpers\Objects;

class Test_Route_Group extends WP_UnitTestCase {

	// /** @testdox It should be possible to create a group with a single route and be able to access the route */
	// public function test_get_route(): void {
	// 	$group = new Route_Group( 'namespace', 'route' );
	// 	$this->assertEquals( 'route', $group->get_route() );
	// }

	// /** @testdox It should be possible to set and get the namespace */
	// public function test_can_set_get_namespace(): void {
	// 	$group = new Route_Group( 'namespace', 'route' );
	// 	$this->assertEquals( 'namespace', $group->get_namespace() );
	// }

	// /** @testdox It should be possible to add multiple routes to the group, check if routes exist and recall them. */
	// public function test_can_add_routes(): void {
	// 	$route1 = new Route( 'GET', 'test' );
	// 	$route2 = new Route( 'POST', 'test' );

	// 	$group = new Route_Group( 'namespace', 'test' );

	// 	$this->assertFalse( $group->has_routes() );

	// 	$group->add_rest_route( $route1 );
	// 	$group->add_rest_route( $route2 );

	// 	$this->assertTrue( $group->has_routes() );

	// 	$this->assertCount( 2, $group->get_rest_routes() );
	// 	$this->assertContains( $route1, $group->get_rest_routes() );
	// 	$this->assertContains( $route2, $group->get_rest_routes() );
	// }

	// public function test_group_builder(): void {
	// 	$factory = new Route_Factory( 'namespace/v2', );
	// 	$group   = $factory->group_builder(
	// 		'example/(?P<id>[\d]+)',
	// 		function( Route_Group $group ) {

	// 			// The get route
	// 			$group->get( 'is_int' );

	// 			// The patch route.
	// 			$group->patch( 'is_float' );
	// 		}
	// 	)
	// 	->add_authentication( 'is_array' )
	// 	->argument(
	// 		Argument::on( 'id' )
	// 			->validation( 'is_int' )
	// 			->sanitization( 'absint' )
	// 			->required()
	// 	);

	// 	dump( $group );
	// }
}
