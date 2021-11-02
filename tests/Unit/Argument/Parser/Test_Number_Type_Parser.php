<?php

declare(strict_types=1);

/**
 * Unit Tests for the number type parser
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
use PinkCrab\Route\Argument\Number_Type;
use PinkCrab\Route\Argument\Argument_Parser;
use PinkCrab\Route\Tests\Unit\Argument\Parser\Abstract_Parser_Testcase;

class Test_Number_Type_Parser extends Abstract_Parser_Testcase {

	public function type_class(): string {
		return Number_Type::class;
	}

	public function type_name(): string {
		return 'number';
	}

	/** @testdox When parsing a number argument, it should be possible to set the min value.,  */
	public function test_number_min_value(): void {
		$expected = array(
			'number-arg' => array(
				'type'      => 'number',
				'minimum' => 4.23,
			),
		);

		$model = Number_Type::on( 'number-arg' )->minimum( 4.23 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing a number argument, setting exclusiveMinimum should only happen when setting the minimum */
	public function test_exclusive_minimum_only_set_when_minimum_set(): void
	{
		$expected_without = array(
			'number-arg' => array(
				'type'      => 'number',
			),
		);

		$model = Number_Type::on( 'number-arg' )->exclusive_minimum();
		$this->assertSame(
			$expected_without,
			( new Argument_Parser( $model ) )->to_array()
		);

		$expected_with = array(
			'number-arg' => array(
				'type'      => 'number',
				'minimum' => 45.69,
				'exclusiveMinimum' => true
			),
		);

		$model->minimum( 45.69 );
		$this->assertSame(
			$expected_with,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

	/** @testdox When parsing a number argument, setting exclusiveMaximum should only happen when setting the maximum */
	public function test_exclusive_maximum_only_set_when_maximum_set(): void
	{
		$expected_without = array(
			'number-arg' => array(
				'type'      => 'number',
			),
		);

		$model = Number_Type::on( 'number-arg' )->exclusive_maximum();
		$this->assertSame(
			$expected_without,
			( new Argument_Parser( $model ) )->to_array()
		);

		$expected_with = array(
			'number-arg' => array(
				'type'      => 'number',
				'maximum' => 546789.546,
				'exclusiveMaximum' => true
			),
		);

		$model->maximum( 546789.546 );
		$this->assertSame(
			$expected_with,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

    /** @testdox When parsing a number argument, it should be possible to set the max value.,  */
	public function test_number_max_value(): void {
		$expected = array(
			'number-arg' => array(
				'type'      => 'number',
				'maximum' => 99.98,
			),
		);

		$model = Number_Type::on( 'number-arg' )->maximum( 99.98 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

    /** @testdox When parsing a number argument, it should be possible to set the multipleOf value,  */
	public function test_number_pattern(): void {
		$expected = array(
			'number-arg' => array(
				'type'      => 'number',
				'multipleOf' => 3.12,
			),
		);

		$model = Number_Type::on( 'number-arg' )->multiple_of( 3.12 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}
}
