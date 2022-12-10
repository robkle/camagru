<?php

require_once __DIR__.'/../interfaces/confirmInputInterface.php';
require_once __DIR__.'/../interfaces/confirmOutputInterface.php';
require_once __DIR__.'/../data/confirmOutputData.php';


class ConfirmInteractor implements ConfirmUserInteractor
{
	public static function run($userdata)
	{
		$status = self::check($userdata);
		$userdata->presenter->confirmOutput($status, $userdata->output_view);
	}

	public static function check($userdata)
	{
		if ($userdata->ckey == null) {
			return ConfirmStatus::QueryInvalid;
		}
		$db_ckey = $userdata->data_access->fetchCkey($userdata->ckey);
		if ($db_ckey === [NULL]) {
			return ConfirmStatus::SystemFailure;
		}	
		if (isset($db_ckey['ckey']) !== true) {
			return ConfirmStatus::AccountInvalid;
		}
		if ($db_ckey['confirm'] === "Yes") {
			return ConfirmStatus::AccountConfirmed;
		}
		if ($userdata->data_access->confirmUser($userdata->ckey) !== TRUE) {
			return ConfirmStatus::SystemFailure;
		}
		return ConfirmStatus::Success;
	}
}
