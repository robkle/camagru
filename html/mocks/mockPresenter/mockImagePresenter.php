<?php

require_once __DIR__.'/../../usecases/interfaces/imageOutputInterface.php';

class MockImagePresenter implements ImageOutput
{
	public function ImageOutput($status, $image, $output_view)
	{
		switch ($status)
		{
			case ImageStatus::Success:
				$output_view->create("Success");
				break;
			case ImageStatus::NonExistent:
				$output_view->create("NonExistent");
				break;	
			case ImageStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
