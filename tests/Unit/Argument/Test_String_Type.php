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
use PinkCrab\Route\Argument\String_Type;

class Test_String_Type extends WP_UnitTestCase {

	/** @testdox When creating a string type, the argument type should be preset.  */
	public function test_sets_string_type(): void {
		$arg = String_Type::on( 'test' );
		$this->assertEquals( 'string', $arg->get_type() );
	}

	/** @testdox It should be possible to set and get the min length for a string argument. */
	public function test_min_length(): void {
		$arg = String_Type::on( 'test' );
		$arg->min_length( 12 );
		$this->assertEquals( 12, $arg->get_min_length() );
	}

	/** @testdox It should be possible to set and get the max length for a string argument. */
	public function test_max_length(): void {
		$arg = String_Type::on( 'test' );
		$arg->max_length( 12 );
		$this->assertEquals( 12, $arg->get_max_length() );
	}

	/** @testdox It should be possible to set and get the pattern for a string argument. */
	public function test_pattern():void {
		$arg = String_Type::on( 'test' );
		$arg->pattern( '#ca[kf]e#' );
		$this->assertEquals( '#ca[kf]e#', $arg->get_pattern() );
	}
}
