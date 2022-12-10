<?php

require_once __DIR__.'/../interfaces/loginInputInterface.php';
require_once __DIR__.'/../../entities/passwdValidator.php';
require_once __DIR__.'/../data/loginOutputData.php';

class LoginInteractor implements LoginInterface
{
	public static function run($loginData)
	{
		$dbUser = $loginData->data_access->fetchUser($loginData->login, null);
		$sessionUser = [];
		$status = self::check($loginData, $dbUser, $sessionUser);
		$loginData->presenter->loginOutput($status, $sessionUser, $loginData->output_view);
	}

	public static function check($loginData, $dbUser, &$sessionUser)
	{
		if ($dbUser === [NULL]) {
			return LoginStatus::SystemFailure;
		}
		if ($dbUser['login'] !== $loginData->login) {
			return LoginStatus::InvalidLogin;
		}
		if (PasswdValidator::passwd_verify($loginData->pswd, $dbUser['pswd']) !== true) {
			return LoginStatus::InvalidPassword;
		}
		if ($dbUser['confirm'] === "No") {
			return LoginStatus::AccountUnconfirmed;
		}
		$sessionUser = [$dbUser['id'], $dbUser['login']];
		return LoginStatus::Success;
	}
}
