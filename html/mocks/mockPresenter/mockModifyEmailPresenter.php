<?php

require_once __DIR__.'/../../usecases/interfaces/modifyEmailOutputInterface.php';

class MockModifyEmailPresenter implements modifyEmailOutput
{
	public function modifyEmailOutput($status, $output_view)
	{
		switch ($status)
		{
			case ModifyEmailStatus::Success:
				$output_view->create("Success");
				break;	
			case ModifyEmailStatus::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case ModifyEmailStatus::InvalidEmail:
				$output_view->create("InvalidEmail");
				break;
			case ModifyEmailStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
