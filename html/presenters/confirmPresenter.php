<?php

require_once __DIR__.'/../usecases/users/confirm.php';

class ConfirmPresenter implements ConfirmOutput
{
	public static function confirmOutput($status, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success");
				break;	
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;	
			case Status::AccountConfirmed:
				$output_view->create("AccountConfirmed");
				break;
			case Status::AccountInvalid:
				$output_view->create("AccountInvalid");
				break;
			case Status::QueryInvalid:
				$output_view->create("QueryInvalid");
				break;
		}
	}
}
