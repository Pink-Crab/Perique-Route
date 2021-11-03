<?php

declare(strict_types=1);

/**
 * Unit Tests for the String Type Argument.
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
use PinkCrab\Route\Argument\Null_Type;
use PinkCrab\Route\Argument\Array_Type;
use PinkCrab\Route\Argument\Number_Type;
use PinkCrab\Route\Argument\Object_Type;
use PinkCrab\Route\Argument\String_Type;
use PinkCrab\Route\Argument\Boolean_Type;
use PinkCrab\Route\Argument\Integer_Type;

class Test_Array_Type extends WP_UnitTestCase {

	/** @testdox When creating a array type, the argument type should be preset.  */
	public function test_sets_Array_Type(): void {
		$arg = Array_Type::on( 'test' );
		$this->assertEquals( 'array', $arg->get_type() );
	}

	/** @testdox It should be possible to set and get the min items for an array argument. */
	public function test_min_items(): void {
		$arg = Array_Type::on( 'test' );
		$arg->min_items( 12 );
		$this->assertEquals( 12, $arg->get_min_items() );
	}

	/** @testdox It should be possible to set and get the max items for an array argument. */
	public function test_max_items(): void {
		$arg = Array_Type::on( 'test' );
		$arg->max_items( 12 );
		$this->assertEquals( 12, $arg->get_max_items() );
	}

	/** @testdox It should be possible to set and get if array can only contain unique items for an array argument. */
	public function test_unique_items():void {
		$arg = Array_Type::on( 'test' );
		// Sets true if not value passed
		$arg->unique_items();
		$this->assertTrue( $arg->get_unique_items() );

		$arg->unique_items( false );
		$this->assertFalse( $arg->get_unique_items() );

		$arg->unique_items( true );
		$this->assertTrue( $arg->get_unique_items() );
	}

	/** @testdox When adding a type to an array items, a unique key should be created for each type definition*/
	public function test_unique_item_type_key() {
		$arg = Array_Type::on( 'some_parent_key' );
		$arg->string_item();
		$arg->string_item(
			function( String_Type $arg ): String_Type {
				return $arg->min_length( 2 );
			}
		);

		$item1 = $arg->get_items()[0];
		$item2 = $arg->get_items()[1];

		$this->assertEquals( 'some_parent_key_item_type_0', $item1->get_key() );
		$this->assertEquals( 'some_parent_key_item_type_1', $item2->get_key() );

		$this->assertEquals( 'string', $item1->get_type() );
		$this->assertEquals( 'string', $item2->get_type() );
	}

	/** @testdox It should be possible to set a string as an item type. */
	public function test_add_string_item_type(): void {
		$arg = Array_Type::on( 'test' );
		$arg->string_item(
			function( $e ) {
				$this->assertInstanceOf( String_Type::class, $e );
				return $e;
			}
		);

		$this->assertIsArray( $arg->get_items() );
		$this->assertCount( 1, $arg->get_items() );
		$type = $arg->get_items()[0];
		$this->assertEquals( 'string', $type->get_type() );
	}

	/** @testdox It should be possible to set a number as an item type. */
	public function test_add_number_item_type(): void {
		$arg = Array_Type::on( 'test' );
		$arg->number_item(
			function( $e ) {
				$this->assertInstanceOf( Number_Type::class, $e );
				return $e;
			}
		);

		$this->assertIsArray( $arg->get_items() );
		$this->assertCount( 1, $arg->get_items() );
		$type = $arg->get_items()[0];
		$this->assertEquals( 'number', $type->get_type() );
	}

	/** @testdox It should be possible to set a integer as an item type. */
	public function test_add_integer_item_type(): void {
		$arg = Array_Type::on( 'test' );
		$arg->integer_item(
			function( $e ) {
				$this->assertInstanceOf( Integer_Type::class, $e );
				return $e;
			}
		);

		$this->assertIsArray( $arg->get_items() );
		$this->assertCount( 1, $arg->get_items() );
		$type = $arg->get_items()[0];
		$this->assertEquals( 'integer', $type->get_type() );
	}

	/** @testdox It should be possible to set a boolean as an item type. */
	public function test_add_boolean_item_type(): void {
		$arg = Array_Type::on( 'test' );
		$arg->boolean_item(
			function( $e ) {
				$this->assertInstanceOf( Boolean_Type::class, $e );
				return $e;
			}
		);

		$this->assertIsArray( $arg->get_items() );
		$this->assertCount( 1, $arg->get_items() );
		$type = $arg->get_items()[0];
		$this->assertEquals( 'boolean', $type->get_type() );
	}

	/** @testdox It should be possible to set a object as an item type. */
	public function test_add_object_item_type(): void {
		$arg = Array_Type::on( 'test' );
		$arg->object_item(
			function( $e ) {
				$this->assertInstanceOf( Object_Type::class, $e );
				return $e;
			}
		);

		$this->assertIsArray( $arg->get_items() );
		$this->assertCount( 1, $arg->get_items() );
		$type = $arg->get_items()[0];
		$this->assertEquals( 'object', $type->get_type() );
	}

	/** @testdox It should be possible to set a null as an item type. */
	public function test_add_null_item_type(): void {
		$arg = Array_Type::on( 'test' );
		$arg->null_item(
			function( $e ) {
				$this->assertInstanceOf( Null_Type::class, $e );
				return $e;
			}
		);

		$this->assertIsArray( $arg->get_items() );
		$this->assertCount( 1, $arg->get_items() );
		$type = $arg->get_items()[0];
		$this->assertEquals( 'null', $type->get_type() );
	}

	/** @testdox It should be possible to set a array as an item type. */
	public function test_add_array_item_type(): void {
		$arg = Array_Type::on( 'test' );
		$arg->array_item(
			function( $e ) {
				$this->assertInstanceOf( Array_Type::class, $e );
				return $e;
			}
		);

		$this->assertIsArray( $arg->get_items() );
		$this->assertCount( 1, $arg->get_items() );
		$type = $arg->get_items()[0];
		$this->assertEquals( 'array', $type->get_type() );
	}

	/** @testdox It should be possible to set if any, one of all of the items are present. */
	public function test_can_set_element_relationship(): void {
		$arg = Array_Type::on( 'some_key' );

		// allOf should be the default.
		$this->assertEquals( 'allOf', $arg->get_relationship() );

		$arg->one_of();
		$this->assertEquals( 'oneOf', $arg->get_relationship() );

		$arg->any_of();
		$this->assertEquals( 'anyOf', $arg->get_relationship() );

		$arg->all_of();
		$this->assertEquals( 'allOf', $arg->get_relationship() );
	}

	/** @testdox It should be possible to check if an array type has items defined. */
	public function test_has_items(): void {
		$arg = Array_Type::on( 'some_key' );
		$this->assertFalse( $arg->has_items() );

		$arg->string_item();
		$this->assertTrue( $arg->has_items() );
	}

	/** @testdox The items attribute should only be set when an item is added, the property should not exist before hand. */
	public function test_items_set_in_attributes(): void {
		$arg = Array_Type::on( 'some_key' );
		$this->assertArrayNotHasKey( 'items', $arg->get_attributes() );

		$arg->string_item();
		$this->assertArrayHasKey( 'items', $arg->get_attributes() );
	}

	/** @testdox It should be possible to get a count of how many items are defined with an array argument */
	public function test_can_count_items(): void
	{
		$arg = Array_Type::on( 'some_key' );
		$this->assertEquals(0, $arg->item_count());
		
		$arg->string_item();
		$this->assertEquals(1, $arg->item_count());
	}


}
