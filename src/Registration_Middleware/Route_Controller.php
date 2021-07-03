<?php

declare(strict_types=1);

/**
 * Abstract Route Controller
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Registration_Middleware;

use PinkCrab\Route\Route_Exception;
use PinkCrab\Route\Route_Collection;
use PinkCrab\Route\Route_Factory;

abstract class Route_Controller {

    /**
     * The namespace for this controllers routes
     *
     * @required
     * @var string
     */
    protected $namespace;

    /**
     * Returns the controllers namspace
     *
     * @return string
     * @throws Route_Exception (code 101)
     */
    final private function get_namespace(): string
    {
        if(! is_string($this->namespace) || mb_strlen($this->namespace) === 0){
            throw Route_Exception::namespace_not_defined(get_class($this));
        }

        return $this->namespace;
    }

    /**
     * Returns a factory for this controller.
     *
     * @return Route_Factory
     * @throws Route_Exception (code 101)
     */
    final private function get_factory(): Route_Factory
    {
        return Route_Factory::for($this->get_namespace());
    }

    /**
     * Adds all routes defined to the passed route collection.
     *
     * @param Route_Collection $collection
     * @return Route_Collection
     */
    final public function get_route( Route_Collection $collection ): Route_Collection
    {
        $routes = $this->define_routes($this->get_factory());
        foreach ($routes as $route ) {
            $collection->add_route($route);
        }
        
        return $collection;
    }

    /**
     * Method defined to regiser all routes.
     *
     * @param Route_Factory $factory
     * @return array
     */
    abstract protected function define_routes(Route_Factory $factory): array;

}