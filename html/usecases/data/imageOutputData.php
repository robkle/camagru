<?php

enum ImageStatus
{
	case Success;
	case NonExistent;
	case SystemFailure;
}

class ImageOutputData
{
	public $image = array();
	public $comments = array();

	function __construct($image, $comments)
	{
		$this->image = $image;
		$this->comments = $comments;
	}
}

