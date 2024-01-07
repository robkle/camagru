<?php

require_once __DIR__.'/../usecases/users/modifyUsername.php';

class ModifyUsernamePresenter implements modifyUsernameOutput
{
	public function modifyUsernameOutput($status, $session_user, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success", $session_user);
				break;	
			case Status::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case Status::InvalidPassword:
				$output_view->create("InvalidPassword");
				break;
			case Status::InvalidUsername:
				$output_view->create("InvalidUsername");
				break;
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
