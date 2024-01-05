<?php

require_once __DIR__.'/../usecases/login/login.php';

class LoginPresenter implements LoginOutput
{
	public function loginOutput($status, $session_user, $login_view)
	{
		switch ($status)
		{
			case Status::Success:
				$login_view->create("Success", $session_user);
				break;
			case Status::InvalidLogin:
				$login_view->create("InvalidLogin");
				break;
			case Status::InvalidPassword:
				$login_view->create("InvalidPassword");
				break;
			case Status::AccountUnconfirmed:
				$login_view->create("AccountUnconfirmed");
				break;
			case Status::SystemFailure:
				$login_view->create("SystemFailure");
				break;
		}
	}
}
