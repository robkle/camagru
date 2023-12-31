<?php

require_once __DIR__.'/../../usecases/passwordRequest/reset.php';

class MockPswdResetPresenter implements PswdResetOutput
{
	public function pswdResetOutput($status, $session_user, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success");
				break;	
			case Status::QueryInvalid:
				$output_view->create("QueryInvalid");
				break;
			case Status::TimeOut:
				$output_view->create("TimeOut");
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
