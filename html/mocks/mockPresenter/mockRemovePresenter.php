<?php

require_once __DIR__.'/../../usecases/interfaces/removeOutputInterface.php';

class MockRemovePresenter implements RemoveOutput
{
	public function removeOutput($status, $output_view)
	{
		switch ($status)
		{
			case RemoveStatus::Success:
				$output_view->create("Success");
				break;	
			case RemoveStatus::Unauthorized:
				$output_view->create("Unauthorized");
				break;
			case RemoveStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
