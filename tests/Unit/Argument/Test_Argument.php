<?php

declare(strict_types=1);

/**
 * Unit Tests for the base Argument.
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
 * @since 1.1.0
 *
 */

namespace PinkCrab\Route\Tests\Unit\Argument;

use WP_UnitTestCase;
use PinkCrab\Route\Argument\Argument;
use PinkCrab\Route\Argument\String_Type;

class Test_Argument extends WP_UnitTestCase {


	/** @testdox It should be possible to create an argument using the static constructor without the config callable passed*/
	public function test_static_constructor_without_config(): void {
		$argument = Argument::on( 'id' )
				->validation( 'is_string' )
				->sanitization( 'esc_html' );

			$this->assertInstanceOf( Argument::class, $argument );
		$this->assertEquals( 'id', $argument->get_key() );
		$this->assertEquals( 'is_string', $argument->get_validation() );
		$this->assertEquals( 'esc_html', $argument->get_sanitization() );
	}

	/** @testdox It should be possible to create an argument using the static constructor with the config callable passed*/
	public function test_can_use_static_constructor_with_config_param(): void {
		$argument = Argument::on(
			'id',
			function( Argument $argument ): Argument {
				return $argument
					->validation( 'is_string' )
					->sanitization( 'esc_html' );
			}
		);

		$this->assertInstanceOf( Argument::class, $argument );
		$this->assertEquals( 'id', $argument->get_key() );
		$this->assertEquals( 'is_string', $argument->get_validation() );
		$this->assertEquals( 'esc_html', $argument->get_sanitization() );
	}

	/** @testdox It should be possible to set and get the validation callback. */
	public function test_set_get_validation(): void {
		$argument = new Argument( 'id' );
		$argument->validation( 'is_float' );

		$this->assertEquals( 'is_float', $argument->get_validation() );
	}

	/** @testdox It should be possible to set and get the sanitization callback. */
	public function test_set_get_sanitization(): void {
		$argument = new Argument( 'id' );
		$argument->sanitization( 'esc_attr' );

		$this->assertEquals( 'esc_attr', $argument->get_sanitization() );
	}

	/** @testdox By default an argument should have its required property set to false, but it should be possible to change that*/
	public function test_required_is_set_false_by_default(): void {
		$argument = new Argument( 'id' );
		$this->assertFalse( $argument->is_required() );

		// Set to true (without passing true)
		$argument->required();
		$this->assertTrue( $argument->is_required() );

		// Set to false
		$argument->required( false );
		$this->assertFalse( $argument->is_required() );

		// Set to true (with passing true)
		$argument->required( true );
		$this->assertTrue( $argument->is_required() );
	}

	/** @testdox It should be possible to set the type of an arguments value and have access to class constants for easy setting */
	public function test_set_argument_type(): void {
		$argument = new Argument( 'id' );

		// Integer
		$argument->type( Argument::TYPE_INTEGER );
		$this->assertEquals( 'integer', $argument->get_type() );

		// Number
		$argument->type( Argument::TYPE_NUMBER );
		$this->assertEquals( 'number', $argument->get_type() );

		// Bool
		$argument->type( Argument::TYPE_BOOLEAN );
		$this->assertEquals( 'boolean', $argument->get_type() );

		// String
		$argument->type( Argument::TYPE_STRING );
		$this->assertEquals( 'string', $argument->get_type() );
	}

	/** @testdox It should be possible to set, get and check if an argument has a default applied. */
	public function test_argument_defaults(): void {
		$argument = new Argument( 'id' );

		// Should have no default
		$this->assertFalse( $argument->has_default() );
		$this->assertNull( $argument->get_default() );

		// With a default.
		$argument->default( 'DEF' );
		$this->assertTrue( $argument->has_default() );
		$this->assertEquals( 'DEF', $argument->get_default() );
	}

	/** @testdox It should be possible to set a description to an argument. */
	public function test_argument_description(): void {
		$argument = new Argument( 'id' );

		$this->assertEquals( '', $argument->get_description() );

		// With description.
		$argument->description( 'DESC' );
		$this->assertEquals( 'DESC', $argument->get_description() );
	}

