<?php

class ArticleController extends BaseController
{
	protected $layout = 'template';

	/**
	 * Page for creating a new article.
	 */
	public function create()
	{
		// Get users for the select list.
		$data['users'] = DB::table('users')->select('id', 'username')->get();

		// Set the page title.
		$this->layout->pageTitle = 'Create article';

		// Set the page content.
		$this->layout->content = View::make('articles.create', $data);
	}

	/**
	 * Save a new article.
	 * 
	 * @return Redirect
	 */
	public function save()
	{
		// Get the input post data.
		$input = $_POST;

		// Set article values.
		$article = array(
			'title' 	=> $input['title'],
			'body' 		=> $input['body'],
			'author' 	=> $input['author'],
			'published' => 1
		);

		// Save the article to the database.
		DB::table('articles')->insert($article);

		// Rediret back to the frontpage.
		return Redirect::to('/');
	}
}