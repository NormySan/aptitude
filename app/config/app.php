<?php

return array(

	'site_location' => 'http://localhost:8888/aptitude/public',

	/**
	 * Services will be added to the applications container on startup.
	 */
	'services' => array(
		'Aptitude\Services\DatabaseService',
		'Aptitude\Services\RedirectService',
		'Aptitude\Services\ResponseService',
		'Aptitude\Services\ViewService',
	),

	/**
	 * Alias that will be registered upon application initiation.
	 */
	'aliases' => array(
		'Controller'	=> 'Aptitude\Controller',
		'DB'			=> 'Aptitude\Facades\DB',
		'Redirect'		=> 'Aptitude\Facades\Redirect',
		'Response'		=> 'Aptitude\Facades\Response',
		'View' 			=> 'Aptitude\Facades\View',
	)
);