<?php

/**
* 
*/
class BaseController extends Controller
{
	public function setupLayout()
	{
		// If a layout was set by the controller use it.
		if (! is_null($this->layout)) {
			
			$this->layout = View::make($this->layout);
		}
	}
}