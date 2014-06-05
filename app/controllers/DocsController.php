<?php

/**
* Documentation controller
*/
class DocsController extends BaseController
{
	protected $layout = 'template';

	/**
	 * Main page for documentation.
	 */
	public function index()
	{
		$data = array();

		$data['sidebar'] = View::make('docs.sidebar');

		$this->layout->pageTitle = 'Documentation';
		$this->layout->content = View::make('docs.index', $data);
	}
}