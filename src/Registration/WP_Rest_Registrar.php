<?php

declare(strict_types=1);

/**
 * Registers routes through WP API from Route mooels.
 *
 * @package PinkCrab\Route\Route
 * @author Glynn Quelch glynn@pinkcrab.co.uk
 * @since 0.0.1
 */

namespace PinkCrab\Route\Registration;

use PinkCrab\Route\Route\Route;


class WP_Rest_Registrar {
    
    public function create_callback(Route $route): callable
    {
        return function() use ( $route ): void {

            $model = $this->map_to_wp_rest($route);

            dump($model);

            echo 'TEST';
        };
    }

    public function map_to_wp_rest(Route $route): WP_Rest_Route
    {
        $wp_rest = new WP_Rest_Route();
        $wp_rest->namespace = $route->get_namespace();
        $wp_rest->route = $route->get_route();
        $wp_rest->args = $this->parse_options($route);
        return $wp_rest;
    }

    /**
     * Parsed the args array used to register.
     *
     * @param Route $route
     * @return array
     */
    protected function parse_options(Route $route): array
    {
        $options = [];
        $options['methods'] = $route->get_method();
        $options['callback'] = $route->get_callback();
        $options['permission_callback'] = $this->compose_permission_callback($route);
        $options['args'] = $this->parse_args($route);

        return $options;
    }

    /**
     * Parsed the args array of options.
     *
     * @param Route $route
     * @return array
     */
    public function parse_args(Route $route): array
    {
        $args = [];
        foreach ($route->get_arguments() as $argument) {
            $arg = [];
            if($argument->get_validation()){
                $arg['validation'] = $argument->get_validation();
            }

            if($argument->get_sanitization()){
                $arg['sanitization'] = $argument->get_sanitization();
            }

            if(! is_null($argument->get_type())){
                $arg['type'] = $argument->get_type();
            }

            if(! is_null($argument->get_required())){
                $arg['required'] = $argument->get_required();
            }

            if('' !== $argument->get_description()){
                $arg['description'] = $argument->get_description(); 
            }

            if(! is_null($argument->get_format())){
                $arg['format'] = $argument->get_format();
            }

            if(! is_null($argument->get_expected())){
                $arg['expected'] = $argument->get_expected();
            }

            $args[$argument->get_key()] = $arg;
        }
        dump($route->get_arguments(), $args);
        return $args;
    }

    /**
     * Compose the permission callback function for the route.
     *
     * @param Route $oute
     * @return callable
     */
    public function compose_permission_callback(Route $route): callable
    {
        $callbacks = $route->get_authentication();
        
        // If we have no callback defined, use return true.
        if(count($callbacks) === 0){
            return '__return_true';
        }

        // If we only have 1, return as is.
        if(count($callbacks) === 1){
            return $callbacks[0];
        }

        return $this->compose_conditional_all_true(...$callbacks);
    }

    /**
     * Creates a single conditional function from many.
     * The passed value will be passed through each callbale,
     * if any result is not truthy, will return false.
     *
     * @param callable(mixed):bool) ...$callables
     * @return callable(mixed):bool
     */
    public function compose_conditional_all_true(callable ...$callables): callable
    {
        return function($value) use ($callables): bool {
            foreach ($callables as $callable) {
               $result = (bool) $callable($value);
               if(true !== $result){
                return false;
               }
            }
            return true;
        };
    }

    /**
     * Creates a single conditional function from many.
     * The passed value will be passed through each callbale,
     * if any result is truthy, will return true, only false as a all failed..
     *
     * @param callable(mixed):bool) ...$callables
     * @return callable(mixed):bool
     */
    public function compose_conditional_any_true(callable ...$callable): callable
    {
        return function($value) use ($callables): bool {
            foreach ($callables as $callable) {
               $result = (bool) $callable($value);
               if(true === $result){
                return true;
               }
            }
            return false;
        };
    }

    /**
     * Creates a single function, which pipes trough each callable in the order passed.
     *
     * @param callable(mixed):mixed ...$callables
     * @return callable(mixed):mixed
     */
    public function compose_piped_callable(callable ...$callables): callable
    {
        return function($value) use ($callables){
            foreach ($callables as $callable ) {
                $value = $callable($value);
            }
            return $value;
        };
    }
}