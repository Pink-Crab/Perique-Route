<?php

declare(strict_types=1);

/**
 * Unit Tests for WP_Route_Registrar
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

namespace PinkCrab\Route\Tests\Unit\Registration;

use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Route\Registration\WP_Rest_Registrar;
use PinkCrab\Route\Route\Argument;
use PinkCrab\Route\Route\Route;
use WP_UnitTestCase;

class Test_WP_Rest_Registrar extends WP_UnitTestCase {

    /** @testdox When registering a route, the args array for register_rest_route() should be compiled from the Route object. */
    public function test_can_parse_args(): void
    {
        $route = new Route('GET', 'test');
        $route->namespace('NS');
        $route->authentication('is_array');
        $route->callback('is_string');
        $route->argument(
            Argument::on('id')
                ->type(Argument::TYPE_STRING)
                ->sanitization('is_null')
                ->validation('is_object')
                ->required()
                ->default('bacon')
        );

        $registrar = new WP_Rest_Registrar();

        $wp_route = Objects::invoke_method($registrar,'parse_options',[$route]);
        dump($wp_route);
    }
}