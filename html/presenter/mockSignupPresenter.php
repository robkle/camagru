<?php

require_once __DIR__.'/../usecases/interfaces/userOutputInterface.php';

class MockSignupPresenter implements UserOutput
{
	public function signupOutput($status, $signup_view)
	{
		switch ($status)
		{
			case SignupStatus::Success:
				$signup_view->create("Success");
				break;	
			case SignupStatus::InvalidLogin:
				$signup_view->create("InvalidLogin");
				break;	
			case SignupStatus::ExistingLogin:
				$signup_view->create("ExistingLogin");
				break;
			case SignupStatus::InvalidEmail:
				$signup_view->create("InvalidEmail");
				break;
			case SignupStatus::ExistingEmail:
				$signup_view->create("ExistingEmail");
				break;
			case SignupStatus::InvalidPassword:
				$signup_view->create("InvalidPassword");
				break;
			case SignupStatus::ConflictPassword:
				$signup_view->create("ConflictPassword");
				break;
			case SignupStatus::SystemFailure:
				$signup_view->create("SystemFailure");
				break;
		}
	}
}
