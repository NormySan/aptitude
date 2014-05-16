<?php

/**
* Default controller
*/
class HomeController extends BaseController
{
	protected $layout = 'template';

	public function index()
	{
		$data['title'] = 'Welcome to aptitude!';
		$data['text'] = 'You have arrived at the default page for the aptitude framework.';

		$this->layout->pageTitle = 'Welcome';
		$this->layout->content = View::make('content', $data);
	}

	public function login()
	{
		$data = array();
		
		$this->layout->pageTitle = 'Welcome';
		$this->layout->content = View::make('login', $data);
	}

	public function json()
	{
		$user = new User;

		$users = $user->getAll();

		return Response::json($users);
	}
}