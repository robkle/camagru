<?php

require_once __DIR__.'/../../usecases/users/changeNotifications.php';

class MockChangeNotificationsPresenter implements changeNotificationsOutput
{
	public function changeNotificationsOutput($status, $output_view)
	{
		switch ($status)
		{
			case Status::Success:
				$output_view->create("Success");
				break;	
			case Status::Unauthorised:
				$output_view->create("Unauthorised");
				break;
			case Status::InvalidOption:
				$output_view->create("InvalidOption");
				break;
			case Status::SystemFailure:
				$output_view->create("SystemFailure");
				break;
		}
	}
}
