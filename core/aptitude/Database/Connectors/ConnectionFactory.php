<?php namespace Aptitude\Database\Connectors;

use PDO;
use Aptitude\Container;
use Aptitude\Database\Connections\MySqlConnection;

class ConnectionFactory
{
	/**
	 * IoC container instance
	 *
	 * @var \Aptitude\Container
	 */
	protected $container;

	/**
	 * Constructor
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Create a database connector.
	 *
	 * @param  array $config
	 * @return \Aptitude\Database\Connections\Connection
	 */
	public function createConnector(array $config)
	{
		if (! isset($config['driver'])) {
			throw new \InvalidArgumentException("No driver set in the supplied database configuration.");
		}

		switch ($config['driver']) {
			case 'mysql':
				return new MySqlConnection;
		}

		throw new \InvalidArgumentException("The supplied database driver {$config['driver']} is not supported");
	}

	/**
	 * Create a database connection
	 *
	 * @param
	 * @return \Aptitude\Database\Connection
	 */
	public function createConnection($driver, PDO $connection, $config)
	{
		// If the database connection already exists in the container
		// return that instead of creating a new one.
		if ($this->container->has($key = "db.connection.{$driver}"))
		{
			return $this->container[$key];
		}

		switch ($driver)
		{
			case 'mysql':
				return new MySqlConnection($connection, $config);
		}

		throw new \InvalidArgumentException("The supplied database driver {$driver} is not supported");
	}
}