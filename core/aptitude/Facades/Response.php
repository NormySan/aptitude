<?php namespace Aptitude\Facades;

use Aptitude\Facade;

/**
* Response
*/
class Response extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeService() { return 'response'; }
}