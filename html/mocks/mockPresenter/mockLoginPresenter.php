<?php

require_once __DIR__.'/../../usecases/interfaces/loginOutputInterface.php';

class mockLoginPresenter implements LoginOutput
{
	public function loginOutput($status, $session_user, $login_view)
	{
		switch ($status)
		{
			case LoginStatus::Success:
				$login_view->create("Success");
				break;
			case LoginStatus::InvalidLogin:
				$login_view->create("InvalidLogin");
				break;
			case LoginStatus::InvalidPassword:
				$login_view->create("InvalidPassword");
				break;
			case LoginStatus::AccountUnconfirmed:
				$login_view->create("AccountUnconfirmed");
				break;
			case LoginStatus::SystemFailure:
				$login_view->create("SystemFailure");
				break;
		}
	}
}
