<?php

/**
* User model.
*/
class User
{
	public function all()
	{
		$users = DB::table('users')->get();

		return $users;
	}
}