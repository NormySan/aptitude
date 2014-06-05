<?php namespace Aptitude\Database;

use Aptitude\Database\Connectors\ConnectionFactory;

class DatabaseManager
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
	 * Database config.
	 * 
	 * @var array
	 */
	protected $config;

	/**
	 * Active connections.
	 * 
	 * @var array
	 */
	protected $connections = array();

	/**
	 * Constructor
	 *
	 * @param  Aptitude\Container $container
	 * @param  Aptitude\Database\Connectors\ConnectionFactory $factory
	 * @return void
	 */
	function __construct($container, $config, ConnectionFactory $factory)
	{
		$this->container 	= $container;
		$this->config 		= $config;
		$this->factory 		= $factory;
	}

	/**
	 * Get a database connection. As of now this will only get the default
	 * connection. Multiple connections will be supported later on.
	 */
	public function connection($name = null)
	{
		// If no name was set get the defult database connection.
		$name = $name ?: $this->getDefaultConnection();

		// If no connection exists we need to create it.
		if (! isset($this->connections[$name])) {

			$connection = $this->makeConnection($name);

			$this->connections[$name] = $connection;
		}
		
		return $this->connections[$name];
	}

	/**
	 * Create a new database connection.
	 * 
	 * @param  string $name Database connection name.
	 * @return object       Instantiated database connection.
	 */
	public function makeConnection($name)
	{
		return $this->factory->make($this->config['connections'][$name]);
	}

	/**
	 * Get the default database connection.
	 * 
	 * @return string Default database connection.
	 */
	public function getDefaultConnection()
	{
		return $this->config['default'];
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