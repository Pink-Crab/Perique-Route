<?php

declare(strict_types=1);

/**
 * Unit Tests for the integer type parser
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
use PinkCrab\Route\Argument\Integer_Type;
use PinkCrab\Route\Argument\Argument_Parser;
use PinkCrab\Route\Tests\Unit\Argument\Parser\Abstract_Parser_Testcase;

class Test_Integer_Type_Parser extends Abstract_Parser_Testcase {

	public function type_class(): string {
		return Integer_Type::class;
	}

	public function type_name(): string {
		return 'integer';
	}

	/** @testdox When parsing a integer argument, it should be possible to set the min value.,  */
	public function test_number_min_value(): void {
		$expected = array(
			'int-arg' => array(
				'type'      => 'integer',
				'minimum' => 5,
			),
		);

		$model = Integer_Type::on( 'int-arg' )->minimum( 5 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing a integer argument, setting exclusiveMinimum should only happen when setting the minimum */
	public function test_exclusive_minimum_only_set_when_minimum_set(): void
	{
		$expected_without = array(
			'int-arg' => array(
				'type'      => 'integer',
			),
		);

		$model = Integer_Type::on( 'int-arg' )->exclusive_minimum();
		$this->assertSame(
			$expected_without,
			( new Argument_Parser( $model ) )->to_array()
		);

		$expected_with = array(
			'int-arg' => array(
				'type'      => 'integer',
				'minimum' => 8,
				'exclusiveMinimum' => true
			),
		);

		$model->minimum( 8 );
		$this->assertSame(
			$expected_with,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing a integer argument, setting exclusiveMaximum should only happen when setting the maximum */
	public function test_exclusive_maximum_only_set_when_maximum_set(): void
	{
		$expected_without = array(
			'int-arg' => array(
				'type'      => 'integer',
			),
		);

		$model = Integer_Type::on( 'int-arg' )->exclusive_maximum();
		$this->assertSame(
			$expected_without,
			( new Argument_Parser( $model ) )->to_array()
		);

		$expected_with = array(
			'int-arg' => array(
				'type'      => 'integer',
				'maximum' => 897,
				'exclusiveMaximum' => true
			),
		);

		$model->maximum( 897 );
		$this->assertSame(
			$expected_with,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

    /** @testdox When parsing a integer argument, it should be possible to set the max value.,  */
	public function test_number_max_value(): void {
		$expected = array(
			'int-arg' => array(
				'type'      => 'integer',
				'maximum' => 798,
			),
		);

		$model = Integer_Type::on( 'int-arg' )->maximum( 798 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

    /** @testdox When parsing a integer argument, it should be possible to set the multipleOf value,  */
	public function test_number_pattern(): void {
		$expected = array(
			'int-arg' => array(
				'type'      => 'integer',
				'multipleOf' => 0.5,
			),
		);

		$model = Integer_Type::on( 'int-arg' )->multiple_of( 0.5 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}
}
