<?php

require_once __DIR__.'/../../usecases/interfaces/modifyUsernameOutputInterface.php';

class MockModifyUsernamePresenter implements modifyUsernameOutput
{
	public function modifyUsernameOutput($status, $session_user, $output_view)
	{
		switch ($status)
		{
			case ModifyUsernameStatus::Success:
				$output_view->create("Success");
				break;	
			case ModifyUsernameStatus::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case ModifyUsernameStatus::InvalidUsername:
				$output_view->create("InvalidUsername");
				break;
			case ModifyUsernameStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
