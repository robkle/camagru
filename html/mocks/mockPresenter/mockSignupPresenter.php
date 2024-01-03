<?php

require_once __DIR__.'/../../usecases/users/signup.php';

class MockSignupPresenter implements UserOutput
{
	public function signupOutput($status, $signup_view)
	{
		switch ($status)
		{
			case Status::Success:
				$signup_view->create("Success");
				break;	
			case Status::InvalidLogin:
				$signup_view->create("InvalidLogin");
				break;	
			case Status::ExistingLogin:
				$signup_view->create("ExistingLogin");
				break;
			case Status::InvalidEmail:
				$signup_view->create("InvalidEmail");
				break;
			case Status::ExistingEmail:
				$signup_view->create("ExistingEmail");
				break;
			case Status::InvalidPassword:
				$signup_view->create("InvalidPassword");
				break;
			case Status::ConflictPassword:
				$signup_view->create("ConflictPassword");
				break;
			case Status::SystemFailure:
				$signup_view->create("SystemFailure");
				break;
		}
	}
}
