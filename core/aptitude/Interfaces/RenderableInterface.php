<?php namespace Aptitude\Interfaces;

interface RenderableInterface
{
	/**
	 * Get the rendered contents of the object.
	 *
	 * @return string
	 */
	public function render();
}