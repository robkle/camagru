<?php

require_once __DIR__.'/../../usecases/interfaces/pswdRequestOutputInterface.php';

class MockPswdRequestPresenter implements PswdRequestOutput
{
	public function pswdRequestOutput($status, $output_view)
	{
		switch ($status)
		{
			case PswdRequestStatus::Success:
				$output_view->create("Success");
				break;	
			case PswdRequestStatus::InvalidEmail:
				$output_view->create("InvalidEmail");
				break;
			case PswdRequestStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
