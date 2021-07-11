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

use PinkCrab\Route\Route\Route_Group;
use PinkCrab\Route\Tests\Fixtures\HTTP_TestCase;


class Test_Group_Registration extends HTTP_TestCase {

    /** Returns a callable for returnnig a 200 response with the passed data.*/
    protected function callback_provider(array $response_data): callable
    {
        return function($request) use ($response_data){
            return new \WP_REST_Response( $response_data );
        };
    }

    /** Returns a callable for checing api-key is foo */
    protected function auth_callback_api_key(): callable
    {
        return function( $request ): bool {
            return $request->get_header( 'api-key' ) === 'foo';
        };
    }

    /** @testdox It should be possible to register a group and have all the routes registered with WP for use. */
    public function test_can_register_route_group(): void
    {
        // Mocked route.
		$group = new Route_Group( 'pinkcrab/v1', '/group-route' );
		$group->get( $this->callback_provider(['method' => 'get']));

		$group->post( $this->callback_provider(['method' => 'post']) )
			->authentication( function( $request ): bool {
            return $request->get_header( 'extra-key' ) === 'bar';
        } );
        
        $group->authentication( $this->auth_callback_api_key() );		
        
        // Register the rout with WP.
		$this->route_manager->from_group( $group );
		$this->route_manager->execute();
		$this->register_routes();

        // Check get route exists with auth check (single api key)
		$response = $this->dispatch_request(
			'GET',
			'/pinkcrab/v1/group-route',
			array(),
			function( $request ) {
				$request->set_header( 'api-key', 'foo' );
				return $request;
			}
		);

		$this->assertEquals( 200, $response->get_status() );
		$this->assertArrayHasKey( 'method', $response->get_data() );
		$this->assertEquals( 'get', $response->get_data()['method'] );

        // Check GET auth error.
        $response = $this->dispatch_request(
			'GET',
			'/pinkcrab/v1/group-route',
		);
        $this->assertEquals( 401, $response->get_status() );

        // Check post requires both headers.
        $response = $this->dispatch_request(
			'POST',
			'/pinkcrab/v1/group-route',
			array(),
			function( $request ) {
				$request->set_header( 'api-key', 'foo' );
				$request->set_header( 'extra-key', 'bar' );
				return $request;
			}
		);

        $this->assertEquals( 200, $response->get_status() );
		$this->assertArrayHasKey( 'method', $response->get_data() );
		$this->assertEquals( 'post', $response->get_data()['method'] );

        // Check post fails with only single header.
        $response = $this->dispatch_request(
			'POST',
			'/pinkcrab/v1/group-route',
			array(),
			function( $request ) {
				$request->set_header( 'api-key', 'foo' );
				return $request;
			}
		);

        $this->assertEquals( 401, $response->get_status() );
    }

}