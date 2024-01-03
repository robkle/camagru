<?php

require_once __DIR__.'/../../entities/outputStatus.php';

class ChangeNotificationsInputData
{
	public $user_id;
	public $notifications;
	public $data_access;
	public $output_view;
	public $presenter;
	

	function __construct($user_id, $input, $data_access, $output_view, $presenter)
	{
		if ($input && array_key_exists('notifications', $input))
		{
			$this->notifications = $input['notifications'];
		}
		$this->user_id = $user_id;
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface ChangeNotificationsInterface
{
	public static function run(ChangeNotificationsInputData $modifydata);
	public static function check(ChangeNotificationsInputData $modifydata);
}

interface changeNotificationsOutput
{
	public function changeNotificationsOutput(ChangeNotificationsStatus $status, ChangeNotificationsViewModel $output_view);
}

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
			return Status::Unauthorised;
		}
		if (in_array($changeData->notifications, ["On", "Off", "Comments", "Likes"]) === false) {
			return Status::InvalidOption;
		}
		$dbUser = $changeData->data_access->fetchUser($changeData->user_id, null, null);
		if ($dbUser === []) {
			return Status::SystemFailure;
		}
		if ($dbUser['id'] === null) {
			return Status::Unauthorised;
		}
		if ($changeData->data_access->changeNotifications($changeData->user_id, $changeData->notifications)!== TRUE) {
			return Status::SystemFailure;
		}
		return Status::Success;
	}
}
