# Perique - Route

....

![alt text](https://img.shields.io/badge/Current_Version-0.1.0-yellow.svg?style=flat " ") 
[![Open Source Love](https://badges.frapsoft.com/os/mit/mit.svg?v=102)]()
![](https://github.com/Pink-Crab/Perique-Ajax/workflows/GitHub_CI/badge.svg " ")
[![codecov](https://codecov.io/gh/Pink-Crab/Perique-Ajax/branch/master/graph/badge.svg?token=NEZOz6FsKK)](https://codecov.io/gh/Pink-Crab/Perique-Ajax)

## Version 0.1.0-beta ##

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
                        Arguemnt::on('id')
                            ->type(Argument::TYPE_STRING)
                            ->validate('is_numeric')
                            ->sanitization('absint')
                            ->required()
                    );

                // Define the DELETE method.
                $group->delete()
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

As per the WordPress API for routes, you will need to define all arguments assigned to the route URL. 

## Change Log ##
* 0.1.0 Extracted from the Registerables module. Now makes use of a custom Registration_Middleware service for dispatching all Ajax calls.