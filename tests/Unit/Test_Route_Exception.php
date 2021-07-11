<?php

declare(strict_types=1);

/**
 * Tests for the Route Exceptions
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

namespace PinkCrab\Route\Tests\Unit;

use Exception;
use WP_UnitTestCase;
use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route_Exception;

class Test_Route_Exception extends WP_UnitTestCase {

    /** @testdox It should be possible to create an exception for a route with no namespace */
    public function test_missing_namespace(): void {
        $exception = Route_Exception::namespace_not_defined('no__namespace');

        $this->assertInstanceOf(Route_Exception::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals(101, $exception->getCode());
        $this->assertEquals('Namespace not defined in no__namespace', $exception->getMessage());
    }

    /** @testdox It should be possible to create an exception for a route with no callback */
    public function test_missing_callback_exception(): void {
        $route = new Route('GET', 'route');
        $route->namespace('namespace');

        $exception = Route_Exception::callback_not_defined($route);
        $this->assertInstanceOf(Route_Exception::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals(102, $exception->getCode());
        $this->assertEquals('Callback not defined for [GET] NAMESPACE/ROUTE', $exception->getMessage());
        
        // With no namespace supplied
        $route2 = new Route('PUT', 'no-ns');
        $exception2 = Route_Exception::callback_not_defined($route2);
        $this->assertEquals('Callback not defined for [PUT] _MISSING_NAMESPACE_/NO-NS', $exception2->getMessage());

    }

    /** @testdox It should be possible to create and exception for a route with an invlid method */
    public function test_invalid_method(): void {
        $route = new Route('INVALID', 'route');
        $exception = Route_Exception::invalid_http_method($route);

        $this->assertInstanceOf(Route_Exception::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
        $this->assertEquals(103, $exception->getCode());
        $this->assertEquals('INVALID is a none supported HTTP Mehtod.', $exception->getMessage());


    }
}