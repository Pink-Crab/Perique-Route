# Route

Defines a single route and its properties.

## Methods (Setters)

### namespace
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

### authentication
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

### callback
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

### argument
**public function argument( Argument $argument )**
> @param PinkCrab\WP_Rest_Schema\Argument\Argument $argument  
> @return \PinkCrab\Route\Route\Route

As per the WordPress API for routes, you will need to define all arguments used in the route URL. These should be passed to the route as a compiled Argument object, but as this uses a fluent API, it can be done inline.

```php
$route = new Route('GET', '/some_route/{foo}');
$route->argument( String_Type::on('foo')->required() );
```
If you have more than one argument, you can pass as many as you need.

> See below for more details on Arguemnts.

### with_method
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

## Methods (Getters)

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