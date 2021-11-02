<?php

declare(strict_types=1);

/**
 * Unit Tests for the array type parser
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
use PinkCrab\Route\Argument\Array_Type;
use PinkCrab\Route\Argument\Argument_Parser;
use PinkCrab\Route\Tests\Unit\Argument\Parser\Abstract_Parser_Testcase;

class Test_Array_Type_Parser extends Abstract_Parser_Testcase {

	public function type_class(): string {
		return Array_Type::class;
	}

	public function type_name(): string {
		return 'array';
	}

	/** @testdox It should be possible to create an array type and set the scala type of the array contents. */
	public function test_single_depth_items() {
		$expected = array(
			'arg-name' => array(
				'type'  => 'array',
				'items' => array(
					'type' => 'string',
				),
			),
		);

		$model = Array_Type::on( 'arg-name' )
			->string_item();

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);

	}

	/**  @testdox It should be possible to allow multiple types of an arrays contents. */
	public function test_multiple_types(): void {
		$expected = array(
			'arg-name' => array(
				'type'  => 'array',
				'items' => array(
					array(
						'type' => 'string',
					),
					array(
						'type' => 'null',
					),
				),
			),
		);

		$model = Array_Type::on( 'arg-name' )
			->string_item()
			->null_item();

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox It should be possible to define an array that has an array as its items. */
	public function test_array_of_array(): void {
		$expected = array(
			'arg-name' => array(
				'type'  => 'array',
				'items' => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'string',
					),
				),

			),
		);

		$model = Array_Type::on( 'arg-name' )
			->array_item(
				function( Array_Type $e ): Array_Type {
					return $e->string_item();
				}
			);

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox It should be possible to deeply nest arrays as array items. */
	public function test_deep_nested_arrays(): void {
		$expected = array(
			'arg-name' => array(
				'type'  => 'array',
				'items' => array(
					'type'  => 'array',
					'name'  => '1st',
					'items' => array(
						'type'  => 'array',
						'name'  => '2nd',
						'items' => array(
							'type' => 'string',
						),
					),
				),
			),
		);

		$model = Array_Type::on( 'arg-name' )
			->array_item(
				function( Array_Type $first_generation ): Array_Type {
					return $first_generation
						->name( '1st' )
						->array_item(
							function ( Array_Type $second_generation ): Array_Type {
								return $second_generation
									->name( '2nd' )
									->string_item();
							}
						);
				}
			);

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/**  @testdox It should be possible to allow multiple types of an arrays contents, but only oneOf is needed*/
	public function test_multiple_types_one_of(): void {
		$expected = array(
			'arg-name' => array(
				'type'  => 'array',
				'items' => array(
					'oneOf' => array(
						array(
							'type' => 'string',
						),
						array(
							'type' => 'null',
						),
					),
				),
			),
		);

		$model = Array_Type::on( 'arg-name' )
			->string_item()
			->null_item()
			->one_of();

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/**  @testdox It should be possible to allow multiple types of an arrays contents, but only anyOf is needed*/
	public function test_multiple_types_any_of(): void {
		$expected = array(
			'arg-name' => array(
				'type'  => 'array',
				'items' => array(
					'anyOf' => array(
						array(
							'type' => 'string',
						),
						array(
							'type' => 'null',
						),
					),
				),
			),
		);

		$model = Array_Type::on( 'arg-name' )
			->string_item()
			->null_item()
			->any_of();

		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}
}
