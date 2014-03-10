<?php namespace Aptitude;

use Closure;
use Aptitude\HTTP\Request;
use Aptitude\HTTP\Response;
use Aptitude\Router\Router;
use Aptitude\Container;

class RouteNotFoundException extends \Exception {}

/**
* Application class
*/
class Application extends Container
{
	/**
	 * The application config
	 *
	 * @var array
	 */
	private $config;

	/**
	 * The current request
	 *
	 * @var \Aptitude\HTTP\Request
	 */
	public $request;

	/**
	 * Application paths
	 */
	protected $paths;

	/**
	 * Instantiate the application.
	 */
	public function __construct(Request $request)
	{
		$this['request'] = $request;
	}

	/**
	 * Set application paths.
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;
	}

	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * Set application router.
	 *
	 * @param \Aptitude\Router\Router
	 * @return void
	 */
	public function setRouter(Router $router)
	{
		$this['router'] = $router;
	}

	/**
	 * 
	 */
	public function run()
	{
		$response = $this['router']->process();

		$response->send();
	}

	/**
	 * 
	 */
	public function handleResponse($response)
	{
	}

	public function processRequest()
	{
		$request = new Request;
		$method = strtolower($request->getRequestMethod());

		foreach ($this->routes as $route)
		{
			if (strpos($route['route'], '/') > 0 or strpos($route['route'], '/') === FALSE)
			{
				$route['route'] = '/' . $route['route'];
			}

			if ($this->currentRoute == $route['route'] && $method === $route['method'])
			{
				// If the route is callable it's a function so lets call the function.
				if (is_callable($route['class'])) {
					return call_user_func($route['class']);
				}

				$class = explode('@', $route['class']);

				$loadedClass = new $class[0];

				return $loadedClass->$class[1]();
			}
		}

		throw new RouteNotFoundException('The requested route could not be found: ' . $this->currentRoute);
	}

	/**
	 * 
	 */
	public function registerServices($services)
	{
		// Register each service set in the application config.
		foreach ($services as $service) {

			// Instantiate the service provider.
			$service = new $service($this);

			// Run the providers registration method.
			$service->register($this);
		}
	}

	/**
	 * Register class aliases
	 */
	public function registerAliases($aliases)
	{
		foreach ($aliases as $alias => $class) {
			
			class_alias($class, $alias);
		}
	}

	/**
	 * Loads the specified config file from the app config folder
	 *
	 * @param string $name The name of the config file to load
	 * @return mixed
	 */
	public function loadConfig($name)
	{
		$config = $this->paths['app'] . '/config/' . $name . '.php';

		if (file_exists($config)) return require $config;

		return false;
	}
}