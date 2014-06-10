<?php

class UsersController extends BaseController
{
	protected $layout = 'template';

	/**
	 * Show all users.
	 */
	public function all()
	{
		$data['users'] = DB::table('users')->select('id', 'username', 'email')->get();

		$this->layout->content = View::make('users.list', $data);
	}
}