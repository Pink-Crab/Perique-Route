# Perique - Route

....

![alt text](https://img.shields.io/badge/Current_Version-0.1.1-yellow.svg?style=flat " ") 
[![Open Source Love](https://badges.frapsoft.com/os/mit/mit.svg?v=102)]()
![](https://github.com/Pink-Crab/Perique-Route/workflows/GitHub_CI/badge.svg " ")
[![codecov](https://codecov.io/gh/Pink-Crab/Perique-Route/branch/master/graph/badge.svg?token=4yEceIaSFP)](https://codecov.io/gh/Pink-Crab/Perique-Route)

## Version 0.1.1 ##

****

## Why? ##

Registering WP Rest Routes can either be extremely simple or a frustrating wit the argument array format. The Perique Route library allows for a simpler way to register single routes and groupes.

****

## Setup ##

To install, you can use composer
```bash
$ composer install pinkcrab/perique-route
```

You will need to include the Registartion_Middleware to the App at boot. We have provided a static method that will handle the dependency injection.

```php
// @file: plugin.php

$app_factory->registration_middleware( PinkCrab\Route\Utils::middleware_provider() );
```
One you have the Route Middleware added to the registartion process, all classes which extend `Route_Controller` will now be processed and all routes defined will be registered.

To create a route controller, just extend `Route_Controller` and return an array of all routes and groups. The controllers are constructed with the DI Container, so all dependencies can be passed. 

```php

class Some_Route extends Route_Controller {

    // @required
    protected $namespace = 'acme/v1';

    // @optional, access to constructor, so allows for full DI.
    protected $some_service;
    protected function __construct(Service $service){
        $this->some_service = $service;
    }

    // @required
    protected function define_routes( Route_Factory $factory): array {
        return [
            // Factory allows for get,post,delete,patch,put requests.
            $factory->get('/users', [$this->some_service, 'some_callback_index' ]),
            $factory->delete('/users', [$this->some_service, 'some_callback_delete' ]),
            
            // Create your groups using the group builder.
            $factory->group_builder('/users/(?P<id>\d+)', function( Route_Group $group) : Route_Group {
                // Define the GET method.
                $group->get([$this->some_service, 'some_other_get_method'])
                    ->argument( // Define the argument proprties as per WP API
                        Argument::on('id')
                            ->type(Argument::TYPE_STRING)
                            ->validate('is_numeric')
                            ->sanitization('absint')
                            ->required()
                    );

                // Define the DELETE method.
                $group->delete([$this->some_service, 'some_other_get_method'])
                    ->authentication('some_extra_check_for_delete');

                // Define route wide authentication (applied to both routes).
                $group->authentication([$this->some_service, 'check_api_key_in_header']);

                return $group;
            })
        ];
    }

}
```

Once you have your Route_Controller setup, its just a case of passing the class to the `reigstration` array and it will be loaded along with Perique.

```php
//file: config/registration.php
return [
    ...other classes
    Some_Route::class,
];
```

# Routes

Each route must be defined as part of a `Route Model`, these can either be created by hand or using the supplied `Route_Factory` (which is how the `Route_Controller` operates.)

## Route Model

A route model has 3 properties which must be defined, `$route`, `$callback` & `$namespace`. Route and Method are passed via the constructor, but namespace must be set manually. 

As per WP Api standards, all arguments in the route must be defined, this is all handled via the `Arguments` object and is explained in more detail below.

> All properties are defined as `protected` and should be handled via the supplied methods

### Methods (Setters)

**public function namespace( string $namespace )**
> @param string $namespace  
> @return \PinkCrab\Route\Route\Route

Sets the namespace for the defined route, this is required (unless creating the route via the `Route_Factory`). This should be done in the same fashion as core WP Rest Registration `my_thing/v1`

*Example*
```php
$route = new Route('GET', '/some_route');
$route->namespace('my_thing/v1');
```

> The above would create an endpoint on **https://www.url.com/wp-json/my_thing/v1/some_route** for **GET** requests.

**public function authentication( callable $auth_callback )**
> @param callable(WP_REST_Request $request):bool $auth_callback  
> @return \PinkCrab\Route\Route\Route

You can assign multiple `authentication` methods to a route, this allows you to have a global set of rules you apply to every route, and then some additional   checks on a route by route basis.

*Example*
```php
$route = new Route('GET', '/some_route');
$route->authentication( function( WP_REST_Request $request ):bool {
    // Do some checks (api key in header etc)
    return true;
});
$route->authentication('some_other_auth_callback');
```
> When passing more than 1 auth callback, they are compiled into an ALL TRUE function. If any of them return false, the whole chain ends and returns false. All must return true.

**public function callback( callable $callback )**
> @param callable(WP_REST_Request $request):WP_REST_Response|WP_Error $auth_callback  
> @return \PinkCrab\Route\Route\Route

You can either return a WP_REST_Response with the response code defined, or a WP_Error if you wish to denote an error (500 response). Your callback will receive the current WP_REST_Request object, which you can use to run your code, ready to return.

```php
$route = new Route('GET', '/some_route');
$route->callback( function( WP_REST_Request $request ) {
    // Do your logic here and then either return error or success.
    if('something' === 'something'){
        return new WP_REST_Response(['data' => 'Your data'], 200, ['optional' => 'headers']);
    } else {
        return new WP_Error(500, 'Something went wrong', ['data' => 'Your data']);
    }
});
```
> You can always return using WP_REST_Response if you wish and just set the response code, but using WP_Error will ensure all call error handling takes place. Please see the WP_Codex for more information on populating either Response or WP_Error.


**public function argument( Argument $argument )**
> @param PinkCrab\Route\Route\Argument $argument  
> @return \PinkCrab\Route\Route\Route

As per the WordPress API for routes, you will need to define all arguments used in the route URL. These should be passed to the route as a compiled Arguemnt object, but as this uses a fluent API, it can be done inline.

```php
$route = new Route('GET', '/some_route/(?P<foo>\d+)');
$route->argument( Arguemnt::on('foo')
    ->type('string')
    ->required()
);
```
If you have more than one argument, you can pass as many as you need.

> See below for more details on Arguemnts.

**public function with_method( string $method )**
> @param string $argument  
> @return \PinkCrab\Route\Route\Route

If you would like to create a copy of an existing route, but with a different method, you can call this useful method. It will just clone the initial route but with an alternative method.

```php
$route_post = new Route('POST', '/some_route');
// Your other setup. $route->authentication(....);

// Create a PUT route using the same setup, but with a different callback.
$route_put = $route_post->with_method('put');
$route_put->callback('some_other_callback');
```
> If you are planning to create a route with multiple methods, please consider using the `Route_Group` (detailed below)

### Methods (Getters)

Most of the Getter methods are primarily used internally, but you have access to them if you wish to create conditional logic around existing routes.

**public function get_namespace()**
> @return string  

Returns the currently defined namespace.

**public function get_route()**
> @return string  

Returns the currently defined route.

**public function get_method()**
> @return string  

Returns the currently defined route method.

**public function get_arguments()**
> @return Argument[]  

Returns an array of all defined arguments.

**public function get_authentication()**
> @return callable[]  

Returns an array of all defined authentication callbacks.

## Route_Group

Like single Route Models, the Route_Group allows for a similar process of creating releated routes that share a common endpoint route and also some functionality. Also like Routes, its better to use the supplied Route_Factory, but the details here will express how to create a `Route_Group` manually (the core methods are used the same regardless).

```php
$group = new Route_Group('my_endpoints/v2','route/');
```
> This would then create a group where all routes assigned are created with the above namespace and route.

### Methods (Setters)

**public function post( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route

Creates a POST endpoint on the groups route. This works the same as $factory->post(..) but without the need to pass the route parameter. Once this is called, you can easily fluently add any additional parameters.

*Example*
```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->post('some_callback')->authentication('some_auth_callback');
```

> The above would create an endpoint on **https://www.url.com/wp-json/my_endpoints/v2/route** for **POST** requests.

**public function get( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

*Same as post() above, but for GET requests.*

**public function put( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

*Same as post() above, but for PUT requests.*

**public function patch( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

*Same as post() above, but for PATCH requests.*

**public function delete( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

*Same as post() above, but for DELETE requests.*

**public function authentication( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route_Group  

Like when used for a single route, this will allow the stacking of authentication callbacks which all defined method, request will be passed through. Any other Authentication callback defined on a single method basis, will be added to those defined globally.

```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->authentication('check_api_key');

// Define routes
$group->post('some_callback')->authentication('some_extra_auth'); // Require extra check on post's
$group->get('some_other_callback');
```

*On the above example any request coming in as GET, will be passed through `check_api_key()`, where as POST will be passed through `check_api_key()` and then `some_extra_auth()`*

**public function argument( Argument $argument )**
> @param PinkCrab\Route\Route\Argument $argument  
> @return \PinkCrab\Route\Route\Route_Group

Like with individual routes, you can apply arguments to entire group of endpoints. Applying them to the group, reduces the need to define then for each method. While also allowing you to overrule them on a per method basis.

```php
$group = new Route_Group('acme/v3', '/some_route/(?P<foo>\d+)');
$group->argument( Argument::on('foo')
    ->type('string')
    ->required()
);
```
*The above would add the `foo` argument to every route that is defined. Any argument added to the individual method, will overwrite what is supplied group (if the have the same key)*

### Methods (Getters)

Most of the Getter methods are primarily used internally, but you have access to them if you wish to create conditional logic around existing groups.

**public function get_namespace()**
> @return string  

Returns the currently defined namespace.

**public function get_route()**
> @return string  

Returns the currently defined route.

**public function get_arguments()**
> @return Argument[]  

Returns an array of all defined arguments.

**public function get_authentication()**
> @return callable[]  

Returns an array of all defined authentication callbacks.

**public function get_rest_routes()**
> @return PinkCrab\Route\Route\Route[]  

Returns an array of each method defined, but has not yet been merged with group level authentication and arguments!

**public function method_exists(string $route)**
> @param string $route    
> @return bool   

Checks if a route/method has been defined.

```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->post('some_callback')->authentication('some_auth_callback');

var_dump($group->method_exists('GET')); // false
var_dump($group->method_exists('POST')); // true
```

## Route_Factory

As most of the time you will be creating endpoints with a fixed namespace, there is a factory that can be used to populate this for every route it creates, while giving a clean, fluent API that can be used to create routes inline as part of arrays and return values.

```php
$group = new Route_Factory('my_endpoints/v2');
$get = $group->get('/endpoint', 'some_callable');
$post = $group->get('/endpoint_2', 'some_other_callable');
```
Both of the above endpoints will be created with the `my_endpoints/v2` namespace.

## Change Log ##
* 0.1.1 Bumped to version 0.2.0 of PinkCrab Collection Dependency
* 0.1.0 Inital version
