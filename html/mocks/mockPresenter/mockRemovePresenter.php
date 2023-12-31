<?php

require_once __DIR__.'/../../usecases/display/remove.php';

class MockRemovePresenter implements RemoveOutput
{
	public function removeOutput($status, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success");
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
