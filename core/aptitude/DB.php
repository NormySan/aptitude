<?php namespace Aptitude;

use PDO;
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
	protected $table = null;

	/**
	 * Column to order by
	 *
	 * @var array
	 */
	protected $orderBy = null;

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
	protected $libraries = array('mysql');

	/**
	 * Valid where operators
	 *
	 * @var array
	 */
	protected $operators = array(
		'=', '<', '>', '>=', '<=', 'IN', 'LIKE'
	);

	/**
	 * How many results to get
	 *
	 * @var integer
	 */
	protected $limit = null;

	/**
	 * Array of joins.
	 * 
	 * @var array
	 */
	protected $joins = array();

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

	/**
	 * Create the connection.
	 */
	public function connection()
	{
		$connectionString = $this->buildConnectionString();

		return $this->createConnection($connectionString);
	}

	/**
	 * Create a new PDO connection.
	 * 
	 * @return object PDO Connection
	 */
	public function createConnection($dsn)
	{	
		$config = $this->getDefaultConfig();

		// Try to create a the new PDO connection.
		try {
			$connection = new PDO($dsn, $config['username'], $config['password']);

			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		} catch(PDOException $e) {

			throw new ConnectionFailedException('Could not connect to the database:' . $e->getMessage());
		}

		return $connection;
	}

	/**
	 * Return the default connection configuration.
	 * 
	 * @return array Default configuration.
	 */
	public function getDefaultConfig()
	{
		return $this->config['connections'][$this->config['default']];
	}

	/**
	 * Assembles
	 */
	public function buildConnectionString()
	{
		$string = '';

		$config = $this->getDefaultConfig();

		// Cast the $port variable to an integer in case it's a string
		$port = (int) $config['port'] ?: 3306;

		// Make sure the supplied library is supported
		if ($this->isValidLibrary($config['driver'])) {
			$string .= $config['driver'] . ':';
		}

		// Add hostname and database to the string
		$string .= 'host=' . $config['host'] . ';' . 'dbname=' . $config['database'];

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
	 * Add table to select from.
	 *
	 * @param  string $name Name of the table to use
	 * @param  mixed  $as 	Table as name.
	 * @return Aptitude\DB;
	 */
	public function table($name, $as = null)
	{
		$this->table = array($name, $as);

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
			$this->addWhere($compare, '=', $operator);

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
		if (! in_array($operator, $this->operators)) {
			throw new InvalidOperatorException('The supplied operator is not valid or not supported.');
		}

		return true;
	}

	/**
	 * Shorthand for creating a join.
	 * 
	 * @param  string $table    Table to join from
	 * @param  string $compare  Value to compare from
	 * @param  sring  $operator Operator to use for comparison
	 * @param  string $against  Value to compare against
	 * @return \Aptitude\DB
	 */
	public function join($table, $compare, $operator, $against = null)
	{
		$this->addJoin('INNER JOIN', $table, $compare, $operator, $against);

		return $this;	
	}

	/**
	 * Shorthand for creating an left join.
	 * 
	 * @param  string $table    Table to join from
	 * @param  string $compare  Value to compare from
	 * @param  sring  $operator Operator to use for comparison
	 * @param  string $against  Value to compare against
	 * @return \Aptitude\DB
	 */
	public function leftJoin($table, $compare, $operator, $against = null)
	{
		$this->addJoin('LEFT JOIN', $table, $compare, $operator, $against);

		return $this;	
	}

	/**
	 * Shorthand for creating an left join.
	 * 
	 * @param  string $table    Table to join from
	 * @param  string $compare  Value to compare from
	 * @param  sring  $operator Operator to use for comparison
	 * @param  string $against  Value to compare against
	 * @return \Aptitude\DB
	 */
	public function rightJoin($table, $compare, $operator, $against = null)
	{
		$this->addJoin('RIGHT JOIN', $table, $compare, $operator, $against);

		return $this;	
	}

	/**
	 * Add a join to the query.
	 *
	 * @param  string $type 	Type of join to add.
	 * @param  string $table    Table to join from
	 * @param  string $compare  Value to compare from
	 * @param  sring  $operator Operator to use for comparison
	 * @param  string $against  Value to compare against
	 * @return \Aptitude\DB
	 */
	public function addJoin($type, $table, $compare, $operator, $against = null)
	{
		if (is_null($against)) {
			$against = $operator;
			$operator = '=';
		}

		$this->joins[] = array($type, $table, $compare, $operator, $against);
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
	 * Get the first result from the query.
	 */
	public function first() {}

	/**
	 * Get the last result in the query.
	 */
	public function last() {}

	/**
	 * Build an insert query and run it.
	 */
	public function insert(array $insertData = array())
	{
		// Make a table was set before bulding the query.
		if (is_null($this->table)) {
			throw new Exception('A table name must be supplied to do an insert query.');
		}

		// Start building the statement.
		$statement = "INSERT INTO {$this->table[0]} ";
		
		$columns = array();
		$values = array();

		// Build values for the insert.
		foreach ($insertData as $key => $data) {
			$columns[] = $key;
			$values[] = "'{$data}'";
		}

		// Rutn columns and values into strings.
		$columns = implode(', ', $columns);
		$values = implode(', ', $values);

		// Add columns and values to the query statement.
		$statement .= "({$columns}) VALUES ({$values})";

		// Prepare the query.
		$query = $this->connection->prepare($statement);

		// Execute query.
		return $query->execute();
	}

	/**
	 * Update table with the supplied value.
	 */
	public function update(array $updateData = array())
	{
		// Make a table was set before bulding the query.
		if (is_null($this->table)) {
			throw new Exception('A table name must be supplied to do an update query.');
		}

		$statement = "UPDATE {$this->table[0]} SET ";

		$update = array();

		// Build an update statement from the data array.
		foreach ($updateData as $key => $data) {
			$update[] = "{$key}={$data}";
		}

		$statement .= implode(',', $update);

		// Add query where statements.
		if (count($this->wheres)) {
			$statement .= ' ' . $this->buildWheres();
		}

		return $this->execute($statement);
	}

	/**
	 * Build insert SQL string.
	 */

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
		if (! in_array($direction, ['desc', 'asc'])) {
			throw new InvalidOrderByDirection("{$direction} is not a valid direction to order by.");
		}

		$this->orderBy = array($column, $direction);

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
		return $this->execute($this->buildStatement());
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
			$statement .= $this->buildTableString();
		}

		// Add query joins.
		if (count($this->joins)) {
			$statement .= $this->buildJoins();
		}

		// Add query where statements.
		if (count($this->wheres)) {
			$statement .= $this->buildWheres();
		}

		// Set query limit.
		if (isset($this->limit)) {
			$statement .= ' LIMIT ' . $this->limit;
		}

		if (!empty($this->orderBy)) {
			$statement .= $this->buildOrderBy();
		}

		return $statement;
	}

	/**
	 * Build order by string.
	 */
	public function buildOrderBy()
	{
		list($column, $direction) = $this->orderBy;

		$direction = strtoupper($direction);

		return " ORDER BY {$column} {$direction}";
	}

	/**
	 * Build the table query string.
	 * 
	 * @return string Built query string.
	 */
	public function buildTableString()
	{
		// Make sure a table is set otherwise throw an exception.
		if (! $this->table) {
			throw new Exception('A table must be set to do a query.');
		}

		// Set table attributes as variables.
		list($table, $as) = $this->table;

		// Start building the table query string.
		$string = " FROM {$table}";

		// If an as value was set add it to the string.
		if (! is_null($as)) {
			$string .= " AS {$as}";
		}

		return $string;
	}

	/**
	 * Build a string of joins for the query statement.
	 * 
	 * @return string
	 */
	public function buildJoins()
	{
		$string = '';

		foreach ($this->joins as $join) {

			list($type, $table, $compare, $operator, $against) = $join;

			$string .= " {$type} `{$table}` ON {$compare} {$operator} {$against}";
		}

		return $string;
	}

	/**
	 * Build a string oh wheres.
	 * 
	 * @return string
	 */
	public function buildWheres()
	{
		$string = '';

		for ($i = 0; $i < count($this->wheres); $i++) {

			list($compare, $operator, $against) = $this->wheres[$i];

			if ($i === 0) {
				$string .= " WHERE {$compare} {$operator} {$against}";
			}
			else {
				$string .= " AND {$compare} {$operator} {$against}";
			}
		}

		return $string;
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

		// Prepare the query.
		$query = $this->connection->prepare($statement);

		// Execute the query with any supplied vars
		$query->execute($vars);

		// Fetch resulting rows
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$results[] = $row;
		}

		return $results;
	}
}