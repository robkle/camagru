<?php

require_once __DIR__.'/../../usecases/interfaces/changeNotificationsOutputInterface.php';

class MockChangeNotificationsPresenter implements changeNotificationsOutput
{
	public function changeNotificationsOutput($status, $output_view)
	{
		switch ($status)
		{
			case ChangeNotificationsStatus::Success:
				$output_view->create("Success");
				break;	
			case ChangeNotificationsStatus::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case ChangeNotificationsStatus::InvalidOption:
				$output_view->create("InvalidOption");
				break;
			case ChangeNotificationsStatus::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
