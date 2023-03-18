<?php

require_once __DIR__.'/../interfaces/pswdRenewInputInterface.php';
require_once __DIR__.'/../interfaces/pswdRenewOutputInterface.php';
require_once __DIR__.'/../data/pswdRenewOutputData.php';
require_once __DIR__.'/../../entities/passwdValidator.php';

class PswdRenewInteractor implements PswdRenewInterface
{
	public static function run($renewdata)
	{
		$status = PswdRenewInteractor::check($renewdata);
		$renewdata->presenter->pswdRenewOutput($status, $renewdata->user_id, $renewdata->output_view);
	}

	public static function check($renewdata)
	{
		if ($renewdata->pswd !== $renewdata->pswd2) {
			return PswdRenewStatus::ConflictPassword;
		}
		if (PasswdValidator::passwd_format($renewdata->pswd) !== 1) {
			return PswdRenewStatus::InvalidPassword;
		}
		if ($renewdata->data_access->changePassword($renewdata->user_id, PasswdValidator::passwd_encrypt($renewdata->pswd)) !== TRUE) {
			return PswdRenewStatus::SystemFailure;
		}
		return PswdRenewStatus::Success;
	}
}
