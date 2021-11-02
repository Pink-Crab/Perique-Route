<?php

declare(strict_types=1);

/**
 * Unit Tests for the string type parser
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
use PinkCrab\Route\Argument\String_Type;
use PinkCrab\Route\Argument\Argument_Parser;
use PinkCrab\Route\Tests\Unit\Argument\Parser\Abstract_Parser_Testcase;

class Test_String_Type_Parser extends Abstract_Parser_Testcase {

	public function type_class(): string {
		return String_Type::class;
	}

	public function type_name(): string {
		return 'string';
	}

	/** @testdox When parsing a string argument, it should be possible to set the min length.,  */
	public function test_string_min_length(): void {
		$expected = array(
			'string-arg' => array(
				'type'      => 'string',
				'minLength' => 4,
			),
		);

		$model = String_Type::on( 'string-arg' )->min_length( 4 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

    /** @testdox When parsing a string argument, it should be possible to set the max length.,  */
	public function test_string_max_length(): void {
		$expected = array(
			'string-arg' => array(
				'type'      => 'string',
				'maxLength' => 42,
			),
		);

		$model = String_Type::on( 'string-arg' )->max_length( 42 );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}

    /** @testdox When parsing a string argument, it should be possible to set the max length.,  */
	public function test_string_pattern(): void {
		$expected = array(
			'string-arg' => array(
				'type'      => 'string',
				'pattern' => '#[0-9]+',
			),
		);

		$model = String_Type::on( 'string-arg' )->pattern( '#[0-9]+' );
		$this->assertSame(
			$expected,
			( new Argument_Parser( $model ) )->to_array()
		);
	}
}
