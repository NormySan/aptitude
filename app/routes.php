<?php

use Aptitude\Router\RouteCollection;
use Aptitude\Router\Route;
use Aptitude\Router\Router;

$collection = new RouteCollection;

$collection->add('hello.world', new Route('/', array(
	'controller' => 'HomeController@index',
	'method' => 'GET'
)));

$collection->add('docs.index', new Route('/docs', array(
	'controller' => 'DocsController@index',
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

$collection->add('articles.create', new Route('/articles/create', array(
	'controller' => 'ArticleController@create',
	'method' => 'GET'
)));

$collection->add('articles.all', new Route('/articles', array(
	'controller' => 'ArticleController@save',
	'method' => 'GET'
)));

$collection->add('articles.save', new Route('/articles', array(
	'controller' => 'ArticleController@save',
	'method' => 'POST'
)));

$collection->add('json.articles', new Route('/json/articles', array(
	'controller' => 'HomeController@jsonArticles',
	'method' => 'GET'
)));

$collection->add('json.articles', new Route('/json/articles/:id', array(
	'controller' => 'HomeController@jsonOneArticle',
	'method' => 'GET'
)));

$collection->add('users.all', new Route('/users', array(
	'controller' => 'UsersController@all',
	'method' => 'GET'
)));

return new Router($collection);