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
                    ->add_argument( // Define the argument proprties as per WP API
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
    $route = new Route('GET', 'some_callback_func');
    $route->namespace('my_thing/v1');
```


## Change Log ##
* 0.1.0 Extracted from the Registerables module. Now makes use of a custom Registration_Middleware service for dispatching all Ajax calls.