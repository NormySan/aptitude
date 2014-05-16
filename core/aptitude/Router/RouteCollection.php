<?php namespace Aptitude\Router;

use Iterator;
use Aptitude\Router\Route;

/**
 * Contains a list of registered routes.
 *
 * References:
 * @link https://github.com/dannyvankooten/PHP-Router/
 * @link http://www.php.net/manual/en/class.iterator.php
 */
class RouteCollection implements Iterator
{
	/**
	 * List of registered routes.
	 */
	public $routes = array();

	/**
	 * Return a list of all registered routes.
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->routes;
	}

	/**
	 * Add a new route to the collection.
	 *
	 * @param string $uri
	 * @param object $action
	 */
	public function add($uri, Route $action)
	{
		$this->routes[$uri] = $action;
	}

	/**
	 * Get the specifiec URI.
	 *
	 * @param  string $uri
	 * @return object
	 */
	public function get($uri)
	{
		return $this->routes[$uri];
	}

	/**
	 * Remove URI with the supplied identifier.
	 *
	 * @param string $uri
	 */
	public function remove($uri)
	{
		unset($this->routes[$uri]);
	}

	/**
	 * Return the current element.
	 *
	 * @return array
	 */
	public function current()
	{
		return current($this->routes);
	}

	/**
	 * Return the key of the current element.
	 *
	 * @return array
	 */
	public function key()
	{
		return key($this->routes);
	}

	/**
	 * Move forward to next element.
	 */
	public function next()
	{
		next($this->routes);
	}

	/**
	 * Rewind the Iterator to the first element.
	 */
	public function rewind()
	{
		reset($this->routes);
	}

	/**
	 * Checks if current position is valid.
	 *
	 * @return bool
	 */
	public function valid()
	{
		if ($this->_routes) {
            return true;
        }
        return false;
	}
}