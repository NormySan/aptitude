<?php

/**
* User model.
*/
class User
{
	public function getAll()
	{
		$users = DB::table('users')->execute();

		print_r($users);
	}
}