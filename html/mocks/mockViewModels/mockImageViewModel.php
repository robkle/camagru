<?php

class MockImageViewModel
{
	public $err_msg;
	public $image;
	public $comments;

	function create($err_msg, $imageData = null)
	{
		$this->err_msg = $err_msg;
		if (isset($imageData)){
			$this->image = $imageData->image;
			$this->comments = $imageData->comments;
		}
	}
}
