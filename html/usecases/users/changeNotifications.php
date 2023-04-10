<?php

require_once __DIR__.'/../interfaces/changeNotificationsInputInterface.php';
require_once __DIR__.'/../interfaces/changeNotificationsOutputInterface.php';
require_once __DIR__.'/../data/changeNotificationsOutputData.php';

class ChangeNotificationsInteractor implements ChangeNotificationsInterface
{
	public static function run($changeData)
	{
		$status = ChangeNotificationsInteractor::check($changeData);
		$changeData->presenter->changeNotificationsOutput($status, $changeData->output_view);
	}

	public static function check($changeData)
	{
		if (strlen($changeData->user_id) == 0) {
			return ChangeNotificationsStatus::Unauthorised;
		}
		if (in_array($changeData->notifications, ["On", "Off", "Comments", "Likes"]) === false) {
			return ChangeNotificationsStatus::InvalidOption;
		}
		$dbUser = $changeData->data_access->fetchUser($changeData->user_id, null, null);
		if ($dbUser === [NULL]) {
			return ChangeNotificationsStatus::SystemFailure;
		}
		if ($dbUser['id'] === null) {
			return ChangeNotificationsStatus::Unauthorised;
		}
		if ($changeData->data_access->changeNotifications($changeData->user_id, $changeData->notifications)!== TRUE) {
			return ChangeNotificationsStatus::SystemFailure;
		}
		return ChangeNotificationsStatus::Success;
	}
}
