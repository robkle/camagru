<?php

require_once __DIR__.'/../../usecases/passwordRequest/renew.php';

class MockPswdRenewPresenter implements PswdRenewOutput
{
	public function pswdRenewOutput($status, $user_id, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success");
				break;	
			case Status::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case Status::InvalidPassword:
				$output_view->create("InvalidPassword");
				break;
			case Status::ConflictPassword:
				$output_view->create("ConflictPassword");
				break;
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
