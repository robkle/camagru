<?php

require_once __DIR__.'/../interfaces/userInputInterface.php';
require_once __DIR__.'/../interfaces/userOutputInterface.php';
#require_once __DIR__.'/../../dataAccess/mockDataAccess.php';
#require_once __DIR__.'/../../presenter/mockSignupPresenter.php';
require_once __DIR__.'/../data/userOutputData.php';
require_once __DIR__.'/../../entities/usernameValidator.php';
require_once __DIR__.'/../../entities/passwdValidator.php';
require_once __DIR__.'/../../entities/ckeyGenerator.php';
#require_once __DIR__.'/../../messageHandler/messageHandler.php';


class SignupInteractor implements UserInteractor
{
	public static function run ($userdata)
	{
		if (UsernameValidator::username_format($userdata->login) == 1) {
			if (filter_var($userdata->email, FILTER_VALIDATE_EMAIL)) {
				if ($userdata->pswd === $userdata->pswd2) {
					if (PasswdValidator::passwd_format($userdata->pswd) == 1) {
						$db_user = $userdata->data_access->fetchUser($userdata->login, $userdata->email);
						if ($db_user != [NULL]) {
							if ($db_user['login'] === null) {
								if ($db_user['email'] === null) {
									$ckey = Ckey::create($userdata->login);
									if ($userdata->data_access->postUser($userdata->login, $userdata->email, PasswdValidator::passwd_encrypt($userdata->pswd), $ckey) == TRUE) {
										$info = new SignupOutputData($userdata->login, $userdata->email, $ckey);
										if ($userdata->message_handler->signupEmail($info) === TRUE) {
											$status = SignupStatus::Success;
										} else {
											$status = SignupStatus::SystemFailure;
										}
									} else {	
										$status = SignupStatus::SystemFailure;
									}
								} else {
									$status = SignupStatus::ExistingEmail;
								}
							} else {
								$status = SignupStatus::ExistingLogin;
							}						
						} else {
							$status = SignupStatus::SystemFailure;
						}
					} else {
						$status = SignupStatus::InvalidPassword;
					}
				} else {
					$status = SignupStatus::ConflictPassword;
				}
			} else {
				$status = SignupStatus::InvalidEmail;
			}		
		} else {
			$status = SignupStatus::InvalidLogin;
		}
		$userdata->presenter->signupOutput($status, $userdata->output_view);
	}
}
