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
                $group->get([$this, 'some_other_get_method'])
                    ->add_argument(
                        Arguemnt::on('id')
                            ->type(Argument::TYPE_STRING)
                            ->validate('is_numeric')
                            ->sanitization('absint')
                            ->required()
                    );
            })
        ];
    }

}
```



## Change Log ##
* 0.1.0 Extracted from the Registerables module. Now makes use of a custom Registration_Middleware service for dispatching all Ajax calls.