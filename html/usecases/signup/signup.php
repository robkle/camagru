<?php

require_once __DIR__.'/../interfaces/userInputInterface.php';
require_once __DIR__.'/../interfaces/userOutputInterface.php';
require_once __DIR__.'/../data/userOutputData.php';
require_once __DIR__.'/../../entities/usernameValidator.php';
require_once __DIR__.'/../../entities/passwdValidator.php';
require_once __DIR__.'/../../entities/tokenHandler.php';

class SignupInteractor implements UserInteractor
{
	public static function run($userdata)
	{
		$status = self::check($userdata);
		$userdata->presenter->signupOutput($status, $userdata->output_view);
	}

	public static function check($userdata)
	{
		if (UsernameValidator::username_format($userdata->login) !== 1) {
			return SignupStatus::InvalidLogin;
		}
		if (filter_var($userdata->email, FILTER_VALIDATE_EMAIL) === false) {
			return SignupStatus::InvalidEmail;
		}
		if ($userdata->pswd !== $userdata->pswd2) {
			return SignupStatus::ConflictPassword;
		}
		if (PasswdValidator::passwd_format($userdata->pswd) !== 1) {
			return SignupStatus::InvalidPassword;
		}
		$db_user = $userdata->data_access->fetchUser(null, $userdata->login, $userdata->email);
		if ($db_user === [NULL]) {
			return SignupStatus::SystemFailure;
		}
		if ($db_user['login'] === $userdata->login) {
			return SignupStatus::ExistingLogin;
		}
		if ($db_user['email'] === $userdata->email) {
			return SignupStatus::ExistingEmail;
		}
		$ckey = Tokens::createCkey($userdata->login);
		if ($userdata->data_access->postUser($userdata->login, $userdata->email, PasswdValidator::passwd_encrypt($userdata->pswd), $ckey) !== TRUE) {
			return SignupStatus::SystemFailure;
		}
		$info = new SignupOutputData($userdata->login, $userdata->email, $ckey);
		if ($userdata->message_handler->signupEmail($info) !== TRUE) {
			return SignupStatus::SystemFailure;
		}
		return SignupStatus::Success;
	}
}
