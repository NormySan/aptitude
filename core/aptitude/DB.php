<?php namespace Aptitude;

use Exception;

class AptitudeDBException extends Exception {}
class NonSupportedLibraryException extends Exception {}
class InvalidOperatorException extends Exception {}
class InvalidOrderByDirection extends Exception {}
class ConnectionFailedException extends Exception {}

/**
* Database connection
*/
class DB
{
	/**
	 * Active database connection
	 * 
	 * @var PDO
	 */
	protected $connection = null;

	/**
	 * Where statements
	 *
	 * @var array
	 */
	protected $wheres = array();

	/**
	 * Main table to select from
	 *
	 * @var string
	 */
	protected $tableName = null;

	/**
	 * Column to order by
	 *
	 * @var array
	 */
	protected $orderBy = array();

	/**
	 * Columns to select from
	 *
	 * @var array
	 */
	protected $selectArray = array();

	/**
	 * Supported PDO libraries
	 *
	 * @var array
	 */
	protected static $libraries = array('mysql');

	/**
	 * Valid where operators
	 *
	 * @var array
	 */
	protected static $whereOpertors = array(
		'=', '<', '>', '>=', '<=', 'IN', 'LIKE'
	);

	/**
	 * How many results to get
	 *
	 * @var integer
	 */
	protected $limit = null;

	/**
	 * Database config.
	 * @var array
	 */
	private $config = array();

	/**
	 * Constructor.
	 */
	function __construct($config)
	{
		$this->config = $config;
		$this->connection = $this->connection();
	}

	public function connection()
	{
		// Get the connection string
		$connectionString = $this->buildConnectionString($driver, $hostname, $database, $port);

		// Try to create a the new PDO connection
		try {
			$connection = new PDO($connectionString, $username, $password);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			throw new ConnectionFailedException('Could not connect to the database:' . $e->getMessage());
		}
	}

	/**
	 * Assembles
	 */
	public function buildConnectionString()
	{
		$config = $this->config[$this->config['default']];

		// Cast the $port variable to an integer in case it's a string
		$port = (int) $config->port || 3306;

		// Make sure the supplied library is supported
		if ($this->isValidLibrary($config->driver)) {
			$string .= $config->driver . ':';
		}

		// Add hostname and database to the string
		$string .= 'host=' . $config['hostname'] . ';' . 'dbname=' . $config['database'];

		// If the port variable is not null and is a valid number 
		// add it to the string.
		if (! is_null($port) && is_numeric($port)) {
			$string .= ';port=' . $port;
		}

		return $string;
	}

	/**
	 * Checks if the supplied library is in the libraries array
	 *
	 * @param string The library string to check for
	 *
	 * @return bool
	 */
	public function isValidLibrary($lib)
	{
		if (! in_array($lib, $this->libraries, true)) {
			throw new NonSupportedLibraryException('The defined database library is not supported.');
		}

		return true;
	}

	/**
	 * Add table to select from
	 *
	 * @param string Name of the table to use
	 *
	 * @return Aptitude\DB;
	 */
	public function table($name)
	{
		$this->tableName = $name;

		return $this;
	}

	/**
	 * Shorthand for adding fields (columns) to select from when querying.
	 *
	 * @param array List of fields to select
	 *
	 * @return DB
	 */
	public function select($fields = array()) {

		$args = func_get_args();

		// If the arguments array has an count higher than one we've got a
		// list of arguments as the fields instead of an array of fields.
		if (count($args) > 1) {

			$this->addFields($args);
			
			return $this;
		}

		$this->addFields($fields);

		return $this;
	}

	/**
	 * Adds fields (columns) to select from
	 *
	 * @param array List of fields to add
	 *
	 * @return void
	 */
	public function addFields($fields)
	{
		// Loop trough the fields and add each field to the select array.
		foreach ($fields as $field) {
			$this->selectArray[] = $field;
		}
	}

