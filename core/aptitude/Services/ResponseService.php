<?php namespace Aptitude\Services;

use Aptitude\ServiceProvider;
use Aptitude\HTTP\Response;

class ResponseService extends ServiceProvider
{
	/**
	 * Register the service.
	 */
	public function register()
	{
		$this->app->register('response', function($container)
		{
			return new Response();
		}, false);
	}
}