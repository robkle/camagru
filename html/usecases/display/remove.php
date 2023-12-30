<?php

require_once __DIR__.'/../interfaces/removeInputInterface.php';
require_once __DIR__.'/../interfaces/removeOutputInterface.php';
require_once __DIR__.'/../data/removeOutputData.php';

class RemoveInteractor implements RemoveInterface
{
	public static function run($removeData)
	{
		$status = self::check($removeData);
		$removeData->presenter->removeOutput($status, $removeData->output_view);
	}

	public static function check($removeData)
	{
		if (empty($removeData->user_id)) {
			return RemoveStatus::Unauthorized;
		}
		$dbuser = $removeData->data_access->fetchUser($removeData->user_id, null, null);
		if ($dbuser === [NULL]) {
			return RemoveStatus::SystemFailure;
		}
		if ($dbuser["id"] == null) {
			return RemoveStatus::Unauthorized;
		}
		if ($removeData->data_access->removeImage($removeData->image_id, $removeData->user_id) === FALSE) {
			return RemoveStatus::SystemFailure;
		}
		return RemoveStatus::Success;
	}
}
