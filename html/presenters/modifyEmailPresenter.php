<?php

require_once __DIR__.'/../usecases/users/modifyEmail.php';

class ModifyEmailPresenter implements modifyEmailOutput
{
	public function modifyEmailOutput($status, $output_view)
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
			case Status::InvalidEmail:
				$output_view->create("InvalidEmail");
				break;
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
