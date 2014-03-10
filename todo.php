<?php

/*
|--------------------------------------------------------------------------
| Aptitude TODO List
|--------------------------------------------------------------------------
|
| Things that needs to be taken care of and changed in the system.
| Everything from small to large changes. 
|
| NOTE! Most of the code in this file can not be run on it's own, it will 
| fail if you try to do so.
|
*/


/*
|--------------------------------------------------------------------------
| Application loading/booting
|--------------------------------------------------------------------------
|
| The whole application should be loaded, booted, send a response and
| run a shutdown from the Application class.
|
*/

// Boots up the application, loads (insert?) app config file, add resolvers
// to the IoC container, register aliases.
$app->boot();

// Checks the routes and returns a response if one was supplied. If no
// response is set it should throw an Exception. (Not sure if it should?)
$app->run();

// Probably not needed for quie a while. Would require more advanced
// features to be added.
// The shutdown would mainly be a bunch of supplied closures doing their
// thing after the application has sent the response.
$app->shutdown();

/*
|--------------------------------------------------------------------------
| Database class
|--------------------------------------------------------------------------
|
| A class that should be able to connect to the most common SQL databases
| with help from the PHP PDO class.
|
| First priority is MySQL suport and after that PostgreSQL and then SQLite
| support.
|
*/

// Instantiate a new DB class assigned to the $db variable.
$db = new DB;

// Simple example of what a select getting all users should look like.
$users = $db->table('users')->get();

// A more advanced select getting only a few collumns.
$users = $db->select(array('username', 'email'))->table('users')->get();

// You should also be able to do a select without supplying an array as
// the value and instead only supply regular params.
$users = $db->select('username', 'email')->table('users')->get();

// Where statements can be done in one of two ways, either without the
// operator in which case it will use the equals to operator or it can
// be used by supplying the operator to use in the where statement.

// Where equals to.
$users = $db->table('users')->where('id', 1)->get();

// Where value in array.
$users = $db->table('users')->where('id', 'IN', array(1, 2, 3))->get();

// Where value is less than.
$users = $db->table('users')->where('id', '<', 10)->get();

// Where value like.
$users = $db->table('users')->where('username', 'LIKE', 'John')->get();

// Get the first record found with the current statement.
$user = $db->table('users')->where('authenticated', 1)->first();

// Get the last record found with the current statement.
$user = $db->table('users')->where('authenticated', 1)->last();

// Joins with a callback function for the join settings.
// Not really sure if this will work?! Would be awesome tough!
$users = $db->table('users')->join(function($join) {

	$join->table('settings')->where('users.id', 'settings.user_id');

})->get();

// Join from table settings ON users.id = settings.user_id.
$users = $db->table('users')->join('settings', 'users.id', '=', 'settings.user_id');

/*
|--------------------------------------------------------------------------
| MongoDB Class
|--------------------------------------------------------------------------
|
| MongoDB support. Not something to to in the near future.
|
*/


/*
|--------------------------------------------------------------------------
| DI (Dependency Injection)
|--------------------------------------------------------------------------
|
| Might be a good feature? Needs more research before implementing.
|
*/