<?php

require_once __DIR__.'/../interfaces/loginInputInterface.php';
require_once __DIR__.'/../../entities/passwdValidator.php';
require_once __DIR__.'/../data/loginOutputData.php';

class LoginInteractor implements LoginInterface
{
	public static function run ($loginData)
	{
		$sessionUser = [];
		$dbUser = $loginData->data_access->fetchUser($loginData->login, null);
		if ($dbUser != [NULL]) {
			if ($dbUser['login'] == $loginData->login) {
				if (PasswdValidator::passwd_verify($loginData->pswd, $dbUser['pswd'])) {
					if ($dbUser['confirm'] !== "No") {
						$status = LoginStatus::Success;
						$sessionUser = [$dbUser['id'], $dbUser['login']];
					} else {
						$status = LoginStatus::AccountUnconfirmed;
					}
				} else {
					$status = LoginStatus::InvalidPassword;
				}
			} else {
				$status = LoginStatus::InvalidLogin;
			}
		} else {
			$status = LoginStatus::SystemFailure;
		}
		$loginData->presenter->loginOutput($status, $sessionUser, $loginData->output_view);
	}
}
