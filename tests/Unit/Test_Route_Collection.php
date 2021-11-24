<?php

declare(strict_types=1);

/**
 * Tests for the Route Collection
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
 *

 */

namespace PinkCrab\Route\Tests\Unit;

use stdClass;
use WP_UnitTestCase;
use PinkCrab\Route\Route\Route;
use PinkCrab\Route\Route_Collection;
use PinkCrab\Route\Route\Route_Group;

class Test_Route_Collection extends WP_UnitTestCase {

    /** @testdox It should only be possible to pass Route and Route_Groups to a Route_Collection  */
    public function test_only_accepts_routes(): void
    {
        $route1 = new Route('GET', 'route');
        $route2 = new Route('POST', 'route');
        $group = new Route_Group('acme', 'route');

        $collection = new Route_Collection([$route1, new stdClass]);
        $collection->push($route2);
        $collection->add_route($group);
        $collection->push(new stdClass);

        $this->assertCount(3, $collection);
        $this->assertContains($route1, $collection->to_array());
        $this->assertContains($route2, $collection->to_array());
        $this->assertContains($group, $collection->to_array());
    }
}