<?php

require_once __DIR__.'/../../usecases/display/image.php';

class MockImagePresenter implements ImageOutput
{
	public function ImageOutput($status, $image, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success", $image);
				break;
			case Status::NonExistent:
				$output_view->create("NonExistent");
				break;	
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
