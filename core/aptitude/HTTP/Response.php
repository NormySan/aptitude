<?php namespace Aptitude\HTTP;

class Response {

	/**
	 * Content to send.
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * Status code to send.
	 *
	 * @var integer
	 */
	protected $statusCode;

	/**
	 * Status text to send.
	 *
	 * @var string
	 */
	protected $statusText;

	/**
	 * Array of headers to send.
	 *
	 * @var array
	 */
	protected $headers;

	/**
	 * Set to true if this is a JSON response.
	 *
	 * @var bool
	 */
	protected $json = false;

	/**
	 * Contains a list of status codes and their text
	 *
	 * @var array
	 */
	public static $statusCodeTexts = array(
		200 => 'OK',
		401 => 'Unauthorized',
		404 => 'Not Found'
	);

	/**
	 * List of some general Content-Types
	 *
	 * @var array
	 */
	public static $contentTypeHeaders = array(
		'html' 	=> 'text/html',
		'json' 	=> 'application/json',
		'jsonp' => 'application/javascript'
	);

	/**
	 * Constructor
	 *
	 * @param string 	$content Content to send as the response
	 * @param integer 	$status  Status code to send with response
	 * @param array 	$headers Headers to set with the response
	 */
	public function __construct($content = '', $status = 200, $headers = array())
	{
		$this->setContent($content);
		$this->setStatusCode($status);
		$this->setHeaders($headers);
	}

	/**
	 * Sets the response status code
	 *
	 * @param integer HTML status code
	 *
	 * Tries to set the response text by looking it up in the status codes text
	 * array. If no match is found the response text will be left empty.
	 *
	 * @return Response
	 */
	public function setStatusCode($code, $text = null)
	{
		$this->statusCode = $code = (int) $code;

		if ($text !== null) {
			$this->statusText = $text;

			return $this;
		}

		$this->statusText = isset(self::$statusCodeTexts[$code]) ? self::$statusCodeTexts[$code] : '';

		return $this;
	}

	/**
	 * Set the response content
	 *
	 * @return Response
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Set the headers
	 *
	 * @return Response
	 */
	public function setHeaders($headers)
	{
		$this->headers = $headers;

		return $this;
	}

	/**
	 * Sends HTTP headers and body
	 *
	 * @return Response
	 */
	public function send()
	{
		$this->sendheaders();
		$this->sendContent();

		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
        }
	}

	/**
	 * Sends content for the current response
	 *
	 * @return Response
	 */
	public function sendContent()
	{
		echo $this->content;

		return $this;
	}

	/**
	 * Send HTTP headers
	 *
	 * @return Response
	 */
	public function sendHeaders()
	{
		// Set header status
		header(sprintf('HTTP/%s %s %2', '1.0', $this->statusCode, $this->statusText));

		// Set the rest of the headers
		foreach ($this->headers as $name => $value) {
			header($name . ': ' . $value, false);
		}

		return $this;
	}

	/**
	 * Add a header
	 *
	 * @param string Name for the header
	 * @param string Value for the header 
	 *
	 * @return Response
	 */
	public function addHeader($name, $value)
	{
		$this->headers[$name] = $value;

		return $this;
	}

	/**
	 * @return Response
	 */
	public function setContentType($value)
	{
		$this->addHeader('Content-Type', $value);

		return $this;
	}

	/**
	 * Shorthand for sending a json response.
	 *
	 * @param  array $array
	 * @return void
	 */
	public function json($array = array())
	{
		$this->sendJson($array);

		return $this;
	}

	/**
	 * Send a json formated response.
	 *
	 * @param  
	 * @return Response
	 */
	public function sendJson($json = array())
	{
		$this->json = true;

		// Set content type
		$this->setContentType('application/json');

		// Encode the data
		$this->content = json_encode($json);
	}

	/**
	 * @return bool
	 */
	public function isJsonResponse()
	{
		return $this->json;
	}

	/**
	 * @return Response
	 */
	public function sendJsonP()
	{
		$this->setContentType('application/javascript');
	}
}