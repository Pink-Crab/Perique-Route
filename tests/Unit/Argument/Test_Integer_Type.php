<?php

declare(strict_types=1);

/**
 * Unit Tests for the integer Type Argument.
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
use PinkCrab\Route\Argument\Integer_Type;

class Test_Integer_Type extends WP_UnitTestCase {

	/** @testdox When creating a integer type, the argument type should be preset.  */
	public function test_sets_Integer_Type(): void {
		$arg = Integer_Type::on( 'test' );
		$this->assertEquals( 'integer', $arg->get_type() );
	}

	/** @testdox It should be possible to set and get the minimum for a integer argument. */
	public function test_minimum(): void {
		$arg = Integer_Type::on( 'test' );
		$arg->minimum( 12 );
		$this->assertEquals( 12, $arg->get_minimum() );
	}

	/** @testdox It should be possible to set and get the maximum for a integer argument. */
	public function test_maximum(): void {
		$arg = Integer_Type::on( 'test' );
		$arg->maximum( 12 );
		$this->assertEquals( 12, $arg->get_maximum() );
	}

	/** @testdox It should be possible to set and get the multiple_of for a integer argument. */
	public function test_multiple_of():void {
		$arg = Integer_Type::on( 'test' );
		$arg->multiple_of( 0.1 );
		$this->assertEquals( 0.1, $arg->get_multiple_of() );

		$arg->multiple_of( 2 );
		$this->assertEquals( 2, $arg->get_multiple_of() );
	}

	/** @testdox It should be possible to set if the minimum value excludes the integer shown (1-4 exclusive would allow 2 & 3 only) */
	public function test_minimum_exclusive():void {
		$arg = Integer_Type::on( 'test' );
		$this->assertNull( $arg->get_exclusive_minimum() );

		$arg->exclusive_minimum();
		$this->assertTrue( $arg->get_exclusive_minimum() );
		$arg->exclusive_minimum( false );
		$this->assertFalse( $arg->get_exclusive_minimum() );
		$arg->exclusive_minimum( true );
		$this->assertTrue( $arg->get_exclusive_minimum() );
	}

	/** @testdox It should be possible to set if the maximum value excludes the integer shown (1-4 exclusive would allow 2 & 3 only) */
	public function test_maximum_exclusive():void {
		$arg = Integer_Type::on( 'test' );
		$this->assertNull( $arg->get_exclusive_maximum() );

		$arg->exclusive_maximum();
		$this->assertTrue( $arg->get_exclusive_maximum() );
		$arg->exclusive_maximum( false );
		$this->assertFalse( $arg->get_exclusive_maximum() );
		$arg->exclusive_maximum( true );
		$this->assertTrue( $arg->get_exclusive_maximum() );
	}
}
