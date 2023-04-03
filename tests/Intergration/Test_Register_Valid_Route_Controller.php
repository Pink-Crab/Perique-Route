<?php

declare(strict_types=1);

/**
 * Intergration tests for a valid route
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

namespace PinkCrab\Route\Tests\Unit\Registration;

use PinkCrab\Route\Utils;
use PinkCrab\Route\Module\Route;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Route\Route_Exception;
use PinkCrab\Route\Route_Collection;
use PinkCrab\Route\Route_Controller;
use phpDocumentor\Reflection\Types\Void_;
use PinkCrab\Route\Module\Route_Middleware;
use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Route\Tests\Fixtures\HTTP_TestCase;
use PinkCrab\Route\Tests\Fixtures\Fixture_Valid_Route_Controller;

class Test_Register_Valid_Route_Controller extends HTTP_TestCase {

	/** @testdox It should be possible to get the namespace from a route controller */
	public function test_can_get_namespace_from_controller(): void {
		$controller = new Fixture_Valid_Route_Controller();

		$this->assertEquals(
			'pinkcrab/v3',
			Objects::invoke_method( $controller, 'get_namespace', array() )
		);
	}

	/** @testdox The route controller should self populate a factory using the defined namespace. */
	public function test_can_access_populated_factory(): void {
		$controller = new Fixture_Valid_Route_Controller();
		$factory    = Objects::invoke_method( $controller, 'get_factory', array() );

		$this->assertEquals(
			'pinkcrab/v3',
			Objects::get_property( $factory, 'namespace' )
		);
	}

	/** @testdox It should be possible to populate a route collection from a route controller. */
	public function test_get_routes(): void {
		$controller = new Fixture_Valid_Route_Controller();
		$collection = new Route_Collection();

		$controller->get_routes( $collection );
		$this->assertCount( 2, $collection );
	}

	/** @testdox If a controller has no namespace defined and exception should be thrown. */
	public function test_throws_exception_if_no_namespace_in_controller(): void {
		$mock_controller = $this->createMock( Route_Controller::class );

		$this->expectException( Route_Exception::class );
		Objects::invoke_method( $mock_controller, 'get_namespace', array() );
	}

	/** @testdox When the middleware is added to the App and a valid controller is added to registration, the routes defined should be working. */
	public function test_as_app_middleware(): void {

		$app = ( new App_Factory() )->with_wp_dice( true )
		->app_config( array() )
		->module( Route::class )
		->registration_classes(
			array(
				Fixture_Valid_Route_Controller::class,
			)
		)
		->boot();

		// Trigger app intialisation
		do_action( 'init' );

		// Extract the registration service and the middleware from app.
		$module_manager       = Objects::get_property( $app, 'module_manager' );
		$registration_service = Objects::get_property( $module_manager, 'registration_service' );
		$middleware           = Objects::get_property( $registration_service, 'middleware' );
		$middleware           = $middleware[ Route_Middleware::class ];

		// Extract the hooks from the dispatcher, from middleware
		$route_manager = Objects::get_property( $middleware, 'route_manager' );
		$hook_loader   = Objects::get_property( $route_manager, 'loader' );
		$hooks         = Objects::get_property( $hook_loader, 'hooks' );
		$hooks         = $hooks->export();

		// Check routes are registered from Hook Loader
		$this->assertTrue( $hooks[0]->is_registered() );
		$this->assertTrue( $hooks[1]->is_registered() );
		$this->assertTrue( $hooks[2]->is_registered() );

		// Check hooks on rest_api_init
		$this->assertEquals( 'rest_api_init', $hooks[0]->get_handle() );
		$this->assertEquals( 'rest_api_init', $hooks[1]->get_handle() );
		$this->assertEquals( 'rest_api_init', $hooks[2]->get_handle() );

		// Initlaise the routes.
		$this->register_routes();

		// Check basic get
		$this->assertEquals(
			200,
			$this->dispatch_request( 'GET', '/pinkcrab/v3/valid-get' )
				->get_status()
		);

		// Check basic PUT not set.
		$this->assertEquals(
			404,
			$this->dispatch_request( 'PUT', '/pinkcrab/v3/valid-get' )
				->get_status()
		);

		// Check group post
		$this->assertEquals(
			200,
			$this->dispatch_request( 'POST', '/pinkcrab/v3/valid-group' )
				->get_status()
		);

		// Check group delete
		$this->assertEquals(
			200,
			$this->dispatch_request( 'DELETE', '/pinkcrab/v3/valid-group' )
				->get_status()
		);
	}
}
