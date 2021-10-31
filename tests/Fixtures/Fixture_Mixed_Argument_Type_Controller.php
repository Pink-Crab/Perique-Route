<?php

declare(strict_types=1);

/**
 * Base class for Rest Integration tests.
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
 * @since 1.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Route
 */

namespace PinkCrab\Route\Tests\Fixtures;

use PinkCrab\Route\Argument\String_Type;
use PinkCrab\Route\Registration_Middleware\Route_Controller;
use PinkCrab\Route\Route\Route_Group;

use PinkCrab\Route\Route_Factory;
use WP_HTTP_Response;

class Fixture_Mixed_Argument_Type_Controller extends Route_Controller {

	/**
	  * The namespace for this controllers routes
	  *
	  * @required
	  * @var string
	  */
	protected $namespace = 'pinkcrab/v3';

	/**
	 * Method defined to register all routes.
	 *
	 * @param Route_Factory $factory
	 * @return array<Route|Route_Group>
	 */
	protected function define_routes( Route_Factory $factory ): array {
		return array(

			$factory->get(
				'mixed_schema(?P<string>[\d]+)',
				'__return_true'
			)->argument(
				String_Type::on( 'string' )
					->description( 'A string value' )
					->min_length( 2 )
					->max_length( 10 )
					->pattern( '#[0-9]+' )
					->default( '0123456' )
					->validation( 'is_numeric' )
			)->argument(
				String_Type::on( 'string' )
					->description( 'A string value' )
					->min_length( 2 )
					->max_length( 10 )
					->pattern( '#[0-9]+' )
					->default( '0123456' )
					->validation( 'is_numeric' )
			),

		);
	}
}
