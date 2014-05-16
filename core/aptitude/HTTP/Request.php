<?php namespace Aptitude\HTTP;

class Request {

	/**
	 * @var string
	 */
	private $requestUri;

	/**
	 * @var array
	 */
	private $server;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var string
	 */
	private $basePath;

	/**
	 * @var string
	 */
	private $scriptFile;

	/**
	 * @var string
	 */
	private $requestMethod;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->server = $_SERVER;

		// Set relevant values
		$this->buildBasePath();
		$this->buildRequestUri();
		$this->requestMethod = $this->server['REQUEST_METHOD'];
	}

	/**
	 * Figures out the base path of the current request
	 */
	public function buildBasePath()
	{
		// Seperate the path to self into an array
		$basePath = explode('/', $this->server['SCRIPT_NAME']);

		// Remove the last part of the array, this is the file that the request was made from
		$this->scriptFile = array_pop($basePath);

		// Glue the basepath back together seperating each vale with /
		$this->basePath = implode('/', $basePath);
	}

	/**
	 * Set the current base path
	 */
	public function setBasePath($path)
	{
		$this->basePath = $path;
	}

	/**
	 * Return the current base path
	 */
	public function getBasePath()
	{
		return $this->basePath;
	}

	/**
	 * Get query string
	 *
	 * @return string
	 */
	public function getQueryString()
	{
		return $this->server['QUERY_STRING'];
	}

	/**
	 * Builds the current request uri
	 *
	 * @return Request
	 */
	public function buildRequestUri()
	{
		// Build the full hostname
		$uri = $this->server['REQUEST_URI'];

		// Remove any potential query string from the current route
		$uri = strtok($uri, '?');

		// Remove script filename if it's present
		$uri = str_replace($this->scriptFile . '/', '', $uri);

		// Remove the base path
		$uri = str_replace($this->basePath, '', $uri);

		// Set the request uri
		$this->requestUri = $uri;

		return $this;
	}

	/**
	 * Get the current request uri
	 *
	 * @return string
	 */
	public function getRequestUri()
	{
		return $this->requestUri;
	}

	/**
	 * Get the request method used
	 *
	 * @return string
	 */
	public function getRequestMethod()
	{
		return $this->requestMethod;
	}

	/**
	 * Get an uri segment
	 *
	 * @param integer The segment number to return
	 *
	 * Checks if the specified segment exists in the uri and returns it. If no segment
	 * is found false will be returned.
	 *
	 * @return mixed
	 */
	public function segment($number)
	{
		$segments = explode('/', $this->requestUri);

		if (!isset($segments[$number])) return false;

		return $segments[$number];
	}

	/**
	 * Checks if the request was a secure request.
	 */
	public function isSecure()
	{
		if (! empty($this->server['HTTPS'])
			&& $this->server['HTTPS'] !== 'off'
			|| $this->server['SERVER_PORT'] == 443) {

		    return true;
		}

		return false;
	}

	/**
	 * Check of the request is an AJAX request.
	 *
	 * @return bool
	 */
	public function isAjax()
	{
		if (isset($this->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
			return true;
		}

		return false;
	}
}