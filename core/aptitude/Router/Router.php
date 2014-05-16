<?php namespace Aptitude\Router;

use Exception;
use Aptitude\HTTP\Request;
use Aptitude\HTTP\Response;
use Aptitude\Interfaces\RenderableInterface;
use Aptitude\Router\RouteCollection as Collection;

class Router
{
	/**
	 * Collection of registered routes.
	 * 
	 * @var array
	 */
	private $routes = array();

	/**
	 * Array of supported methods.
	 */
	private $availibleMethods = array('GET', 'POST', 'PUT', 'DELETE');

	/**
	 * The set base path.
	 * 
	 * @var string
	 */
	private $basePath = '';

	/**
	 * Constructor.
	 *
	 * @param  Collection $collection Collection of routes.
	 */
	public function __construct(Collection $collection)
	{
		$this->routes = $collection;
	}

	/**
	 * Process the current request.
	 * 
	 * @return object|bool Matched route or false.
	 */
	public function process()
	{	
		return $this->match($this->requestUrl(), $this->requestMethod());
	}

	/**
	 * Find a match for the current route.
	 * 
	 * @param  string $requestUrl    Current url.
	 * @param  string $requestMethod Current method.
	 * @return object|bool           Matched route or false.
	 */
	public function match($requestUrl, $requestMethod = 'GET')
	{
		foreach($this->routes->all() as $route) {

			// compare server request method with route's allowed http methods
            if (! in_array($requestMethod, (array) $route->getMethod())) {
                continue;
            }

            // check if request _url matches route regex. if not, return false.
            if (! preg_match("@^". $this->basePath . $route->getRegex() ."*$@i", $requestUrl, $matches)) {
                continue;
            }

            $params = array();

            // Get all parameter values from the url.
            if (preg_match_all("/:([\w-]+)/", $route->getUrl(), $argument_keys)) {

                // grab array with matches
                $argument_keys = $argument_keys[1];

                // loop trough parameter names, store matching value in $params array
                foreach ($argument_keys as $key => $name) {
                    if (isset($matches[$key + 1])) {
                        $params[$name] = $matches[$key + 1];
                    }
                }

            }

            // Set parameters on the route.
            $route->setParameters($params);

            return $this->processRoute($route);
		}

		// If no route was founc throw an exception.
		throw new Exception("No route matching [$requestUrl] was found.");
	}

	/**
	 * Process matched route.
	 *
	 * @param   $route The matched route.
	 * @return  \Aptitude\HTTP\Response
	 */
	public function processRoute($route)
	{
		$config = $route->getConfig();

		// If the route is callable it's a callback.
		if (is_callable($config['controller'])) {
			return $this->callableRoute($route);
		}

		list($controller, $method) = explode('@', $config['controller']);

		$class = new $controller;

		// Call method on the controller and supply it with the parameters.
		$response = $class->callMethod($method, $route->getParameters());

		// If the response is an instance of renderable interface build a
		// response for it and return.
		if ($response instanceof RenderableInterface) {
			return $this->buildResponse($response->render());
		}

		return $response;
	}

	/**
	 * Create a new response from the callable function.
	 *
	 * @return  \Aptitude\HTTP\Response
	 */
	public function callableRoute($route)
	{
		$config = $route->getConfig();

		$content = call_user_func($config['controller']);

		return $this->buildResponse($content);
	}

	/**
	 * Return a new response object.
	 * 
	 * @param   $content Content for the response.
	 * @return  \Aptitude\HTTP\Response
	 */
	public function buildResponse($content)
	{
		return new Response($content);
	}

	/**
	 * Set base path for all requests.
	 * 
	 * @param string $path Path to set for base path.
	 */
	public function setBasePath($path)
	{
		$this->basePath = (string) $path;
	}

	/**
	 * Returns current requests URL.
	 * 
	 * @return string Request URL.
	 */
	public function requestUrl()
	{
		return $_SERVER['REQUEST_URI'];
	}

	/**
	 * Return method of the current request.
	 * 
	 * @return string Method.
	 */
	public function requestMethod()
	{
		// Since PHP does not support methods other than GET and POST we need to
		// check for a custom value supplied with the request. If it matches any
		// of the supported requests we use that method instead of the set metod
		// for the request.
		if (isset($_POST['_method'])) {

			$method = strtoupper($_POST['_method']);

			if (in_array($method, $this->availibleMethods)) {
				return $method;
			}
		}

		return $_SERVER['REQUEST_METHOD'];
	}
}