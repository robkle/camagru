<?php

require_once __DIR__.'/../interfaces/modifyEmailInputInterface.php';
require_once __DIR__.'/../interfaces/modifyEmailOutputInterface.php';
require_once __DIR__.'/../data/modifyEmailOutputData.php';

class ModifyEmailInteractor implements ModifyEmailInterface
{
	public static function run($modifydata)
	{
		$status = ModifyEmailInteractor::check($modifydata);
		$modifydata->presenter->modifyEmailOutput($status, $modifydata->output_view);
	}

	public static function check($modifydata)
	{
		if (strlen($modifydata->user_id) == 0) {
			return ModifyEmailStatus::Unauthorised;
		}
		if (filter_var($modifydata->email, FILTER_VALIDATE_EMAIL) === false) {
			return ModifyEmailStatus::InvalidEmail;
		}
		$dbUser = $modifydata->data_access->fetchUser($modifydata->user_id, null, null);
		if ($dbUser === [NULL]) {
			return ModifyEmailStatus::SystemFailure;
		}
		if ($dbUser['id'] === null) {
			return ModifyEmailStatus::Unauthorised;
		}
		if ($modifydata->data_access->changeEmail($modifydata->user_id, $modifydata->email)!== TRUE) {
			return ModifyEmailStatus::SystemFailure;
		}
		return ModifyEmailStatus::Success;
	}
}
