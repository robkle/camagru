<?php

require_once __DIR__.'/../interfaces/pswdResetInputInterface.php';
require_once __DIR__.'/../interfaces/pswdResetOutputInterface.php';
require_once __DIR__.'/../data/pswdResetOutputData.php';
require_once __DIR__.'/../../entities/tokenHandler.php';

class PswdResetInteractor implements PswdResetInterface
{
	public static function run($resetdata)
	{
		$sessionUser = new PswdResetOutputData();
		$status = PswdResetInteractor::check($resetdata, $sessionUser);
		$resetdata->presenter->pswdResetOutput($status, $sessionUser, $resetdata->output_view);
	}

	public static function check($resetdata, &$sessionUser)
	{
		//Check if url query string exists
		if ($resetdata->token == null) {
			return PswdResetStatus::QueryInvalid;
		}
		//fetch token array containing token, timeout and email
		$db_token = $resetdata->data_access->fetchRequestToken($resetdata->token);
		if ($db_token === [NULL]) {
			return PswdResetStatus::SystemFailure; 
		}
		//Check timeout
		if ($db_token['timeout'] < date("U")) {
			return PswdResetStatus::TimeOut;
		}
		//fetch user by email
		$dbUser = $resetdata->data_access->fetchUser(null, null, $db_token['email']);
		if ($dbUser === [NULL]) {
			return PswdResetStatus::SystemFailure;
		}
		//Check if user exists
		if ($dbUser['email'] == NULL) {
			return PswdResetStatus::InvalidEmail;
		} 
		$sessionUser->create($dbUser['id']);
		return PswdResetStatus::Success;
	}
}
