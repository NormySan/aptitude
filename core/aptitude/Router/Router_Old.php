<?php namespace Aptitude\Router;

use Exception;
use Aptitude\HTTP\Request;
use Aptitude\HTTP\Response;
use Aptitude\Interfaces\RenderableInterface;

/**
* Router class
*/
class Router
{
	/**
	 * Collection of configured routes
	 *
	 * @var array
	 */
	private $collection = array();

	/**
	 * Request class.
	 *
	 * @var \Aptitude\HTTP\Request
	 */
	private $request;

	/**
	 * Do some stuff on instantiation
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Process routes.
	 */
	public function process()
	{
		$requestUri 	= $this->request->getRequestUri();
		$requestMethod 	= $this->request->getRequestMethod();

		foreach ($this->collection as $route)
		{
			if (strpos($route['route'], '/') > 0 || strpos($route['route'], '/') === FALSE)
			{
				$route['route'] = '/' . $route['route'];
			}

			if ($requestUri == $route['route'] && $requestMethod === $route['method'])
			{
				// If the route is callable it's a callback.
				if (is_callable($route['class'])) {
					return $this->callableRoute($route);
				}

				$parts = explode('@', $route['class']);

				$class = new $parts[0];

				$response = $class->callMethod($parts[1], array());

				if ($response instanceof RenderableInterface) {
					return $this->buildResponse($response->render());
				}

				return $response;
			}
		}

		throw new Exception('The requested route could not be found: ' . $requestUri);
	}

	/**
	 * Return new response object.
	 */
	public function buildResponse($content)
	{
		return new Response($content);
	}

	/**
	 * Create a new response from the callable function.
	 */
	public function callableRoute($route)
	{
		$content = call_user_func($route['class']);

		return $this->buildResponse($content);
	}

	/*
	public function getCurrentRoute()
	{
		// Get the full hostname
		$uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		// Remove any potential query string from the current route
		$uri = strtok($uri, '?');

		// Remove the site path from the uri so we only get the segments that we actually want
		$uri = str_replace($this->siteUrl, '', $uri);

		return $uri;
	}
	*/

	/**
	 * Returns the current route
	 * @return string
	 */
	public function current()
	{
		return $this->currentRoute;
	}

	/**
	 * Adds a new get route
	 */
	public function get($route, $class)
	{
		$this->addRoute($route, $class, 'GET');
	}

	/**
	 * Adds a new post route
	 */
	public function post($route, $class)
	{
		$this->addRoute($route, $class, 'POST');
	}

	/**
	 * Adds a new route
	 * @return Router
	 */
	public function addRoute($route, $class, $method)
	{
		$this->collection[] = array(
			'route' 	=> $route,
			'class' 	=> $class,
			'method' 	=> $method
		);

		return $this;
	}
}