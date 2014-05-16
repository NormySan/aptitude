<?php

use Aptitude\Router\RouteCollection;
use Aptitude\Router\Route;
use Aptitude\Router\Router;

$collection = new RouteCollection;

$collection->add('hello.world', new Route('/', array(
	'controller' => 'HomeController@index',
	'method' => 'GET'
)));

$collection->add('users', new Route('/users', array(
	'controller' => 'UsersController@list',
	'method' => 'GET'
)));

$collection->add('users.show', new Route('/users/:id', array(
	'controller' => 'UsersController@show',
	'method' => 'GET'
)));

$collection->add('users.show.gallery.show', new Route('/users/:id/gallery/:number', array(
	'controller' => 'UsersController@show',
	'method' => 'GET'
)));

/**
 * Instantiate the router and give it the request object.
 */
//$route = new Aptitude\Router\Router($app['request']);

/**
 * Application routes
 */
// $route->get('/', 'HomeController@index');
// $route->get('login', 'HomeController@login');
// $route->get('json', 'HomeController@json');
// $route->get('/test', function() {
// 	$response = new Response;

// 	$response->sendJson(array('hello' => 'some random content'));
// });

return new Router($collection);