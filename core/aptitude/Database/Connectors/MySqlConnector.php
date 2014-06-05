<?php namespace Aptitude\Database\Connectors;

class MySqlConnector extends Connector implements ConnectorInterface
{
	/**
	 * Create a database connection.
	 *
	 * @param array $config
	 * @return PDO
	 */
	public function connect(array $config) {
		
		return $this->createConnection($this->getDsn($config), $config, $this->getOptions());
	}

	/**
	 * Create the PDO dsn connection string.
	 * 
	 * @param  array  $config Database configuration.
	 * @return string         Dsn connection string.
	 */
	public function getDsn($config)
	{
		// Turn config array attributes into variables.
		extract($config);

		$dsn = 'mysql:host=' . $host . ';dbname=' . $database;

		// If a port was set append it to the dsn string.
		if (isset($port)) {
			$dsn .= ';port=' . $port;
		}

		return $dsn;
	}
}