<?php

require_once __DIR__.'/../../usecases/interfaces/galleryOutputInterface.php';

class MockGalleryPresenter implements GalleryOutput
{
	public function GalleryOutput($status, $gallery, $output_view)
	{
		switch ($status)
		{
			case GalleryStatus::Success:
				$output_view->create("Success");
				break;
			case GalleryStatus::Unauthorized:
				$output_view->create("Unauthorized");
				break;	
			case GalleryStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
