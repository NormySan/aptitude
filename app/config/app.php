<?php

return array(

	'site_location' => 'http://localhost:8888/aptitude/public',

	/**
	 * Services will be added to the applications container on startup.
	 */
	'services' => array(
		'Aptitude\Services\ViewService',
	),

	/**
	 * Alias that will be registered upon application initiation.
	 */
	'aliases' => array(
		'Controller'	=> 'Aptitude\Controller',
		'View' 			=> 'Aptitude\Facades\View',
	)
);