<?php namespace Aptitude\Router;

class Route
{
	/**
	 * Availible methods.
	 */
	private $methods = array();

	/**
	 * Route URL.
	 * @var string
	 */
	private $url;

	/**
	 * Route method.
	 */
	private $method;

	/**
	 * Route configuration.
	 * @var array
	 */
	private $config;

	/**
	 * Route parameters.
	 * @var array
	 */
	private $parameters = array();

	/**
	 * Constructor.
	 * @param  string $resource Url to the route resource.
	 * @param  array  $config   Route configuration.
	 */
	public function __construct($resource, array $config)
	{
		// Set the url for this route.
		$this->url = $resource;

		// Set routes config.
		$this->config = $config;

		// Set method for this route.
		$this->method = isset($config['method']) ? $config['method'] : '';
	}

	/**
	 * Get route with replacement parameters.
	 * @return [type] [description]
	 */
	public function getRegex()
	{
		return preg_replace_callback("/:(\w+)/", array(&$this, 'substituteFilter'), $this->url);
	}

	/**
	 * Replace url parameters with substitude regexes.
	 * 
	 * @param  array $matches Array of matched parameters.
	 * @return [type]          [description]
	 */
	private function substituteFilter($matches)
    {
        if (isset($matches[1]) && isset($this->filters[$matches[1]])) {
            return $this->filters[$matches[1]];
        }

        return "([\w-]+)";
    }

    /**
     * Add route parameters.
     * 
     * @param array $parameters Parameters to set.
     */
    public function setParameters(array $parameters)
    {
    	$this->parameters = $parameters;
    }

	/**
	 * Parse parameters from the route url.
	 * 
	 * @return array Route parameters.
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * Return the routes method.
	 * 
	 * @return string Route HTTP method.
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 * Return routes URL.
	 * 
	 * @return string Route URL.
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Set route URL.
	 * @param string $url URL to set.
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * Return route config.
	 * 
	 * @return array
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Set the route config.
	 * 
	 * @param array $config
	 */
	public function setConfig($config)
	{
		$this->config = $config;
	}
}