	/** @testdox It should be possible to set the format for an argument and have access to constants for accepted formats. */
	public function test_argument_format(): void {
		$argument = new Argument( 'id' );

		$this->assertNull( $argument->get_format() );

		// IP
		$argument->format( Argument::FORMAT_IP );
		$this->assertEquals( 'ip', $argument->get_format() );

		// Datetime
		$argument->format( Argument::FORMAT_DATE_TIME );
		$this->assertEquals( 'date-time', $argument->get_format() );

		// Email
		$argument->format( Argument::FORMAT_EMAIL );
		$this->assertEquals( 'email', $argument->get_format() );

		// URL
		$argument->format( Argument::FORMAT_URL );
		$this->assertEquals( 'url', $argument->get_format() );
	}

	/** @testdox It should be possible to set the expected values by pushing them either singulr or in a group. */
	public function test_expected_enum(): void {
		$argument = new Argument( 'id' );

		$this->assertNull( $argument->get_expected() );

		// It should be possible to push values.
		$argument->expected( 'ONE' );
		$this->assertCount( 1, $argument->get_expected() );
		$this->assertContains( 'ONE', $argument->get_expected() );

		$argument->expected( 'TWO' );
		$this->assertCount( 2, $argument->get_expected() );
		$this->assertContains( 'ONE', $argument->get_expected() );
		$this->assertContains( 'TWO', $argument->get_expected() );

		$argument->expected( 'THREE', 'FOUR' );
		$this->assertCount( 4, $argument->get_expected() );
		$this->assertContains( 'ONE', $argument->get_expected() );
		$this->assertContains( 'TWO', $argument->get_expected() );
		$this->assertContains( 'THREE', $argument->get_expected() );
		$this->assertContains( 'FOUR', $argument->get_expected() );
	}

	/** @testdox It should be possible to allow multiple types. */
	public function test_allow_multiple_types(): void {
		$argument = new Argument( 'id' );
		$argument->type( array( 'string', 'number' ) );
		$this->assertIsArray( $argument->get_type() );
		$this->assertCount( 2, $argument->get_type() );
		$this->assertContains( 'string', $argument->get_type() );
		$this->assertContains( 'number', $argument->get_type() );
	}

	/** @testdox It should be possible to add additional types to create a union typed, argument. */
	public function test_allow_additional_types() {
		$argument = new Argument( 'id' );
		$argument->type( 'string' );
		$argument->union_with_type( 'number' );
		$argument->union_with_type( 'boolean' );

		$this->assertIsArray( $argument->get_type() );
		$this->assertCount( 3, $argument->get_type() );
		$this->assertContains( 'string', $argument->get_type() );
		$this->assertContains( 'number', $argument->get_type() );
		$this->assertContains( 'boolean', $argument->get_type() );
	}

	/** @testdox Only strings and arrays should be allowed as types. */
	public function test_only_string_and_array_as_argument_types(): void {

		$types    = array( 1, 1.2, new \stdClass, true, null );
		$argument = new Argument( 'id' );

		$caught = array();
		foreach ( $types as $type ) {
			try {
				$argument->type( $type );
			} catch ( \Throwable $th ) {
				$caught[] = $type;
			}
		}

		$this->assertCount( 5, $caught );
		$this->assertSame( $types, $caught );
	}

	/** @testdox It should be possible to give an argument a name and get the name. */
	public function test_name(): void {
		$argument = new Argument( 'id' );

		$this->assertNull( $argument->get_name() );

		$argument->name( 'name it' );
		$this->assertEquals( 'name it', $argument->get_name() );
	}

	/** @testdox It should be possible to set and get the arguments, attributes as arrays. */
	public function test_set_get_attributes(): void {
		$argument = new Argument( 'id' );

		$argument->set_attributes( array( 'key' => 'value' ) );
		$this->assertNotEmpty( $argument->get_attributes() );
		$this->assertArrayHasKey( 'key', $argument->get_attributes() );
		$this->assertContains( 'value', $argument->get_attributes() );
	}
}
