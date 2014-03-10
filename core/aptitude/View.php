<?php namespace Aptitude;

use Aptitude\Exceptions\ViewException;
use Aptitude\Interfaces\RenderableInterface as Renderable;

/**
* View class
*/
class View implements Renderable
{
	private $extension = '.php';

	/**
	 * Available system paths.
	 */
	protected $paths;

	/**
	 * 
	 */
	private $view;

	/**
	 * 
	 */
	private $data;

	/**
	 * Constructor.
	 *
	 * @param  array $paths
	 * @return void
	 */
	public function __construct($paths)
	{
		$this->paths = $paths;
	}

	/**
	 * Create a new view from template
	 */
	public function make($view, $data = array())
	{
		$this->view = $view;
		$this->data = $data;

		return $this;
	}

	/**
	 * Render this view
	 */
	public function render()
	{
		// Prepare the data before we use it in the view.
		$this->prepareData();

		// Convert the first level indexes of the data array to variables
		extract($this->data);

		// Start the buffer
		ob_start();

		// Include view file
		include $this->pathname($this->view);

		// Get content from the buffer
		$view = ob_get_clean();

		return $view;
	}

	/**
	 * Prepare the data for the rendered view.
	 */
	public function prepareData()
	{
		// If any of the data is a view we need to render it before
		// we can use it as data in the current view.
		foreach ($this->data as $key => $data) {
			
			if ($data instanceof RenderableInterface) {
				$this->data[$key] = $data->render();
			}
		}
	}

	/**
	 * Gets the pathname and adds the file extension
	 */
	private function pathname($path)
	{
		// Replace any dots in the name with directory separators
		$path = str_replace('.', DIRECTORY_SEPARATOR, $path);

		// Add the extension and the views directory to the path
		$path = $this->paths['app'] . '/views/' . $path . $this->extension;

		if (file_exists($path))
		{
			return $path;
		}

		throw new ViewException('The requested view could not be found: ' . $path);
	}

	/**
	 * Get data set on the view.
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->data[$key];
	}

	/**
	 * Set data on the view.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * Get the string contents of the view.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}
}