<?php

enum GalleryStatus
{
	case Success;
	case Unauthorized;
	case SystemFailure;
}

class GalleryOutputData
{
	public $gallery = array();

	function __construct($gallery)
	{
		$this->gallery = $gallery;
	}
}

