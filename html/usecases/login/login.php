<?php

require_once __DIR__.'/../../entities/passwdValidator.php';
require_once __DIR__.'/../../entities/outputStatus.php';

class LoginInputData
{
	public $login;
	public $pswd;
	public $data_access;
	public $output_view;
	public $presenter;

	function __construct($input, $data_access, $output_view, $presenter)
	{
		if (array_key_exists('login', $input)) {
			$this->login = $input['login'];
		}
		if (array_key_exists('pswd', $input)) {
			$this->pswd = $input['pswd'];
		}
		$this->data_access = $data_access;
		$this->output_view = $output_view;
		$this->presenter = $presenter;
	}
}

interface LoginInterface
{
	public static function run(LoginInputData $logindata);
	public static function check($loginData, $dbUser, &$sessionUser);
}

class LoginOutputData
{
	public $userid;
	public $username;

	function create($userid, $username)
	{
		$this->userid = $userid;
		$this->username = $username;
	}
}

interface LoginOutput
{
	public function loginOutput(LoginStatus $status, LoginOutputData $session_user, LoginViewModel $login_view);
}

class LoginInteractor implements LoginInterface
{
	public static function run($loginData)
	{
		$dbUser = $loginData->data_access->fetchUser(null, $loginData->login, null);
		$sessionUser = new LoginOutputData();
		$status = self::check($loginData, $dbUser, $sessionUser);
		$loginData->presenter->loginOutput($status, $sessionUser, $loginData->output_view);
	}

	public static function check($loginData, $dbUser, &$sessionUser)
	{
		if ($dbUser === [NULL]) {
			return Status::SystemFailure;
		}
		if ($dbUser['login'] !== $loginData->login) {
			return Status::InvalidLogin;
		}
		if (PasswdValidator::passwd_verify($loginData->pswd, $dbUser['pswd']) !== true) {
			return Status::InvalidPassword;
		}
		if ($dbUser['confirm'] === "No") {
			return Status::AccountUnconfirmed;
		}
		$sessionUser->create($dbUser['id'], $dbUser['login']);
		return Status::Success;
	}
}
