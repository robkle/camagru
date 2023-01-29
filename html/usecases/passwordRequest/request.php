<?php

require_once __DIR__.'/../interfaces/pswdRequestInputInterface.php';
require_once __DIR__.'/../interfaces/pswdRequestOutputInterface.php';
require_once __DIR__.'/../data/pswdRequestOutputData.php';
require_once __DIR__.'/../../entities/tokenHandler.php';

class PswdRequestInteractor implements PswdRequestInterface
{
	public static function run($requestdata)
	{
		$status = PswdRequestInteractor::check($requestdata);		
		$requestdata->presenter->pswdRequestOutput($status, $requestdata->output_view);
	}

	public static function check($requestdata)
	{
		$db_user = $requestdata->data_access->fetchUser(null, null, $requestdata->email);
		if ($db_user === [NULL]) {
			return PswdRequestStatus::SystemFailure;
		}
		if ($db_user['email'] === null) {
			return PswdRequestStatus::InvalidEmail;
		}
		if ($requestdata->data_access->deleteRequestToken($db_user['email']) !== true)
		{
			return PswdRequestStatus::SystemFailure;
		}
		$token = Tokens::createToken();
		if ($requestdata->data_access->postRequestToken($db_user['email'], $token, date("U") + 1800) !== true)
		{
			return PswdRequestStatus::SystemFailure;
		}
		$info = new PswdRequestOutputData($requestdata->email, bin2hex($token));
		if ($requestdata->message_handler->pswdRequestEmail($info) !== true) {
			return PswdRequestStatus::SystemFailure;
		}
		return PswdRequestStatus::Success;
	}
}
