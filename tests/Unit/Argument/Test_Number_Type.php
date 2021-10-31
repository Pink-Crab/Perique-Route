<?php

declare(strict_types=1);

/**
 * Unit Tests for the Number Type Argument.
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
use PinkCrab\Route\Argument\Number_Type;

class Test_Number_Type extends WP_UnitTestCase {

	/** @testdox When creating a string type, the argument type should be preset.  */
	public function test_sets_number_type(): void {
		$arg = Number_Type::on( 'test' );
		$this->assertEquals( 'number', $arg->get_type() );
	}

	/** @testdox It should be possible to set and get the minimum for a number argument. */
	public function test_minimum(): void {
		$arg = Number_Type::on( 'test' );
		$arg->minimum( 12 );
		$this->assertEquals( 12, $arg->get_minimum() );
	}

	/** @testdox It should be possible to set and get the maximum for a number argument. */
	public function test_maximum(): void {
		$arg = Number_Type::on( 'test' );
		$arg->maximum( 12 );
		$this->assertEquals( 12, $arg->get_maximum() );
	}

	/** @testdox It should be possible to set and get the multiple_of for a number argument. */
	public function test_multiple_of():void {
		$arg = Number_Type::on( 'test' );
		$arg->multiple_of( 0.1 );
		$this->assertEquals( 0.1, $arg->get_multiple_of() );

		$arg->multiple_of( 2 );
		$this->assertEquals( 2, $arg->get_multiple_of() );
	}
}
