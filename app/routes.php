<?php

/**
 * Instantiate the router and give it the request object.
 */
$route = new Aptitude\Router\Router($app['request']);

/**
 * Application routes
 */
$route->get('/', 'HomeController@index');
$route->get('login', 'HomeController@login');
$route->get('json', 'HomeController@json');
$route->get('/test', function() {
	$response = new Response;

	$response->sendJson(array('hello' => 'some random content'));
});

return $route;