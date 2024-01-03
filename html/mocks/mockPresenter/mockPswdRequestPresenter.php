<?php

require_once __DIR__.'/../../usecases/passwordRequest/request.php';

class MockPswdRequestPresenter implements PswdRequestOutput
{
	public function pswdRequestOutput($status, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success");
				break;	
			case Status::InvalidEmail:
				$output_view->create("InvalidEmail");
				break;
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
