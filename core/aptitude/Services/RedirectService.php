<?php namespace Aptitude\Services;

use Aptitude\ServiceProvider;
use Aptitude\Redirect;

class RedirectService extends ServiceProvider
{
	/**
	 * Register the service.
	 */
	public function register()
	{
		$this->app->register('redirect', function($container)
		{
			return new Redirect();
		}, false);
	}
}