<?php namespace Aptitude\Database\Connectors;

use PDO;

/**
* MySQL Database Connector
*/
class MySqlConnector extends Connector implements ConnectorInterface
{
	/**
	 * 
	 */
	protected $config

	function __construct($driver, PDO $pdo, $config)
	{
		$this->config = $config;
	}
}