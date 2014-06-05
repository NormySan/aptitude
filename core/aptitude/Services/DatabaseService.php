<?php namespace Aptitude\Services;

use Aptitude\ServiceProvider;
use Aptitude\DB;
use Aptitude\Database\DatabaseManager;
use Aptitude\Database\Connectors\ConnectionFactory;

class DatabaseService extends ServiceProvider
{
	/**
	 * Register the service.
	 */
	/*
	public function register()
	{
		// Factory that creates the database connections.
		$this->app->register('db.factory', function($container)
		{
			return new ConnectionFactory($container);
		});

		// Database handler that takes care of intantiated databases.
		$this->app->register('db', function($container)
		{
			$config = $container['app']->loadConfig('database');

			return new DatabaseManager($container, $config, $container['db.factory']);
		});
	}*/

	public function register()
	{
		$this->app->register('db', function($container) {

			$config = $container['app']->loadConfig('database');

			return new DB($config);
		});
	}
}