	/**
	 * Shorthand for adding where statements
	 *
	 * @param string Value to compare from
	 * @param string Operator to use
	 * @param string Value to compare against
	 *
	 * @return \Aptitude\DB;
	 */
	public function where($compare, $operator, $against = null)
	{
		// If no against value was set treat the comparison as an equals
		// to comparison.
		if (is_null($against)) {
			$this->addWhere($against, '=', $operator);

			return $this;
		}

		$this->addWhere($compare, $operator, $against);

		return $this;
	}

	/**
	 * Adds a where statement to the wheres array
	 *
	 * @param string Value to compare from
	 * @param string Operator to use
	 * @param string Value to compare against
	 * 
	 */
	public function addWhere($compare, $operator, $against)
	{
		// Make sure the operator is valid. If the operator was not valid
		// an exception will be thrown so no need to do any if checks.
		$this->isValidOperator($operator);

		// Add the where new where to the wheres array
		$this->wheres[] = array($compare, $operator, $against);
	}

	/**
	 * Checks to see if the supplied operator is valid and supported
	 *
	 * @param string The operator to check for
	 *
	 * @return bool
	 */
	public function isValidOperator($operator)
	{
		// Check if the operator is in the operators array. If it's not an
		// exception will be thrown.
		if (in_array($operator, $this->operators)) {
			throw new InvalidOperatorException('The supplied operator is not valid or not supported.');
		}

		return true;
	}

	/**
	 * Shorthand for executing the query
	 *
	 * @return array
	 */
	public function get()
	{
		return $this->query();
	}

	/**
	 * Shorthand for executing the query with only one 
	 */
	public function first()
	{
		# code ...
	}

	public function last()
	{
		# code...
	}

	/**
	 * Sets the column to order by
	 *
	 * @param string Column to order by
	 * @param string Direction to order by
	 */
	public function orderBy($column, $direction)
	{
		// Make sure the direction is in lowecase before checking it
		$direction = strtolower($direction);

		// Make sure the direction is either desc or asc
		if ($direction != 'desc' || $direction != 'asc') {
			throw new InvalidOrderByDirection($direction . ' is not a valid direction to order by.');
		}

		$this->orderBy = array(
			'column' 	=> $column,
			'direction' => $direction
		);

		return $this;
	}

	/**
	 * Shorthand for setting the query limit
	 *
	 * @param integer Limit to set
	 *
	 * @return \Aptitude\DB
	 */
	public function limit($limit)
	{
		$this->setQueryLimit($limit);

		return $this;
	}

	/**
	 * Set the query limit
	 *
	 * @param integer Limit to set
	 *
	 * @return void
	 */
	public function setQueryLimit($limit)
	{
		// Cast limit to an integer
		$limit = (int) $limit;

		$this->limit = $limit;
	}

	/**
	 * Runs the query
	 *
	 * @return array The result from the executed query
	 */
	private function query()
	{
		return $this->execute($this->buildStatement);
	}

	/**
	 * Build the query statement
	 *
	 * @return string
	 */
	public function buildStatement()
	{
		$statement = '';

		// Set columns to select from
		if (count($this->selectArray)) {
			$statement .= 'SELECT ' . implode(',', $this->selectArray);
		} else {
			$statement .= 'SELECT * ';
		}

		// Set table to select from
		if (! is_null($this->table)) {
			$statement .= 'FROM' . $this->table;
		}

		return $statement;
	}

	/**
	 * Execute the query
	 *
	 * @param string Statement string to query
	 * @param array  Values to replace with in the prepared statement
	 *
	 * @return array
	 */
	public function execute($statement, $vars = array())
	{
		$result = array();

		// Prepare the query
		$query = $this->conection->prepare($statement);

		// Execute the query with any supplied vars
		$query->execute($vars);

		// Fetch resulting rows
		while ($row = $query->fetch()) {
			$results[] = $row;
		}

		return $resuts;
	}
}