<?php namespace Aptitude\Facades;

use Aptitude\Facade;

/**
* View facade
*/
class View extends Facade
{
	/**
	 * Get the registered name of the service.
	 *
	 * @return string
	 */
	protected static function getFacadeService() {
		return 'view';
	}
}