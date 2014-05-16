<?php

use Aptitude\Router\RouteCollection;
use Aptitude\Router\Route;
use Aptitude\Router\Router;

$collection = new RouteCollection;

$collection->add('hello.world', new Route('/', array(
	'controller' => 'HomeController@index',
	'method' => 'GET'
)));

$collection->add('users.show', new Route('/json', array(
	'controller' => 'HomeController@json',
	'method' => 'GET'
)));

$collection->add('auth.login', new Route('/login', array(
	'controller' => 'HomeController@login',
	'method' => 'GET'
)));

return new Router($collection);