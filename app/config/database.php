<?php

return array(

	// Default connection to use when no other connection is defined
	'default' => 'mysql',

	// Database connection configurations
	'connections' => array(
		'mysql' => array(
			'driver'	=> 'mysql',
			'host' 		=> '127.0.0.1',
			'database'	=> 'aptitude',
			'username' 	=> 'root',
			'password' 	=> 'root',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'port' 		=> 3306
		)
	)
);