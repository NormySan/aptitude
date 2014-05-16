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
		$view = new View;

		$data['content'] = $view->make('login');

		$this->layout->pageTitle = 'Welcome';
		$this->layout->content = View::make('content', $data);
	}

	public function json()
	{
		$array = array(
			'message' => 'I am the king of JSON'
		);

		return Response::json($array);
	}
}