<?php namespace Aptitude;

use Exception;
use \Aptitude\HTTP\Response;

class Redirect
{
	/**
	 * Redirect to the supplied url.
	 * 
	 * @param  string  $path   Url to redirect to.
	 * @param  integer $status Status code to set for the redirect.
	 * @return \Aptitude\HTTP\Response
	 */
	public function to($path, $status = 302, $headers = array())
	{
		// If no custom header was supplied create one.
		if (empty($headers)) {
			$headers = $this->createRedirectHeader($path);
		}

		return $this->createRedirect($path, $status, $headers);
	}

	/**
	 * Create a redirect header.
	 * 
	 * @param  string $path Path to redirect to.
	 * @return array        Array of headers.
	 */
	public function createRedirectHeader($path)
	{
		return array('Location' => $path);
	}

	/**
	 * Create a new redirect response.
	 * 
	 * @return \Aptitude\HTTP\Response
	 */
	public function createRedirect($path, $status, $headers)
	{
		return new Response('', $status, $headers);
	}
}