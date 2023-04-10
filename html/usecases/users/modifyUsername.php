<?php

require_once __DIR__.'/../interfaces/modifyUsernameInputInterface.php';
require_once __DIR__.'/../interfaces/modifyUsernameOutputInterface.php';
require_once __DIR__.'/../data/modifyUsernameOutputData.php';
require_once __DIR__.'/../../entities/usernameValidator.php';

class ModifyUsernameInteractor implements ModifyUsernameInterface
{
	public static function run($modifydata)
	{
		$sessionUser = new ModifyUsernameOutputData();
		$status = ModifyUsernameInteractor::check($modifydata, $sessionUser);
		$modifydata->presenter->modifyUsernameOutput($status, $sessionUser, $modifydata->output_view);
	}

	public static function check($modifydata, &$sessionUser)
	{
		if (strlen($modifydata->user_id) == 0) {
			return ModifyUsernameStatus::Unauthorised;
		}
		if (UsernameValidator::username_format($modifydata->username) !== 1) {
			return ModifyUsernameStatus::InvalidUsername;
		}
		$dbUser = $modifydata->data_access->fetchUser($modifydata->user_id, null, null);
		if ($dbUser === [NULL]) {
			return ModifyUsernameStatus::SystemFailure;
		}
		if ($dbUser['id'] === null) {
			return ModifyUsernameStatus::Unauthorised;
		}
		if ($modifydata->data_access->changeUsername($modifydata->user_id, $modifydata->username)!== TRUE) {
			return ModifyUsernameStatus::SystemFailure;
		}
		$sessionUser->create($modifydata->user_id, $modifydata->username);		
		return ModifyUsernameStatus::Success;
	}
}
