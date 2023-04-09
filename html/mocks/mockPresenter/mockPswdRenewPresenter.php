<?php

require_once __DIR__.'/../../usecases/interfaces/pswdRenewOutputInterface.php';

class MockPswdRenewPresenter implements PswdRenewOutput
{
	public function pswdRenewOutput($status, $user_id, $output_view)
	{
		switch ($status)
		{
			case PswdRenewStatus::Success:
				$output_view->create("Success");
				break;	
			case PswdRenewStatus::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case PswdRenewStatus::InvalidPassword:
				$output_view->create("InvalidPassword");
				break;
			case PswdRenewStatus::ConflictPassword:
				$output_view->create("ConflictPassword");
				break;
			case PswdRenewStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
