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

use Exception;
use WP_UnitTestCase;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Route\Argument\Array_Type;
use PinkCrab\Route\Argument\Attribute\Children;

class Test_Children_Trait extends WP_UnitTestCase {

	/** @testdox Trying to use the Children trait on a none argument class, should result in an exception. */
	public function test_throws_exception_if_used_on_not_argument_class(): void {
		$var = new class(){
			use Children;
		};

		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Only classes that extend Argument can create children types' );
		$this->expectExceptionCode( '300' );

		Objects::invoke_method( $var, 'create_child', array( 'foo', 'bar' ) );
	}

    /** @testdox It should not be possible to request a type which is not String, Integer, Number, Array, Boolean, Null or Object. An exception will be thrown attemping others. */
    public function test_throws_exception_if_none_valid_type_passed(): void
    {
        $arg = new Array_Type('key');
		
        $this->expectException( Exception::class );
		$this->expectExceptionMessage( 'bar is not a valid argument type.' );
		$this->expectExceptionCode( '301' );
        
        Objects::invoke_method( $arg, 'create_child', array( 'foo', 'bar' ) );

    }

}
