<?php

class MockGalleryViewModel
{
	public $err_msg;
	public $gallery;

	function create($err_msg, $galleryData = null)
	{
		$this->err_msg = $err_msg;
		if (isset($galleryData)) {
			$this->gallery = $galleryData->gallery;
		}
	}
}
