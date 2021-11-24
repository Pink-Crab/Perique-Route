<?php

declare(strict_types=1);

/**
 * Intergration tests for route groups
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
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Perique\Application\App;
use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Route\Tests\Fixtures\HTTP_TestCase;
use PinkCrab\Route\Tests\Fixtures\Fixture_Route_Shortcut_Args;


class Test_Shortcut_Args_Registration extends HTTP_TestCase {

	public function setUp() {
		parent::setUp();
		$app = new App();
		Objects::set_property( $app, 'app_config', null );
		Objects::set_property( $app, 'container', null );
		Objects::set_property( $app, 'registration', null );
		Objects::set_property( $app, 'loader', null );
		Objects::set_property( $app, 'booted', false );
		$app = null;
	}

	/** @testdox It should be possible to define a route using shortcut (dynamic and named) arguments and have a general rule for allowed symbols. */
	public function test_as_app_middleware(): void {
		$middleware = Utils::middleware_provider();

		$app = ( new App_Factory() )->with_wp_dice( true )
		->app_config( array() )
		->registration_middleware( $middleware )
		->registration_classes(
			array(
				Fixture_Route_Shortcut_Args::class,
			)
		)
		->boot();

		// Run wp setup for routing and app intialisation.
		do_action( 'init' );
		$this->register_routes();

		$namespace = '/pinkcrab/v3/';

		// Check with valid single curlies
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/single-curliness/foo' )->get_status() );
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/single-curliness/allow12345' )->get_status() );
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/single-curliness/allow&.:_=#@?' )->get_status() );

		// Double curlies
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/dual-curliness/allow12345/allow&.:_=#@?' )->get_status() );

		// Single named
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/single-named/foo' )->get_status() );
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/single-named/allow12345' )->get_status() );
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/single-named/allow&.:_=#@?' )->get_status() );

		// Mixed
		$this->assertEquals( 200, $this->dispatch_request( 'GET', $namespace . 'shortcut/mixed/allow12345/allow&.:_=#@?' )->get_status() );
	}

}
