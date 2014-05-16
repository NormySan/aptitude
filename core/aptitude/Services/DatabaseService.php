<?php namespace Aptitude\Services;

use Aptitude\ServiceProvider;
use Aptitude\Database\DatabaseHandler;
use Aptitude\Database\Connectors\ConnectionFactory;

class DatabaseService extends ServiceProvider
{
	/**
	 * Register the service.
	 */
	public function register()
	{
		// Factory that creates database connections.
		$this->app->register('db.factory', function($container)
		{
			return new ConnectionFactory($container);
		});

		// Database handler that takes care of intantiated databases.
		$this->app->register('db', function($container)
		{
			return new DatabaseHandler($container, $container['db.factory']);
		});
	}
}