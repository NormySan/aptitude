<?php namespace Aptitude;

use Exception;

/**
* Base controller class
*/
abstract class Controller
{
	/**
	 * The view file to load.
	 *
	 * @var string
	 */
	protected $layout = null;

	/**
	 * Is this a restful controller?
	 *
	 * @var bool
	 */
	protected $restful = false;

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function setupLayout() {}

	/**
	 * Run a method on the controller.
	 *
	 * @param string  $method
	 * @param array   $parameters
	 *
	 * @return mixed
	 */
	public function callMethod($method, $parameters)
	{
		$this->setupLayout();

		$response = call_user_func_array(array($this, $method), $parameters);

		// Do not setup layout if this is a json response.
		if ($response instanceof Response && $response->isJsonResponse()) {
			return $response;
		}

		if (is_null($response) && ! is_null($this->layout))
		{
			$response = $this->layout;
		}

		return $response;
	}

	/**
	 * Handle calls to missing methods on the controller.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		throw new Exception("Method [$method] does not exist.");
	}
}