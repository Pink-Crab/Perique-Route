<?php

declare(strict_types=1);

/**
 * Tests a Route Argument model
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
use PinkCrab\Route\Route_Argument;

class Test_Route_Argument extends WP_UnitTestCase {

	/** @testdox It should be possible to create an argument using the static constructor without the config callable passed*/
	public function test_static_constructor_without_config(): void {
		$argument = Route_Argument::on( 'id' )
            ->validation( 'is_string' )
            ->sanitization( 'esc_html' );

        $this->assertInstanceOf( Route_Argument::class, $argument );
		$this->assertEquals( 'id', $argument->get_key() );
		$this->assertEquals( 'is_string', $argument->get_validation() );
		$this->assertEquals( 'esc_html', $argument->get_sanitization() );
	}

	/** @testdox It should be possible to create an argument using the static constructor with the config callable passed*/
	public function test_can_use_static_constructor_with_config_param(): void {
		$argument = Route_Argument::on(
			'id',
			function( Route_Argument $argument ): Route_Argument {
				return $argument
					->validation( 'is_string' )
					->sanitization( 'esc_html' );
			}
		);

		$this->assertInstanceOf( Route_Argument::class, $argument );
		$this->assertEquals( 'id', $argument->get_key() );
		$this->assertEquals( 'is_string', $argument->get_validation() );
		$this->assertEquals( 'esc_html', $argument->get_sanitization() );
	}

	/** @testdox It should be possible to set and get the validation callback. */
	public function test_set_get_validation(): void {
		$argument = new Route_Argument( 'id' );
		$argument->validation( 'is_float' );

		$this->assertEquals( 'is_float', $argument->get_validation() );
	}

	/** @testdox It should be possible to set and get the sanitization callback. */
	public function test_set_get_sanitization(): void {
		$argument = new Route_Argument( 'id' );
		$argument->sanitization( 'esc_attr' );

		$this->assertEquals( 'esc_attr', $argument->get_sanitization() );
	}

	public function test_required_is_set_false_by_default(Type $var = null)
	{
		# code...
	}
}
