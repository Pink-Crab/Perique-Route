<?php

declare(strict_types=1);

/**
 * Tests for the Utils helpers class
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

use WP_UnitTestCase;
use PinkCrab\Route\Registration_Middleware\Route_Middleware;
use PinkCrab\Route\Utils;
use stdClass;

class Test_Utils extends WP_UnitTestCase {

    /** @testdox It should be possible to combine callables to create an ALL_TRUE function where each callable MUST return true */
    public function test_compose_conditional_all_true(): void
    {
        $all_true = Utils::compose_conditional_all_true(
            'is_string', 'is_numeric'
        );

        $this->assertFalse($all_true(1));
        $this->assertFalse($all_true('NUMBER'));
        $this->assertTrue($all_true('12'));
    }

    /** @testdox It should be possible to combine callables to create an ANY_TRUE function where any callable CAN return true */
    public function test_compose_any_true(): void
    {
        $any_true = Utils::compose_conditional_any_true(
            'is_string', 'is_float', 'is_array'
        );

        $this->assertTrue($any_true('string'));
        $this->assertTrue($any_true(12.5));
        $this->assertTrue($any_true(['arrray']));
        $this->assertFalse($any_true(1));
        $this->assertFalse($any_true(new stdClass));
    }

    /** @testdox It should be possible to combine callables to create a function where each callable MUST return true */
    public function test_compose_piped_callable(): void
    {

        $piped = Utils::compose_piped_callable(
            'strtoupper', function($e){
                return $e.$e;
            }
        );

        $this->assertEquals('ERER', $piped('er'));
    }

    /** @testdox It should be possible to get a populated instance of the Route Middleware using a helper. */
    public function test_middleware_provider(): void
    {
        $middleware = Utils::middleware_provider();
        $this->assertInstanceOf(Route_Middleware::class, $middleware);
    }
}