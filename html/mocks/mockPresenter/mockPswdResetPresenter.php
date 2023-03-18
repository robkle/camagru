<?php

require_once __DIR__.'/../../usecases/interfaces/pswdResetOutputInterface.php';

class MockPswdResetPresenter implements PswdResetOutput
{
	public function pswdResetOutput($status, $session_user, $output_view)
	{
		switch ($status)
		{
			case PswdResetStatus::Success:
				$output_view->create("Success");
				break;	
			case PswdResetStatus::QueryInvalid:
				$output_view->create("QueryInvalid");
				break;
			case PswdResetStatus::TimeOut:
				$output_view->create("TimeOut");
				break;	
			case PswdResetStatus::InvalidEmail:
				$output_view->create("InvalidEmail");
				break;	
			case PswdResetStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;	
		}
	}
}
