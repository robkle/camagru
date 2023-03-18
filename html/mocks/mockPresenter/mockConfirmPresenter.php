<?php

require_once __DIR__.'/../../usecases/interfaces/confirmOutputInterface.php';

class MockConfirmPresenter implements ConfirmOutput
{
	public static function confirmOutput($status, $output_view)
	{
		switch ($status)
		{
			case ConfirmStatus::Success:
				$output_view->create("Success");
				break;	
			case ConfirmStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;	
			case ConfirmStatus::AccountConfirmed:
				$output_view->create("AccountConfirmed");
				break;
			case ConfirmStatus::AccountInvalid:
				$output_view->create("AccountInvalid");
				break;
			case ConfirmStatus::QueryInvalid:
				$output_view->create("QueryInvalid");
				break;
		}
	}
}
