<?php

/**
* Article model.
*/
class Article
{
	/**
	 * Get all articles.
	 *
	 * @param  number  $limit 		Limit number of articles to query for.
	 * @param  boolean $published 	Show published or not published articles.
	 * @return array 				Array of articles.
	 */
	public function all($limit = 20, $published = true)
	{
		// Set published to value of 1 or 0 instead of boolean true/false.
		$published = $published ? 1 : 0;

		// Make sure thr limit is an integer.
		$limit = (int) $limit;

		// Initialize articles query.
		$query = DB::table('articles', 'art')
			->select('art.id', 'art.author', 'art.title', 'art.body', 'art.created_at', 'users.username')
			->join('users', 'art.author', '=', 'users.id')
			->where('art.published', $published)
			->limit($limit);

		// Run the articles query.
		$articles = $query->get();

		return $articles;
	}
}