<?php

require_once __DIR__.'/../../usecases/display/gallery.php';

class MockGalleryPresenter implements GalleryOutput
{
	public function GalleryOutput($status, $gallery, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success", $gallery);
				break;
			case Status::Unauthorised:
				$output_view->create("Unauthorised");
				break;	
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
