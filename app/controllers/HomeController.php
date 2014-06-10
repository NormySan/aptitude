<?php

/**
* Default controller
*/
class HomeController extends BaseController
{
	protected $layout = 'template';

	/**
	 * Index page of the application.
	 */
	public function index()
	{
		// Article model.
		$article = new Article;

		// Get articles from the article model.
		$data['articles'] = $article->all();

		// Data for the jumbotron.
		$data['title'] = 'Welcome to aptitude!';
		$data['text'] = 'You have arrived at the default page for the aptitude framework.';

		// Set the page title.
		$this->layout->pageTitle = 'Welcome';

		// Set the page content.
		$this->layout->content = View::make('content', $data);
	}

	/**
	 * Simple page with a login form.
	 */
	public function login()
	{
		$data = array();
		
		$this->layout->pageTitle = 'Welcome';
		$this->layout->content = View::make('login', $data);
	}

	/**
	 * Example of returning a response with json data.
	 * 
	 * @return Response
	 */
	public function json()
	{
		// User model.
		$user = new User;

		// Get all users.
		$users = $user->all();

		// Return the users as json data.
		return Response::json($users);
	}

	/**
	 * 
	 */
	public function jsonArticles()
	{
		$articles = DB::table('articles')->get();

		return Response::json($articles);
	}

	/**
	 * [jsonOneArticle description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function jsonOneArticle($id)
	{
		$article = DB::table('articles')->where('id', $id)->get();

		$author = DB::table('users')->where('id', $article[0]['author'])->get();

		$article[0]['author'] = $author;

		return Response::json($article);
	}
}