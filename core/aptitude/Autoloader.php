<?php

/**
* Autoloader class
*/
class Autoloader
{
	/**
	 * The file extension to use
	 */
	private $fileExtension = '.php';

	/**
	 * The namespace separator
	 */
	private $namespaceSeparator = '\\';

	/**
	 * Directories to look inside
	 */
	private $directories = array();

	/**
	 * Register the class as an autoloader
	 */
	public function register()
	{
		spl_autoload_register(array($this, 'loadClass'));
	}

	/**
	 * Tries to load a class
	 */
	public function loadClass($classname)
	{
		$filename = '';
		$namespace = '';

		// Trim any whitespace or '\' seperator from the beginning of the classname
		$classname = ltrim($classname, $this->namespaceSeparator);

		// Get the position of the last occurance of '\' in the classname
		$lastNamespacePos = strrpos($classname, $this->namespaceSeparator);

		// If we get a namespace position we want to do some work 
		if ($lastNamespacePos)
		{
			// Get the class namespace, starts at position 0 and ends at the position of
			// the last '\' seperator in the classname string.
			$namespace = substr($classname, 0, $lastNamespacePos);

			// Get the classname, starts at position after the last namespace '\' seperator.
			$classname = substr($classname, $lastNamespacePos + 1);

			// Replace all namespace '\' seperators with directory seperators '/' in the namespace
			// and append a directory seperator at the end of the string.
			$filename = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}

		// Replace any underscores '_' with a directory seperator and append '.php' to
		// the end of the filename.
		$filename .= str_replace('_', DIRECTORY_SEPARATOR, $classname) . $this->fileExtension;

		// Loop trough each directory and search for the file
		foreach ($this->directories as $directory)
		{
			if (file_exists($directory . DIRECTORY_SEPARATOR . $filename))
			{
				include $directory . DIRECTORY_SEPARATOR . $filename;

				return true;
			}
		}

		return false;
	}

	/**
	 * Add one directory
	 */
	public function addDirectory($directory)
	{
		$this->directories[] = $directory;
	}

	/**
	 * Add multiple directories
	 */
	public function addDirectories($directories = array())
	{
		foreach ($directories as $directory)
		{
			$this->addDirectory($directory);
		}
	}
}