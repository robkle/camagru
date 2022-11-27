<?php

require_once __DIR__.'/../interfaces/confirmInputInterface.php';
require_once __DIR__.'/../interfaces/confirmOutputInterface.php';
#require_once __DIR__.'/../../dataAccess/mockDataAccess.php';
#require_once __DIR__.'/../../presenter/mockConfirmPresenter.php';
require_once __DIR__.'/../data/confirmOutputData.php';


class ConfirmInteractor implements ConfirmUserInteractor
{
	public static function run ($userdata)
	{
		//check if ckey is not null
		if (isset($userdata->ckey) !== FALSE) {
			//fetch ckey from db
			$db_ckey = $userdata->data_access->fetchCkey($userdata->ckey);
			if ($db_ckey !== [NULL]) {	
				//check that it exists
				if (isset($db_ckey['ckey']) !== FALSE) {
					//check that confirm is No
					if ($db_ckey['confirm'] !== "Yes") {
					//update db with YES
						if ($userdata->data_access->confirmUser($userdata->ckey) === TRUE) {
							$status = ConfirmStatus::Success;
						} else {
							$status = ConfirmStatus::SystemFailure;
						}
					} else {
					$status = ConfirmStatus::AccountConfirmed;
					}
				} else {
					$status = ConfirmStatus::AccountInvalid;
				}
			} else {
				$status = ConfirmStatus::SystemFailure;
			}
		} else {
			$status = ConfirmStatus::QueryInvalid;
		}
		$userdata->presenter->confirmOutput($status, $userdata->output_view);
	}
}
