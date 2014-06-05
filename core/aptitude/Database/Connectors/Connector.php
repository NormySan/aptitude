<?php namespace Aptitude\Database\Connectors;

use PDO;

class Connector
{
	/**
	 * PDO connection driver options.
	 *
	 * @var array
	 */
	protected $options = array(
		PDO::ATTR_CASE => PDO::CASE_NATURAL,
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
		PDO::ATTR_STRINGIFY_FETCHES => false,
		PDO::ATTR_EMULATE_PREPARES => false,
	);

	/**
	 * Instantiates a new PDO connection.
	 *
	 * @param  string $dsn
	 * @param  array  $config
	 * @param  array  $options
	 * @return PDO
	 */
	public function createConnection($dsn, array $config, array $driverOptions)
	{
		$username = $config['username'];
		$password = $config['password'];

		return new PDO($dsn, $username, $password, $driverOptions);
	}

	/**
	 * Set driver options.
	 *
	 * @param  array $options
	 * @return void
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
	}

	/**
	 * Get the set driver options.
	 *
	 * @param  array $options
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
	}
}