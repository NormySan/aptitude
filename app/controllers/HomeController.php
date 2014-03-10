<?php

/**
* Default controller
*/
class HomeController extends BaseController
{
	protected $layout = 'template';

	public function index()
	{
		$data['hello'] = 'Hello Wolrd';

		$this->layout->pageTitle = 'Welcome';
		$this->layout->content = View::make('content', $data);
	}

	public function login()
	{
		$view = new View;

		$data['content'] = $view->make('login');

		return new Response($view->make('template', $data));
	}

	public function json()
	{
		$array = array(
			'message' => 'I am the king of JSON'
		);

		$response = new Response;
		$response->sendJson($array);
	}
}