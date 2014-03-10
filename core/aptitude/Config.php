<?php namespace Aptitude;

class Config
{
	/**
	 * Loads the specified config file from the app config folder
	 *
	 * @param string $name The name of the config file to load
	 * @return mixed
	 */
	public function load($name)
	{
		$config = APP_PATH . '/config/' . $name . '.php';

		if (file_exists($config)) return require $config;

		return false;
	}
}