<?php

/**
* User model.
*/
class User
{
	public function all()
	{
		$users = DB::table('users')->select('id', 'username', 'email')->get();

		return $users;
	}
}