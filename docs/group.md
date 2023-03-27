# Route Group

Defines a group of routes around the same path, but with different methods.

## Methods (Setters)

## POST \[HTTP Method\]
**public function post( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route

Creates a POST endpoint on the groups route. This works the same as $factory->post(..) but without the need to pass the route parameter. Once this is called, you can easily fluently add any additional parameters.

```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->post('some_callback')->authentication('some_auth_callback');
```

> The above would create an endpoint on **https://www.url.com/wp-json/my_endpoints/v2/route** for **POST** requests.

### GET \[HTTP Method\]
**public function get( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->get('some_callback')->authentication('some_auth_callback');
```

*Same as post() above, but for GET requests.*

### PUT \[HTTP Method\]
**public function put( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->put('some_callback')->authentication('some_auth_callback');
```


*Same as post() above, but for PUT requests.*

### PATCH \[HTTP Method\]
**public function patch( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->patch('some_callback')->authentication('some_auth_callback');
```


*Same as post() above, but for PATCH requests.*

### DELETE \[HTTP Method\]
**public function delete( callable $callback )**
> @param callable $callback  
> @return \PinkCrab\Route\Route\Route  

```php
$group = new Route_Group('my_endpoints/v2','route/');
$group->delete('some_callback')->authentication('some_auth_callback');
```


*Same as post() above, but for DELETE requests.*

### authentication
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

### argument
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

## Methods (Getters)

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