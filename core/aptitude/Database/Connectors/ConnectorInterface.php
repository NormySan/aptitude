<?php namespace Aptitude\Database\Connectors;

interface ConnectorInterface
{
	/**
	 * Create a database connection.
	 *
	 * @param array $config
	 * @return PDO
	 */
	public function connect(array $config);
}