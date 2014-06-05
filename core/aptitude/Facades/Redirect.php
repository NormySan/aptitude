<?php namespace Aptitude\Facades;

use Aptitude\Facade;

/**
* Redirect
*/
class Redirect extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeService() { return 'redirect'; }
}