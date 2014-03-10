<?php namespace Aptitude\Services;

use Aptitude\ServiceProvider;
use Aptitude\View;

class ViewService extends ServiceProvider
{
	/**
	 * Register the service.
	 */
	public function register()
	{
		$this->app->register('view', function($container)
		{
			$paths = $container['app']->getPaths();

			return new View($paths);
		}, false);
	}
}