<?php

declare(strict_types=1);

/**
 * Abstract test case for the type parsers.
 *
 * Runs mostly shared functionality
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

namespace PinkCrab\Route\Tests\Unit\Argument\Parser;

use WP_UnitTestCase;
use PinkCrab\Route\Argument\Argument;
use PinkCrab\Route\Argument\String_Type;
use PinkCrab\Route\Argument\Argument_Parser;

abstract class Abstract_Parser_Testcase extends WP_UnitTestCase {

	abstract public function type_class(): string;
	abstract public function type_name(): string;

	/** @testdox When parsing an argument, the key and type must be the bar minimum that is always set. */
	public function test_key_with_type() {
		$expected = array(
			'arg-name' => array( 'type' => $this->type_name() ),
		);

		$model = $this->type_class()::on( 'arg-name' );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

		/** @testdox It should be possible to set the types of an argument. */
	public function test_setting_type_with_array(): void {

		// Avoid unions of same type.
		$union_type = $this->type_name() === 'null'
			? 'boolean' : 'null';

		$expected = array(
			'arg-name' => array(
				'type' => array( $this->type_name(), $union_type ),
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->type( array( $this->type_name(), $union_type ) );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox It should be possible to set the types of an argument. */
	public function test_setting_union_type(): void {

		// Avoid unions of same type.
		$union_type = $this->type_name() === 'boolean'
			? 'null' : 'boolean';

		$expected = array(
			'arg-name' => array(
				'type' => array( $this->type_name(), $union_type ),
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->union_with_type( $union_type );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the format should be listed if defined. */
	public function test_format(): void {
		$expected = array(
			'arg-name' => array(
				'type'   => $this->type_name(),
				'format' => 'url',
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->format( Argument::FORMAT_URL );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the description should be listed if defined. */
	public function test_description(): void {
		$expected = array(
			'arg-name' => array(
				'type'        => $this->type_name(),
				'description' => 'some description',
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->description( 'some description' );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the name should be listed if defined. */
	public function test_name(): void {
		$expected = array(
			'arg-name' => array(
				'type' => $this->type_name(),
				'name' => 'some name',
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->name( 'some name' );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the default should be listed if defined. */
	public function test_default(): void {
		$expected = array(
			'arg-name' => array(
				'type'    => $this->type_name(),
				'default' => 'some default',
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->default( 'some default' );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the required should be listed if defined. */
	public function test_required(): void {
		$expected = array(
			'arg-name' => array(
				'type'     => $this->type_name(),
				'required' => false,
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->required( false );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the expected should be listed if defined. */
	public function test_expected(): void {
		$expected = array(
			'arg-name' => array(
				'type' => $this->type_name(),
				'enum' => array( 'one', 'two' ),
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->expected( 'one', 'two' );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the sanitize should be listed if defined. */
	public function test_sanitize(): void {
		$callback = function( ...$e ) {
			return $e;
		};

		$expected = array(
			'arg-name' => array(
				'sanitize_callback' => $callback,
				'type'              => $this->type_name(),
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->sanitization( $callback );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing the argument, the validation should be listed if defined. */
	public function test_validation(): void {
		$callback = function( ...$e ) {
			return $e;
		};

		$expected = array(
			'arg-name' => array(
				'validate_callback' => $callback,
				'type'              => $this->type_name(),
			),
		);

		$model = $this->type_class()::on( 'arg-name' )
			->validation( $callback );

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

    /** @testdox It should be possible to use a simple static method to construct and export as an array in WP Rest Schema format. */
	public function test_static_to_array_shortcut(): void {
		$model = $this->type_class()::on( 'arg-name' )
			->expected( 'one', 'two' );

		$this->assertSame(
			( new Argument_Parser( $model ) )->to_array(),
			Argument_Parser::as_array( $model )
		);
	}

}
