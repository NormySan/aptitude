<?php namespace Aptitude\Facades;

use Aptitude\Facade;

/**
* Database facade
*/
class DB
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeService() { return 'db'; }
}