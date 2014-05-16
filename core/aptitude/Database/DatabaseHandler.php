<?php namespace Aptitude\Database;

use Aptitude\Container;
use Aptitude\Database\Connectors\ConnectionFactory;

class DatabaseHandler
{
	/**
	 * IoC Container
	 *
	 * @var \Aptitude\Container
	 */
	protected $container;

	/**
	 * Database Connection Factory
	 *
	 * @var \Aptitude\Database\Connectors\ConnectionFactory
	 */
	protected $factory;

	/**
	 * Constructor
	 *
	 * @param  Aptitude\Container $container
	 * @param  Aptitude\Database\Connectors\ConnectionFactory $factory
	 * @return void
	 */
	function __construct(Container $container, ConnectionFactory $factory)
	{
		$this->container = $container;
		$this->factory = $factory;
	}

	/**
	 * 
	 */
	public function connection()
	{
		
	}

	/**
	 * Dynamically pass methods to the default connection.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array($this->connection(), $method), $parameters);
	}
}